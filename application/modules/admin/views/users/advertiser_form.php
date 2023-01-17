<div class="breadcrumbs ace-save-state" id="breadcrumbs">


    <ul class="breadcrumb">
        <li>
            <i class="ace-icon fa fa-home home-icon"></i>
            <a href="<?php echo base_url('admin/dashboard') ?>">Home</a>
        </li>

        <li>
            <a href="<?php echo base_url('admin/users') ?>">Users</a>
        </li>
        <li class="active">Add Advertiser</li>
    </ul><!-- /.breadcrumb -->


</div>

<div class="page-header">
    <h1>
        Advertiser

    </h1>
</div><!-- /.page-header -->



<div class="row">
    <div class="col-xs-12">

        <?php
        if ($this->session->flashdata('success_message')) {
            echo '<div class="alert alert-success alertMessage">' . $this->session->flashdata('success_message') . '</div>';
        };
        ?>
        <div class="clearfix"></div>
        <!-- Notification -->
        <?php
        if ($this->session->flashdata('error_message')) {
            echo '<div class="alert alert-danger">' . $this->session->flashdata('error_message') . '</div>';
        };
        ?>
        <div class="clearfix"></div>
        <!-- /Notification -->

        <div class="space-8"></div>
        <div class="space-8"></div>
        <form id="users_form" name="users_form" action="<?php echo base_url('admin/users/add_advertiser') ?>" class="form-horizontal" role="form" method="post"  accept-charset="utf-8" enctype="multipart/form-data">

            <input type="hidden" name="action" id="action" value="<?php echo $action; ?>">

            <input type="hidden"  id="user_id" name="user_id" value="<?php echo $row['user_id']; ?>"  >


            <script>$("#account_type").val(<?php echo $row['account_type'] ?>);</script>
            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">First Name *</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <input type="text" minlength="2"  class="col-xs-12 col-sm-5" id="first_name" name="first_name"  placeholder="First Name" value="<?php echo $row['first_name']; ?>" required>
                    </div>
                </div>
            </div>
            <div class="space-2"></div>
            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Last Name *</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <input type="text" minlength="2"  class="col-xs-12 col-sm-5" id="last_name" name="last_name" placeholder="Last Name" value="<?php echo $row['last_name'] ?>" required />
                    </div></div>
            </div>
            <div class="space-2"></div>

