<?php

session_start();
include "../authenticate_user_session.php";
require_once ("../../php_class/EscapeString.inc");
require_once ("../../php_class/dbencryt.inc");
require_once ("../../dbpdx.inc");

function getPatientIDFormat($operate,$item,$d){
    $format='';
    if(strpos($operate,'edit') > -1 ){
        if($item!= null){
            $format.="<input type='checkbox' class='hidden' name='pid[]' value='".$d."' checked/>";
        }else{
            $format.="<a class='btn btn-xs btn-success editpid' href='patient.php?operate=edit&pid=".$d."'> Edit </a>";
        }
    }
    if(strpos($operate,'delete') > -1 ){
        $format.=" <button type='button' class='btn btn-xs btn-danger delete-patient'
                 data-toggle='modal' data-target='#delete_patient_modal'> Delete </button>";
    }
    if(strpos($operate,'singleSelect') > -1 ){
        $format.="<input type='radio' name='pid' value='".$d."'/>";
    }

    if(strpos($operate,'multiSelect') > -1){
        $format.=" <input type='checkbox' name='pid[]' value='".$d."'/>";
    }
    return $format."<br>".$d;
}

function getTherapyListStr($d){
    global $hostname,$username,$password,$newdbname;
    $db = new mysqli(Encryption::decrypt($hostname),Encryption::decrypt($username),Encryption::decrypt($password),Encryption::decrypt($newdbname));
    if($db->connect_error){
        die('Unable to connect to database: ' . $db->connect_error);
    }

    $resultStr="";
    if($d!==null){
        $sql="SELECT cther.Initial_Therapy FROM Therapy AS ther LEFT JOIN CodeTherapy AS cther ON ther.Therapy=cther.ID WHERE Patient_ID=?;";
        if($result = $db->prepare($sql)) {
            $result->bind_param("s",$d);
            $result->execute();
            $result->bind_result($therapyStr);
            while($result->fetch()){
                if($therapyStr!=null || $therapyStr!=""){
                    if($resultStr!="") $resultStr.=", ";
                    $resultStr.=$therapyStr;
                }
            }
            $result->close();
        }
    }
    $db->close();
    return $resultStr;
}

/*
* DataTables example server-side processing script.
*
* Please note that this script is intentionally extremely simply to show how
* server-side processing can be implemented, and probably shouldn't be used as
* the basis for a large complex system. It is suitable for simple use cases as
* for learning.
*
* See http://datatables.net/usage/server-side for full details on the server-
* side processing requirements of DataTables.
*
* @license MIT - http://datatables.net/license_mit
*/

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
* Easy set variables
*/

// DB table to use
$table = 'Patient';

// Table's primary key
$primaryKey = 'Patient_ID';

