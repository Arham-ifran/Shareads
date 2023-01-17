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


        </script>
    </head>
    <input type="hidden" value="<?php echo base_url() ?>" id="base_url"/>
    <input type="hidden" value="<?php echo $this->session->userdata('full_name') ?>" id="login_user_name"/>
    <input type="hidden" value="<?php echo $this->session->userdata('user_id') ?>" id="login_user_id"/>
    <!--/head-->

    <style>
        .btn-danger {
    margin-top: 0 !important;
}
    </style>

    <div id="wraper_divs" style="display:none;"></div>

    <?php
    $currentPage = $this->uri->segment(1);
    ?>

    <body class="homepage">
        <div class="menu123">
            <header id="header">
                <div class="top-bar">


                </div>

<!--                <nav class="navbar navbar-inverse other-pages" role="banner">
                    <div class="container">
                        <div class="navbar-header">

                            <div class="navbar-brand" style="cursor:default;"><img src="<?php echo base_url('assets/site/images/logo.png') ?>" alt="<?php echo SITE_NAME; ?>" title="<?php echo SITE_NAME; ?>"></div>



                        </div>

                    </div>
                </nav>-->

            </header>
        </div>
