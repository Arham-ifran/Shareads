
<div class="breadcrumbs ace-save-state" id="breadcrumbs">


    <ul class="breadcrumb">
        <li>
            <i class="ace-icon fa fa-home home-icon"></i>
            <a href="<?php echo base_url('admin/dashboard') ?>">Home</a>
        </li>

        <li>
            <a href="<?php echo base_url('admin/payment_integration') ?>">Payment Method</a>
        </li>
        <li class="active">Add Payment Method</li>
    </ul><!-- /.breadcrumb -->


</div>


<div class="page-header">
    <h1>
        Payment Method Integration

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


        <form id="formPayment" name="formPayment" action="<?php echo base_url('admin/payment_integration/add') ?>" class="form-horizontal" role="form" method="post"  accept-charset="utf-8" >

            <input type="hidden" name="action" id="action" value="<?php echo $action; ?>">
            <input type="hidden"  id="id" name="id" value="<?php echo $row['id']; ?>"  >

            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Production Type *</label>
                <div class="col-xs-12 col-sm-9">
                    <div>
                        <label class="blue">
                            <input type="radio" id="integration_type" class="ace" <?php echo ( isset($row) && $row['integration_type'] == 1) ? 'checked' : '' ?> value="1" name="integration_type" required>
                            <span class="lbl">&nbsp;Live URL</span>
                        </label>
                    </div>
                    <div>
                        <label class="blue">
                            <input type="radio" class="ace" <?php echo ( isset($row) && $row['integration_type'] == 0) ? 'checked' : '' ?> id="integration_type" value="0" name="integration_type" required>
                            <span class="lbl">&nbsp;Sandbox URL</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="space-2"></div>


            <h3 class="header smaller lighter blue">PayPal Integration</h3>
            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">API Username *</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <input  type="text" class="col-xs-12 col-sm-5" id="api_username" name="api_username"  placeholder="API Username" value="<?php echo $row['api_username']; ?>" required>
                    </div>
                </div>
            </div>
            <div class="space-2"></div>

            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">API Password *</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <input type="text" class="col-xs-12 col-sm-5"  id="api_password" name="api_password"  placeholder="API Password" value="<?php echo $row['api_password'] ?>" required></div>
                </div>
            </div>
            <div class="space-2"></div>

            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">API Signature *</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                    <input type="text" class="col-xs-12 col-sm-5" id="api_signature" name="api_signature"  placeholder="API Signature" value="<?php echo $row['api_signature'] ?>" required></div>
                </div>
            </div>
            <div class="space-2"></div>

            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Application ID *</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                    <input type="text" class="col-xs-12 col-sm-5" id="application_id" name="application_id"  placeholder="Application ID" value="<?php echo $row['application_id'] ?>" required></div>
                </div>
            </div>
            <div class="space-2"></div>

            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Live PayPal URL *</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                    <input type="url" class="col-xs-12 col-sm-5" id="live_url" name="live_url"  placeholder="Live URL" value="<?php echo $row['live_url'] ?>" required></div>
                </div>
            </div>
            <div class="space-2"></div>

            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Sandbox PayPal URL *</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                    <input type="url" class="col-xs-12 col-sm-5" id="sandbox_url" name="sandbox_url"  placeholder="Sandbox URL" value="<?php echo $row['sandbox_url'] ?>" required></div>
                </div>
            </div>
            <div class="space-2"></div>

            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">PayPal ID (Business Email ID)*</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                    <input type="email" class="col-xs-12 col-sm-5" id="paypal_id" name="paypal_id"  placeholder="PayPal Business Email ID" value="<?php echo $row['paypal_id'] ?>" required></div>
                </div>
            </div>
            <div class="space-2"></div>



            <div class="space-2"></div>
            <div class="clearfix form-actions">
                <div class="col-md-offset-3 col-md-9">
                    <button id="submit_btn" class="btn btn-info" type="submit">
                        <i class="ace-icon fa fa-check bigger-110"></i>
                        Submit
                    </button>

                    &nbsp; &nbsp; &nbsp;
                    <button class="btn" type="reset" onclick="clear_form_elements('#formPayment');">
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
        $('#formPayment').validate({
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