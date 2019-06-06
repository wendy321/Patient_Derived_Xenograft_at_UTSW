<?php
// Avoid CSRF attack:
// (1) (Access-Control-Allow-Origin: $_SERVER['HTTP_ORIGIN']), not (Access-Control-Allow-Origin: *)
//      => Avoid request from all other domains, allow only origin domain. Meet SOP (Same Origin Policy).
// (2) (Access-Control-Allow-Headers: X-Requested-With: XMLHttpRequest)
//      => This header can't be added to a cross domain XHR request without the consent of server via CORS (Cross-Origin Resource Sharing).
//    header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

include "./dbpdx.inc";
require_once "./php_class/dbencryt.inc";
require_once "./php_class/EscapeString.inc";

/****************************************************
Clean Input Data
 *****************************************************/
// Method 1 (if use "sentObj" or "JSON.stringify(sentObj)" in axios XMLHTTPRequest in discoveryData.js)
$filters = json_decode(file_get_contents("php://input"), true);
//    ex: array ( 0 => {value_name: "Hispanic", table: "Patient", variable: "Ethnicity", value: "Hispanic", id: 23} )


$search_list=null;
if(is_array($filters)){
    foreach($filters as $select) {
        $p_table = EscapeString::escape($select["table"]);
        $p_variable = EscapeString::escape($select["variable"]);
        $p_value = EscapeString::escape($select["value"]);
        $search_list[$p_table][$p_variable][]=$p_value;
    }
}

/****************************************************
Connect to MySQL database
 *****************************************************/
$connection = new mysqli(Encryption::decrypt($hostname), Encryption::decrypt($username), Encryption::decrypt($password),
    Encryption::decrypt($olddbname));
if ($connection->connect_errno) {
    echo "Could not connect pdx:" . $connection->connect_error. "\n";
}

/****************************************************
Get Selected Patient ID and Patient Number
 *****************************************************/
// Get Selected Patient Info function
function getSelectedPatient($database,$connection,$search_list){
    $PID= array();
    $sql="select distinct PatientID from Patient";
    $result = $connection->query($sql);
    while($row = $result->fetch_assoc())
        $PID[]="'".$row['PatientID']."'";
    sort($PID);
    $patient_number=sizeof($PID);
    $in = implode(',', $PID);
    $isbreak=false;
    if($patient_number!==0 && !is_null($search_list)){
        foreach($search_list as $table => $table_value ) {
            if(strcmp($table,'Patient')) continue;
            foreach ($table_value as $variable => $variable_value) {
                $value_set="";
                foreach ($variable_value as $value) {
                    if($value !== "NULL" || !strpos($value_set, "NULL")) {
                        if($value_set !== "") $value_set.=",";
                        $value_set.=$value;
                    }
                }

                $value_set=explode(",",$value_set);
                $null_clause="";
                $range_clause="";
                $individual_clause="";
                foreach ($value_set as $item){
                    if($item==="NULL" && $null_clause===""){
                        if(!strcmp($variable,"Age_Months"))
                            $null_clause.="(" . $variable. "is NULL)";
                        else
                            $null_clause.="(" . $variable. "=\"\")";
                    }elseif(strpos($item,"-")){
                        $ranges=explode("-",$item);
                        if($range_clause!=="") $range_clause.=" or ";
                        $range_clause.="(" . $variable . " >= " . $ranges[0] . " and " . $variable . " < " . $ranges[1] . ")";
                    }else{
                        if($individual_clause!=="") $individual_clause.=",";
                        $individual_clause.="\"".$item."\"";
                    }
                }
                if($individual_clause!=="") $individual_clause=" ".$variable." in (".$individual_clause.") ";

                $where_clause=$null_clause;
                if($where_clause!=="" && $range_clause!=="") $where_clause.=" or ".$range_clause;
                else $where_clause.=$range_clause;

                if($where_clause!=="" && $individual_clause!=="") $where_clause.=" or ".$individual_clause;
                else $where_clause.=$individual_clause;

                $sql = "select distinct PatientID from " . $table . " where PatientID in (" . $in . ")";
                if($where_clause!=="") $sql.=" and (".$where_clause.")";

                $PID_Select = array();
                $result = $connection->query($sql);
                while($row = $result->fetch_assoc()) {
                    $PID_Select[]= "'".$row['PatientID']."'";
                }
                $result->close();

                $PID_Select=array_unique($PID_Select);
                sort($PID_Select);
                $patient_number=sizeof($PID_Select);
                $in = implode(',',$PID_Select);

                if($patient_number === 0) {
                    $isbreak=true;
                    break;
                }
            }
            if($isbreak) break;
        }
    }
    return $patient_number."|".$in."|".$sql;
}

