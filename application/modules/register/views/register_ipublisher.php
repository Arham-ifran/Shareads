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
        if ($this->session->userdata('user_id'))
        {
            ?>
            <a class="<?php echo $currentPage == 'dashboard' ? 'active1' : '' ?>" href="<?php echo base_url('dashboard') ?>"> My Dashboard</a>
            <a href="<?php echo base_url('logout') ?>"> Logout</a>
<?php }
else
{ ?>
            <a class="<?php echo $currentPage == 'login' ? 'active1' : '' ?>" href="<?php echo base_url('login') ?>">Login</a>

        <?php } ?>
        <a class="<?php echo $currentPage == '' || $currentPage == 'home' ? 'active1' : '' ?>" href="<?php echo base_url(); ?>">Home</a>

        <?php
        $pages_data = get_header_menu(4);
        foreach ($pages_data as $pages)
        {
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
            <div class="col-sm-6 col-xs-6 col-md-6"> 
                <a class="logo" href="<?php echo base_url(); ?>">
                    <img src="<?php echo base_url('assets/site/images/logo.png') ?>" class="wow animated fadeInLeft" alt="" />
                </a> 
            </div>
            <div class="col-sm-6 col-xs-6 col-md-6"> <span class="menu_icon wow animated fadeInRight" onclick="openNav()">&#9776;</span> </div>
            <div class="col-sm-6 col-xs-6 col-md-6 caption text-center wow animated fadeInDown">

            </div>
            <script>
                $(function () {
                    $('[data-toggle="tooltip"]').tooltip()
                })
            </script>
            <div class="clearfix"></div>
            <div class="container" style="position:relative; z-index: 1;">
                <h1 class="signup_heading">Welcome to shareads  <a href="#" type="button" class="btn btn-secondary toltip_btn test" data-toggle="tooltip" data-placement="bottom" title="<?php echo P_TEXT ?>"><i class="fa fa-question-circle" aria-hidden="true"></i></a></h1>
                <div style="text-align:  center;"><p style="color: white;width: 87%;text-align: center;margin: auto;margin-bottom: 1%;">We are having the best network of competitive advertisers to ensure the best fit for your products. We are providing the best services as we have top advertisers and technology at the same time. Simply sign up and earn with us.</p></div>
                <div id="signup_form">
                    <div class="row">
                        <form id="signupForm" name="signupForm" action="<?php echo base_url('register/' . $type.'/'.$user_key) ?>"  role="form" method="post" accept-charset="utf-8">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input id="first_name" name="first_name" placeholder="First Name" type="text" value="<?php echo $row['first_name']; ?>"  class="form-control" required/>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input id="last_name" name="last_name" placeholder="Last Name" type="text" value="<?php echo $row['last_name']; ?>"  class="form-control" required/>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <input onchange="checkEmail();" id="email" name="email" placeholder="Email (Required)" type="email" value="<?php echo $row['email']; ?>" class="form-control" required />
                                    <label class="help-block" id="emailExist_error" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <input id="password" name="password" placeholder="Password (Required)" type="password" class="form-control" required />
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <input id="password" name="confirm_password" placeholder="Confirm Password (Required)" type="password" class="form-control" required />
                                </div>
                            </div>

                            <div class="col-xs-12">
                                <div class="form-group">
                                    <button type="submit" class="btn green_btn">Sign Up</button>
                                </div>
								<div class="login form-group">
									<button type="submit" class="btn green_btn"><a href="<?php echo base_url('login') ?>" >Login</a></button>
								</div>
                            </div>
                        </form>

                        <div class="clearfix"></div>
                        
                        <div class="col-sm-6 text-right create_account">
                            <a href="<?php echo base_url('register/advertiser'); ?>">Create an Advertiser account</a><a href="#" type="button" class="btn btn-secondary toltip_btn test" data-toggle="tooltip" data-placement="top" title="<?php echo A_TEXT ?>"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xs-12 col-md-6 text-right socialmedia social_icon"> 
                <?php
                if (FACEBOOK <> '')
                {
                    ?>
                    <a class="facebook" href="<?php echo FACEBOOK; ?>"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                    <?php
                }
                if (TWITTER <> '')
                {
                    ?>
                    <a class="twitter" href="<?php echo TWITTER; ?>"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                        <?php
                    }
                    if (LINKEDIN <> '')
                    {
                        ?>
                    <a class="linkedin" href="<?php echo LINKEDIN; ?>"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
    <?php
}
/* if (GOOGLE <> '') {
  ?>
  <a class="google" href="<?php echo GOOGLE; ?>"><i class="fa fa-google-plus" aria-hidden="true"></i></a>
  <?php } */
?>


            </div>
        </div>
    </div>
    <!-- end container --> 
</div>

