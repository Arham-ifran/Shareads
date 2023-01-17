
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 heading-bg">
            <h1>Sign Up as Publisher</h1>
        </div>
    </div>
</div>

<section id="about-us">
    <div class="container">
        <div class="row">
            <div class="col-md-6 wow slideInLeft about-us animated" data-wow-delay="600ms" data-wow-duration="1000ms">
                <div class="login-box">
                    <h3 class="bottom-space">Would you like to become a Publisher? Sign up here!</h3><br />
                    <form id="signupForm" name="signupForm" action="<?php echo base_url('register/' . $type) ?>"  role="form" method="post" accept-charset="utf-8">
                        <div class="form-group">
                            <label class="left-align control-label">First Name *</label>
                            <div class="clearfix">
                                <input id="first_name" name="first_name" placeholder="First Name" type="text"  class="form-control" required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="left-align control-label">Last Name *</label>
                            <div class="clearfix">
                                <input id="last_name" name="last_name" placeholder="Last Name" type="text"  class="form-control" required/>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="left-align control-label">Email *</label>
                            <div class="clearfix">
                                <input onchange="checkEmail();" id="email" name="email" placeholder="Email (Required)" type="email"  class="form-control" required />
                                <label class="help-block" id="emailExist_error" style="display: none;"></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="larg-btn-red" ><i style="margin-right:10px;" class="fa fa-sign-out"></i>Sign Up</button>
                        </div>
                        <div class="form-group">
                            <a href="<?php echo base_url('login'); ?>" class="btn btn-primary"><i style="margin-right:10px;" class="fa fa-sign-in"></i>Sign In</a>
                        </div>
                        <div class="form-group">
                            <a href="<?php echo base_url('register/advertiser'); ?>" class="btn btn-link" style="float:right; padding-right:0px;">Create Advertiser Account<i style="margin-left:10px;" class="fa fa-arrow-right"></i></a>
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
$this->load->view('bottom_content');
?>