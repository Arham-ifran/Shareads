
<div class="page-header">
    <h1>
        Site Settings
        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            Main site settings
        </small>
    </h1>
</div><!-- /.page-header -->

<div class="row">
    <div class="col-xs-12">

        <?php
        if ($this->session->flashdata('success_message'))
        {
            echo '<div class="alert alert-success alertMessage">' . $this->session->flashdata('success_message') . '</div>';
        };
        ?>
        <div class="clearfix"></div>
        <!-- Notification -->
        <?php
        if ($this->session->flashdata('error_message'))
        {
            echo '<div class="alert alert-danger">' . $this->session->flashdata('error_message') . '</div>';
        };
        ?>
        <div class="clearfix"></div>
        <!-- /Notification -->

        <!-- PAGE CONTENT BEGINS -->
        <form class="form-horizontal" role="form" nam="setting_form" id="setting_form" method="post" action="<?php echo base_url('admin/site_settings') ?>" accept-charset="utf-8" enctype="multipart/form-data" >
            <input type="hidden" id="id" name="id" value="<?php echo isset($row) ? $row['id'] : 0 ?>"/>

            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="site_name">Site Name * </label>

                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <input type="text"  name="site_name" id="site_name" value="<?php echo $row['site_name']; ?>" placeholder="Site Name" class="col-xs-12 col-sm-5" required/></div>
                </div>
            </div>
            <div class="space-2"></div>

            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="site_title">Site Title * </label>

                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <input type="text"  id="site_title" name="site_title" value="<?php echo $row['site_title']; ?>" placeholder="Site Title" class="col-xs-12 col-sm-5" required/></div>
                </div>
            </div>
            <div class="space-2"></div>

            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="site_keywords">Site Keywords * </label>

                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">

                        <textarea  class="col-xs-12 col-sm-5 limited" id="site_keywords" name="site_keywords" placeholder="Site Keywords" style="height: 150px;" maxlength="1000" required><?php echo $row['site_keywords']; ?></textarea>
                    </div>
                </div>
            </div>
            <div class="space-2"></div>

            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="site_description">Site Description * </label>

                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <textarea  class="col-xs-12 col-sm-5 limited" id="site_description" name="site_description" placeholder="Site Description" style="height: 75px;"  maxlength="1000" required><?php echo $row['site_description']; ?></textarea>
                    </div>
                </div>
            </div>
            <div class="space-2"></div>


            <h3 class="header smaller lighter blue"> Signup Page Tooltip Text</h3>


            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="p_text">Publisher Text * </label>

                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <textarea  class="col-xs-12 col-sm-5 limited" id="p_text" name="p_text" placeholder="Text" style="height: 100px;"  maxlength="1000" required><?php echo $row['p_text']; ?></textarea>
                    </div>
                </div>
            </div>
            <div class="space-2"></div>
            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="a_text">Advertiser Text * </label>

                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <textarea  class="col-xs-12 col-sm-5 limited" id="a_text" name="a_text" placeholder="Text" style="height: 100px;"  maxlength="1000" required><?php echo $row['a_text']; ?></textarea>
                    </div>
                </div>
            </div>
            <div class="space-2"></div>


            <!--            <div class="form-group">
                            <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="commission_rate">Commission Rate </label>
            
                            <div class="col-xs-12 col-sm-9">
                                <input type="number" id="commission_rate" name="commission_rate" value="<?php echo $row['commission_rate']; ?>" placeholder="Commission Rate" class="col-xs-12 col-sm-5"/>
                            </div>
                        </div>
                        <div class="space-2"></div>-->

            <h3 class="header smaller lighter blue"> Address Details</h3>


            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="admin_email">Admin Email *</label>

                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <input type="email" id="admin_email" name="admin_email" value="<?php echo $row['admin_email']; ?>" placeholder="Admin Email" class="col-xs-12 col-sm-5" required /></div>
                </div>
            </div>
            <div class="space-2"></div>

            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="admin_phone">Admin Phone No</label>

                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <input type="text" id="admin_phone" name="admin_phone" value="<?php echo $row['admin_phone']; ?>" placeholder="Admin Phone No" class="col-xs-12 col-sm-5" /></div>
                </div>
            </div>
            <div class="space-2"></div>


            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="admin_mobile">Admin Mobile No</label>

                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <input type="text" id="admin_mobile" name="admin_mobile" value="<?php echo $row['admin_mobile']; ?>" placeholder="Admin Mobile No" class="col-xs-12 col-sm-5" /></div>
                </div>
            </div>
            <div class="space-2"></div>


            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="admin_address">Admin Address  *</label>

                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <textarea  class="col-xs-12 col-sm-5" id="admin_address" name="admin_address" placeholder="Admin Address" style="height: 75px;" required><?php echo $row['admin_address']; ?></textarea></div>
                </div>
            </div>
            <div class="space-2"></div>




            <h3 class="header smaller lighter blue"> Social Details</h3>

            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Facebook URL </label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <input  type="url" class="col-xs-12 col-sm-5" id="facebook" name="facebook"  placeholder="Facebook URL" value="<?php echo $row['facebook']; ?>" />
                    </div>
                </div>
            </div>
            <div class="space-2"></div>
            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Twitter URL </label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <input  type="url" class="col-xs-12 col-sm-5" id="twitter" name="twitter"  placeholder="Twitter URL" value="<?php echo $row['twitter']; ?>" />
                    </div>
                </div>
            </div>
            <div class="space-2"></div>
            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Gplus URL </label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <input  type="url" class="col-xs-12 col-sm-5" id="google" name="google"  placeholder="Gplus URL" value="<?php echo $row['google']; ?>" />
                    </div>
                </div>
            </div>

            <div class="space-2"></div>
            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">LinkedIn URL </label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <input  type="url" class="col-xs-12 col-sm-5" id="linkedin" name="linkedin"  placeholder="LinkedIn URL" value="<?php echo $row['linkedin']; ?>" />
                    </div>
                </div>
            </div>
            <div class="space-2"></div>
            <h3 class="header smaller lighter blue"><?php echo SITE_NAME; ?> Product Commission (%)</h3>
            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="SHAREADS_COMMISION">Commision for each product (in %)</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <input type="text" id="SHAREADS_COMMISION" name="SHAREADS_COMMISION" value="<?php echo $row['SHAREADS_COMMISION']; ?>" placeholder="<?php echo SITE_NAME; ?> commssion in percentage" min="0" max="100" class="col-xs-12 col-sm-5" required /></div>
                </div>
            </div>
            <div class="space-2"></div>
            <h3 class="header smaller lighter blue">Currency Settings</h3>
            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Currency option</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <select class="col-xs-12 col-sm-5" id="currency" name="currency" required>
                            <option value="">Select Currency</option>
                            <?php
                            foreach (getCurrencies() as $currency)
                            {
                                echo '<option value="' . $currency['currency_id'] . '" ' . (($currency['currency_id'] == $row['currency']) ? "selected" : "" ) . ' >' . $currency['currency_symbol'] . '  ' . ucfirst($currency['currency']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="space-2"></div>



            <h3 class="header smaller lighter blue">Pay Commission After</h3>

            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Days *</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <input  type="number" class="col-xs-12 col-sm-5" id="no_of_days" name="no_of_days"  placeholder="No of Days" value="<?php echo $row['no_of_days']; ?>" required/>
                    </div>
                </div>
            </div>
            <div class="space-2"></div>

            <div class="space-2"></div>



            <h3 class="header smaller lighter blue">Minimum withdrawal Commission</h3>

            <?php
            $row['LIMIT_WITHDRAW'] = unserialize($row['LIMIT_WITHDRAW']);
            foreach (getCurrencies() as $key => $value)
            {
                ?>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right">Minimum withdrawal Limit in (<?php echo $value['currency_name'] . ')*'; ?></label>
                    <div class="col-xs-12 col-sm-9">
                        <div class="clearfix">
                            <input  type="number" class="col-xs-12 col-sm-5" name="LIMIT_WITHDRAW[<?php echo $value['currency_id']; ?>]" id="LIMIT_WITHDRAW<?php echo $key; ?>"  placeholder="Minimum Limit Withdraw" value="<?php echo $row['LIMIT_WITHDRAW'][$value['currency_id']]; ?>" required/>
                            <?php echo '<strong style="padding: 10px;font-size: 20px;">' . $value['currency_symbol'] . '</strong>'; ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <div class="space-2"></div>



            <h3 class="header smaller lighter blue">Mail Chimp Details</h3>

            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Mail Chimp ID *</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <input  type="text" class="col-xs-12 col-sm-5" id="mail_chimp_id" name="mail_chimp_id"  placeholder="Mail Chimp ID" value="<?php echo $row['mail_chimp_id']; ?>" required/>
                    </div>
                </div>
            </div>
            <div class="space-2"></div>
            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Mail Chimp Key *</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <input  type="text" class="col-xs-12 col-sm-5" id="mail_chimp_key" name="mail_chimp_key"  placeholder="Mail Chimp Key" value="<?php echo $row['mail_chimp_key']; ?>" required/>
                    </div>
                </div>
            </div>
            <div class="space-2"></div>


            <h3 class="header smaller lighter blue">Google Analytics  / Other Tracking Codes</h3>
            <div class="space-2"></div>


            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="google_analytics_code">Google Analytics Code</label>

                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <textarea  class="col-xs-12 col-sm-9" id="google_analytics_code" name="google_analytics_code" placeholder="Google Analytics Code" style="height: 150px;" ><?php echo $row['google_analytics_code']; ?></textarea></div>
                </div>
            </div>
            <div class="space-2"></div>

            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="other_codes">Other Tracking Codes</label>

                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <textarea  class="col-xs-12 col-sm-9" id="other_codes" name="other_codes" placeholder="Other Tracking Codes" style="height: 150px;" ><?php echo $row['other_codes']; ?></textarea></div>
                    <label class="label label-important">if multiple tracking codes/scripts are available then paste in a new line of textarea.</label>
                </div>
            </div>

            <div class="space-2"></div>




            <h3 class="header smaller lighter blue">Support Chat</h3>
            <div class="space-2"></div>


            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="support_chat_code">Support Chat Code</label>

                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <textarea  class="col-xs-12 col-sm-9" id="support_chat_code" name="support_chat_code" placeholder="Support Chat Code" style="height: 150px;" ><?php echo $row['support_chat_code']; ?></textarea></div>
                </div>
            </div>
            <div class="space-2"></div>



            <div class="space-2"></div>



            <div class="space-2"></div>
            <div class="clearfix form-actions">
                <div class="col-md-offset-3 col-md-9">
                    <button id="submit_btn" class="btn btn-info" type="submit">
                        <i class="ace-icon fa fa-check bigger-110"></i>
                        Submit
                    </button>

                    &nbsp; &nbsp; &nbsp;
                    <button class="btn" type="reset" onclick="clear_form_elements('#setting_form');">
                        <i class="ace-icon fa fa-undo bigger-110"></i>
                        Reset
                    </button>
                </div>
            </div>




        </form>



        <!-- PAGE CONTENT ENDS -->
    </div><!-- /.col -->
</div><!-- /.row -->


<script>
    $(function () {

        $('#SHAREADS_COMMISION').keyup(function (e) {
            var num = $(this).val();
            if (e.which != 8) {
                num = sortNumber(num);
                if (isNaN(num) || num < 0 || num > 100) {
                    alert("Only Enter Number Between 0-100");
                    $(this).val(sortNumber(num.substr(0, num.length - 1)));
                } else
                    $(this).val(sortNumber(num));
            } else {
                if (num < 2)
                    $(this).val("");
                else
                    $(this).val(sortNumber(num.substr(0, num.length - 1)));
            }
        });
        function sortNumber(n) {
            var newNumber = "";
            for (var i = 0; i < n.length; i++)
                if (n[i] != "")
                    newNumber += n[i];
            return newNumber;
        }


        $('#setting_form').validate({
            errorElement: 'div',
            errorClass: 'help-block',
            focusInvalid: true,
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
                } else if (element.is('.select2')) {
                    error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
                } else if (element.is('.chosen-select')) {
                    error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
                } else
                    error.insertAfter(element.parent());
            },
            invalidHandler: function (form) {
            }
        });
    });
</script>