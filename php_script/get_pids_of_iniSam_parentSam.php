<?php
session_start();
include "authenticate_user_session.php";

$root_dir="..";
$php_class_dir=$root_dir."/php_class";
require("{$root_dir}/dbpdx.inc");
require("{$php_class_dir}/dbencryt.inc");
require("{$php_class_dir}/EscapeString.inc");

$db = new mysqli(Encryption::decrypt($hostname),Encryption::decrypt($username),Encryption::decrypt($password),Encryption::decrypt($newdbname));
if($db->connect_error){
    die('Unable to connect to database: ' . $db->connect_error);
}
$db->set_charset("utf-8");


$iniSamUuid=(isset($_POST["iniSamUuid"]) && $_POST["iniSamUuid"]!=="")?$_POST["iniSamUuid"]:null;
$parentSamUuid=(isset($_POST["parentSamUuid"]) && $_POST["parentSamUuid"]!=="")?$_POST["parentSamUuid"]:null;

$iniSamPid = null;
if($iniSamUuid!==null){
    $sql="SELECT Patient_ID FROM Sample WHERE UUID = ?";
    if($result = $db->prepare($sql)){
        $result->bind_param("s",$iniSamUuid);
        $result->execute();
        $result->bind_result($iniSamPid);
        $result->fetch();
        $result->close();
    }
}

$parentSamPid = null;
if($parentSamUuid!==null){
    $sql="SELECT Patient_ID FROM Sample WHERE UUID = ?";
    if($result = $db->prepare($sql)){
        $result->bind_param("s",$parentSamUuid);
        $result->execute();
        $result->bind_result($parentSamPid);
        $result->fetch();
        $result->close();
    }
}

echo json_encode(["iniSamPid"=>$iniSamPid,"parentSamPid"=>$parentSamPid]);
