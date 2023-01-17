<div class="page-header">
    <h1>
        Categories
        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            Manage Categories
        </small>
    </h1>
</div><!-- /.page-header -->

<div class="row">
    <div class="col-xs-12">

        <?php
        if (rights(91) == true) {
            ?>
        <div class="col-sm-8"></div>
        <div class="col-sm-4">
            <a href="<?php echo base_url('admin/categories/add') ?>"><button type="button" class="btn btn-primary"   style="float: right; margin-right: 15px;">Add New</button></a>
        </div>
        <?php }?>
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
                <div class="alert" id="formErrorMsg" style="display: none;"></div>

                <div class="space-4"></div>
        <div class="table-header"> Results for "All Categories"</div>
                <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Category Title</th>
                            <th class="col-md-2" style="text-align: center;">Status</th>
                            <th class="col-md-2" style="text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (is_array($results) && count($results) > 0) {
                            $i = 1;
                            foreach ($results as $row) {
                                if ($row->status == 1) {
                            $status = '<span class="label label-sm label-info status_label' . $row->category_id . '">Active</span>';
                                } else {
                                    $status = '<span class="label label-sm label-danger status_label' . $row->category_id . '">Inactive</span>';
                                }
                                ?>
                                <tr id="categories_<?php echo $row->category_id; ?>">
                                    <td><?php echo $row->spcr . ' ' . ucwords($row->category_name); ?></td>
                                    <td style="text-align: center;"><?php echo $status; ?></td>
                                    <td style="text-align: center;">



                                        <div class="hidden-sm hidden-xs btn-group">
                                    <?php
                                    if ($row->status == 1) {
                                        echo '<a title="Status" href="javascript:void(0);" class="blue status_button' . $row->category_id . '" onclick=updateStatus("categories",' . $row->category_id . ',1)><i class="ace-icon fa fa-plus bigger-130"></i></a>';
                                    } else {
                                        echo '<a title="Status" href="javascript:void(0);" class="red status_button' . $row->category_id . '" onclick=updateStatus("categories",' . $row->category_id . ',0)><i class="ace-icon fa fa-minus bigger-130"></i></a>';
                                    }
                                    ?>
                                    <?php
                                    if (rights(92) == true) {
                                        ?>
                                        <a title="Edit" class="green" href="<?php echo base_url('admin/categories/edit/' . $this->common->encode($row->category_id)); ?>">
                                            <i class="ace-icon fa fa-pencil bigger-130"></i>
                                        </a>
        <?php } ?>

                                    <?php
                                    if (rights(93) == true) {

                                            ?>
                                            <a  title="Delete" class="red" onclick="return delete_confirm();" href="<?php echo base_url('admin/categories/delete/' . $this->common->encode($row->category_id)) ?>">
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

        <?php if ($row->status == 1) { ?>
                                                <li>
                                                    <a href="javascript:void(0);" onclick="updateStatus('categories', '<?php echo $row->category_id; ?>', 1)" class="tooltip-info status_sm_button<?php echo $row->category_id; ?>" data-rel="tooltip" title="View">
                                                        <span class="blue">
                                                            <i class="ace-icon fa fa-plus bigger-120"></i>
                                                        </span>
                                                    </a>
                                                </li>

        <?php } else { ?>
                                                <li>
                                                    <a href="javascript:void(0);" onclick="updateStatus('categories', '<?php echo $row->category_id; ?>', 1)" class="tooltip-info status_sm_button<?php echo $row->category_id; ?>" data-rel="tooltip" title="View">
                                                        <span class="red">
                                                            <i class="ace-icon fa fa-minus bigger-120"></i>
                                                        </span>
                                                    </a>
                                                </li>
        <?php }
        ?>

                                            <?php
                                            if (rights(92) == true) {
                                                ?>
                                                <li>
                                                    <a  href="<?php echo base_url('admin/categories/edit/' . $this->common->encode($row->category_id)) ?>" class="tooltip-success" data-rel="tooltip" title="Edit">
                                                        <span class="green">
                                                            <i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
                                                        </span>
                                                    </a>
                                                </li>
        <?php } ?>

        <?php
        if (rights(93) == true) {

                ?>
                                                    <li>
                                                        <a href="<?php echo base_url('admin/categories/delete/' . $this->common->encode($row->category_id)) ?>" onclick="return delete_confirm();" class="tooltip-error" data-rel="tooltip" title="Delete">
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
                                $i++;
                            }
                            ?>
                            <?php
                        } else {
                            ?>
                            <tr>
                                <td colspan="3"><div class="alert alert-danger">No record found.</div></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>


            </div>
        </section>
    </div>
</div>
<!-- page specific plugin scripts -->

        <script src="<?php echo base_url('assets/admin/js/jquery.dataTables.min.js') ?>"></script>
        <script src="<?php echo base_url('assets/admin/js/jquery.dataTables.bootstrap.min.js') ?>"></script>
<script>
jQuery(function(){
                    $('#dynamic-table').dataTable({
                            bAutoWidth: false,scrollX: true,
                            "aoColumns": [
                                {"bSortable": true},
                                null,
                                {"bSortable": false}
                            ],
                            "aaSorting": []

                        });


});
</script>