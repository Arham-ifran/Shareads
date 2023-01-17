
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php
        $uri          = explode("/", $_SERVER['REQUEST_URI']);
        global $urlType;
        $urlType      = @$uri[1];
        $urlType1     = @$uri[2];
        $strings      = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $url          = trim(strtok($strings, '?'));
        $currentPage  = $this->uri->segment(1);
        $currentPage1 = $this->uri->segment(2);
        ?>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <?php
        if (!isset($posts) && empty($posts))
        {
            ?>
            <meta name="keywords" content="<?php echo (isset($meta_keywords) && $meta_keywords <> '') ? $meta_keywords : SITE_KEYWORDS; ?>" />
            <meta name="description" content="<?php echo (isset($meta_description) && $meta_description <> '') ? $meta_description : SITE_DESCRIPTION; ?>" />
            <?php
        }
        else
        {
            ?>
            <meta name="keywords" content="<?php echo (isset($posts) && $posts <> '') ? str_replace('"', ' ', $posts['post_title']) : SITE_KEYWORDS; ?>" />
            <meta name="description" content="<?php echo (isset($posts) && $posts <> '') ? str_replace('"', ' ', $posts['short_description']) : SITE_DESCRIPTION; ?>" />
        <?php } ?>

        <?php
        if (!isset($posts) && empty($posts))
        {
            ?>
            <meta property="og:title" content="<?php echo (isset($title) && $title <> '') ? $title : SITE_TITLE; ?>" />
            <meta property="og:description" content="<?php echo (isset($meta_description) && $meta_description <> '') ? $meta_description : SITE_DESCRIPTION; ?>" />
            <?php
        }
        else
        {
            ?>
            <meta name="og:keywords" content="<?php echo (isset($posts) && $posts <> '') ? str_replace('"', ' ', $posts['post_title']) : SITE_KEYWORDS; ?>" />
            <meta name="og:description" content="<?php echo (isset($posts) && $posts <> '') ? str_replace('"', ' ', $posts['short_description']) : SITE_DESCRIPTION; ?>" />
            <meta name="og:url" content="<?php echo (isset($posts) && $posts <> '') ? base_url('blog/posts/' . $this->common->encode($posts['post_id'])) : base_url(); ?>" />
        <?php } ?>

        <meta property="fb:app_id" content="1203665559658257">
            <meta property="og:url" content="<?php echo base_url(); ?>" />
            <meta property="og:image" content="<?php echo base_url("assets/site/images/logo_fb.jpg"); ?>"/>
            <!--        <meta property="og:image:width" content="475" />
                <meta property="og:image:height" content="355" />-->

            <!-- USED FOR SHARE PURPOSE END-->

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
                                            <!--                                            <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500' rel='stylesheet' type='text/css' />-->
                                            <!-- Bootstrap core CSS -->
                                            <link href="<?php echo base_url('assets/site/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css" />

                                            <!-- FontAwesome Icons -->
                                            <link href="<?php echo base_url('assets/site/css/font-awesome.min.css') ?>" rel="stylesheet" type="text/css" />

                                            <!-- Animation css -->
                                            <link href="<?php echo base_url('assets/site/css/animate.min.css') ?>" rel="stylesheet" />

                                            <!-- Custom styles for this template -->
                                            <link href="<?php echo base_url('assets/site/css/main.min.css') ?>" rel="stylesheet" type="text/css" />
                                            <link href="<?php echo base_url('assets/site/css/style.css') ?>" rel="stylesheet" type="text/css" />
                                            <script src="<?php echo base_url('assets/site/js/jquery.js') ?>"></script>
                                            <script>
                                                var BASE_URL = '<?php echo base_url() ?>';
                                                var currentPage = '<?php echo $this->uri->segment(1); ?>';
                                                var ajax_alert = 'Error occured during Ajax request...';
                                                var LOGIN_USER_ID = '<?php echo $this->session->userdata('user_id') <> '' ? $this->session->userdata('user_id') : 0 ?>';
                                                $(function ()
                                                {
                                                    $('#containerEditable, #containerEditable1').removeAttr('contenteditable').removeAttr('tpl').removeClass('ckeditor');
                                                });
                                            </script>
                                            <style>
                                                /* WELCOME message popup */
                                                div#main-header.signup_bg.welcom-sec {
                                                    min-height: 768px;
                                                }
                                                .welcom-sec .pop-box{
                                                    background: #FFFFFF;
                                                    border: 6px solid #f4f4f4;
                                                    border-radius: 12px;
                                                    padding: 1em 2rem;
                                                }
                                                .welcome-wrap{
                                                    width: 85%;
                                                    margin: 0rem auto;
                                                }
                                                .welcome-message{
                                                    display: block;
                                                    margin-top: 5rem;
                                                }
                                                .welcome-message h1{
                                                    font-size: 32px;
                                                    color: #8ac229;
                                                }
                                                .welcome-message p{
                                                    font-family: 'Avenir Next';
                                                    font-weight: 500;
                                                    font-size: 18px;
                                                    margin: 10px auto;
                                                    color: #2b4761;
                                                    line-height: 20px;
                                                }
                                                .welcome-btns{
                                                    margin: 2rem 0 0 0;
                                                    display: flex;
                                                    flex-direction: row;
                                                    flex-wrap: nowrap;
                                                }
                                                .welcome-btns a{
                                                    flex: 0 1 auto;
                                                    flex-flow: row nowrap;
                                                    margin: 0 7px;
                                                    font-size: 15px;
                                                    padding: 10px;
                                                }
                                                .green_btn{
                                                    border-radius: 50px;
                                                    height: 42px;
                                                    width: 100%;
                                                    text-align: center;
                                                    color: white;
                                                    background: #8ac229;
                                                    text-transform: uppercase;
                                                    font-size: 18px;
                                                    font-family: 'GothamRounded-Medium';
                                                    font-weight: normal;
                                                    outline: none;
                                                }
                                                .green_btn:hover{
                                                    background: #69941f;
                                                    color: #FFFFFF;
                                                }
                                                .blue_btn{
                                                    border-radius: 50px;
                                                    height: 42px;
                                                    width: 100%;
                                                    text-align: center;
                                                    color: white;
                                                    background: #2b4761;
                                                    text-transform: uppercase;
                                                    font-size: 18px;
                                                    font-family: 'GothamRounded-Medium';
                                                    font-weight: normal;
                                                    outline: none;
                                                }
                                                .blue_btn:hover{
                                                    color: white;
                                                    background: #436788;
                                                }
                                                .welcome-checkbox{
                                                    margin: 15px auto;
                                                    display: block;
                                                }
                                                .welcome-checkbox label{
                                                    font-family: 'Avenir Next';
                                                    font-weight: 500;
                                                    font-size: 16px;
                                                    margin: 10px auto;
                                                    color: #2b4761;
                                                }

                                                @media screen and (max-width:767px){
                                                    .welcome-wrap{
                                                        width: 100%;
                                                        margin: 6rem auto;
                                                    }
                                                    .welcome-btns {
                                                        flex-direction: column;
                                                        flex-wrap: wrap;
                                                    }
                                                    .welcome-btns a {
                                                        margin: 10px 0;
                                                        flex-flow: column wrap;
                                                        flex: 0 0 auto;
                                                    }

                                                }

                                            </style>
                                            </head>


                                            <div id="main-header" class="flex-container signup_bg welcom-sec">
                                                <div class="container">
                                                    <div class="row">
                                                        <div class="welcome-wrap">
                                                            <div class="pop-box">
                                                                <div class="col-sm-12 col-xs-12 col-md-12">
                                                                    <a class="logo text-center" href="<?php echo base_url(); ?>">
                                                                        <img src="<?php echo base_url(); ?>/assets/site/images/logo-b.png" class="center-block" alt="">
                                                                    </a>
                                                                </div>
                                                                <div class="clearfix"></div>
                                                                <div class="col-sm-12 col-xs-12 col-md-12">
                                                                    <div class="welcome-message">
                                                                        <h1 class="text-center">Welcome to ShareAds</h1>
                                                                        <p class="text-center"><?php echo $template['content']; ?></p>
                                                                        <div class="welcome-btns">
                                                                            <a href="<?php echo base_url('products'); ?>" class="btn green_btn">Products</a>
                                                                            <a href="<?php echo base_url('dashboard'); ?>" class="btn green_btn">Dashboard</a>
                                                                            <a href="<?php echo base_url('settings/changepassword'); ?>" class="btn blue_btn">Update password</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="clearfix"></div>
                                                                <div class="col-xs-12">
                                                                    <div class="form-group welcome-checkbox">
                                                                        <input type="checkbox" name="status" value="1" class="filled-in" id="filled-in-box" checked>
                                                                            <label for="filled-in-box">Don't show this again!</label>
                                                                    </div>
                                                                </div>
                                                                <div class="clearfix"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- end container -->
                                            </div>



                                            <!-- Placed at the end of the document so the pages load faster -->
                                            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
                                            <script src="<?php echo base_url('assets/site/js/bootstrap.min.js') ?>"></script>
                                            <script>

                                                $(document).ready(function ()
                                                {
                                                    $.ajax({
                                                        type: "POST",
                                                        url: "<?php echo base_url('dashboard/change_welcome_show'); ?>",
                                                        async: true,
                                                        data: {
                                                            status: 0 // as you are getting in php $_POST['action1'] 
                                                        },
                                                        success: function (msg)
                                                        {

                                                        }
                                                    });

                                                    $("input[name=status]").change(function ()
                                                    {
                                                        var value = ($(this).prop('checked') == true) ? 0 : 1;
                                                        $.ajax({
                                                            type: "POST",
                                                            url: "<?php echo base_url('dashboard/change_welcome_show'); ?>",
                                                            async: true,
                                                            data: {
                                                                status: value // as you are getting in php $_POST['action1'] 
                                                            },
                                                            success: function (msg)
                                                            {

                                                            }
                                                        });
                                                    });
                                                });

                                                function openNav()
                                                {
                                                    document.getElementById("myNav").style.width = "100%";
                                                }
                                                function closeNav()
                                                {
                                                    document.getElementById("myNav").style.width = "0%";
                                                }
                                                $('.arrow-section').click(function ()
                                                {
                                                    $('html, body').animate({
                                                        scrollTop: $($(this).attr('href')).offset().top
                                                    }, 500);
                                                    return false;
                                                });
                                            </script>
                                            <script src="<?php echo base_url('assets/site/js/wow.min.js') ?>"></script>
                                            <script>
                                                wow = new WOW(
                                                        {
                                                            animateClass: 'animated',
                                                            offset: 100,
                                                            callback: function (box)
                                                            {
                                                                console.log("WOW: animating <" + box.tagName.toLowerCase() + ">")
                                                            }
                                                        }
                                                );
                                                wow.init();
                                                //    document.getElementById('moar').onclick = function () {
                                                //        var section = document.createElement('section');
                                                //        section.className = 'section--purple wow fadeInDown';
                                                //        this.parentNode.insertBefore(section, this);
                                                //    };
                                            </script>

                                            <script src="<?php echo base_url('assets/site/js/main.min.js') ?>"></script>
                                            <script src="<?php echo base_url('assets/admin/js/validation/jquery.validate.min.js'); ?>"></script>
                                            <script src="<?php echo base_url('assets/admin/js/validation/additional-methods.min.js'); ?>"></script>
                                            <script type="text/javascript" src="<?php echo base_url('assets/site/js/progress/jqprogress.min.js'); ?>"></script>
                                            <script src="<?php echo base_url('assets/admin/js/custom/global.min.js'); ?>"></script>
                                            <script src="<?php echo base_url('assets/site/js/custom/custom_functions.min.js') ?>"></script>


                                            <!--Other Tracking codes-->
                                            <?php echo OTHER_CODES; ?>
                                            <!--Other Tracking codes-->

                                            <!--Google Analytics codes-->
                                            <?php echo GOOGLE_ANALYTICS_CODE; ?>
                                            <!--Google Analytics codes-->

                                            <!--Support Chat code-->
                                            <?php echo SUPPORT_CHAT_CODE; ?>
                                            <!--Support Chat code-->

                                            </body>
                                            </html>