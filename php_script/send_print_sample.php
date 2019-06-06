<?php

session_start();
include "authenticate_user_session.php";

$root_dir="..";
$php_class_dir=$root_dir."/php_class";
require("{$root_dir}/dbpdx.inc");
require("{$php_class_dir}/dbencryt.inc");
require("{$php_class_dir}/EscapeString.inc");
require("{$php_class_dir}/.inc");

$db = new mysqli(Encryption::decrypt($hostname),Encryption::decrypt($username),Encryption::decrypt($password),Encryption::decrypt($newdbname));
if($db->connect_error){
    die('Unable to connect to database: ' . $db->connect_error);
}

$uuid=(isset($_POST["uuid"]) && $_POST["uuid"]!="")?EscapeString::escape($_POST["uuid"]):null;
$userId=(isset($_POST["user_id"]) && $_POST["user_id"]!="")?EscapeString::escape($_POST["user_id"]):null;

if($uuid!==null && $userId!==null){



    $isBarcodePrint=null;
    $sql="SELECT isBarcodePrint FROM PrintLabelJob WHERE UUID = ? AND User_Account_ID = ?;";
    if($result = $db->prepare($sql)) {
        $result->bind_param("ss",$uuid,$userId);
        $result->execute();
        $result->bind_result($isBarcodePrint);
        $result->fetch();
        $result->close();
    }
    if($isBarcodePrint!==null){
        if($isBarcodePrint=="1"){
            $sql="UPDATE PrintLabelJob isBarcodePrint SET isBarcodePrint = 0 WHERE UUID = ? AND User_Account_ID = ?;";
            if($result = $db->prepare($sql)) {
                $result->bind_param("ss",$uuid,$userId);
                $result->execute();
                $result->close();
            }
        }
    }else{
        $samId=$pid=$locPid=$osPid=null;
        $sql="SELECT s.Sample_ID, s.Patient_ID, p.Local_Patient_ID, p.OpenSpecimen_Patient_ID FROM Sample AS s ".
            "LEFT JOIN Patient AS p ON s.Patient_ID = p.Patient_ID WHERE UUID = ? ;";
        if($result = $db->prepare($sql)) {
            $result->bind_param("s",$uuid);
            $result->execute();
            $result->bind_result($samId,$pid,$locPid,$osPid);
            $result->fetch();
            $result->close();
        }

        $sql="INSERT INTO PrintLabelJob (UUID,Sample_ID,Patient_ID,Local_Patient_ID,OpenSpecimen_Patient_ID,User_Account_ID,isBarcodePrint) ".
            "VALUES (?,?,?,?,?,?,0)";
        if($result = $db->prepare($sql)) {
            $result->bind_param("ssssss",$uuid,$samId,$pid,$locPid,$osPid,$userId);
            $result->execute();
            $result->close();
        }
    }
    echo json_encode(array("stat"=>"Success! ","msg"=>"Success to submit the sample, <b>".$uuid."</b>, label printing job.", "class"=>"alert-success"));
}else{
    echo json_encode(array("stat"=>"Fail! ","msg"=>"Fail to submit the sample label printing job.", "class"=>"alert-danger"));
}

$db->close();