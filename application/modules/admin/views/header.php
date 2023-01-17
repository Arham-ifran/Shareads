<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="description" content="<?php echo SITE_DESCRIPTION <> '' ? SITE_DESCRIPTION : ''; ?>">
        <meta name="keywords" content="<?php echo SITE_KEYWORDS <> '' ? SITE_KEYWORDS : ''; ?>" />

        <meta name="author" content="Saeed UIlah Khan">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="IE=7" />
        <meta http-equiv="X-UA-Compatible" content="IE=8" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=8;FF=3;OtherUA=4" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <?php
        $uri   = explode("/", $_SERVER['REQUEST_URI']);
        global $type;
        $type  = @$uri[2];
        $type  = str_replace('_', ' ', $type);
        ?>
        <title><?php echo ucwords($type) ?> | <?php echo SITE_NAME <> '' ? SITE_NAME : 'ShareAds'; ?> Admin Panel</title>

        <link rel="icon" href="<?php echo base_url('assets/site/images/Favicon.png'); ?>">
        <link rel="shortcut icon" href="<?php echo base_url('assets/site/images/Favicon.png'); ?>">


        <!-- bootstrap & fontawesome -->
        <link rel="stylesheet" href="<?php echo base_url('assets/admin/css/bootstrap.min.css'); ?>" />
        <link rel="stylesheet" href="<?php echo base_url('assets/admin/font-awesome/css/font-awesome.min.css'); ?>" />

        <!-- page specific plugin styles -->
        <link rel="stylesheet" href="<?php echo base_url('assets/admin/css/jquery-ui.custom.min.css'); ?>" />
        <link rel="stylesheet" href="<?php echo base_url('assets/admin/css/datepicker.min.css'); ?>" />

        <!-- text fonts -->
        <link rel="stylesheet" href="<?php echo base_url('assets/admin/fonts/fonts.googleapis.com.css'); ?>" />


        <!-- ace styles -->
        <link rel="stylesheet" href="<?php echo base_url('assets/admin/css/ace.min.css'); ?>" class="ace-main-stylesheet" id="main-ace-style" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/site/js/progress/jqprogress.min.css'); ?>"/>
        <!-- Custom -->
        <link rel="stylesheet" href="<?php echo base_url('assets/admin/css/custom/custom.css'); ?>" class="" />

        <!--[if lte IE 9]>
                <link rel="stylesheet" href="<?php echo base_url('assets/admin/css/ace-part2.min.css'); ?>" class="ace-main-stylesheet" />
        <![endif]-->

        <!--[if lte IE 9]>
          <link rel="stylesheet" href="<?php echo base_url('assets/admin/css/ace-ie.min.css'); ?>" />
        <![endif]-->

        <!-- inline styles related to this page -->

        <!-- ace settings handler -->
        <script src="<?php echo base_url('assets/admin/js/ace-extra.min.js'); ?>"></script>

        <!--<script src="<?php // echo base_url('assets/admin/js/ace-extra.min.js');      ?>"></script>-->
        <!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

        <!--[if lte IE 8]>
        <script src="<?php echo base_url('assets/admin/js/html5shiv.min.js'); ?>"></script>
        <script src="<?php echo base_url('assets/admin/js/respond.min.js'); ?>"></script>
        <![endif]-->

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

        <?php
        $skins = 'no-skin';
        if ($_COOKIE['skin_s'] <> '')
        {
            $skins = $_COOKIE['skin_s'];
        }
        else if ($_COOKIE['skin_s'] == 'skin-3')
        {
            $skins = $_COOKIE['skin_s'] . ' no-skin';
        }
        if ($skins <> 'no-skin')
        {
            ?>

            <link rel="stylesheet" href="<?php echo base_url('assets/admin/css/ace-skins.min.css'); ?>" class="ace-main-stylesheet" />
        <?php } ?>
        <style>
            .bottom,.top{border-bottom: 1px solid #e0e0e0!important;padding: 12px 12px 12px 12px!important;background-color: #EFF3F8!important;}
            .dt-button.buttons-excel.buttons-html5,.dt-button.buttons-excel.buttons-html5:hover,.dt-button.buttons-excel.buttons-html5:active{background: #428bc9!important;border: none!important;color: white!important;box-shadow: 0px 0px 11px #ccc!important;padding-left: 100%!important;padding-right: 100%!important;}
        </style>
        <style>
            .dataTables_processing {
                color: black !important;
                z-index: 99999 !important;
                top: 40% !important;
                height: auto !important;
                background-color: rgba(255, 255, 255, 0.9) !important;
                box-shadow: 0px 0px 15px #ccc !important;
                top: 45% !important;
                padding: 0 !important;
                left: 80% !important;
                width: 20% !important;
            }
            .dataTables_processing img{
                    height: 73px !important;
            }
        </style>
    </head>
    <div id="wraper_divs" style="display:none;"></div>
    <div id="loaderDiv" class="spinLayout" style="display:none;"><img src="<?php echo base_url('assets/admin/img/loading.gif'); ?>" alt="Loading..." ></div>
    <script type="text/javascript">
        var ADMIN_URL = '<?php echo base_url() ?>admin/';
        var BASE_URL = '<?php echo base_url(); ?>';
        var ajax_alert = 'Error occured during Ajax request...';
        var delete_confirm = 'Are you sure! You want to delete this.';
        var IMAGE_UPLOAD_URL = '<?php echo base_url('admin/listings') ?>';
        var MAX_NO_IMAGE = '<?php echo MAX_NO_IMAGE; ?>';
        var MAX_UPLOAD_SIZE = '<?php echo (MAX_UPLOAD_SIZE == "") ? '20' : MAX_UPLOAD_SIZE; ?>';
        var lbl_active = 'Active';
        var lbl_inactive = 'Inactive';
        var lbl_yes = 'Yes';
        var lbl_no = 'No';
<?php
if ($this->session->flashdata('success_message'))
{
    echo 'setTimeout(function () {$(\'.alertMessage\').slideUp(\'slow\');}, 3000);';
};
?>

    </script>


    <body class="<?php echo 'no-skin'; //$skins;      ?>">
        <div id="navbar" class="navbar navbar-default  ace-save-state">


            <div class="navbar-container ace-save-state" id="navbar-container">
                <button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
                    <span class="sr-only">Toggle sidebar</span>

                    <span class="icon-bar"></span>

                    <span class="icon-bar"></span>

                    <span class="icon-bar"></span>
                </button>

                <div class="navbar-header pull-left">
                    <a title="<?php echo SITE_NAME; ?>" href="<?php echo base_url('admin/dashboard'); ?>" class="navbar-brand">
                        <small>
                            <img alt="<?php echo SITE_NAME; ?>" title="<?php echo SITE_NAME; ?>" src="<?php echo base_url('assets/admin/img/logo50x50.png') ?>" style=" width: 25px;" />
                            <!--<i class="fa fa-leaf"></i>-->
                            <?php echo SITE_NAME; ?>
                        </small>
                    </a>
                </div>

                <div class="navbar-buttons navbar-header pull-right" role="navigation">
                    <ul class="nav ace-nav">

                        <li class="light-blue">
                            <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                                <?php
                                $_photo = $this->session->userdata('photo');
                                if ($_photo == '')
                                {
                                    $_photo = 'abc.png';
                                }
                                $image = $this->common->check_image(base_url("uploads/admin_users/small/" . $_photo), 'no_image.jpg');
                                ?>
                                <img class="nav-user-photo" src="<?php echo $image; ?>" alt="<?php echo $this->session->userdata('full_name'); ?>" />
                                <span class="user-info">
                                    <small>Welcome,</small>
                                    <?php echo $this->session->userdata('full_name'); ?>
                                </span>

                                <i class="ace-icon fa fa-caret-down"></i>
                            </a>

                            <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
                                <?php
                                if (rights(1) == true)
                                {
                                    ?>
                                    <li>
                                        <a href="<?php echo base_url('admin/site_settings'); ?>">
                                            <i class="ace-icon fa fa-cog"></i>
                                            Settings
                                        </a>
                                    </li>
                                <?php } ?>

                                <li>
                                    <a href="<?php echo base_url('admin/admin_users/edit/' . $this->common->encode($this->session->userdata('user_id'))); ?>">
                                        <i class="ace-icon fa fa-user"></i>
                                        Profile
                                    </a>
                                </li>

                                <li class="divider"></li>

                                <li>
                                    <a href="<?php echo base_url('admin/login/logout'); ?>">
                                        <i class="ace-icon fa fa-power-off"></i>
                                        Logout
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div><!-- /.navbar-container -->
        </div>

        <div class="main-container  ace-save-state" id="main-container">


            <!-- include left navigation -->
            <?php $this->load->view('left-nav'); ?>