<div class="breadcrumbs ace-save-state" id="breadcrumbs">


    <ul class="breadcrumb">
        <li>
            <i class="ace-icon fa fa-home home-icon"></i>
            <a href="<?php echo base_url('admin/dashboard') ?>">Home</a>
        </li>

        <li>
            <a href="<?php echo base_url('admin/pages') ?>">CMS Pages</a>
        </li>
        <li class="active">Add CMS Pages</li>
    </ul><!-- /.breadcrumb -->


</div>


<div class="page-header">
    <h1>
        CMS Pages

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
        <?php
        $display = '';
        if ($row['is_main_page'] == 1) {
            $display = 'display:none';
        }
        ?>
        <div class="space-8"></div>
        <div class="space-8"></div>
        <form id="formCmsPage" name="formCmsPage" action="<?php echo base_url('admin/pages/add') ?>" class="form-horizontal" role="form" method="post" accept-charset="utf-8">

            <input type="hidden" name="action" id="action" value="<?php echo $action; ?>">

            <input type="hidden"  id="cmId" name="cmId" value="<?php echo $row['cmId']; ?>"  >


            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="pages">Is Main Page</label>
                <div class="col-xs-12 col-sm-9">
                    <input  type="checkbox" <?php echo ($row['is_main_page'] == 1) ? 'checked' : '' ?> onclick="is_main()" id="is_main_page" value="1" name="is_main_page">&nbsp;(Check if you want to make it master page)
                </div>
            </div>
            <div class="space-2"></div>
            <div style="<?php echo $display; ?>" id="page_div" class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="pages">Master Page</label>
                <div class="col-xs-12 col-sm-9">
                    <select id="page_id" name="page_id" class="col-xs-12 col-sm-5" required>
                        <?php
                        $sel = '';
                        foreach ($allpages as $pg) {
                            echo '<option value="' . $pg['cmId'] . '">' . $pg['title'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
            <script>$("#page_id").val(<?php echo $row['page_id'] ?>);</script>
            <div class="space-2"></div>
            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Title *</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <input onchange="creatPageSlug()" minlength="3" type="text" class="col-xs-12 col-sm-5" id="pageTitle" name="title"  placeholder="Title" value="<?php echo $row['title']; ?>" required>
                        <div  id="pageExist_error" style="display: none;"> Opps! Already exists. Please try another.</div></div>
                </div>
            </div>
            <div class="space-2"></div>
            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Slug</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <input type="text" class="col-xs-12 col-sm-5" id="slug" name="slug"  readonly placeholder="Slug" value="<?php echo $row['slug'] ?>">
                        <input type="hidden"  id="old_slug" value="<?php echo $row['title']; ?>">   </div></div>
            </div>
            <div class="space-2"></div>
            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Description *</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">

                        <textarea class="col-xs-12 col-sm-5 ckeditor" id="description" name="description"  placeholder="Description" required><?php echo $row['description'] ?></textarea>

                    </div>
                </div>
            </div>
            <div class="space-2"></div>
            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Meta Keywords</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <input type="text" class="col-xs-12 col-sm-5" id="meta_keywords" name="meta_keywords"  placeholder="Meta Keywords" value="<?php echo $row['meta_keywords'] ?>"></div>
                </div>
            </div>
            <div class="space-2"></div>
            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Meta Description</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <textarea class="col-xs-12 col-sm-5" name="meta_description" placeholder="Meta Description"><?php echo $row['meta_description'] ?></textarea></div>
                </div>
            </div>
            <div class="space-2"></div>
            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Is Contact Us *</label>
                <div>
                    <div class="col-xs-12 col-sm-9">
                        <div class="clearfix">
                            <label><input type="radio" id="is_contactus" <?php echo ($row['is_contactus'] == 1) ? 'checked' : '' ?> value="1" name="is_contactus">&nbsp;Yes</label>&nbsp;&nbsp;<label><input type="radio" <?php echo ($row['is_contactus'] == 0) ? 'checked' : '' ?> id="is_contactus" value="0" name="is_contactus">&nbsp;No</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="space-2"></div>
            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Show in Header</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <label><input type="radio" id="show_header" <?php echo ($row['show_header'] == 1) ? 'checked' : '' ?> value="1" name="show_header">&nbsp;Yes</label>&nbsp;&nbsp;<label><input type="radio" <?php echo ($row['show_header'] == 0) ? 'checked' : '' ?> id="show_header" value="0" name="show_header">&nbsp;No</label>
                    </div>
                </div>
            </div>
            <div class="space-2"></div>
            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Show in Footer</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <label><input type="radio" id="show_footer" <?php echo ($row['show_footer'] == 1) ? 'checked' : '' ?> value="1" name="show_footer">&nbsp;Yes</label>&nbsp;&nbsp;<label><input type="radio" <?php echo ($row['show_footer'] == 0) ? 'checked' : '' ?> id="show_footer" value="0" name="show_footer">&nbsp;No</label>
                    </div>
                </div>
            </div>
            <div class="space-2"></div>

            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Show in Tabs Menu</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <label><input type="radio" id="show_left" <?php echo ($row['show_left'] == 1) ? 'checked' : '' ?> value="1" name="show_left">&nbsp;Yes</label>&nbsp;&nbsp;<label><input type="radio" <?php echo ($row['show_left'] == 0) ? 'checked' : '' ?> id="show_left" value="0" name="show_left">&nbsp;No</label>
                    </div>
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



            <div class="space-2"></div>
            <div class="clearfix form-actions">
                <div class="col-md-offset-3 col-md-9">
                    <button id="submit_btn" class="btn btn-info" type="submit">
                        <i class="ace-icon fa fa-check bigger-110"></i>
                        Submit
                    </button>

                    &nbsp; &nbsp; &nbsp;
                    <button class="btn" type="reset" onclick="clear_form_elements('#formCmsPage');">
                        <i class="ace-icon fa fa-undo bigger-110"></i>
                        Reset
                    </button>
                </div>
            </div>

        </form>

    </div>
</div>
<script src="<?php echo base_url('assets/admin/js/ckeditor/ckeditor.js') ?>"></script>

<script>
                        $(function () {
                            $('#formCmsPage').validate({
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
                        function is_main()
                        {
                            if (document.getElementById('is_main_page').checked == true)
                            {
                                $('#page_div').hide();
                            } else
                            {
                                $('#page_div').show();
                            }
                        }

</script>