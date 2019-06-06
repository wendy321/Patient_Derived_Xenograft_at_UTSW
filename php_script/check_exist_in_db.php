<?php

session_start();
include "authenticate_user_session.php";

$root_dir="..";
$php_class_dir=$root_dir."/php_class";
require_once ("{$php_class_dir}/EscapeString.inc");
require_once("{$php_class_dir}/dbencryt.inc");
require_once("{$root_dir}/dbpdx.inc");

$table=isset($_POST["table"])?EscapeString::escape($_POST["table"]):null;
$field=isset($_POST["field"])?EscapeString::escape($_POST["field"]):null;
$value=isset($_POST["value"])?EscapeString::escape($_POST["value"]):null;
$exceptValue=isset($_POST["exceptValue"])?EscapeString::escape($_POST["exceptValue"]):"";

$response="";

if($table===null || $field===null || $value===null){
    $response.="Query string can't be empty.";
    echo json_encode(["msg"=>$response]);
    exit;
}

$db = new mysqli(Encryption::decrypt($hostname),Encryption::decrypt($username),Encryption::decrypt($password),Encryption::decrypt($newdbname));
if($db->connect_error){
    die('Unable to connect to database: ' . $db->connect_error);
}
$db->set_charset("utf8");

$idExist=null;
if($table === "Sample"){
    $sql="SELECT UUID FROM ".$table." WHERE ".$field."=? AND ".$field." NOT IN (?)";
}
if($table === "Patient"){
    $sql="SELECT Patient_ID FROM ".$table." WHERE ".$field."=? AND ".$field." NOT IN (?)";
}

if($result = $db->prepare($sql)){
    $result->bind_param("ss",$value,$exceptValue);
    $result->execute();
    $result->bind_result($idExist);
    $result->fetch();
    $result->close();
}
$db->close();

if($idExist!==null){
    $response.="exist";
}else{
    $response.="not exist";
}

echo json_encode(["msg"=>$response,"id"=>$idExist]);