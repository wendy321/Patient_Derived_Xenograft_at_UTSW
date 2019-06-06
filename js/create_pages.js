"use strict";

/**
 *  Toggle effect on method1 (single sample input) and method2 (batch sample input) block
 */
var toggleOfTwoMethods = function(method1, method1Detail, method2, method2Detail){

    function toggleFunc(method, methodDetail, OtherMethodDetail){
        $(method).on('click',function(){
            $(this).find("input").prop("checked",true);
            $(methodDetail).toggle("fast","linear");
            if($(OtherMethodDetail).css('display') === "block"){
                $(OtherMethodDetail).toggle("fast","linear");
            }
        });
    }

    $(method1Detail).toggle();

    toggleFunc(method1, method1Detail, method2Detail);
    toggleFunc(method2, method2Detail, method1Detail);
};



/**
 *  div border effect in method 1 (single entity data upload)
 */
var borderEffectOfMouseEnterLeaveDiv = function(divEle, initialBorderCss, mouseEnterBorderCss, mouseLeaveBorderCss,
    titleInBorderDiv, initialTitleCss){

    $(divEle).css(initialBorderCss);
    $(titleInBorderDiv).css(initialTitleCss);
    $(divEle).on('mouseenter',function(){
        $(this).css(mouseEnterBorderCss);
    });
    $(divEle).on('mouseleave',function(){
        $(this).css(mouseLeaveBorderCss);
    });
};


/**
 *  method 2 (multiple entities data upload - uploading files)
 */
var uploadExcelFile = function(fileEle,uploadUrl,minFileCnt,maxFileCnt,maxFileSize,inputMethodEle,successMsgEle,failMsgEle){
    $(fileEle).fileinput({
        previewFileType: "any",
        previewFileIconSettings: {'xlsx': '<i class="fa fa-file-excel text-success"></i>'},
        previewSettings:{office: {width: "213px", height: "160px"}},
        previewSettingsSmall:{office: {width: "100%", height: "160px"}},
        previewZoomButtonIcons:{
            toggleheader: '<i class="fa fa-arrows-alt-v"></i>',
            fullscreen: '<i class="fa fa-arrows"></i>',
            borderless: '<i class="fa fa-expand"></i>',
            close: '<i class="fa fa-times"></i>'
        },
        allowedFileTypes:["office"],
        allowedFileExtensions: ["xlsx"],
        fileTypeSettings: {
            office: function (vType, vName) {
                return vType.match(/(excel)$/i) ||
                    vName.match(/\.(xlsx?)$/i);
            }
        },
        fileActionSettings:{
            removeClass: 'btn btn-kv btn-default btn-outline-secondary fa-file-minus',
            zoomClass: 'hidden'
        },
        previewClass: "bg-warning",
        caption: '<div class="file-caption form-control " tabindex="500">\n' +
            '  <span class="file-caption-icon"><i class="fa fa-file"></i></span>\n' +
            '  <input class="file-caption-name" onkeydown="return false;" onpaste="return false;">\n' +
            '</div>',
        browseClass: "btn btn-success",
        browseLabel: "Search File",
        browseIcon: "<i class=\"fa fa-search\"></i> ",
        removeClass: "btn btn-danger",
        removeLabel: "Delete",
        removeIcon: "<i class=\"fa fa-trash\"></i> ",
        cancelClass: "btn btn-warning",
        cancelLabel: "Cancel",
        cancelIcon: "<i class=\"fa fa-ban\"></i> ",
        uploadClass: "btn btn-info",
        uploadLabel: "Upload",
        uploadIcon: "<i class=\"fa fa-upload\"></i> ",
        uploadUrl: uploadUrl,
        uploadAsync: true, // boolean whether the batch upload will be asynchronous/in parallel.
        minFileCount:minFileCnt,
        maxFileCount: maxFileCnt,
        maxFileSize:maxFileSize, // 1000 = 1Mb
        uploadExtraData: function() {
            return {
                method: $(inputMethodEle).val()
            };
        }
    }).on('fileloaded', function(event) {
        $('.kv-file-upload').addClass('fa fa-file-plus');
        $('.kv-file-remove').addClass('fa fa-file-minus');
        $('.kv-file-zoom').addClass('fa fa-search-plus');
        $(inputMethodEle).prop("checked",true);
    }).on('filedeleted', function(event) {
        $(inputMethodEle).prop("checked",false);
    }).on('filereset', function(event) {
        $(inputMethodEle).prop("checked",false);
    }).on('fileuploaded', function(event,data,previewId,index) {
        var fname = data.files[index].name;
        var fgoto= data.response['goto'];
        var ferror= data.response['error'];
        if(fgoto !== '' && ferror === ''){
            $(successMsgEle).find('ul').append('<li>' + 'Uploaded file '  +  fname + ' successfully.' + '</li>')
                .fadeIn('slow').fadeOut(1000);
            window.location.href=fgoto;
        }else{
            $(failMsgEle).find('ul').append('<li>' + 'Uploaded file '  +  fname + ' fail.' + '</li>')
                .fadeIn('slow').fadeOut(1000);
        }
    });
};


