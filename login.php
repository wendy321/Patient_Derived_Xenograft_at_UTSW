<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>PDX Portal</title>
    <meta name="keywords" content="PDX, UTSW">
    <meta name="description" content="A user-friendly public platform for data integration, analysis, sharing,
		and management for proposed projects.">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="images/utsw_icon.jpg">
    <link rel="stylesheet" href="css/vendor/animate.min.css">
    <link rel="stylesheet" href="css/vendor/bootstrap.min.3.4.css">
    <link rel="stylesheet" href="css/vendor/fontawesome-all.min.css">
    <link rel="stylesheet" href="css/vendor/fileinput.css" media="all"  type="text/css">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/templatemo-style.css">
    <link rel="stylesheet" href="css/login.css">
    <!--[if lt IE 9]>
    <script src="js/vendor/html5shiv.min.js"></script>
    <script src="js/vendor/respond.min.js"></script>
    <![endif]-->
</head>
<body id="top">

<?php include("nav.php");?>

<section id="bkg">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-1 col-md-10">
                <h1 class="wow fadeIn" data-wow-offset="50" data-wow-delay="0.9s">Patient Derived Xenograft Web Portal </h1>
                <div class="element">
                    <div class="sub-element">A user-friendly public platform</div>
                    <div class="sub-element">Data integration, analysis, sharing, and management</div>
                    <div class="sub-element">A data entry system using standardized common data elements</div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="login">
    <div class="container">
        <div class="card card-container">
            <div class="text-center"><h3 class="card-title">Login</h3></div>
            <form class="form-signin" action="php_script/check_user.php" method="post">
                <label for="inputEmail"> Email: </label>
                <input type="email" id="inputEmail" name="email" class="form-control" placeholder="Email address" required autofocus>
                <label for="inputPassword"> Password: </label>
                <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" required>
                <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Sign in</button>
            </form>
        </div>
    </div>
</section>

<?php include ("footer.php");?>

<script src="js/vendor/jquery.js"></script>
<script src="js/vendor/jquery-ui.js"></script>
<script src="js/vendor/bootstrap.min.3.4.js"></script>
<script src="js/vendor/typed.js"></script>
<script src="js/vendor/wow.min.js"></script>
<script src="js/custom.js"></script>
<script type="text/javascript">
    $('.templatemo-nav ul li:last-child a').addClass('current');
    <?php
        $errorCode=isset($_GET["error"])?$_GET["error"]:null;
        if($errorCode!==null){
            $str_append="<div id=\"error\" class=\"row\">"
                ."<div class=\"col-md-12 text-center\">"
                ."<div class=\"alert alert-danger alert-dismissable\">"
                ."<i class=\"fa fa-times-circle\" data-dismiss=\"alert\" style=\"float:right\"></i>"
                ."<strong>Error</strong><br>";
            if($errorCode==="1"){
                $str_append.="User isn\'t found.";
            }
            $str_append.="</div></div></div>";
            echo "$('form').append('".$str_append."');";
            echo "$('input').on('keypress',function(){
                $('#error').slideUp(100);
            })";
        }
    ?>

</script>
</body>
</html>
