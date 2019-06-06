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
        .card {
            margin: 2rem;
        }
        .card-parent:hover{
            filter: saturate(2) brightness(105%);
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
                    <span>DATA INVENTORY</span>
                </h2>
            </div>

            <div class="col-12"><h3 class="text-center">MANAGE</h3></div>
            <div class="card-parent col-md-offset-2 col-md-4 col-xs-6 wow fadeIn bg-danger" data-wow-offset="50"
                 data-wow-delay="0.6s">
                <div class="card card-overlay">
                    <div class="card-block">
                        <h3 class="card-title text-center">
                            Sample
                        </h3>
                        <div class="card-text">
                            <?php
                                if($add!=null && $add=="1"){
                                    echo "<div class=\"card-text-item\">
                                            <h4 style=\"font-weight: 600;\">
                                                <i class=\"fa fa-th-large\"></i>
                                                <a style=\"color: black;\" href=\"create_sample.php\" target=\"_blank\">Create: </a>
                                            </h4>
                                            create single sample or batch upload samples
                                          </div>";
                                }
                            ?>
                            <br/>
                            <?php
                                $append_operate_str="";
                                $append_title_str="";
                                $append_text_str="";
                                if($view!=null && $view=="1"){
                                    $append_operate_str.="_view";
                                    $append_title_str.="Search";
                                    $append_text_str.="search sample";
                                }
                                if($mod!=null && $mod=="1"){
                                    $append_operate_str.="_edit";
                                    $append_title_str.=($append_title_str!="")?" & Edit":"Edit";
                                    $append_text_str.=($append_text_str!="")?" and edit sample":"edit sample";
                                }
                                if($delete!=null && $delete=="1"){
                                    $append_operate_str.="_delete";
                                    $append_title_str.=($append_title_str!="")?"":"Edit";
                                    $append_text_str.=($append_text_str!="")?"":"edit sample";
                                }
                                $append_title_str.=":";
                                echo "<div class=\"card-text-item\">
                                            <h4 style=\"font-weight: 600;\">
                                                    <i class=\"fa fa-search\"></i> 
                                                 <a href=\"select_search_sample_method.php\" 
                                                 target=\"_blank\" style=\"color:black;\">".$append_title_str." </a>
                                            </h4>
                                            ".$append_text_str."
                                     </div>";
                            ?>
                            <br/>
                            <?php
                            if($print!=null && $print=="1"){
                                echo "<div class=\"card-text-item\">
                                            <h4 style=\"font-weight: 600;\">
                                                <i class=\"fa fa-barcode\"></i>
                                                <a style=\"color: black;\" href=\"samplelist.php?operate=_view_print\" target=\"_blank\">Search & Print: </a>
                                            </h4>
                                            search sample and print sample barcode label
                                          </div>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-parent col-md-4 col-xs-6 wow fadeIn bg-info" data-wow-offset="50" data-wow-delay="0.6s">
                <div class="card">
                    <div class="card-block">
                        <h3 class="card-title text-center">
                            Patient
                        </h3>
                        <div class="card-text">
                            <?php
                            if($add!=null && $add=="1"){
                                echo "<div class=\"card-text-item\">
                                         <h4 style=\"font-weight: 600;\">
                                            <i class=\"fa fa-user\"></i>  
                                            <a href=\"create_patient.php\" target=\"_blank\" style=\"color:black;\">Create: </a>
                                         </h4>
                                         create single patient or batch upload patients
                                      </div>";
                            }
                            ?>

                            <br/>
                            <?php
                            echo "<div class=\"card-text-item\">
                                      <h4 style=\"font-weight: 600;\">
                                          <i class=\"fa fa-search\"></i> 
                                          <a href=\"patientlist.php?operate=".$append_operate_str."\" 
                                              target=\"_blank\" style=\"color:black;\">".$append_title_str."</a>
                                      </h4>
                                      ".str_replace("sample","patient",$append_text_str)."
                                  </div>";
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        if($mod!=null && $mod=="1"){
            echo "<hr>
                    <div class=\"row\">
                        <div class=\"col-12\"><h3 class=\"text-center\">LINK</h3></div>
                        <div class=\"card-parent col-md-offset-3 col-md-6 col-xs-12 wow fadeIn bg-warning\" data-wow-offset=\"50\" data-wow-delay=\"0.6s\">
                            <div class=\"card\">
                                <div class=\"card-block\">
                                    <h3 class=\"card-title text-center\">
                                        Link Sample & Patient
                                    </h3>
                                    <div class=\"card-text\">
                                        <div class=\"card-text-item\">
                                            <h4 style=\"font-weight: 600;\"><i class=\"fa fa-external-link-alt\"></i>
                                                <a href=\"samplelist.php?operate=edit&item=patient\" target=\"_blank\" style=\"color:black;\">Link: </a>
                                            </h4>
                                            link sample to patient
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>";
        }
        ?>
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
    $(function () {

        function keepSameDivHeight(d1, d11, d2, d21){
            if($(d11).height() > $(d21).height()){
                $(d1).height($(d11).height()+40);
                $(d2).height($(d11).height()+40);
            }else{
                $(d1).height($(d21).height()+40);
                $(d2).height($(d21).height()+40);
            }
        }

        $('.templatemo-nav ul li:nth-child(4) a').addClass('current');

        const card_parent = '.card-parent';
        const card_overlay = '.card-overlay';
        let d1 = $(card_parent).get(0);
        let d11 = $(d1).find(card_overlay);
        let d2= $(card_parent).get(1);
        let d21 = $(d2).find(card_overlay);
        keepSameDivHeight(d1, d11, d2, d21);
        $(window).on('resize', function(){
            keepSameDivHeight(d1, d11, d2, d21);
        });
    });
</script>
</body>
</html>