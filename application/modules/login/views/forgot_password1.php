
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 heading-bg">
            <h1>Forgot Password</h1>
        </div>
    </div>
</div>

<section id="about-us">
    <div class="container">
        <div class="row">
            <div class="col-md-6 wow slideInLeft about-us animated" data-wow-delay="600ms" data-wow-duration="1000ms">
                <div class="login-box">
                    <h3 class="bottom-space">Forgot your password</h3><br />
                    <form id="ForgotPasswordForm" name="ForgotPasswordForm" action="<?php echo base_url('forgot-password') ?>"  role="form" method="post" accept-charset="utf-8">

                        <div class="form-group">
                            <label class="left-align control-label">Email *</label>
                            <div class="clearfix">
                                <input  id="email" name="email" placeholder="Email (Required)" type="email"  class="form-control" required />

                            </div>
                        </div>



                        <div class="form-group">
                            <button type="submit" class="larg-btn-red" ><i style="margin-right:10px;" class="fa fa-sign-out"></i>Submit Request</button>
                        </div>
                        <div class="form-group">
                            <a href="<?php echo base_url('register'); ?>" class="btn btn-primary"><i style="margin-right:10px;" class="fa fa-sign-in"></i>Create New Account</a>
                        </div>
                        <div class="form-group">
                            <a href="<?php echo base_url('login'); ?>" class="btn btn-primary"><i style="margin-right:10px;" class="fa fa-sign-in"></i>Sign In</a>
                        </div>
                    </form>

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