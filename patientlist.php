<?php

session_start();
$_SESSION["pre_page"]=basename($_SERVER['PHP_SELF']);
$php_script_dir="php_script";
$php_class_dir="php_class";
include "{$php_script_dir}/authenticate_user_session.php";
require ("{$php_class_dir}/EscapeString.inc");
$operate=(isset($_GET['operate']) && $_GET['operate']!=="")?EscapeString::escape($_GET['operate']):null;
$pid=(isset($_GET['pid']) && $_GET['pid']!=="")?EscapeString::escape($_GET['pid']):null;
$item='';
if(strpos($operate,'edit') > -1){
    $item=(isset($_GET['item']) && $_GET['item']!=="")?EscapeString::escape($_GET['item']):null;
}

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
            <div class="col-md-12 text-center" style="margin-top: 4rem;">
                <h2 class="wow bounceIn" data-wow-offset="50" data-wow-delay="0.3s">
                    <span>
                        <?php
                        $echoStr="";
                        if(strpos($operate,"edit") > -1 || strpos($operate,"delete") > -1){
                            $echoStr.="Edit Patient Information";
                        }else{
                            $echoStr.="View Patient Information";
                        }
                        echo $echoStr."<br>";
                        ?>
                    </span>
                </h2>
                <?php
                if(strpos($operate,'delete') > -1 ){
                    echo "<div id=\"deletePatientMsg\" class=\"row\"></div>";
                }
                ?>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="col-md-12 text-center">
                <table id="patient_table" class="display" style="width: 100%;">
                    <thead> <tr> <th>Patient ID</th>
                        <th>Local Patient ID</th>
                        <th>OpenSpecimen Patient ID</th>
                        <th>Final Diagnosis</th>
                        <th>Metastatic At Diagnosis</th>
                        <th>Therapy</th>
                        <th>Vital Status</th>
                        <th>Sex</th>
                        <th>Race</th>
                        <th>Ethnic</th>
                        <th>Age At Diagnosis (Months)</th>
                        <th>Age At Diagnosis (Year Old)</th>
                        <th>Age At Diagnosis (A.D.)</th>
                        <th>Primary Or Metastasis Tumor</th>
                        <th>Tumor Site</th>
                        <th>Tumor Laterality</th>
                        <th>Tumor Direction</th>
                        <th>Father Patient ID</th>
                        <th>Mother Patient ID</th>
                        <th>Note</th></tr> </thead>
                    <tbody></tbody>
                </table>
                <br/>
                <div id="delete_patient_modal" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h3 class="modal-title">Delete a Patient</h3>
                            </div>
                            <div class="modal-body" style="padding:24px;">
                                Are you sure to delete Patient ID: <b></b> ?
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary" data-dismiss="modal" type="button"> Cancel</button>
                                <button id="deletePatientBtn" class="btn btn-danger" data-dismiss="modal" type="button"> Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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

