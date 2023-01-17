<div class="breadcrumbs ace-save-state" id="breadcrumbs">


    <ul class="breadcrumb">
        <li>
            <i class="ace-icon fa fa-home home-icon"></i>
            <a href="<?php echo base_url('admin/dashboard') ?>">Home</a>
        </li>

        <li>
            <a href="<?php echo base_url('admin/admin_users') ?>">Admin Users</a>
        </li>
        <li class="active">Add Admin</li>
    </ul><!-- /.breadcrumb -->


</div>

<div class="page-header">
    <h1>
        Admin Users

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
        <form id="admin_users_form" name="admin_users_form" action="<?php echo base_url('admin/admin_users/add') ?>" class="form-horizontal" role="form" method="post"  accept-charset="utf-8" enctype="multipart/form-data">

            <input type="hidden" name="action" id="action" value="<?php echo $action; ?>">

            <input type="hidden"  id="user_id" name="user_id" value="<?php echo $row['user_id']; ?>"  >


            <?php
            if($this->session->userdata('role_id') == 0) {
                if(isset($row) && $row['role_id'] == 0)
                {

                }else{
                ?>

            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="admin_users">Role</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                    <select id="role_id" name="role_id" class="col-xs-12 col-sm-5" required>
                        <option value="">---- Select Role -----</option>
                        <?php
                        $sel = '';
                        foreach ($all_roles as $pg) {
                            echo '<option value="' . $pg['role_id'] . '">' . $pg['role'] . '</option>';
                        }
                        ?>
                    </select>
                        </div>
                </div>
            </div>
            <script>$("#role_id").val(<?php echo $row['role_id'] ?>);</script>
            <?php }
            }?>

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
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Last Name</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <input type="text" minlength="2"  class="col-xs-12 col-sm-5" id="last_name" name="last_name" placeholder="Last Name" value="<?php echo $row['last_name'] ?>" />
                    </div></div>
            </div>


            <div class="space-2"></div>
            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Email *</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">

                        <input type="email" class="col-xs-12 col-sm-5 ckeditor" id="email" name="email" value="<?php echo $row['email'] ?>"  placeholder="Email"  autocomplete="off" onchange="checkEmail();" required/>
                        <label class="help-block" id="emailExist_error" style="display: none;"></label>
                    </div>
                </div>
            </div>

            <div class="space-2"></div>
            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Password <?php echo (!isset($row)) ? '*' : '' ?></label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">

                        <input type="password"  minlength="6" class="col-xs-12 col-sm-5 ckeditor" id="password" name="password"  autocomplete="off"  placeholder="Password" <?php echo (!isset($row)) ? 'required' : '' ?>/>

                    </div>
                </div>
            </div>




            <div class="space-2"></div>

            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="photo">Photo</label>

                <div  class="col-xs-10 col-sm-4">
                    <input type="file" id="photo" name="photo"  accept="image/*">
                    <div class="space-2"></div>
                    <input type="hidden" name="old_photo"  id="old_photo" value="<?php echo $row['photo']; ?>">
                    <?php
                    if (isset($row)) {
                        if ($row['photo'] == '') {
                            $row['photo'] = 'abc.png';
                        }
                        echo '<img src="' . $this->common->check_image(base_url("uploads/admin_users/small/" . $row['photo']), 'no_image.jpg') . '" width="50" height="50" />';
                    }
                    ?>


                </div>
            </div>
<?php
if ($this->session->userdata('role_id') == 0 && $this->session->userdata('user_id') <> $row['status']) {
    ?>
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

            <?php }?>



            <div class="space-2"></div>
            <div class="clearfix form-actions">
                <div class="col-md-offset-3 col-md-9">
                    <button id="submit_btn" class="btn btn-info" type="submit">
                        <i class="ace-icon fa fa-check bigger-110"></i>
                        Submit
                    </button>

                    &nbsp; &nbsp; &nbsp;
                    <button class="btn" type="reset" onclick="clear_form_elements('#admin_users_form');">
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
        $('#admin_users_form').validate({
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