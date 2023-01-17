<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css"/>
<div class="page-header">
    <h1>
        Publisher Commission Report
<!--        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>

        </small>-->
    </h1>
</div><!-- /.page-header -->

<div class="row">
    <div class="col-xs-12">

        <form class="form-inline" role="form" id="searchListing" method="post" action="<?php echo base_url('admin/reports/publisher_commissions') ?>">
            <input type="text" class="form-control date-picker" name="date_from" id="date_from" placeholder="Search Date From" value="<?php echo $date_from ?>">
            <input type="text" class="form-control date-picker" name="date_to" id="date_to" placeholder="Search Date To" value="<?php echo $date_to ?>">

            <button type="submit" class="btn btn-danger">Search</button>
            <button type="button" class="btn btn-info" onclick="$('#date_from').val('');$('#date_to').val('');$('form').submit();">Clear Filters</button>
        </form>


    </div>
<div class="clearfix space-8"></div>
    <div class="col-sm-8">
        <?php if(count($result->result()) > 0)
        {?>
                <a  class="btn btn-info"  target="_blank" href="<?php echo base_url('admin/reports/print_publisher_commissions_report') ?>"> Print Report</a>
        <?php }?>

                    </div>
                    <div class="col-sm-4">
                    </div>

</div>
<div class="clearfix space-8"></div>

<div class="row">
    <div class="col-xs-12">
        <div class="space-4"></div>
        <div class="table-header"> Results for "All Publishers Commissions"</div>
        <table id="dynamic-table" class="table table-striped table-bordered table-hover">

            <thead>
                <tr>
                    <th>Publisher</th>
                    <th>Listing Name</th>
                    <th>Category</th>
                    <th>Is Confirmed</th>
                    <th>Commission</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 0;
                $total = 0;
                    foreach ($result->result() as $row) {

                        ?>
                        <tr>
                            <td><?php echo $row->full_name; ?></td>
                            <td><?php echo $row->product_name; ?></td>
                            <td><?php echo $row->category_name; ?></td>
                            <td><?php echo (($row->is_confirmed)? '<span class="label label-success">Confirmed</span>' : '<span class="label label-danger">Not Confirmed</span>'); ?></td>
                            <td><?php echo getSiteCurrencySymbol(); ?><?php echo number_format(get_currency_rate($row->total_commission,$row->p_cy,CURRENCY),2); ?></td>
                            <td><?php echo date('d M, Y', $row->created); ?></td>
                            <?php
                                $total = $total+get_currency_rate($row->total_commission,$row->p_cy,CURRENCY);
                            ?>

                        </tr>
                        <?php
                    }
                    ?>



            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" style="text-align:center;">Total Commission</th>
                    <th  style="text-align:left;"><?php echo getSiteCurrencySymbol(); ?><?php echo number_format($total,2) ?></th>
                    <th></th>
                </tr>

            </tfoot>


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
                                    title: 'Publisher Commission Report | ShareAds Admin Panel',
                                    text: 'Export to excel', extend: 'excel', footer: false,
                                    exportOptions: {columns: [0, 1, 2, 3, 4, 5]}
                                }
                            ],
                            bAutoWidth: false,
                            scrollX: true,
                            "aoColumns": [null, null, null, null, null, null],
                            "aaSorting": []

                        });
                    } catch (e)
                    {
                        console.log(e);
                    }

                });
</script>