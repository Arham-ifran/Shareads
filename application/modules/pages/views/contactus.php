<h3 class="pull-left">Send Us Your Query</h3>

<div class="clearfix"></div>
<form id="contactus_form" name="contactus_form" action="<?php echo base_url('pages/contactus/' . $pages['slug']) ?>" class="form-horizontal" role="form" method="post" accept-charset="utf-8">
    <fieldset>
        <!-- Form Name -->
        <legend></legend>

        <!--Select input-->
        <div class="form-group animated fadeInDown ">
            <label class="col-md-4 control-label" for="name:">Your Name <span>(required)</span></label>
            <div class="col-md-6">
                <div class="clearfix">
                    <input value="<?php echo $this->session->userdata('full_name'); ?>" name="user_name" id="user_name" placeholder="Enter Your full name" required type="text"  class="form-control">
                </div>
            </div>
        </div>
        <div class="form-group animated fadeInDown">
            <label class="col-md-4 control-label " for="Email:">Your Email <span>(required)</span></label>
            <div class="col-md-6">
                <div class="clearfix">
                    <input value="<?php echo $this->session->userdata('email'); ?>" name="email_address" id="email_address" placeholder="Enter your email" type="email" required class="form-control">
                </div>
            </div>
        </div>

        <div class="form-group animated fadeInDown">
            <label class="col-md-4 control-label " for="Email:">Phone</label>
            <div class="col-md-6">
                <div class="clearfix">
                    <input value="" name="Phone" id="Phone" placeholder="Enter your Phone" type="tel"  class="form-control">
                </div>
            </div>
        </div>

        <div class="form-group animated fadeInDown">
            <label class="col-md-4 control-label " for="Subject:">Subject <span>(required)</span></label>
            <div class="col-md-6">
                <div class="clearfix">
                    <input name="subject" id="subject" placeholder="Subject" required type="text"  class="form-control">
                </div>
            </div>
        </div>
        <div class="form-group animated fadeInDown">
            <label class="col-md-4 control-label " for="message:">Your Message <span>(required)</span></label>
            <div class="col-md-6">
                <div class="clearfix">
                    <textarea id="comments" name="comments" placeholder="Feedback"  required class="form-control"></textarea>
                </div>
            </div>
        </div>



        <!-- Button (Double) -->
        
        <div class="form-group animated fadeInDown">
            <label class="col-md-4 control-label" for="button1id"></label>
            <div class="col-md-8">
                <button type="submit"  class="btn green_btn">Submit</button>
                <button type="reset"  class="btn blue_btn">Reset</button>
            </div>
        </div>
    </fieldset>
</form>

