<?php

session_start();
$_SESSION["pre_page"]=basename($_SERVER['PHP_SELF']);
$php_script_dir="php_script";
$php_class_dir="php_class";
include "{$php_script_dir}/authenticate_user_session.php";
require ("{$php_class_dir}/EscapeString.inc");
$operate=(isset($_GET['operate']) && $_GET['operate']!=="")?EscapeString::escape($_GET['operate']):null;
$uuid=(isset($_GET['uuid']) && $_GET['uuid']!=="")?EscapeString::escape($_GET['uuid']):null;
$item='';
if(strpos($operate,'edit') > -1){
    $item=(isset($_GET['item']) && $_GET['item']!=="")?EscapeString::escape($_GET['item']):null;
}
$error=(isset($_GET['error']) && $_GET['error']!=="")?EscapeString::escape($_GET['error']):null;

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
    <style>
        .hightlight{
            border-color: #0cab2f;
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
                <h2 class="wow bounceIn" data-wow-offset="50" data-wow-delay="0.3s">
                    <span>
                        <?php
                        $echoStr="";
                        if(strpos($operate,"edit") > -1 && $item=="patient"){
                            $echoStr="Link Sample to Patient";
                        }elseif(strpos($operate,"edit") > -1 || strpos($operate,"delete") > -1){
                            $echoStr="Edit Sample Information";
                        }else{
                            $echoStr="View Sample Information";
                        }
                        echo $echoStr."<br>";
                        ?>

                    </span>
                </h2>
                <?php
                if(strpos($operate,"edit") > -1 && $item=="patient"){
                    echo "<div class=\"row\" style='color:#0cab2f;font-weight: 500;'>
                            Please select a Patient ID for the Sample UUID you would like to link with. </div>";
                }
                if(strpos($operate,'delete') > -1 ){
                    echo "<div id=\"deleteSampleMsg\" class=\"row\"></div>";
                }
                if(strpos($operate,'print') > -1 ){
                    echo "<div id=\"printSampleMsg\" class=\"row\"></div>";
                }
                ?>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="col-md-12 text-center">
                <?php
                // link sample to patient UI
                if(strpos($operate,"edit") > -1 && $item=="patient") {
                    echo "<form method=\"post\" action=\"".$php_script_dir."/send_link_sample_patient.php\">";
                }
                ?>
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

                <?php
                // link sample to patient UI
                if(strpos($operate,"edit") > -1 && $item=="patient") {
                    echo "<div class='text-primary' style='font-weight: 500;'>Each page submit once</div><br>
                          <a class=\"btn btn-success\" href=\"samplelist.php?operate=".$operate."&item=".$item."\">Reset</a>
                          <input id=\"submitbtn\"  type=\"submit\" class=\"btn btn-primary\" value=\"Submit\"/></form>";
                }
                ?>
                <br/>
                <div id="delete_sample_modal" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h3 class="modal-title">Delete a Sample</h3>
                            </div>
                            <div class="modal-body" style="padding:24px;">
                                Are you sure to delete Sample UUID: <b></b> ?
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary" data-dismiss="modal" type="button"> Cancel</button>
                                <button id="deleteSampleBtn" class="btn btn-danger" data-dismiss="modal" type="button"> Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            // link sample to patient UI
            if(strpos($operate,"edit") > -1 && $item=="patient") {
                echo "<div id=\"patient_modal\" class=\"modal fade text-center\" role=\"dialog\">
                        <div class=\"modal-dialog\">
                            <div class=\"modal-content\">
                                <div class=\"modal-header\">
                                    <button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
                                    <h3 class=\"modal-title\">Select a Patient</h3>
                                </div>
                                <div class=\"modal-body\" style=\"padding:24px;\">
                                    <br/><br/>
                                    <table id=\"patient_table\" class=\"display\" style=\"width:100%\">
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
                                <div class=\"modal-footer\">
                                    <button id=\"deselect_pid\" type=\"button\" class=\"btn btn-warning\">Deselect</button>
                                    <button type=\"button\" class=\"btn btn-primary\" data-dismiss=\"modal\">Confirm</button>
                                </div>
                            </div>
                        </div>
                    </div>";
            }
            ?>
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
<script src="js/sample_datatables.js"></script>

<script type="text/javascript">
    $(function () {

        <?php
        // link sample to patient UI
        if(strpos($operate,"edit") > -1 && $item=="patient"){
            echo "
                function initializeLinkedPidsInCurrTablePage(sampleDataTable){
                    let linkedPids = [];
                    if(sampleDataTable!=null){
                        sampleDataTable.rows({page: 'current'}).every(function(rowIdx, tableLoop, rowLoop){
                            let uuid=this.data()[0].match(/(?:br>).*$/g)[0].replace(\"br>\",\"\");
                            let iniSamUuid = this.data()[6];
                            let parentSamUuid = this.data()[7];
                            
                            // get patient ids of initial_sample_uuid and parent_sample_uuid
                            if((iniSamUuid!==null && iniSamUuid!=='') || (parentSamUuid!==null && parentSamUuid!=='')){
                                $.ajax({
                                    type: \"POST\",
                                    url: \"php_script/get_pids_of_iniSam_parentSam.php\",
                                    data: {\"iniSamUuid\": iniSamUuid, 
                                            \"parentSamUuid\": parentSamUuid
                                            },
                                    async: true,
                                    cache: false,
                                    dataType: \"json\",
                                    success: function(result,status,xhr){
                                        linkedPids.push({
                                        uuid: uuid, 
                                        iniPid: null, 
                                        iniSamPid: result[\"iniSamPid\"], 
                                        parentSamPid: result[\"parentSamPid\"], 
                                        selectPid:null});
                                    },
                                    error: function(xhr,status,error){
                                        linkedPids.push({
                                        uuid: uuid, 
                                        iniPid: null, 
                                        iniSamPid: null, 
                                        parentSamPid: null, 
                                        selectPid: null});
                                    }
                                });
                            }else{
                                linkedPids.push({
                                uuid: uuid, 
                                iniPid: null, 
                                iniSamPid: null, 
                                parentSamPid: null, 
                                selectPid: null});
                            }
                            
                            
                        });
                    }
                    return linkedPids;
                }
               
                function changeSelectPidInLinkedPids(linkedPids, uuid, selectPid){
                    for(var i in linkedPids){
                        if(linkedPids[i].uuid === uuid){
                            linkedPids[i].selectPid = selectPid;
                            break;
                        }
                    }
                    return linkedPids;
                }
                
                function removeSelectPidInLinkedPids(LinkedPids, uuid){
                    for(var i in linkedPids){
                        if(linkedPids[i].uuid === uuid){
                            linkedPids[i].selectPid = null;
                            break;
                        }
                    }                
                    return LinkedPids;
                }
                
                function showErrMsgForPidInput(pidInput, errMsg){
                    $(pidInput).closest('div').addClass('has-error').find('.error-msg').text(errMsg);
                    setTimeout(function(){
                        $(pidInput).closest('div').removeClass('has-error').find('.error-msg').text('');
                    },3000);           
                }
            ";
        }
        ?>

        $('.templatemo-nav ul li:nth-child(4) a').addClass('current');
        $('table thead th').addClass("text-center");

        /* Sample Data Table */
        const sample_table=$('#sample_table');

        // Add search input box for each table column
        $(sample_table).find('thead th').each( function (i) {
            let placeHolder=" Search";
            let title = $(this).text();
            if(i===0){
                $(this).html( title+'<br><small>(Scan one or more Barcodes) or (Type in one Sample UUID)</small>'+
                    '<textarea cols="37" placeholder=" '+ placeHolder + ' "></textarea>' );
            }else{
                $(this).html( title+'<input type="text" placeholder=" '+ placeHolder + ' "/>' );
            }
        });

        <?php
        // link sample to patient UI
        if(strpos($operate,"edit") > -1 && $item=="patient"){
            echo "
                var patient_table=$('#patient_table');
                var patient_modal = $('#patient_modal');
                var patient_deselect_id = $('#deselect_pid');
                var patient_data_table_script='server_processing_patient';
                var patient_dataTable=null;
                var patient_input_global=[];
                var cur_on_click_input_global='';
                var cur_on_click_uuid_global='';
                var linkedPids = null;
                ";
        }
        ?>

        // Instantiate Sample Data Table
        if (!($.fn.DataTable.isDataTable(sample_table))){
            let exportFormat={
                format: {
                    body: function ( data, rowIdx, columnIdx, node ) {
                        return data.replace(/(<(?!\/?(svg|g|rect)(?=>|\s.*>))\/?(.|\n)*?>)|(Edit)|(Delete)/gm, '');
                    }
                }
            };
            $(sample_table).DataTable({
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
                 * performs are handed off to a server where an SQL engine (or similar) can perform these actions
                 * on the large data set.
                 * (ps. However, with server-side processing enabled, there may have two errors.
                 * 1) 414 error code, URI is too long.
                 * 2) error, regular expression for datatable.column().search(RegEx, true, false) API doesn't work.
                 * So I disable the server-side processing) */
                "serverSide": false,
                "ajax": {
                    "url": 'php_script/datatable/server_processing_sample.php',
                    "type": 'GET',
                    // Chrome deprecates the async: false because of bad user experience
                    "async": true,
                    "data": function (d) {
                        d.operate = "<?php echo $operate;?>";
                        d.uuid = "<?php echo $uuid;?>";
                        d.item = "<?php echo $item;?>";
                    }
                },
                "deferRender": true,
                "searching": true,
            /* fnDrawCallback function will be called again after each datatable draws e.g. draw after sorting,
             * after pressing next page, or after pressing other page.
             * (ps. fnDrawCallback function is called twice before initComplete function.) */
            "fnDrawCallback": function( oSettings ) {
                <?php
                // link sample to patient UI
                if(strpos($operate,"edit") > -1 && $item=="patient") {
                    echo "
                    // hide n+2 ~ n+35 columns of sample data table
                    $('th:nth-child(n+2):nth-last-child(n+35)') . css('display', 'none');
                    $('td:nth-child(n+2):nth-last-child(n+35)') . css('display', 'none');

                    // For current data table page, if the number of patient input boxes are more than zero
                    let patient_input = $(sample_table) . find('input.hightlight');
                    if (patient_input . length > 0) {
                    
                        // clean current page patient_id input boxes which have values 
                        for(var i=0; i < patient_input.length ; i++){
                            $(patient_input.get(i)).val('');
                        }
                        
                        // initialize the recording array with uuids, initial pids, selected-linked pids
                         linkedPids = initializeLinkedPidsInCurrTablePage(sample_table . DataTable());

                        // filter out patient input objs which have already in the patient_input_global array
                        let filtered_patient_input = [];
                        for (let i = 0; i < patient_input . length; i++){
                            if (!patient_input_global . includes(patient_input . get(i))) {
                                patient_input_global . push(patient_input . get(i));
                                filtered_patient_input . push(patient_input . get(i));
                            }
                        }
                                              
                        // get patient data table obj
                        if (!($.fn . DataTable . isDataTable(patient_table))) {
                            patient_dataTable = $(patient_table) . DataTable( {
                                \"responsive\": true,
                                \"retrieve\": true,
                                \"processing\": true,
                                \"serverSide\": true,
                                \"ajax\": {
                                \"url\": 'php_script/datatable/' + patient_data_table_script + '.php',
                                \"type\": 'GET',
                                \"data\":function (d ) {
                                    d . operate = 'singleSelect';
                                    }
                                },
                                \"deferRender\": true,
                                \"searching\": true
                            });
                        } else {
                            patient_dataTable = $(patient_table).DataTable();
                        }

                        // on click action of the newly added patient input objs
                        $(filtered_patient_input) . on('click', function () {
                            cur_on_click_input_global = $(this);
                            cur_on_click_uuid_global = $(this).closest('td').siblings('td:first-child');
                            $(this) . trigger('blur');
                            
                            // responsive dataTable format
                            setTimeout(function () {
                                patient_dataTable . columns . adjust() . responsive . recalc();
                                $(patient_table) . on('click', 'td', function () {
                                    var tr = $(this) . parents('tr');
                                    var row = patient_dataTable . row(tr);
                                    if (row[\"child\"] . isShown()) {
                                        if (!$(tr) . next('.child') . find('span.dtr-data') . hasClass('changeFormat')) {
                                            $(tr) . next('.child') . find('span.dtr-data') . addClass('changeFormat');
                                        }
                                    }
                                });
                            }, 200);
                        }) . on('keydown', function () {
                            $(this) . trigger('blur');
                        });

                    }
                    ";
                }
                ?>
            },
            // initComplete function() is only called once, when data table completes initialization
            "initComplete": function(settings, json){
                <?php
                // link sample to patient UI
                if(strpos($operate,"edit") > -1 && $item=="patient") {
                    echo "         
                        $(patient_deselect_id).on('click',function(){
                            $(patient_table).find('tbody tr td:first-child input:checked').prop('checked',false);
                        });
                              
                        $(patient_modal).find('button[data-dismiss=\"modal\"]').on('click',function(){
                            var id=$(patient_table).find('tbody tr td:first-child input:checked').val();
                            var uuid = $(cur_on_click_uuid_global).text();
                            var foundLinkedPids = linkedPids.find(function(e){
                                return e.uuid === uuid;
                            });
                            if(foundLinkedPids!==null){
                                if(id!==''){
                                    if(foundLinkedPids.iniSamPid !== null || foundLinkedPids.parentSamPid !== null){
                                        if(foundLinkedPids.iniSamPid !== null && foundLinkedPids.iniSamPid !== id){
                                            $(cur_on_click_input_global).val('');
                                            showErrMsgForPidInput(cur_on_click_input_global, 
                                            'The selected patient_id conflicts with patient_ids of initial sample and parent sample.');
                                            linkedPids = removeSelectPidInLinkedPids(linkedPids, uuid);
                                            
                                        }else if(foundLinkedPids.parentSamPid !== null && foundLinkedPids.parentSamPid !== id){
                                            $(cur_on_click_input_global).val('');
                                            showErrMsgForPidInput(cur_on_click_input_global, 
                                            'The selected patient_id conflicts with patient_ids of initial sample and parent sample.');
                                            linkedPids = removeSelectPidInLinkedPids(linkedPids, uuid);                                            
                                            
                                        }else{
                                            $(cur_on_click_input_global).val(id);                                          
                                            $(cur_on_click_input_global).closest('div').removeClass('has-error')
                                            .find('.error-msg').text('');
                                            linkedPids = changeSelectPidInLinkedPids(linkedPids, uuid, id);
                                        }
                                    }else{
                                        $(cur_on_click_input_global).val(id);
                                        $(cur_on_click_input_global).closest('div').removeClass('has-error')
                                        .find('.error-msg').text('');                                          
                                        linkedPids = changeSelectPidInLinkedPids(linkedPids, uuid, id);
                                    }                                
                                }else{
                                    $(cur_on_click_input_global).val(id);
                                    $(cur_on_click_input_global).closest('div').removeClass('has-error')
                                    .find('.error-msg').text('');
                                    linkedPids = changeSelectPidInLinkedPids(linkedPids, uuid, id);
                                }
                            }
                            
                            $(patient_table).find('tbody tr td:first-child input:checked').prop('checked',false);
                        });
                    ";
                 }
                ?>

                <?php
                // batch sample upload result messages
                if($uuid==="allnew"){
                    require_once ("{$php_class_dir}/dbencryt.inc");
                    require_once ("dbpdx.inc");

                    $db = new mysqli(Encryption::decrypt($hostname),Encryption::decrypt($username),
                        Encryption::decrypt($password),Encryption::decrypt($remoter_dbname));
                    if($db->connect_error){
                        die('Unable to connect to database: ' . $db->connect_error);
                    }
                    $sql="SELECT J.Status, J.JobID FROM Jobs AS J INNER JOIN PDXParameters AS P ON J.JobID = P.JobID"
                        ." WHERE J.Software=\"pdx\" AND J.Analysis=\"samplebatchupload\" AND J.Status NOT IN(0,2) "
                        ." AND P.AccountID=? AND P.Tag=1 ORDER BY J.CreateTime DESC LIMIT 1;";
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
                                $insert_str .=  "<div class=\"row\"><div class=\"col-md-12 text-center\">" .
                                    "<div class=\"alert alert-danger alert-dismissable\">".
                                    "<i class=\"fa fa-times\" data-dismiss=\"alert\" style=\"float:right\"></i>" .
                                    "<strong>Job Fail Message!</strong><br>Please contact developer.</div></div></div>";
                            }

                            // warn msg
                            $sql="SELECT WarnMsg, ExistedSampleUUIDs FROM PDXWarnResults AS W LEFT JOIN PDXParameters ".
                                " AS P ON W.JobID=P.JobID WHERE P.JobID=? AND P.AccountID=? AND P.Tag=1";
                            if($result = $db->prepare($sql)) {
                                $result->bind_param('ss',$jobid,$_SESSION["user_id"]);
                                $result->execute();
                                $result->bind_result($warnMsg, $existSamUuids);
                                $result->fetch();
                                $result->close();
                                if ($existSamUuids !== null) {
                                    $insert_str .= "<div class=\"row\"><div class=\"col-md-12 text-center\">".
                                        "<div class=\"alert alert-warning alert-dismissable\">".
                                        "<i class=\"fa fa-times\" data-dismiss=\"alert\" style=\"float:right\"></i>".
                                        "<strong>Info Message!</strong><br>".$warnMsg."</div></div></div>";
                                }
                            }

                            // error msg
                            $sql="SELECT ErrorMsg FROM PDXErrorResults AS E LEFT JOIN PDXParameters AS P ".
                                "ON E.JobID=P.JobID WHERE P.JobID=? AND P.AccountID=? AND P.Tag=1";
                            if($result = $db->prepare($sql)){
                                $result->bind_param('ss',$jobid,$_SESSION["user_id"]);
                                $result->execute();
                                $result->bind_result($errMsg);
                                $result->fetch();
                                $result->close();
                                if($errMsg != null) {
                                    $insert_str .= "<div class=\"row\"><div class=\"col-md-12 text-center\">" .
                                        "<div class=\"alert alert-danger alert-dismissable\">" .
                                        "<i class=\"fa fa-times\" data-dismiss=\"alert\" style=\"float:right\"></i>" .
                                        "<strong>Error Message!</strong><br>" . $errMsg . "</div></div></div>";
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

                if($error!==null){
                    $errMsg = "";
                    if($error === "1"){
                        $errMsg .= "Transaction error. Please contact developer.";
                    }
                    $insert_str .= "<div class=\"row\"><div class=\"col-md-12 text-center\">" .
                        "<div class=\"alert alert-danger alert-dismissable\">" .
                        "<i class=\"fa fa-times\" data-dismiss=\"alert\" style=\"float:right\"></i>" .
                        "<strong>Error Message!</strong><br>" . $errMsg . "</div></div></div>";
                    echo "$('".$insert_str."').insertAfter('#fh5co-services .container .row:first-child h2');\n";
                }
                ?>

            }
            // Ajax event fired when an Ajax request is completed, which generates (xhr.dt) file.
            // (ps. This event is before data table fnDrawCallback & initComplete)
        }).on('xhr.dt', function ( e, settings, json, xhr ){
                if(json == null) {
                    console.log(xhr.error);
                }
            });
        }

        applySearchsampleDataTables(sample_table);

        // delete a sample
        $(sample_table).on('click','.delete-sample',function(){
            let arr=$(this).parent().text().split(' ');
            let id=arr[arr.length-1];
            $('#delete_sample_modal').find('.modal-body b').text(id);
            $('#deleteSampleBtn').on('click',function(){
                const deleteSampleMsg='#deleteSampleMsg';
                $.ajax({
                    type: "POST",
                    url: "php_script/send_delete_sample.php",
                    data: {"uuid":id},
                    async: true,
                    cache: false,
                    dataType: "json",
                    success: function(result,status,xhr){
                        var str="<div class=\"col-md-12 text-center alert-dismissible alert "+result.class+" fade in\"> " +
                            "<div><i class=\"close fa fa-times\" data-dismiss=\"alert\" aria-label=\"close\"></i> " +
                            "<strong>"+result.stat+"</strong><br>"+result.msg+"</div></div>";

                        $(sample_table).DataTable().ajax.reload();
                        $(deleteSampleMsg).empty().append(str);
                    },
                    error: function(xhr,status,error){
                        var str="<div class=\"col-md-12 text-center alert-dismissible alert alert-danger fade in\"> " +
                            "<div><i class=\"close fa fa-times\" data-dismiss=\"alert\" aria-label=\"close\"></i> " +
                            "<strong>Fail! </strong><br>Please contact developer.</div></div>";
                        $(deleteSampleMsg).empty().append(str);
                    }
                });
            });
        });

        <?php
        // print a sample
        if(strpos($operate,'print') > -1 ){
            echo "
               $(sample_table).on('click','.print-sample',function(){
                    var printUuid = $(this).parent().text().substring(16);
                    var printSampleMsg='#printSampleMsg';
                    $.ajax({
                        type: 'POST',
                        url: 'php_script/send_print_sample.php',
                        data: {'uuid': printUuid, 'user_id': ".$_SESSION["user_id"]."},
                        async: true,
                        cache: false,
                        dataType: 'json',
                        success: function(result,status,xhr){
                            var str='<div class=\"col - md - 12 text - center alert - dismissible alert '+result.class+' fade in\" > ' +
                                '<div><i class=\"close fa fa-times\" data-dismiss=\"alert\" aria-label=\"close\"></i> ' +
                                '<strong>'+result.stat+'</strong><br>'+result.msg+'</div></div>';
                            $(printSampleMsg).empty().append(str);
                        },
                        error: function(xhr,status,error){
                            var str='<div class=\"col - md - 12 text - center alert - dismissible alert alert - danger fade in\"> ' +
                                '<div><i class=\"close fa fa-times\" data-dismiss=\"alert\" aria-label=\"close\"></i> ' +
                                '<strong>Fail! </strong><br>Please contact developer.</div></div>';
                            $(printSampleMsg).empty().append(str);
                        }
        
                    });
                });              
            ";
        }
        ?>
    });
</script>
</body>
</html>