// Operation for patients decides the style in column formatter
$operate=(isset($_GET['operate']) && $_GET['operate']!="")?EscapeString::escape($_GET['operate']):null;
$pid=(isset($_GET['pid']) && $_GET['pid']!="")?EscapeString::escape($_GET['pid']):null;
$item=(isset($_GET['item']) && $_GET['item']!="")?EscapeString::escape($_GET['item']):null;

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$column_idx=0;
$columns = array(
    array(
        'db' => "`{$table}`.`{$primaryKey}`",
        'dt' => $column_idx,
        'field' => "Patient_ID",
        'formatter' => function( $d, $row ) {
            global $operate,$item;
            return getPatientIDFormat($operate,$item,$d);
        }
    ),
    array(
        'db' => 'Local_Patient_ID',
        'dt' => ++$column_idx,
        'field' => 'Local_Patient_ID',
    ),
    array(
        'db' => 'OpenSpecimen_Patient_ID',
        'dt' => ++$column_idx,
        'field' => 'OpenSpecimen_Patient_ID'
    ),
    array(
        'db' => 'Initial_Diagnosis',
        'dt' => ++$column_idx,
        'field' => 'Initial_Diagnosis',
    ),
    array(
        'db' => 'Metastatic_At_Diagnosis',
        'dt' => ++$column_idx ,
        'field' => 'Metastatic_At_Diagnosis',
        'formatter' => function( $d, $row ) {
            switch ($d){
                case 1: $result='Yes';break;
                case 2: $result='No';break;
                case 99: $result='Unknown';break;
                default: $result='';
            }
            return $result;
        }
    ),
    array(
        'db' => "`{$table}`.`isDelete`",
        'dt' => ++$column_idx ,
        'field' => "isDelete",
        'formatter' => function( $d, $row ) {
            if($d=="0"){
                $sysPatID=$row[0];
                return getTherapyListStr($sysPatID);
            }else{
                return "";
            }
        }
    ),
    array(
        'db' => 'Initial_VitalStatus',
        'dt' => ++$column_idx ,
        'field' => 'Initial_VitalStatus',
    ),
    array(
        'db' => 'Initial_Sex',
        'dt' => ++$column_idx ,
        'field' => 'Initial_Sex'
    ),
    array(
        'db' => 'Initial_Race',
        'dt' => ++$column_idx ,
        'field' => 'Initial_Race',
    ),
    array(
        'db' => 'Initial_Ethnic',
        'dt'=> ++$column_idx,
        'field' => 'Initial_Ethnic',
    ),
    array(
        'db' => 'Age_At_Diagnosis_In_Months',
        'dt' => ++$column_idx ,
        'field' => 'Age_At_Diagnosis_In_Months',
    ),
    array(
        'db' => 'Age_At_Diagnosis_In_Year_Old',
        'dt' => ++$column_idx ,
        'field' => 'Age_At_Diagnosis_In_Year_Old',
    ),
    array(
        'db' => 'Age_At_Diagnosis_in_AD_Year',
        'dt' => ++$column_idx ,
        'field' => 'Age_At_Diagnosis_in_AD_Year',
    ),
    array(
        'db' => 'Initial_TumorType',
        'dt'=> ++$column_idx,
        'field' => 'Initial_TumorType',
    ),
    array(
        'db' => 'Initial_AnatomicalSite',
        'dt'=> ++$column_idx,
        'field' => 'Initial_AnatomicalSite',
    ),
    array(
        'db' => 'Initial_AnatomicalLaterality',
        'dt'=> ++$column_idx,
        'field' => 'Initial_AnatomicalLaterality',
    ),
    array(
        'db' => 'Initial_AnatomicalDirection',
        'dt'=> ++$column_idx,
        'field' => 'Initial_AnatomicalDirection',
    ),
    array(
        'db' => 'Father_Patient_ID',
        'dt'=> ++$column_idx,
        'field' => 'Father_Patient_ID',
    ),
    array(
        'db' => 'Mother_Patient_ID',
        'dt'=> ++$column_idx,
        'field' => 'Mother_Patient_ID',
    ),
    array('db' => "`{$table}`.`Note`", 'dt'=> ++$column_idx,'field' => "Note",
    )
);


$sql_details = array(
    'user' => Encryption::decrypt($username),
    'pass' => Encryption::decrypt($password),
    'db'   => Encryption::decrypt($newdbname),
    'host' => Encryption::decrypt($hostname)
);


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
* If you just want to use the basic configuration for DataTables with PHP
* server-side, there is no need to edit below this line.
*/

require( '../../php_class/vendor/SSP_customized.php' );