<?php /*
            <div class="space-2"></div>
            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Paypal Email</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">

                        <input type="email" class="col-xs-12 col-sm-5" id="paypal_email" name="paypal_email" value="<?php echo $row['paypal_email'] ?>"  autocomplete="off"  placeholder="Paypal Email"/>
                        <br><br>
                        <label class="help-block" id="paypal_error" style="display: none;"></label>
                    </div>
                </div>
            </div>
*/ ?>

                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right">About Me</label>
                    <div class="col-xs-12 col-sm-9">
                        <div class="clearfix">
                            <textarea id="about_me" name="about_me" placeholder="About Me (500 characters max)" class="col-xs-12 col-sm-5 limited" maxlength="500" rows="3" ><?php echo $row['about_me'] ?></textarea>
                        </div>
                    </div>
                </div>

            <div class="space-2"></div>
            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Email *</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">

                        <input type="email" class="col-xs-12 col-sm-5" id="email" name="email" value="<?php echo $row['email'] ?>"  placeholder="Email" autocomplete="off"  onchange="checkUsersEmail();" required />
                        <br><br>
                        <label class="help-block" id="emailExist_error" style="display: none;"></label>
                    </div>
                </div>
            </div>

            <div class="space-2"></div>

            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Password <?php echo (!isset($row)) ? '*' : '*' ?></label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">

                        <input type="password" minlength="6"  class="col-xs-12 col-sm-5 ckeditor" id="password" name="password"  placeholder="Password" <?php echo (!isset($row)) ? 'required' : 'required' ?>  autocomplete="off" />

                    </div>
                </div>
            </div>

            <div class="space-2"></div>

            <h3 class="header smaller lighter blue">Phone No & Location</h3>

            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Phone</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <input  type="text"  class="col-xs-12 col-sm-5" id="phone" name="phone"  placeholder="Phone No" value="<?php echo $row['phone']; ?>">

                    </div>
                </div>
            </div>
            <div class="space-2"></div>

            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Fax</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <input  type="text"  class="col-xs-12 col-sm-5" id="fax" name="fax"  placeholder="Fax No" value="<?php echo $row['fax']; ?>">

                    </div>
                </div>
            </div>
            <div class="space-2"></div>


            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">City</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <input  type="text"  class="col-xs-12 col-sm-5" id="city" name="city"  placeholder="City" value="<?php echo $row['city']; ?>">

                    </div>
                </div>
            </div>
            <div class="space-2"></div>

            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">State / Province</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <input  type="text"  class="col-xs-12 col-sm-5" id="state" name="state"  placeholder="State / Province" value="<?php echo $row['state']; ?>">

                    </div>
                </div>
            </div>
            <div class="space-2"></div>

            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Address</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <input  type="text"  class="col-xs-12 col-sm-5" id="address" name="address"  placeholder="Address" value="<?php echo $row['address']; ?>">

                    </div>
                </div>
            </div>
            <div class="space-2"></div>

            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Additional Address</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <input  type="text"  class="col-xs-12 col-sm-5" id="additional_address" name="additional_address"  placeholder="Additional Address" value="<?php echo $row['additional_address']; ?>">

                    </div>
                </div>
            </div>
            <div class="space-2"></div>


            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Zip Code</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <input  type="text"  class="col-xs-12 col-sm-5" id="zip_code" name="zip_code"  placeholder="Zip Code" value="<?php echo $row['zip_code']; ?>">

                    </div>
                </div>
            </div>
            <div class="space-2"></div>
            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Country</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <select id="country" name="country" class="col-xs-12 col-sm-5">
                            <option value="">Select Country</option>
                            <?php foreach ($countries as $country) { ?>
                                <option value="<?php echo $country['country'] ?>"><?php echo $country['country'] ?></option>
                            <?php } ?>

                        </select>
                        <script>$('#country').val('<?php echo $row['country'] ?>');</script>

                    </div>
                </div>
            </div>
            <div class="space-2"></div>



            <div class="space-2"></div>

            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="photo">Photo</label>

                <div  class="col-xs-10 col-sm-4">
                    <input type="file" id="photo" name="photo" accept="image/*">
                    <div class="space-2"></div>
                    <input type="hidden" name="old_photo"  id="old_photo" value="<?php echo $row['photo']; ?>">
                    <?php
                    if (isset($row)) {
                        if ($row['photo'] == '') {
                            $row['photo'] = 'abc.png';
                        }
                        echo '<img src="' . $this->common->check_image(base_url("uploads/users/small/" . $row['photo']), 'no_image.jpg') . '" width="50" height="50" />';
                    }
                    ?>


                </div>
            </div>


            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Hold Payment</label>
                <div class="col-xs-12 col-sm-9">
                    <div>
                        <label class="blue">
                            <input type="checkbox" id="hold_payment" class="ace" <?php echo ( isset($row) && $row['hold_payment'] == 1) ? 'checked' : '' ?> value="1" name="hold_payment">
                            <span class="lbl">&nbsp;Yes</span>
                        </label>
                    </div>

                </div>
            </div>
            <div class="space-2"></div>



            <?php
            if ($row['user_id'] <> 1) {
            ?>
            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Status</label>
                <div class="col-xs-12 col-sm-9">
                    <div>
                        <label class="blue">
                            <input type="radio" id="status" class="ace" <?php echo ((isset($row) && $row['status'] == 1) || isset($row) == false) ? 'checked' : '' ?> value="1" name="status" required>
                            <span class="lbl">&nbsp;Active</span>
                        </label>
                    </div>
                    <div>
                        <label class="blue">
                            <input type="radio" class="ace" <?php echo ( isset($row) && $row['status'] == 0) ? 'checked' : '' ?> id="status" value="0" name="status" required>
                            <span class="lbl">&nbsp;Inactive</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="space-2"></div>

            <?php }?>


            <div class="space-2"></div>
            <div class="clearfix form-actions">
                <div class="col-md-offset-3 col-md-9">
                    <button id="submit_btn" class="btn btn-info" type="submit">
                        <i class="ace-icon fa fa-check bigger-110"></i>
                        Submit
                    </button>

                    &nbsp; &nbsp; &nbsp;
                    <button class="btn" type="reset" onclick="clear_form_elements('#users_form');">
                        <i class="ace-icon fa fa-undo bigger-110"></i>
                        Reset
                    </button>
                </div>
            </div>

        </form>

    </div>
</div>

<script>
    $(function () {
        
        $('#users_form').validate({
            errorElement: 'div',
            errorClass: 'help-block',
            focusInvalid: false,
            highlight: function (e) {
                $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
            },
            success: function (e) {
                $(e).closest('.form-group').removeClass('has-error');
                $(e).remove();
            },
            errorPlacement: function (error, element) {
                if (element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
                    var controls = element.closest('div[class*="col-"]');
                    if (controls.find(':checkbox,:radio').length > 1)
                        controls.append(error);
                    else
                        error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
                }
                else if (element.is('.select2')) {
                    error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
                }
                else if (element.is('.chosen-select')) {
                    error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
                }
                else
                    error.insertAfter(element.parent());
            },
            invalidHandler: function (form) {
            }
        });
    });

</script>