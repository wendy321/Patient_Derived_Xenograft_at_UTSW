<?php
session_start();
include "authenticate_user_session.php";

$root_dir="..";
$php_class_dir=$root_dir."/php_class";
require("{$root_dir}/dbpdx.inc");
require("{$php_class_dir}/dbencryt.inc");
require("{$php_class_dir}/ChangeHistory.inc");
require("{$php_class_dir}/EscapeString.inc");
require("{$php_class_dir}/SampleID.inc");
require("{$php_class_dir}/FatherMotherDataUpdateForSample.inc");

function convertToDatabaseDateTime($date){
    return $date===null?null:"{$date} 00:00:00";
}

function changeHistoryUpdateDataOfSamples($db,$changeHistoryObj,$isVariableChange,$table,$primaryKey,$uuids,$variableName,
                                          $variableNewVal,$userId){
    if($isVariableChange){
        $changeHistoryObj->recordChangeHistory($table,$primaryKey,$uuids,$variableName,$variableNewVal,$userId);
        $sql = "UPDATE ".$table." SET ".$variableName." = \"".$variableNewVal."\" WHERE UUID IN (".$uuids.")";
        if($result = $db->prepare($sql)){
            $result->execute();
            $result->close();
        }
    }
}

// Escape special characters in inputs
foreach ($_POST as $k => $v){
    $escape=EscapeString::escape($v);
    $_POST[$k]=(isset($_POST[$k]) && $escape!=="")?$escape:null;
}

// check required fields
$isErrUuid=0;
$isErrIniSam=0;
$isErrLocOpenSam=0;
$isErrSpecTyp=0;
$isErrSamClass=0;
$isErrSamSrc=0;

$uuid=$_POST['uuid'];
if($uuid===null) {$uuid="";$isErrUuid=1;}

$isIniSam=$_POST['isInitialSample'];
if($isIniSam===null) $isErrIniSam=1;

$localSamId=$_POST['localSampleId'];
$openSamId=$_POST['openSpecSampleId'];
if($localSamId===null && $openSamId===null) $isErrLocOpenSam=1;

$specimenType=$_POST['specimenType'];
if($specimenType==null) $isErrSpecTyp=1;

$samClass=$_POST['sampleClass'];
if($samClass==null) $isErrSamClass=1;

$samSrc=$_POST['sampleSrc'];
if($samSrc==null) $isErrSamSrc=1;