// Get Selected Patient Number and Patient IDs
$patient_pdx=getSelectedPatient("PDX_v3",$connection,$search_list);

/****************************************************
Get Selected Sample ID and Sample Number
 *****************************************************/
function getSelectedSample($database,$connection,$search_list){
    $SID= array();
    $sql="select distinct SampleID from Sample";
    $result = $connection->query($sql);
    while($row = $result->fetch_assoc()) $SID[]="'".$row['SampleID']."'";
    $sample_number=sizeof($SID);
    $in = implode(',', $SID);
    $isbreak=false;
    if($sample_number && !is_null($search_list)){
        foreach($search_list as $table => $table_value ) {
            if(strcmp($table,'Sample')) continue;
            foreach ($table_value as $variable => $variable_value) {
                $value_set="";
                foreach ($variable_value as $value) {
                    if($value !== "NULL" || !strpos($value_set, "NULL")) {
                        if($value_set !== "") $value_set.=",";
                        $value_set.=$value;
                    }
                }

                $value_set=explode(",",$value_set);
                $null_clause="";
                $range_clause="";
                $individual_clause="";
                foreach ($value_set as $item){
                    if($item==="NULL" && $null_clause===""){
                        $null_clause.="(" . $variable. "=\"\")";
                    }elseif(strpos($item,"-")){
                        $ranges=explode("-",$item);
                        if($range_clause!=="") $range_clause.=" or ";
                        $range_clause.="(" . $variable . " >= " . $ranges[0] . " and " . $variable . " < " . $ranges[1] . ")";
                    }else{
                        if($individual_clause!=="") $individual_clause.=",";
                        $individual_clause.="\"".$item."\"";
                    }
                }
                if($individual_clause!=="") $individual_clause=" ".$variable." in (".$individual_clause.") ";

                $where_clause=$null_clause;
                if($where_clause!=="" && $range_clause!=="") $where_clause.=" or ".$range_clause;
                else $where_clause.=$range_clause;

                if($where_clause!=="" && $individual_clause!=="") $where_clause.=" or ".$individual_clause;
                else $where_clause.=$individual_clause;

                $sql = "select distinct SampleID from Sample where SampleID in (" . $in . ")";
                if($where_clause!=="") $sql.=" and (".$where_clause.")";

                $SID_Select = array();
                $result = $connection->query($sql);
                while($row = $result->fetch_assoc()) {
                    $SID_Select[]= "'".$row['SampleID']."'";
                }
                $result->close();

                $SID_Select=array_unique($SID_Select);
                $sample_number=sizeof($SID_Select);
                $in = implode(',',$SID_Select);

                if(!$sample_number) {
                    $isbreak=true;
                    break;
                }

//                if($isbreak) break;
            }
            if($isbreak) break;
        }
    }
    return $sample_number."|".$in."|".$sql;
}
$sample_pdx=getSelectedSample("PDX_v3",$connection,$search_list);

/****************************************************
Get Patient Number and Sample Number for drawing Pie Chart
 *****************************************************/
// PDX selected patient number and ids
$patients_pdx=explode('|',$patient_pdx);
$patient_number_pdx=(int)$patients_pdx[0];
$patient_in_pdx=$patients_pdx[1];
$patient_sql_pdx=$patients_pdx[2];

// PDX selected sample number and ids
$samples_pdx=explode('|',$sample_pdx);
$sample_number_pdx=(int)$samples_pdx[0];
$sample_in_pdx=$samples_pdx[1];
$sample_sql_pdx=$samples_pdx[2];

