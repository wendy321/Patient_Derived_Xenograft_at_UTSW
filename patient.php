<?php
session_start();
$_SESSION["pre_page"]=basename($_SERVER['PHP_SELF']);
$php_class_dir="php_class";
$php_script_dir="php_script";
include "{$php_script_dir}/authenticate_user_session.php";
include "dbpdx.inc";
require ("{$php_class_dir}/EscapeString.inc");
require ("{$php_class_dir}/dbencryt.inc");
$operate=(isset($_GET['operate']) && $_GET['operate']!=="")?EscapeString::escape($_GET['operate']):null;
$pid=(isset($_GET['pid']) && $_GET['pid']!=="")?EscapeString::escape($_GET['pid']):null;

if($pid!=null){
    // Database connection
    $db = new mysqli(Encryption::decrypt($hostname),Encryption::decrypt($username),Encryption::decrypt($password),Encryption::decrypt($newdbname));
    if($db->connect_error){
        die('Unable to connect to database: ' . $db->connect_error);
    }
    $db->set_charset("utf8");

    // Patient table
    $localPid=$openPid=$fPid=$mPid=$ageDiagMon=$ageDiagYrOld=$ageDiagYrAd=$sex=$race=$ethnic=$vitalStatus=$isMetaAtDiag=$note="";
    $sql="SELECT Local_Patient_ID,OpenSpecimen_Patient_ID,Father_Patient_ID,Mother_Patient_ID,Age_At_Diagnosis_In_Months, "
        ."Age_At_Diagnosis_In_Year_Old,Age_At_Diagnosis_in_AD_Year,Sex,Race,Ethnic,Vital_Status,Metastatic_At_Diagnosis, "
        ."Note FROM Patient WHERE Patient_ID = ?";
    if($result = $db->prepare($sql)){
        $result->bind_param("s",$pid);
        $result->execute();
        $result->bind_result($localPid,$openPid,$fPid,$mPid,$ageDiagMon,$ageDiagYrOld,$ageDiagYrAd,$sex,$race,$ethnic,
            $vitalStatus,$isMetaAtDiag,$note);
        $result->fetch();
        $result->close();
    }

    // Diagnosis table
    $finalDiag="";
    $sql="SELECT Diagnosis FROM Diagnosis WHERE Patient_ID = ? AND isFinalDiagnosis = 1 ORDER BY CreateTime DESC LIMIT 1";
    if($result = $db->prepare($sql)){
        $result->bind_param("s",$pid);
        $result->execute();
        $result->bind_result($finalDiag);
        $result->fetch();
        $result->close();
    }

    // Therapy table
    $therapy_arr=[];
    $sql="SELECT DISTINCT Therapy FROM Therapy WHERE Patient_ID = ?";
    if($result = $db->prepare($sql)){
        $result->bind_param("s",$pid);
        $result->execute();
        $result->bind_result($therapy);
        while($result->fetch()){
            array_push($therapy_arr,$therapy);
        }
        $result->close();
    }
    // Tumor table
    $priTumorSite=$priTumorLater=$priTumorDir=$metTumorSite=$metTumorLater=$metTumorDir="";
    $sql="SELECT Tumor_Type,Site,Site_Laterality,Site_Direction FROM Tumor WHERE Patient_ID = ?";
    if($result = $db->prepare($sql)){
        $result->bind_param("s",$pid);
        $result->execute();
        $result->bind_result($tumorType,$tumorSite,$tumorLater,$tumorDir);
        while($result->fetch()){
            // primary tumor
            if($tumorType=="1"){
                $priTumorSite=$tumorSite;
                $priTumorLater=$tumorLater;
                $priTumorDir=$tumorDir;
            }
            // metastasis tumor
            if($tumorType=="2"){
                $metTumorSite=$tumorSite;
                $metTumorLater=$tumorLater;
                $metTumorDir=$tumorDir;
            }
        }
        $result->close();
    }

    $db->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>PDX Portal</title>
    <meta name="keywords" content="PDX, UTSW">
    <meta name="description" content="A user-friendly public platform for data integration, analysis, sharing,
		and management for proposed projects.">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="images/utsw_icon.jpg">
    <link rel="stylesheet" href="css/vendor/jquery-ui.css" type='text/css'>
    <link rel="stylesheet" href="css/vendor/animate.min.css">
    <link rel="stylesheet" href="css/vendor/bootstrap.min.3.4.css">
    <link rel="stylesheet" href="css/vendor/fontawesome-all.min.css">
    <link rel="stylesheet" href="css/vendor/jquery.dataTables.min.css"/>
    <link rel="stylesheet" href="css/vendor/buttons.dataTables.min.css"/>
    <link rel="stylesheet" href="css/vendor/responsive.dataTables.min.css"/>
    <link rel="stylesheet" href="css/vendor/fileinput.css" media="all"  type="text/css">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/templatemo-style.css">

    <!--[if lt IE 9]>
    <script src="js/vendor/html5shiv.min.js"></script>
    <script src="js/vendor/respond.min.js"></script>
    <![endif]-->
    <style>

    </style>
</head>
<body id="top">

<!-- start preloader -->
<div class="preloader">
    <div class="sk-spinner sk-spinner-wave">
        <div class="sk-rect1"></div>
        <div class="sk-rect2"></div>
        <div class="sk-rect3"></div>
        <div class="sk-rect4"></div>
        <div class="sk-rect5"></div>
    </div>
</div>
<!-- end preloader -->

<!-- start navigation -->
<?php include("nav.php");?>
<!-- end navigation -->

<!-- start bkg -->
<section id="bkg">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-1 col-md-10">
                <h1 class="wow fadeIn" data-wow-offset="50" data-wow-delay="0.9s">Patient Derived Xenograft Web Portal </h1>
                <div class="element">
                    <div class="sub-element">A user-friendly public platform</div>
                    <div class="sub-element">Data integration, analysis, sharing, and management</div>
                    <div class="sub-element">A data entry system using standardized common data elements</div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end bkg -->

<section id="fh5co-services">
    <div class="container">
        <div class="row">
            <br><br>
            <div class="col-md-12 text-center">
                <h2 class="wow bounceIn" data-wow-offset="50" data-wow-delay="0.3s">
                    <span>
                        <?php
                            if($operate!=null && strpos($operate,"edit") !== false){
                                echo "Edit";
                            }else{
                                echo "View";
                            }
                        ?>
                        Patient
                    </span>
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="form-group">
                    <form method="post" action="<?php echo $php_script_dir;?>/send_update_patient.php">
                        <!--Input Method 1 Start-->
                        <div class="alert alert-warning">
                            <div class="border-effect">
                                <h3 class="text-center"> SYSTEM PATIENT ID </h3>
                                <div class='form-group text-center'>
                                    <label for="ddl_sysPatientId" style="font-size: 1.5em"><?php echo $pid;?>
                                    </label>
                                    <input type="text" class="form-control hidden" id="ddl_sysPatientId" name="sysPatientId"
                                           readonly pattern=".{7}" value="<?php echo $pid;?>"/>
                                </div>
                            </div>

                            <hr>

                            <div class="border-effect">
                                <p class="text-center" style="font-weight: 700">
                                    <em><i class="fa fa-asterisk fa-fw"></i></em> as required field
                                </p>
                                <h3 class="text-center"> PATIENT ID </h3>
                                <div class="text-justify">Please fill in <b>Local Patient ID, OpenSpecimen Patient ID, or both</b>. It's important to fill
                                    in at least one of them, so we can easily trace data and prevent from storing duplicate patients
                                    into database. System will automatically generate a system-wide and format-unified
                                    <b>System Patient ID </b> later after adding the patient information.
                                </div><br/>
                                <div class='form-group'>
                                    <label for="ddl_localPatientId" class="control-label">Local Patient ID (max 30 characters)
                                        <i class="fa fa-question-circle"
                                           title="Patient ID in your local system">
                                        </i>
                                        <em><i class="fa fa-asterisk fa-fw"></i></em>
                                    </label>
                                    <input type="text" class="form-control" id="ddl_localPatientId" name="localPatientId"
                                           pattern=".{0,30}"/>
                                    <span class="glyphicon glyphicon-remove form-control-feedback hidden"></span>
                                    <div class='error-msg text-left hidden'></div>
                                </div><br/>
                                <div class="text-center" style="font-weight:700;">OR</div><br/>
                                <div class='form-group'>
                                    <label for="ddl_openSpecPatientId" class="control-label">
                                        OpenSpecimen Patient ID (max 30 characters)
                                        <i class="fa fa-question-circle"
                                           title="Patient ID in OpenSpecimen">
                                        </i>
                                        <em><i class="fa fa-asterisk fa-fw"></i></em>
                                    </label>
                                    <input type="text" class="form-control" id="ddl_openSpecPatientId" name="openSpecPatientId"
                                           pattern=".{0,30}"/>
                                    <span class="glyphicon glyphicon-remove form-control-feedback hidden"></span>
                                    <div class='error-msg text-left'></div>
                                </div><br>
                            </div>

                            <hr>

                            <div class="border-effect">
                                <h3 class="text-center"> PATIENT INFO </h3>
                                <label for="ddl_sex">Sex</label>
                                <select id="ddl_sex" name="sex" class="form-control">
                                    <option value="">Please select ...</option>
                                    <option value="1">Female</option>
                                    <option value="2">Male</option>
                                    <option value="3">Undifferntiated</option>
                                    <option value="99">Unknown</option>
                                </select><br>

                                <label for="ddl_race">Race</label>
                                <select id="ddl_race" name="race" class="form-control">
                                    <option value="">Please select ...</option>
                                    <option value="1">American Indian or Alaska Native</option>
                                    <option value="2">Asian</option>
                                    <option value="3">Black or African American</option>
                                    <option value="4">Native Hawaiian or Other Pacific Islander</option>
                                    <option value="5">White</option>
                                    <option value="6">Multiple</option>
                                    <option value="98">Other</option>
                                    <option value="99">Unknown</option>
                                </select><br>

                                <label for="ddl_ethic">Ethnic</label>
                                <select id="ddl_ethic" name="ethic" class="form-control">
                                    <option value="">Please select ...</option>
                                    <option value="1">Hispanic or Latino</option>
                                    <option value="2">Not Hispanic Latino</option>
                                    <option value="99">Unknown</option>
                                </select><br>

                                <label for="ddl_death">Vital Status</label>
                                <select id="ddl_death" name="death" class="form-control">
                                    <option value="">Please select ...</option>
                                    <option value="0">Alive</option>
                                    <option value="1">Death</option>
                                    <option value="99">Unknown</option>
                                </select><br>
                            </div>

                            <hr>

                            <div class="border-effect">
                                <h3 class="text-center"> DIAGNOSIS </h3>
                                <label for="ddl_ageDiagMon">Age At Diagnosis ( Months )
                                    <i class="fa fa-question-circle"
                                       title="Age at diagnosis in months relative to date of birth (DOB).">
                                    </i>
                                </label>
                                <input id="ddl_ageDiagMon" name="ageDiagMon" class="form-control" type="number"
                                       min="0" step="1" max="1800" value=""/><br>

                                <label for="ddl_ageDiagYrOld">Age At Diagnosis ( Year-Old )
                                    <i class="fa fa-question-circle"
                                       title="Age at diagnosis in year-old relative to date of birth (DOB).">
                                    </i>
                                </label>
                                <input id="ddl_ageDiagYrOld" name="ageDiagYrOld" class="form-control" type="number"
                                       min="1" step="1" max="200" placeholder="e.g. 15"/><br>

                                <label for="ddl_ageDiagYrAd">Age At Diagnosis ( A.D. Year )
                                    <i class="fa fa-question-circle"
                                       title="Age at diagnosis in A.D. year.">
                                    </i>
                                </label>
                                <input id="ddl_ageDiagYrAd" name="ageDiagYrAd" class="form-control" type="number"
                                       min="1" step="1" max="9999" placeholder="e.g. 1976"/><br>

                                <label for="ddl_metaDiag">Metastatic At Diagnosis</label>
                                <select id="ddl_metaDiag" name="metaDiag" class="form-control">
                                    <option value="">Please select ...</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                    <option value="99">Unknown</option>
                                </select><br>

                                <label for="ddl_finalDiag">Final Diagnosis</label>
                                <select id="ddl_finalDiag" name="finalDiag" class="form-control">
                                    <option value="">Please select ...</option>
                                    <optgroup label="Astrocytoma">
                                        <option value="20">Astrocytoma, anaplastic</option>
                                        <option value="21">Astrocytoma, NOS</option>
                                        <option value="22">Pilocytic astrocytoma</option>
                                    </optgroup>
                                    <optgroup label="Blastoma">
                                        <option value="10">Gonadoblastoma</option>
                                        <option value="38">Hepatoblastoma</option>
                                        <option value="40">Neuroblastoma, high risk</option>
                                        <option value="42">Medulloblastoma, NOS</option>
                                        <option value="39">Nephroblastoma, NOS</option>
                                        <option value="41">Neuroblastoma, NOS</option>
                                        <option value="43">Pineoblastoma</option>
                                    </optgroup>
                                    <optgroup label="Carcinoma">
                                        <option value="12">Adrenal cortical carcinoma</option>
                                        <option value="6">Choriocarcinoma</option>
                                        <option value="4">Embryonal carcinoma, NOS</option>
                                        <option value="13">Hepatocellular carcinoma, NOS</option>
                                    </optgroup>
                                    <optgroup label="Germ Cell Tumor">
                                        <option value="3">Germinoma</option>
                                        <option value="11">Mixed germ cell tumor</option>
                                        <option value="1">Seminoma</option>
                                        <option value="5">Yolk sac tumor</option>
                                    </optgroup>
                                    <optgroup label="Glioma">
                                        <option value="23">Ganglioglioma, anaplastic</option>
                                        <option value="24">Glioma, malignant</option>
                                        <option value="25">Paraganglioma, NOS</option>
                                    </optgroup>
                                    <optgroup label="Leukemia">
                                        <option value="19">Acute biphenotypic leukemia (Acute mixed lineage leukemia)</option>
                                        <option value="16">Acute lymphoblastic leukemia, NOS</option>
                                        <option value="17">Acute myeloid leukemia, NOS</option>
                                        <option value="18">Negative for leukemia</option>
                                        <option value="14">Precursor B-cell lymphoblastic leukemia (Pre-B ALL)</option>
                                        <option value="15">Precursor T-cell lymphoblastic leukemia</option>
                                    </optgroup>
                                    <optgroup label="Lymphoma">
                                        <option value="47">ALK positive large B-cell lymphoma</option>
                                        <option value="48">Anaplastic large cell lymphoma, NOS</option>
                                        <option value="46">Follicular lymphoma, NOS</option>
                                        <option value="44">Hodgkin lymphoma, NOS</option>
                                        <option value="45">Malignant lymphoma, large B-cell, diffuse, NOS</option>
                                        <option value="56">Malignant peripheral nerve sheath tumor</option>
                                        <option value="49">Post transplant lymphoproliferative disorder, NOS</option>
                                        <option value="50">Precursor T-cell lymphoblastic lymphoma (T-lymphoblastic lymphoma)</option>
                                        <option value="51">Lymph node, benign</option>
                                    </optgroup>
                                    <optgroup label="Sarcoma">
                                        <option value="28">Clear cell sarcoma of kidney</option>
                                        <option value="29">Dermatofibrosarcoma, NOS</option>
                                        <option value="30">Embryonal rhabdomyosarcoma</option>
                                        <option value="31">Ewing sarcoma</option>
                                        <option value="32">Fibrosarcoma, NOS</option>
                                        <option value="33">Osteosarcoma, NOS</option>
                                        <option value="34">Pleomorphic liposarcoma</option>
                                        <option value="35">Rhabdomyosarcoma, NOS</option>
                                        <option value="37">Sarcoma, NOS</option>
                                        <option value="36">Synovial sarcoma, NOS</option>
                                    </optgroup>
                                    <optgroup label="Teratoma">
                                        <option value="8">Teratoma, benign</option>
                                        <option value="7">Teratoma, malignant, NOS</option>
                                        <option value="9">Teratoma, NOS</option>
                                    </optgroup>
                                    <optgroup label="Wilms">
                                        <option value="26">Nephroblastoma, NOS (Wilms tumor, anaplastic)</option>
                                        <option value="27">Nephroblastoma, NOS (Wilms tumor)</option>
                                    </optgroup>
                                    <optgroup label="Other">
                                        <option value="52">Mesoblastic nephroma</option>
                                        <option value="2">Dysgerminoma</option>
                                        <option value="53">Sertoli-Leydig cell tumor, NOS</option>
                                        <option value="54">Lupus erythematosus</option>
                                        <option value="55">Epithelioid mesothelioma, malignant</option>
                                        <option value="57">Pheochromocytoma, NOS</option>
                                    </optgroup>
                                    <option value="98">Other</option>
                                    <option value="99">Unknown</option>
                                </select><br>
                            </div>

                            <hr>

                            <div class="border-effect">
                                <h3 class="text-center"> THERAPY </h3>
                                <p style="display: inline-block;max-width: 100%;margin-bottom: 5px;font-weight: 700;">
                                    Therapy ( prior to procedure taken)
                                </p>
                                <div class="checkbox" style="padding:0 3% ">
                                    <label for="chemo" style="width:32%">
                                        <input type="checkbox" id="chemo" name="therapy[]" value="1">Chemotherapy
                                    </label>

                                    <label for="immuno"  style="width:32%">
                                        <input type="checkbox" id="immuno" name="therapy[]" value="3">Immunotherapy
                                    </label>

                                    <label for="radia"  style="width:32%">
                                        <input type="checkbox" id="radia" name="therapy[]" value="2">Radiation Therapy
                                    </label>

                                    <label for="target" style="width:32%">
                                        <input type="checkbox" id="target" name="therapy[]" value="4">Target Therapy
                                    </label>

                                    <label for="other" style="width:32%">
                                        <input type="checkbox" id="other" name="therapy[]" value="98">Other
                                    </label>

                                    <label for="notreat" style="width:32%">
                                        <input type="checkbox" id="notreat" name="therapy[]" value="97">No Treatment
                                    </label>

                                    <label for="unknown" style="width:32%">
                                        <input type="checkbox" id="unknown" name="therapy[]" value="99">Unknown
                                    </label>
                                </div>
                            </div>

                            <div id="detail" style="display: none">
                                <hr>

                                <div class="border-effect">
                                    <h3 class="text-center"> PRIMARY TUMOR SITE </h3>
                                    <label for="ddl_priTumorSite">Site (Need to be confirmed)
                                        <i class="fa fa-question-circle"
                                           title="Primary tumor site of the patient.">
                                        </i></label>
                                    <select class="form-control" id="ddl_priTumorSite" name="priTumorSite">
                                        <option value="">Please select ...</option>
                                        <option value="10">Abdomen, NOS</option>
                                        <option value="11">Adrenal gland, NOS</option>
                                        <option value="12">Bone Marrow</option>
                                        <option value="13">Brain, NOS</option>
                                        <option value="14">Cerebellum, NOS</option>
                                        <option value="15">Cerebral meninges</option>
                                        <option value="16">Frontal lobe</option>
                                        <option value="17">Parietal lobe</option>
                                        <option value="18">Intra-abdominal lymph nodes</option>
                                        <option value="19">Kidney, NOS</option>
                                        <option value="3">Liver</option>
                                        <option value="20">Long bones of upper limb, scapula and associated joints</option>
                                        <option value="21">Long bones of lower limb and associated joints</option>
                                        <option value="22">Lung, NOS</option>
                                        <option value="23">Lymph nodes of head, face and neck</option>
                                        <option value="24">Lymph node, NOS</option>
                                        <option value="25">Mandible</option>
                                        <option value="4">Mediastinum, NOS</option>
                                        <option value="26">Mouth, NOS</option>
                                        <option value="5">Ovary</option>
                                        <option value="27">Parotid gland</option>
                                        <option value="28">Pelvis, NOS</option>
                                        <option value="29">Pleura, NOS</option>
                                        <option value="6">Retroperitoneum</option>
                                        <option value="30">Rib, Sternum, Clavicle and associated joints</option>
                                        <option value="31">Short bones of upper limb and associated joints</option>
                                        <option value="32">Short bones of lower limb and associated joints</option>
                                        <option value="33">Skin of scalp and neck</option>
                                        <option value="34">Spinal cord</option>
                                        <option value="8">Testis, NOS</option>
                                        <option value="35">Thorax, NOS</option>
                                        <option value="9">Vagina, NOS</option>
                                        <option value="98">Other</option>
                                        <option value="99">Unknown</option>
                                    </select><br>

                                    <label for="ddl_priTumorLater">Laterality </label>
                                    <select class="form-control" id="ddl_priTumorLater" name="priTumorLater">
                                        <option value="">Please select ...</option>
                                        <option value="1">Left</option>
                                        <option value="2">Right</option>
                                        <option value="3">Bilateral</option>
                                        <option value="98">Other</option>
                                        <option value="99">Unknown</option>
                                    </select><br>

                                    <label for="ddl_priTumorDir">Direction </label>
                                    <select class="form-control" id="ddl_priTumorDir" name="priTumorDir">
                                        <option value="">Please select ...</option>
                                        <option value="1">Proximal</option>
                                        <option value="2">Distal</option>
                                        <option value="3">Anterior</option>
                                        <option value="4">Posterior</option>
                                        <option value="98">Other</option>
                                        <option value="99">Unknown</option>
                                    </select><br>
                                </div>

                                <hr>

                                <div class="border-effect">
                                    <h3 class="text-center"> METASTASIS TUMOR SITE </h3>
                                    <label for="ddl_metTumorSite">Site (Need to be confirmed)
                                        <i class="fa fa-question-circle"
                                           title="Metastasis tumor site of the patient.">
                                        </i></label>
                                    <select class="form-control" id="ddl_metTumorSite" name="metTumorSite">
                                        <option value="">Please select ...</option>
                                        <option value="10">Abdomen, NOS</option>
                                        <option value="11">Adrenal gland, NOS</option>
                                        <option value="12">Bone Marrow</option>
                                        <option value="13">Brain, NOS</option>
                                        <option value="14">Cerebellum, NOS</option>
                                        <option value="15">Cerebral meninges</option>
                                        <option value="16">Frontal lobe</option>
                                        <option value="17">Parietal lobe</option>
                                        <option value="18">Intra-abdominal lymph nodes</option>
                                        <option value="19">Kidney, NOS</option>
                                        <option value="3">Liver</option>
                                        <option value="20">Long bones of upper limb, scapula and associated joints</option>
                                        <option value="21">Long bones of lower limb and associated joints</option>
                                        <option value="22">Lung, NOS</option>
                                        <option value="23">Lymph nodes of head, face and neck</option>
                                        <option value="24">Lymph node, NOS</option>
                                        <option value="25">Mandible</option>
                                        <option value="4">Mediastinum, NOS</option>
                                        <option value="26">Mouth, NOS</option>
                                        <option value="5">Ovary</option>
                                        <option value="27">Parotid gland</option>
                                        <option value="28">Pelvis, NOS</option>
                                        <option value="29">Pleura, NOS</option>
                                        <option value="6">Retroperitoneum</option>
                                        <option value="30">Rib, Sternum, Clavicle and associated joints</option>
                                        <option value="31">Short bones of upper limb and associated joints</option>
                                        <option value="32">Short bones of lower limb and associated joints</option>
                                        <option value="33">Skin of scalp and neck</option>
                                        <option value="34">Spinal cord</option>
                                        <option value="8">Testis, NOS</option>
                                        <option value="35">Thorax, NOS</option>
                                        <option value="9">Vagina, NOS</option>
                                        <option value="98">Other</option>
                                        <option value="99">Unknown</option>
                                    </select><br>

                                    <label for="ddl_metTumorLater">Laterality</label>
                                    <select class="form-control" id="ddl_metTumorLater" name="metTumorLater">
                                        <option value="">Please select ...</option>
                                        <option value="1">Left</option>
                                        <option value="2">Right</option>
                                        <option value="3">Bilateral</option>
                                        <option value="98">Other</option>
                                        <option value="99">Unknown</option>
                                    </select><br>

                                    <label for="ddl_metTumorDir">Direction</label>
                                    <select class="form-control" id="ddl_metTumorDir" name="metTumorDir">
                                        <option value="">Please select ...</option>
                                        <option value="1">Proximal</option>
                                        <option value="2">Distal</option>
                                        <option value="3">Anterior</option>
                                        <option value="4">Posterior</option>
                                        <option value="98">Other</option>
                                        <option value="99">Unknown</option>
                                    </select><br>
                                </div>

                                <hr>

                                <div class="border-effect">
                                    <h3 class="text-center"> FATHER & MOTHER PATIENT ID </h3>
                                    <div class="text-justify">
                                        If the father or mother patient id is not in our system yet,
                                        please leave this input empty and add it later.
                                        You can add a new father or mother patient info by going to
                                        <a href="create_patient.php" target="_blank">create patient page</a>.
                                    </div><br>
                                    <label for="ddl_fatherPid" class="control-label">Father Patient ID</label>
                                    <input type="text" class="form-control" id="ddl_fatherPid" name="fatherPid"
                                           pattern=".{0,7}" data-toggle="modal" data-target="#patient_modal"/><br>

                                    <label for="ddl_motherPid" class="control-label">Mother Patient ID</label>
                                    <input type="text" class="form-control" id="ddl_motherPid" name="motherPid"
                                           pattern=".{0,7}" data-toggle="modal" data-target="#patient_modal"/>
                                </div>

                                <hr>

                                <div class="border-effect">
                                    <h3 class="text-center"> NOTE </h3>
                                    <label for="txt_notes">Notes</label>
                                    <textarea id="txt_notes" class="form-control" name="notes"
                                              placeholder="Enter Your Notes (max 100 characters)"
                                              rows="5" maxlength="100"></textarea>
                                </div>
                            </div>
                            <br/>
                            <div id="seemore" class="btn btn-info">See More Variables</div>
                            <br/><br/>
                            <a class="btn btn-success" href="patient.php?operate=<?php echo $operate;?>&pid=<?php echo $pid;?>">Reset</a>
                            <input id="submitbtn" type="submit" class="btn btn-primary" value="Submit"/>
                            <sub><em><i class="fa fa-asterisk fa-fw"></i></em> as required field</sub>
                        </div>
                        <!--Input Method 1 END-->
                    </form>
                </div>
            </div>

            <div id="patient_modal" class="modal fade text-center" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h3 class="modal-title">Select a Patient</h3>
                        </div>
                        <div class="modal-body" style="padding:24px;">
                            <br/><br/>
                            <table id="patient_table" class="display" style="width:100%">
                                <thead>
                                <tr>
                                    <th>System Patient ID</th>
                                    <th>Local Patient ID</th>
                                    <th>OpenSpecimen Patient ID</th>
                                    <th>Final Diagnosis</th>
                                    <th>Metastatic At Diagnosis</th>
                                    <th>Therapy</th>
                                    <th>Vital Status</th>
                                    <th>Sex</th>
                                    <th>Race</th>
                                    <th>Ethnic</th>
                                    <th>Age at Diagnosis (Months)</th>
                                    <th>Age at Diagnosis (Year-Old)</th>
                                    <th>Age at Diagnosis (A.D. Year)</th>
                                    <th>Tumor Type</th>
                                    <th>Tumor Site</th>
                                    <th>Tumor Site Laterality </th>
                                    <th>Tumor Site Direction </th>
                                    <th>Notes</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div><br/>
                        <div class="modal-footer">
                            <button id="deselect_pid" type="button" class="btn btn-warning">Deselect</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Confirm</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- end portfolio -->

<!-- start copyright -->
<?php include ("footer.php");?>
<!-- end copyright -->
<script src="js/vendor/jquery.js"></script>
<script src="js/vendor/jquery-ui.js"></script>
<script src="js/vendor/bootstrap.min.3.4.js"></script>
<script src="js/vendor/typed.js"></script>
<script src="js/vendor/wow.min.js"></script>
<script src="js/vendor/jquery.dataTables.min.js"></script>
<script src="js/vendor/dataTables.responsive.min.js"></script>
<script src="js/vendor/fileinput.js" type="text/javascript"></script>
<script src="js/custom.js"></script>
<script src="js/create_pages.js"></script>
<script type="text/javascript">
    $(function () {
        $('.templatemo-nav ul li:nth-child(4) a').addClass('current');

        // toggle read more and read less
        toggleReadMoreOrLess("#instructionBottom", "Close", "Read More ..", "#instruction", "slow" , 'linear');
        toggleReadMoreOrLess("#seemore", "Less ..", "See More Variables", "#detail", "fast", "linear");


        // border animation
        const borderDiv='.border-effect';
        const initialBorderCss = {"border":"0.5px solid #fcf8e3", "border-radius":"3px", "padding":"10px", "margin":"0"};
        const mouseEnterBorderCss={"border-color":"#8a6d3b","box-shadow":"3px 3px 8px 3px #8a6d3b"};
        const mouseLeaveBorderCss={"border-color":"#fcf8e3","box-shadow":"none"};
        const titleInBorderDiv=$(borderDiv).find('h3');
        const initialTitleCss={"color":"#60481e","font-weight":"650"};
        borderEffectOfMouseEnterLeaveDiv(borderDiv, initialBorderCss, mouseEnterBorderCss, mouseLeaveBorderCss,
            titleInBorderDiv,initialTitleCss);

        // check whether local patient id or open specimen patient id already exist in the database
        const localPidInput=$("#ddl_localPatientId");
        const openPidInput=$("#ddl_openSpecPatientId");
        const exceptLocalPidInput="<?php echo $localPid;?>";
        const exceptOpenPidInput="<?php echo $openPid;?>";
        const checkedInputs = ["#ddl_localPatientId","#ddl_openSpecPatientId"];
        $(localPidInput).on("change",function(){
            checkExistInDBExcept("Patient","Local_Patient_ID",$(this).val(),this,exceptLocalPidInput,checkedInputs,"#submitbtn");
        });
        $(openPidInput).on("change",function(){
            checkExistInDBExcept("Patient","OpenSpecimen_Patient_ID",$(this).val(),this,exceptOpenPidInput,checkedInputs,"#submitbtn");
        });

        // Therapy checkbox selection
        // if any of no treatment, other, unknown is checked, then other can't be checked
        $('#notreat, #unknown').on('click',function(){
            let labels=$(this).parent().siblings();
            labels.each(function (i,item) {
                $(item).find('input').prop('checked',false);
            });
        });
        // if any of chemo, radiation, immuno, target is checked, then no treatment, other, and unknown can't be checked
        $('#chemo, #radia, #immuno, #target, #other').on('click',function(){
            $.each(['#notreat', '#unknown'],function(i,item){
                if($(item).prop('checked')){
                    $(item).prop('checked',false);
                }
            });
        });

        /**
         *  Patient ID Input & Patient DataTable Interaction
         */
        const patient_input=['#ddl_fatherPid','#ddl_motherPid'];
        const patient_modal = $('#patient_modal');
        const patient_table=$('#patient_table');
        const patient_data_table_script="server_processing_patient";
        const patient_deselect_id = $('#deselect_pid');
        inputAndModalInteraction(patient_input, patient_modal, patient_table, patient_data_table_script, patient_deselect_id,"singleSelect");

        // Fill in patient info
        $(localPidInput).val('<?php echo $localPid;?>');
        $(openPidInput).val('<?php echo $openPid;?>');
        $('#ddl_sex').find('option[value="<?php echo $sex;?>"]').prop('selected',true);
        $('#ddl_race').find('option[value="<?php echo $race;?>"]').prop('selected',true);
        $('#ddl_ethic').find('option[value="<?php echo $ethnic;?>"]').prop('selected',true);
        $('#ddl_death').find('option[value="<?php echo $vitalStatus;?>"]').prop('selected',true);
        $('#ddl_ageDiagMon').val('<?php echo $ageDiagMon;?>');
        $('#ddl_ageDiagYrOld').val('<?php echo $ageDiagYrOld;?>');
        $('#ddl_ageDiagYrAd').val('<?php echo $ageDiagYrAd;?>');
        $('#ddl_metaDiag').find('option[value="<?php echo $isMetaAtDiag;?>"]').prop('selected',true);
        $('#ddl_finalDiag').find('option[value="<?php echo $finalDiag;?>"]').prop('selected',true);
        <?php
            foreach ($therapy_arr as $k=>$v){
                echo "$(\".checkbox\").find(\"input[name='therapy[]'][value='".$v."']\").prop(\"checked\",true);\n\t";
            }
        ?>
        $('#ddl_priTumorSite').find('option[value="<?php echo $priTumorSite;?>"]').prop('selected',true);
        $('#ddl_priTumorLater').find('option[value="<?php echo $priTumorLater;?>"]').prop('selected',true);
        $('#ddl_priTumorDir').find('option[value="<?php echo $priTumorDir;?>"]').prop('selected',true);
        $('#ddl_metTumorSite').find('option[value="<?php echo $metTumorSite;?>"]').prop('selected',true);
        $('#ddl_metTumorLater').find('option[value="<?php echo $metTumorLater;?>"]').prop('selected',true);
        $('#ddl_metTumorDir').find('option[value="<?php echo $metTumorDir;?>"]').prop('selected',true);
        $('#ddl_fatherPid').val('<?php echo $fPid;?>');
        $('#ddl_motherPid').val('<?php echo $mPid;?>');
        $('#txt_notes').val('<?php echo $note;?>');
    });
</script>

</body>
</html>
