<div class="breadcrumbs ace-save-state" id="breadcrumbs">


    <ul class="breadcrumb">
        <li>
            <i class="ace-icon fa fa-home home-icon"></i>
            <a href="<?php echo base_url('admin/dashboard') ?>">Home</a>
        </li>

        <li>
            <a href="<?php echo base_url('admin/roles') ?>">Roles</a>
        </li>
        <li class="active">Add role</li>
    </ul><!-- /.breadcrumb -->


</div>

<div class="page-header">
    <h1>
        Roles

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
        <form id="roles_form" name="roles_form" action="<?php echo base_url('admin/roles/add') ?>" class="form-horizontal" role="form" method="post" accept-charset="utf-8">

            <input type="hidden" name="action" id="action" value="<?php echo $action; ?>"/>

            <input type="hidden"  id="role_id" name="role_id" value="<?php echo $row['role_id']; ?>"/>

            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Role Name *</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <input type="text" minlength="2" class="col-xs-12 col-sm-5" id="role" name="role"  placeholder="Role Name" value="<?php echo $row['role']; ?>" required>
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

            <h3 class="header smaller lighter blue">
                    Modules
                    <small>To give permission on specific module.(YES/NO)</small>
									</h3>

<div class="space-2"></div>


<?php
        $user_rights=explode(",", @$row['right_ids']);

        for($i=0;$i<count($rights);$i++)// as $right
        {
                if($i == 0)
                {
                              echo '<h4 class="blue">'.$rights[$i]['module'].'</h4>'
                                      . '<div class="form-group"><div class="controls">';
                              $module_id = $rights[$i]['module_id'];
                }
        ?>
        <div class="pull-left" style="width: 200px;">
            <label><?php echo $rights[$i]['right']?></label>
                <div class="space-1"></div>
                <label>
                    <input type="checkbox" class="ace ace-switch ace-switch-5" <?php if(in_array( $rights[$i]['id'],$user_rights)) echo 'checked="checked"';?> id="<?php echo $rights[$i]['id']?>" value="1"  name='<?php echo $rights[$i]['id']?>'>
                    <span class="lbl"></span>
                </label>
            </div>
      <?php
                if($module_id != $rights[($i+1)]['module_id'])
                {
                      echo '</div></div><h4 class="blue">'.$rights[($i+1)]['module'].'</h4><div class="form-group"><div class="controls">';
                      $module_id = $rights[($i+1)]['module_id'];
                }
      }
			?>


            <div class="space-2"></div>


            <div class="space-2"></div>
            <div class="clearfix form-actions">
                <div class="col-md-offset-3 col-md-9">
                    <button id="submit_btn" class="btn btn-info" type="submit">
                        <i class="ace-icon fa fa-check bigger-110"></i>
                        Submit
                    </button>

                    &nbsp; &nbsp; &nbsp;
                    <button class="btn" type="reset" onclick="clear_form_elements('#roles_form');">
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
        $('#roles_form').validate({
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


</script>