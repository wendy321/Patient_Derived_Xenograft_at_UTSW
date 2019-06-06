<?php

session_start();

// Authenticate user session
include "authenticate_user_session.php";

$root_dir="..";
$php_class_dir=$root_dir."/php_class";
require_once ("{$php_class_dir}/SampleID.inc");
require_once ("{$php_class_dir}/UUID.inc");
require_once ("{$php_class_dir}/PatientID.inc");
require_once("{$php_class_dir}/ChangeHistory.inc");
require_once ("{$php_class_dir}/EscapeString.inc");
require_once("{$php_class_dir}/dbencryt.inc");
require_once("{$root_dir}/dbpdx.inc");

/* Check record existence in database by using Curl (http request)
 * @param mixed $data
 * @return mixed Return an array of message ("exist" or "not exist") and id (patient_id or sample_uuid))
 * */
function checkExistGetIdByCurl($data){
    global $hostname;
    $ch = curl_init();
    $url ='http://'.Encryption::decrypt($hostname).'/pdx-v3/php_script/check_exist_in_db.php';
    curl_setopt($ch, CURLOPT_URL, $url);
    // CURLOPT_RETURNTRANSFER:
    // TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it directly.
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // CURLINFO_HEADER_OUT:
    // TRUE to track the handle's request string.
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    // assign TRUE to json_decode() => return array
    // assign FALSE (default) to json_decode() => return object
    $samIdResult = json_decode(curl_exec($ch),true);
    curl_close($ch);
    return $samIdResult;
}

/* Get Patient_Id in Sample table in db by sample UUID
 * @param mixed $db
 * @param string $uuid (sample UUID)
 * @return string $pid (found Patient_Id)
 * */
function getPatientIdBySampleUuid($db, $uuid){
    $pid= null;
    $sql = "SELECT Patient_ID FROM Sample WHERE UUID = ?";
    if($result = $db->prepare($sql)){
        $result->bind_param("s",$uuid);
        $result->execute();
        $result->bind_result($pid);
        $result->fetch();
        $result->close();
    }
    return $pid;
}

/* Check Father and Mother Sample Existence and Operate Father and Mother Sample & Patient Data in DB
 * @param object $db
 * @param string $localSampleId
 * @param string $openSpecimenSampleId
 * @param string $fatherOrMotherNote
 * @return mixed Return array of
 *  $isWarnSamIdsConflict (is local_sample_id and openspecimen_sample_id have different sample_uuid)
 *  $uuid (sample_uuid)
 *  $pid (patient_id of the sample_uuid)
 * */
function operateFatherMotherData($db,$localSampleId,$openSpecimenSampleId,$fatherOrMotherNote){
    // check whether father local sample id exists or not
    $locSamUuid = null;
    if($localSampleId!==null){
        $data = array(
            'table' => 'Sample',
            'field' => 'Local_Sample_ID',
            'value' => $localSampleId,
            'exceptValue' => ''
        );
        $locSamIdResult = checkExistGetIdByCurl($data);
        $locSamUuid = $locSamIdResult["id"];
    }

    // check whether father openspecimen sample id exists or not
    $openSpecSamUuid = null;
    if($openSpecimenSampleId!==null){
        $data = array(
            'table' => 'Sample',
            'field' => 'OpenSpecimen_Sample_ID',
            'value' => $openSpecimenSampleId,
            'exceptValue' => ''
        );
        $openSpecSamIdResult = checkExistGetIdByCurl($data);
        $openSpecSamUuid = $openSpecSamIdResult["id"];
    }

    // if both father local sample id & father openspecimen sample id exist in db
    $isWarnSamIdsConflict = 0;
    $uuid = null;
    $pid = null;
    if($locSamUuid !== null && $openSpecSamUuid !== null){
        if($locSamUuid !== $openSpecSamUuid){
            $isWarnSamIdsConflict=1;
        }else{
            $uuid = $locSamUuid;
            $pid=getPatientIdBySampleUuid($db, $uuid);
        }
    }// if father/mother local sample id exists in db
    elseif($locSamUuid !== null){
        $uuid = $locSamUuid;
        $pid=getPatientIdBySampleUuid($db, $uuid);
    }// if father/mother openspecimen sample id exists in db
    elseif($openSpecSamUuid !== null){
        $uuid = $openSpecSamUuid;
        $pid=getPatientIdBySampleUuid($db, $uuid);
    }// if both father/mother local sample id & father/mother openspecimen sample id NOT exist in db
    else{
        // create father patient record
        $pid=PatientID::generatePatientID($db,true);
        $sex=$fatherOrMotherNote==="Father"?2:($fatherOrMotherNote==="Mother"?1:null);
        $sql="INSERT INTO Patient (Patient_ID,Sex,Note,CreateTime) VALUES (?,?,?,NOW())";
        if($result = $db->prepare($sql)){
            $result->bind_param("sis",$pid,$sex,$fatherOrMotherNote);
            $result->execute();
            $result->close();
        }
        // create father sample record
        $uuid = UUID::generate36DigitUUID();
        $sampleId=SampleID::generateSampleID($db,$pid,99,99,1);
        $sql="INSERT INTO Sample (UUID,Sample_ID,Local_Sample_ID,OpenSpecimen_Sample_ID,Patient_ID,Notes,Project,CreateTime) "
            ."VALUES (?,?,?,?,?,?,2,NOW())";
        if($result = $db->prepare($sql)){
            $result->bind_param("ssssss",$uuid,$sampleId,$localSampleId,$openSpecimenSampleId,$pid,$fatherOrMotherNote);
            $result->execute();
            $result->close();
        }
    }

    return array("isWarnSamIdsConflict"=>$isWarnSamIdsConflict,"uuid"=>$uuid,"pid"=>$pid);
}

