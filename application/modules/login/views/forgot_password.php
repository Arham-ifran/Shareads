<div id="myNav" class="overlay">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <div class="overlay-content">
        <?php
        if ($this->session->userdata('user_id') && $this->session->userdata('is_admin') == 0) {
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
            <a class="<?php echo $currentPage == 'pages' && $currentPage1 == $pages['slug'] ? 'active1' : '' ?>" href="<?php echo base_url($pages['slug']) ?>"><?php echo $pages['title']; ?></a>
            <?php
        }
        ?>
        <a class="<?php echo $currentPage == 'blog' ? 'active1' : '' ?>" href="<?php echo base_url('blog') ?>">Blog</a>

    </div>
</div>

<div id="main-header" class="flex-container signup_bg">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-xs-6 col-md-6"> 
                <a class="logo" href="<?php echo base_url(); ?>">
                    <img src="<?php echo base_url('assets/site/images/logo.png') ?>" class="wow animated fadeInLeft" alt="" />
                </a> 
            </div>
            <div class="col-sm-6 col-xs-6 col-md-6"> <span class="menu_icon wow animated fadeInRight" onclick="openNav()">&#9776;</span> </div>
            <div class="col-sm-12 col-xs-12 col-md-12 caption text-center wow animated fadeInDown">
                <h1 class="signup_heading">Forgot your password</h1>
            </div>
            <div class="clearfix"></div>
            <div class="container">
                <div id="signup_form">
                    <div class="row">
                    <form id="ForgotPasswordForm" name="ForgotPasswordForm" action="<?php echo base_url('forgot-password') ?>"  role="form" method="post" accept-charset="utf-8">
                            <input  id="last_url" name="last_url"  type="hidden" value="<?php echo $last_url; ?>" />
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <input  id="email" name="email" placeholder="Email (Required)" type="email"  class="form-control" required />

                                </div>
                            </div>
                            
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <button type="submit" class="btn green_btn">Submit Request</button>
                                </div>
                            </div>
                        </form>
                        <div class="clearfix"></div>
                        <div class="col-sm-6 login">
                            <a class="btn green_btn no_margin" href="<?php echo base_url('register') ?>" >Create Account</a>
                        </div>
                        <div class="col-sm-6 text-right create_account">
                            <a href="<?php echo base_url('login'); ?>">Login</a>
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
                if (GOOGLE <> '') {
                    ?>
                    <a class="google" href="<?php echo GOOGLE; ?>"><i class="fa fa-google-plus" aria-hidden="true"></i></a> 
                    <?php } ?>


            </div>
        </div>
    </div>
    <!-- end container --> 
</div>

