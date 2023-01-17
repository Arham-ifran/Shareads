<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css"/>
<div class="page-header">
    <h1>
        Help topics
        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            Manage Help topics
        </small>
    </h1>
</div><!-- /.page-header -->

<div class="row">
    <div class="col-xs-12">
        <div class="col-sm-8"></div>
        <div class="col-sm-4">
            <?php
            // Check rights
            if (rights(98) == true)
            {
                ?>

                <a href="<?php echo base_url('admin/helptopics/add') ?>"><button type="button" class="btn btn-primary"   style="float: right;">Add New</button></a>

            <?php } ?>

        </div>
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
        <?php
        $form_attributes = array('name' => 'form1', 'method' => 'post', 'id' => 'form1', 'class' => 'form-horizontal', 'role' => 'form');
        echo form_open('', $form_attributes);
        ?>
        <div class="alert" id="formErrorMsg" style="display: none;">
        </div>
        <div class="space-4"></div>
        <div class="table-header"> Results for "All Help topics"</div>
        <table id="dynamic-table" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th width="30%">Title</th>
                    <th width="30%">Description</th>
                    <th width="10%">Status</th>
                    <th width="10%">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows() > 0)
                {
                    foreach ($result->result() as $row)
                    {
                        if ($row->status == 1)
                        {
                            $status = '<span class="label label-sm label-info status_label' . $row->id . '">Active</span>';
                        }
                        else
                        {
                            $status = '<span class="label label-sm label-danger status_label' . $row->id . '">Inactive</span>';
                        }
                        ?>
                        <tr id="helptopics_<?php echo $row->id; ?>">

                            <td class="">
                                
                                <?php
                                $dot = '';
                                if (count(explode(' ', $row->title)) > 10)
                                {
                                    $dot = '...';
                                }
                                echo '<strong>'.implode(' ', array_slice(explode(' ', $row->title), 0, 10)) . $dot.'</strong>';
                                ?></td>
                            <td class="">

                                <?php
                                $dot = '';
                                if (count(explode(' ', $row->description)) > 10)
                                {
                                    $dot = '...';
                                }
                                echo implode(' ', array_slice(explode(' ', $row->description), 0, 10)) . $dot;
                                ?></td>
                            <td><?php echo $status; ?></td>
                            <td>

                                <div class="hidden-sm hidden-xs btn-group">
                                    <?php
                                    if ($row->status == 1)
                                    {
                                        echo '<a title="Status" href="javascript:void(0);" class="blue status_button' . $row->id . '" onclick=updateStatus("helptopics",' . $row->id . ',1)><i class="ace-icon fa fa-plus bigger-130"></i></a>';
                                    }
                                    else
                                    {
                                        echo '<a title="Status" href="javascript:void(0);" class="red status_button' . $row->id . '" onclick=updateStatus("helptopics",' . $row->id . ',0)><i class="ace-icon fa fa-minus bigger-130"></i></a>';
                                    }
                                    ?>
                                    <?php
                                    // Check rights
                                    if (rights(99) == true)
                                    {
                                        ?>
                                        <a title="Edit" class="green" href="<?php
                                        echo base_url('admin/helptopics/edit/' . $this->common->encode($row->id
                                        ));
                                        ?>">
                                            <i class="ace-icon fa fa-pencil bigger-130"></i>
                                        </a>
                                        <?php
                                    }
                                    // Check rights
                                    if (rights(100) == true)
                                    {
                                        ?>
                                        <a  title="Delete" class="red" onclick="return delete_confirm();" href="<?php echo base_url('admin/helptopics/delete/' . $this->common->encode($row->id)); ?>">
                                            <i class="ace-icon fa fa-trash-o bigger-130"></i>
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
                                            if ($row->status == 1)
                                            {
                                                ?>
                                                <li>
                                                    <a href="javascript:void(0);" onclick="updateStatus('helptopics', '<?php echo $row->id; ?>', 1)" class="tooltip-info status_sm_button<?php echo $row->id; ?>" data-rel="tooltip" title="View">
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
                                                    <a href="javascript:void(0);" onclick="updateStatus('helptopics', '<?php echo $row->id; ?>', 1)" class="tooltip-info status_sm_button<?php echo $row->id; ?>" data-rel="tooltip" title="View">
                                                        <span class="red">
                                                            <i class="ace-icon fa fa-minus bigger-120"></i>
                                                        </span>
                                                    </a>
                                                </li>
                                            <?php }
                                            ?>

                                            <?php
                                            // Check rights
                                            if (rights(99) == true)
                                            {
                                                ?>
                                                <li>
                                                    <a  href="<?php echo base_url('admin/helptopics/edit/' . $this->common->encode($row->id)) ?>" class="tooltip-success" data-rel="tooltip" title="Edit">
                                                        <span class="green">
                                                            <i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
                                                        </span>
                                                    </a>
                                                </li>
                                                <?php
                                            }
                                            // Check rights
                                            if (rights(100) == true)
                                            {
                                                ?>
                                                ?>

                                                <li>
                                                    <a  href="<?php echo base_url('admin/helptopics/delete/' . $this->common->encode($row->id)); ?>" onclick="return delete_confirm();" class="tooltip-error" data-rel="tooltip" title="Delete">
                                                        <span class="red">
                                                            <i class="ace-icon fa fa-trash-o bigger-120"></i>
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
<?php echo form_close(); ?>
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
                                                                title: 'Help topicss | ShareAds Admin Panel',
                                                                text: 'Export to excel', extend: 'excel', footer: false,
                                                                exportOptions: {columns: [0, 1]}
                                                            }
                                                        ],
                                                        bAutoWidth: false, scrollX: true,
                                                        "aoColumns": [
                                                            null, null, {"bSortable": false},
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