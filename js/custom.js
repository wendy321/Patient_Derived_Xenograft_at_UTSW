"use strict";

/** ----------------------------------------
 * Utility
 * ----------------------------------------
 */

/**
 * Make date works in all browsers
 */
var makeDatePickerCompatibleAllBrowsers = function(){
    if ($('[type="date"]').prop('type') !== 'date' ) {
        $(this).datepicker();
    }
};


/**
 *  Enable or disable button
 */
var enOrDisBtn = function (eleId,isDisabled){
    $(eleId).prop("disabled", isDisabled);
};

/**
 *  Toggle section for read more or read less button
 */
var toggleReadMoreOrLess = function(button, btnTextWhenShow, btnTextWhenHide, ShowHideElem, speed, mode){
    $(button).on('click',function(){
        $(ShowHideElem).slideToggle(speed, mode);
        if($(this).text() === btnTextWhenHide){
            $(this).text(btnTextWhenShow);
        }else{
            $(this).text(btnTextWhenHide);
        }
    });
};


/**
 * Show or hide div based on the selected value
 */
var showOrHideByValue = function(showHideEle, eleWithValue, showValue, hideValue){
    var value = $(eleWithValue).val();
    if(value===showValue){
        $(showHideEle).slideDown("slow","linear");
    }else{
        $(showHideEle).slideUp("slow","linear");
    }
};

/**
 * Clear content or not based on the selected value
 */
var clearByValue = function(elemsToBeClear, eleWithValue, clearValue){
    var value = $(eleWithValue).val();
    if(value===clearValue){
        elemsToBeClear.forEach(function(item,index){
            $(item.id).val(item.clearTo);
        });
    }
};

/**
 * Toggle related select option group based on the main select option
 */
var toggleRelateOptgrpInput = function (mainOpt,defaultTxt,relateOprGrp){
    var main=$(mainOpt).find('option:selected').text();
    if(main===defaultTxt){
        $(relateOprGrp).find('optgroup').hide();
        $(relateOprGrp).find('option').show();
    }else{
        $(relateOprGrp).find('option[label="'+defaultTxt+'"]').hide();
        $(relateOprGrp).find('optgroup').hide();
        $(relateOprGrp).find('optgroup[label='+main+']').show();
    }
};

/**
 * Input and Modal Interaction
 * @description:
 * After clicking input, the related modal with data table inside it pops up.
 * Select or deselect row in the data table.
 * After pressing dismiss modal, the selected id will be filled in the input.
 * @param: inputId, modelId, tableId, dataTable, deselectId
 * @return: void
 */
var inputAndModalInteraction = function(inputId, modalId, tableId, data_table_script, deselectId, operate){
    var dataTable=null;
    if (!($.fn.DataTable.isDataTable(tableId))){
        dataTable=$(tableId).DataTable( {
            "responsive": true,
            "retrieve": true,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": 'php_script/datatable/'+data_table_script+'.php',
                "type": 'GET',
                "data":function ( d ) {
                    d.operate=operate;
                }
            },
            "deferRender": true,
            "searching": true
        });
    }else{
        dataTable=$(tableId).DataTable();
    }

    var onClickInput="";
    if(Array.isArray(inputId)){
        inputId.forEach(function(item, index){
            $(item).on('click',function () {
                onClickInput=$(this);
                $(this).trigger('blur');
                setTimeout(function(){
                    dataTable.columns.adjust().responsive.recalc();
                    $(tableId).on('click','td',function(){
                        var tr = $(this).parents('tr');
                        var row = dataTable.row( tr );
                        if ( row["child"].isShown() ) {
                            if(!$(tr).next('.child').find('span.dtr-data').hasClass('changeFormat')) {
                                $(tr).next('.child').find('span.dtr-data').addClass('changeFormat');
                            }
                        }
                    });                    
                },200);
            }).on('keydown',function(){
                $(this).trigger('blur');
            });
        });
    }else{
        $(inputId).on('click',function () {
            console.log("inputId:"+inputId);
            onClickInput=$(this);
            $(this).trigger('blur');
            setTimeout(function(){
                dataTable.columns.adjust().responsive.recalc();
                $(tableId).on('click','td',function(){
                    var tr = $(this).parents('tr');
                    var row = dataTable.row( tr );
                    if ( row["child"].isShown() ) {
                        if(!$(tr).next('.child').find('span.dtr-data').hasClass('changeFormat')) {
                            $(tr).next('.child').find('span.dtr-data').addClass('changeFormat');
                        }
                    }
                });                
            },200);
        }).on('keydown',function(){
            $(this).trigger('blur');
        });
    }

    $(deselectId).on('click',function(){
        $(tableId).find('tbody tr td:first-child input:checked').prop("checked",false);
    });

    $(modalId).find('button[data-dismiss="modal"]').on('click',function(){
        var id=$(tableId).find('tbody tr td:first-child input:checked').val();
        $(onClickInput).val(id);
        $(tableId).find('tbody tr td:first-child input:checked').prop("checked",false);
    });
};