if($isErrUuid==1 || $isErrIniSam==1 || $isErrLocOpenSam==1){
    header("Location:{$root_dir}/sample.php?operate=edit&uuid={$uuid}&errUuid={$isErrUuid}&errIniSam={$isErrIniSam}".
        "&errLocOpenSam={$isErrLocOpenSam}&errSpecTyp={$isErrSpecTyp}&errSamClass={$isErrSamClass}&errSamSrc={$isErrSamSrc}");
}else{

    $db = new mysqli(Encryption::decrypt($hostname),Encryption::decrypt($username),Encryption::decrypt($password),Encryption::decrypt($newdbname));
    if($db->connect_error){
        die('Unable to connect to database: ' . $db->connect_error);
    }
    $db->set_charset("utf-8");

    // check optional fields
    $sampleId=$_POST['sysSampleId'];
    $parentSamUuid=$_POST['parentSampleUuid'];
    $samDerDate=convertToDatabaseDateTime($_POST['sampleDerivedDate']);
    $iniSamUuidDB=$_POST['initialSampleUuidDB'];
    $samType=$_POST['sampleType'];
    if($samType===null){
        switch($samClass){
            case "01": $samType="19";break;
            case "02": $samType="29";break;
            case "03": $samType="39";break;
            case "04": $samType="49";break;
            case "05": $samType="59";break;
            case "06": $samType="699";break;
            case "98": $samType="98";break;
            case "99":$samType="99"; break;
            default:$samType=null;
        }
    }
    $samPrep=$_POST['samplePre'];
    $exiBarcode=$_POST['existedBarcode'];
    $sysPatId=$_POST['systemPatientId'];
    $fatherSamUuid = $_POST['fatherSampleUuid'];
    $motherSamUuid = $_POST['motherSampleUuid'];
    $proceType=$_POST['procedureType'];
    $proceDate=convertToDatabaseDateTime($_POST['procedureDate']);
    $priTumorSite=$_POST['priTumorSite'];
    $priTumorLater=$_POST['priTumorLater'];
    $priTumorDir=$_POST['priTumorDir'];
    $quanNum=$_POST['quantityNum'];
    $quanUnit=$_POST['quantityUnit'];
    $concenNum=$_POST['concenNum'];
    $concenUnit=$_POST['concenUnit'];
    $bank=$_POST['bank'];
    $room=$_POST['room'];
    $cabType=$_POST['containerType'];
    $cabTemp=$_POST['containerTemp'];
    $cabNum=$_POST['containerNum'];
    $shelfNum=$_POST['shelfNum'];
    $rackNum=$_POST['rackNum'];
    $boxNum=$_POST['boxNum'];
    $posNum=$_POST['posNum'];
    $posTxT=$_POST['posTxt'];
    $shipDate=convertToDatabaseDateTime($_POST['shipDate']);
    $pdxGenDate=convertToDatabaseDateTime($_POST['pdxGenDate']);
    $pdxRecDate=convertToDatabaseDateTime($_POST['pdxRecDate']);
    $institute=$_POST['institute'];
    $notes=$_POST['notes'];

    // record change history of current sample
    $changeHistory = new ChangeHistory($db);
    $table="Sample";
    $priKey="UUID";
    $changeHistory->recordChangeHistory($table,$priKey,$uuid,"Existed_Barcode",$exiBarcode,$_SESSION["user_id"]);
    $changeHistory->recordChangeHistory($table,$priKey,$uuid,"Local_Sample_ID",$localSamId,$_SESSION["user_id"]);
    $changeHistory->recordChangeHistory($table,$priKey,$uuid,"OpenSpecimen_Sample_ID",$openSamId,$_SESSION["user_id"]);
    $isSysPidChange=$changeHistory->recordChangeHistory($table,$priKey,$uuid,"Patient_ID",$sysPatId,$_SESSION["user_id"]);
    $changeHistory->recordChangeHistory($table,$priKey,$uuid,"Father_UUID",$fatherSamUuid,$_SESSION["user_id"]);
    $changeHistory->recordChangeHistory($table,$priKey,$uuid,"Mother_UUID",$motherSamUuid,$_SESSION["user_id"]);
    $changeHistory->recordChangeHistory($table,$priKey,$uuid,"Sample_Contributor_Institute_ID",$institute,$_SESSION["user_id"]);
    $isSamSrcChange=$changeHistory->recordChangeHistory($table,$priKey,$uuid,"Sample_Source",(int)$samSrc,$_SESSION["user_id"]);
    $isProceTypeChange=$changeHistory->recordChangeHistory($table,$priKey,$uuid,"Procedure_Type",$proceType,$_SESSION["user_id"]);
    $isProceDateChange=$changeHistory->recordChangeHistory($table,$priKey,$uuid,"Date_Procedure",$proceDate,$_SESSION["user_id"]);
    $changeHistory->recordChangeHistory($table,$priKey,$uuid,"Initial_Sample_UUID",$iniSamUuidDB,$_SESSION["user_id"]);
    $changeHistory->recordChangeHistory($table,$priKey,$uuid,"Parent_UUID",$parentSamUuid,$_SESSION["user_id"]);
    $changeHistory->recordChangeHistory($table,$priKey,$uuid,"Date_Derive_From_Parent",$samDerDate,$_SESSION["user_id"]);
    $isSpecTypeChange=$changeHistory->recordChangeHistory($table,$priKey,$uuid,"Specimen_Type",(int)$specimenType,$_SESSION["user_id"]);
    $isSamClassChange=$changeHistory->recordChangeHistory($table,$priKey,$uuid,"Sample_Class",(int)$samClass,$_SESSION["user_id"]);
    $changeHistory->recordChangeHistory($table,$priKey,$uuid,"Sample_Type",$samType,$_SESSION["user_id"]);
    $changeHistory->recordChangeHistory($table,$priKey,$uuid,"Storage_Bank",$bank,$_SESSION["user_id"]);
    $changeHistory->recordChangeHistory($table,$priKey,$uuid,"Storage_Room",$room,$_SESSION["user_id"]);
    $changeHistory->recordChangeHistory($table,$priKey,$uuid,"Cabinet_Type",$cabType,$_SESSION["user_id"]);
    $changeHistory->recordChangeHistory($table,$priKey,$uuid,"Cabinet_Temperature",$cabTemp,$_SESSION["user_id"]);
    $changeHistory->recordChangeHistory($table,$priKey,$uuid,"Cabinet_Number",$cabNum,$_SESSION["user_id"]);
    $changeHistory->recordChangeHistory($table,$priKey,$uuid,"Shelf_Number",$shelfNum,$_SESSION["user_id"]);
    $changeHistory->recordChangeHistory($table,$priKey,$uuid,"Rack_Number",$rackNum,$_SESSION["user_id"]);
    $changeHistory->recordChangeHistory($table,$priKey,$uuid,"Box_Number",$boxNum,$_SESSION["user_id"]);
    $changeHistory->recordChangeHistory($table,$priKey,$uuid,"Position_Number",$posNum,$_SESSION["user_id"]);
    $changeHistory->recordChangeHistory($table,$priKey,$uuid,"Position_Text",$posTxT,$_SESSION["user_id"]);
    $changeHistory->recordChangeHistory($table,$priKey,$uuid,"Quantity_Value",$quanNum,$_SESSION["user_id"]);
    $changeHistory->recordChangeHistory($table,$priKey,$uuid,"Quantity_Unit",$quanUnit,$_SESSION["user_id"]);
    $changeHistory->recordChangeHistory($table,$priKey,$uuid,"Concentration_Value",$concenNum,$_SESSION["user_id"]);
    $changeHistory->recordChangeHistory($table,$priKey,$uuid,"Concentration_Unit",$concenUnit,$_SESSION["user_id"]);
    $changeHistory->recordChangeHistory($table,$priKey,$uuid,"Sample_Prep",$samPrep,$_SESSION["user_id"]);
    $isSiteChange=$changeHistory->recordChangeHistory($table,$priKey,$uuid,"Anatomical_Site",$priTumorSite,$_SESSION["user_id"]);
    $isLaterChange=$changeHistory->recordChangeHistory($table,$priKey,$uuid,"Anatomical_Laterality",$priTumorLater,$_SESSION["user_id"]);
    $isDirChange=$changeHistory->recordChangeHistory($table,$priKey,$uuid,"Anatomical_Direction",$priTumorDir,$_SESSION["user_id"]);
    $isShipDateChange=$changeHistory->recordChangeHistory($table,$priKey,$uuid,"Date_Sample_Ship",$shipDate,$_SESSION["user_id"]);
    $isPDXGenDateChange=$changeHistory->recordChangeHistory($table,$priKey,$uuid,"Date_PDX_Sample_Generate",$pdxGenDate,$_SESSION["user_id"]);
    $isPDXRecDateChange=$changeHistory->recordChangeHistory($table,$priKey,$uuid,"Date_PDX_Sample_Receive",$pdxRecDate,$_SESSION["user_id"]);
    $changeHistory->recordChangeHistory($table,$priKey,$uuid,"Notes",$notes,$_SESSION["user_id"]);
    $changeHistory->recordChangeHistory($table,$priKey,$uuid,"isInitialSample",$isIniSam,$_SESSION["user_id"]);
    if($isSysPidChange || $isSpecTypeChange || $isSamClassChange || $isSamSrcChange){
        $sampleId=SampleID::generateSampleID($db,$sysPatId,$specimenType,$samClass,$samSrc);
    }
    $changeHistory->recordChangeHistory($table,$priKey,$uuid,"Sample_ID",$sampleId,$_SESSION["user_id"]);
    $changeHistory=null;

    // update sample record in database
    $sql="UPDATE Sample SET Existed_Barcode = ?,Sample_ID = ?,Local_Sample_ID = ?,OpenSpecimen_Sample_ID = ?,Patient_ID = ?,"
        ."Sample_Contributor_Institute_ID = ?,Sample_Source = ?,Procedure_Type = ?,Date_Procedure = ?,Initial_Sample_UUID = ?,"
        ."Parent_UUID = ?,Date_Derive_From_Parent= ?, Specimen_Type = ?,Sample_Class = ?,Sample_Type = ?,"
        ."Storage_Bank = ?,Storage_Room = ? ,Cabinet_Type = ?,Cabinet_Temperature = ?,Cabinet_Number = ?,Shelf_Number = ?,"
        ."Rack_Number = ?,Box_Number = ? ,Position_Number = ?,Position_Text = ?,"
        ."Quantity_Value = ?,Quantity_Unit = ?,Concentration_Value = ?,Concentration_Unit = ?,Sample_Prep = ?,"
        ."Anatomical_Site = ?,Anatomical_Laterality = ?,Anatomical_Direction = ?,"
        ."Date_Sample_Ship = ?,Date_PDX_Sample_Generate = ?,Date_PDX_Sample_Receive = ?,Notes = ?,isInitialSample = ? "
        ." WHERE UUID = ?";


    if($result = $db->prepare($sql)){
        $result->bind_param("sssssssssssssssssssssssssssssssssssssss",
            $exiBarcode,$sampleId,$localSamId,$openSamId,$sysPatId,
            $institute,$samSrc,$proceType,$proceDate,$iniSamUuidDB,
            $parentSamUuid,$samDerDate,$specimenType,$samClass,$samType,
            $bank,$room,$cabType,$cabTemp,$cabNum,$shelfNum,$rackNum,$boxNum,$posNum,$posTxT,
            $quanNum,$quanUnit,$concenNum,$concenUnit,$samPrep,
            $priTumorSite,$priTumorLater,$priTumorDir,
            $shipDate,$pdxGenDate,$pdxRecDate,$notes,$isIniSam,$uuid);
        $result->execute();
        $result->close();
    }

    // if current sample's patient_id is changed, check and operate father & mother data of this sample
    if($isSysPidChange){
        FatherMotherDataUpdateForSample::operateFatherMotherDataWhenPatientIdChangeInSample($db, $sysPatId, $uuid);
    }

    // if procedure-related values, specimen_type, site-related values, ship date, pdx-related dates, or patient_id
    // of the current sample are changed, these values of children also need to be changed.
    if($isProceTypeChange || $isProceDateChange || $isSiteChange || $isLaterChange || $isDirChange
        || $isShipDateChange || $isPDXGenDateChange || $isPDXRecDateChange || $isSysPidChange || $isSpecTypeChange){

        // find children/derived samples of the current sample
        $childUuid = null;
        $childUuids = null;
        $parentUuids = null;
        $allChildUuids = null;
        $curDepth = 1;
        $maxDepth = 4;
        while($curDepth < $maxDepth){
            if($curDepth==1){
                $sql = "SELECT UUID FROM Sample WHERE Initial_Sample_UUID =? OR Parent_UUID = ?";
                if($result = $db->prepare($sql)){
                    $result->bind_param("ss",$uuid,$uuid);
                    $result->execute();
                    $result->bind_result($childUuid);
                    while($result->fetch()){
                        if($childUuids===null){
                            $childUuids.="\"".$childUuid."\"";
                        }else{
                            $childUuids.=",\"".$childUuid."\"";
                        }
                    }
                    $result->close();
                    if($childUuids===null){
                        break;
                    }else{
                        $parentUuids = $childUuids;
                        $allChildUuids = $childUuids;
                        $childUuids = null;
                    }
                }
            }else{
                $sql = "SELECT UUID FROM Sample WHERE UUID NOT IN (".$parentUuids.") AND Parent_UUID IN (".$parentUuids.")";
                if($result = $db->prepare($sql)){
                    $result->execute();
                    $result->bind_result($childUuid);
                    while($result->fetch()){
                        if($childUuids===null){
                            $childUuids.="\"".$childUuid."\"";
                        }else{
                            $childUuids.=",\"".$childUuid."\"";
                        }
                    }
                    $result->close();
                    if($childUuids===null){
                        break;
                    }else{
                        $parentUuids = $childUuids;
                        $allChildUuids .= ",".$childUuids;
                        $childUuids = null;
                    }
                }
            }
            $curDepth++;
        }

        // if children/ derived samples are found, record change history and update records.
        if($allChildUuids!==null){
            $changeHis = new ChangeHistory($db);
            $table="Sample";
            $priKey="UUID";
            // record change history and update for NON sample_id-related variables
            changeHistoryUpdateDataOfSamples($db,$changeHis,$isProceTypeChange,$table,$priKey,$allChildUuids,"Procedure_Type",$proceType,$_SESSION["user_id"]);
            changeHistoryUpdateDataOfSamples($db,$changeHis,$isProceDateChange,$table,$priKey,$allChildUuids,"Date_Procedure",$proceDate,$_SESSION["user_id"]);
            changeHistoryUpdateDataOfSamples($db,$changeHis,$isSiteChange,$table,$priKey,$allChildUuids,"Anatomical_Site",$priTumorSite,$_SESSION["user_id"]);
            changeHistoryUpdateDataOfSamples($db,$changeHis,$isLaterChange,$table,$priKey,$allChildUuids,"Anatomical_Laterality",$priTumorLater,$_SESSION["user_id"]);
            changeHistoryUpdateDataOfSamples($db,$changeHis,$isDirChange,$table,$priKey,$allChildUuids,"Anatomical_Direction",$priTumorDir,$_SESSION["user_id"]);
            changeHistoryUpdateDataOfSamples($db,$changeHis,$isShipDateChange,$table,$priKey,$allChildUuids,"Date_Sample_Ship",$shipDate,$_SESSION["user_id"]);
            changeHistoryUpdateDataOfSamples($db,$changeHis,$isPDXGenDateChange,$table,$priKey,$allChildUuids,"Date_PDX_Sample_Generate",$pdxGenDate,$_SESSION["user_id"]);
            changeHistoryUpdateDataOfSamples($db,$changeHis,$isPDXRecDateChange,$table,$priKey,$allChildUuids,"Date_PDX_Sample_Receive",$pdxRecDate,$_SESSION["user_id"]);

            // record change history and update data for sample_id-related variables
            if($isSysPidChange || $isSpecTypeChange){
                $allChildUuids_arr = explode(",",$allChildUuids);
                foreach($allChildUuids_arr as $v){
                    $childUuid=str_replace("\"","",$v);
                    // record change history for Patient_ID, Specimen Type
                    $changeHis->recordChangeHistory($table,$priKey,$childUuid,"Patient_ID",$sysPatId,$_SESSION["user_id"]);
                    $changeHis->recordChangeHistory($table,$priKey,$childUuid,"Specimen_Type",(int)$specimenType,$_SESSION["user_id"]);

                    // generate new Sample_ID
                    $sampleId=SampleID::generateSampleIDWiChangePidSpecimenType($db,$sysPatId,$specimenType,$childUuid);
                    // record change history for Sample_ID
                    $changeHis->recordChangeHistory($table,$priKey,$childUuid,"Sample_ID",$sampleId,$_SESSION["user_id"]);

                    // update Patient_ID, Specimen Type, and Sample_ID
                    $sql="UPDATE Sample SET Patient_ID = ?, Specimen_Type = ?, Sample_ID = ? WHERE UUID = ?";
                    if($result = $db->prepare($sql)){
                        $result->bind_param("ssss",$sysPatId,$specimenType,$sampleId,$childUuid);
                        $result->execute();
                        $result->close();
                    }
                }
            }
            $changeHis=null;
        }
    }
    $db->close();
    header("Location:{$root_dir}/samplelist.php?operate=view&uuid={$uuid}");
}

