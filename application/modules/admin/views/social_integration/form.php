
<div class="breadcrumbs ace-save-state" id="breadcrumbs">


    <ul class="breadcrumb">
        <li>
            <i class="ace-icon fa fa-home home-icon"></i>
            <a href="<?php echo base_url('admin/dashboard') ?>">Home</a>
        </li>

        <li>
            <a href="<?php echo base_url('admin/social_integration') ?>">Social Integration</a>
        </li>
        <li class="active">Add Social Keys</li>
    </ul><!-- /.breadcrumb -->


</div>


<div class="page-header">
    <h1>
        Social Integration

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


        <form id="formsocial" name="formsocial" action="<?php echo base_url('admin/social_integration/add') ?>" class="form-horizontal" role="form" method="post"  accept-charset="utf-8" >

            <input type="hidden" name="action" id="action" value="<?php echo $action; ?>">
            <input type="hidden"  id="id" name="id" value="<?php echo $row['id']; ?>"  >




            <h3 class="header smaller lighter blue">Facebook</h3>
            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Facebook AppId *</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <input  type="text" class="col-xs-12 col-sm-5" id="facebook_appId" name="facebook_appId"  placeholder="Facebook AppId" value="<?php echo $row['facebook_appId']; ?>" required>
                    </div>
                </div>
            </div>
            <div class="space-2"></div>

            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Facebook Secret *</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <input type="text" class="col-xs-12 col-sm-5"  id="facebook_secret" name="facebook_secret"  placeholder="Facebook Secret" value="<?php echo $row['facebook_secret'] ?>" required></div>
                </div>
            </div>
            <div class="space-2"></div>

            <h3 class="header smaller lighter blue">Twitter</h3>

            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Twitter Token *</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                    <input type="text" class="col-xs-12 col-sm-5" id="twitter_consumer_token" name="twitter_consumer_token"  placeholder="Twitter Token" value="<?php echo $row['twitter_consumer_token'] ?>" required></div>
                </div>
            </div>
            <div class="space-2"></div>

            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Twitter Secret *</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                    <input type="text" class="col-xs-12 col-sm-5" id="twitter_consumer_secret" name="twitter_consumer_secret"  placeholder="Twitter Secret" value="<?php echo $row['twitter_consumer_secret'] ?>" required></div>
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
                    <button class="btn" type="reset" onclick="clear_form_elements('#formsocial');">
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
        $('#formsocial').validate({
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