// Get Sex Info function
function getGenderInfo($database,$connection,$in){
    $return_result=array(
        'female'=>0,
        'male'=>0,
        'unknown'=>0
    );

    if(!strcmp($in,''))
        return $return_result;

    $table='Patient';
    $variable='Gender';
    $primaryid='PatientID';
    $sql = "select (select count(distinct ".$primaryid.") from ".$table." where ".$variable."=\"f\" and ".$primaryid." in (" . $in . ")) as female,
        (select count(distinct ".$primaryid.") from ".$table." where ".$variable."=\"m\" and ".$primaryid." in (" . $in . ")) as male,
        (select count(distinct ".$primaryid.") from ".$table." where ".$variable."=\"\" and ".$primaryid." in (" . $in . ")) as unknown";
    $result = $connection->query($sql);
    while ($row = $result->fetch_assoc()) {
        foreach ($return_result as $k=>$v){
            $return_result[$k]=(int)$row[$k];
        }
    }
    $result->close();
    return $return_result;
}


// Get Ethnicity Info function
function getEthnicityInfo($database,$connection,$in){
    $return_result=array(
        'hispanic'=>0,
        'non_hispanic'=>0,
        'white'=>0,
        'unknown'=>0
    );

    if(!strcmp($in,''))
        return $return_result;

    $table='Patient';
    $variable='Ethnicity';
    $primaryid='PatientID';
    $sql = "select (select count(distinct ".$primaryid.") from ".$table." where ".$variable."=\"hispanic\" and ".$primaryid." in (".$in.")) as hispanic,
            (select count(distinct ".$primaryid.") from ".$table." where ".$variable."=\"non-hispanic\" and ".$primaryid." in (".$in.")) as non_hispanic,
            (select count(distinct ".$primaryid.") from ".$table." where ".$variable."=\"white\" and ".$primaryid." in (".$in.")) as white,
            (select count(distinct ".$primaryid.") from ".$table." where ".$variable."=\"\" and ".$primaryid." in (".$in.")) as unknown";

    $result = $connection->query($sql);
    while ($row = $result->fetch_assoc()) {
        foreach ($return_result as $k=>$v){
            $return_result[$k]=(int)$row[$k];
        }
    }
    $result->close();
    return $return_result;
}

// Get Race Info function
function getRaceInfo($database,$connection,$in){
    $return_result=array(
        'african_american'=>0,
        'african_american_and_white'=>0,
        'asian'=>0,
        'hispanic'=>0,
        'white'=>0,
        'other'=>0,
        'unknown'=>0
    );

    if(!strcmp($in,''))
        return $return_result;

    $table='Patient';
    $variable='Race';
    $primaryid='PatientID';
    $sql = "select (select count(distinct ".$primaryid.") from ".$table." where ".$variable."=\"aa\" and ".$primaryid." in (".$in.")) as african_american,
            (select count(distinct ".$primaryid.") from ".$table." where ".$variable."=\"aa/white\" and ".$primaryid." in (".$in.")) as african_american_and_white,
            (select count(distinct ".$primaryid.") from ".$table." where ".$variable."=\"asian\" and ".$primaryid." in (".$in.")) as asian,
            (select count(distinct ".$primaryid.") from ".$table." where ".$variable."=\"hispanic\" and ".$primaryid." in (".$in.")) as hispanic,
            (select count(distinct ".$primaryid.") from ".$table." where ".$variable."=\"white\" and ".$primaryid." in (".$in.")) as white,
            (select count(distinct ".$primaryid.") from ".$table." where ".$variable."=\"other\" and ".$primaryid." in (".$in.")) as other,
            (select count(distinct ".$primaryid.") from ".$table." where ".$variable."=\"\" and ".$primaryid." in (".$in.")) as unknown";

    $result = $connection->query($sql);
    while ($row = $result->fetch_assoc()) {
        foreach ($return_result as $k=>$v){
            $return_result[$k]=(int)$row[$k];
        }
    }
    $result->close();
    return $return_result;
}

// Get Age Info function
function getAgeInfo($database,$connection,$in){
    $name_mapping= array(
        '0-13'=>'0-1 yr',
        '13-61'=>'2-5 yrs',
        '61-121'=>'6-10 yrs',
        '121-181'=>'11-15 yrs',
        '181-241'=>'16-20 yrs',
        '241-300'=>'>= 21 yrs',
        'NULL'=>'unknown'
    );

    $return_result= array(
        '0-1 yr'=>0,
        '2-5 yrs'=>0,
        '6-10 yrs'=>0,
        '11-15 yrs'=>0,
        '16-20 yrs'=>0,
        '>= 21 yrs'=>0,
        'unknown'=>0
    );

    if(!strcmp($in,''))
        return $return_result;

    $table='Patient';
    $variable='Age_Months';
    $primaryid='PatientID';
    foreach($name_mapping as $k=>$v){
        $ages=explode("-",$k);
        $n=0;
        if(!strcmp($k,'NULL'))
            $sql= "select count(distinct ".$primaryid.") from ".$table." where ".$variable." is null and ".$primaryid." in (" . $in . ")";
        else
            $sql="select count(distinct ".$primaryid.") from ".$table." where ".$variable." >= ".$ages[0]." and ".$variable." < ".$ages[1]." and ".$primaryid." in (" . $in . ")";
        if ($result = $connection->prepare($sql)) {
            $result->execute();
            $result->bind_result($n);
            $result->fetch();
            $result->close();
            $return_result[$v]=$n;
        }
    }

    return $return_result;
}

// Get FinalDiagnosis Info function
function getFinalDiagnosisInfo($database,$connection,$in){
    $name_mapping= array(
        'b_acute_lymphoblastic_leukemia'=>'(b cell) acute lymphoblastic leukemia',
        't_acute_lymphoblastic_leukemia'=>'(t cell) acute lymphoblastic leukemia',
        'acute_lymphoblastic_leukemia'=>'acute lymphoblastic leukemia',
        'acute_myeloid_leukemia'=>'acute myeloid leukemia',
        'adrenal_cortical_carcinoma'=>'adrenal cortical carcinoma',
        'aml_leukemia'=>'aml leukemia',
        'anaplastic_astrocytoma'=>'anaplastic astrocytoma',
        'anaplastic_ganglioglioma'=>'anaplastic ganglioglioma',
        'anaplastic_wilms'=>'anaplastic wilms',
        'benign_lymph_node'=>'benign lymph node',
        'cellular_congenitial_mesoblastic_nephroma'=>'cellular congenitial mesoblastic nephroma',
        'clear_cell_sarcoma_of_kidney'=>'clear cell sarcoma of kidney',
        'dermatofibrosarcoma_protuberans'=>'dermatofibrosarcoma protuberans',
        'diffuse_astrocytoma_grade_ii'=>'diffuse astrocytoma (grade ii)',
        'dysgerminoma'=>'dysgerminoma',
        'embryonal_rhabdomyosarcoma'=>'embryonal rhabdomyosarcoma',
        'ewing_sarcoma'=>'ewing\'s sarcoma',
        'fibrosarcoma'=>'fibrosarcoma',
        'hepatoblastoma'=>'hepatoblastoma',
        'hepatocellular_carcinoma'=>'hepatocellular carcinoma',
        'high_grade_glioma'=>'high grade glioma',
        'high_risk_neuroblastoma'=>'high risk neuroblastoma',
        'hodgkin_lymphoma'=>'hodgkin lymphoma',
        'immature_teratoma'=>'immature teratoma',
        'large_b_cell_lymphoma'=>'large b cell lymphoma',
        'leydig_cell_tumor_of_ovary'=>'leydig cell tumor of ovary',
        'lupus_erythematosus'=>'lupus erythematosus',
        'malignant_epitheloid_mesothelioma'=>'malignant epitheloid mesothelioma',
        'malignant_peripheral_nerve_sheath_tumor'=>'malignant peripheral nerve sheath tumor',
        'medulloblastoma'=>'medulloblastoma',
        'mixed_malignant_germ_cell_tumor'=>'mixed malignant germ cell tumor',
        'negative_for_leukemia'=>'negative for leukemia',
        'nephroblastoma'=>'nephroblastoma',
        'neuroblastoma'=>'neuroblastoma',
        'osteosarcoma'=>'osteosarcoma',
        'paraganglioma'=>'paraganglioma',
        'pediatric_type_follicular_lymphoma'=>'pediatric-type follicular lymphoma',
        'pheochromocytoma'=>'pheochromocytoma',
        'pilocytic_astrocytoma'=>'pilocytic astrocytoma',
        'pleomorphic_sarcoma'=>'pleomorphic sarcoma',
        'pre_b_all_leukemia'=>'pre b all leukemia',
        'ptld'=>'ptld (post transplant lymphoproliferative disorder)',
        'rhabdomyosarcoma'=>'rhabdomyosarcoma',
        'sarcoma'=>'sarcoma',
        'synovial_sarcoma'=>'synovial sarcoma',
        't_lymphoblastic_leukemia'=>'t lymphoblastic leukemia',
        't_lymphoblastic_lymphoma'=>'t lymphoblastic lymphoma',
        'wilms_tumor'=>'wilms tumor',
        'NULL'=>'unknown'
    );

    $return_result= array(
        '(b cell) acute lymphoblastic leukemia'=>0,
        '(t cell) acute lymphoblastic leukemia'=>0,
        'acute lymphoblastic leukemia'=>0,
        'acute myeloid leukemia'=>0,
        'adrenal cortical carcinoma'=>0,
        'aml leukemia'=>0,
        'anaplastic astrocytoma'=>0,
        'anaplastic ganglioglioma'=>0,
        'anaplastic wilms'=>0,
        'benign lymph node'=>0,
        'cellular congenitial mesoblastic nephroma'=>0,
        'clear cell sarcoma of kidney'=>0,
        'dermatofibrosarcoma protuberans'=>0,
        'diffuse astrocytoma (grade ii)'=>0,
        'dysgerminoma'=>0,
        'embryonal rhabdomyosarcoma'=>0,
        'ewing\'s sarcoma'=>0,
        'fibrosarcoma'=>0,
        'hepatoblastoma'=>0,
        'hepatocellular carcinoma'=>0,
        'high grade glioma'=>0,
        'high risk neuroblastoma'=>0,
        'hodgkin lymphoma'=>0,
        'immature teratoma'=>0,
        'large b cell lymphoma'=>0,
        'leydig cell tumor of ovary'=>0,
        'lupus erythematosus'=>0,
        'malignant epitheloid mesothelioma'=>0,
        'malignant peripheral nerve sheath tumor'=>0,
        'medulloblastoma'=>0,
        'mixed malignant germ cell tumor'=>0,
        'negative for leukemia'=>0,
        'nephroblastoma'=>0,
        'neuroblastoma'=>0,
        'osteosarcoma'=>0,
        'paraganglioma'=>0,
        'pediatric-type follicular lymphoma'=>0,
        'pheochromocytoma'=>0,
        'pilocytic astrocytoma'=>0,
        'pleomorphic sarcoma'=>0,
        'pre b all leukemia'=>0,
        'ptld (post transplant lymphoproliferative disorder)'=>0,
        'rhabdomyosarcoma'=>0,
        'sarcoma'=>0,
        'synovial sarcoma'=>0,
        't lymphoblastic leukemia'=>0,
        't lymphoblastic lymphoma'=>0,
        'wilms tumor'=>0,
        'unknown'=>0

    );

    if(!strcmp($in,''))
        return $return_result;

    $table='Sample';
    $variable='FinalDiagnosis';
    $primaryid='SampleID';


    $sql="select ";
    $isfirst=1;
    foreach ($name_mapping as $k=>$v){
        if($isfirst!=1){
            $sql .= ",";
        }
        if(!strcmp($k,'NULL')){
            $sql.= "(select count(distinct ".$primaryid.") from ".$table." where ".$variable."=\"\" and ".$primaryid." in (".$in.")) as ".$v;
        }else{
            $sql.="(select count(distinct ".$primaryid.") from ".$table." where ".$variable."=\"".$v."\" and ".$primaryid." in (".$in.")) as ".$k;
        }
        $isfirst=0;
    }

    $result = $connection->query($sql);
    while ($row = $result->fetch_assoc()) {
        foreach ($name_mapping as $k=>$v){
            if(!strcmp($k,'NULL')){
                $return_result[$v]=(int)$row[$v];
            }else{
                $return_result[$v]=(int)$row[$k];
            }
        }
    }

    $result->close();
    return $return_result;
}

// Get Therapy Info function
function getTherapyInfo($database,$connection,$in){
    $return_result= array(
        'chemo'=>0,
        'chemotherapy_and_radiation'=>0,
        'no_treatment'=>0,
        'unknown'=>0
    );

    if(!strcmp($in,''))
        return $return_result;

    $table='Sample';
    $variable='TherapyPriorPDXCollection';
    $primaryid='SampleID';
    $sql = "select (select count(distinct ".$primaryid.") from ".$table." where ".$variable."=\"chemo\" and ".$primaryid." in (".$in.")) as chemo,
            (select count(distinct ".$primaryid.") from ".$table." where ".$variable."=\"chemo and radiation\" and ".$primaryid." in (".$in.")) as chemotherapy_and_radiation,
            (select count(distinct ".$primaryid.") from ".$table." where ".$variable."=\"no treatment\" and ".$primaryid." in (".$in.")) as no_treatment,
            (select count(distinct ".$primaryid.") from ".$table." where ".$variable."=\"\" and ".$primaryid." in (".$in.")) as unknown";

    $result = $connection->query($sql);
    while ($row = $result->fetch_assoc()) {
        foreach ($return_result as $k=>$v){
            $return_result[$k]=(int)$row[$k];
        }
    }
    $result->close();
    return $return_result;
}

// Get Primary Tumor Site Info function
function getPrimaryTumorSiteInfo($database,$connection,$in){
    $name_mapping= array(
        'abdomen'=>'abdomen',
        'bone_marrow_blood'=>'bone marrow/blood',
        'brain'=>'brain',
        'brain_left_parieto_lobes'=>'brain, left parieto-occipital lobes',
        'brain_left_frontal_lobe'=>'brain- left frontal lobe',
        'brain_right_frontal_lobe'=>'brain- right frontal lobe',
        'cerebellum'=>'cerebellum',
        'left_adrenal_gland'=>'left adrenal gland',
        'left_cerebellum'=>'left cerebellum',
        'left_chest'=>'left chest',
        'left_chest_wall'=>'left chest wall',
        'left_femur'=>'left femur',
        'left_kidney'=>'left kidney',
        'left_lymph_node_neck'=>'left lymph node neck',
        'left_ovary'=>'left ovary',
        'left_parotid'=>'left parotid',
        'left_pleural_cavity'=>'left pleural cavity',
        'left_pleural_cavity_and_left_lung'=>'left pleural cavity and left lung',
        'left_posterior_mediastinum'=>'left posterior mediastinum',
        'left_proximal_femur'=>'left proximal femur',
        'left_retroperitoneum'=>'left retroperitoneum',
        'left_rib'=>'left rib',
        'left_testis'=>'left testis',
        'liver'=>'liver',
        'mediastinum'=>'mediastinum',
        'mesenteric_lymph_node'=>'mesenteric lymph node',
        'omentum'=>'omentum',
        'pelvis'=>'pelvis',
        'posterior_fossa'=>'posterior fossa',
        'right_femur'=>'right femur',
        'right_foot'=>'right foot',
        'right_kidney'=>'right kidney',
        'right_kidney_and_adrenal_gland'=>'right kidney and adrenal gland',
        'right_ovary'=>'right ovary',
        'right_parotid_gland'=>'right parotid gland',
        'right_proximal_humerus'=>'right proximal humerus',
        'right_testis'=>'right testis',
        'right_tibia'=>'right tibia',
        'scalp'=>'scalp',
        'NULL'=>'unknown'
    );

    $return_result= array(
        'abdomen'=>0,
        'bone marrow/blood'=>0,
        'brain'=>0,
        'brain, left parieto-occipital lobes'=>0,
        'brain- left frontal lobe'=>0,
        'brain- right frontal lobe'=>0,
        'cerebellum'=>0,
        'left adrenal gland'=>0,
        'left cerebellum'=>0,
        'left chest'=>0,
        'left chest wall'=>0,
        'left femur'=>0,
        'left kidney'=>0,
        'left lymph node neck'=>0,
        'left ovary'=>0,
        'left parotid'=>0,
        'left pleural cavity'=>0,
        'left pleural cavity and left lung'=>0,
        'left posterior mediastinum'=>0,
        'left proximal femur'=>0,
        'left retroperitoneum'=>0,
        'left rib'=>0,
        'left testis'=>0,
        'liver'=>0,
        'mediastinum'=>0,
        'mesenteric lymph node'=>0,
        'omentum'=>0,
        'pelvis'=>0,
        'posterior fossa'=>0,
        'right femur'=>0,
        'right foot'=>0,
        'right kidney'=>0,
        'right kidney and adrenal gland'=>0,
        'right ovary'=>0,
        'right parotid gland'=>0,
        'right proximal humerus'=>0,
        'right testis'=>0,
        'right tibia'=>0,
        'scalp'=>0,
        'unknown'=>0
    );

    if(!strcmp($in,''))
        return $return_result;

    $table='Sample';
    $variable='PrimaryTumorSite';
    $primaryid='SampleID';

    $sql="select ";
    $isfirst=1;
    foreach ($name_mapping as $k=>$v){
        if($isfirst!=1){
            $sql .= ",";
        }
        if(!strcmp($k,'NULL')){
            $sql.= "(select count(distinct ".$primaryid.") from ".$table." where ".$variable."=\"\" and ".$primaryid." in (".$in.")) as ".$v;
        }else{
            $sql.="(select count(distinct ".$primaryid.") from ".$table." where ".$variable."=\"".$v."\" and ".$primaryid." in (".$in.")) as ".$k;
        }
        $isfirst=0;
    }

    $result = $connection->query($sql);
    while ($row = $result->fetch_assoc()) {
        foreach ($name_mapping as $k=>$v){
            if(!strcmp($k,'NULL')){
                $return_result[$v]=(int)$row[$v];
            }else{
                $return_result[$v]=(int)$row[$k];
            }
        }
    }
    $result->close();
    return $return_result;
}


// Get Primary Or Relapse Info function
function getPrimaryOrRelapseInfo($database,$connection,$in){
    $name_mapping= array(
        'p'=>'primary',
        'r'=>'relapse',
        'NULL'=>'unknown'
    );

    $return_result=array(
        'primary'=>0,
        'relapse'=>0,
        'unknown'=>0
    );

    if(!strcmp($in,''))
        return $return_result;

    $table='Sample';
    $variable='PriOrRelapse';
    $primaryid='SampleID';

    $sql="select ";
    $isfirst=1;
    foreach ($name_mapping as $k=>$v){
        if($isfirst!=1){
            $sql .= ",";
        }
        if(!strcmp($k,'NULL')){
            $sql.= "(select count(distinct ".$primaryid.") from ".$table." where ".$variable."=\"\" and ".$primaryid." in (".$in.")) as ".$v;
        }else{
            $sql.="(select count(distinct ".$primaryid.") from ".$table." where ".$variable."=\"".$v."\" and ".$primaryid." in (".$in.")) as ".$k;
        }
        $isfirst=0;
    }

    $result = $connection->query($sql);
    while ($row = $result->fetch_assoc()) {
        foreach ($name_mapping as $k=>$v){
            if(!strcmp($k,'NULL')){
                $return_result[$v]=(int)$row[$v];
            }else{
                $return_result[$v]=(int)$row[$k];
            }
        }
    }
    $result->close();

    return $return_result;
}

// Get Procedure Type Info function
function getBiospyInfo($database,$connection,$in){
    $name_mapping= array(
        'amputation'=>'amputation',
        'aspirate'=>'aspirate',
        'autopsy'=>'autopsy',
        'core'=>'core',
        'excisional'=>'excisional',
        'leukophoresis'=>'leukophoresis',
        'marrow_aspirate'=>'marrow aspirate',
        'resection'=>'resection',
        'NULL'=>'unknown'
    );
    $return_result=array(
        'amputation'=>0,
        'aspirate'=>0,
        'autopsy'=>0,
        'core'=>0,
        'excisional'=>0,
        'leukophoresis'=>0,
        'marrow aspirate'=>0,
        'resection'=>0,
        'unknown'=>0
    );

    if(!strcmp($in,''))
        return $return_result;

    $table='Sample';
    $variable='Biospy';
    $primaryid='SampleID';

    $sql="select ";
    $isfirst=1;
    foreach ($name_mapping as $k=>$v){
        if($isfirst!=1){
            $sql .= ",";
        }
        if(!strcmp($k,'NULL')){
            $sql.= "(select count(distinct ".$primaryid.") from ".$table." where ".$variable."=\"\" and ".$primaryid." in (".$in.")) as ".$v;
        }else{
            $sql.="(select count(distinct ".$primaryid.") from ".$table." where ".$variable."=\"".$v."\" and ".$primaryid." in (".$in.")) as ".$k;
        }
        $isfirst=0;
    }

    $result = $connection->query($sql);
    while ($row = $result->fetch_assoc()) {
        foreach ($name_mapping as $k=>$v){
            if(!strcmp($k,'NULL')){
                $return_result[$v]=(int)$row[$v];
            }else{
                $return_result[$v]=(int)$row[$k];
            }
        }
    }
    $result->close();
    return $return_result;
}

// Get Has PDX DNA Info function
function getHasPDXDNAInfo($database,$connection,$in){
    $return_result=array(
        'yes'=>0,
        'no'=>0,
        'unknown'=>0
    );

    if(!strcmp($in,''))
        return $return_result;

    $table='Sample';
    $variable='IsPDXDNACollected';
    $primaryid='SampleID';
    $sql = "select (select count(distinct ".$primaryid.") from ".$table." where ".$variable."=\"y\" and ".$primaryid." in (" . $in . ")) as yes,
        (select count(distinct ".$primaryid.") from ".$table." where ".$variable."=\"n\" and ".$primaryid." in (" . $in . ")) as no,
        (select count(distinct ".$primaryid.") from ".$table." where ".$variable."=\"\" and ".$primaryid." in (" . $in . ")) as unknown";
    $result = $connection->query($sql);
    while ($row = $result->fetch_assoc()) {
        foreach ($return_result as $k=>$v){
            $return_result[$k]=(int)$row[$k];
        }
    }
    $result->close();
    return $return_result;
}

// Get Has PDX RNA Info function
function getHasPDXRNAInfo($database,$connection,$in){
    $return_result=array(
        'yes'=>0,
        'no'=>0,
        'unknown'=>0
    );

    if(!strcmp($in,''))
        return $return_result;

    $table='Sample';
    $variable='IsPDXRNACollected';
    $primaryid='SampleID';
    $sql = "select (select count(distinct ".$primaryid.") from ".$table." where ".$variable."=\"y\" and ".$primaryid." in (" . $in . ")) as yes,
        (select count(distinct ".$primaryid.") from ".$table." where ".$variable."=\"n\" and ".$primaryid." in (" . $in . ")) as no,
        (select count(distinct ".$primaryid.") from ".$table." where ".$variable."=\"\" and ".$primaryid." in (" . $in . ")) as unknown";
    $result = $connection->query($sql);
    while ($row = $result->fetch_assoc()) {
        foreach ($return_result as $k=>$v){
            $return_result[$k]=(int)$row[$k];
        }
    }
    $result->close();
    return $return_result;
}

// Get Has Primary DNA Info function
function getHasPriDNAInfo($database,$connection,$in){
    $return_result=array(
        'yes'=>0,
        'no'=>0,
        'unknown'=>0
    );

    if(!strcmp($in,''))
        return $return_result;

    $table='Sample';
    $variable='IsPrimaryDNACollected';
    $primaryid='SampleID';
    $sql = "select (select count(distinct ".$primaryid.") from ".$table." where ".$variable."=\"y\" and ".$primaryid." in (" . $in . ")) as yes,
        (select count(distinct ".$primaryid.") from ".$table." where ".$variable."=\"n\" and ".$primaryid." in (" . $in . ")) as no,
        (select count(distinct ".$primaryid.") from ".$table." where ".$variable."=\"\" and ".$primaryid." in (" . $in . ")) as unknown";
    $result = $connection->query($sql);
    while ($row = $result->fetch_assoc()) {
        foreach ($return_result as $k=>$v){
            $return_result[$k]=(int)$row[$k];
        }
    }
    $result->close();
    return $return_result;
}

// Get Has Primary RNA Info function
function getHasPriRNAInfo($database,$connection,$in){
    $return_result=array(
        'yes'=>0,
        'no'=>0,
        'unknown'=>0
    );

    if(!strcmp($in,''))
        return $return_result;

    $table='Sample';
    $variable='IsPrimaryRNACollected';
    $primaryid='SampleID';
    $sql = "select (select count(distinct ".$primaryid.") from ".$table." where ".$variable."=\"y\" and ".$primaryid." in (" . $in . ")) as yes,
        (select count(distinct ".$primaryid.") from ".$table." where ".$variable."=\"n\" and ".$primaryid." in (" . $in . ")) as no,
        (select count(distinct ".$primaryid.") from ".$table." where ".$variable."=\"\"  and ".$primaryid." in (" . $in . ")) as unknown";
    $result = $connection->query($sql);
    while ($row = $result->fetch_assoc()) {
        foreach ($return_result as $k=>$v){
            $return_result[$k]=(int)$row[$k];
        }
    }
    $result->close();
    return $return_result;
}


// Get PDX Result array
$gender_pdx=getGenderInfo("PDX_v3",$connection,$patient_in_pdx);
$eth_pdx=getEthnicityInfo("PDX_v3",$connection,$patient_in_pdx);
$race_pdx=getRaceInfo("PDX_v3",$connection,$patient_in_pdx);
$age_pdx=getAgeInfo("PDX_v3",$connection,$patient_in_pdx);
$final_diagnosis_pdx=getFinalDiagnosisInfo("PDX_v3",$connection,$sample_in_pdx);
$therapy_pdx=getTherapyInfo("PDX_v3",$connection,$sample_in_pdx);
$psite_pdx=getPrimaryTumorSiteInfo("PDX_v3",$connection,$sample_in_pdx);
$primary_relapse_pdx=getPrimaryOrRelapseInfo("PDX_v3",$connection,$sample_in_pdx);
$procedure_type_pdx=getBiospyInfo("PDX_v3",$connection,$sample_in_pdx);
$has_pdx_dna_pdx=getHasPDXDNAInfo("PDX_v3",$connection,$sample_in_pdx);
$has_pdx_rna_pdx=getHasPDXRNAInfo("PDX_v3",$connection,$sample_in_pdx);
$has_primary_dna_pdx=getHasPriDNAInfo("PDX_v3",$connection,$sample_in_pdx);
$has_primary_rna_pdx=getHasPriRNAInfo("PDX_v3",$connection,$sample_in_pdx);

$connection->close();

echo json_encode(array(
    "primary_relapse_pdx" => $primary_relapse_pdx,
    "filters" => $filters,
    "search_list"  => $search_list,
    "in" => ["pin"=> $patient_in_pdx,"sin"=> $sample_in_pdx],
    "patient_sql" => $patient_sql_pdx,
    "sample_sql" => $sample_sql_pdx,
    "num"=> ["patientsNum"=> $patient_number_pdx, "sampleNum"=> $sample_number_pdx],
    "pie"=> [
        "gender"=> $gender_pdx,
        "ethnicity"=>$eth_pdx,
        "race"=>$race_pdx,
        "age"=>$age_pdx,
        "finalDiagnosis"=>$final_diagnosis_pdx,
        "therapy"=>$therapy_pdx,
        "primaryTumorSite"=>$psite_pdx,
        "primaryRelapse"=>$primary_relapse_pdx,
        "procedureType"=>$procedure_type_pdx,
        "hasPDXDna"=>$has_pdx_dna_pdx,
        "hasPDXRna"=>$has_pdx_rna_pdx,
        "hasPrimaryDna"=>$has_primary_dna_pdx,
        "hasPrimaryRna"=>$has_primary_rna_pdx]));


