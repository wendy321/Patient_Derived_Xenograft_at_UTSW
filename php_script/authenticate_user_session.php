<?php
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
    else {
        if ($_SESSION['HTTP_USER_AGENT'] != md5($_SERVER['HTTP_USER_AGENT'])) {
            header("location:login.php");
            exit;
        }
    }

    $user=null;
    if(!isset($_SESSION["user_id"])){
        header("location:login.php");
    }