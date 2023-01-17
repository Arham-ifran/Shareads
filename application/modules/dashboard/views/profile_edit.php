<?php $this->load->view('includes/profile_info'); ?>

<section class="container">
    <div class="heading_links clearfix"><h3 class="main_heading">Settings</h3></div>
    <div class="row">
        <div class="col-md-6 col-xs-9"><h4>Edit Information</h4></div>
    </div>

    <div class="row">

        <div class="col-md-9 col-sm-12">
            <form id="users_form" name="users_form" action="<?php echo base_url('settings/edit') ?>" class="form-horizontal" role="form" method="post"  accept-charset="utf-8" enctype="multipart/form-data">
                <fieldset>
                    <!-- Form Name -->
                    <legend></legend>

                    <div class="form-group ">
                        <label class="col-sm-3">First Name *</label>
                        <div class="col-md-6">
                            <div class="clearfix">
                                <input minlength="2" type="text" class="form-control input-md" id="first_name" name="first_name"  placeholder="First Name" value="<?php echo $userdata['first_name']; ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="space-2"></div>

                    <div class="form-group">
                        <label class="col-sm-3">Last Name *</label>
                        <div class="col-md-6">
                            <div class="clearfix">
                                <input minlength="2" type="text" class="form-control input-md" id="last_name" name="last_name"  placeholder="Last Name" value="<?php echo $userdata['last_name']; ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="space-2"></div>


                    <div class="form-group">
                        <label class="col-sm-3">About Me</label>
                        <div class="col-md-6">
                            <div class="clearfix">
                                <textarea id="about_me" name="about_me" placeholder="About Me (500 characters max)" class="form-control input-md limited" maxlength="500" rows="3" ><?php echo $userdata['about_me'] ?></textarea>
                            </div>
                        </div>
                    </div>



                    <div class="space-2"></div>

                    <div class="form-group ">
                        <label class="col-sm-3">Gender</label>
                        <div class="col-md-6">

                            <label class="col-sm-3">
                                <input type="radio" id="gender" class="ace" <?php echo ( isset($userdata) && $userdata['gender'] == 'male') ? 'checked' : '' ?> value="male" name="gender" >
                                <span class="lbl">&nbsp;Male</span>
                            </label>

                            <label class="col-sm-3">
                                <input type="radio" class="ace" <?php echo ( isset($userdata) && $userdata['gender'] == 'female') ? 'checked' : '' ?> <?php echo (!isset($userdata)) ? 'checked' : '' ?> id="gender" value="female" name="gender" >
                                <span class="lbl">&nbsp;Female</span>
                            </label>

                        </div>
                    </div>
                    <div class="space-2"></div>

                    <div class="form-group">
                        <label class="col-sm-3">Email *</label>
                        <div class="col-md-6">
                            <div class="clearfix">
                                <?php
                                if ($userdata['email'] == '')
                                {
                                    ?>
                                    <input type="email" class="form-control input-md" id="email" name="email"  placeholder="Email" value="<?php echo $userdata['email']; ?>" onchange="checkUsersEmail();" autocomplete="off" required>
                                    <?php
                                }
                                else
                                {
                                    echo $userdata['email'];
                                }
                                ?>
                            </div>
                            <label class="help-block" id="emailExist_error" style="display: none;"></label>
                        </div>
                    </div>

                    <div class="space-2"></div>


                    <h4 class="header smaller lighter blue">Phone No & Location</h4><br />

                    <div class="form-group">
                        <label class="col-sm-3">Phone</label>
                        <div class="col-md-6">
                            <div class="clearfix">
                                <input type="text" class="form-control input-md" id="phone" name="phone"  placeholder="Phone" value="<?php echo $userdata['phone']; ?>"></div>
                        </div>
                    </div>
                    <div class="space-2"></div>

                    <div class="form-group">
                        <label class="col-sm-3">Fax</label>
                        <div class="col-md-6">
                            <div class="clearfix">
                                <input type="text" class="form-control input-md" id="fax" name="fax"  placeholder="Fax" value="<?php echo $userdata['fax']; ?>"></div>
                        </div>
                    </div>
                    <div class="space-2"></div>

                    <div class="form-group">
                        <label class="col-sm-3">City</label>
                        <div class="col-md-6">
                            <div class="clearfix">
                                <input type="text" class="form-control input-md" id="city" name="city"  placeholder="City" value="<?php echo $userdata['city']; ?>"></div>
                        </div>
                    </div>
                    <div class="space-2"></div>

                    <div class="form-group">
                        <label class="col-sm-3">State / Province</label>
                        <div class="col-md-6">
                            <div class="clearfix">
                                <input type="text" class="form-control input-md" id="state" name="state"  placeholder="State / Province" value="<?php echo $userdata['state']; ?>"></div>
                        </div>
                    </div>
                    <div class="space-2"></div>


                    <div class="form-group ">
                        <label class="col-sm-3">Address</label>
                        <div class="col-md-6">
                            <div class="clearfix">
                                <input  type="text"  class="form-control input-md" id="address" name="address"  placeholder="Address" value="<?php echo $userdata['address']; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="space-2"></div>

                    <div class="form-group ">
                        <label class="col-sm-3">Additional Address</label>
                        <div class="col-md-6">
                            <div class="clearfix">
                                <input  type="text"  class="form-control input-md" id="additional_address" name="additional_address"  placeholder="Additional Address" value="<?php echo $userdata['additional_address']; ?>">

                            </div>
                        </div>
                    </div>
                    <div class="space-2"></div>
                    <div class="form-group">
                        <label class="col-sm-3">Zip Code</label>
                        <div class="col-md-6">
                            <div class="clearfix">
                                <input  type="text"  class="form-control input-md" id="zip_code" name="zip_code"  placeholder="Zip Code" value="<?php echo $userdata['zip_code']; ?>" >

                            </div>
                        </div>
                    </div>
                    <div class="space-2"></div>
                    <div class="form-group">
                        <label class="col-sm-3">Country</label>
                        <div class="col-md-6">
                            <div class="clearfix">
                                <select id="country" name="country" class="form-control input-md">
                                    <option value="">Select Country</option>
                                    <?php
                                    foreach ($countries as $country)
                                    {
                                        ?>
                                        <option value="<?php echo $country['country'] ?>"><?php echo $country['country'] ?></option>
                                    <?php } ?>

                                </select>
                                <script>$('#country').val('<?php echo $userdata['country'] ?>');</script>

                            </div>
                        </div>
                    </div>
                    <div class="space-2"></div>






                    <div class="form-group">
                        <label class="col-sm-3" for="photo">Photo</label>

                        <div  class="col-md-6">
                            <input type="file" id="photo" name="photo" accept="image/*">
                            <div class="space-2"></div>
                            <input type="hidden" name="old_photo"  id="old_photo" value="<?php echo $userdata['photo']; ?>">
                            <?php
                            if (isset($userdata))
                            {
                                if ($userdata['photo'] == '')
                                {
                                    $userdata['photo'] = 'abc.png';
                                }
                                echo '<img id="pro_img" src="' . $this->common->check_image(base_url("uploads/users/small/" . $userdata['photo']), 'no_image.jpg') . '" width="50" height="50" />';
                                if ($userdata['photo'] != '')
                                {
                                    ?>
                                    <br/> <a href="javascript:void(0)" onclick="delImage('<?php echo $userdata['gender']; ?>', '<?php echo $userdata['photo']; ?>')"><i class="fa fa-trash-o"></i></a>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div class="space-2"></div>
                    <h4 class="header smaller lighter blue">Payment Settings</h4><br />
                    <?php
                    if ($this->session->userdata('account_type') == 1)
                    {
                        ?>
                     <div class="space-2"></div>
                        <div class="form-group">
                            <label class="col-sm-3">Currency</label>
                            <div class="col-md-6">
                                <div class="clearfix">
                                    <select id="currency" class="form-control input-md" disabled>
                                        <option value="">Select Currency</option>
                                        <?php
                                        foreach (getCurrencies() as $currency)
                                        {
                                            echo '<option value="' . $currency['currency_id'] . '" ' . (($currency['currency_id'] == $row['currency']) ? "selected" : "" ) . ' >' . $currency['currency_symbol'] . '  ' . ucfirst($currency['currency']) . '</option>';
                                        }
                                        ?>

                                    </select>
                                    <script>$('#currency').val('<?php echo $userdata['currency'] ?>');</script>

                                </div>
                            </div>
                        </div>
                        <div class="space-2"></div>
                        <div class="form-group">
                            <label class="col-sm-3">Payment Type</label>
                            <div class="col-md-6">
                                <label class="col-sm-5">
                                    <input type="radio" id="payment_type" class="ace" <?php echo ( isset($userdata) && $userdata['payment_type'] == '1') ? 'checked' : '' ?> value="1" name="payment_type" >
                                    <span class="lbl">&nbsp;Paypal</span>
                                </label>
                                <label class="col-sm-5">
                                    <input type="radio" class="ace" <?php echo ( isset($userdata) && $userdata['payment_type'] == '2') ? 'checked' : '' ?> <?php echo (!isset($userdata)) ? 'checked' : '' ?> id="payment_type" value="2" name="payment_type" >
                                    <span class="lbl">&nbsp;Wire Transfer</span>
                                </label>
                            </div>
                        </div>
                        <div id="paypal" style="display: <?php echo ($userdata['payment_type'] == 1) ? 'block' : 'none' ?>;">
                            <div class="space-2"></div>
                            <div class="form-group ">
                                <label class="col-sm-3">PayPal First Name</label>
                                <div class="col-md-6">
                                    <div class="clearfix">
                                        <input  type="text"  class="form-control input-md" id="paypal_first_name" name="paypal_first_name"  placeholder="Paypal First Name" value="<?php echo $userdata['paypal_first_name']; ?>">

                                    </div>
                                </div>
                            </div>
                        <div class="space-2"></div>
                            <div class="form-group ">
                                <label class="col-sm-3">PayPal Last Name</label>
                                <div class="col-md-6">
                                    <div class="clearfix">
                                        <input  type="text"  class="form-control input-md" id="paypal_last_name" name="paypal_last_name"  placeholder="Paypal Last Name" value="<?php echo $userdata['paypal_last_name']; ?>">

                                    </div>
                                </div>
                            </div>
                            <div class="space-2"></div>

                            <div class="form-group">
                                <label class="col-sm-3">PayPal Email *</label>
                                <div class="col-md-6">
                                    <div class="clearfix">
                                        <input type="email" id="paypal_email" name="paypal_email" placeholder="PayPal Email" class="form-control" value="<?php echo $userdata['paypal_email'] ?>" />
                                        <label class="alert-danger" id="paypal_error" style="display: none;"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="space-2"></div>
                        <div id="wire_transfer" style="display: <?php echo ($userdata['payment_type'] == 2) ? 'block' : 'none' ?>;">
                            <div class="space-2"></div>
                            <div class="form-group ">
                                <label class="col-sm-3">Account holder name</label>
                                <div class="col-md-6">
                                    <div class="clearfix">
                                        <input  type="text"  class="form-control input-md" id="account_holder_name" name="account_holder_name"  placeholder="Account holder name" value="<?php echo $userdata['account_holder_name']; ?>"/>

                                    </div>
                                </div>
                            </div>
                            <div class="space-2"></div>
                            <div class="form-group ">
                                <label class="col-sm-3">Account number</label>
                                <div class="col-md-6">
                                    <div class="clearfix">
                                        <input  type="text"  class="form-control input-md" id="account_number" name="account_number"  placeholder="Account Number" value="<?php echo $userdata['account_number']; ?>" />

                                    </div>
                                </div>
                            </div>
                            <div class="space-2"></div>
                            <div class="form-group ">
                                <label class="col-sm-3">Iban code</label>
                                <div class="col-md-6">
                                    <div class="clearfix">
                                        <input  type="text"  class="form-control input-md" id="iban_code" name="iban_code"  placeholder="Iban code" maxlength="34" value="<?php echo $userdata['iban_code']; ?>" />

                                    </div>
                                </div>
                            </div>
                            <div class="space-2"></div>
                            <div class="form-group ">
                                <label class="col-sm-3">Swift code</label>
                                <div class="col-md-6">
                                    <div class="clearfix">
                                        <input  type="text"  class="form-control input-md" id="swift_code" name="swift_code"  placeholder="Swift code" maxlength="11" value="<?php echo $userdata['swift_code']; ?>"/>

                                    </div>
                                </div>
                            </div>
                            <div class="space-2"></div>
                            <div class="form-group ">
                                <label class="col-sm-3">SORT code</label>
                                <div class="col-md-6">
                                    <div class="clearfix">
                                        <input  type="text"  class="form-control input-md" id="sort_code" name="sort_code"  placeholder="SORT code" maxlength="11" value="<?php echo $userdata['sort_code']; ?>">

                                    </div>
                                </div>
                            </div>
                            <div class="space-2"></div>
                            <div class="form-group ">
                                <label class="col-sm-3">Bank name</label>
                                <div class="col-md-6">
                                    <div class="clearfix">
                                        <input  type="text"  class="form-control input-md" id="bank_name" name="bank_name"  placeholder="Bank name" value="<?php echo $userdata['bank_name']; ?>">

                                    </div>
                                </div>
                            </div><div class="space-2"></div>
                            <div class="form-group ">
                                <label class="col-sm-3">Bank address</label>
                                <div class="col-md-6">
                                    <div class="clearfix">
                                        <input  type="text"  class="form-control input-md" id="bank_address" name="bank_address"  placeholder="Bank Address" value="<?php echo $userdata['bank_address']; ?>">

                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <?php
                    if ($this->session->userdata('account_type') == 2)
                    {
                        
                        ?>
                        <div class="space-2"></div>
                            <div class="form-group ">
                                <label class="col-sm-3">PayPal First Name</label>
                                <div class="col-md-6">
                                    <div class="clearfix">
                                        <input  type="text"  class="form-control input-md" id="paypal_first_name" name="paypal_first_name"  placeholder="Paypal First Name" value="<?php echo $userdata['paypal_first_name']; ?>" required>

                                    </div>
                                </div>
                            </div>
                        <div class="space-2"></div>
                            <div class="form-group ">
                                <label class="col-sm-3">PayPal Last Name</label>
                                <div class="col-md-6">
                                    <div class="clearfix">
                                        <input  type="text"  class="form-control input-md" id="paypal_last_name" name="paypal_last_name"  placeholder="Paypal Last Name" value="<?php echo $userdata['paypal_last_name']; ?>" required>

                                    </div>
                                </div>
                            </div>
                        <div class="space-2"></div>

                        <div class="form-group">
                            <label class="col-sm-3">PayPal Email *</label>
                            <div class="col-md-6">
                                <div class="clearfix">
                                    <input type="email" id="paypal_email" name="paypal_email"  placeholder="PayPal Email" class="form-control" value="<?php echo $userdata['paypal_email'] ?>"  required/>
                                    <!--onchange="varfyPaypalEmail();"-->
                                    <label class="alert-danger" id="paypal_error" style="display: none;"></label>
                                </div>
                            </div>
                        </div>
                        <div class="space-2"></div>
                         <div class="form-group">
                            <label class="col-sm-3">Currency</label>
                            <div class="col-md-6">
                                <div class="clearfix">
                                    <select id="currency" class="form-control input-md" disabled>
                                        <option value="">Select Currency</option>
                                        <?php
                                        foreach (getCurrencies() as $currency)
                                        {
                                            echo '<option value="' . $currency['currency_id'] . '" ' . (($currency['currency_id'] == $row['currency']) ? "selected" : "" ) . ' >' . $currency['currency_symbol'] . '  ' . ucfirst($currency['currency']) . '</option>';
                                        }
                                        ?>

                                    </select>
                                    <script>$('#currency').val('<?php echo $userdata['currency'] ?>');</script>

                                </div>
                            </div>
                        </div>
                        <?php if (date('d') <= 10)
                        {
                            ?>
                            <div class="form-group ">
                                <label class="col-sm-3">Payment Schedule</label>
                                <div class="col-md-6">
                                    <label class="col-sm-5">
                                        <input type="radio" id="payment_schedule" class="ace" <?php echo ( isset($userdata) && $userdata['payment_schedule'] == '1') ? 'checked' : '' ?> value="1" name="payment_schedule" >
                                        <span class="lbl">&nbsp;Biweekly <small>(15 days)</small></span>
                                    </label>
                                    <label class="col-sm-3">
                                        <input type="radio" class="ace" <?php echo ( isset($userdata) && $userdata['payment_schedule'] == '2') ? 'checked' : '' ?> <?php echo (!isset($userdata)) ? 'checked' : '' ?> id="payment_schedule" value="2" name="payment_schedule" >
                                        <span class="lbl">&nbsp;Monthly</span>
                                    </label>
                                </div>
                            </div>
                         <div class="space-2"></div>
                       
    <?php } ?>

                       

<?php } ?>

                    <div class="space-2"></div>
                    <div class="space-2"></div>

                    <!-- Button (Double) -->
                    <div class="form-group">
                        <label class="col-sm-3"></label>
                        <div class="col-md-8">
                            <button type="submit" class="btn btn-success">Update Profile</button>
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
    $('input[name=payment_type]').change(function () {
        if (this.value == 2)
        {
            $('#paypal').hide();
            $('#wire_transfer').show();
        } else {
            $('#wire_transfer').hide();
            $('#paypal').show();
        }
    });
</script>