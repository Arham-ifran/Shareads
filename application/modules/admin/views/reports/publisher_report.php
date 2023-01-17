<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css"/>
<div class="page-header">
    <h1>
        Publisher Report
<!--        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>

        </small>-->
    </h1>
</div><!-- /.page-header -->

<div class="row">
    <div class="col-xs-12">

        <form class="form-inline" role="form" id="searchListing" method="post" action="<?php echo base_url('admin/reports/publisher') ?>">
            <input type="text" class="form-control date-picker" name="date_from" id="date_from" placeholder="Search Date From" value="<?php echo $date_from ?>">
            <input type="text" class="form-control date-picker" name="date_to" id="date_to" placeholder="Search Date To" value="<?php echo $date_to ?>">

            <button type="submit" class="btn btn-danger">Search</button>
            <button type="button" class="btn btn-info" onclick="$('#date_from').val('');$('#date_to').val('');$('form').submit();">Clear Filters</button>
        </form>


    </div>
    <div class="clearfix space-8"></div>
    <div class="col-sm-8">
        <?php if (count($result->result()) > 0)
        {
            ?>
            <a  class="btn btn-info"  target="_blank" href="<?php echo base_url('admin/reports/print_publisher_report') ?>"> Print Report </a>
<?php } ?>

    </div>
    <div class="col-sm-4">
    </div>

</div>
<div class="clearfix space-8"></div>

<div class="row">
    <div class="col-xs-12">

        <div class="space-4"></div>
        <div class="table-header"> Results for "Publishers"</div>

        <table id="dynamic-table" class="table table-striped table-bordered table-hover">

            <thead>
                <tr>
                    <th>User Name</th>
                    <th>Account Type</th>
                    <th class="">Email</th>
                    <th class="">Phone</th>
                    <th class="">Country</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 0;

                foreach ($result->result() as $row)
                {
                    if ($row->status == 1)
                    {
                        $status = '<span class="label label-sm label-info status_label' . $row->user_id . '">Active</span>';
                    }
                    else
                    {
                        $status = '<span class="label label-sm label-danger status_label' . $row->user_id . '">Inactive</span>';
                    }
                    ?>
                    <tr>
                        <td><?php echo ucwords($row->full_name); ?></td>
                        <td><?php echo ucwords($row->user_type); ?></td>
                        <td  class=""><?php echo $row->email ?></td>
                        <td  class=""><?php echo $row->phone ?></td>
                        <td  class=""><?php echo $row->country ?></td>
                        <td><?php echo $status; ?></td>

                    </tr>
    <?php
}
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
                                    title: 'Publisher Report | ShareAds Admin Panel',
                                    text: 'Export to excel', extend: 'excel', footer: false,
                                    exportOptions: {columns: [0, 1, 2, 3, 4, 5]}
                                }
                            ],
                            bAutoWidth: false, scrollX: true,
                            "aoColumns": [
                                {"bSortable": true},
                                {"bSortable": true}, null, null, null,
                                {"bSortable": true}
                            ],
                            "aaSorting": []
                        });
                    } catch (e)
                    {
                        console.log(e);
                    }
                });
</script>