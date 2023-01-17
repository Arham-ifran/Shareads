<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta charset="utf-8" />
        <title><?php echo SITE_NAME <> '' ? SITE_NAME : 'ShareAds'; ?> Login | Admin Panel</title>

        <meta name="description" content="<?php echo SITE_DESCRIPTION <> '' ? SITE_DESCRIPTION : ''; ?>">
        <meta name="keywords" content="<?php echo SITE_KEYWORDS <> '' ? SITE_KEYWORDS : ''; ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

        <link rel="icon" href="<?php echo base_url('assets/site/images/Favicon.png'); ?>">
        <link rel="shortcut icon" href="<?php echo base_url('assets/site/images/Favicon.png'); ?>">


        <!-- bootstrap & fontawesome -->
        <link rel="stylesheet" href="<?php echo base_url('assets/admin/css/bootstrap.min.css'); ?>" />
        <link rel="stylesheet" href="<?php echo base_url('assets/admin/font-awesome/css/font-awesome.min.css'); ?>" />

        <!-- text fonts -->
        <link rel="stylesheet" href="<?php echo base_url('assets/admin/fonts/fonts.googleapis.com.css'); ?>" />

        <!-- ace styles -->
        <link rel="stylesheet" href="<?php echo base_url('assets/admin/css/ace.min.css'); ?>" />

        <!--[if lte IE 9]>
                <link rel="stylesheet" href="<?php echo base_url('assets/admin/css/ace-part2.min.css'); ?>" />
        <![endif]-->
        <link rel="stylesheet" href="<?php echo base_url('assets/admin/css/ace-rtl.min.css'); ?>" />

        <!--[if lte IE 9]>
          <link rel="stylesheet" href="<?php echo base_url('assets/admin/css/ace-ie.min.css'); ?>" />
        <![endif]-->

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

        <!--[if lt IE 9]>
        <script src="<?php echo base_url('assets/admin/js/html5shiv.min.js'); ?>"></script>
        <script src="<?php echo base_url('assets/admin/js/respond.min.js'); ?>"></script>
        <![endif]-->
    </head>

    <body class="login-layout blur-login">
        <div class="main-container">
            <div class="main-content">
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="login-container">
                            <div class="center">
                                <h1>
                                    <img alt="<?php echo SITE_NAME; ?>" title="<?php echo SITE_NAME; ?>" src="<?php echo base_url('assets/admin/img/logo.png')?>" />
                                    <div class="clearfix"></div>
                                    <span class="red">Admin</span>
                                    <span class="white" id="id-text2">Panel</span>
                                </h1>
