"use strict";

/**
 * Parent Sample UUID Input & Initial Sample UUID Input & Sample DataTable Interaction
 */
var matchedRow =
    {"puuid": {"puuid": null, "#ddl_systemPatientId": {val: null, type: "string"},
        "#ddl_specimenType": {val: null, type: "type"}, "#ddl_procedureType": {val: null, type: "type"},
        "#ddl_procedureDate": {val: null, type: "date"}, "#ddl_priTumorSite": {val: null, type: "type"},
        "#ddl_priTumorLater": {val: null, type: "type"}, "#ddl_priTumorDir": {val: null, type: "type"},
        "#ddl_shipDate": {val: null, type: "date"}, "#ddl_pdxGenDate": {val: null, type: "date"},
        "#ddl_pdxRecDate":{val: null, type: "date"}},
    "iuuid":{"iuuid": null, "#ddl_systemPatientId": {val: null, type: "string"},
        "#ddl_specimenType": {val: null, type: "type"}, "#ddl_procedureType": {val: null, type: "type"},
        "#ddl_procedureDate": {val: null, type: "date"}, "#ddl_priTumorSite": {val: null, type: "type"},
        "#ddl_priTumorLater": {val: null, type: "type"}, "#ddl_priTumorDir": {val: null, type: "type"},
        "#ddl_shipDate": {val: null, type: "date"}, "#ddl_pdxGenDate": {val: null, type: "date"},
        "#ddl_pdxRecDate":{val: null, type: "date"}}};

