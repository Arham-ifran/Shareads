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
    .toltip_btn:hover i {
        color: #73AD21; 
    }
    .test + .tooltip.bottom > .tooltip-arrow {
        border-bottom: 5px solid #73AD21;
    }
    .test + .tooltip.top > .tooltip-arrow {
        border-top: 5px solid #73AD21;
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
            <?php
        }
        else
        {
            ?>
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
            <div class="col-sm-12 col-xs-12 col-md-6"> 
                <a class="logo" href="<?php echo base_url(); ?>">
                    <img src="<?php echo base_url('assets/site/images/logo.png') ?>" class="wow animated fadeInLeft" alt="" />
                </a> 
            </div>
            <div class="col-sm-12 col-xs-12 col-md-6"> <span class="menu_icon wow animated fadeInRight" onclick="openNav()">&#9776;</span> </div>
            <div class="col-sm-12 col-xs-12 col-md-12 caption text-center wow animated fadeInDown">
            </div>
            <script>
                $(function ()
                {
                    $('[data-toggle="tooltip"]').tooltip()
                })
            </script>
            <div class="clearfix"></div>
            <div class="container" style="position:relative; z-index: 1;">
                <h1 class="signup_heading">Become an Advertiser today  <a href="#" type="button" class="btn btn-secondary toltip_btn test" data-toggle="tooltip" data-placement="bottom" title="<?php echo A_TEXT ?>"><i class="fa fa-question-circle" aria-hidden="true"></i></a></h1>
                <div id="signup_form">
                    <div class="row">
                        <form id="signupForm" name="signupForm" action="<?php echo base_url('register/' . $type) ?>"  role="form" method="post" accept-charset="utf-8">
                            <input type="hidden" name="is_from_campaign" value="1" />
                            <input type="hidden" name="site_refrence" id="site_refrence" value="" />
                            
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input id="first_name" name="first_name" placeholder="First Name" type="text"  class="form-control" required/>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input id="last_name" name="last_name" placeholder="Last Name" type="text"  class="form-control" required/>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <input onchange="checkEmail();" id="email" name="email" placeholder="Email (Required)" type="email"  class="form-control" required />
                                    <label class="help-block" id="emailExist_error" style="display: none;"></label>
                                </div>
                            </div>

                            <div class="col-xs-12">
                                <div class="form-group">
                                    <button type="submit" class="btn green_btn">Sign Up</button>
                                </div>
                            </div>
                        </form>

                        <div class="clearfix"></div>

                        <div class="col-sm-6 social_button">
                            <div class="form-group">
                                <a href="<?php echo $fbLoginUrl ?>" class="facebook" ><i class="fa fa-facebook"></i>Facebook</a>
                            </div>
                        </div>
                        <div class="col-sm-6 social_button">
                            <div class="form-group">
                                <a href="<?php echo base_url('login/twitter') ?>" class="twitter"><i class="fa fa-twitter"></i>Twitter</a>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-sm-6 login">
                            <a href="<?php echo base_url('login') ?>" >Login</a>
                        </div>
                        <div class="col-sm-6 text-right create_account">
                            <a href="<?php echo base_url('register/publisher'); ?>">Create a Publisher account</a><a href="#" type="button" class="btn btn-secondary toltip_btn test" data-toggle="tooltip" data-placement="top" title="<?php echo P_TEXT; ?>"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
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
                <script>
<?php
if (isset($_GET))
{
    if (isset($_GET['fname']) && trim($_GET['fname']) <> '')
    {
        ?> document.getElementById('first_name').value = '<?php echo trim($_GET['fname']); ?>'; <?php
    }
    if (isset($_GET['lname']) && trim($_GET['lname']) <> '')
    {
        ?> document.getElementById('last_name').value = '<?php echo trim($_GET['lname']); ?>'; <?php
    }
    if (isset($_GET['email']) && trim($_GET['email']) <> '')
    {
        ?> document.getElementById('email').value = '<?php echo trim($_GET['email']); ?>'; <?php
    }
    if (isset($_GET['sr']) && trim($_GET['sr']) <> '')
    {
        ?> document.getElementById('site_refrence').value = '<?php echo trim($_GET['sr']); ?>'; <?php
    }
    if((isset($_GET['fname']) && trim($_GET['fname']) <> '') && (isset($_GET['lname']) && trim($_GET['lname']) <> '') && (isset($_GET['email']) && trim($_GET['email']) <> '')){
    ?>
    //$('form').append('<input type="hidden" name="is_from_campaign" value="1" />');
    <?php
    }
}
?>
                </script>
            </div>
        </div>
    </div>
    <!-- end container --> 
</div>

