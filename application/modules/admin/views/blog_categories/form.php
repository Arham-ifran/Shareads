
<div class="breadcrumbs ace-save-state" id="breadcrumbs">
   >

    <ul class="breadcrumb">
        <li>
            <i class="ace-icon fa fa-home home-icon"></i>
            <a href="<?php echo base_url('admin/dashboard') ?>">Home</a>
        </li>

        <li>
            <a href="<?php echo base_url('admin/blog_categories') ?>">Blog Categories</a>
        </li>
        <li class="active">Add category</li>
    </ul><!-- /.breadcrumb -->


</div>

<div class="page-header">
    <h1>
         Blog Categories

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

        <form id="category_form" name="category_form" action="<?php echo base_url('admin/blog_categories/add') ?>" class="form-horizontal" role="form" method="post"  accept-charset="utf-8" enctype="multipart/form-data">

            <input type="hidden" name="action" id="action" value="<?php echo $action; ?>">
            <input type="hidden"  id="id" name="id" value="<?php echo $row['id']; ?>"  >




            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Category Name *</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <input  type="text" minlength="2" class="col-xs-12 col-sm-5" id="category" name="category"  placeholder="Category Name" value="<?php echo $row['category']; ?>" required>
                    </div>
                </div>
            </div>

            <div class="space-2"></div>





            <div class="space-2"></div>

            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Status *</label>
                <div class="col-xs-12 col-sm-9">
                    <div>
                        <label class="blue">
                            <input type="radio" id="status" class="ace" <?php echo ( isset($row) && $row['status'] == 1) ? 'checked' : '' ?> value="1" name="status" required>
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



            <div class="space-2"></div>
            <div class="clearfix form-actions">
                <div class="col-md-offset-3 col-md-9">
                    <button id="submit_btn" class="btn btn-info" type="submit">
                        <i class="ace-icon fa fa-check bigger-110"></i>
                        Submit
                    </button>

                    &nbsp; &nbsp; &nbsp;
                    <button class="btn" type="reset" onclick="clear_form_elements('#category_form');">
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
        $('#category_form').validate({
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