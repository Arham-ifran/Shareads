<?php $this->load->view('includes/profile_info'); ?>

<section class="container" style="padding: 45px">
    <div class="heading_links clearfix"><h3 class="main_heading">Settings</h3></div>
    <div class="row">
        <div class="col-md-6 col-xs-9"><h4>Change Password</h4></div>
    </div>

    <div class="row">

        <div class="col-md-9 col-sm-12">
            <form id="password_form" name="password_form" action="<?php echo base_url('settings/changepassword') ?>" class="form-horizontal" role="form" method="post"  accept-charset="utf-8">
                <fieldset>
                    <!-- Form Name -->
                    <legend></legend>

                    <div class="form-group">
                        <label class="col-sm-3">Old Password</label>
                        <div class="col-md-6">
                            <div class="clearfix">
                                <input minlength="6" type="password" class="form-control input-md" id="password" name="password"  placeholder="Password" value="" autocomplete="off" required="required" /></div>
                        </div>
                    </div>
                    <div class="space-2"></div>

                    <div class="form-group">
                        <label class="col-sm-3">New Password</label>
                        <div class="col-md-6">
                            <div class="clearfix">
                                <input minlength="6" type="password" class="form-control input-md" id="new_password" name="new_password"  placeholder="New Password" value="" autocomplete="off" required="required" /></div>
                        </div>
                    </div>
                    <div class="space-2"></div>


                    <div class="form-group">
                        <label class="col-sm-3">Confirm Password</label>
                        <div class="col-md-6">
                            <div class="clearfix">
                                <input minlength="6" type="password" class="form-control input-md" id="confirm_password" name="confirm_password"  placeholder="Confirm Password" value="" autocomplete="off" required="required" /></div>
                        </div>
                    </div>
                    <div class="space-2"></div>


                    <!-- Button (Double) -->
                    <div class="form-group">
                        <label class="col-sm-3"></label>
                        <div class="col-md-8">
                            <button type="submit" class="btn btn-success">Update Password</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
        <?php $this->load->view('includes/right_bar') ?>
    </div>
</section>
