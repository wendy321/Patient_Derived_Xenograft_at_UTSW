<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>PDX Portal</title>
    <meta name="keywords" content="PDX, UTSW">
    <meta name="description" content="A user-friendly public platform for data integration, analysis, sharing,
		and management for proposed projects.">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="images/utsw_icon.jpg">
    <link rel="stylesheet" href="css/vendor/animate.min.css">
    <link rel="stylesheet" href="css/vendor/bootstrap.min.3.4.css">
    <link rel="stylesheet" href="css/vendor/bootstrapValidator.min.css">
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

<!-- start contact -->
<section id="contact">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="wow bounceIn" data-wow-offset="50" data-wow-delay="0.3s"> <span>CONTACT</span></h2>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12 wow fadeInLeft" data-wow-offset="50" data-wow-delay="0.9s">
                <form id="contact_form" action="" method="post" novalidate="novalidate" class="bv-form">
                    <fieldset>
                        <div class="row">
                            <div class="form-group col-lg-6 has-feedback">
                                <div class="col-lg-12">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                        <input name="name" placeholder="Name" class="form-control" type="text" data-bv-field="name">
                                        <i class="form-control-feedback" data-bv-icon-for="name" style="display: none;"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-lg-6 has-feedback">
                                <div class="col-lg-12">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                        <input name="email" placeholder="E-Mail Address" class="form-control" type="text" data-bv-field="email">
                                        <i class="form-control-feedback" data-bv-icon-for="email" style="display: none;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-lg-6 has-feedback">
                                <div class="col-lg-12">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-list"></i></span>
                                        <input name="subject" placeholder="Subject" class="form-control" type="text" data-bv-field="subject">
                                        <i class="form-control-feedback" data-bv-icon-for="subject" style="display: none;"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-lg-6 has-feedback">
                                <div class="col-lg-12">
                                    <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i style="background: url('images/rectangular-grid.svg') no-repeat center;padding: 0.8rem;"></i>
                                                </span>
                                        <input name="institution" placeholder="Institution" class="form-control" type="text" data-bv-field="institution">
                                        <i class="form-control-feedback" data-bv-icon-for="institution" style="display: none;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-lg-12 has-feedback">
                                <div class="col-lg-12">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil-alt"></i></span>
                                        <textarea name="comment" placeholder="Comment" rows="13" class="form-control" data-bv-field="comment"></textarea>
                                        <i class="form-control-feedback" data-bv-icon-for="comment" style="display: none;"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-12 control-label"></label>
                                <div class="col-xs-offset-2 col-xs-4 col-sm-offset-2 col-sm-4 col-md-offset-2 col-md-4 col-lg-offset-2 col-lg-4 text-center">
                                    <input type="submit" class="btn btn-primary" value="Send">
                                </div>
                                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 text-center">
                                    <input type="reset" class="btn btn-primary" value="Reset">
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12 wow fadeInRight" data-wow-offset="50" data-wow-delay="0.6s">
                <address>
                    <p class="address-title">INFORMATION</p>
                    <span>Please contact us or visit us for more infirmation.</span>
                    <p><i class="fa fa-phone"></i> 214-648-4003</p>
                    <p><i class="fa fa-fax"></i> 214-648-5612</p>
                    <p><i class="fa fa-envelope"></i>
                        <a href="mailto:yang.xie@utsouthwestern.edu">Yang.Xie@UTsouthwestern.edu</a>
                        <a href="mailto:Guanghua.Xiao@UTSouthwestern.edu" style="padding-left: 2rem;">Guanghua.Xiao@UTSouthwestern.edu</a>
                    </p>
                    <p><i class="fa fa-map-marker"></i> Suite NC8.512, 5323 Harry Hines Blvd., Dallas, TX 75390</p>
                </address>
            </div>
        </div>
    </div>
</section>
<!-- end contact -->

<!-- start copyright -->
<?php include ("footer.php");?>
<!-- end copyright -->
<script src="js/vendor/jquery.js"></script>
<script src="js/vendor/bootstrap.min.3.4.js"></script>
<script src="js/vendor/bootstrapvalidator.min.js"></script>
<script src="js/vendor/typed.js"></script>
<script src="js/vendor/wow.min.js"></script>
<script src="js/custom.js"></script>
<script type="text/javascript">
    $('.templatemo-nav ul li:nth-child(6) a').addClass('current');
    $('#contact_form').bootstrapValidator({
        feedbackIcons: {
            valid: 'fa fa-check',
            invalid: 'fa fa-times',
            validating: 'fa fa-retweet'
        },
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: 'Please supply your name'
                    },
                    stringLength: {
                        min: 2,
                        max: 30,
                        message:'Please enter at least 2 characters and no more than 30'
                    }
                }
            },
            email: {
                validators: {
                    notEmpty: {
                        message: 'Please supply your email address'
                    },
                    emailAddress: {
                        message: 'Please supply a valid email address'
                    }
                }
            },
            subject: {
                validators: {
                    stringLength: {
                        max: 30,
                        message:'Please enter no more than 30 characters'
                    }
                }
            },
            institution: {
                validators: {
                    stringLength: {
                        max: 30,
                        message:'Please enter no more than 30 characters'
                    }
                }
            },
            comment: {
                validators: {
                    notEmpty: {
                        message: 'Please supply a description of your project'
                    },
                    stringLength: {
                        min: 10,
                        max: 200,
                        message:'Please enter at least 10 characters and no more than 200'
                    }
                }
            }
        }
    });
</script>
</body>
</html>