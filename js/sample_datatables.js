"use strict";

var sampleDataTables = function(tableEle,operate,uuid,item,filter,dataTableServerProcessPhp,msgEle){

    if ($.fn.DataTable.isDataTable(tableEle)) {
        $(tableEle).DataTable().clear().destroy();
    }else{
        // Add search input box for each table column
        $(tableEle).find('thead th').each( function (i) {
            var placeHolder=" Search";
            var title = $(this).text();
            if(i===0){
                $(this).html( title+'<br><small>(Scan one or more Barcodes) or (Type in one Sample UUID)</small>'+
                    '<textarea cols="37" placeholder=" '+ placeHolder + ' "></textarea>' );
            }else{
                $(this).html( title+'<input type="text" placeholder=" '+ placeHolder + ' "/>' );
            }
        });
    }

    let exportFormat={
        format: {
            body: function ( data, rowIdx, columnIdx, node ) {
                return data.replace(/(<(?!\/?(svg|g|rect)(?=>|\s.*>))\/?(.|\n)*?>)|(Edit)|(Delete)/gm, '');
            }
        }
    };

    let promise = new Promise(function(resolve, reject) {
        $(tableEle).DataTable({
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
             on the large data set. However, with server-side processing enabled, regular expression of
             datatable.column().search() API doesn't work, so I turn off the server-side processing */
            "serverSide": false,
            "ajax": {
                "url": 'php_script/datatable/'+dataTableServerProcessPhp,
                "type": 'GET',
                // Chrome deprecates the async: false because of bad user experience
                "async": true,
                "data": function (d) {
                    d.operate = operate;
                    d.uuid = uuid;
                    d.item = item;
                    d.filter = filter;
                }
            },
            "deferRender": true,
            "searching": true,
            "fnDrawCallback":function( oSettings ) {
                if(operate==="_singleSelect"){
                    $(tableEle).find("tbody tr td:first-child input[type='radio']").prop("checked",false);
                }
            },
            "initComplete":function(settings, json){
                if(json.data == null){
                    reject("error msg");
                }else {
                    applySearchsampleDataTables(tableEle);
                    resolve("success msg");
                }
            }
        });
    });
    promise.then(function(successMsg){
        $(msgEle).empty("");
        if($(tableEle).DataTable().data().count() === 0){
            $(msgEle).append("<b>Warning!</b> <br/> No data is found.");
            $(msgEle).slideDown("fast");
        }else{
            $(msgEle).slideUp("fast");
        }
    }).catch(function(errorMsg) {
        console.log(errorMsg);
    });


};


var applySearchsampleDataTables = function(tableEle){
    // Apply search for each table column
    $(tableEle).DataTable().columns().every(function(index){
        var that = this;
        // display searched sample uuid list after scanning multiple barcodes
        if(index===0){
            $('textarea', this.header()).on('keypress keyup', function(){
                $('#searchUuids').remove();
                var inputValue = this.value;
                var size=inputValue.split(/(?:\s|\r|\n|\r\n)+/g).length;
                if(size > 1) {
                    $('<div id="searchUuids" class="col-md-offset-4 col-md-4 text-center" ' +
                        'style="background-color:#ffffff;border:1px #9a9a9b dotted; border-radius: 15px;padding-top:0.7em;' +
                        'padding-bottom:0.7em;">' +
                        '<b>Searched Sample UUID:</b><br>' + inputValue.replace(/(?:\s|\r|\n|\r\n)+/g,"<br>") +
                        '</div>').prependTo('#fh5co-services .container .row:nth-child(3)');
                }
            });
        }

        // apply search for columns
        $('input, textarea', this.header()).on('keyup', function(){
            var strOutput= "";
            var val = this.value;
            if(val.length % 36 === 0){
                var n =val.length / 36;
                for(var i=1;i<=n;i++){
                    if(i!==1){
                        strOutput += "\n" + val.slice((i-1)*36,i*36);
                    }else{
                        strOutput += val.slice((i-1)*36,i*36);
                    }
                }
            }
            if (that.search() !== val ) {
                //If server-side processing is enabled, regular expression of datatable.column().search() API doesn't work.
                var searchStrRep=strOutput.replace(/(\s|\r|\n|\r\n)+/g,'|');
                var Regex=(index === 0?((strOutput !== '')?searchStrRep:val):val);
                that.search(Regex, true, false).draw();
            }
        });
    });
};