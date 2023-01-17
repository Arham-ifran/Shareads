<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css"/>
<div class="page-header">
    <h1>
        Publisher Invitations
        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            Manage Publisher Invitations
        </small>
    </h1>
</div><!-- /.page-header -->

<div class="row">
    <div class="col-xs-12">
        <?php
        if (rights(83) == true)
        {
            ?>
            <div class="col-sm-2"></div>
            <div class="col-sm-10">
                <a href="<?php echo base_url('assets/admin/sample_excel_files/sample.xls') ?>"><button type="button" class="btn btn-warning"   style="float: right; margin-right: 15px;">Download Excel Template</button></a>
                <form method="POST" enctype="multipart/form-data" id="import_csv_do" action="<?php echo base_url('admin/users/import_invitations') ?>">
                    <input id="import_csv_target" accept=".xls" type="file" name="excel_file" style="opacity: 0;float: left;height: 0px; width: 0px;">
                    <a id="import_csv_trigger" class="btn btn-primary pull-right" style="margin-right:5px">Import Excel <small>(only .xls files)</small></a>
                </form>                
                <a href="<?php echo base_url('admin/users/invitation_settings') ?>"><button type="button" class="btn btn-primary"   style="float: right; margin-right: 15px;">Invitation Settings</button></a>
                <a href="<?php echo base_url('admin/users/add_publisher_invitations') ?>"><button type="button" class="btn btn-primary"   style="float: right; margin-right: 15px;">Add Publisher Invitations</button></a>
            </div>
        <?php } ?>
    </div>
</div>

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

        <div class="alert" id="formErrorMsg" style="display: none;">
        </div>
        <div class="space-4"></div>
        <div class="table-header"> Results for "All Publishers Invitations"</div>
        <div class="space-4"></div>
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#pending_clicked">Pending/Clicked</a></li>
            <li><a data-toggle="tab" href="#accepted">Accepted</a></li>
        </ul>

        <div class="tab-content">
            <div id="pending_clicked" class="tab-pane fade in active">
                <form id="resendform" method="post" action="<?php echo base_url() . 'admin/users/resendinvitations_selected'; ?>">
                    <div class="col-md-12">
                        <div class="row">
                            <div id="plz-select-one" class="alert alert-danger" style="margin-top: 10px; display:none">
                                <strong>Attention!</strong> Please select at least one
                            </div>
                        </div>
                    </div>
                    <div class="space-4"></div>
                    <input type="submit" value="Send Invitations to selected" class="btn btn-primary" />
                    <a href="<?php echo base_url() . 'admin/users/resendinvitations_all'; ?>" class="btn btn-primary" >Send Invitations to all</a>
                    <div class="space-4"></div>
                    <table id="dynamic-table1" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th><input class="order_checkboxes_main" value="1" type="checkbox"></th>
                                <th class="">Email</th>
                                <th class="">Link</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows() > 0)
                            {
                                foreach ($result->result() as $row)
                                {
                                    if ($row->status == 2)
                                    {
                                        continue;
                                    }
                                    if ($row->status == 1)
                                    {
                                        $status = '<span class="label label-sm label-warning status_label' . $row->id . '">Link Clicked</span>';
                                    }
                                    else if ($row->status == 2)
                                    {
                                        $status = '<span class="label label-sm label-info status_label' . $row->id . '">Signed Up</span>';
                                    }
                                    else
                                    {
                                        $status = '<span class="label label-sm label-danger status_label' . $row->id . '">Invitation Sent</span>';
                                    }
                                    ?>
                                    <tr id="publisher_invitations_<?php echo $row->id; ?>">
                                        <td><input class="order_checkboxes" name="selected_checkbox[]" value="<?php echo $row->id ?>" type="checkbox"><input type="hidden" value="<?php echo $row->secret_key; ?>" class="secret_key" /></td>
                                        <td  class=""><?php echo $row->email ?></td>
                                        <td><a href="<?php echo base_url() . 'register/ipublisher/' . $row->user_key; ?>" ><?php echo base_url() . 'register/ipublisher/' . $row->user_key; ?></a></td>
                                        <td><?php echo $status; ?></td>
                                    </tr>
                                    <?php
                                }
                                ?>

                            <?php }
                            ?>
                        </tbody>
                    </table>
                </form>
            </div>
            <div id="accepted" class="tab-pane fade">
                <table id="dynamic-table3" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="">Email</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows() > 0)
                        {
                            foreach ($result->result() as $row)
                            {
                                if ($row->status <> 2)
                                {
                                    continue;
                                }
                                if ($row->status == 1)
                                {
                                    $status = '<span class="label label-sm label-warning status_label' . $row->id . '">Link Clicked</span>';
                                }
                                else if ($row->status == 2)
                                {
                                    $status = '<span class="label label-sm label-info status_label' . $row->id . '">Signed Up</span>';
                                }
                                else
                                {
                                    $status = '<span class="label label-sm label-danger status_label' . $row->id . '">Invitation Sent</span>';
                                }
                                ?>
                                <tr id="publisher_invitations_<?php echo $row->id; ?>">
                                    <td  class=""><?php echo $row->email ?></td>
                                    <td><?php echo $status; ?></td>
                                </tr>
                                <?php
                            }
                            ?>

                        <?php }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>


    </div>