var inputAndModalInteractionSample = function(psampleInput, psampleModal, psampleTableId, psampleDeselectId,
                                              isampleInput, isampleModal, isampleTableId, isampleDeselectId,
                                              sampleDataTableScript, operate){

    var pDataTable = null;
    if (!($.fn.DataTable.isDataTable(psampleTableId))){
        pDataTable=$(psampleTableId).DataTable( {
            "responsive": true,
            "retrieve": true,
            "processing": true,
            // if enable serverSide, the request URI may be too long, resulting in 414 error
            "serverSide": false,
            "ajax": {
                "url": 'php_script/datatable/'+sampleDataTableScript+'.php',
                "type": 'GET',
                "data":function ( d ) {
                    d.operate=operate;
                    d.filter="no";
                }
            },
            "deferRender": true,
            "searching": true
        });
    }else{
        pDataTable=$(psampleTableId).DataTable();
    }

    var iDataTable = null;
    if (!($.fn.DataTable.isDataTable(isampleTableId))){
        iDataTable=$(isampleTableId).DataTable( {
            "responsive": true,
            "retrieve": true,
            "processing": true,
            // if enable serverSide, the request URI may be too long, resulting in 414 error
            "serverSide": false,
            "ajax": {
                "url": 'php_script/datatable/'+sampleDataTableScript+'.php',
                "type": 'GET',
                "data":function ( d ) {
                    d.operate=operate;
                    d.filter="initialSam";
                }
            },
            "fnInitComplete": function(oSettings, json) {
                if($(isampleInput).val()!=="" || $(psampleInput).val()!==""){
                    var mRow = matchedRow["puuid"];
                    for(var key in mRow){
                        if(key==="puuid") continue;
                        var isShowAutoFillMsg=false;
                        if(mRow[key].type==="type"){
                            var selected = $(key).find("option:selected");
                            $(key).find("option").each(function(i){
                                if((typeof selected !== "undefined") && ($(selected).val()!=="") &&
                                    ($(this).val()!==$(selected).val())){
                                    $(this).prop("disabled",true);
                                    isShowAutoFillMsg = true;
                                }
                            });
                        }
                        if(mRow[key].type==="date" || mRow[key].type==="string"){
                            if($(key).val()!==""){
                                $(key).prop("readonly",true);
                                isShowAutoFillMsg = true;
                            }
                        }
                        if(isShowAutoFillMsg){
                            $(key).prev("label").find(".msg").text("automatically filled based on your selected Parent Sample");
                        }
                    }
                }
            },
            "deferRender": true,
            "searching": true
        });
    }else{
        iDataTable=$(isampleTableId).DataTable();
    }

    $(psampleInput).on('click',function () {
        $(this).trigger('blur');
        setTimeout(function(){
            pDataTable.columns.adjust().responsive.recalc();
            // if isampleInput has value, only show NOT conflict one (procedure-related values should be same)
            if($(isampleInput).val()!==""){
                pDataTable.columns(5).search(matchedRow["iuuid"]["#ddl_systemPatientId"].val,true,false).draw();
                pDataTable.columns(14).search(matchedRow["iuuid"]["#ddl_specimenType"].val,true,false).draw();
                pDataTable.columns(9).search(matchedRow["iuuid"]["#ddl_procedureType"].val,true,false).draw();
                pDataTable.columns(10).search(matchedRow["iuuid"]["#ddl_procedureDate"].val,true,false).draw();
                pDataTable.columns(11).search(matchedRow["iuuid"]["#ddl_priTumorSite"].val,true,false).draw();
                pDataTable.columns(12).search(matchedRow["iuuid"]["#ddl_priTumorLater"].val,true,false).draw();
                pDataTable.columns(13).search(matchedRow["iuuid"]["#ddl_priTumorDir"].val,true,false).draw();
                pDataTable.columns(33).search(matchedRow["iuuid"]["#ddl_shipDate"].val,true,false).draw();
                pDataTable.columns(34).search(matchedRow["iuuid"]["#ddl_pdxGenDate"].val,true,false).draw();
                pDataTable.columns(35).search(matchedRow["iuuid"]["#ddl_pdxRecDate"].val,true,false).draw();
            }else{
                pDataTable.columns(5).search("",true,false).draw();
                pDataTable.columns(14).search("",true,false).draw();
                pDataTable.columns(9).search("",true,false).draw();
                pDataTable.columns(10).search("",true,false).draw();
                pDataTable.columns(11).search("",true,false).draw();
                pDataTable.columns(12).search("",true,false).draw();
                pDataTable.columns(13).search("",true,false).draw();
                pDataTable.columns(33).search("",true,false).draw();
                pDataTable.columns(34).search("",true,false).draw();
                pDataTable.columns(35).search("",true,false).draw();
            }
        },200);
    }).on('keydown',function(){
        $(this).trigger('blur');
    });

    $(isampleInput).on('click',function () {
        $(this).trigger('blur');
        setTimeout(function(){
            iDataTable.columns.adjust().responsive.recalc();
            // if psampleInput has value, only show NOT conflict one (procedure-related values should be same)
            if($(psampleInput).val()!==""){
                iDataTable.columns(5).search(matchedRow["puuid"]["#ddl_systemPatientId"].val,true,false).draw();
                iDataTable.columns(14).search(matchedRow["puuid"]["#ddl_specimenType"].val,true,false).draw();
                iDataTable.columns(9).search(matchedRow["puuid"]["#ddl_procedureType"].val,true,false).draw();
                iDataTable.columns(10).search(matchedRow["puuid"]["#ddl_procedureDate"].val,true,false).draw();
                iDataTable.columns(11).search(matchedRow["puuid"]["#ddl_priTumorSite"].val,true,false).draw();
                iDataTable.columns(12).search(matchedRow["puuid"]["#ddl_priTumorLater"].val,true,false).draw();
                iDataTable.columns(13).search(matchedRow["puuid"]["#ddl_priTumorDir"].val,true,false).draw();
                iDataTable.columns(33).search(matchedRow["puuid"]["#ddl_shipDate"].val,true,false).draw();
                iDataTable.columns(34).search(matchedRow["puuid"]["#ddl_pdxGenDate"].val,true,false).draw();
                iDataTable.columns(35).search(matchedRow["puuid"]["#ddl_pdxRecDate"].val,true,false).draw();
            }else{
                iDataTable.columns(5).search("",true,false).draw();
                iDataTable.columns(14).search("",true,false).draw();
                iDataTable.columns(9).search("",true,false).draw();
                iDataTable.columns(10).search("",true,false).draw();
                iDataTable.columns(11).search("",true,false).draw();
                iDataTable.columns(12).search("",true,false).draw();
                iDataTable.columns(13).search("",true,false).draw();
                iDataTable.columns(33).search("",true,false).draw();
                iDataTable.columns(34).search("",true,false).draw();
                iDataTable.columns(35).search("",true,false).draw();
            }
        },200);
    }).on('keydown',function(){
        $(this).trigger('blur');
    });

    $(psampleDeselectId).on('click',function(){
        $(psampleTableId).find('tbody tr td:first-child input:checked').prop("checked",false);
    });

    $(isampleDeselectId).on('click',function(){
        $(isampleTableId).find('tbody tr td:first-child input:checked').prop("checked",false);
    });

    $(psampleModal).find('button[data-dismiss="modal"]').on('click',function(){
        var pid=$(psampleTableId).find('tbody tr td:first-child input:checked').val();
        $(psampleInput).val(pid);
        $(psampleTableId).find('tbody tr td:first-child input:checked').prop("checked",false);

        if(pid!=="" && pid!==null && typeof pid!== "undefined"){
            cleanComValsInCurrentSample();
            copyComValsFromSampleToCurrentSample(pid, pDataTable, "puuid");
        }else{
            var iid=$(isampleInput).val();
            if(iid==="" || iid==null || typeof iid== "undefined"){
                cleanComValsInCurrentSample();
            }
            cleanMatchedRow("puuid");
        }
    });

    $(isampleModal).find('button[data-dismiss="modal"]').on('click',function(){
        var iid=$(isampleTableId).find('tbody tr td:first-child input:checked').val();
        $(isampleInput).val(iid);
        $(isampleTableId).find('tbody tr td:first-child input:checked').prop("checked",false);

        if((iid!=="" && iid!==null && typeof iid!== "undefined")){
            cleanComValsInCurrentSample();
            copyComValsFromSampleToCurrentSample(iid, iDataTable, "iuuid");
        }else{
            var pid=$(psampleInput).val();
            if(pid==="" || pid==null || typeof pid== "undefined"){
                cleanComValsInCurrentSample();
            }
            cleanMatchedRow("iuuid");
        }
    });
};

