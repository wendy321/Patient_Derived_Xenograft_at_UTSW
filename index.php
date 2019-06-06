<?php
session_start();

include "dbpdx.inc";
$php_class_dir="php_class";
require ("{$php_class_dir}/dbencryt.inc");

// Database connection
$db = new mysqli(Encryption::decrypt($hostname), Encryption::decrypt($username), Encryption::decrypt($password), Encryption::decrypt($newdbname));
if ($db->connect_error) {
    die('Unable to connect to database: ' . $db->connect_error);
}
$db->set_charset("utf8");

$pidCnt=null;
$sql = "SELECT count(Patient_ID) FROM Patient WHERE isDelete = 0";
if ($result = $db->prepare($sql)) {
    $result->execute();
    $result->bind_result($pidCnt);
    $result->fetch();
    $result->close();
}

$samCnt=null;
$sql = "SELECT count(UUID) FROM Sample WHERE isDelete = 0";
if ($result = $db->prepare($sql)) {
    $result->execute();
    $result->bind_result($samCnt);
    $result->fetch();
    $result->close();
}

$dnaCnt=null;
$sql = "SELECT count(UUID) FROM Sample WHERE Sample_Type BETWEEN 11 AND 19 AND isDelete = 0";
if ($result = $db->prepare($sql)) {
    $result->execute();
    $result->bind_result($dnaCnt);
    $result->fetch();
    $result->close();
}

$rnaCnt=null;
$sql = "SELECT count(UUID) FROM Sample WHERE Sample_Type BETWEEN 21 AND 29 AND isDelete = 0";
if ($result = $db->prepare($sql)) {
    $result->execute();
    $result->bind_result($rnaCnt);
    $result->fetch();
    $result->close();
}

$db->close();

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>PDX Portal</title>
		<meta name="keywords" content="PDX, UTSW">
		<meta name="description" content="A user-friendly Patient Derived Xenograft Web Portal">
		<meta http-equiv="X-UA-Compatible" content="IE=Edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" href="images/utsw_icon.jpg">
		<link rel="stylesheet" href="css/vendor/animate.min.css">
		<link rel="stylesheet" href="css/vendor/bootstrap.min.3.4.css">
		<link rel="stylesheet" href="css/vendor/fontawesome-all.min.css">
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="css/templatemo-style.css">

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

    	<!-- start home -->
    	<section id="home">
    		<div class="container">
    			<div class="row">
    				<div class="col-md-offset-1 col-md-10">
    					<h1 class="wow fadeIn" data-wow-offset="50" data-wow-delay="0.9s">Patient Derived Xenograft Web Portal </h1>
    					<div class="element">
                            <div class="sub-element">A user-friendly public platform</div>
                            <div class="sub-element">Data integration, analysis, sharing, and management</div>
							<div class="sub-element">A data entry system using standardized common data elements</div>
                        </div>
    					<a data-scroll href="discovery.php" class="btn btn-default wow fadeInUp" data-wow-offset="50" data-wow-delay="0.6s">DISCOVER</a>
    				</div>
    			</div>
    		</div>
    	</section>
    	<!-- end home -->

    	<!-- start number -->
		<section id="number">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
    					<h2 class="wow bounceIn" data-wow-offset="50" data-wow-delay="0.3s"> <span>NUMBER</span></h2>
    				</div>
					<div class="col-md-3 col-sm-3 col-xs-12 wow fadeInLeft" data-wow-offset="50" data-wow-delay="0.6s">
						<div class="media">
							<div class="media-heading-wrapper">
								<div class="media-object pull-left">
									<i class="fa fa-users"></i>
								</div>
								<h3 class="media-heading"><?php echo $pidCnt;?> PATIENT</h3>
							</div>
							<div class="media-body">
								<p>Total <?php echo $pidCnt;?> patients in PDX database</p>
							</div>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 col-xs-12 wow fadeInUp" data-wow-offset="50" data-wow-delay="0.9s">
						<div class="media">
							<div class="media-heading-wrapper">
								<div class="media-object pull-left">
									<i class="fa fa-prescription-bottle"></i>
								</div>
								<h3 class="media-heading"><?php echo $samCnt;?> SAMPLE</h3>
							</div>
							<div class="media-body">
								<p>Total <?php echo $samCnt;?> samples in PDX database</p>
							</div>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 col-xs-12 wow fadeInUp" data-wow-offset="50" data-wow-delay="0.9s">
						<div class="media">
							<div class="media-heading-wrapper">
								<div class="media-object pull-left">
									<i class="fa fa-dna"></i>
								</div>
								<h3 class="media-heading"><?php echo $dnaCnt;?> PDX DNA</h3>
							</div>
							<div class="media-body">
								<p>Total <?php echo $dnaCnt;?> samples are processed for PDX DNA.</p>
							</div>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 col-xs-12 wow fadeInRight" data-wow-offset="50" data-wow-delay="0.6s">
						<div class="media">
							<div class="media-heading-wrapper">
								<div class="media-object pull-left">
									<i class="fa rna"></i>
								</div>
								<h3 class="media-heading"><?php echo $rnaCnt;?> PDX RNA</h3>
							</div>
							<div class="media-body">
								<p>Total <?php echo $rnaCnt;?> samples are processed for PDX RNA.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- end number -->

		<!-- start copyright -->
		<?php include ("footer.php");?>
		<!-- end copyright -->
		<script src="js/vendor/jquery.js"></script>
		<script src="js/vendor/bootstrap.min.3.4.js"></script>
		<script src="js/vendor/jquery.singlePageNav.min.js"></script>
		<script src="js/vendor/typed.js"></script>
		<script src="js/vendor/wow.min.js"></script>
		<script src="js/custom.js"></script>
		<script type="text/javascript">
			$('.templatemo-nav ul li:first-child a').addClass('current');
		</script>
	</body>
</html>