// Escape special characters in inputs
foreach ($_POST as $k => $v){
    $escape=EscapeString::escape($v);
    $_POST[$k]=(isset($_POST[$k]) && $escape!=="")?$escape:null;
}

$method=(isset($_POST['method']) && $_POST['method']!=="")?$_POST['method']:null;
if($method=== "1"){
    // check required fields
    $isErrIniSam=0;
    $isErrLocOpenSam=0;

    $isIniSam=$_POST['isInitialSample'];
    if($isIniSam===null) $isErrIniSam=1;

    $localSamId=$_POST['localSampleId'];
    $openSamId=$_POST['openSpecSampleId'];
    if($localSamId===null && $openSamId===null) $isErrLocOpenSam=1;

    if($isErrIniSam==1 || $isErrLocOpenSam==1){
        header("Location:{$root_dir}/create_sample.php?isErrIniSam={$isErrIniSam}&isErrLocOpenSam={$isErrLocOpenSam}");
    }else{
        $isWarnFatherSamIdsConflict=0;
        $isWarnMotherSamIdsConflict=0;

        // check optional fields
        $parentSamUuid=$_POST['parentSampleUuid'];
        $samDerDate=$_POST['sampleDerivedDate'];
        $iniSamUuidDB=$_POST['initialSampleUuidDB'];
        $samSrc=$_POST['sampleSrc'];
        $specimenType=$_POST['specimenType'];
        $samClass=$_POST['sampleClass'];
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
        $faLocSamID = $_POST['fatherLocalSampleId'];
        $faOpenSpecSamID = $_POST['fatherOpenSpecSampleId'];
        $moLocSamID =$_POST['motherLocalSampleId'];
        $moOpenSpecSamID =$_POST['motherOpenSpecSampleId'];
        $proceType=$_POST['procedureType'];
        $proceDate=$_POST['procedureDate'];
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
        $shipDate=$_POST['shipDate'];
        $pdxGenDate=$_POST['pdxGenDate'];
        $pdxRecDate=$_POST['pdxRecDate'];
        $institute=$_POST['institute'];
        $notes=$_POST['notes'];

        // Database connection
        $db = new mysqli(Encryption::decrypt($hostname),Encryption::decrypt($username),Encryption::decrypt($password),Encryption::decrypt($newdbname));
        if($db->connect_error){
            die('Unable to connect to database: ' . $db->connect_error);
        }
        $db->set_charset("utf8");

        // Before inserting current/child Sample into Database
        $fIsWarnSamIdsConflict = 0;
        $fUuid = null;
        $fPid = null;
        // if father sample ids are not null
        if($faLocSamID!==null || $faOpenSpecSamID!==null){
            $fSamResult=operateFatherMotherData($db,$faLocSamID,$faOpenSpecSamID,"Father");
            $fIsWarnSamIdsConflict = $fSamResult["isWarnSamIdsConflict"];
            $fUuid = $fSamResult["uuid"];
            $fPid = $fSamResult["pid"];
        }


        $mIsWarnSamIdsConflict = 0;
        $mUuid= null;
        $mPid = null;
        // if mother sample ids are not null
        if($moLocSamID!==null || $moOpenSpecSamID!==null){
            $mSamResult=operateFatherMotherData($db,$moLocSamID,$moOpenSpecSamID,"Mother");
            $mIsWarnSamIdsConflict = $mSamResult["isWarnSamIdsConflict"];
            $mUuid = $mSamResult["uuid"];
            $mPid = $mSamResult["pid"];
        }

        if($fIsWarnSamIdsConflict === 0 && $mIsWarnSamIdsConflict === 0){
            // if current/child sample has system patient id in database, update it with father_patient_id and mother patient_id
            if($sysPatId!==null && (($faLocSamID!==null || $faOpenSpecSamID!==null) || ($moLocSamID!==null || $moOpenSpecSamID!==null))){

                // record change history
                $changeHis = new ChangeHistory($db);
                $changeHis -> recordChangeHistory("Patient", "Patient_ID", $sysPatId, "Father_Patient_ID", $fPid, $_SESSION["user_id"]);
                $changeHis -> recordChangeHistory("Patient", "Patient_ID", $sysPatId, "Mother_Patient_ID", $mPid, $_SESSION["user_id"]);
                $changeHis  = null;

                $sql="UPDATE Patient SET Father_Patient_ID = ? , Mother_Patient_ID = ? WHERE Patient_ID = ?";
                if($result = $db->prepare($sql)){
                    $result->bind_param("sss",$fPid,$mPid,$sysPatId);
                    $result->execute();
                    $result->close();
                }
            }

            // Insert current/child Sample Into Database
            // check any of local_sample_id and openSpecimen_sample_id exists in the database (This's been checked in frontend.)
            $uuid = UUID::generate36DigitUUID();
            $sampleId=SampleID::generateSampleID($db,$sysPatId,$specimenType,$samClass,$samSrc);
            $sql="INSERT INTO Sample (UUID,Existed_Barcode,Sample_ID,Local_Sample_ID,OpenSpecimen_Sample_ID,Patient_ID,"
                ."Sample_Contributor_Institute_ID,Sample_Source,Procedure_Type,Date_Procedure,Initial_Sample_UUID,Parent_UUID,"
                ."Date_Derive_From_Parent,Specimen_Type,Sample_Class,Sample_Type,Storage_Bank,Storage_Room,Cabinet_Type,"
                ."Cabinet_Temperature,Cabinet_Number,Shelf_Number,Rack_Number,Box_Number,Position_Number,Position_Text,"
                ."Quantity_Value,Quantity_Unit,Concentration_Value,Concentration_Unit,Sample_Prep,"
                ."Anatomical_Site,Anatomical_Laterality,Anatomical_Direction,"
                ."Date_Sample_Ship,Date_PDX_Sample_Generate,Date_PDX_Sample_Receive,Notes,Project,isInitialSample,CreateTime) "
                ."VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,2,?,NOW())";

            $specimenType=(String)((int)$specimenType);
            $samClass=(String)((int)$samClass);
            $samSrc=(String)((int)$samSrc);

            if($result = $db->prepare($sql)){
                $result->bind_param("sssssssssssssssssssssssssssssssssssssss",
                    $uuid,$exiBarcode,$sampleId,$localSamId,$openSamId,$sysPatId,
                    $institute,$samSrc,$proceType,$proceDate,$iniSamUuidDB,$parentSamUuid,
                    $samDerDate,$specimenType,$samClass,$samType,$bank,$room,$cabType,
                    $cabTemp,$cabNum,$shelfNum,$rackNum,$boxNum,$posNum,$posTxT,
                    $quanNum,$quanUnit,$concenNum,$concenUnit,$samPrep,
                    $priTumorSite,$priTumorLater,$priTumorDir,
                    $shipDate,$pdxGenDate,$pdxRecDate,$notes,$isIniSam);
                $result->execute();
                $result->close();
            }

            // Record current/child, father, mother samples relationship in ChildFatherMotherSampleMap table
            // (ps. It'd be better to save relationship in another table instead of sample table,
            // because one child sample may map to multiple father/mother samples.)
            if(!($sysPatId!==null && (($faLocSamID!==null || $faOpenSpecSamID!==null) || ($moLocSamID!==null || $moOpenSpecSamID!==null)))) {
                $sql = "INSERT INTO ChildFatherMotherSampleMap(Child_Sample_UUID, Mother_Sample_UUID, Father_Sample_UUID)" .
                    " VALUES(?,?,?)";
                if ($result = $db->prepare($sql)) {
                    $result->bind_param("sss", $uuid, $mUuid, $fUuid);
                    $result->execute();
                    $result->close();
                }
            }
            $db->close();
            header("Location:{$root_dir}/samplelist.php?operate=view_print&uuid={$uuid}");
        }else{
            $db->close();
            header("Location:{$root_dir}/create_sample.php?isWarnFaSamConf={$fIsWarnSamIdsConflict}".
                "&isWarnMoSamConf={$mIsWarnSamIdsConflict}");
        }
    }
}