/**
 * Clean matchedRow
 */
var cleanMatchedRow = function(parentOrIniSample){
    for(var key in matchedRow[parentOrIniSample]){
        if(key===parentOrIniSample) {
            matchedRow[parentOrIniSample][key] = null;
            continue;
        }
        matchedRow[parentOrIniSample][key].val = null;
    }
};

/**
 * Copy common values from direct parent sample to current sample
 */
var copyComValsFromSampleToCurrentSample = function (sampleUuid, dataTable, parentOrIniSample) {
    var RegEx=new RegExp("^[\\<\\'\\=\\-\\/\\>\\w\\s]+("+sampleUuid+")$");
    var mRow = matchedRow[parentOrIniSample];
    // get related values from parent sample row in datatable
    dataTable.rows().every(function(rowIdx, tableLoop, rowLoop){
        if(RegEx.test(this.data()[0])){
            mRow[parentOrIniSample]=sampleUuid;
            if(this.data()[5] !== ""){
                let pidRegEx = /^(?:.+)([\w]{7})(?:<\/u><\/a>)$/g;
                let pidMatch = pidRegEx.exec(this.data()[5]);
                mRow["#ddl_systemPatientId"].val=pidMatch[1];
            }else{
                mRow["#ddl_systemPatientId"].val=this.data()[5];
            }
            mRow["#ddl_specimenType"].val=this.data()[14];
            mRow["#ddl_procedureType"].val=this.data()[9];
            mRow["#ddl_procedureDate"].val=
                (typeof this.data()[10] ==="undefined" || this.data()[10] === null || this.data()[10] === "")?
                    null:this.data()[10].split(" ")[0];
            mRow["#ddl_priTumorSite"].val=this.data()[11];
            mRow["#ddl_priTumorLater"].val=this.data()[12];
            mRow["#ddl_priTumorDir"].val=this.data()[13];
            mRow["#ddl_shipDate"].val=
                (typeof this.data()[33] ==="undefined" || this.data()[33] === null || this.data()[33] === "")?
                    null:this.data()[33].split(" ")[0];
            mRow["#ddl_pdxGenDate"].val=
                (typeof this.data()[34] ==="undefined" || this.data()[34] === null || this.data()[34] === "")?
                    null:this.data()[34].split(" ")[0];
            mRow["#ddl_pdxRecDate"].val=
                (typeof this.data()[35] ==="undefined" || this.data()[35] === null || this.data()[35] === "")?
                    null:this.data()[35].split(" ")[0];
        }
    });

    // copy the related values to the current sample and show msg
    for(var key in mRow){
        if(key===parentOrIniSample && mRow[key]===null) break;
        if(key===parentOrIniSample && mRow[key]!==null) continue;
        if(mRow[key].type==="type"){
            if(mRow[key].val!==null){
                $(key).find("option").each(function(i){
                    if($(this).text().toUpperCase()===mRow[key].val.toUpperCase()){
                        $(key).val($(this).prop("value"));
                        $(key).prop("selected",true);
                    }else{
                        $(this).prop("disabled",true);
                    }
                });
            }
        }
        if(mRow[key].type==="date" || mRow[key].type==="string"){
            if(mRow[key].val!==null){
                $(key).val(mRow[key].val);
                $(key).prop("readonly",true);
            }
        }
        $(key).prev("label").find(".msg").text("automatically filled based on your selected Parent Sample");
    }
};

/**
 * Clean common values in current sample inputs
 */
var cleanComValsInCurrentSample = function(){
    for(var key1 in matchedRow["puuid"]){
        if(key1==="puuid") continue;
        $(key1).val("");
        $(key1).prev("label").find(".msg").text("");
        if(matchedRow["puuid"][key1].type==="type"){
            $(key1).find("option").each(function(i){
                if((matchedRow["puuid"][key1].val !=null)
                    && ($(this).text().toUpperCase()===matchedRow["puuid"][key1].val.toUpperCase())){
                    $(key1).prop("selected",false);
                }else{
                    $(this).prop("disabled",false);
                }
            });
        }
        if(matchedRow["puuid"][key1].type==="date" || matchedRow["puuid"][key1].type==="string"){
            $(key1).prop("readonly",false);
        }

    }
};


