<?php

session_start();

// Authenticate user session
include "authenticate_user_session.php";

$root_dir="..";
$php_class_dir=$root_dir."/php_class";
require_once ("{$php_class_dir}/PatientID.inc");
require_once ("{$php_class_dir}/EscapeString.inc");
require_once("{$php_class_dir}/dbencryt.inc");
require_once("{$root_dir}/dbpdx.inc");

// Escape special characters in inputs
foreach ($_POST as $k => $v){
    if(gettype($v)==="array"){
        foreach ($v as $k1 => $v1){
            $escape=EscapeString::escape($v1);
            $_POST[$k][$k1]=(isset($_POST[$k]) && isset($_POST[$k][$k1]) && $escape!=="")?$escape:null;
        }
    }else{
        $escape=EscapeString::escape($v);
        $_POST[$k]=(isset($_POST[$k]) && $escape!=="")?$escape:null;
    }
}

$method=(isset($_POST['method']) && $_POST['method']!=="")?$_POST['method']:null;
if($method=== "1"){
    // check required fields
    $isErrLocOpenPat=0;

    $localPatId=$_POST['localPatientId'];
    $openPatId=$_POST['openSpecPatientId'];
    if($localPatId===null && $openPatId===null) $isErrLocOpenPat=1;

    if($isErrLocOpenPat===1){
        header("Location:{$root_dir}/create_patient.php?isErrLocOpenPat={$isErrLocOpenPat}");
    }else{
        // check optional fields
        $sex=$_POST['sex'];
        $race=$_POST['race'];
        $ethic=$_POST['ethic'];
        $death=$_POST['death'];
        $ageDiagMon=$_POST['ageDiagMon'];
        $ageDiagYrOld=$_POST['ageDiagYrOld'];
        $ageDiagYrAd=$_POST['ageDiagYrAd'];
        $metaDiag=$_POST['metaDiag'];
        $finalDiag=$_POST['finalDiag'];
        $therapy_arr=$_POST['therapy'];
        $priTumorType=1;
        $priTumorSite=$_POST['priTumorSite'];
        $priTumorLater=$_POST['priTumorLater'];
        $priTumorDir=$_POST['priTumorDir'];
        $metTumorType=2;
        $metTumorSite=$_POST['metTumorSite'];
        $metTumorLater=$_POST['metTumorLater'];
        $metTumorDir=$_POST['metTumorDir'];
        $fPid=$_POST['fatherPid'];
        $mPid=$_POST['motherPid'];
        $notes=$_POST['notes'];

        // Database connection
        $db = new mysqli(Encryption::decrypt($hostname),Encryption::decrypt($username),Encryption::decrypt($password),Encryption::decrypt($newdbname));
        if($db->connect_error){
            die('Unable to connect to database: ' . $db->connect_error);
        }
        $db->set_charset("utf8");

        try{
            $db->begin_transaction();
            $isError = false;
            // Insert Patient Into Database
            $sysPid = PatientID::generatePatientID($db,true);
            $sql="INSERT INTO Patient (Patient_ID,Local_Patient_ID,OpenSpecimen_Patient_ID,Father_Patient_ID,Mother_Patient_ID, "
                ."Age_At_Diagnosis_In_Months,Age_At_Diagnosis_In_Year_Old,Age_At_Diagnosis_in_AD_Year,Sex,Race,Ethnic, "
                ."Vital_Status,Metastatic_At_Diagnosis, Note,CreateTime) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,NOW())";

            if($result = $db->prepare($sql)){
                $result->bind_param("ssssssssssssss",
                    $sysPid,$localPatId,$openPatId,$fPid,$mPid,$ageDiagMon,$ageDiagYrOld,$ageDiagYrAd,$sex,$race
                    ,$ethic,$death,$metaDiag,$notes);
                if(!$result->execute()){
                    $isError=true;
                }
                $result->close();
            }

            // Insert Final Diagnosis Into Database
            if($finalDiag!==null){
                $sql="INSERT INTO Diagnosis (Patient_ID,Diagnosis,isFinalDiagnosis,CreateTime) VALUES (?,?,1,NOW())";
                if($result = $db->prepare($sql)){
                    $result->bind_param("ss",$sysPid,$finalDiag);
                    if(!$result->execute()){
                        $isError=true;
                    }
                    $result->close();
                }
            }

            // Insert Therapy Into Database
            $sql="INSERT INTO Therapy (Patient_ID,Therapy,CreateTime) VALUES (?,?,NOW())";
            if($result = $db->prepare($sql)){
                foreach($therapy_arr as $val){
                    $result->bind_param("ss",$sysPid,$val);
                    if(!$result->execute()){
                        $isError=true;
                    }
                }
                $result->close();
            }


            // Insert Tumor Into Database
            $sql="INSERT INTO Tumor (Patient_ID,Tumor_Type,Site,Site_Laterality,Site_Direction,CreateTime) VALUES (?,?,?,?,?,NOW())";
            if($result = $db->prepare($sql)){
                if($priTumorSite!==null || $priTumorLater!==null || $priTumorDir!==null){
                    $result->bind_param("sssss",$sysPid,$priTumorType,$priTumorSite,$priTumorLater,$priTumorDir);
                    if(!$result->execute()){
                        $isError=true;
                    }
                }
                if($metTumorSite!==null || $metTumorLater!==null || $metTumorDir!==null){
                    $result->bind_param("sssss",$sysPid,$metTumorType,$metTumorSite,$metTumorLater,$metTumorDir);
                    if(!$result->execute()){
                        $isError=true;
                    }
                }
                $result->close();
            }
            if($isError){
                $db->rollback();
            }else{
                $db->commit();
            }
        }catch(PDOException $e){
            $db->rollback();
        }
        $db->close();
        header("Location:{$root_dir}/patientlist.php?operate=view&pid={$sysPid}");
    }
}