</div>

<!-- page specific plugin scripts -->

<script src="<?php echo base_url('assets/admin/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/admin/js/jquery.dataTables.bootstrap.min.js') ?>"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
<script>
    var message = "Are you sure you want to resend invitations to the selected";
    $(document).ready(function () {
        $("#import_csv_trigger").click(function () {
            $("#import_csv_target").click();
        });
        $("#import_csv_target").change(function () {
            $("#import_csv_do").submit();
        });
    });
    jQuery(function () {
        try {
            $('#dynamic-table1').dataTable({
                dom: '<"top"B<"clear">f>rt<"bottom"lp><"clear">',
                buttons: [
                    {
                        title: 'Pending/Clicked Invitation of Publishers | ShareAds Admin Panel',
                        text: 'Export to excel', extend: 'excel', footer: false,
                        exportOptions: {columns: [1, 2, 3]}
                    }
                ],
                aLengthMenu: [
                    [25, 50, 100, 200, -1],
                    [25, 50, 100, 200, "All"]
                ],
                'iDisplayLength': 100,
            });
//            $('#dynamic-table2').dataTable({
//                aLengthMenu: [
//                    [25, 50, 100, 200, -1],
//                    [25, 50, 100, 200, "All"]
//                ],
//                'iDisplayLength': 100,
//            });
            $('#dynamic-table3').dataTable({
                dom: '<"top"B<"clear">f>rt<"bottom"lp><"clear">',
                buttons: [
                    {
                        title: 'Accepted Invitation of Publishers | ShareAds Admin Panel',
                        text: 'Export to excel', extend: 'excel', footer: false,
                        exportOptions: {columns: [0, 1]}
                    }
                ],
                aLengthMenu: [
                    [25, 50, 100, 200, -1],
                    [25, 50, 100, 200, "All"]
                ],
                'iDisplayLength': 100,
            });
        } catch (e)
        {
            console.log(e);
        }

        $('.order_checkboxes_main').change(function () {
            if ($('.order_checkboxes_main:checked').length > 0) {
                $('.order_checkboxes').prop('checked', true);
            } else {
                $('.order_checkboxes').prop('checked', false);
            }
        });
        $("#resendform").submit(function () {
            if ($('.order_checkboxes:checked').length > 0) {
                $('#plz-select-one').hide();
                if (confirm(message)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                $('#plz-select-one').show();
                hideMessage();
                return false;
            }
        });
    });
    function hideMessage()
    {
        setTimeout(function () {
            $('#plz-select-one').hide('slow');
        }, 4000);
    }
</script>