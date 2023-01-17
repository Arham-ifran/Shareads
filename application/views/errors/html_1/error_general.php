<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
        $uri = explode("/", $_SERVER['REQUEST_URI']);
        global $urlType;
        $urlType = @$uri[1];
        $urlType1 = @$uri[2];
        $strings = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $url = trim(strtok($strings, '?'));
        ?>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Page Not Found</title>

        <!-- core CSS -->
        <link href="<?php echo BASE_URL.'assets/site/css/bootstrap.min.css' ?>" rel="stylesheet">
        <link href="<?php echo BASE_URL.'assets/site/css/font-awesome.min.css' ?>" rel="stylesheet">
        <link href="<?php echo BASE_URL.'assets/site/css/animate.min.css' ?>" rel="stylesheet">
        <link href="<?php echo BASE_URL.'assets/site/css/main.min.css' ?>" rel="stylesheet">
        <link href="<?php echo BASE_URL.'assets/site/css/responsive.min.css'?>" rel="stylesheet">
        <link href="<?php echo BASE_URL.'assets/site/css/custom.min.css' ?>" rel="stylesheet">
        <link rel="icon" href="<?php echo BASE_URL.'assets/site/images/Favicon.png'; ?>">
        <link rel="shortcut icon" href="<?php echo BASE_URL.'assets/site/images/Favicon.png'; ?>">


        <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500' rel='stylesheet' type='text/css'>

        <script src="<?php echo BASE_URL.'assets/site/js/jquery.js' ?>"></script>

    </head>
    <!--/head-->
    <style>
        .blue {
            color: #478fca !important;
        }h1.smaller {
            font-size: 31px; color: #999;
        }.bigger-125 {
            font-size: 125% !important;
        }h3.smaller {
            font-size: 21px;
        }
    </style>

    <body class="homepage">
        <div class="menu123">
            <header id="header">
                <div class="top-bar">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6 col-sm-4 col-xs-12 ">
                                <div class="social top-bdr">
                                    &nbsp;
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-8 col-xs-12 contact_bar">
                                <div class="top-number">
                                    <p>&nbsp;</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <nav class="navbar navbar-inverse other-pages" role="banner">
                    <div class="container">
                        <div class="navbar-header">

                            <a class="navbar-brand" href="<?php echo BASE_URL ?>"><img src="<?php echo BASE_URL.'assets/site/images/logo.png' ?>" alt="ShareAds" title="ShareAds"></a>



                        </div>
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                            <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
                        </button>
                        <div class="row">
                            <div class="btn-group btn-width navbar-right">

                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="collapse navbar-collapse yamm">
                                    <ul class="nav navbar-nav">
                                        <li><a class="<?php echo $currentPage == '' || $currentPage == 'home' ? 'active1' : '' ?>" href="<?php echo BASE_URL; ?>">Home</a></li>

                                            <li><a href="<?php echo BASE_URL; ?>pages/contact-us" class="">Contact Us</a></li>

                                            <li><a href="<?php echo BASE_URL; ?>pages/about-us" class="">About Us</a></li>

                                        </ul>

                                </div>
                            </div>
                        </div>
                    </div>
                </nav>

            </header>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 heading-bg">
                    <h1>Opps...! Error</h1>
                </div>
            </div>
        </div>
        <section id="contact-info">
            <div class="container">
                <div class="fadeInDown heading-area">

                    <div class="error-container">
                        <div class="well">
                            <h1 class="grey lighter smaller">
                                <span class="blue bigger-125">
                                    <i class="ace-icon fa fa-sitemap"></i>
                                    404
                                </span>
                                Page Not Found
                            </h1>

                            <hr />
                            <h3 class="lighter smaller">We looked everywhere but we couldn't find it!</h3>

                            <div>


                                <div class="space"></div>
                                <h4 class="smaller">Try one of the following:</h4>

                                <ul class="list-unstyled spaced inline bigger-110 margin-15">
                                    <li>
                                        <i class="ace-icon fa fa-hand-o-right blue"></i>
                                        Re-check the url
                                    </li>

                                    <li>
                                        <i class="ace-icon fa fa-hand-o-right blue"></i>
                                        Read the faq
                                    </li>

                                    <li>
                                        <i class="ace-icon fa fa-hand-o-right blue"></i>
                                        Tell us about it
                                    </li>
                                </ul>
                            </div>

                            <hr />
                            <div class="space"></div>

                            <div class="center">
                                <a href="javascript:history.back(-1)" class="btn btn-default">
                                    <i class="ace-icon fa fa-arrow-left"></i>
                                    Go Back
                                </a>

                                <a href="<?php echo BASE_URL.'dashboard' ?>" class="btn btn-success">
                                    <i class="ace-icon fa fa-tachometer"></i>
                                    Dashboard
                                </a>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </section>








        <footer id="footer" class="wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="600ms">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">



                        <div class="bottom-menu">

                            <a href="<?php echo BASE_URL; ?>pages/terms-of-use">Terms of Use</a>
                            &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                            <a href="<?php echo BASE_URL; ?>pages/privacy-policy">Privacy Policy</a>
                            &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                            <a href="<?php echo BASE_URL; ?>pages/contact-us">Contact Us</a>
                            &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                            <a href="<?php echo BASE_URL; ?>pages/about-us">About Us</a>
                            &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                            <a href="<?php echo BASE_URL; ?>blog">Blog</a>




                        </div>
                    </div>
                </div>
            </div>
        </footer>


        <section id="btmfooter">
            <div class="border-style">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="footer-bottom">
                                <i style="margin:0px 5px; font-size:16px;" class="fa fa-phone"></i> 1-677-124-44227 <i style="margin:0px 5px; padding-left:20px; font-size:16px;" class="fa fa-map-marker"></i> 437 S Olive St, Los Angeles
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="footer-bottom text-right">ShareAds. Copyright &copy; <?php echo date('Y'); ?> </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>



        <script src="<?php echo BASE_URL.'assets/site/js/bootstrap.min.js' ?>"></script>
        <script src="<?php echo BASE_URL.'assets/site/js/main.min.js' ?>"></script>
        <script src="<?php echo BASE_URL.'assets/site/js/wow.min.js' ?>"></script>


    </body>
</html>