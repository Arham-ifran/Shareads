<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
?>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css"/>
<div class="page-header">
    <h1>
        Invoice Management
    </h1>
</div><!-- /.page-header -->
<div class="row">
    <div class="col-xs-12">
    </div>
    <div class="clearfix space-8"></div>
    <div class="col-sm-8">
    </div>
    <div class="col-sm-4">
    </div>
</div>
<div class="clearfix space-8"></div>
<div class="row">
    <div class="col-xs-12">
        <form class="form-inline" role="form" id="searchListing" method="post" action="<?php echo base_url('admin/commission/manage_invoices') ?>">
            <input type="text" class="form-control date-picker" name="date_from" id="date_from" placeholder="Search Date From" value="<?php echo $date_from ?>">
            <input type="text" class="form-control date-picker" name="date_to" id="date_to" placeholder="Search Date To" value="<?php echo $date_to ?>">
            <button type="submit" class="btn btn-danger">Search</button>
            <button type="button" class="btn btn-info" onclick="$('#date_from').val(''); $('#date_to').val(''); $('form').submit();">Clear Filters</button>
        </form>
    </div>
    <div class="col-xs-12">
        <div class="space-4"></div>
        <div class="table-header"> Results for "All Invoices" </div>
        <table id="dynamic-table" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>Publisher Name</th>
                    <th>Email</th>
                    <th>Invoice generated date</th>
                    <th>Due date</th>
                    <th>Invoice Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($all_invoices as $invoice)
                {
                    $total += (double) get_currency_rate($invoice['invoice_amount'], $invoice['invoice_currency'], CURRENCY);
                    ?>
                    <tr>
                        <td><?php echo ucfirst($invoice['full_name']); ?></td>
                        <td><?php echo $invoice['email']; ?></td>
                        <td><?php echo date('d-M, Y', $invoice['created']); ?></td>
                        <td><?php echo date('d-M, Y', strtotime(date("Y-m-d H:i:s", $invoice['created']) . '+7 day')); ?></td>
                        <td><?php echo getSiteCurrencySymbol('', $invoice['invoice_currency']) . ' ' . number_format($invoice['invoice_amount'], 2); ?></td>
                        <td>
                            <?php
                            if ($invoice['due_date'] < (time() . '+1 day') && $invoice['status'] == 0)
                            {
                                echo '<span class="label label-danger">Over Due</span>';
                            }
                            else
                            {
                                echo $invoice['status'] == 0 ? '<span class="label label-dafault">Not Paid</span>' : '<span class="label label-success">Paid</span>';
                            }
                            ?>
                        </td>
                        <td><a class="label label-primary" href="<?php echo base_url('admin/commission/view_invoice/' . $this->common->encode($invoice['invoice_id'])) ?>">View invoice</a></td>
                    </tr>
                    <?php
                } if (sizeof($all_invoices) == 0)
                {
                    echo '<tr><td colspan="7" style="text-align:center;">No Invoice Record Found</td></tr>';
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="8" style="text-align:center;">&nbsp;</th>
                </tr>
                <tr>
                    <th style="text-align:center;">TOTAL</th>
                    <th  style="text-align:left;"></th>
                    <th  style="text-align:left;"></th>
                    <th  style="text-align:left;"></th>
                    <th  style="text-align:left;"><?php echo getSiteCurrencySymbol(); ?><?php echo number_format($total, 2) ?></th>
                    <th  style="text-align:left;"></th>
                    <th  style="text-align:left;"></th>
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

                $(function () {
                    try {
                        $('.table').dataTable({
                            dom: '<"top"B<"clear">f>rt<"bottom"lp><"clear">',
                            buttons: [
                                {
                                    title: 'Invoices | ShareAds Admin Panel',
                                    text: 'Export to excel', extend: 'excel', footer: false,
                                    exportOptions: {columns: [0, 1, 2, 3, 4, 5]}
                                }
                            ],
                            "aoColumns": [null, null, null, null, null, {"bSortable": false}, {"bSortable": false}],
                            "aaSorting": []
                        });
                    } catch (e)
                    {

                    }
                });

</script>
