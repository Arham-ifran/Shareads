<div class="page-header">
    <h1>
        CMS Pages
        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            Manage CMS Pages
        </small>
    </h1>
</div><!-- /.page-header -->

<div class="row">
    <div class="col-xs-12">
        <div class="col-sm-8"></div>
        <div class="col-sm-4">
        <?php
        // Check rights
        if (rights(26) == true ) {
        ?>

            <a href="<?php echo base_url('admin/pages/add') ?>"><button type="button" class="btn btn-primary"   style="float: right;">Add New</button></a>

        <?php }?>

        <button type="button" class="btn btn-success" onclick="updateOrder('pages', 'form1');"  style="float: right; margin-right: 15px;">Update Order</button>
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
        <?php
        $form_attributes = array('name' => 'form1', 'method' => 'post', 'id' => 'form1', 'class' => 'form-horizontal', 'role' => 'form');
        echo form_open('', $form_attributes);
        ?>
        <div class="alert" id="formErrorMsg" style="display: none;">
        </div>
        <div class="space-4"></div>
        <div class="table-header"> Results for "All CMS Pages"</div>
        <table id="dynamic-table" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>

                    <th>Page Name</th>

                    <th class="">Page URL</th>
                    <td>Ordering</td>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows() > 0) {
                    foreach ($result->result() as $row) {
                        if ($row->status == 1) {
                            $status = '<span class="label label-sm label-info status_label' . $row->cmId . '">Active</span>';
                        } else {
                            $status = '<span class="label label-sm label-danger status_label' . $row->cmId . '">Inactive</span>';
                        }
                        ?>
                        <tr id="pages_<?php echo $row->cmId; ?>">


                            <td>
                                <?php if($row->page_id <> 0)
                           {
                               echo '<b>'.getVal('title', 'c_contentmanagement', 'cmId', $row->page_id).'</b> <i class="ace-icon fa fa-angle-double-right"></i> ';
                           } ?>
                                <?php echo ucwords($row->title); ?></td>
                            <td  class=""><a target="_blank" href="<?php echo base_url($row->slug); ?>"><?php echo base_url( $row->slug); ?></a></td>

                            <?php
                            $order_Block = '<input name="order_' . $row->cmId . '" type="text" size="3" maxlength="5" id="order_' . $row->cmId . '" value="' . $row->ordering . '"/>';

                        $order_Block .='<input name="ordId[]" type="hidden" id="ordId[]" value="' . $row->cmId . '" />';
                            ?>
                            <td><?php echo $order_Block; ?></td>


                            <td><?php echo $status; ?></td>
                            <td>

                                <div class="hidden-sm hidden-xs btn-group">
                                    <?php
                                    if ($row->status == 1) {
                            echo '<a title="Status" href="javascript:void(0);" class="blue status_button' . $row->cmId . '" onclick=updateStatus("pages",' . $row->cmId . ',1)><i class="ace-icon fa fa-plus bigger-130"></i></a>';
                        } else {
                            echo '<a title="Status" href="javascript:void(0);" class="red status_button' . $row->cmId . '" onclick=updateStatus("pages",' . $row->cmId . ',0)><i class="ace-icon fa fa-minus bigger-130"></i></a>';
                        }
                                    ?>
                                    <?php
                                    // Check rights
                                    if (rights(27) == true ) {
                                    ?>
                                    <a title="Edit" class="green" href="<?php echo base_url('admin/pages/edit/' . $this->common->encode($row->cmId)); ?>">
                                        <i class="ace-icon fa fa-pencil bigger-130"></i>
                                    </a>
                                    <?php
                                    }
                                    // Check rights
                                    if (rights(28) == true ) {
                                    ?>
                                    <a  title="Delete" class="red" onclick="return delete_confirm();" href="<?php echo base_url('admin/pages/delete/' . $this->common->encode($row->cmId)); ?>">
                                        <i class="ace-icon fa fa-trash-o bigger-130"></i>
                                    </a>
                                    <?php }?>

                                </div>

                                  <div class="hidden-md hidden-lg">
                                    <div class="inline pos-rel">
                                        <button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
                                            <i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
                                        </button>

                                        <ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">

                                            <?php
                                    if ($row->status == 1) {?>
                                    <li>
                                        <a href="javascript:void(0);" onclick="updateStatus('pages','<?php echo $row->cmId;?>',1)" class="tooltip-info status_sm_button<?php echo $row->cmId;?>" data-rel="tooltip" title="View">
                                            <span class="blue">
                                                <i class="ace-icon fa fa-plus bigger-120"></i>
                                            </span>
                                        </a>
                                    </li>

                      <?php  } else {?>
                                    <li>
                                        <a href="javascript:void(0);" onclick="updateStatus('pages','<?php echo $row->cmId;?>',1)" class="tooltip-info status_sm_button<?php echo $row->cmId;?>" data-rel="tooltip" title="View">
                                            <span class="red">
                                                <i class="ace-icon fa fa-minus bigger-120"></i>
                                            </span>
                                        </a>
                                    </li>
                        <?php }
                                    ?>

                                    <?php
                                // Check rights
                                if (rights(27) == true ) {
                                ?>
                                            <li>
                                                <a  href="<?php echo base_url('admin/pages/edit/' . $this->common->encode($row->cmId)) ?>" class="tooltip-success" data-rel="tooltip" title="Edit">
                                                    <span class="green">
                                                        <i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
                                                    </span>
                                                </a>
                                            </li>
                                            <?php
                                }
        // Check rights
        if (rights(28) == true ) {
        ?>
                                            ?>

                                            <li>
                                                <a  href="<?php echo base_url('admin/pages/delete/' . $this->common->encode($row->cmId)); ?>" onclick="return delete_confirm();" class="tooltip-error" data-rel="tooltip" title="Delete">
                                                    <span class="red">
                                                        <i class="ace-icon fa fa-trash-o bigger-120"></i>
                                                    </span>
                                                </a>
                                            </li>
        <?php }?>
                                        </ul>
                                    </div>
                                </div>


                            </td>
                        </tr>
                        <?php
                    }
                    ?>

                    <?php
                }  ?>
            </tbody>
        </table>
        <?php echo form_close(); ?>
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
                                null, null, null,
                                {"bSortable": false}
                            ],
                            "aaSorting": []

                        });


});
</script>