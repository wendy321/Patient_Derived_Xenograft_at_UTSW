<?php
    session_start();
    session_destroy();

    // change php session id (avoid same session id kept after jumping to login.php)
    session_start();
    session_regenerate_id();
    header('Location: ../login.php');

