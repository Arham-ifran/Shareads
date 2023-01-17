<div class="breadcrumbs ace-save-state" id="breadcrumbs">


    <ul class="breadcrumb">
        <li>
            <i class="ace-icon fa fa-home home-icon"></i>
            <a href="<?php echo base_url('admin/dashboard') ?>">Home</a>
        </li>

        <li>
            <a href="<?php echo base_url('admin/feedback') ?>">Feedback</a>
        </li>
        <li class="active">Reply Feedback</li>
    </ul><!-- /.breadcrumb -->


</div>

<div class="page-header">
    <h1>
        Feedback

    </h1>
</div><!-- /.page-header -->



<div class="row">
    <div class="col-xs-12">
        <form class="form-horizontal" role="form" method="post" accept-charset="utf-8">
            <div class="form-group">
                <label class="control-label col-xs-12  col-sm-3 no-padding-right">
                    <h4>Contact us Message Status:</h4>
                </label>
                <div class="col-xs-12 col-sm-4 ">

                            <?php
                            if (isset($row) && $row['status'] == 1) {
                                echo '<h4><strong>Provided</strong></h4>';
                            } else {
                                echo '<h4><strong>Pending</strong></h4>';
                            }
                            ?>

                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="form-group">
                <label class="control-label col-xs-12  col-sm-1 no-padding-right">Name:</label>
                <div class="col-xs-12 col-sm-4 ">

                            <?php echo $row['name']; ?>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="form-group">
                <label class="control-label col-xs-12  col-sm-1 no-padding-right">Email:</label>
                <div class="col-xs-12 col-sm-4 ">

                            <?php echo $row['email']; ?>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="form-group">
                <label class="control-label col-xs-12  col-sm-1 no-padding-right">Phone:</label>
                <div class="col-xs-12 col-sm-4 ">


                            <?php echo $row['phone']; ?>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="form-group">
                <label class="control-label col-xs-12  col-sm-1 no-padding-right">Subject:</label>
                <div class="col-xs-12 col-sm-4 ">

                            <?php echo $row['subject']; ?>
            </div>
            <div class="clearfix"></div>
            <div class="form-group">
                <label class="control-label col-xs-12  col-sm-1 no-padding-right">Message:</label>
                <div class="col-xs-12 col-sm-9 ">

                            <?php echo $row['comments']; ?>
                </div>

            </div>
        </form>

    </div>
</div>
<div class="hr hr-18 dotted hr-double"></div>
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
        <form id="formReplyFeedback" name="formReplyFeedback" action="<?php echo base_url('admin/feedback/send'); ?>" class="form-horizontal" role="form" method="post" accept-charset="utf-8">
            <input type="hidden" name="action" id="action" value="<?php echo $action; ?>">

            <input type="hidden"  id="feedId" name="feedId" value="<?php echo $row['feedId']; ?>"  >

            <input type="hidden"  id="userName" name="userName" value="<?php echo $row['name']; ?>"  >

            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">To *</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <input type="text" class="col-xs-12 col-sm-5" readonly id="to_email" name="to_email"  placeholder="To email address" value="<?php echo $row['email']; ?>" required></div>
                </div>
            </div>
            <div class="space-2"></div>

            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">From *</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <input type="text" class="col-xs-12 col-sm-5"  id="from_email" name="from_email"  placeholder="From email address" value="<?php echo NO_REPLY_EMAIL ?>" required>
                    </div></div>
            </div>
            <div class="space-2"></div>

            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Subject *</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <input type="text" class="col-xs-12 col-sm-5"  id="subject" name="subject"  placeholder="Subject" value="RE: <?php echo $row['subject']; ?>" required></div>
                </div>
            </div>
            <div class="space-2"></div>

            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Message *</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <textarea class="col-xs-12 col-sm-5" id="message" name="message"  placeholder="Message" required></textarea></div>
                </div>
            </div>
            <div class="space-2"></div>

            <div class="space-2"></div>
            <div class="clearfix form-actions">
                <div class="col-md-offset-3 col-md-9">
                    <button id="submit_btn" class="btn btn-info" type="submit">
                        <i class="ace-icon fa fa-check bigger-110"></i>
                        Send Email
                    </button>

                    &nbsp; &nbsp; &nbsp;
                    <button class="btn" type="reset" onclick="clear_form_elements('#formReplyFeedback');">
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
        $('#formReplyFeedback').validate({
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
