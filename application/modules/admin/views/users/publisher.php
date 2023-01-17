<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css"/>
<div class="page-header">
    <h1>
        Publisher
        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            Manage Publisher
        </small>
    </h1>
</div><!-- /.page-header -->

<div class="row">
    <div class="col-xs-12">
        <?php
        if (rights(83) == true)
        {
            ?>
            <div class="col-sm-8"></div>
            <div class="col-sm-4">
                <?php /* <a href="<?php echo base_url('admin/users/add_publisher') ?>"><button type="button" class="btn btn-primary"   style="float: right; margin-right: 15px;">Add New</button></a> */ ?>
                <a href="<?php echo base_url('admin/users/add_publisher_with_invitation') ?>"><button type="button" class="btn btn-primary"   style="float: right; margin-right: 15px;">Invite new publisher</button></a>
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
        <div class="table-header"> Results for "All Publishers"</div>

        <table id="datatable" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>User Name</th>
                    <th class="">Email</th>
                    <th>Status</th>
                    <th>Action</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody>
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

    $(function ()
    {
        table = $('#datatable').DataTable({
            "responsive": true,
            "autoWidth": false,
            "scrollX": true,
            "processing": true,
            "serverSide": true,
            "order": [],
            dom: '<"top"B<"clear">f>rt<"bottom"lp><"clear">',
            "language": {processing: '<img src="<?php echo base_url(); ?>assets/admin/img/loading_icon.gif" /> '},
            buttons: [
                {
                    title: 'Publisher Users | ShareAds Admin Panel',
                    text: 'Export to excel', extend: 'excel', footer: false,
                    exportOptions: {columns: [0, 1, 2]}
                }
            ],
            "ajax": {
                "url": "<?php echo base_url('admin/users/pagination_pub'); ?>",
                "type": "POST"
            },
            "columnDefs": [
                {
                    "targets": [2,3,4],
                    "orderable": false,
                },
            ],
        });
    });

//                                            jQuery(function () {
//                                                $('.table').dataTable({
//                                                    bAutoWidth: false, scrollX: true,
//                                                    dom: '<"top"B<"clear">f>rt<"bottom"lp><"clear">',
//                                                    buttons: [
//                                                        {
//                                                            title: 'Publisher Users | ShareAds Admin Panel',
//                                                            text: 'Export to excel', extend: 'excel', footer: false,
//                                                            exportOptions: {columns: [0,1,2,3]}
//                                                        }
//                                                    ],
//                                                    "aoColumns": [
//                                                        {"bSortable": true},
//                                                        null, null, null, null,
//                                                        {"bSortable": false}
//                                                    ],
//                                                    "aaSorting": []
//
//                                                });
//
//
//                                            });
</script>