<script type="text/javascript">
    $(function () {
        $('.templatemo-nav ul li:nth-child(4) a').addClass('current');
        $('table thead th').addClass("text-center");

        /* Patient Data Table */
        var patient_table=$('#patient_table');

        // Add search input box for each table column
        $(patient_table).find('thead th').each( function (i) {
            let placeHolder=" Search";
            let title = $(this).text();
            $(this).html( title+'<input type="text" placeholder=" '+ placeHolder + ' "/>' );
        });

        // Instantiate Patient Data Table
        if (!($.fn.DataTable.isDataTable(patient_table))){
            let exportFormat={
                format: {
                    body: function ( data, rowIdx, columnIdx, node ) {
                        return data.replace(/(<(?!\/?(svg|g|rect)(?=>|\s.*>))\/?(.|\n)*?>)|(Edit)|(Delete)/gm, '');
                    }
                }
            };

            $(patient_table).DataTable({
                "dom": 'Bfrtip',
                buttons: [
                    {
                        extend: 'copy',
                        exportOptions: exportFormat
                    },
                    {
                        extend: 'csv',
                        exportOptions: exportFormat
                    },
                    {
                        extend: 'excel',
                        exportOptions: exportFormat
                    },
                    {
                        extend: 'pdf',
                        orientation: 'landscape',
                        pageSize: 'A1',
                        exportOptions: exportFormat
                    },
                    {
                        extend: 'print',
                        exportOptions: exportFormat
                    }
                ],
                "scrollX": true,
                "pageLength": 5,
                "responsive": false,
                "retrieve": true,
                "processing": true,
                /* With server-side processing enabled, all paging, searching, ordering actions that DataTables
                 performs are handed off to a server where an SQL engine (or similar) can perform these actions
                 on the large data set.
                 However, with server-side processing enabled, there may have two errors.
                 First error, 414 error code, URI is too long.
                 Second error, regular expression for datatable.column().search(RegEx, true, false) API doesn't work.
                 So I disable the server-side processing */
                "serverSide": false,
                "ajax": {
                    "url": 'php_script/datatable/server_processing_patient.php',
                    "type": 'GET',
                    // Chrome deprecates the async: false because of bad user experience
                    "async": true,
                    "data": function (d) {
                        d.operate = "<?php echo $operate;?>";
                        d.pid = "<?php echo $pid;?>";
                        d.item = "<?php echo $item;?>";
                    }
                },
                "deferRender": true,
                "searching": true,
                "initComplete": function(settings, json){
                    if(json.data == null){
                        console.log(xhr.error);
                    }else {
                        <?php
                        // result message of batch patient upload
                        if($pid==="allnew"){
                            require_once ("{$php_class_dir}/dbencryt.inc");
                            require_once ("dbpdx.inc");

                            $db = new mysqli(Encryption::decrypt($hostname),Encryption::decrypt($username),Encryption::decrypt($password),Encryption::decrypt($remoter_dbname));
                            if($db->connect_error){
                                die('Unable to connect to database: ' . $db->connect_error);
                            }
                            $sql="SELECT J.Status, J.JobID FROM Jobs AS J INNER JOIN PDXParameters AS P ON J.JobID = P.JobID WHERE J.Software=\"pdx\" ".
                                "AND J.Analysis=\"patientbatchupload\" AND J.Status NOT IN(0,2) AND P.AccountID=? AND P.Tag=1 ORDER BY J.CreateTime DESC LIMIT 1;";
                            if($result = $db->prepare($sql)){
                                $result->bind_param('s', $_SESSION["user_id"]);
                                $result->execute();
                                $result->bind_result($status,$jobid);
                                $result->fetch();
                                $result->close();
                                if($status!=0){
                                    $insert_str = "";
                                    // job fail msg
                                    if($status == 9){
                                        $insert_str .=  "<div class=\"row\"><div class=\"col-md-12 text-center\"><div class=\"alert alert-danger alert-dismissable\">".
                                            "<i class=\"fa fa-times\" data-dismiss=\"alert\" style=\"float:right\"></i><strong>Job Fail Message!</strong><br>Please contact developer.</div></div></div>";
                                    }

                                    // warn msg
                                    $sql="SELECT WarnMsg, ExistedPatientIDs FROM PDXWarnResults AS W LEFT JOIN PDXParameters AS P ON W.JobID=P.JobID WHERE P.JobID=? AND P.AccountID=? AND P.Tag=1";
                                    if($result = $db->prepare($sql)) {
                                        $result->bind_param('ss',$jobid,$_SESSION["user_id"]);
                                        $result->execute();
                                        $result->bind_result($warnMsg, $existSamUuids);
                                        $result->fetch();
                                        $result->close();
                                        if ($existSamUuids !== null) {
                                            $insert_str .= "<div class=\"row\"><div class=\"col-md-12 text-center\"><div class=\"alert alert-warning alert-dismissable\">".
                                                "<i class=\"fa fa-times\" data-dismiss=\"alert\" style=\"float:right\"></i><strong>Info Message!</strong><br>".$warnMsg."</div></div></div>";
                                        }
                                    }

                                    // error msg
                                    $sql="SELECT ErrorMsg FROM PDXErrorResults AS E LEFT JOIN PDXParameters AS P ON E.JobID=P.JobID WHERE P.JobID=? AND P.AccountID=? AND P.Tag=1";
                                    if($result = $db->prepare($sql)){
                                        $result->bind_param('ss',$jobid,$_SESSION["user_id"]);
                                        $result->execute();
                                        $result->bind_result($errMsg);
                                        $result->fetch();
                                        $result->close();
                                        if($errMsg != null) {
                                            $insert_str .= "<div class=\"row\"><div class=\"col-md-12 text-center\"><div class=\"alert alert-danger alert-dismissable\">" .
                                                "<i class=\"fa fa-times\" data-dismiss=\"alert\" style=\"float:right\"></i><strong>Error Message!</strong><br>" . $errMsg . "</div></div></div>";
                                        }
                                    }

                                    // msg clean up
                                    $insert_str = preg_replace("/[\n\r|\n|\r]/","",$insert_str);
                                    // display msg
                                    echo "$('".$insert_str."').insertAfter('#fh5co-services .container .row:first-child h2');\n";
                                }else{
                                    // reload page
                                    echo "location.reload(true);";
                                }
                            }
                            $db->close();
                        }
                        ?>
                    }
                }
            });
        }

        // Apply search for each table column
        $(patient_table).DataTable().columns().every(function(index){
            let that = this;
            // apply search
            $('input', this.header()).on('keyup change', function(){
                if (that.search() !== this.value ) {
                    //If server-side processing is enabled, regular expression of datatable.column().search() API doesn't work.
                    let searchStrRep=this.value.replace(/(\s|\r|\n|\r\n)+/g,'|');
                    let Regex=(index === 0?((this.value !== '')?searchStrRep:this.value):this.value);
                    that.search(Regex, true, false).draw();
                }
            });
        });

        // delete a patient
        $(patient_table).on('click','.delete-patient',function(){
            let arr=$(this).parent().text().split(' ');
            let id=arr[arr.length-1];
            $('#delete_patient_modal').find('.modal-body b').text(id);
            $('#deletePatientBtn').on('click',function(){
                const deletePatientMsg='#deletePatientMsg';
                $.ajax({
                    type: "POST",
                    url: "php_script/send_delete_patient.php",
                    data: {"pid":id},
                    async: true,
                    cache: true,
                    dataType: "json",
                    success: function(result,status,xhr){
                        /**
                         * @param {{stat:string}} result
                         */
                        let str="<div class=\"col-md-12 text-center alert-dismissible alert "+result.class+" fade in\"> " +
                            "<div><i class=\"close fa fa-times\" data-dismiss=\"alert\" aria-label=\"close\"></i> " +
                            "<strong>"+result.stat+"</strong><br>"+result.msg+"</div></div>";
                        $(patient_table).DataTable().ajax.reload();
                        $(deletePatientMsg).empty().append(str);

                    },
                    error: function(xhr,status,error){
                        let str="<div class=\"col-md-12 text-center alert-dismissible alert alert-danger fade in\"> " +
                            "<div><i class=\"close fa fa-times\" data-dismiss=\"alert\" aria-label=\"close\"></i> " +
                            "<strong>Fail! </strong><br>Please contact developer.</div></div>";
                        $(deletePatientMsg).empty().append(str);
                    },

                });
            });
        });
    });
</script>
</body>
</html>

