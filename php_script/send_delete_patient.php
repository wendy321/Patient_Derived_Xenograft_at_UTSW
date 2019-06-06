<?php

session_start();
include "authenticate_user_session.php";

$root_dir="..";
$php_class_dir=$root_dir."/php_class";
require("{$root_dir}/dbpdx.inc");
require("{$php_class_dir}/dbencryt.inc");
require("{$php_class_dir}/EscapeString.inc");
require("{$php_class_dir}/ChangeHistory.inc");

$db = new mysqli(Encryption::decrypt($hostname),Encryption::decrypt($username),Encryption::decrypt($password),Encryption::decrypt($newdbname));
if($db->connect_error){
    die('Unable to connect to database: ' . $db->connect_error);
}

$pid=(isset($_POST["pid"]) && $_POST["pid"]!="")?EscapeString::escape($_POST["pid"]):null;

if($pid!==null){

    $changeHistory=new ChangeHistory($db);
    $changeHistory->recordChangeHistory("Patient","Patient_ID",$pid,"isDelete",1,$_SESSION["user_id"]);
    $changeHistory->recordChangeHistory("Sample","UUID",$pid,"Patient_ID",null,$_SESSION["user_id"]);
    $uuid=$nSampleId=null;
    $sql="SELECT UUID, CONCAT(\"ZZZZZZZ\",SUBSTRING(Sample_ID,8,8)) AS N_Sample_ID FROM Sample WHERE Patient_ID=?;";
    if($result = $db->prepare($sql)) {
        $result->bind_param("s",$pid);
        $result->execute();
        $result->bind_result($uuid, $nSampleId);
        while($result->fetch()){
            $changeHistory->recordChangeHistory("Sample","UUID",$uuid,"Sample_ID",$nSampleId,$_SESSION["user_id"]);
        }
        $result->close();
    }
    $changeHistory=null;

    // mark Patient.isDelete = 1
    $sql="UPDATE Patient SET isDelete=1 WHERE Patient_ID=?;";
    if($result = $db->prepare($sql)) {
        $result->bind_param("s",$pid);
        $result->execute();
        $result->close();
    }
    //unlink the Patient with Sample
    $sql="UPDATE Sample SET Patient_ID=NULL, Sample_ID = CONCAT(\"ZZZZZZZ\",SUBSTRING(Sample_ID,8,8)) WHERE Patient_ID=?;";
    if($result = $db->prepare($sql)) {
        $result->bind_param("s",$pid);
        $result->execute();
        $result->close();
    }
    echo json_encode(array("stat"=>"Success! ","msg"=>"Success to delete the patient.", "class"=>"alert-success"));
}else{
    echo json_encode(array("stat"=>"Fail! ","msg"=>"Fail to delete the patient.", "class"=>"alert-danger"));
}

$db->close();