$joinQuery = "FROM `{$table}` LEFT JOIN `Diagnosis` AS `dia` ON (`{$table}`.`Patient_ID` = `dia`.`Patient_ID`)" .
    " LEFT JOIN `CodeDiagnosis` AS `cd` ON `dia`.`Diagnosis`=`cd`.`ID`" .
    " LEFT JOIN `Tumor` AS `tu` ON (`{$table}`.`Patient_ID` = `tu`.`Patient_ID`)" .
    " LEFT JOIN `CodeTumorType` AS `ctutype` ON `tu`.`Tumor_Type`=`ctutype`.`ID`" .
    " LEFT JOIN `CodeAnatomicalSite` AS `csite` ON `tu`.`Site`=`csite`.`ID`" .
    " LEFT JOIN `CodeAnatomicalLaterality` AS `clater` ON `tu`.`Site_Laterality`=`clater`.`ID`" .
    " LEFT JOIN `CodeAnatomicalDirection` AS `cdir` ON `tu`.`Site_Direction`=`cdir`.`ID`" .
    " LEFT JOIN `CodeSex` AS `cs` ON `{$table}`.`Sex`=`cs`.`ID`" .
    " LEFT JOIN `CodeRace` AS `cr` ON `{$table}`.`Race`=`cr`.`ID`" .
    " LEFT JOIN `CodeEthnic` AS `ce` ON `{$table}`.`Ethnic`=`ce`.`ID`".
    " LEFT JOIN `CodeVitalStatus` AS `cvs` ON `{$table}`.`Vital_Status`=`cvs`.`ID`";


$patientIsClinicalSysCondition="";
if(strpos($operate,'select') !== FALSE && $item==="qbrcPat"){
    $patientIsClinicalSysCondition=" AND `{$table}`.`Patient_ID` REGEXP '^[A-Z]{1}[0-9]{6}$'";
}

$extraCondition="";
// For searching new added patient after EXCEL batch upload
if($pid==="allnew"){
    $newPidsStr = '';
    $db_remoter = new mysqli(Encryption::decrypt($hostname), Encryption::decrypt($username),
        Encryption::decrypt($password), Encryption::decrypt($remoter_dbname));
    if ($db_remoter->connect_error) {
        die('Unable to connect to database: ' . $db_remoter->connect_error);
    }

    $sql = "SELECT S.NewPatientID FROM PDXParameters AS P LEFT JOIN PDXSuccessResults AS S ON P.JobID = S.JobID ".
        "WHERE P.AccountID=? AND P.Tag='1' AND P.DataType='patient'";
    if ($result = $db_remoter->prepare($sql)) {
        $result->bind_param("i", $_SESSION["user_id"]);
        $result->execute();
        $result->bind_result($newPid);
        $isfirst = 1;
        while ($row = $result->fetch()) {
            if($isfirst == 1){
                $newPidsStr .= "\"".$newPid."\"";
                $isfirst = 0;
            }else{
                $newPidsStr .= ",\"".$newPid."\"";
            }
        }
        $result->close();
    }
    $db_remoter->close();

    if($newPidsStr !== ''){
        $extraCondition = "`{$table}`.`{$primaryKey}` IN ({$newPidsStr}) AND `{$table}`.`isDelete` = 0 ";
    }else{
        $extraCondition = "`{$table}`.`{$primaryKey}` is NULL AND `{$table}`.`isDelete` = 0 ";
    }
// For searching multiple patients
}elseif(strpos($pid,',') !== FALSE){
    $pidArr=explode(",",$pid);
    $pidsStr="";
    $isfirst=1;
    foreach ($pidArr as $k=>$v){
        if($isfirst===1){
            $pidsStr.="\"".$v."\"";
            $isfirst=0;
        }else{
            $pidsStr.=",\"".$v."\"";
        }
    }
    $extraCondition = "`{$table}`.`{$primaryKey}` IN (".$pidsStr.") AND `{$table}`.`isDelete` = 0 ";
// For searching one patient
}elseif($pid!==null){
    $extraCondition = "`{$table}`.`{$primaryKey}` =\"".$pid."\" AND `{$table}`.`isDelete` = 0 ";
// For searching all un-deleted patients
}else{
    $extraCondition ="`{$table}`.`isDelete` = 0 ";
}
$extraCondition.=$patientIsClinicalSysCondition;

echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraCondition)
);

