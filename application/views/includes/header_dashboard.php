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
    <?php if (!isset($posts) && empty($posts)){ ?>
         <meta name="keywords" content="<?php echo (isset($meta_keywords) && $meta_keywords <> '') ? $meta_keywords : SITE_KEYWORDS; ?>" />
        <meta name="description" content="<?php echo (isset($meta_description) && $meta_description <> '') ? $meta_description : SITE_DESCRIPTION; ?>" />
        <?php }else{ ?>
                <meta name="keywords" content="<?php echo (isset($posts) && $posts <> '') ? str_replace('"', ' ', $posts['post_title']) : SITE_KEYWORDS; ?>" />
        <meta name="description" content="<?php echo (isset($posts) && $posts <> '') ? str_replace('"', ' ', $posts['short_description']) : SITE_DESCRIPTION; ?>" />
        <?php } ?>
        <meta name="author" content="Arhamsoft.com">
        <title><?php echo (isset($title) && $title <> '') ? $title : SITE_TITLE; ?></title>
        <link href="<?php echo base_url('assets/site/css/responsive.min.css') ?>" rel="stylesheet" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/site/js/progress/jqprogress.min.css'); ?>"/>
        <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500' rel='stylesheet' type='text/css' />
        <link href="<?php echo base_url('assets/site/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url('assets/site/css/font-awesome.min.css') ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url('assets/site/css/animate.min.css') ?>" rel="stylesheet" />
        <link href="<?php echo base_url('assets/site/css/main.min.css') ?>" rel="stylesheet" type="text/css" />
        <!-- core CSS -->
        <link href="<?php echo base_url('assets/site/css/style.min.css') ?>" rel="stylesheet">
        <link href="<?php echo base_url('assets/site/css/style.css') ?>" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/site/js/progress/jqprogress.min.css'); ?>"/>
        <link href="<?php echo base_url('assets/site/css/custom.min.css') ?>" rel="stylesheet">
        <link rel="icon" href="<?php echo base_url('assets/site/images/Favicon.png'); ?>">
        <link rel="shortcut icon" href="<?php echo base_url('assets/site/images/Favicon.png'); ?>">
        <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Roboto:300,400,500' type='text/css'>
        <script src="<?php echo base_url('assets/site/js/jquery.js') ?>"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script>
            var BASE_URL = '<?php echo base_url() ?>';
            var currentPage = '<?php echo $this->uri->segment(1); ?>';
            var IMAGE_UPLOAD_URL = '<?php echo base_url('products') ?>';
            var ajax_alert = 'Error occured during Ajax request...';
            var LOGIN_USER_ID = '<?php echo $this->session->userdata('user_id') <> '' ? $this->session->userdata('user_id') : 0 ?>';
        </script>
        </head>
        <input type="hidden" value="<?php echo base_url() ?>" id="base_url"/>
        <input type="hidden" value="<?php echo $this->session->userdata('full_name') ?>" id="login_user_name"/>
        <input type="hidden" value="<?php echo $this->session->userdata('user_id') ?>" id="login_user_id"/>
        <div id="wraper_divs" style="display:none;"></div>
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
  <div class="alert alertMessage" id="formErrorMsg" style="display: none;">
            <button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
          </div>
  <div class="alert alertMessage" id="formErrorMsgContact" style="display: none">
            <button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
          </div>
  <div class="clearfix"></div>
  <!-- /Notification -->
</div>

        <body>
<!--<div id="myNav" class="overlay"> <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
            <div class="overlay-content">
                <?php
                if ($currentPage <> 'checkout' && $currentPage <> 'payment' && $currentPage <> 'detail') {
                    ?>
                    <a href="<?php echo base_url('logout') ?>"> Logout</a>
                <?php } ?>

            </div>
        </div>-->
<header id="landingpage_header" class="flex-container">
          <div class="container">
          <div class="row">
    <div class="col-sm-6 col-xs-6 col-md-6"> <a class="logo" href="<?php echo base_url(); ?>"> <img src="<?php echo base_url('assets/site/images/logo.png') ?>" class="wow animated fadeInLeft" alt="" /> </a> </div>
    <div class="col-sm-6 col-xs-6 col-md-6 profile-img btn-group" style="background:none; border:none;">
              <div class="profile_area hidden-sm"> <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                      <div class="pic">
                        <img id="p_img" src="<?php $_photo = $this->session->userdata('photo'); if ($_photo == '') { $_photo = 'abc.png';}
                        echo $this->common->is_person_image_exist(base_url("uploads/users/medium/" . $_photo), $this->session->userdata('gender'));
                        ?>" alt="<?php echo $this->session->userdata('full_name') ?>"/> </div>
                      <h4 style="color: white;"> <?php echo $this->session->userdata('full_name') ?>
                <?php if ($this->session->userdata('account_type') == 1) {?>
                <?php } ?>
              </h4>
                </a>
        <ul class="dropdown-menu" style="left:auto !important;">
        <li>
             <a href="<?php echo base_url('dashboard') ?>"> My Dashboard</a>
           
          </li>
          <?php if ($this->session->userdata('account_type') == 1){ ?> 
          <li>
             <a href="<?php echo base_url('wallet') ?>"> Wallet</a>
           
          </li>
                        <?php } ?>
                  <li>
            <?php
                if ($currentPage <> 'checkout' && $currentPage <> 'payment' && $currentPage <> 'detail') {
                    ?>
            <a href="<?php echo base_url('logout') ?>"> Logout</a>
            <?php } ?>
          </li>
          
                </ul>
      </div>
      <!-- Profile Mobile View -->
      <div class="profile_area hidden-lg hidden-md  hidden-xs" style="margin: 20px 0 0 0;"> 
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                      <h4 style="color: white;"> <?php echo $this->session->userdata('full_name') ?>
                <?php if ($this->session->userdata('account_type') == 1) {?>
                <span class="user_id">(<?php echo $this->session->userdata('user_key') ?>)</span>
                <?php } ?>
              </h4>
              <div class="pic">
                        <img id="p_img" src="<?php $_photo = $this->session->userdata('photo'); if ($_photo == '') { $_photo = 'abc.png';}
                        echo $this->common->is_person_image_exist(base_url("uploads/users/medium/" . $_photo), $this->session->userdata('gender'));
                        ?>" alt="<?php echo $this->session->userdata('full_name') ?>"/> </div>
                </a>
        <ul class="dropdown-menu" style="left:auto !important;">
        <li>
             <a href="<?php echo base_url('dashboard') ?>"> My Dashboard</a>
           
          </li>
          <?php if ($this->session->userdata('account_type') == 1){ ?> 
          <li>
             <a href="<?php echo base_url('wallet') ?>"> Wallet</a>
           
          </li>
                        <?php } ?>
                  <li>
            <?php
                if ($currentPage <> 'checkout' && $currentPage <> 'payment' && $currentPage <> 'detail') {
                    ?>
            <a href="<?php echo base_url('logout') ?>"> Logout</a>
            <?php } ?>
          </li>
          
                </ul>
      </div>
            </div>
  </div>
        </header>

