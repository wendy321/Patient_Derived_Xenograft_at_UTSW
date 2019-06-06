<?php

    session_start();

    // make it complicate to prevent session fixation
    if (!isset($_SESSION['initia']))
    {
        session_regenerate_id();
        $_SESSION['initia'] = true;
    }

    // compare current user browser with previous one to prevent session hijacking
    if (!isset($_SESSION['HTTP_USER_AGENT']))
    {
        $_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
    }
    else
    {
        if ($_SESSION['HTTP_USER_AGENT'] != md5($_SERVER['HTTP_USER_AGENT']))
        {
            /* prompt for password*/
            exit;
        }
    }

    $root_dir="..";
    $php_class_dir=$root_dir."/php_class";

    // user authentication
    require_once ("{$php_class_dir}/EscapeString.inc");
    $input_email=(isset($_POST["email"]) && ($_POST["email"]!==""))?EscapeString::escape($_POST["email"]):null;
    $input_pword=(isset($_POST["password"]) && ($_POST["password"]!==""))?EscapeString::escape($_POST["password"]):null;

    if($input_email==null || $input_pword==null){
        header("Location:login.php");
    }else{
        require_once ("{$php_class_dir}/dbencryt.inc");
        require_once("{$root_dir}/dbpdx.inc");
        $db = new mysqli(Encryption::decrypt($hostname),Encryption::decrypt($username),
            Encryption::decrypt($password),Encryption::decrypt($newdbname));
        if($db->connect_error) {
            die('Unable to connect to database: ' . $db->connect_error);
        }

        if($result=$db->prepare("SELECT DOP.DataOps, CA.ID, CA.Account FROM DataOpsPermission AS DOP LEFT JOIN CodeAccount".
            " AS CA ON DOP.Role=CA.Role WHERE DOP.Project=2 AND CA.Email=? AND CA.Password=?")){
            $result->bind_param("ss",$input_email,$input_pword);
            $result->execute();
            $result->bind_result($data_ops_id,$user_id, $user_name);
            $result->fetch();
            $result->close();

            if ($data_ops_id===null && $user_id===null){
                header("Location:{$root_dir}/login.php?error=1");
            }else{
                $_SESSION["user_name"]=$user_name;
                $_SESSION["user_id"]=$user_id;
                $_SESSION["project"]= "2";
                if($result=$db->prepare("SELECT t.View,t.Add,t.Modify,t.Delete,t.Print FROM CodeDataOps AS t WHERE t.ID = ?")){
                    $result->bind_param("i",$data_ops_id);
                    $result->execute();
                    $result->bind_result($view,$add,$mod,$del,$print);
                    $result->fetch();
                    $result->close();
                    $_SESSION["data_ops_perm"]=
                        array("Project"=>2, "View"=>$view, "Add"=>$add, "Modify"=>$mod, "Delete"=>$del, "Print" => $print);
                }
                if(isset($_SESSION["pre_page"]) && ($_SESSION["pre_page"]!=="login.php" || $_SESSION["pre_page"]!=="")){
                    header("Location:{$root_dir}/{$_SESSION["pre_page"]}");
                }else{
                    header("Location:{$root_dir}/index.php");
                }

            }
        }
        $db->close();
    }
?>