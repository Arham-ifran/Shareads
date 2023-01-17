<div class="breadcrumbs ace-save-state" id="breadcrumbs">


    <ul class="breadcrumb">
        <li>
            <i class="ace-icon fa fa-home home-icon"></i>
            <a href="<?php echo base_url('admin/dashboard') ?>">Home</a>
        </li>

        <li>
            <a href="<?php echo base_url('admin/categories') ?>">Categories</a>
        </li>
        <li class="active">Add Category</li>
    </ul><!-- /.breadcrumb -->


</div>

<div class="page-header">
    <h1>
      Categories

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
        <form id="categories_form" name="categories_form" action="<?php echo base_url('admin/categories/add') ?>" class="form-horizontal" role="form" method="post"  accept-charset="utf-8" enctype="multipart/form-data">

            <input type="hidden" name="category_id" id="category_id"  value="<?php echo $row['category_id']; ?>" />
            <input type="hidden" name="action" id="action" value="<?php echo $action; ?>" />

            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Category Name *</label>

                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <input onchange="creatCategorySlug()" minlength="2" type="text" class="col-xs-12 col-sm-5" id="catTitle" name="category_name"  placeholder="" value="<?php echo $row['category_name']; ?>" required>

                        <div  id="CatExist_error" class="help-block" style="display: none;">Opps! Already exists. Please try another.</div>

                    </div>
                </div>
            </div>
            <div class="space-2"></div>

            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Slug</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <input type="text" class="col-xs-12 col-sm-5" id="category_slug" name="category_slug"  readonly placeholder="Slug" value="<?php echo $row['category_slug'] ?>">
                        <input type="hidden"  id="old_category_slug" value="<?php echo $row['category_slug']; ?>">
                    </div></div>
            </div>
            <div class="space-2"></div>

            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Select Parent </label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <select id="parent_id" name="parent_id" class="col-xs-12 col-sm-5">
                            <option value="0">None</option>
                            <?php echo $this->categories_model->getCategories(0, 0, $row['parent_id']); ?>
                        </select></div>
                </div>
            </div>
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

            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Meta Keywords </label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <?php
                        $inputArray = array(
                            'name' => 'meta_keywords',
                            'id' => 'meta_keywords',
                            'rows' => 3,
                            'value' => $row['meta_keywords'],
                            'class' => 'col-xs-12 col-sm-5',
                            'placeholder' => 'Meta Keywords'
                        );
                        echo form_textarea($inputArray);
                        ?></div>
                </div>
            </div>
            <div class="space-2"></div>
            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Meta Description </label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <?php
                        $inputArray = array(
                            'name' => 'meta_description',
                            'id' => 'meta_description',
                            'rows' => 3,
                            'value' => $row['meta_description'],
                            'class' => 'col-xs-12 col-sm-5',
                            'placeholder' => 'Meta Description'
                        );
                        echo form_textarea($inputArray);
                        ?></div>
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
                    <button class="btn" type="reset" onclick="clear_form_elements('#categories_form');">
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
        $('#categories_form').validate({
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
