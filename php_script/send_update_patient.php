<?php
session_start();
include "authenticate_user_session.php";

$root_dir="..";
$php_class_dir=$root_dir."/php_class";
require("{$root_dir}/dbpdx.inc");
require("{$php_class_dir}/dbencryt.inc");
require("{$php_class_dir}/EscapeString.inc");
require("{$php_class_dir}/ChangeHistory.inc");
require_once ("{$php_class_dir}/PatientID.inc");


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

// check required fields
$isErrPid=0;
$isErrLocOpenPat=0;

$pid=$_POST['sysPatientId'];
if($pid===null) {$pid="";$isErrPid=1;}

$localPatId=$_POST['localPatientId'];
$openPatId=$_POST['openSpecPatientId'];
if($localPatId===null && $openPatId===null) $isErrLocOpenPat=1;

if($isErrPid==1 || $isErrLocOpenPat==1){
    header("Location:{$root_dir}/patient.php?operate=edit&pid={$pid}&isErrPid={$isErrPid}&isErrLocOpenPat={$isErrLocOpenPat}");
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
    $inTherapyArr=$_POST['therapy'];
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

    $db = new mysqli(Encryption::decrypt($hostname),Encryption::decrypt($username),Encryption::decrypt($password),Encryption::decrypt($newdbname));
    if($db->connect_error){
        die('Unable to connect to database: ' . $db->connect_error);
    }
    $db->set_charset("utf-8");

    // record change history
    $changeHistory = new ChangeHistory($db);
    $table="Patient";
    $priKey="Patient_ID";
    $changeHistory->recordChangeHistory($table,$priKey,$pid,"Local_Patient_ID",$localPatId,$_SESSION["user_id"]);
    $changeHistory->recordChangeHistory($table,$priKey,$pid,"OpenSpecimen_Patient_ID",$openPatId,$_SESSION["user_id"]);
    $changeHistory->recordChangeHistory($table,$priKey,$pid,"Father_Patient_ID",$fPid,$_SESSION["user_id"]);
    $changeHistory->recordChangeHistory($table,$priKey,$pid,"Mother_Patient_ID",$mPid,$_SESSION["user_id"]);
    $changeHistory->recordChangeHistory($table,$priKey,$pid,"Age_At_Diagnosis_In_Months",$ageDiagMon,$_SESSION["user_id"]);
    $changeHistory->recordChangeHistory($table,$priKey,$pid,"Age_At_Diagnosis_In_Year_Old",$ageDiagYrOld,$_SESSION["user_id"]);
    $changeHistory->recordChangeHistory($table,$priKey,$pid,"Age_At_Diagnosis_in_AD_Year",$ageDiagYrAd,$_SESSION["user_id"]);
    $changeHistory->recordChangeHistory($table,$priKey,$pid,"Sex",$sex,$_SESSION["user_id"]);
    $changeHistory->recordChangeHistory($table,$priKey,$pid,"Race",$race,$_SESSION["user_id"]);
    $changeHistory->recordChangeHistory($table,$priKey,$pid,"Ethnic",$ethic,$_SESSION["user_id"]);
    $changeHistory->recordChangeHistory($table,$priKey,$pid,"Vital_Status",$death,$_SESSION["user_id"]);
    $changeHistory->recordChangeHistory($table,$priKey,$pid,"Metastatic_At_Diagnosis",$metaDiag,$_SESSION["user_id"]);
    $changeHistory->recordChangeHistory($table,$priKey,$pid,"Note",$notes,$_SESSION["user_id"]);

    try{
        $db->begin_transaction();
        $isError = false;
        // update patient record in database
        $sql="UPDATE Patient SET Local_Patient_ID = ? ,OpenSpecimen_Patient_ID = ?,Father_Patient_ID = ?, "
            ."Mother_Patient_ID = ? ,Age_At_Diagnosis_In_Months = ?,Age_At_Diagnosis_In_Year_Old = ?, "
            ."Age_At_Diagnosis_in_AD_Year = ?,Sex  = ?,Race  = ?,Ethnic  = ?,Vital_Status  = ?, "
            ."Metastatic_At_Diagnosis = ?, Note= ? WHERE Patient_ID = ?";

        if($result = $db->prepare($sql)){
            $result->bind_param("ssssssssssssss",
                $localPatId,$openPatId,$fPid,$mPid,$ageDiagMon,$ageDiagYrOld,$ageDiagYrAd,$sex,$race,$ethic
                ,$death,$metaDiag,$notes,$pid);
            if(!$result->execute()){
                $isError=true;
            }
            $result->close();
        }

        // update or insert final diagnosis record in database
        // check whether final diagnosis exists in db or not
        $oriDiagId=$oriDiag=null;
        $sql="SELECT Diagnosis_ID, Diagnosis FROM Diagnosis WHERE Patient_ID = ? AND isFinalDiagnosis = 1 AND isDelete = 0";
        if($result = $db->prepare($sql)){
            $result->bind_param("s", $pid);
            if(!$result->execute()){
                $isError=true;
            }else{
                $result->bind_result($oriDiagId, $oriDiag);
                $result->fetch();
            }
            $result->close();
        }
        // final diagnosis exists in db
        $table="Diagnosis";
        $priKey="Diagnosis_ID";
        if($oriDiagId!==null){
            if($finalDiag===null){
                $sql="DELETE FROM Diagnosis WHERE Diagnosis_ID = ?";
                if($result = $db->prepare($sql)){
                    $result->bind_param("s",$oriDiagId);
                    if(!$result->execute()){
                        $isError=true;
                    }
                    $result->close();
                }
            }else{
                $isFinalDiagChange =
                    $changeHistory->recordChangeHistory($table,$priKey,$oriDiagId,"Diagnosis",$finalDiag,$_SESSION["user_id"]);
                if($isFinalDiagChange){
                    $sql="UPDATE Diagnosis SET Diagnosis =? WHERE Patient_ID = ? AND isFinalDiagnosis = 1 AND isDelete = 0";
                    if($result = $db->prepare($sql)){
                        $result->bind_param("ss",$finalDiag, $pid);
                        if(!$result->execute()){
                            $isError=true;
                        }
                        $result->close();
                    }
                }
            }
        // final diagnosis doesn't exist in db
        }else{
            if($finalDiag!==null){
                $sql="INSERT INTO Diagnosis (Patient_ID, Diagnosis, isFinalDiagnosis, isDelete, CreateTime) "
                    ."VALUES (?,?,1,0,NOW())";
                if($result = $db->prepare($sql)){
                    $result->bind_param("ss",$pid,$finalDiag);
                    if(!$result->execute()){
                        $isError=true;
                    }
                    $result->close();
                }
            }
        }

        // update therapy record(s) in database
        $sql="SELECT Therapy FROM Therapy WHERE Patient_ID = ? AND isDelete = 0";
        if($result = $db->prepare($sql)){
            $result->bind_param("s",$pid);
            $result->execute();
            $result->bind_result($dbTherapy);
            $dbTherapyArr = [];
            while($result->fetch()){
                array_push($dbTherapyArr,$dbTherapy);
            }
            $result->close();

            // check whether input therapies are in $dbTherapyArr
            $existTherapyArr=[];
            foreach ($inTherapyArr as $inV){
                // if yes, store values into exist_arr
                if(in_array($inV,$dbTherapyArr)){
                    array_push($existTherapyArr,$inV);
                // if no, insert new therapy record into db
                }else{
                    $sql="INSERT INTO Therapy (Patient_ID,Therapy,CreateTime) VALUES (?,?,NOW())";
                    if($result = $db->prepare($sql)){
                        $result->bind_param("ss",$pid,$inV);
                        if(!$result->execute()){
                            $isError=true;
                        }
                        $result->close();
                    }
                }

            }

            // check whether db therapies are in $inTherapyArr
            foreach ($dbTherapyArr as $dbV){
                // if no, delete the old therapy record in db
                if(!in_array($dbV,$existTherapyArr)){
                    $sql="DELETE FROM Therapy WHERE Patient_ID=? AND Therapy=?";
                    if($result=$db->prepare($sql)){
                        $result->bind_param("ss",$pid,$dbV);
                        if(!$result->execute()){
                            $isError=true;
                        }
                        $result->close();
                    }
                }
            }
        }

        // update tumor record(s) in database
        // check whether tumor records exist in db or not
        $table="Tumor";
        $priKey="ID";
        $sql="SELECT ID, Tumor_Type, Site, Site_Laterality, Site_Direction FROM Tumor WHERE Patient_ID = ? AND isDelete = 0";
        if($result = $db->prepare($sql)) {
            $result->bind_param("s", $pid);
            $result->execute();
            $result->bind_result($dbIdTumor,$dbTumorType, $dbTumorSite, $dbTumorLater, $dbTumorDir);
            $dbTumor = [(string)$priTumorType=>null,(string)$metTumorType=>null];
            while($result->fetch()){
                if($dbTumorType==$priTumorType){
                    $dbTumor[(string)$priTumorType]=["id"=>$dbIdTumor,"site"=>$dbTumorSite,"later"=>$dbTumorLater,"dir"=>$dbTumorDir];
                }
                if($dbTumorType==$metTumorType){
                    $dbTumor[(string)$metTumorType]=["id"=>$dbIdTumor,"site"=>$dbTumorSite,"later"=>$dbTumorLater,"dir"=>$dbTumorDir];
                }
            }
            $result->close();

            // primary tumor records exist in db
            if($dbTumor[(string)$priTumorType]!==null){
                $dbPriTumorId = $dbTumor[(string)$priTumorType]["id"];
                $dbPriTumorSite = $dbTumor[(string)$priTumorType]["site"];
                $dbPriTumorLater = $dbTumor[(string)$priTumorType]["later"];
                $dbPriTumorDir = $dbTumor[(string)$priTumorType]["dir"];
                if($priTumorSite===null && $priTumorLater===null && $priTumorDir===null){
                    $sql="DELETE FROM Tumor WHERE ID = ?";
                    if($result = $db->prepare($sql)) {
                        $result->bind_param("s",$dbPriTumorId);
                        if(!$result->execute()){
                            $isError=true;
                        }
                        $result->close();
                    }
                }else{
                    if($priTumorSite!==$dbPriTumorSite || $priTumorLater!==$dbPriTumorLater || $priTumorDir!==$dbPriTumorDir){
                        $changeHistory->recordChangeHistory($table,$priKey,$dbPriTumorId,"Site",$priTumorSite,$_SESSION["user_id"]);
                        $changeHistory->recordChangeHistory($table,$priKey,$dbPriTumorId,"Site_Laterality",$priTumorLater,$_SESSION["user_id"]);
                        $changeHistory->recordChangeHistory($table,$priKey,$dbPriTumorId,"Site_Direction",$priTumorDir,$_SESSION["user_id"]);
                        $sql="UPDATE Tumor SET Site = ?, Site_Laterality = ?, Site_Direction = ? WHERE Patient_ID = ? AND Tumor_Type = ?";
                        if($result = $db->prepare($sql)) {
                            $result->bind_param("sssss",$priTumorSite,$priTumorLater,$priTumorDir,$pid,$priTumorType);
                            if(!$result->execute()){
                                $isError=true;
                            }
                            $result->close();
                        }
                    }
                }
            // primary tumor records don't exist in db
            }else{
                if($priTumorSite!==null || $priTumorLater!==null || $priTumorDir!==null){
                    $sql="INSERT INTO Tumor (Patient_ID,Tumor_Type,Site,Site_Laterality,Site_Direction,CreateTime) VALUES (?,?,?,?,?,NOW())";
                    if($result = $db->prepare($sql)) {
                        $result->bind_param("sssss",$pid,$priTumorType,$priTumorSite,$priTumorLater,$priTumorDir);
                        if(!$result->execute()){
                            $isError=true;
                        }
                        $result->close();
                    }
                }
            }

            // metastatic tumor records exist in db
            if($dbTumor[(string)$metTumorType]!==null){
                $dbMetTumorId = $dbTumor[(string)$metTumorType]["id"];
                $dbMetTumorSite = $dbTumor[(string)$metTumorType]["site"];
                $dbMetTumorLater = $dbTumor[(string)$metTumorType]["later"];
                $dbMetTumorDir = $dbTumor[(string)$metTumorType]["dir"];
                if($metTumorSite===null && $metTumorLater===null && $metTumorDir===null){
                    $sql="DELETE FROM Tumor WHERE ID = ?";
                    if($result = $db->prepare($sql)) {
                        $result->bind_param("s",$dbMetTumorId);
                        if(!$result->execute()){
                            $isError=true;
                        }
                        $result->close();
                    }
                }else{
                    if($metTumorSite!==$dbMetTumorSite || $metTumorLater!==$dbMetTumorLater || $metTumorDir!==$dbMetTumorDir){
                        $changeHistory->recordChangeHistory($table,$priKey,$dbMetTumorId,"Site",$metTumorSite,$_SESSION["user_id"]);
                        $changeHistory->recordChangeHistory($table,$priKey,$dbMetTumorId,"Site_Laterality",$metTumorLater,$_SESSION["user_id"]);
                        $changeHistory->recordChangeHistory($table,$priKey,$dbMetTumorId,"Site_Direction",$metTumorDir,$_SESSION["user_id"]);
                        $sql="UPDATE Tumor SET Site = ?, Site_Laterality = ?, Site_Direction = ? WHERE Patient_ID = ? AND Tumor_Type = ?";
                        if($result = $db->prepare($sql)) {
                            $result->bind_param("sssss",$metTumorSite,$metTumorLater,$metTumorDir,$pid,$metTumorType);
                            if(!$result->execute()){
                                $isError=true;
                            }
                            $result->close();
                        }
                    }
                }
            // metastatic tumor records don't exist in db
            }else{
                if($metTumorSite!==null || $metTumorLater!==null || $metTumorDir!==null){
                    $sql="INSERT INTO Tumor (Patient_ID,Tumor_Type,Site,Site_Laterality,Site_Direction,CreateTime) VALUES (?,?,?,?,?,NOW())";
                    if($result = $db->prepare($sql)) {
                        $result->bind_param("sssss",$pid,$metTumorType,$metTumorSite,$metTumorLater,$metTumorDir);
                        if(!$result->execute()){
                            $isError=true;
                        }
                        $result->close();
                    }
                }
            }
        }

        if($isError){
            $db->rollback();
        }else{
            $db->commit();
        }
    }catch(PDOException $e){
        $db->rollback();
    }finally{
        $changeHistory=null;
        $db->close();
    }

    header("Location:{$root_dir}/patientlist.php?operate=view&pid={$pid}");
}

