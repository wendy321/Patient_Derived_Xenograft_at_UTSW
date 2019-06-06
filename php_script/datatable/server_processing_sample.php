<?php
session_start();
include "../authenticate_user_session.php";
include '../../php_class/vendor_barcode/barcode.php';
require_once ("../../php_class/EscapeString.inc");
require_once ("../../php_class/SampleID.inc");
require_once ("../../php_class/dbencryt.inc");
require_once ("../../dbpdx.inc");

function getUUIDFormat($operate,$item,$d){
    $format="";
    if(strpos($operate,'edit') > -1 ){
        if($item!= null){
            $format.="<input type='checkbox' class='hidden' name='sampleUuid[]' value='".$d."' checked/>";
        }else{
            $format.="<a class='btn btn-xs btn-success editsampleid' href='sample.php?operate=edit&uuid=".$d."'> Edit </a>";
        }
    }
    if(strpos($operate,'delete') > -1 ){
        $format.=" <button type='button' class='btn btn-xs btn-danger delete-sample' 
                 data-toggle='modal' data-target='#delete_sample_modal'> Delete </button>";
    }
    if(strpos($operate,'singleSelect') > -1){
        $format.=" <input type='radio' name='sampleuuid' value='".$d."'/>";
    }
    if(strpos($operate,'multiSelect') > -1){
        $format.=" <input type='checkbox' name='sampleuuid[]' value='".$d."'/>";
    }
    if(strpos($operate,'print') > -1 ){
        $format.=" <button type='button' class='btn btn-xs btn-primary print-sample'> Print Barcode </button>";
    }
    return $format."<br>".$d;
}

function getSampleIDFormat($uuid){
    global $hostname, $username,$password,$newdbname;
    $db = new mysqli(Encryption::decrypt($hostname),Encryption::decrypt($username),
        Encryption::decrypt($password),Encryption::decrypt($newdbname));
    if($db->connect_error){
        die('Unable to connect to database: ' . $db->connect_error);
    }
    $convertedSampleId=SampleID::getConvertedSampleID($db,$uuid);
    $db->close();
    $resultPid = substr($convertedSampleId, 0, -9);
    $specimenType = substr($convertedSampleId, -9, 2);
    $sampleClass = substr($convertedSampleId, -7, 2);
    $sampleSrc = substr($convertedSampleId, -5, 3);
    $autoInc = substr($convertedSampleId, -2, 2);
    return $resultPid . $specimenType . $sampleClass . $sampleSrc . $autoInc;
}

function getBarcodeImg($d){
    $generator = new barcode_generator();
    $symbology="dmtx";
    $data=$d;
    $options=array("sf"=>4);
    $svg = $generator->render_svg($symbology, $data, $options);
    return $svg;
}

