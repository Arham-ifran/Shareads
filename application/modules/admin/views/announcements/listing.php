<div class="page-header">
    <h1>
         Site Ads
        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            Manage Site Ads
        </small>
    </h1>
</div><!-- /.page-header -->

<div class="row">
    <div class="col-xs-12">
        <?php
        if (rights(69) == true) {
            ?>
            <div class="col-sm-8"></div>
            <div class="col-sm-4">
                <a href="<?php echo base_url('admin/announcements/add') ?>"><button type="button" class="btn btn-primary"   style="float: right; margin-right: 15px;">Add New</button></a>
            </div>
        <?php } ?>
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
        <div class="table-header"> Results for "All Site Ads"</div>
        <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Is Image / Text</th>
                            <th>Location</th>
                            <th>Expire After</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
if ($result->num_rows() > 0) {
    foreach ($result->result() as $row) {
        if ($row->status == 1) {
                            $status = '<span class="label label-sm label-info status_label' . $row->ads_id . '">Active</span>';
                        } else {
                            $status = '<span class="label label-sm label-danger status_label' . $row->ads_id . '">Inactive</span>';
                        }
        ?>
                                <tr id="announcements_<?php echo $row->ads_id; ?>">
                                    <td><?php
                                    if ($row->images == '') {
                                        $row->images = 'abc.png';
                                        }
                                        $image = $this->common->check_image(base_url("uploads/announcements/pic/" . $row->images), 'no_image.jpg');                            ?>
                                        <img src="<?php echo $image; ?>" width="75" /></td>
                                    <td><?php echo ($row->is_banner ==1)? 'Image':'Text'; ?></td>
                                    <td><?php if($row->announcements_destination_id ==1)
                                    {
                                        echo 'Header Area';
                                    }elseif($row->announcements_destination_id ==2)
                                    {
                                        echo 'Footer Area';
                                    }elseif($row->announcements_destination_id ==3)
                                    {
                                        echo 'Left Side Area';
                                    }elseif($row->announcements_destination_id ==4)
                                    {
                                        echo 'Right Side Area';
                                    }elseif($row->announcements_destination_id ==5)
                                    {
                                        echo 'Centered Area';
                                    }
?></td>

                                    <td><?php echo date('M d,Y',$row->end_date); ?></td>
                                    <td><?php echo $status; ?></td>
                                    <td>
                                         <div class="hidden-sm hidden-xs btn-group">
                                    <?php
                                    if ($row->status == 1) {
                                        echo '<a title="Status" href="javascript:void(0);" class="blue status_button' . $row->ads_id . '" onclick=updateStatus("announcements",' . $row->ads_id . ',1)><i class="ace-icon fa fa-plus bigger-130"></i></a>';
                                    } else {
                                        echo '<a title="Status" href="javascript:void(0);" class="red status_button' . $row->ads_id . '" onclick=updateStatus("announcements",' . $row->ads_id . ',0)><i class="ace-icon fa fa-minus bigger-130"></i></a>';
                                    }
                                    ?>
                                    <?php
                                    if (rights(70) == true) {
                                        ?>
                                        <a title="Edit" class="green" href="<?php echo base_url('admin/announcements/edit/' . $this->common->encode($row->ads_id)); ?>">
                                            <i class="ace-icon fa fa-pencil bigger-130"></i>
                                        </a>
                                    <?php } ?>

                                    <?php
                                    if (rights(71) == true) {
                                        ?>
                                        <a  title="Delete" class="red" onclick="return delete_confirm();" href="<?php echo base_url('admin/announcements/delete/' . $this->common->encode($row->ads_id)) ?>">
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
                                                    <a href="javascript:void(0);" onclick="updateStatus('announcements', '<?php echo $row->ads_id; ?>', 1)" class="tooltip-info status_sm_button<?php echo $row->ads_id; ?>" data-rel="tooltip" title="View">
                                                        <span class="blue">
                                                            <i class="ace-icon fa fa-plus bigger-120"></i>
                                                        </span>
                                                    </a>
                                                </li>

                                            <?php } else { ?>
                                                <li>
                                                    <a href="javascript:void(0);" onclick="updateStatus('announcements', '<?php echo $row->ads_id; ?>', 1)" class="tooltip-info status_sm_button<?php echo $row->ads_id; ?>" data-rel="tooltip" title="View">
                                                        <span class="red">
                                                            <i class="ace-icon fa fa-minus bigger-120"></i>
                                                        </span>
                                                    </a>
                                                </li>
                                            <?php }
                                            ?>

                                            <?php
                                            if (rights(70) == true) {
                                                ?>
                                                <li>
                                                    <a  href="<?php echo base_url('admin/announcements/edit/' . $this->common->encode($row->ads_id)) ?>" class="tooltip-success" data-rel="tooltip" title="Edit">
                                                        <span class="green">
                                                            <i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
                                                        </span>
                                                    </a>
                                                </li>
                                            <?php } ?>

                                            <?php
                                            if (rights(71) == true) {
                                                ?>
                                                <li>
                                                    <a href="<?php echo base_url('admin/announcements/delete/' . $this->common->encode($row->ads_id)) ?>" onclick="return delete_confirm();" class="tooltip-error" data-rel="tooltip" title="Delete">
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
    <?php
} ?>
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
        bAutoWidth: false,scrollX: true,
        "aoColumns": [
            {"bSortable": false},
            null, null, null,null,
            {"bSortable": false}
        ],
        "aaSorting": []

    });


});
</script>