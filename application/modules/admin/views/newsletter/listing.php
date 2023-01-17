<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css"/>
<div class="page-header">
    <h1>
        Newsletter
        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            Manage Newsletter
        </small>
    </h1>
</div><!-- /.page-header -->

<div class="row">
    <div class="col-xs-12">

        <div class="col-sm-6"></div>
        <div class="col-sm-6">

            <a href="<?php echo base_url('admin/newsletter/send_newsletter') ?>"><button type="button" class="btn btn-success"   style="float: right; margin-right: 15px;">Send Newsletter</button></a>

            <a href="<?php echo base_url('admin/newsletter/export_csv') ?>"><button type="button" class="btn btn-primary"   style="float: right; margin-right: 15px;">Export CSV</button></a>

            <a href="<?php echo base_url('admin/newsletter/export_excel') ?>"><button type="button" class="btn btn-primary"   style="float: right; margin-right: 15px;">Export Excel</button></a>

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

        <div class="alert" id="formErrorMsg" style="display: none;">
        </div>
        <div class="space-4"></div>
        <div class="table-header"> Results for "All Newsletters"</div>
        <?php /* ?>
          <table id="dynamic-table" class="table table-striped table-bordered table-hover">


          <thead>
          <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Action</th>
          </tr>
          </thead>
          <tbody>
          <?php
          if ($result->num_rows() > 0)
          {
          foreach ($result->result() as $row)
          {
          ?>
          <tr id="newsletter_<?php echo $row->id; ?>">
          <td><?php echo ucwords($row->full_name); ?></td>
          <td><?php echo $row->email; ?></td>
          <td>

          <?php
          // Check rights
          if (rights(31) == true)
          {
          ?>
          <a  title="Delete" class="red" onclick="return delete_confirm();" href="<?php echo base_url('admin/newsletter/delete/' . $this->common->encode($row->id) . '/' . $row->type); ?>" onclick="return delete_confirm();">
          <i class="ace-icon fa fa-trash-o bigger-130"></i>
          </a>
          <?php } ?>

          </td>
          </tr>
          <?php
          }
          }
          ?>
          </tbody>
          </table>
          <?php */ ?>

        <table id="datatable" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Action</th>
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
            "dom": '<"top"B<"clear">f>rt<"bottom"lp><"clear">',
            "language": {processing: '<img src="<?php echo base_url(); ?>assets/admin/img/loading_icon.gif" /> '},
            buttons: [
                {
                    title: 'Newsletter | ShareAds Admin Panel',
                    text: 'Export to excel', extend: 'excel', footer: false,
                    exportOptions: {columns: [0, 1]}
                }
            ],
            "ajax": {
                "url": "<?php echo base_url('admin/newsletter/pagination'); ?>",
                "type": "POST"
            }, "columnDefs": [
                {
                    "targets": [2],
                    "orderable": false,
                },
            ],
        });
    });

//                            jQuery(function () {
//                                try {
//                                    $('.table').dataTable({
//                                        dom: '<"top"B<"clear">f>rt<"bottom"lp><"clear">',
//                                        buttons: [
//                                            {
//                                                title: 'Newsletter | ShareAds Admin Panel',
//                                                text: 'Export to excel', extend: 'excel', footer: false,
//                                                exportOptions: {columns: [0, 1]}
//                                            }
//                                        ],
//                                        bAutoWidth: false, scrollX: true,
//                                        "aoColumns": [
//                                            {"bSortable": true},
//                                            null,
//                                            {"bSortable": false}
//                                        ],
//                                        "aaSorting": []
//
//                                    });
//                                } catch (e)
//                                {
//                                    console.log(e);
//                                }
//
//
//                            });
</script>