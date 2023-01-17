<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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
        <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />  
        <?php if (!isset($posts) && empty($posts)){ ?>
         <meta name="keywords" content="<?php echo (isset($meta_keywords) && $meta_keywords <> '') ? $meta_keywords : SITE_KEYWORDS; ?>" />
        <meta name="description" content="<?php echo (isset($meta_description) && $meta_description <> '') ? $meta_description : SITE_DESCRIPTION; ?>" />
        <?php }else{ ?>
                <meta name="keywords" content="<?php echo (isset($posts) && $posts <> '') ? str_replace('"', ' ', $posts['post_title']) : SITE_KEYWORDS; ?>" />
        <meta name="description" content="<?php echo (isset($posts) && $posts <> '') ? str_replace('"', ' ', $posts['short_description']) : SITE_DESCRIPTION; ?>" />
        <?php } ?>
     <?php if (!isset($posts) && empty($posts)){ ?>
             <meta property="og:title" content="<?php echo (isset($title) && $title <> '') ? $title : SITE_TITLE; ?>" />
        <meta property="og:description" content="<?php echo (isset($meta_description) && $meta_description <> '') ? $meta_description : SITE_DESCRIPTION; ?>" />
        <?php }else{ ?>
                <meta name="og:keywords" content="<?php echo (isset($posts) && $posts <> '') ? str_replace('"', ' ', $posts['post_title']) : SITE_KEYWORDS; ?>" />
        <meta name="og:description" content="<?php echo (isset($posts) && $posts <> '') ? str_replace('"', ' ', $posts['short_description']) : SITE_DESCRIPTION; ?>" />
         <meta name="og:url" content="<?php echo (isset($posts) && $posts <> '') ? base_url('blog/posts/'.$this->common->encode($posts['post_id'])) : base_url(); ?>" />
        <?php } ?>
        <meta property="fb:app_id" content="1203665559658257"/>
        <meta property="og:image" content="<?php echo base_url("assets/site/images/logo_fb.jpg"); ?>"/>
        <meta property="og:url" content="<?php echo base_url(); ?>" />
        <meta name="twitter:card" content="summary_large_image">
            <meta name="twitter:site" content="@shareads">
            <meta name="twitter:title" content="<?php echo (isset($title) && $title <> '') ? $title : SITE_TITLE; ?>">
            <meta name="twitter:description" content="<?php echo (isset($meta_description) && $meta_description <> '') ? $meta_description : SITE_DESCRIPTION; ?>">
                        <?php
                        $image = base_url("uploads/products/medium/d-logo_37876682263.png");
                        echo '<meta name="twitter:image:src" content="' . $image . '">';
                        ?>
            <meta name="twitter:image:alt" content="<?php echo (isset($title) && $title <> '') ? $title : SITE_TITLE; ?>" />
            <meta name="twitter:domain" content="<?php echo base_url(); ?>">

                            <!--Google-->
            <meta itemprop="name" content="<?php echo (isset($title) && $title <> '') ? $title : SITE_TITLE; ?>">
            <meta itemprop="description" content="<?php echo (isset($meta_description) && $meta_description <> '') ? $meta_description : SITE_DESCRIPTION; ?>">
            <meta itemprop="image" content="<?php echo $image; ?>">
        <meta name="author" content="Arhamsoft.com" />
        <title><?php echo (isset($title) && $title <> '') ? $title : SITE_TITLE; ?></title>
        <link href="<?php echo base_url('assets/site/css/responsive.min.css') ?>" rel="stylesheet" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/site/js/progress/jqprogress.min.css'); ?>"/>
        <link href="<?php echo base_url('assets/site/css/custom.min.css') ?>" rel="stylesheet"/>
        <link rel="icon" href="<?php echo base_url('assets/site/images/Favicon.png'); ?>"/>
        <link rel="shortcut icon" href="<?php echo base_url('assets/site/images/Favicon.png'); ?>"/>
        <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500' rel='stylesheet' type='text/css' />
        <link href="<?php echo base_url('assets/site/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url('assets/site/css/font-awesome.min.css') ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url('assets/site/css/animate.min.css') ?>" rel="stylesheet" />
        <link href="<?php echo base_url('assets/site/css/main.min.css') ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url('assets/site/css/style.css') ?>" rel="stylesheet" type="text/css" />
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
    <div id="wraper_divs" style="display:none;"></div>
    <?php
    $currentPage = $this->uri->segment(1);
    ?>
    <div class="gritter_div col-md-4">

        <?php
        if ($this->session->flashdata('success_message')) {
            echo '<div class="alert alert-success alertMessage">'
            . '<button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>'
            . $this->session->flashdata('success_message') . '
                                </div>';
        }
        ?>
        <div class="clearfix"></div>
        <?php echo validation_errors('<div class="alert alert-danger alertMessage"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>', '</div>'); ?>
        <?php
        if ($this->session->flashdata('error_message')) {
            echo '<div class="alert alert-danger alertMessage">'
            . '<button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>'
            . $this->session->flashdata('error_message') . '</div>';
        };
        ?>
        <div class="alert alertMessage" id="formErrorMsg" style="display: none;"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button></div>
        <div class="alert alertMessage" id="formErrorMsgContact" style="display: none;">
            <button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
        </div>
        <div class="clearfix"></div>
    </div>
    <body>