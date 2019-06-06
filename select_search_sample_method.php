<?php
session_start();

$_SESSION["pre_page"]=basename($_SERVER['PHP_SELF']);
$php_script_dir="php_script";
include "{$php_script_dir}/authenticate_user_session.php";

$view=$add=$mod=$delete=null;
if(isset($_SESSION["data_ops_perm"]) && !empty($_SESSION["data_ops_perm"])){
    $view=$_SESSION["data_ops_perm"]["View"];
    $add=$_SESSION["data_ops_perm"]["Add"];
    $mod=$_SESSION["data_ops_perm"]["Modify"];
    $delete=$_SESSION["data_ops_perm"]["Delete"];
    $print=$_SESSION["data_ops_perm"]["Print"];
}

?>
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
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/templatemo-style.css">
    <style type="text/css">
        h3 {
            font-weight: 700;
            margin-bottom: 2rem;
        }
        .btn.btn-success {
            margin-left: 1.5em!important;
        }
        @media only screen and (max-width: 687px) {
            .btn-group.btn-group-lg {
                padding-left: 25%;
                padding-right: 25%;
            }
            .btn.btn-success {
                margin-left: 0!important;
                margin-top: 1em;
            }
        }
    </style>

    <!--[if lt IE 9]>
    <script src="js/vendor/html5shiv.min.js"></script>
    <script src="js/vendor/respond.min.js"></script>
    <![endif]-->
</head>
<body id="top">

<!-- start preloader -->
<div class="preloader">
    <div class="sk-spinner sk-spinner-wave">
        <div class="sk-rect1"></div>
        <div class="sk-rect2"></div>
        <div class="sk-rect3"></div>
        <div class="sk-rect4"></div>
        <div class="sk-rect5"></div>
    </div>
</div>
<!-- end preloader -->

<!-- start navigation -->
<?php include ("nav.php");?>
<!-- end navigation -->

<!-- start bkg -->
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
<!-- end bkg -->

<!-- start portfolio -->
<section id="portfolio">
    <div class="container">
        <div class="row" style="margin-bottom: 4rem;">
            <div class="col-md-12">
                <h2 class="wow bounceIn" data-wow-offset="50" data-wow-delay="0.3s">
                    <span>Search Sample</span>
                </h2>
            </div>
            <div class="col-offset-2 col-8 text-center"><br/><br/><br/>
                <h3>Select a Search Method</h3><br/>
                <div class="btn-group btn-group-lg">
                    <?php
                    $append_operate_str="";
                    if($view!=null && $view=="1"){
                        $append_operate_str.="_view";
                    }
                    if($mod!=null && $mod=="1"){
                        $append_operate_str.="_edit";
                    }
                    if($delete!=null && $delete=="1"){
                        $append_operate_str.="_delete";
                    }
                    ?>
                    <a class="btn btn-primary" href="samplelist.php?operate=<?php echo $append_operate_str;?>" target="_self">
                        Search Sample by Sample Variables
                    </a>
                    <a class="btn btn-success" href="samplelist_father_mother.php?operate=<?php echo $append_operate_str;?>" target="_self">
                        Search Father and Mother Samples
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end portfolio -->

<!-- start copyright -->
<?php include ("footer.php");?>
<!-- end copyright -->
<script src="js/vendor/jquery.js"></script>
<script src="js/vendor/bootstrap.min.3.4.js"></script>
<script src="js/vendor/typed.js"></script>
<script src="js/vendor/wow.min.js"></script>
<script src="js/custom.js"></script>
<script type="text/javascript">
    $('.templatemo-nav ul li:nth-child(4) a').addClass('current');
</script>
</body>
</html>