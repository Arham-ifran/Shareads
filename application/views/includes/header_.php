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
        $currentPage = $this->uri->segment(1);
        $currentPage1 = $this->uri->segment(2);
        ?>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="<?php echo (isset($meta_description) && $meta_description <> '') ? $meta_description : SITE_DESCRIPTION; ?>">
        <meta name="keywords" content="<?php echo (isset($meta_keywords) && $meta_keywords <> '') ? $meta_keywords : SITE_KEYWORDS; ?>" />

        <meta name="author" content="Arhamsoft.com">
        <title><?php echo (isset($title) && $title <> '') ? $title : SITE_TITLE; ?></title>

        <!-- core CSS -->
        <link href="<?php echo base_url('assets/site/css/bootstrap.min.css') ?>" rel="stylesheet">
        <link href="<?php echo base_url('assets/site/css/font-awesome.min.css') ?>" rel="stylesheet">
        <link href="<?php echo base_url('assets/site/css/animate.min.css') ?>" rel="stylesheet">
        <link href="<?php echo base_url('assets/site/css/main.min.css') ?>" rel="stylesheet">
        <link href="<?php echo base_url('assets/site/css/responsive.min.css') ?>" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/site/js/progress/jqprogress.min.css'); ?>"/>
        <link href="<?php echo base_url('assets/site/css/custom.min.css') ?>" rel="stylesheet">
        <link rel="icon" href="<?php echo base_url('assets/site/images/Favicon.png'); ?>">
        <link rel="shortcut icon" href="<?php echo base_url('assets/site/images/Favicon.png'); ?>">


        <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500' rel='stylesheet' type='text/css'>

        <script src="<?php echo base_url('assets/site/js/jquery.js') ?>"></script>
        <script>
            var BASE_URL = '<?php echo base_url() ?>';
            var currentPage = '<?php echo $this->uri->segment(1); ?>';
            var ajax_alert = 'Error occured during Ajax request...';
            var LOGIN_USER_ID = '<?php echo $this->session->userdata('user_id') <> '' ? $this->session->userdata('user_id') : 0 ?>';
            $(function () {
                $('#containerEditable, #containerEditable1').removeAttr('contenteditable').removeAttr('tpl').removeClass('ckeditor');
            });
        </script>
    </head>
    <input type="hidden" value="<?php echo base_url() ?>" id="base_url"/>
    <input type="hidden" value="<?php echo $this->session->userdata('full_name') ?>" id="login_user_name"/>
    <input type="hidden" value="<?php echo $this->session->userdata('user_id') ?>" id="login_user_id"/>
    <!--/head-->

    <div id="wraper_divs" style="display:none;"></div>

    <?php
    $currentPage = $this->uri->segment(1);
    ?>

    <div class="gritter_div col-md-4">

        <?php
        if ($this->session->flashdata('success_message')) {
            echo '<div class="alert alert-success alertMessage">'
            . '<button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>'
            //. '<i class="ace-icon fa fa-check-circle-o green fa-3x"></i>'
            . $this->session->flashdata('success_message') . '
                                </div>';
        }
        ?>
        <div class="clearfix"></div>
        <?php echo validation_errors('<div class="alert alert-danger alertMessage"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>', '</div>'); ?>
        <!-- Notification -->
        <?php
        if ($this->session->flashdata('error_message')) {
            echo '<div class="alert alert-danger alertMessage">'
            . '<button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>'
            . $this->session->flashdata('error_message') . '</div>';
        };
        ?>
        <div class="alert alertMessage" id="formErrorMsg" style="display: none;"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button></div>

        <div class="alert alertMessage" id="formErrorMsgContact" style="display: none">
            <button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
        </div>

        <div class="clearfix"></div>
        <!-- /Notification -->
    </div>

    <body class="homepage">
        <div class="menu123">
            <header id="header">
                <div class="top-bar">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6 col-sm-4 col-xs-12 ">
                                <div class="social top-bdr">
                                    <ul class="social-share">
                                        <?php
                                        if (FACEBOOK <> '') {
                                            ?>
                                            <li class="fb-color"><a href="<?php echo FACEBOOK; ?>"><i class="fa fa-facebook"></i></a></li>
                                            <?php
                                        }
                                        if (TWITTER <> '') {
                                            ?>
                                            <li class="tw-color"><a href="<?php echo TWITTER; ?>"><i class="fa fa-twitter"></i></a></li>
                                            <?php
                                        }
                                        if (LINKEDIN <> '') {
                                            ?>
                                            <li class="li-color"><a href="<?php echo LINKEDIN; ?>"><i class="fa fa-linkedin"></i></a></li>
                                            <?php
                                        }
                                        if (GOOGLE <> '') {
                                            ?>
                                            <li class="go-color" style="border-right:solid 1px #505050;"><a href="<?php echo GOOGLE; ?>"><i class="fa fa-google-plus"></i></a></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-8 col-xs-12 contact_bar">
                                <div class="login_btn">
                                    <?php
                                    if ($this->session->userdata('user_id')) {
                                        ?>
                                        <a href="<?php echo base_url('dashboard') ?>" class="btn"><i class="fa fa-dashboard"></i> My Dashboard</a>

                                        <a href="<?php echo base_url('logout') ?>" class="btn btn-danger"><i class="fa fa-sign-out"></i> Logout</a>

                                    <?php } else { ?>
                                        <a href="<?php echo base_url('login') ?>" class="btn"><i class="fa fa-key"></i>&nbsp;Login</a>

                                    <?php } ?>
                                </div>
                                <div class="top-number hidden">
                                    <p>
                                        <i class="fa fa-phone"></i> <?php echo ADMIN_PHONE; ?>
                                        <span><br/></span>
                                        <i class="fa fa-map-marker"></i> <?php echo ADMIN_ADDRESS; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <nav class="navbar navbar-inverse other-pages" role="banner">
                    <div class="container">
                        <div class="navbar-header">

                            <a class="navbar-brand" href="<?php echo base_url() ?>"><img src="<?php echo base_url('assets/site/images/logo.png') ?>" alt="<?php echo SITE_NAME; ?>" title="<?php echo SITE_NAME; ?>"></a>
                        </div>
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                            <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
                        </button>
                        <div class="row">
                            <div class="btn-group btn-width navbar-right hidden">
                                <?php
                                if ($this->session->userdata('user_id')) {
                                    ?>
                                    <a class="btn btn-danger btn-lg col-xs-12" href="<?php echo base_url('dashboard') ?>"><i class="fa fa-user"></i>&nbsp;My Dashboard</a>
                                <?php } else { ?>
                                    <a class="btn btn-danger btn-lg col-xs-12" href="<?php echo base_url('login') ?>"><i class="fa fa-user"></i>&nbsp;My Account</a>
                                <?php } ?>
                            </div>
                            <div class="col-md-8 col-sm-12 col-xs-12">
                                <div class="collapse navbar-collapse yamm">
                                    <ul class="nav navbar-nav">
                                        <li><a class="<?php echo $currentPage == '' || $currentPage == 'home' ? 'active1' : '' ?>" href="<?php echo base_url(); ?>">Home</a></li>

                                        <?php
                                        $pages_data = get_header_menu(4);
                                        foreach ($pages_data as $pages) {
                                            ?>
                                            <li><a class="<?php echo $currentPage == $pages['slug'] ? 'active1' : '' ?>" href="<?php echo base_url($pages['slug']) ?>"><?php echo $pages['title']; ?></a></li>
                                            <?php
                                        }
                                        ?>
                                        <li><a class="<?php echo $currentPage == 'blog' ? 'active1' : '' ?>" href="<?php echo base_url('blog') ?>">Blog</a></li>
                                        <?php
                                        if ($this->session->userdata('user_id')) {
                                            ?>

                                            <li class="loginCell"><a class="<?php echo $currentPage == 'dashboard' ? 'active1' : '' ?>" href="<?php echo base_url('dashboard') ?>">Dashboard</a></li>

                                        <?php } else { ?>
                                            <li class="loginCell"><a class="<?php echo $currentPage == 'login' ? 'active1' : '' ?>" href="<?php echo base_url('login') ?>">Login</a></li>
                                            <?php }
                                            ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>

            </header>
        </div>
