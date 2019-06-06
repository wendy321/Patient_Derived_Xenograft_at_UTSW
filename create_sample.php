<?php
session_start();
$_SESSION["pre_page"]=basename($_SERVER['PHP_SELF']);
$php_script_class="php_class";
$php_script_dir="php_script";
include "{$php_script_dir}/authenticate_user_session.php";
$isErrIniSam=(isset($_GET["isErrIniSam"])&&$_GET["isErrIniSam"]!=="")?$_GET["isErrIniSam"]:null;
$isErrLocOpenSam=(isset($_GET["isErrLocOpenSam"])&&$_GET["isErrLocOpenSam"]!=="")?$_GET["isErrLocOpenSam"]:null;
$isWarnFaSamConf=(isset($_GET["isWarnFaSamConf"])&&$_GET["isWarnFaSamConf"]!=="")?$_GET["isWarnFaSamConf"]:null;
$isWarnMoSamConf=(isset($_GET["isWarnMoSamConf"])&&$_GET["isWarnMoSamConf"]!=="")?$_GET["isWarnMoSamConf"]:null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
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
    <link rel="stylesheet" href="css/vendor/fileinput.css" media="all"  type="text/css">
    <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700'  type='text/css'>
    <link rel="stylesheet" href="css/templatemo-style.css" type='text/css'>
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
            <div class="col-md-offset-2 col-md-8 text-justify">
                <div class="text-center">
                    <h3>How to automatically print out sample barcode? </h3>
                    <div id="instruction" class="text-center" style="display: none;padding:20px;">
                        <div id="svg_container">
                            <embed class="img-responsive" src="images/instruction/connect_BarTender_Database.svg"
                                   type="image/svg+xml">
                        </div>
                        <br>
                        <div>
                            <p class="text-justify">
                                If you would like to use <b> (BarTender Software- Enterprise Automation Edition) </b>
                                to print out the barcodes, please <a href = "mailto: shinyi.lin@utsouthwestern.edu">
                                    Send Email</a> to us to ask for the database account and password, and follow
                                <a href="https://support.seagullscientific.com/hc/en-us/articles/115004699828-Building
                                    -a-database-integration-Video-33-42-" target="_blank">
                                    Connect BarTender Software to Database Instruction</a>.
                                Then, you will be able to print out the sample barcode(s) after creating new sample(s)
                                in this web page.
                            </p>
                        </div>
                    </div>
                    <br>
                    <a id="instructionBottom" class="btn btn-default btn-sm">Read More ..</a>
                </div>
            </div>
        </div>
        <br><br>
        <div class="row" id="title">
            <div class="col-md-12 text-center">
                <h2 class="wow bounceIn" data-wow-offset="50" data-wow-delay="0.3s">
                    <span>Input Sample</span>
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="form-group">
                    <form method="post" action="<?php echo $php_script_dir;?>/send_create_sample_Single.php">
                        <div id="method1" class="radio">
                            <label class="radio-inline" for="method1input">
                                <input id="method1input" type="radio" name="method" value="1">
                            </label>
                            <span style="font-size: 2rem;"> <b>( Method 1 ) Input Single Sample Information </b> </span>
                            <p>All samples should have same variable list.</p>
                        </div>

                        <br>

                        <div class="alert alert-warning">
                            <div class="border-effect">
                                <p class="text-center" style="font-weight: 700">
                                    <em><i class="fa fa-asterisk fa-fw"></i></em> as required field
                                </p>
                                <h3 class="text-center"> INITIAL SAMPLE or DERIVED SAMPLE</h3>
                                <div class="text-justify">
                                    <b>Initial sample</b> is collected directly from procedure.
                                    <b>Derived sample</b> is derived from upstream sample which may be an initial sample
                                    or a derived sample.
                                    <img class="img-responsive" src="images/SMS_Sample_Derivation_Hierarchy_Example_v2.png" alt="sample derivation hierarchy"/>
                                </div><br>

                                <label for="ddl_isInitialSample">
                                    Initial Sample or Derived Sample?
                                    <em><i class="fa fa-asterisk fa-fw"></i></em>
                                </label>
                                <select class="form-control" id="ddl_isInitialSample" name="isInitialSample">
                                    <option value="1">Initial Sample</option>
                                    <option value="0">Derived Sample</option>
                                </select><br>

                                <div id="derive" style="display: none;">
                                    <hr>
                                    <label for="ddl_parentSampleUuid">
                                        Direct Upstream Sample UUID
                                        <small style="font-weight: 500"> (in database) </small>
                                    </label>
                                    <input class="form-control" id="ddl_parentSampleUuid" name="parentSampleUuid"
                                        pattern=".{0,36}" data-toggle="modal" data-target="#parent_sample_modal"/><br>

                                    <label for="ddl_sampleDerivedDate">
                                        Date Derived
                                        <small style="font-weight: 500"> (from direct upstream sample) </small>
                                    </label>
                                    <input type="date" class="form-control" id="ddl_sampleDerivedDate" name="sampleDerivedDate"
                                           placeholder="mm/dd/yyyy" /><br>

                                    <hr>

                                    <div id="inisample">
                                        <label for="ddl_initialSampleUuidDB">
                                            Initial Sample UUID
                                            <small style="font-weight: 500"> (in database) </small>
                                        </label>
                                        <input class="form-control" id="ddl_initialSampleUuidDB" name="initialSampleUuidDB"
                                               pattern=".{0,36}" data-toggle="modal" data-target="#ini_sample_modal"/><br>
                                    </div><br>
                                </div><br>
                            </div>

                            <hr>

                            <div class="border-effect">
                                <h3 class="text-center"> SAMPLE ID </h3>
                                <div class="text-justify">Please fill in <b>Local Sample ID, OpenSpecimen Sample ID, or
                                    both</b>. It's important to fill in at least one, so we can easily trace data and
                                    prevent from storing  duplicate samples into database. System will automatically
                                    generate a system-wide and format-unified <b>System Sample ID </b> later after
                                    adding the sample information.
                                </div><br>
                                <!-- Fill in either of two-->
                                <div class='form-group'>
                                    <label for="ddl_localSampleId" class="control-label">Local Sample ID (max 30 characters)
                                        <i class="fa fa-question-circle"
                                           title="Sample ID in your local system, not OpenSpecimen">
                                        </i>
                                        <em><i class="fa fa-asterisk fa-fw"></i></em>
                                    </label>
                                    <input type="text" class="form-control" id="ddl_localSampleId" name="localSampleId"
                                           pattern=".{0,30}"/>
                                    <span class="glyphicon glyphicon-remove form-control-feedback hidden"></span>
                                    <div class='error-msg text-left hidden'></div>
                                </div><br>
                                <div class="text-center" style="font-weight:700;">OR</div><br>
                                <div class='form-group'>
                                    <label for="ddl_openSpecSampleId" class="control-label">OpenSpecimen Sample ID (max 30 characters)
                                        <i class="fa fa-question-circle"
                                           title="Sample ID in OpenSpecimen">
                                        </i>
                                        <em><i class="fa fa-asterisk fa-fw"></i></em>
                                    </label>
                                    <input type="text" class="form-control" id="ddl_openSpecSampleId" name="openSpecSampleId"
                                           pattern=".{0,30}"/>
                                    <span class="glyphicon glyphicon-remove form-control-feedback hidden"></span>
                                    <div class='error-msg text-left hidden'></div>
                                </div><br>
                            </div>

                            <hr>

                            <div class="border-effect">
                                <h3 class="text-center"> SAMPLE</h3>
                                <label for="ddl_sampleSrc">Sample Source (for barcode labeling)
                                    <em><i class="fa fa-asterisk fa-fw"></i></em>
                                </label>
                                <select class="form-control" id="ddl_sampleSrc" name="sampleSrc" required>
                                    <option value="">Please select ...</option>
                                    <option value="01">Patient</option>
                                    <option value="02">PDX</option>
                                </select><br>

                                <label for="ddl_sampleClass">Sample Class (for barcode labeling)
                                    <em><i class="fa fa-asterisk fa-fw"></i></em>
                                </label>
                                <select class="form-control" id="ddl_sampleClass" name="sampleClass" required>
                                    <option label="Please select ..." value="">Please select ...</option>
                                    <option value="01">DNA</option>
                                    <option value="02">RNA</option>
                                    <option value="03">Protein</option>
                                    <option value="04">Tissue</option>
                                    <option value="05">Cell</option>
                                    <option value="06">Fluid</option>
                                    <option value="98">Other</option>
                                    <option value="99">Unknown</option>
                                </select><br>

                                <label for="ddl_sampleType">Sample Type
                                    <i class="fa fa-question-circle"
                                       title="You must select Sample Class first before selecting Sample Type.">
                                    </i>
                                </label>
                                <select class="form-control" id="ddl_sampleType" name="sampleType">
                                    <option label="Please select ..." value="">Please select ...</option>
                                    <optgroup label="DNA">
                                        <option value="11">DNA, Whole Genome Amplified DNA</option>
                                        <option value="14">DNA, Genomic DNA</option>
                                        <option value="12">DNA, cDNA</option>
                                        <option value="13">DNA, ctDNA</option>
                                        <option value="19">DNA, Not Specified</option>
                                    </optgroup>
                                    <optgroup label="RNA">
                                        <option value="21">RNA, poly-A enriched</option>
                                        <option value="22">RNA, Nuclear</option>
                                        <option value="23">RNA, Cytoplasmic</option>
                                        <option value="24">RNA, Total RNA</option>
                                        <option value="29">RNA, Not Specified</option>
                                    </optgroup>
                                    <optgroup label="Protein">
                                        <option value="39">Protein, Not Specified</option>
                                    </optgroup>
                                    <optgroup label="Tissue">
                                        <option value="41">Tissue, Tissue Block</option>
                                        <option value="42">Tissue, Tissue Slide</option>
                                        <option value="43">Tissue, Microdissected</option>
                                        <option value="49">Tissue, Not Specified</option>
                                    </optgroup>
                                    <optgroup label="Cell">
                                        <option value="51">Cell, Pleural Effusion All Cells</option>
                                        <option value="52">Cell, Pleural Effusion White Blood Cells</option>
                                        <option value="53">Cell, Peripheral Blood All Cells</option>
                                        <option value="54">Cell, Peripheral Blood White Cells</option>
                                        <option value="55">Cell, Peripheral Blood Mononuclear Cell (PBMC)</option>
                                        <option value="56">Cell, Cell Pellet</option>
                                        <option value="59">Cell, Not Specified</option>
                                    </optgroup>
                                    <optgroup label="Fluid">
                                        <option value="61">Fluid, Whole Blood</option>
                                        <option value="62">Fluid, Plasma</option>
                                        <option value="63">Fluid, Serum</option>
                                        <option value="64">Fluid, Bone Marrow</option>
                                        <option value="65">Fluid, Urine</option>
                                        <option value="66">Fluid, Saliva</option>
                                        <option value="67">Fluid, Cerebrospinal Fluid</option>
                                        <option value="68">Fluid, Pleural Fluid</option>
                                        <option value="69">Fluid, Ascites</option>
                                        <option value="610">Fluid, Lavage</option>
                                        <option value="611">Fluid, Body Cavity Fluid</option>
                                        <option value="612">Fluid, Milk</option>
                                        <option value="613">Fluid, Vitreous Fluid</option>
                                        <option value="614">Fluid, Gastric Fluid</option>
                                        <option value="615">Fluid, Amniotic Fluid</option>
                                        <option value="616">Fluid, Bile</option>
                                        <option value="617">Fluid, Synovial Fluid</option>
                                        <option value="618">Fuild, Sweat</option>
                                        <option value="619">Fuild, Feces</option>
                                        <option value="620">Fuild, Buffy Coat</option>
                                        <option value="621">Fuild, Sputum</option>
                                        <option value="699">Fluid, Not Specified</option>
                                    </optgroup>
                                    <optgroup label="Other">
                                        <option value="98">Other</option>
                                    </optgroup>
                                    <optgroup label="Unknown">
                                        <option value="99">Unknown</option>
                                    </optgroup>
                                </select><br>

                                <label>Sample Preparation
                                    <i class="fa fa-question-circle"
                                       title="Describe how the specimen was processed. Choose all that apply.">
                                    </i></label>
                                <div class="radio">
                                    <label class="radio-inline">
                                        <input type="radio" name="samplePre" value="1">Flash Frozen
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="samplePre" value="2">Frozen with OCT
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="samplePre" value="3">FFPE
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="samplePre" value="4">Fresh
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="samplePre" value="98">Other
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="samplePre" value="99">Unknown
                                    </label>
                                </div><br>
                            </div>

                            <hr>

                            <div class="border-effect">
                                <h3 class="text-center"> PROCEDURE </h3>
                                <label for="ddl_procedureType">Procedure Type
                                    <i class="fa fa-question-circle"
                                       title="The procedure type by which the initial sample was obtained.">
                                    </i>
                                    <span class="msg" style="font-weight: 500; color: blue;"></span>
                                </label>
                                <select class="form-control" id="ddl_procedureType" name="procedureType">
                                    <option value="">Please select ...</option>
                                    <option value="9">Autopsy</option>
                                    <option value="8">Leukapheresis</option>
                                    <option value="7">Needle Aspirate</option>
                                    <option value="1">Needle Core Biopsy</option>
                                    <option value="5">Skin Biopsy</option>
                                    <option value="2">Surgical Resection</option>
                                    <option value="3">Blood Collection</option>
                                    <option value="4">Saliva Collection</option>
                                    <option value="98">Other</option>
                                    <option value="99">Unknown</option>
                                </select><br>

                                <label for="ddl_procedureDate">Procedure Date
                                    <i class="fa fa-question-circle"
                                       title="The procedure date when the initial sample was obtained.">
                                    </i>
                                    <span class="msg" style="font-weight: 500; color: blue;"></span>
                                </label>
                                <input type="date" class="form-control" id="ddl_procedureDate" name="procedureDate" placeholder="mm/dd/yyyy"/><br>

                                <label for="ddl_specimenType">Inital Sample (Specimen) Type (for barcode labeling)
                                    <em><i class="fa fa-asterisk fa-fw"></i></em>
                                    <span class="msg" style="font-weight: 500; color: blue;"></span>
                                </label>
                                <select class="form-control" id="ddl_specimenType" name="specimenType" required>
                                    <option value="">Please select ...</option>
                                    <option value="01">Primary Solid Tumor</option>
                                    <option value="02">Recurrent Solid Tumor</option>
                                    <option value="12">Primary Blood Derived Cancer - Bone Marrow</option>
                                    <option value="13">Recurrent Blood Derived Cancer - Bone Marrow</option>
                                    <option value="03">Metastatic Tumor</option>
                                    <option value="11">Solid Tissue Normal</option>
                                    <option value="14">Germline, Blood </option>
                                    <option value="15">Germline, Saliva </option>
                                    <option value="98">Other</option>
                                    <option value="99">Unknown</option>
                                </select><br>

                                <label for="ddl_priTumorSite">Site
                                    <span class="msg" style="font-weight: 500; color: blue;"></span>
                                </label>
                                <select class="form-control" id="ddl_priTumorSite" name="priTumorSite">
                                    <option value="">Please select ...</option>
                                    <option value="10">Abdomen, NOS</option>
                                    <option value="11">Adrenal gland, NOS</option>
                                    <option value="12">Bone Marrow</option>
                                    <option value="13">Brain, NOS</option>
                                    <option value="14">Cerebellum, NOS</option>
                                    <option value="15">Cerebral meninges</option>
                                    <option value="16">Frontal lobe</option>
                                    <option value="17">Parietal lobe</option>
                                    <option value="18">Intra-abdominal lymph nodes</option>
                                    <option value="19">Kidney, NOS</option>
                                    <option value="3">Liver</option>
                                    <option value="20">Long bones of upper limb, scapula and associated joints</option>
                                    <option value="21">Long bones of lower limb and associated joints</option>
                                    <option value="22">Lung, NOS</option>
                                    <option value="23">Lymph nodes of head, face and neck</option>
                                    <option value="24">Lymph node, NOS</option>
                                    <option value="25">Mandible</option>
                                    <option value="4">Mediastinum, NOS</option>
                                    <option value="26">Mouth, NOS</option>
                                    <option value="5">Ovary</option>
                                    <option value="27">Parotid gland</option>
                                    <option value="28">Pelvis, NOS</option>
                                    <option value="29">Pleura, NOS</option>
                                    <option value="6">Retroperitoneum</option>
                                    <option value="30">Rib, Sternum, Clavicle and associated joints</option>
                                    <option value="31">Short bones of upper limb and associated joints</option>
                                    <option value="32">Short bones of lower limb and associated joints</option>
                                    <option value="33">Skin of scalp and neck</option>
                                    <option value="34">Spinal cord</option>
                                    <option value="8">Testis, NOS</option>
                                    <option value="35">Thorax, NOS</option>
                                    <option value="9">Vagina, NOS</option>
                                    <option value="98">Other</option>
                                    <option value="99">Unknown</option>
                                </select><br>

                                <label for="ddl_priTumorLater">
                                    Site - Laterality
                                    <span class="msg" style="font-weight: 500; color: blue;"></span>
                                </label>
                                <select class="form-control" id="ddl_priTumorLater" name="priTumorLater">
                                    <option value="">Please select ...</option>
                                    <option value="1">Left</option>
                                    <option value="2">Right</option>
                                    <option value="3">Bilateral</option>
                                    <option value="98">Other</option>
                                    <option value="99">Unknown</option>
                                </select><br>

                                <label for="ddl_priTumorDir">
                                    Site - Direction
                                    <span class="msg" style="font-weight: 500; color: blue;"></span>
                                </label>
                                <select class="form-control" id="ddl_priTumorDir" name="priTumorDir">
                                    <option value="">Please select ...</option>
                                    <option value="1">Proximal</option>
                                    <option value="2">Distal</option>
                                    <option value="3">Anterior</option>
                                    <option value="4">Posterior</option>
                                    <option value="98">Other</option>
                                    <option value="99">Unknown</option>
                                </select><br>

                            </div>

                            <div id="detail" style="display: none">

                                <hr>

                                <div class="border-effect">
                                    <h3 class="text-center"> EXIST BARCODE</h3>
                                    <p>Input the existed barcode of the sample, if there has one.
                                        Otherwise, system will automatically generate a new
                                        <span class="text-justify" style="text-decoration: underline;cursor: pointer"
                                              data-toggle="popover" data-placement="right" title="System Barcode Format"
                                              data-content="Patient_ID +
                                              Specimen_Type + Sample_Class +
                                              ('Patient_Tumor' or 'PDX') + auto-increment integer" >System Barcode
                                        </span> for you.
                                    </p>
                                    <label for="ddl_existedBarcode">Existed Sample Barcode (max 255 characters)</label>
                                    <input type="text" class="form-control" id="ddl_existedBarcode" name="existedBarcode"
                                           pattern=".{0,255}"/><br>
                                </div>

                                <hr>

                                <div class="border-effect">
                                    <h3 class="text-center"> PATIENT ID</h3>
                                    <div class="text-justify">
                                        If the system patient id is not in our system yet, please leave
                                        it empty and add it later. You can store a new patient info into the database by going to
                                        <a href="create_patient.php" target="_blank">create patient page</a>.
                                    </div><br>
                                    <label for="ddl_systemPatientId">
                                        System Patient ID
                                        <span class="msg" style="font-weight: 500; color: blue;"></span>
                                    </label>
                                    <input type="text" class="form-control" id="ddl_systemPatientId" name="systemPatientId"
                                           pattern=".{0,7}" data-toggle="modal" data-target="#patient_modal"/><br>
                                </div>

                                <hr>

                                <div class="border-effect">
                                    <h3 class="text-center"> FATHER SAMPLE ID </h3>
                                    <div class="text-justify">
                                        Please fill in <b>Father Local Sample ID, Father OpenSpecimen Sample ID, or
                                        both</b>. It's important to fill in at least one, so we can easily trace data and
                                        prevent from storing  duplicate samples into database. System will automatically
                                        generate a system-wide and format-unified <b>Father System Sample ID </b> later after
                                        adding the sample information.
                                    </div><br>
                                    <div class='form-group'>
                                        <label for="ddl_fatherLocalSampleId" class="control-label">
                                            Father Local Sample ID (max 30 characters)
                                            <i class="fa fa-question-circle"
                                               title="Father Sample ID in your local system, not OpenSpecimen">
                                            </i>
                                        </label>
                                        <input type="text" class="form-control" id="ddl_fatherLocalSampleId"
                                               name="fatherLocalSampleId" pattern=".{0,30}"/>
                                        <span class="glyphicon glyphicon-remove form-control-feedback hidden"></span>
                                        <div class='error-msg text-left hidden'></div>
                                    </div><br>
                                    <div class="text-center" style="font-weight:700;">OR</div><br>
                                    <div class='form-group'>
                                        <label for="ddl_fatherOpenSpecSampleId" class="control-label">
                                            Father OpenSpecimen Sample ID (max 30 characters)
                                            <i class="fa fa-question-circle"
                                               title="Father Sample ID in OpenSpecimen">
                                            </i>
                                        </label>
                                        <input type="text" class="form-control" id="ddl_fatherOpenSpecSampleId"
                                               name="fatherOpenSpecSampleId" pattern=".{0,30}"/>
                                        <span class="glyphicon glyphicon-remove form-control-feedback hidden"></span>
                                        <div class='error-msg text-left hidden'></div>
                                    </div><br>
                                </div>

                                <hr>

                                <div class="border-effect">
                                    <h3 class="text-center"> MOTHER SAMPLE ID </h3>
                                    <div class="text-justify">
                                        Please fill in <b>Mother Local Sample ID, Mother OpenSpecimen Sample ID, or
                                        both</b>. It's important to fill in at least one, so we can easily trace data and
                                        prevent from storing  duplicate samples into database. System will automatically
                                        generate a system-wide and format-unified <b>Mother System Sample ID </b> later after
                                        adding the sample information.
                                    </div><br>
                                    <div class='form-group'>
                                        <label for="ddl_motherLocalSampleId" class="control-label">
                                            Mother Local Sample ID (max 30 characters)
                                            <i class="fa fa-question-circle"
                                               title="Mother Sample ID in your local system, not OpenSpecimen">
                                            </i>
                                        </label>
                                        <input type="text" class="form-control" id="ddl_motherLocalSampleId"
                                               name="motherLocalSampleId" pattern=".{0,30}"/>
                                        <span class="glyphicon glyphicon-remove form-control-feedback hidden"></span>
                                        <div class='error-msg text-left hidden'></div>
                                    </div><br>
                                    <div class="text-center" style="font-weight:700;">OR</div><br>
                                    <div class='form-group'>
                                        <label for="ddl_motherOpenSpecSampleId" class="control-label">
                                            Mother OpenSpecimen Sample ID (max 30 characters)
                                            <i class="fa fa-question-circle"
                                               title="Mother Sample ID in OpenSpecimen">
                                            </i>
                                        </label>
                                        <input type="text" class="form-control" id="ddl_motherOpenSpecSampleId"
                                               name="motherOpenSpecSampleId" pattern=".{0,30}"/>
                                        <span class="glyphicon glyphicon-remove form-control-feedback hidden"></span>
                                        <div class='error-msg text-left hidden'></div>
                                    </div><br>
                                </div>

                                <hr>

                                <div class="border-effect">
                                    <h3 class="text-center"> AMOUNT </h3>
                                    <label for="ddl_quantityNum">Amount Value</label>
                                    <input type="number" class="form-control" id="ddl_quantityNum" name="quantityNum"
                                           step="0.00001" max="99999.99999"/><br>

                                    <label for="ddl_quantityUnit">Amount Unit</label>
                                    <select class="form-control" id="ddl_quantityUnit" name="quantityUnit">
                                        <option value="">Please select ...</option>
                                        <option value="1">μg</option>
                                        <option value="2">mg</option>
                                        <option value="3">g</option>
                                        <option value="4">μL</option>
                                        <option value="5">mL</option>
                                        <option value="6">scrolls</option>
                                        <option value="7">cassettes</option>
                                        <option value="8">slides</option>
                                        <option value="9">blocks</option>
                                        <option value="10">unspecified</option>
                                    </select><br>
                                </div>

                                <hr>

                                <div class="border-effect">
                                    <h3 class="text-center"> CONCENTRATION </h3>
                                    <label for="ddl_concenNum">Concentration Value</label>
                                    <input type="number" class="form-control" id="ddl_concenNum" name="concenNum"
                                           step="0.00001" max="99999.99999"/><br>

                                    <label for="ddl_concenUnit">Concentration Unit (max 30 characters)</label>
                                    <input type="text" class="form-control" id="ddl_concenUnit" name="concenUnit"
                                           maxlength="30" placeholder="ng/μL"/><br>
                                </div>

                                <hr>

                                <div class="border-effect">
                                    <h3 class="text-center"> STORAGE </h3>
                                    <label for="ddl_bank">Bank
                                        <i class="fa fa-question-circle"
                                           title="Where the sample is kept in.">
                                        </i>
                                    </label>
                                    <select class="form-control" id="ddl_bank" name="bank">
                                        <option label="Please select ..." value="">Please select ...</option>
                                        <option value="1">UT Health Science Center at San Antonio</option>
                                        <option value="2">UT Southwestern Medical Center - Skapek</option>
                                        <option value="3">UT Southwestern Medical Center - Precision Medicine Lab</option>
                                        <option value="99">Unknown</option>
                                    </select><br>

                                    <label for="ddl_room">Storage Room</label>
                                    <select class="form-control" id="ddl_room" name="room">
                                        <option label="Please select ..." value="">Please select ...</option>
                                        <option value="3">UTSW, Precision Medicine Lab</option>
                                        <option value="99">Unknown</option>
                                    </select><br>

                                    <label for="ddl_containerType">Container Type</label>
                                    <select class="form-control" id="ddl_containerType" name="containerType">
                                        <option value="">Please select ...</option>
                                        <option value="1">Freezer</option>
                                        <option value="2">Storage Cabinet</option>
                                        <option value="3">Refrigerator</option>
                                        <option value="4">Tank</option>
                                        <option value="97">Lab, pending</option>
                                        <option value="99">Unknown</option>
                                    </select><br>

                                    <label for="ddl_containerTemp">Container Temperature</label>
                                    <select class="form-control" id="ddl_containerTemp" name="containerTemp">
                                        <option value="">Please select ...</option>
                                        <option value="1">-20 &#8451;</option>
                                        <option value="2">-80 &#8451;</option>
                                        <option value="3">4 &#8451;</option>
                                        <option value="4">Room Temperature</option>
                                        <option value="5">Liquid Nitrogen</option>
                                    </select><br>

                                    <label for="ddl_containerNum">Container Number</label>
                                    <input type="number" class="form-control" id="ddl_containerNum" name="containerNum" min="1" max="99"/><br>

                                    <label for="ddl_shelfNum">Shelf Number</label>
                                    <input type="number" class="form-control" id="ddl_shelfNum" name="shelfNum" min="1" max="99"/><br>

                                    <label for="ddl_rackNum">Rack Number</label>
                                    <input type="number" class="form-control" id="ddl_rackNum" name="rackNum" min="1" max="99"/><br>

                                    <label for="ddl_boxNum">Box Number</label>
                                    <input type="number" class="form-control" id="ddl_boxNum" name="boxNum" min="1" max="99"/><br>

                                    <label for="ddl_posNum">Position Number</label>
                                    <input type="number" class="form-control" id="ddl_posNum" name="posNum" min="1" max="100"/><br>

                                    <label for="ddl_posTxt">Position Text (max 5 characters)</label>
                                    <input type="text" class="form-control" id="ddl_posTxt" name="posTxt" pattern=".{0,5}"/><br>
                                </div>

                                <hr>

                                <div class="border-effect">
                                    <h3 class="text-center"> DATE </h3>
                                    <label for="ddl_shipDate">Date Patient Tumor Sample Shipped
                                        <i class="fa fa-question-circle"
                                           title="The date on which the sample was shipped to San Antonio.">
                                        </i>
                                        <span class="msg" style="font-weight: 500; color: blue;"></span>
                                    </label>
                                    <input type="date" class="form-control" id="ddl_shipDate" name="shipDate" placeholder="mm/dd/yyyy"/><br>

                                    <label for="ddl_pdxGenDate">Date PDX Sample Generated
                                        <i class="fa fa-question-circle"
                                           title="The date on which the PDX sample was generated.">
                                        </i>
                                        <span class="msg" style="font-weight: 500; color: blue;"></span>
                                    </label>
                                    <input type="date" class="form-control" id="ddl_pdxGenDate" name="pdxGenDate" placeholder="mm/dd/yyyy"/><br>

                                    <label for="ddl_pdxRecDate">Date PDX Sample Received
                                        <i class="fa fa-question-circle"
                                           title="The date on which the PDX sample was received back.">
                                        </i>
                                        <span class="msg" style="font-weight: 500; color: blue;"></span>
                                    </label>
                                    <input type="date" class="form-control" id="ddl_pdxRecDate" name="pdxRecDate" placeholder="mm/dd/yyyy"/><br>
                                </div>

                                <hr>

                                <div class="border-effect">
                                    <h3 class="text-center"> CONTRIBUTOR </h3>
                                    <label for="ddl_institute">Sample Contributor Institute</label>
                                    <select class="form-control" id="ddl_institute" name="institute">
                                        <option value="">Please select ...</option>
                                        <option value="9">Methodist Hospital</option>
                                        <option value="10">OSU</option>
                                        <option value="11">UT Health Science Center at San Antonio</option>
                                        <option value="1">UT Southwestern Medical Center</option>
                                        <option value="98">Other (Please specify institute name in the NOTES field)</option>
                                        <option value="99">Unknown</option>
                                    </select><br>
                                </div>

                                <hr>

                                <div class="border-effect">
                                    <h3 class="text-center"> NOTE </h3>
                                    <label>Notes</label>
                                    <textarea id="txt_notes" class="form-control" name="notes"
                                              placeholder="Enter Your Notes (max 100 characters)"
                                              rows="5" maxlength="100"></textarea>
                                </div>
                            </div>
                            <br/>
                            <div id="seemore" class="btn btn-info">See More Variables</div>
                            <br/><br/>
                            <input id="submitbtn"  type="submit" class="btn btn-primary" value="Submit"/>
                            <sub><em><i class="fa fa-asterisk fa-fw"></i></em> as required field</sub>
                        </div>


                        <div id="method2" class="radio">
                            <label class="radio-inline" for="method2input">
                                <input id="method2input" type="radio" name="method" value="2">
                            </label>
                            <span style="font-size: 2rem;"> <b>( Method 2 ) Upload Multiple Sample Information</b> (xlsx file, <= 1 Mb)</span>
                        </div>

                        Download
                        <a href="example/sample_batch_upload_example_PDX_V2.xlsx"> Template </a> and
                        <a href="example/Sample_Codebook_PDX_V5.xlsx">Sample Codebook</a>
                        <br>
                        <div class="alert alert-success">
                            <div class="file-loading">
                                <input id="inputfile" name="inputfile" type="file" accept="application/vnd.openxmlformats-
                            officedocument.spreadsheetml.sheet,application/vnd.ms-excel" size="1">
                            </div>
                            <div id="filesuccessmsg" class="alert alert-success" style="margin-top:10px;display:none"><ul></ul></div>
                            <div id="filefailmsg" class="alert alert-danger" style="margin-top:10px;display:none"><ul></ul></div>
                        </div>
                    </form>
                </div>
            </div>


            <div id="parent_sample_modal" class="modal fade text-center" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h2 class="modal-title">Select a Initial Sample</h2>
                        </div>
                        <div class="modal-body" style="padding:24px;">
                            <br/><br/>
                            <table id="parent_sample_table" class="display responsive" style="width: 100%;">
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
                                    <th>Storage Bank</th><th>Storage Room</th><th>Storage Cabinet Type</th>
                                    <th>Storage Cabinet Temperature</th><th>Cabinet Number</th>
                                    <th>Shelf Number</th><th>Rack Number</th>
                                    <th>Box Number</th><th>Position Number</th><th>Position Text</th>
                                    <th>Date Sample Shipped</th><th>Date PDX Sample Generated</th><th>Date PDX Sample Received</th>
                                    <th>Sample Contributor Institute</th><th>Data Matrix Barcode</th></tr> </thead>
                                <tbody></tbody>
                            </table>
                        </div><br/>
                        <div class="modal-footer">
                            <button id="deselect_psid" type="button" class="btn btn-warning">Deselect</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Confirm</button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="ini_sample_modal" class="modal fade text-center" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h2 class="modal-title">Select a Initial Sample</h2>
                        </div>
                        <div class="modal-body" style="padding:24px;">
                            <br/><br/>
                            <table id="ini_sample_table" class="display responsive" style="width: 100%;">
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
                                    <th>Storage Bank</th><th>Storage Room</th><th>Storage Cabinet Type</th>
                                    <th>Storage Cabinet Temperature</th><th>Cabinet Number</th>
                                    <th>Shelf Number</th><th>Rack Number</th>
                                    <th>Box Number</th><th>Position Number</th><th>Position Text</th>
                                    <th>Date Sample Shipped</th><th>Date PDX Sample Generated</th><th>Date PDX Sample Received</th>
                                    <th>Sample Contributor Institute</th><th>Data Matrix Barcode</th></tr> </thead>
                                <tbody></tbody>
                            </table>
                        </div><br/>
                        <div class="modal-footer">
                            <button id="deselect_isid" type="button" class="btn btn-warning">Deselect</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Confirm</button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="patient_modal" class="modal fade text-center" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h3 class="modal-title">Select a Patient</h3>
                        </div>
                        <div class="modal-body" style="padding:24px;">
                            <br/><br/>
                            <table id="patient_table" class="display" style="width:100%">
                                <thead>
                                <tr>
                                    <th>System Patient ID</th>
                                    <th>Local Patient ID</th>
                                    <th>OpenSpecimen Patient ID</th>
                                    <th>Final Diagnosis</th>
                                    <th>Metastatic At Diagnosis</th>
                                    <th>Therapy</th>
                                    <th>Vital Status</th>
                                    <th>Sex</th>
                                    <th>Race</th>
                                    <th>Ethnic</th>
                                    <th>Age at Diagnosis (Months)</th>
                                    <th>Age at Diagnosis (Year-Old)</th>
                                    <th>Age at Diagnosis (A.D. Year)</th>
                                    <th>Tumor Type</th>
                                    <th>Tumor Site</th>
                                    <th>Tumor Site Laterality </th>
                                    <th>Tumor Site Direction </th>
                                    <th>Notes</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div><br/>
                        <div class="modal-footer">
                            <button id="deselect_pid" type="button" class="btn btn-warning">Deselect</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Confirm</button>
                        </div>
                    </div>
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
<script src="js/vendor/jquery-ui.js"></script>
<script src="js/vendor/bootstrap.min.3.4.js"></script>
<script src="js/vendor/typed.js"></script>
<script src="js/vendor/wow.min.js"></script>
<script src="js/vendor/jquery.dataTables.min.js"></script>
<script src="js/vendor/dataTables.responsive.min.js"></script>
<script src="js/vendor/fileinput.js" type="text/javascript"></script>
<script src="js/custom.js"></script>
<script src="js/create_pages.js"></script>
<script src="js/create_sample_page.js"></script>
<script type="text/javascript">
    $(function () {
        $('.templatemo-nav ul li:nth-child(4) a').addClass('current');

        <?php
        if($isErrIniSam == "1" || $isErrLocOpenSam == "1" || $isWarnFaSamConf == "1" || $isWarnMoSamConf == "1") {
            $titleStr = "<div class=\"col-md-offset-2 col-md-8 alert alert-danger alert-dismissable\" id=\"wholeMsg\">".
                "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>".
                "<div class=\"text-center\"><b>Fail Message!</b></div><p></p></div>";
            echo "$('#title').append('".$titleStr."');";

            if ($isErrIniSam == "1") {
                echo "$('#wholeMsg').find('p')
                        .append('<br><em><i class=\"fa fa-asterisk fa-fw\" style=\"font-size:0.8em;color: #a94442\"></i></em> '
                        +'Initial_Sample_or_Derived_Sample field is required.');";
                echo "$('#ddl_isInitialSample').on('change',function(){
                        $('#wholeMsg').find('a').trigger('click');
                      });";
            }
            if ($isErrLocOpenSam == "1") {
                echo "$('#wholeMsg').find('p')
                        .append('<br><em><i class=\"fa fa-asterisk fa-fw\" style=\"font-size:0.8em;color: #a94442\"></i></em> '
                        +'Please fill in at least one of the Local_Sample_ID and '
                        +'the OpenSpecimen_Sample_ID.');";
                echo "$('#ddl_localSampleId, #ddl_openSpecSampleId').on('change',function(){
                        $('#wholeMsg').find('a').trigger('click');
                     });";
            }

            if ($isWarnFaSamConf == "1") {
                echo "$('#wholeMsg').find('p')
                    .append('<br><em><i class=\"fa fa-asterisk fa-fw\" style=\"font-size:0.8em;color: #a94442\"></i></em> '
                    +'Sample_UUIDs of Father Local_Sample_ID and Father OpenSpecimen_Sample_ID conflict in database.'
                    +'<br> Please fill in another Father Local_Sample_ID or Father OpenSpecimen_Sample_ID.');";
                echo "$('#ddl_fatherLocalSampleId, #ddl_fatherOpenSpecSampleId').on('change',function(){
                        $('#wholeMsg').find('a').trigger('click');
                     });";
            }

            if ($isWarnMoSamConf == "1") {
                echo "$('#wholeMsg').find('p')
                    .append('<br><em><i class=\"fa fa-asterisk fa-fw\" style=\"font-size:0.8em;color: #a94442\"></i></em> '
                    +'Sample_UUIDs of Mother Local_Sample_ID and Mother OpenSpecimen_Sample_ID conflict in database.'
                    +'<br> Please fill in another Mother Local_Sample_ID or Mother OpenSpecimen_Sample_ID.');";
                echo "$('#ddl_motherLocalSampleId, #ddl_motherOpenSpecSampleId').on('change',function(){
                        $('#wholeMsg').find('a').trigger('click');
                     });";
            }
        }

        ?>
        // toggle read more and read less
        toggleReadMoreOrLess("#instructionBottom", "Close", "Read More ..", "#instruction", "slow" , 'linear');
        toggleReadMoreOrLess("#seemore", "Less ..", "See More Variables", "#detail", "fast", "linear");

        // toggle method 1 and method 2
        // let is supported since ECMAScript 2015 (i.e. ES6)
        const method1tag=$("#method1");
        const method1detail=$(method1tag).next().next();
        const method2tag=$("#method2");
        const method2detail=$(method2tag).next().next().next();
        toggleOfTwoMethods (method1tag, method1detail, method2tag, method2detail);

        // border animation
        const borderDiv='.border-effect';
        const initialBorderCss = {"border":"0.5px solid #fcf8e3", "border-radius":"3px", "padding":"10px", "margin":"0"};
        const mouseEnterBorderCss={"border-color":"#8a6d3b","box-shadow":"3px 3px 8px 3px #8a6d3b"};
        const mouseLeaveBorderCss={"border-color":"#fcf8e3","box-shadow":"none"};
        const titleInBorderDiv=$(borderDiv).find('h3');
        const initialTitleCss={"color":"#60481e","font-weight":"650"};
        borderEffectOfMouseEnterLeaveDiv(borderDiv, initialBorderCss, mouseEnterBorderCss, mouseLeaveBorderCss,
            titleInBorderDiv,initialTitleCss);

        // date picker
        makeDatePickerCompatibleAllBrowsers();

        // animation by selecting initial sample or selecting derived sample
        const isIniSam_input = "#ddl_isInitialSample";
        const derive_div = "#derive";
        const psample_input=$('#ddl_parentSampleUuid');
        const isample_input=$('#ddl_initialSampleUuidDB');
        showOrHideByValue(derive_div, isIniSam_input, "0", "1");
        $(isIniSam_input).on("change",function(){
            showOrHideByValue(derive_div, this, "0", "1");
            clearByValue([{id: psample_input,clearTo:""}, {id:"#ddl_sampleDerivedDate",clearTo:""},
                {id:isample_input,clearTo:""}], this, "1");
            if($(this).val()==="1") cleanComValsInCurrentSample();
        });

        // Check whether local_sample_id, openspecimen_sample_id, not including father's and mother's, already exist in database
        const sampleIdArr = [{id:'#ddl_localSampleId',field:"Local_Sample_ID"}, {id:'#ddl_openSpecSampleId',field:"OpenSpecimen_Sample_ID"}];
        var checkedInputs = [];
        sampleIdArr.forEach(function(elem){
            checkedInputs.push(elem.id);
            $(elem.id).on("change",function(){
                let val = $(this).val();
                checkExistInDBExcept("Sample",elem.field,val,elem.id,"",checkedInputs,"#submitbtn");
            });
        });

        // show or hide the sample type opt based on the selected sample class
        const input_sampleClass="#ddl_sampleClass";
        const input_sampleClass_defaultTxt="Please select ...";
        const input_sampleType="#ddl_sampleType";
        toggleRelateOptgrpInput(input_sampleClass,input_sampleClass_defaultTxt,input_sampleType);
        $(input_sampleClass).on('change',function(){
            toggleRelateOptgrpInput(this,input_sampleClass_defaultTxt,input_sampleType);
        });

        /**
         * Parent Sample UUID Input & Initial Sample UUID Input & Sample DataTable Interaction
         */
        const sample_data_table_script="server_processing_sample";
        const psample_modal = $('#parent_sample_modal');
        const isample_modal = $('#ini_sample_modal');
        const psample_table=$('#parent_sample_table');
        const isample_table=$('#ini_sample_table');
        const psample_deselect_id = $('#deselect_psid');
        const isample_deselect_id = $('#deselect_isid');
        inputAndModalInteractionSample(psample_input, psample_modal, psample_table, psample_deselect_id,
            isample_input, isample_modal, isample_table, isample_deselect_id, sample_data_table_script,"singleSelect");


        /**
         *  Patient ID Input & Patient DataTable Interaction
         */
        const patient_input=$('#ddl_systemPatientId');
        const patient_modal = $('#patient_modal');
        const patient_table=$('#patient_table');
        const patient_data_table_script="server_processing_patient";
        const patient_deselect_id = $('#deselect_pid');
        inputAndModalInteraction(patient_input, patient_modal, patient_table, patient_data_table_script, patient_deselect_id,"singleSelect");


        /**
         *  Method 2: Batch sample input interaction
         */
        const fileEle="#inputfile";
        const uploadUrl="<?php echo $php_script_dir;?>/send_create_sample_XLSX.php";
        const minFileCnt=1;
        const maxFileCnt=1;
        const maxFileSize=1000;// 1 Mb
        const inputMethodEle="#method2input";
        const successMsgEle="#filesuccessmsg";
        const failMsgEle="#filefailmsg";
        uploadExcelFile(fileEle,uploadUrl,minFileCnt,maxFileCnt,maxFileSize,inputMethodEle,successMsgEle,failMsgEle);
    });
</script>

</body>
</html>