/**
 * Check whether a data already exists in the database, except for a certain data value
 */
var checkExistInDBExcept =function(table,field,value,inputEle,exceptValue,checkedInputs,disBtn){
    var promise1 = new Promise(function(resolve, reject) {
        $.ajax({
            method: "POST",
            url: "php_script/check_exist_in_db.php",
            data: { table: table, field: field,value: value, exceptValue: exceptValue},
            dataType:"json",
            async: true,
        }).done(function(resp) {
            if(resp["msg"]!=="exist" && resp["msg"]!=="not exist"){
                reject(new Error("other"));
            }else{
                resolve(resp["msg"]);
            }
        }).fail(function(jqXHR, textStatus) {
            reject(new Error("check existence in db fail: " + textStatus));
        });
    });

    Promise.all([promise1])
    .then(function(value) {
        if(value[0]==="exist"){
            $(inputEle).parent().addClass("has-error has-feedback");
            $(inputEle).next().removeClass("hidden");
            $(inputEle).next().next().removeClass("hidden").text("This "+field+" has been used by other "+table+
                ". Please enter another one.");
        }else{
            $(inputEle).parent().removeClass("has-error has-feedback");
            $(inputEle).next().addClass("hidden");
            $(inputEle).next().next().addClass("hidden").text("");
        }
        enOrDisBtn(disBtn,checkHasErrorClassInParent(checkedInputs));
    }).catch(function(errorMsg){
        console.log(errorMsg);
    });
};


/**
 * Enable or disable button by checking whether elements have has-error class
 */
var checkHasErrorClassInParent = function(checkedInputs){
    var hasErr=false;
    $.each(checkedInputs,function(i,v){
        if($(v).parent().hasClass("has-error")){
            hasErr=true;
        }
    });
    return hasErr;
};


/** ----------------------------------------
 * Window is loaded.
 * ----------------------------------------
 */
$(window).load(function(){
    /* start preloader */
    $('.preloader').fadeOut(1500);
    /* end preloader */
});



/** ----------------------------------------
 * HTML document is loaded. DOM is ready.
 * ----------------------------------------
 */
$(function(){

    /* start typed element */
    var subElementArray = $.map($('.sub-element'), function(el) { return $(el).text(); });    
    $(".element").typed({
        strings: subElementArray,
        typeSpeed: 40,
        contentType: 'html',
        showCursor: false,
        loop: true,
        loopCount: true,
    });
    var subEleArray = $.map($('.sub-ele'), function(el) { return $(el).text(); });
    $(".ele").typed({
        strings: subEleArray,
        typeSpeed: 40,
        contentType: 'html',
        showCursor: false,
        loop: true,
        loopCount: true,
    });
    /* end typed element */

    /* start navigation top */
    $(window).scroll(function(){
        if($(this).scrollTop()>58){
            $(".templatemo-nav").addClass("sticky");
        }
        else{
            $(".templatemo-nav").removeClass("sticky");
        }
    });
    /* end navigation top */
    
    /* Hide mobile menu after clicking on a link */
    $('.navbar-collapse a').click(function(){
        $(".navbar-collapse").collapse('hide');
    });

    $('body').bind('touchstart', function() {});

    /* start wow */
    new WOW().init();
    /* end wow */

    /* start submenu style */
    var dropdown = $('.dropdown');
    var dropdown_menu = $(document).find(dropdown).find('ul.dropdown-menu');
    $(dropdown).mouseenter(function(){
        if($(dropdown_menu).css('display')==='none'){
            $(dropdown_menu).slideDown("slow");
        }
    });
    $(dropdown_menu).mouseleave(function () {
        if($(dropdown_menu).css('display')==='block') {
            $(dropdown_menu).slideUp("slow");
        }
    });
    /* end submenu style */

    /* start bootstrap popover */
    $('[data-toggle="popover"]').popover();
    /* end bootstrap popover */
});

