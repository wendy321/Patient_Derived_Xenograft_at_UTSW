<?php

session_start();

// Authenticate user session
include "authenticate_user_session.php";

$root_dir="..";
$php_class_dir=$root_dir."/php_class";
require_once ("{$php_class_dir}/ChangeHistory.inc");
require_once ("{$php_class_dir}/PatientID.inc");
require_once ("{$php_class_dir}/EscapeString.inc");
require_once("{$php_class_dir}/dbencryt.inc");
require_once("{$root_dir}/dbpdx.inc");

// Connect to database
$db = new mysqli(Encryption::decrypt($hostname),Encryption::decrypt($username),Encryption::decrypt($password),Encryption::decrypt($remoter_dbname));
if($db->connect_error){
    die('Unable to connect to database: ' . $db->connect_error);
}

// Escape special characters in inputs
foreach ($_POST as $k => $v){
    $_POST[$k]=EscapeString::escape($v);
}

$method=(isset($_POST['method']) && $_POST['method']!=="")?$_POST['method']:null;
if ($method === "2") {
    $isSuccess = true;
    $errorMsg = "";
    $file = null;
    if (empty($_FILES['inputfile'])) {
        $isSuccess = false;
        $errorMsg .= 'No file. Please import a file.';
    } else {
        $file = $_FILES['inputfile'];
        $name = $file['name'];
        $type = $file['type'];
        if ($type !== "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
            $isSuccess = false;
            $errorMsg .= 'Wrong file type. Please import XSLX file.';
        }
        $error = $file['error'];
        if ($error > 0) {
            $isSuccess = false;
            $errorMsg .= ' Fail to upload file.';
        }
        $size = $file['size'];
        if ($size > 1000000) {
            $isSuccess = false;
            $errorMsg .= ' File size is too large. File size should be <= 1 MB.';
        }
    }

    if ($isSuccess == false) {
        echo json_encode(['goto' => '', 'error' => $errorMsg]);
    } else {
        // change previous batch upload tag to 2 (old batch)
        $sql = "UPDATE PDXParameters SET Tag='2' WHERE AccountID=? AND Tag='1' AND DataType='patient' ";
        if ($result = $db->prepare($sql)) {
            $result->bind_param("i", $_SESSION["user_id"]);
            $result->execute();
            $result->close();
        }

        // insert new job
        $jobid = uniqid("", TRUE);
        $sql = "INSERT INTO Jobs(JobID,Software,Analysis,Status,CreateTime) VALUES (?,\"pdx\",\"patientbatchupload\",0,now())";
        if ($result = $db->prepare($sql)) {
            $result->bind_param("s", $jobid);
            $result->execute();
            $result->close();
        }

        // insert new job parameters with user account id, batch upload tag 1, data type, and blob xlsx file
        $sql = "INSERT INTO PDXParameters(JobID,AccountID,Tag,DataType,XLSXFile) VALUES (?,?,1,\"patient\",?)";
        if ($result = $db->prepare($sql)) {
            $result->bind_param("sib", $jobid, $_SESSION["user_id"], $blob);

            $fp = fopen($file['tmp_name'], "rb");
            while (!feof($fp)) {
                $result->send_long_data(2, fread($fp, filesize($file['tmp_name'])));
            }
            fclose($fp);

            $result->execute();
            $result->close();
        }
        echo json_encode(['goto' => 'patientlist.php?operate=view&pid=allnew', 'error' => '']);
    }
}
$db->close();