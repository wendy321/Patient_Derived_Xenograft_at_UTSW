<?php

class FatherMotherDataUpdateForSample
{

    public static function getFatherOrMotherPatientIdByPatientId($db, $patientId){

        $sql="SELECT Father_Patient_ID, Mother_Patient_ID FROM Patient WHERE Patient_ID = ?";
        $fPid=$mPid=null;
        if($result = $db->prepare($sql)){
            $result->bind_param("s", $patientId);
            $result->execute();
            $result->bind_result($fPid,$mPid);
            $result->fetch();
            $result->close();
        }
        return array("fatherPid"=>$fPid,"motherPid"=>$mPid);
    }

    public static function getFatherOrMotherPatientIdBySampleUuid($db, $samUuid){
        $sql="SELECT Father_Sample_UUID, Mother_Sample_UUID FROM ChildFatherMotherSampleMap WHERE Child_Sample_UUID = ?";
        $fUuid=$mUuid=null;
        if($result = $db->prepare($sql)){
            $result->bind_param("s", $samUuid);
            $result->execute();
            $result->bind_result($fUuid,$mUuid);
            $result->fetch();
            $result->close();
        }

        $fPid=null;
        if($fUuid!=null){
            $sql="SELECT Patient_ID FROM Sample WHERE UUID = ?";
            if($result = $db->prepare($sql)){
                $result->bind_param("s", $fUuid);
                $result->execute();
                $result->bind_result($fPid);
                $result->fetch();
                $result->close();
            }
        }

        $mPid=null;
        if($mUuid!=null){
            $sql="SELECT Patient_ID FROM Sample WHERE UUID = ?";
            if($result = $db->prepare($sql)){
                $result->bind_param("s", $mUuid);
                $result->execute();
                $result->bind_result($mPid);
                $result->fetch();
                $result->close();
            }
        }

        return array("fatherPid"=>$fPid,"motherPid"=>$mPid);
    }

    public static function updateFatherMotherPatientIdForPatientId($db, $fatherPatientId, $motherPatientId, $patientId){

        $chaHis = new ChangeHistory($db);
        $chaHis->recordChangeHistory("Patient","Patient_ID",$patientId,
            "Father_Patient_ID",$fatherPatientId,$_SESSION["user_id"]);
        $chaHis->recordChangeHistory("Patient","Patient_ID",$patientId,
            "Mother_Patient_ID",$motherPatientId,$_SESSION["user_id"]);
        $chaHis = null;

        $sql="UPDATE Patient SET Father_Patient_ID = ?, Mother_Patient_ID = ? WHERE Patient_ID = ?";
        if($result = $db->prepare($sql)){
            $result->bind_param("sss", $fatherPatientId, $motherPatientId, $patientId);
            $result->execute();
            $result->close();
        }
    }

    public static function dropRecordInChildFatherMotherSampleMapTableByChildUuid($db, $samUuid){
        $sql="DELETE FROM ChildFatherMotherSampleMap WHERE Child_Sample_UUID = ?";
        if($result = $db->prepare($sql)){
            $result->bind_param("s",$samUuid);
            $result->execute();
            $result->close();
        }
    }

    public static function operateFatherMotherDataWhenPatientIdChangeInSample($db, $patientId, $samUuid){
        $fmPidsPatient = FatherMotherDataUpdateForSample::getFatherOrMotherPatientIdByPatientId($db, $patientId);
        $fPidPatient = $fmPidsPatient["fatherPid"];
        $mPidPatient = $fmPidsPatient["motherPid"];
        if($patientId == null){
            if($fPidPatient!=null || $mPidPatient!=null){
                FatherMotherDataUpdateForSample::dropRecordInChildFatherMotherSampleMapTableByChildUuid($db, $samUuid);
            }
        }else{
            $fmPidsCFMMap = FatherMotherDataUpdateForSample::getFatherOrMotherPatientIdBySampleUuid($db, $samUuid);
            $fPidCFMMap = $fmPidsCFMMap["fatherPid"];
            $mPidCFMMap = $fmPidsCFMMap["motherPid"];
            if($fPidPatient!=null || $mPidPatient!=null){
                if($fPidCFMMap!=null || $mPidCFMMap!=null){
                    FatherMotherDataUpdateForSample::dropRecordInChildFatherMotherSampleMapTableByChildUuid($db, $samUuid);
                }
            }else{
                if($fPidCFMMap!=null || $mPidCFMMap!=null){
                    if(!($patientId==$fPidCFMMap || $patientId==$mPidCFMMap)){
                        FatherMotherDataUpdateForSample::updateFatherMotherPatientIdForPatientId($db, $fPidCFMMap, $mPidCFMMap, $patientId);
                    }
                    FatherMotherDataUpdateForSample::dropRecordInChildFatherMotherSampleMapTableByChildUuid($db, $samUuid);
                }
            }
        }
    }
}