<div class="page-header">
    <h1>
        Lead Generation
        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            Manage Lead Generation
        </small>
    </h1>
</div><!-- /.page-header -->

<div class="row">
    <div class="col-xs-12">

        <div class="col-sm-8">

        </div>


    </div>
</div>

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

        <div class="alert" id="formErrorMsg" style="display: none;">
        </div>
        <div class="space-4"></div>
        <div class="table-header"> Results for "All Lead Generation" </div>
        <table id="dynamic-table" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th class="">Advertiser</th>
                    <th>Listing Name</th>
                    <th>Commission</th>
                    <th class="">Category</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows() > 0) {
                    foreach ($result->result() as $row) {
                        if ($row->status == 1) {
                            $status = '<span class="label label-sm label-info">Confirmed</span>';
                        } else if ($row->status == 2) {
                            $status = '<span class="label label-sm label-danger">Canceled</span>';
                        } else {
                            $status = '<span class="label label-sm label-warning">Pending</span>';
                        }
                        ?>
                        <tr id="lead_generation_<?php echo $row->id; ?>">

                            <td class=""><?php echo ucwords($row->full_name); ?></td>

                            <td><?php echo ucwords($row->product_name); ?></td>
                            <td><?php echo getSiteCurrencySymbol(); ?><?php echo number_format($row->commission, 2); ?></td>


                            <td class=""><?php echo $row->category_name ?></td>


                            <td><?php echo $status; ?></td>


                            <td>

                                <div class="hidden-sm hidden-xs btn-group">

        <?php
        if ($row->status <> 2 && $row->status <> 1) {
            ?>

                                        <a title="Confirm" class="btn btn-primary btn-sm" href="<?php echo base_url('admin/lead_generation/change_status/' . $this->common->encode($row->id)); ?>/1">
                                            Confirm
                                        </a>


                                        <a  title="Cancel" class="btn btn-danger btn-sm" onclick="return delete_cancel();" href="<?php echo base_url('admin/lead_generation/change_status/' . $this->common->encode($row->id)); ?>/2" >
                                            Cancel
                                        </a>

        <?php } ?>

                                </div>

                                <div class="hidden-md hidden-lg">
                                    <div class="inline pos-rel">
                                        <button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
                                            <i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
                                        </button>

                                        <ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">

        <?php
        if ($row->status <> 2 && $row->status <> 1) {
            ?>
                                                <li>
                                                    <a  href="<?php echo base_url('admin/lead_generation/change_status/' . $this->common->encode($row->id)) ?>/1" class="tooltip-success" data-rel="tooltip" title="Confirm">
                                                        <span class="green">
                                                            <i class="ace-icon fa fa-check bigger-120"></i>
                                                        </span>
                                                    </a>
                                                </li>


                                                <li>
                                                    <a  href="<?php echo base_url('admin/lead_generation/change_status/' . $this->common->encode($row->id)); ?>/2" onclick="return delete_confirm();" class="tooltip-error" data-rel="tooltip" title="Cancel">
                                                        <span class="red">
                                                            <i class="ace-icon fa fa-times bigger-120"></i>
                                                        </span>
                                                    </a>
                                                </li>
        <?php } ?>
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
<script>
                                                        jQuery(function () {
                                                            $('#dynamic-table').dataTable({
                                                                bAutoWidth: false, scrollX: true,
                                                                "aoColumns": [
                                                                    {"bSortable": true},
                                                                    null, null, null, null,
                                                                    {"bSortable": false}
                                                                ],
                                                                "aaSorting": []

                                                            });


                                                        });
</script>