<?php

session_start();
include "authenticate_user_session.php";

$root_dir="..";
$php_class_dir=$root_dir."/php_class";
require("{$root_dir}/dbpdx.inc");
require("{$php_class_dir}/dbencryt.inc");
require("{$php_class_dir}/EscapeString.inc");
require("{$php_class_dir}/ChangeHistory.inc");
require("{$php_class_dir}/SampleID.inc");
require("{$php_class_dir}/FatherMotherDataUpdateForSample.inc");

$db = new mysqli(Encryption::decrypt($hostname),Encryption::decrypt($username),Encryption::decrypt($password),Encryption::decrypt($newdbname));
if($db->connect_error){
    die('Unable to connect to database: ' . $db->connect_error);
}
$db->set_charset("utf-8");

$isError=false;
$errMsg="";
$uuids="";
try {
    $db->begin_transaction();
    $sampleUuids=(isset($_POST["sampleUuid"]))?$_POST["sampleUuid"]:null;
    $patientIds=(isset($_POST["patientId"]))?$_POST["patientId"]:null;
    foreach ($sampleUuids as $k => $v){
        $sampleUuid = (isset($v) && $v!=="")?$v:null;
        if($sampleUuid === null) continue;
        $patientId = (isset($patientIds[$k]) && $patientIds[$k]!="")?$patientIds[$k]:null;
        if($patientId === null) continue;
        $patientId=$patientIds[$k];

        // get new Sample_ID
        $sampleId=null;
        $specType=$samClass=$samSrc="";
        $sql="SELECT Specimen_Type, Sample_Class, Sample_Source FROM Sample WHERE UUID = ?";
        if($result = $db->prepare($sql)){
            $result->bind_param("s",$sampleUuid);
            if(!$result->execute()){
                $result->close();
                $isError=true;
                $errMsg="1";
                break;
            }else{
                $result->bind_result($specType,$samClass,$samSrc);
                $result->fetch();
                $result->close();
                $sampleId = SampleID::generateSampleID($db,$patientId,$specType,$samClass,$samSrc);
                if($sampleId===null){
                    $result->close();
                    $isError=true;
                    $errMsg=$sampleId;
                    break;
                }
            }
        }else{
            $result->close();
            $isError=true;
            $errMsg="3";
            break;
        }

        // record change history
        $changehistory=new ChangeHistory($db);
        $isSysPidChange = $changehistory->recordChangeHistory("Sample","UUID",$sampleUuid,"Patient_ID",$patientId,$_SESSION["user_id"]);
        $changehistory->recordChangeHistory("Sample","UUID",$sampleUuid,"Sample_ID",$sampleId,$_SESSION["user_id"]);
        $changehistory=null;

        // update Patient_ID and Sample_ID of the Sample record
        $sql="UPDATE Sample SET Patient_ID = ?, Sample_ID = ? WHERE UUID = ?";
        if($result = $db->prepare($sql)){
            $result->bind_param("sss", $patientId, $sampleId, $sampleUuid);
            if(!$result->execute()){
                $isError=true;
                $errMsg="4";
                break;
            }else{
                $uuids.=($uuids!=="")?",".$sampleUuid:$sampleUuid;
            }
            $result->close();
        }else{
            $isError=true;
            $errMsg="5";
            break;
        }

        // operate father & mother information, if patient_id changes.
        if($isSysPidChange){
            FatherMotherDataUpdateForSample::operateFatherMotherDataWhenPatientIdChangeInSample($db, $patientId, $sampleUuid);
        }
    }
    if($isError){
        $db -> rollback();
    }else{
        $db -> commit();
    }
}catch(PDOException $e){
    $db->rollback();
}

$db -> close();

if($isError){
    header("Location:{$root_dir}/samplelist.php?operate=view&uuid={$uuids}&error=1&errMsg={$errMsg}");
}else{
    header("Location:{$root_dir}/samplelist.php?operate=view&uuid={$uuids}");
}


