<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css"/>
<div class="page-header">
    <h1>
        Payment Method Integration
        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            Manage Payment Method
        </small>
    </h1>
</div><!-- /.page-header -->

<div class="row">
    <div class="col-xs-12">
        <?php
        if (rights(65) == true)
        {
            if ($result->num_rows() < 1)
            {
                ?>
                <div class="col-sm-8"></div>
                <div class="col-sm-4">
                    <a href="<?php echo base_url('admin/payment_integration/add') ?>"><button type="button" class="btn btn-primary"   style="float: right; margin-right: 15px;">Add New</button></a>
                </div>
                <?php
            }
        }
        ?>
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
        <div class="table-header"> "Payment Integration Settings"</div>
        <table id="dynamic-table" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>Production Type</th>
                    <th class="">API Username</th>
                    <th class="">API Password</th>

                    <th>PayPal ID</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows() > 0)
                {
                    foreach ($result->result() as $row)
                    {
                        if ($row->integration_type == 1)
                        {
                            $type = '<span class="label label-success">Live</span>';
                        }
                        else
                        {
                            $type = '<span class="label label-warning">Sandbox</span>';
                        }
                        ?>
                        <tr id="payment_integration_<?php echo $row->id; ?>">
                            <td><?php echo $type; ?></td>
                            <td class=""><?php echo ($row->api_username); ?></td>
                            <td class=""><?php echo ($row->api_password); ?></td>

                            <td><?php echo ($row->paypal_id); ?></td>
                            <td>
                                <div class="hidden-sm hidden-xs btn-group">
                                    <?php
                                    if ($row->status == 1)
                                    {
                                        echo '<a title="Status" href="javascript:void(0);" class="blue status_button' . $row->id . '" onclick=updateStatus("payment_integration",' . $row->id . ',1)><i class="ace-icon fa fa-plus bigger-130"></i></a>';
                                    }
                                    else
                                    {
                                        echo '<a title="Status" href="javascript:void(0);" class="red status_button' . $row->id . '" onclick=updateStatus("payment_integration",' . $row->id . ',0)><i class="ace-icon fa fa-minus bigger-130"></i></a>';
                                    }
                                    ?>
                                    <?php
                                    if (rights(66) == true)
                                    {
                                        ?>
                                        <a title="Edit" class="green" href="<?php echo base_url('admin/payment_integration/edit/' . $this->common->encode($row->id)); ?>">
                                            <i class="ace-icon fa fa-pencil bigger-130"></i>
                                        </a>
                                    <?php } ?>

                                    <?php
                                    if (rights(67) == true)
                                    {
                                        ?>
                                        <a  title="Delete" class="red" onclick="return delete_confirm();" href="<?php echo base_url('admin/payment_integration/delete/' . $this->common->encode($row->id)) ?>">
                                            <i class="ace-icon fa fa-trash-o bigger-130"></i>
                                        </a>
                                        <?php
                                    }
                                    ?>

                                </div>

                                <div class="hidden-md hidden-lg">
                                    <div class="inline pos-rel">
                                        <button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
                                            <i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
                                        </button>

                                        <ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">

                                            <?php if ($row->status == 1)
                                            {
                                                ?>
                                                <li>
                                                    <a href="javascript:void(0);" onclick="updateStatus('payment_integration', '<?php echo $row->id; ?>', 1)" class="tooltip-info status_sm_button<?php echo $row->id; ?>" data-rel="tooltip" title="View">
                                                        <span class="blue">
                                                            <i class="ace-icon fa fa-plus bigger-120"></i>
                                                        </span>
                                                    </a>
                                                </li>

                                            <?php
                                            }
                                            else
                                            {
                                                ?>
                                                <li>
                                                    <a href="javascript:void(0);" onclick="updateStatus('payment_integration', '<?php echo $row->id; ?>', 1)" class="tooltip-info status_sm_button<?php echo $row->id; ?>" data-rel="tooltip" title="View">
                                                        <span class="red">
                                                            <i class="ace-icon fa fa-minus bigger-120"></i>
                                                        </span>
                                                    </a>
                                                </li>
                                            <?php }
                                            ?>

                                            <?php
                                            if (rights(66) == true)
                                            {
                                                ?>
                                                <li>
                                                    <a  href="<?php echo base_url('admin/payment_integration/edit/' . $this->common->encode($row->id)) ?>" class="tooltip-success" data-rel="tooltip" title="Edit">
                                                        <span class="green">
                                                            <i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
                                                        </span>
                                                    </a>
                                                </li>
                                            <?php } ?>

                                            <?php
                                            if (rights(67) == true)
                                            {
                                                ?>
                                                <li>
                                                    <a href="<?php echo base_url('admin/payment_integration/delete/' . $this->common->encode($row->id)) ?>" onclick="return delete_confirm();" class="tooltip-error" data-rel="tooltip" title="Delete">
                                                        <span class="red">
                                                            <i class="ace-icon fa fa-trash-o bigger-120"></i>
                                                        </span>
                                                    </a>
                                                </li>
            <?php
        }
        ?>
                                        </ul>
                                    </div>
                                </div>
                            </td>
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

<!-- page specific plugin scripts -->


<script src="<?php echo base_url('assets/admin/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/admin/js/jquery.dataTables.bootstrap.min.js') ?>"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
<script>
                                            jQuery(function () {
                                                try {
                                                    $('.table').dataTable({
                                                        dom: '<"top"B<"clear">f>rt<"bottom"lp><"clear">',
                                                        buttons: [
                                                            {
                                                                title: 'Payment Integration | ShareAds Admin Panel',
                                                                text: 'Export to excel', extend: 'excel', footer: false,
                                                                exportOptions: {columns: [0,1,2,3]}
                                                            }
                                                        ],
                                                        bAutoWidth: false, scrollX: true,
                                                        "aoColumns": [
                                                            {"bSortable": false},
                                                            null, null, null,
                                                            {"bSortable": false}
                                                        ],
                                                        "aaSorting": []

                                                    });
                                                } catch (e)
                                                {
                                                    console.log(e);
                                                }

                                            });
</script>