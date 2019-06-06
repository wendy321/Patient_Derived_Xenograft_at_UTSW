<?php
session_start();
?>
<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><title>PDX Portal</title><meta name="keywords" content="PDX, UTSW"><meta name="description" content="A user-friendly public platform for data integration, analysis, sharing,
		and management for proposed projects."><meta http-equiv="X-UA-Compatible" content="IE=Edge"><meta name="viewport" content="width=device-width,initial-scale=1"><link rel="icon" href="./images/utsw_icon.jpg"><link rel="stylesheet" href="css/discover_bundle.css"><link rel="stylesheet" href="css/vendor/fontawesome-all.min.css"><link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700" rel="stylesheet" type="text/css"><style>
        .preloader-circle
        {
            width:100px;
            height:100px;
            position:relative;
            left:50%;
            bottom:50%;
            z-index:1;
            background:#fff url('images/loading_spinner.gif') no-repeat center center;
        }</style><!--[if lt IE 9]>
    <script src="js/vendor/html5shiv.min.js"></script>
    <script src="js/vendor/respond.min.js"></script>
    <![endif]--></head><body id="top"><div class="preloader"><div class="sk-spinner sk-spinner-wave"><div class="sk-rect1"></div><div class="sk-rect2"></div><div class="sk-rect3"></div><div class="sk-rect4"></div><div class="sk-rect5"></div></div></div><?php include ("nav.php");?><div id="app"></div><footer id="copyright"><div class="container"><div class="row"><div class="col-md-12 text-center"><p class="wow bounceIn" data-wow-offset="50" data-wow-delay="0.3s">Copyright &copy; 2018 <a href="https://qbrc.swmed.edu/" target="_blank">Quantitative Biomedical Research Center</a>, <a href="https://www.utsouthwestern.edu/" target="_blank">UT Southwestern Medical Center</a></p></div></div></div></footer><script src="js/discover_bundle.js"></script><script type="text/javascript">$('.templatemo-nav ul li:nth-child(3) a').addClass('current');
    $(".tree-number,.piecharts").fadeIn("slow");

    const dropdown = $('.dropdown');
    const dropdown_menu = $(document).find(dropdown).find('ul.dropdown-menu');
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
</script></body></html>