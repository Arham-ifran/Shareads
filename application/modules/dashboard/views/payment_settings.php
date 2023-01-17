<?php $this->load->view('includes/profile_info'); ?>

<section class="container">
    <div class="heading_links clearfix"><h3 class="main_heading">Settings</h3></div>
    <div class="row">
        <div class="col-md-6 col-xs-9"><h4>Edit Payment Settings</h4></div>
    </div>

    <div class="row">

        <div class="col-md-9 col-sm-12">
            <form id="users_form" name="users_form" action="<?php echo base_url('settings/payment_settings') ?>" class="form-horizontal" role="form" method="post"  accept-charset="utf-8" enctype="multipart/form-data">
                <fieldset>
                    <!-- Form Name -->
                    <legend></legend>

                    <div class="form-group">
                        <label class="col-sm-3">PayPal Email *</label>
                        <div class="col-md-6">
                            <div class="clearfix">
                                <input type="email" id="paypal_email" name="paypal_email" placeholder="PayPal Email" class="form-control" value="<?php echo $userdata['paypal_email'] ?>"  required/>
                                <label class="alert-danger" id="paypal_error" style="display: none;"></label>
                            </div>
                        </div>
                    </div>
                    <div class="space-2"></div>
                    <div class="form-group ">
                        <label class="col-sm-3">Payment Schedule</label>
                        <div class="col-md-6">
                            <label class="col-sm-5">
                                <input type="radio" id="payment_schedule" class="ace" <?php echo ( isset($userdata) && $userdata['payment_schedule'] == '1') ? 'checked' : '' ?> value="1" name="payment_schedule" >
                                <span class="lbl">&nbsp;By weekly <small>after 15 days</small></span>
                            </label>
                            <label class="col-sm-3">
                                <input type="radio" class="ace" <?php echo ( isset($userdata) && $userdata['payment_schedule'] == '2') ? 'checked' : '' ?> <?php echo (!isset($userdata)) ? 'checked' : '' ?> id="payment_schedule" value="2" name="payment_schedule" >
                                <span class="lbl">&nbsp;Monthly</span>
                            </label>
                        </div>
                    </div>
                    <div class="space-2"></div>
                    <!-- Button (Double) -->
                    <div class="form-group">
                        <label class="col-sm-3"></label>
                        <div class="col-md-8">
                            <button type="submit" class="btn btn-success">Update Payment Settings</button>
                            <button type="reset" class="btn btn-danger">Reset</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
        <?php $this->load->view('includes/right_bar') ?>
    </div>
</section>
<script>
    function delImage(gender, img_name) {
        $.ajax({
            url: BASE_URL + 'dashboard/deImage',
            type: 'POST',
            dataType: "json",
            data: {
                'gender': gender,
                'image': img_name
            },
            async: false,
            success: function (data) {
                $("#p_img").attr("src", data.img);
                $("#pro_img").attr("src", data.img);
            }
        });
    }
</script>