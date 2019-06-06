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

$uuid=(isset($_POST["uuid"]) && $_POST["uuid"]!="")?EscapeString::escape($_POST["uuid"]):null;

$changehistory=new ChangeHistory($db);
$changehistory->recordChangeHistory("Sample","UUID",$uuid,"isDelete",1,$_SESSION["user_id"]);
$changehistory=null;

if($uuid!==null){
    $sql="UPDATE Sample SET isDelete=1 WHERE UUID=?;";
    if($result = $db->prepare($sql)) {
        $result->bind_param("s",$uuid);
        $result->execute();
        $result->close();
    }
    echo json_encode(array("stat"=>"Success! ","msg"=>"Success to delete the sample.", "class"=>"alert-success"));
}else{
    echo json_encode(array("stat"=>"Fail! ","msg"=>"Fail to delete the sample.", "class"=>"alert-danger"));
}

$db->close();