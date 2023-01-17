<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css"/>
<div class="page-header">
    <h1>
        Feedback
        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            Manage Feedback
        </small>
    </h1>
</div><!-- /.page-header -->

<div class="row">
    <div class="col-xs-12">

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
        <div class="table-header"> Results for "All Feedbacks" </div>


        <table id="datatable" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th class="">Email</th>
                    <th class="">Phone</th>
                    <th class="">Message Status</th>
                    <th>View Message</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
<div id="modal_reply" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Reply Customer</h4>
            </div>
            <div id="modal_reply_body_ajax" class="modal-body">

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

    $(function ()
    {
        table = $('#datatable').DataTable({
            "responsive": true,
            "autoWidth": false,
            "scrollX": true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "dom": '<"top"B<"clear">f>rt<"bottom"lp><"clear">',
            "language": {processing: '<img src="<?php echo base_url(); ?>assets/admin/img/loading_icon.gif" /> '},
                buttons: [
                    {
                        title: 'Feedback | ShareAds Admin Panel',
                        text: 'Export to excel', extend: 'excel', footer: false,
                        exportOptions: {columns: [0, 1, 2, 3]}
                    }
                ],
            "ajax": {
                "url": "<?php echo base_url('admin/feedback/pagination'); ?>",
                "type": "POST"
            },"columnDefs": [
                {
                    "targets": [3,4,5],
                    "orderable": false,
                },
            ],
        });
    });

    function reply_user(_id)
    {
        $('#modal_reply_body_ajax').html('');
        $.ajax({
            url: '<?php echo base_url('admin/feedback/reply_user_ajax'); ?>/' + _id,
            type: 'GET',
            success: function (data)
            {
                data = JSON.parse(data);
                if (data.status == 1)
                {
                    $('#modal_reply').modal('show');
                    $('#modal_reply_body_ajax').html(data.form);
                }
                else
                {
                    $('#modal_reply').modal('hide');
                    $('#modal_reply_body_ajax').html('');
                }
            }
        });
        return false;
    }

//    jQuery(function ()
//    {
//        try
//        {
//            $('.table').dataTable({
//                dom: '<"top"B<"clear">f>rt<"bottom"lp><"clear">',
//                buttons: [
//                    {
//                        title: 'Feedback | ShareAds Admin Panel',
//                        text: 'Export to excel', extend: 'excel', footer: false,
//                        exportOptions: {columns: [0, 1, 2, 3]}
//                    }
//                ],
//                bAutoWidth: false, scrollX: true,
//                "aoColumns": [
//                    {"bSortable": false},
//                    null, null, null, null,
//                    {"bSortable": false}
//                ],
//                "aaSorting": []
//
//            });
//        }
//        catch (e)
//        {
//            console.log(e);
//        }

//    });
</script>