function getInputBoxFormat($d, $inputName){
    return "<div class='form-group'>
                <label class='control-label'>
                    <input class='form-control text-center hightlight' type='text' name='". $inputName ."' 
                        value='". $d ."' style='width:140px'/>
                </label>    
                <div class='error-msg text-left'></div>
            </div>";
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
$table = 'Sample';

// Table's primary key
$primaryKey = 'UUID';


$operate=(isset($_GET['operate']) && $_GET['operate']!="")?EscapeString::escape($_GET['operate']):null;
$uuid=(isset($_GET['uuid']) && $_GET['uuid']!="")?EscapeString::escape($_GET['uuid']):null;
$item=(isset($_GET['item']) && $_GET['item']!="")?EscapeString::escape($_GET['item']):null;
$filter=(isset($_GET['filter']) && $_GET['filter']!="")?EscapeString::escape($_GET['filter']):null;

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$column_idx=0;
$columns = array(
    array(
        'db' => "`{$table}`.`{$primaryKey}`",
        'dt' => $column_idx, 'field' => 'UUID',
        'formatter' => function ($d) {
            global $operate,$item;
            return getUUIDFormat($operate,$item,$d);
        }
    ),
    array(
        'db' => "`{$table}`.`Existed_Barcode`",
        'dt' => ++$column_idx, 'field' => 'Existed_Barcode',
    ),
    array(
        'db' => "`{$table}`.`Sample_ID`",
        'dt' => ++$column_idx, 'field' => 'Sample_ID',
        'formatter' => function ($d,$row) {
            return getSampleIDFormat($row[0]);
        }
    ),
    array(
        'db' => "`{$table}`.`Local_Sample_ID`",
        'dt' => ++$column_idx, 'field' => 'Local_Sample_ID',
    ),
    array(
        'db' => "`{$table}`.`OpenSpecimen_Sample_ID`",
        'dt' => ++$column_idx, 'field' => 'OpenSpecimen_Sample_ID',
    ),
    array(
        'db' => "`{$table}`.`Patient_ID`",
        'dt' => ++$column_idx, 'field' => 'Patient_ID',
        'formatter' => function ($d, $row) {
            global $operate, $item;
            $isPidDelete=$row[39];
            if($isPidDelete==='1'){
                return '';
            }else{
                if((strpos($operate, 'edit') > -1 && $item === "patient")) {
                    return "<div class='form-group'>
                                    <label class='control-label'>
                                        <input class='form-control text-center hightlight' type='text' name='patientId[]' 
                                            value='" . $d . "' data-toggle='modal' data-target='#patient_modal'
                                            style='width:140px'/>
                                    </label>    
                                    <div class='error-msg text-left'></div>
                                </div>";
                }else{
                    return $d!==null?'<a href="patientlist.php?operate=view&pid='.$d.'"><u>'.$d.'</u></a>':'';
                }

            }
        }
    ),
    array(
        'db' => "`{$table}`.`Initial_Sample_UUID`",
        'dt' => ++$column_idx, 'field' => 'Initial_Sample_UUID'
    ),
    array(
        'db' => "`{$table}`.`Parent_UUID`",
        'dt' => ++$column_idx, 'field' => 'Parent_UUID'
    ),
    array(
        'db' => "`{$table}`.`Date_Derive_From_Parent`",
        'dt' => ++$column_idx, 'field' => 'Date_Derive_From_Parent'
    ),
    array(
        'db' => "Initial_ProcedureType",
        'dt' => ++$column_idx, 'field' => 'Initial_ProcedureType',
    ),
    array(
        'db' => "`{$table}`.`Date_Procedure`",
        'dt' => ++$column_idx, 'field' => 'Date_Procedure',
    ),
    array(
        'db' => 'Initial_AnatomicalSite',
        'dt' => ++$column_idx, 'field' => 'Initial_AnatomicalSite',
    ),
    array(
        'db' => 'Initial_AnatomicalLaterality',
        'dt' => ++$column_idx, 'field' => 'Initial_AnatomicalLaterality',
    ),
    array(
        'db' => 'Initial_AnatomicalDirection',
        'dt' => ++$column_idx, 'field' => 'Initial_AnatomicalDirection',
    ),
    array(
        'db' => 'Initial_SpecimenType',
        'dt' => ++$column_idx, 'field' => 'Initial_SpecimenType',
    ),
    array(
        'db' => "Initial_SampleSource",
        'dt' => ++$column_idx, 'field' => 'Initial_SampleSource',
    ),
    array(
        'db' => 'Initial_SampleClass',
        'dt' => ++$column_idx, 'field' => 'Initial_SampleClass',
    ),
    array(
        'db' => 'Initial_SampleType',
        'dt' => ++$column_idx, 'field' => 'Initial_SampleType',
    ),
    array(
        'db' => 'Initial_SamplePrep',
        'dt' => ++$column_idx, 'field' => 'Initial_SamplePrep',
    ),
    array(
        'db' => "`{$table}`.`Quantity_Value`",
        'dt' => ++$column_idx, 'field' => 'Quantity_Value',
    ),
    array(
        'db' => 'Initial_AmountUnit',
        'dt' => ++$column_idx, 'field' => 'Initial_AmountUnit',
    ),
    array(
        'db' => "`{$table}`.`Concentration_Value`",
        'dt' => ++$column_idx, 'field' => 'Concentration_Value',
    ),
    array(
        'db' => "`{$table}`.`Concentration_Unit`",
        'dt' => ++$column_idx, 'field' => 'Concentration_Unit',
    ),
    array(
        'db' => 'Initial_SampleStorageBank',
        'dt' => ++$column_idx, 'field' => 'Initial_SampleStorageBank',
    ),
    array(
        'db' => 'Initial_StorageRoom',
        'dt' => ++$column_idx, 'field' => 'Initial_StorageRoom',
    ),
    array(
        'db' => 'Initial_StorageCabinetType',
        'dt' => ++$column_idx, 'field' => 'Initial_StorageCabinetType',
    ),
    array(
        'db' => 'Initial_Temperature',
        'dt' => ++$column_idx, 'field' => 'Initial_Temperature',
    ),
    array(
        'db' => "`{$table}`.`Cabinet_Number`",
        'dt' => ++$column_idx, 'field' => 'Cabinet_Number',
    ),
    array(
        'db' => "`{$table}`.`Shelf_Number`",
        'dt' => ++$column_idx, 'field' => 'Shelf_Number',
    ),
    array(
        'db' => "`{$table}`.`Rack_Number`",
        'dt' => ++$column_idx, 'field' => 'Rack_Number',
    ),
    array(
        'db' => "`{$table}`.`Box_Number`",
        'dt' => ++$column_idx, 'field' => 'Box_Number',
    ),
    array(
        'db' => "`{$table}`.`Position_Number`",
        'dt' => ++$column_idx, 'field' => 'Position_Number',
    ),
    array(
        'db' => "`{$table}`.`Position_Text`",
        'dt' => ++$column_idx, 'field' => 'Position_Text',
    ),
    array(
        'db' => "`{$table}`.`Date_Sample_Ship`",
        'dt' => ++$column_idx, 'field' => 'Date_Sample_Ship',
    ),
    array(
        'db' => "`{$table}`.`Date_PDX_Sample_Generate`",
        'dt' => ++$column_idx, 'field' => 'Date_PDX_Sample_Generate',
    ),
    array(
        'db' => "`{$table}`.`Date_PDX_Sample_Receive`",
        'dt' => ++$column_idx, 'field' => 'Date_PDX_Sample_Receive',
    ),
    array(
        'db' => 'Initial_SampleContributorInstitute',
        'dt' => ++$column_idx, 'field' => 'Initial_SampleContributorInstitute',
    ),
    array(
        'db' => "`{$table}`.`isDelete`",
        'dt' => ++$column_idx, 'field' => "isDelete",
        'formatter' => function ($d,$row) {
            return getBarcodeImg($row[0])."<br> Sample ID:".getSampleIDFormat($row[0]);
        }
    ),
    array(
        'db' => "`{$table}`.`Notes`",
        'dt' => ++$column_idx, 'field' => 'Notes',
    ),
    array(
        'db' => "`{$table}`.`CreateTime`",
        'dt' => ++$column_idx, 'field' => 'CreateTime',
    ),
    array(
        'db' => '`pat`.`isDelete`',
        'dt' => ++$column_idx, 'field' => 'isDelete',
    )

);

// SQL server connection information
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

$joinQuery="FROM `{$table}`";
$joinQuery .= " LEFT JOIN `Patient` AS `pat` ON `{$table}`.`Patient_ID`=`pat`.`Patient_ID`" .
    " LEFT JOIN `CodeProcedureType` AS `cpt` ON `{$table}`.`Procedure_Type`=`cpt`.`ID`" .
    " LEFT JOIN `CodeAnatomicalSite` AS `cas` ON `{$table}`.`Anatomical_Site`=`cas`.`ID`" .
    " LEFT JOIN `CodeAnatomicalLaterality` AS `cal` ON `{$table}`.`Anatomical_Laterality`=`cal`.`ID`" .
    " LEFT JOIN `CodeAnatomicalDirection` AS `cad` ON `{$table}`.`Anatomical_Direction`=`cad`.`ID`" .
    " LEFT JOIN `CodeSpecimenType` AS `cst` ON `{$table}`.`Specimen_Type`=`cst`.`ID`" .
    " LEFT JOIN `CodeSampleSource` AS `css` ON `{$table}`.`Sample_Source`=`css`.`ID`" .
    " LEFT JOIN `CodeSampleClass` AS `csc` ON `{$table}`.`Sample_Class`=`csc`.`ID`" .
    " LEFT JOIN `CodeSampleType` AS `csat` ON `{$table}`.`Sample_Type`=`csat`.`ID`" .
    " LEFT JOIN `CodeSamplePrep` AS `csp` ON `{$table}`.`Sample_Prep`=`csp`.`ID`" .
    " LEFT JOIN `CodeAmountUnit` AS `cau` ON `{$table}`.`Quantity_Unit`=`cau`.`ID`" .
    " LEFT JOIN `CodeSampleStorageBank` AS `csb` ON `{$table}`.`Storage_Bank`=`csb`.`ID`" .
    " LEFT JOIN `CodeStorageRoom` AS `csr` ON `{$table}`.`Storage_Room`=`csr`.`ID`" .
    " LEFT JOIN `CodeStorageCabinetType` AS `cscat` ON `{$table}`.`Cabinet_Type`=`cscat`.`ID`" .
    " LEFT JOIN `CodeTemperature` AS `ct` ON `{$table}`.`Cabinet_Temperature`=`ct`.`ID`" .
    " LEFT JOIN `CodeSampleContributorInstitute` AS `csct` ON `{$table}`.`Sample_Contributor_Institute_ID`=`csct`.`ID`";

$isInitialSamCondition="";
if(strpos($filter,'initialSam') > -1){
    $isInitialSamCondition=" AND `{$table}`.`isInitialSample` = 1 ";
}
if(strpos($filter,'no') > -1){
    $isInitialSamCondition="";
}

$hasFaMotherSamCondition="";
if(strpos($filter,'hasFaMoSam') > -1){
    $hasFaMotherSamCondition=" AND ((`pat`.`Father_Patient_ID` IS NOT NULL OR `pat`.`Mother_Patient_ID` IS NOT NULL) ".
        " OR `{$table}`.`{$primaryKey}` IN (SELECT `Child_Sample_UUID` FROM `ChildFatherMotherSampleMap` ".
        " WHERE `Father_Sample_UUID` IS NOT NULL OR `Mother_Sample_UUID` IS NOT NULL)) ";
}

// sql condition for linking unlinked-patient_id to sample uuid
$patientIsNullCondition="";
if(strpos($operate,'edit') > -1 && $item==="patient"){
    $patientIsNullCondition=" AND (`{$table}`.`Patient_ID` IS NULL)";
}

// sql condition for linking parent sample to sample uuid
$parentIsNullCondition="";
if (strpos($operate,'edit') > -1 && $item === "parentSample") {
    $parentIsNullCondition=" AND `{$table}`.`Parent_UUID` IS NULL ";
}

$projectCondition="";
if($_SESSION["user_id"] != 1){
    $projectCondition.=" AND (`{$table}`.`Project` IN (".$_SESSION["project"].")) OR `{$table}`.`Project` IS NULL";
}

$extraCondition="";
// For searching new added sample after EXCEL batch upload
if($uuid==="allnew"){
    $new_uuids_str = "";
    $db_remoter = new mysqli(Encryption::decrypt($hostname), Encryption::decrypt($username), Encryption::decrypt($password)
        , Encryption::decrypt($remoter_dbname));
    if ($db_remoter->connect_error) {
        die('Unable to connect to database: ' . $db_remoter->connect_error);
    }
    $sql = "SELECT S.NewSampleUUID, S.isEditParentUUID, S.isEditInitialSamUUID, S.isEditPatientID ".
        "FROM PDXParameters AS P LEFT JOIN PDXSuccessResults AS S ON P.JobID = S.JobID ".
        "WHERE P.AccountID=? AND P.Tag='1' AND P.DataType='sample'";
    if ($result = $db_remoter->prepare($sql)) {
        $result->bind_param("i", $_SESSION["user_id"]);
        $result->execute();
        $result->bind_result($newuuid, $isEditParent,$isEditInitial,$isEditPatient);
        $isfirst = 1;
        while ($row = $result->fetch()) {
            if($isfirst == 1){
                $new_uuids_str .= "\"".$newuuid."\"";
                $isfirst = 0;
            }else{
                $new_uuids_str .= ",\"".$newuuid."\"";
            }
        }
        $result->close();
    }
    $db_remoter->close();
    if($new_uuids_str !== ""){
        $extraCondition = "`{$table}`.`{$primaryKey}` IN ({$new_uuids_str}) AND `{$table}`.`isDelete` = 0 ";
    }else{
        $extraCondition = "`{$table}`.`{$primaryKey}` is NULL";
    }
}elseif(strpos($uuid,',') !== FALSE){
    $uuidArr=explode(",",$uuid);
    $uuidsStr="";
    $isfirst=1;
    foreach ($uuidArr as $k=>$v){
        if($isfirst===1){
            $uuidsStr.="\"".$v."\"";
            $isfirst=0;
        }else{
            $uuidsStr.=",\"".$v."\"";
        }
    }
    $extraCondition = "`{$table}`.`{$primaryKey}` IN (".$uuidsStr.") AND `{$table}`.`isDelete` = 0 ";
}elseif($uuid !== null){
    $extraCondition = "`{$table}`.`{$primaryKey}` = \"".$uuid."\" AND `{$table}`.`isDelete` = 0 ";
}else{
    $extraCondition ="`{$table}`.`isDelete` = 0 ";
}

$extraCondition.=$isInitialSamCondition.$hasFaMotherSamCondition.$patientIsNullCondition.$parentIsNullCondition.$projectCondition;

echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraCondition)
);
