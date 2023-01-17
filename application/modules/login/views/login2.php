
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 heading-bg">
            <h1>Sign In</h1>
        </div>
    </div>
</div>

<section id="about-us">
    <div class="container">
        <div class="row">
            <div class="col-md-6 wow slideInLeft about-us animated" data-wow-delay="600ms" data-wow-duration="1000ms">
                <div class="login-box">
                    <h3 class="bottom-space">Have An Account? Sign In!</h3><br />
                    <form id="loginForm" name="loginForm" action="<?php echo base_url('login') ?>"  role="form" method="post" accept-charset="utf-8">
                        <input  id="last_url" name="last_url"  type="hidden" value="<?php echo $last_url; ?>" />
                        <div class="form-group">
                            <div class="btn-group ful-section">
                                <a href="<?php echo $fbLoginUrl ?>" class="btn btn-md btn-info fb-color half-section facebook"><i style="margin-right:10px;" class="fa fa-facebook"></i>Facebook</a>
                                <a href="<?php echo base_url('login/twitter') ?>" class="btn btn-md btn-info tw-color half-section twitter"><i style="margin-right:10px;" class="fa fa-twitter"></i>Twitter</a>

                            </div>
                        </div>

                        <div class="form-group">
                            <label class="left-align control-label">Email *</label>
                            <div class="clearfix">
                                <input  id="email" name="email" placeholder="Email (Required)" type="email"  class="form-control" required />

                            </div>
                        </div>

                        <div class="form-group">
                            <label class="left-align control-label">Password *</label>
                            <div class="clearfix">
                                <input id="password"  name="password" placeholder="Password (Required)" type="password"  class="form-control" required/>
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label class="pull-left"> <input type="checkbox" value="1" name="rememberme" class="pull-left"> Remember Me</label>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="larg-btn-red" ><i style="margin-right:10px;" class="fa fa-sign-out"></i>Login</button>
                        </div>
                        <div class="form-group">
                            <a href="<?php echo base_url('register'); ?>" class="btn btn-primary"><i style="margin-right:10px;" class="fa fa-sign-in"></i>Create New Account</a>
                        </div>
                        <div class="form-group">
                            <a href="<?php echo base_url('forgot-password'); ?>" class="btn btn-link" style="float:right; padding-right:0px;">Forgot Password<i style="margin-left:10px;" class="fa fa-arrow-right"></i></a>
                        </div>
                    </form>
                    <br />
                </div>
            </div>


            <div class="col-md-4">
                <div class="sign-in wow slideInRight" data-wow-duration="1000ms" data-wow-delay="300ms"> <img src="<?php echo base_url('assets/site/images/sign-in.png'); ?>"/> </div>
            </div>


        </div>

    </div>

</section>


<?php
$this->load->view('register/bottom_content');
?>