<?php

session_start();
$_SESSION["pre_page"]=basename($_SERVER['PHP_SELF']);
$php_script_dir="php_script";
$php_class_dir="php_class";
include "{$php_script_dir}/authenticate_user_session.php";
require ("{$php_class_dir}/EscapeString.inc");
$operate=(isset($_GET['operate']) && $_GET['operate']!=="")?EscapeString::escape($_GET['operate']):null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">f
    <title>PDX Portal</title>
    <meta name="keywords" content="PDX, UTSW">
    <meta name="description" content="Biobank Sample Management System with feactures of sample inventory and tracking,
    , barcode generation and scanning, cohort discovery, online data analysis.">
    <meta name="author" content="Shin-Yi Lin">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="images/utsw_icon.jpg"/>
    <link rel="stylesheet" href="css/vendor/jquery-ui.css" type='text/css'>
    <link rel="stylesheet" href="css/vendor/animate.min.css" type='text/css'>
    <link rel="stylesheet" href="css/vendor/bootstrap.min.3.4.css">
    <link rel="stylesheet" href="css/vendor/fontawesome-all.min.css" type='text/css'>
    <link rel="stylesheet" href="css/vendor/jquery.dataTables.min.css"/>
    <link rel="stylesheet" href="css/vendor/buttons.dataTables.min.css"/>
    <link rel="stylesheet" href="css/vendor/responsive.dataTables.min.css"/>
    <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700'  type='text/css'>
    <link rel="stylesheet" href="css/templatemo-style.css" type='text/css'>
    <!--[if lt IE 9]>
    <script src="js/vendor/html5shiv.min.js"></script>
    <script src="js/vendor/respond.min.js"></script>
    <![endif]-->
    <style>
        .step-text{
            margin-top: 1.5em;
            margin-bottom: 2em;
            font-size: 2em;
            font-family: "Times New Roman", Times, serif;
        }
        hr {
            height: 4px;
            margin-left: 15px;
            margin-bottom:-3px;
        }
        .hr-primary{
            background-image: -webkit-linear-gradient(left, #000000, #9f9ba1, rgba(0,0,0,0))!important;
        }
        footer > .container > .row > .col-md-12 > p {
            visibility: visible!important;
        }
    </style>
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
<?php include("nav.php");?>
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

<section id="fh5co-services">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center" style="margin-top: 4rem;">
                <h2 class="wow bounceIn" data-wow-offset="50" data-wow-delay="0.3s"
                    style="border-bottom-width: 0;padding-bottom: 0;">
                    <span>
                        Search <br> Father & Mother Sample Information
                    </span>
                </h2>
            </div>
        </div>
        <br/>
        <div class="row">
            <div id="step1" class="col-md-12 text-center">
                <hr class="hr-primary" />
                <div class="step-text">
                    <b>STEP 1. Select a Sample UUID</b><br>
                    <div style="font-size: 0.7em;margin-top: 0.8em;">
                        The following samples all have father or mother sample.
                        Please select a sample UUID to display its father or mother sample information.
                    </div>
                </div>
                <table id="sample_table" class="display" style="width: 100%;">
                    <thead> <tr> <th>Sample UUID</th> <th>Existed Barcode</th>
                        <th>Sample ID</th>
                        <th>Local Sample ID</th><th>OpenSpecimen Sample ID</th>
                        <th>Patient ID</th>
                        <th>Initial Sample UUID</th>
                        <th>Direct Upstream Sample UUID</th><th>Date Derived From Direct Upstream Sample</th>
                        <th>Procedure Type</th><th>Procedure Date</th>
                        <th>Procedure Site</th><th>Procedure Site Laterality</th><th>Procedure Site Direction</th>
                        <th>Initial Sample (Specimen) Type</th><th>Sample Source</th>
                        <th>Sample Class</th><th>Sample Type</th><th>Sample Preparation Method</th>
                        <th>Amount Value</th><th>Amount Unit</th>
                        <th>Concentration Value</th><th>Concentration Unit</th>
                        <th>Storage Bank</th><th>Storage Room</th><th>Storage Container Type</th>
                        <th>Storage Container Temperature</th><th>Container Number</th>
                        <th>Shelf Number</th><th>Rack Number</th>
                        <th>Box Number</th><th>Position Number</th><th>Position Text</th>
                        <th>Date Sample Shipped</th><th>Date PDX Sample Generated</th><th>Date PDX Sample Received</th>
                        <th>Sample Contributor Institute</th><th>Data Matrix Barcode</th></tr> </thead>
                    <tbody></tbody>
                </table>
            </div>

            <div id="step2" class="col-md-12 text-center" style="display: none;">
                <hr class="hr-primary" />
                <div class="step-text">
                    <b>STEP 2. Display Father and Mother Sample Information</b>
                    <div id="msg" class="alert alert-warning col-md-offset-4 col-md-4" style="font-size: 0.7em;margin-top: 1em;"></div>
                </div>

                <table id="fm_sample_table" class="display" style="width: 100%;">
                    <thead> <tr> <th>Sample UUID</th> <th>Existed Barcode</th>
                        <th>Sample ID</th>
                        <th>Local Sample ID</th><th>OpenSpecimen Sample ID</th>
                        <th>Patient ID</th>
                        <th>Procedure Type</th><th>Procedure Date</th>
                        <th>Procedure Site</th><th>Procedure Site Laterality</th><th>Procedure Site Direction</th>
                        <th>Initial Sample (Specimen) Type</th><th>Sample Source</th>
                        <th>Sample Class</th><th>Sample Type</th><th>Sample Preparation Method</th>
                        <th>Amount Value</th><th>Amount Unit</th>
                        <th>Concentration Value</th><th>Concentration Unit</th>
                        <th>Storage Bank</th><th>Storage Room</th><th>Storage Container Type</th>
                        <th>Storage Container Temperature</th><th>Container Number</th>
                        <th>Shelf Number</th><th>Rack Number</th>
                        <th>Box Number</th><th>Position Number</th><th>Position Text</th>
                        <th>Sample Contributor Institute</th><th>Data Matrix Barcode</th></tr> </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div><br/><br/>
</section>

<?php include("footer.php"); ?>

<script src="js/vendor/jquery.js"></script>
<script src="js/vendor/jquery-ui.js"></script>
<script src="js/vendor/bootstrap.min.3.4.js"></script>
<script src="js/vendor/typed.js"></script>
<script src="js/vendor/wow.min.js"></script>
<!--Data Table Start-->
<script src="js/vendor/jquery.dataTables.min.js"></script>
<script src="js/vendor/dataTables.responsive.min.js"></script>
<script src="js/vendor/dataTables.buttons.min.js"></script>
<script src="js/vendor/buttons.flash.min.js"></script>
<script src="js/vendor/jszip.min.js"></script>
<script src="js/vendor/pdfmake.min.js"></script>
<script src="js/vendor/vfs_fonts.js"></script>
<script src="js/vendor/buttons.html5.min.js"></script>
<script src="js/vendor/buttons.print.min.js"></script>
<!--Data Table End-->
<script src="js/custom.js"></script>
<script src="js/sample_datatables.js"></script>

<script type="text/javascript">
    $(function () {
        $('.templatemo-nav ul li:nth-child(4) a').addClass('current');
        $('table thead th').addClass("text-center");

        /* Sample Data Table */
        const sampleTable = '#sample_table';
        sampleDataTables(sampleTable, "_singleSelect","","","hasFaMoSam","server_processing_sample.php");

        /* Father & Mother Sample Data Table */
        const fmSampleTable = '#fm_sample_table';
        sampleDataTables(fmSampleTable, "edit_delete","","","","server_processing_fm_sample.php","#msg");

        /* Action after submitting selected sample_uuid of Sample Data Table */
        const step2Divs="#step2";
        let checkedSamUuid = null;
        $(sampleTable).on("click","tbody td:first-child input[type='radio']",function(){
             if($(this).prop("checked") === true){
                 checkedSamUuid=$(this).val();
                 sampleDataTables(fmSampleTable, "<?php echo $operate;?>",checkedSamUuid,"","","server_processing_fm_sample.php","#msg");
                 if($(step2Divs).css("display") === "none"){
                     $(step2Divs).slideDown(1000);
                 }
             }
        });

        /* Action every time sample table is sorted  */
        $(sampleTable).on( 'order.dt',  function () {
            sampleDataTables(fmSampleTable, "edit_delete","","","","server_processing_fm_sample.php","#msg");
        }).DataTable();

    });
</script>
</body>
</html>

