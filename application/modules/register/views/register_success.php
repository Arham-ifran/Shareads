<style>
    .toltip_btn{
        background: transparent;
        color: white;
        font-size: 32px;
    }
    .test + .tooltip > .tooltip-inner {
        background-color: #73AD21;
        color: #FFFFFF;
        border: 1px solid green;
        padding: 5px;
        max-width:400px;
        width: 100% !important;
        font-size: 12px;

    }
    .test + .tooltip.bottom > .tooltip-arrow {
        border-bottom: 5px solid #73AD21;
    }
.test + .tooltip.top > .tooltip-arrow {
        border-top: 5px solid #73AD21;
    }
.toltip_btn:hover i {
    color: #73AD21;
}
    .tooltip{
        position:absolute !important;
        z-index:9999 !important;}
    @media (max-width:400px)
    {.tooltip{
         right:10%;}}
     </style>
<div id="myNav" class="overlay">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <div class="overlay-content">
        <?php
        if ($this->session->userdata('user_id')) {
            ?>
            <a class="<?php echo $currentPage == 'dashboard' ? 'active1' : '' ?>" href="<?php echo base_url('dashboard') ?>"> My Dashboard</a>
            <a href="<?php echo base_url('logout') ?>"> Logout</a>
        <?php } else { ?>
            <a class="<?php echo $currentPage == 'login' ? 'active1' : '' ?>" href="<?php echo base_url('login') ?>">Login</a>

        <?php } ?>
        <a class="<?php echo $currentPage == '' || $currentPage == 'home' ? 'active1' : '' ?>" href="<?php echo base_url(); ?>">Home</a>

        <?php
        $pages_data = get_header_menu(4);
        foreach ($pages_data as $pages) {
            ?>
            <a class="<?php echo $currentPage == 'pages' && $currentPage1 == $pages['slug'] ? 'active1' : '' ?>" href="<?php echo base_url('pages/' . $pages['slug']) ?>"><?php echo $pages['title']; ?></a>
            <?php
        }
        ?>
        <a class="<?php echo $currentPage == 'blog' ? 'active1' : '' ?>" href="<?php echo base_url('blog') ?>">Blog</a>

    </div>
</div>

<div id="main-header" class="flex-container signup_bg">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-xs-12 col-md-6">
                <a class="logo" href="<?php echo base_url(); ?>">
                    <img src="<?php echo base_url('assets/site/images/logo.png') ?>" class="wow animated fadeInLeft" alt="" />
                </a>
            </div>
            <div class="col-sm-12 col-xs-12 col-md-6"> <span class="menu_icon wow animated fadeInRight" onclick="openNav()">&#9776;</span> </div>
            <div class="col-sm-12 col-xs-12 col-md-12 caption text-center wow animated fadeInDown">

            </div>
            <script>
                $(function () {
                    $('[data-toggle="tooltip"]').tooltip()
                })
            </script>
            <div class="clearfix"></div>
            <div class="container" style="position:relative; z-index: 1;">

                <div id="signup_form">
                    <div class="row">

                        <div class="alert alert-success">Congratulation! Your account has been created.</h5>. Please check your email for account information.</div>



                        <div class="clearfix"></div>
                        <div class="col-sm-6 login">
                            <a  href="<?php echo base_url('login') ?>" >Login</a>
                        </div>
                        <div class="col-sm-6 text-right create_account">
                            <a href="<?php echo base_url('register/advertiser'); ?>">Create an Advertiser account</a><a href="#" type="button" class="btn btn-secondary toltip_btn test" data-toggle="tooltip" data-placement="top" title="<?php echo A_TEXT ?>"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xs-12 col-md-6 text-right socialmedia social_icon">
                <?php
                if (FACEBOOK <> '') {
                    ?>
                    <a class="facebook" href="<?php echo FACEBOOK; ?>"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                    <?php
                }
                if (TWITTER <> '') {
                    ?>
                    <a class="twitter" href="<?php echo TWITTER; ?>"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                    <?php
                }
                if (LINKEDIN <> '') {
                    ?>
                    <a class="linkedin" href="<?php echo LINKEDIN; ?>"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
                    <?php
                }
                /*if (GOOGLE <> '') {
                    ?>
                    <a class="google" href="<?php echo GOOGLE; ?>"><i class="fa fa-google-plus" aria-hidden="true"></i></a>
                    <?php }*/ ?>


            </div>
        </div>
    </div>
    <!-- end container -->
</div>

     <!-- REFERSION TRACKING: BEGIN -->
<script src="//mizslp.refersion.com/tracker/v3/pub_a735cfbf5531d9b1489e.js"></script>
<script>_refersion(function(){ _rfsn._addCart("<?php echo $user_id.$user_key?>"); });</script>
<!-- REFERSION TRACKING: END -->

     <!-- REFERSION TRACKING: BEGIN -->
<script>
_refersion(function(){

        
	_rfsn._addCustomer({
		'first_name': '<?php echo $first_name?>',
		'last_name': '<?php echo $last_name?>',
		'email': '<?php echo $email?>',
		'ip_address': '<?php echo $_SERVER['REMOTE_ADDR']?>'
	});

});
</script>
<!-- REFERSION TRACKING: END -->



