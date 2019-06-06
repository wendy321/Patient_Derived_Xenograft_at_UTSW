<?php
session_start();
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
        <div class="row">
            <div class="col-md-12">
                <h2 class="wow bounceIn" data-wow-offset="50" data-wow-delay="0.3s">
                    <span>ANALYSIS</span>  <a href="#" class="btn btn-xs btn-warning">Coming Soon</a>
                </h2>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12 wow fadeIn" data-wow-offset="50" data-wow-delay="0.6s">
                <div class="portfolio-thumb">
                    <h4 class="text-center">Meta Analysis</h4>
                    <img src="images/analysis/meta_tumor_vs_normal.jpg" class="img-responsive" alt="portfolio img 1">
                    <div class="portfolio-overlay">
                        <h4>Meta Analysis</h4>
                        <p>effectively combines the statistical strength from multiple data sets which allows greater
                            precision than using any of the single studies.</p>
                        <a href="#" class="btn btn-warning" disabled="true">Coming Soon</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12 wow fadeIn" data-wow-offset="50" data-wow-delay="0.6s">
                <div class="portfolio-thumb">
                    <h4 class="text-center">Survival Analysis</h4>
                    <img src="images/analysis/gene_based_survival.png" class="img-responsive" alt="portfolio img 2">
                    <div class="portfolio-overlay">
                        <h4>Survival Analysis</h4>
                        <p>measures the association between overall survival and expression of a selected gene in a
                            selected data set. Users have the control to select a subset of the patients based on
                            clinical features such as gender, age, histology types and smoking status.</p>
                        <a href="#" class="btn btn-warning" disabled="true">Coming Soon</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12 wow fadeIn" data-wow-offset="50" data-wow-delay="0.6s">
                <div class="portfolio-thumb">
                    <h4 class="text-center">Comparative Analysis</h4>
                    <img src="images/analysis/compare-small.jpg" class="img-responsive" alt="portfolio img 4">
                    <div class="portfolio-overlay">
                        <h4>Comparative Analysis</h4>
                        <p>allows users to compare gene expression from different tissue types or from samples originated
                            from patients with different clinical features. Users have the control to define two sample
                            groups based on one or more clinical features.</p>
                        <a href="#" class="btn btn-warning" disabled="true">Coming Soon</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12 wow fadeIn" data-wow-offset="50" data-wow-delay="0.6s">
                <div class="portfolio-thumb">
                    <h4 class="text-center">Correlation Analysis</h4>
                    <img src="images/analysis/correlation.png" class="img-responsive" alt="portfolio img 3">
                    <div class="portfolio-overlay">
                        <h4>Correlation Analysis</h4>
                        <p>visualizes the expression correlations among a list of user-defined genes in the selected
                            data set as a hierarchically clustered heatmap.</p>
                        <a href="#" class="btn btn-warning" disabled="true">Coming Soon</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>s
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
    $('.templatemo-nav ul li:nth-child(5) a').addClass('current');
</script>
</body>
</html>