<!--                                <h4 class="light-blue" id="id-company-text">&copy; <?php echo SITE_NAME; ?></h4>-->
                            </div>

                            <div class="space-6"></div>

                            <div class="position-relative">
                                <div id="login-box" class="login-box visible widget-box no-border">
                                    <div class="widget-body">
                                        <div class="widget-main">
                                            <h4 class="header blue lighter bigger">
                                                <i class="ace-icon fa fa-coffee green"></i>
                                                Please Enter Your Information
                                            </h4>
                                            <?php
                                            if ($this->session->flashdata('success_message')) {
                                                echo '<div class="alert alert-success">' . $this->session->flashdata('success_message') . '</div>';
                                            };
                                            ?>
                                            <div class="clearfix"></div>
                                            <!-- Notification -->
                                            <?php
                                            if ($this->session->flashdata('error_message')) {
                                                echo '<div class="alert alert-danger">' . $this->session->flashdata('error_message') . '</div>';
                                            };
                                            ?>
                                            <div class="clearfix"></div>
                                            <!-- /Notification -->
                                            <div class="space-6"></div>

                                            <form id="login_form" name="login_form" action="<?php echo base_url('admin/login'); ?>" method="post"><input  id="last_url" name="last_url"  type="hidden" value="<?php echo $last_url;?>" />
                                                <fieldset>
                                                    <label class="block clearfix ">
                                                        <span class="block input-icon input-icon-right">
                                                            <input type="email" name="email" id="email" class="form-control" placeholder="Email" required/>
                                                            <i class="ace-icon fa fa-user"></i>
                                                        </span>
                                                    </label>

                                                    <label class="block clearfix ">
                                                        <span class="block input-icon input-icon-right">
                                                            <input type="password" name="password" id="password" class="form-control" placeholder="Password" required/>
                                                            <i class="ace-icon fa fa-lock"></i>
                                                        </span>
                                                    </label>

                                                    <div class="space"></div>

                                                    <div class="clearfix">
                                                        <label class="inline">
                                                            <input type="checkbox" name="rememberme" id="rememberme" class="ace" />
                                                            <span class="lbl"> Remember Me</span>
                                                        </label>

                                                        <button type="submit" class="width-35 pull-right btn btn-sm btn-primary">
                                                            <i class="ace-icon fa fa-key"></i>
                                                            <span class="bigger-110">Login</span>
                                                        </button>
                                                    </div>

                                                    <div class="space-4"></div>
                                                </fieldset>
                                            </form>


                                        </div><!-- /.widget-main -->


                                    </div><!-- /.widget-body -->
                                </div><!-- /.login-box -->




                            </div><!-- /.position-relative -->


                        </div>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.main-content -->
        </div><!-- /.main-container -->

        <!-- basic scripts -->

        <!--[if !IE]> -->
        <script src="<?php echo base_url('assets/admin/js/jquery.2.1.1.min.js'); ?>"></script>

        <!-- <![endif]-->

        <!--[if IE]>
<script src="<?php echo base_url('assets/admin/js/jquery.1.11.1.min.js'); ?>"></script>
<![endif]-->

        <!--[if !IE]> -->
        <script type="text/javascript">
            window.jQuery || document.write("<script src='<?php echo base_url('assets/admin/js/jquery.min.js'); ?>'>" + "<" + "/script>");
        </script>

        <!-- <![endif]-->

        <!--[if IE]>
<script type="text/javascript">
window.jQuery || document.write("<script src='<?php echo base_url('assets/admin/js/jquery1x.min.js'); ?>'>"+"<"+"/script>");
</script>
<![endif]-->
        <script type="text/javascript">
            if ('ontouchstart' in document.documentElement)
                document.write("<script src='<?php echo base_url('assets/admin/js/jquery.mobile.custom.min.js'); ?>'>" + "<" + "/script>");
        </script>
        <script src="<?php echo base_url('assets/admin/js/validation/jquery.validate.js'); ?>"></script>
        <script src="<?php echo base_url('assets/admin/js/validation/additional-methods.js'); ?>"></script>

        <script src="<?php echo base_url('assets/admin/js/custom/global.js'); ?>"></script>
        <script src="<?php echo base_url('assets/admin/js/custom/custom_functions.js'); ?>"></script>

        <!-- inline scripts related to this page -->
        <script type="text/javascript">
            jQuery(function ($) {
                $('#login_form').validate();

            });

            jQuery(function ($) {
                $('#btn-login-dark').on('click', function (e) {
                    $('body').attr('class', 'login-layout');
                    $('#id-text2').attr('class', 'white');
                    $('#id-company-text').attr('class', 'blue');

                    e.preventDefault();
                });
                $('#btn-login-light').on('click', function (e) {
                    $('body').attr('class', 'login-layout light-login');
                    $('#id-text2').attr('class', 'grey');
                    $('#id-company-text').attr('class', 'blue');

                    e.preventDefault();
                });
                $('#btn-login-blur').on('click', function (e) {
                    $('body').attr('class', 'login-layout blur-login');
                    $('#id-text2').attr('class', 'white');
                    $('#id-company-text').attr('class', 'light-blue');

                    e.preventDefault();
                });

            });
        </script>
    </body>
</html>
