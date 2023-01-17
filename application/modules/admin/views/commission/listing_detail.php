<div class="page-header">
    <h1>
        Commission
    </h1>
</div>
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
        <div class='alert alert-info'> Commission will be sent manually after <?php echo NO_OF_DAYS;?> days</div>
        <table id="dynamic-table" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>Advertiser Commission</th>
                    <th>Admin Commission</th>
                    <th>Overall Commission</th>
                    <th>Total Product Price</th>
                    <th>Advertiser Total</th>
                    <th>Publisher Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = $total = $gtotal = 0;
                ?>
                <tr>

                    <td><?php echo getSiteCurrencySymbol(); ?><?php echo number_format($advertiser['all_advertiser_commission']); ?></td>
                    <td><?php echo getSiteCurrencySymbol(); ?><?php echo number_format($publisher['overall_commission'] - $advertiser['all_advertiser_commission']); ?></td>
                    <td><?php echo getSiteCurrencySymbol(); ?><?php echo number_format($publisher['overall_commission']); ?></td>
                    <td><?php echo getSiteCurrencySymbol(); ?><?php echo number_format($publisher['overall_price']); ?></td>
                    <td><?php echo getSiteCurrencySymbol(); ?><?php echo number_format($advertiser['all_advertiser_commission']); ?></td>
                    <td><?php echo getSiteCurrencySymbol(); ?><?php echo $total = number_format($publisher['overall_price'] - $publisher['overall_commission']); ?> </td>
                    <td><?php echo $advertiser['is_paid'] == 0 && $publisher['is_paid'] == 0?'Pending':'Paid' ?></td>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="7" style="text-align:center;">&nbsp;</th>
                </tr>
                <tr>
                    <th colspan="3" style="text-align:center;">PENDING TOTAL</th>
                    <th  style="text-align:left;"><?php echo getSiteCurrencySymbol(); ?><?php echo number_format($publisher['overall_price'], 2) ?></th>
                    <th  style="text-align:left;"><?php echo getSiteCurrencySymbol(); ?><?php echo number_format($advertiser['all_advertiser_commission'], 2) ?></th>
                    <th  style="text-align:left;"><?php echo getSiteCurrencySymbol(); ?><?php echo number_format($total, 2) ?></th>
                    <th  style="text-align:left;"><?php echo $advertiser['is_paid'] == 0 && $publisher['is_paid'] == 0?'Pending':'Paid' ?></th>
                </tr>
                <tr>
                    <th colspan="3" style="text-align:center;">OVER ALL TOTAL PENDING SALE & COMMISSION</th>
                    <th  style="text-align:left;"><?php echo getSiteCurrencySymbol(); ?><?php echo number_format($publisher['overall_price'], 2) ?></th>
                    <th  style="text-align:left;"><?php echo getSiteCurrencySymbol(); ?><?php echo number_format($advertiser['all_advertiser_commission'], 2) ?></th>
                    <th  style="text-align:left;"><?php echo getSiteCurrencySymbol(); ?><?php echo number_format($total, 2) ?></th>
                    <th  style="text-align:left;"><?php echo $advertiser['is_paid'] == 0 && $publisher['is_paid'] == 0?'Pending':'Paid' ?></th>
                </tr>
                <?php
                if ($advertiser['is_paid'] == 0 && $publisher['is_paid'] == 0) {
                    ?>
                    <tr>
                        <th colspan="7" style="text-align:center;">
                            <a class="btn btn-primary btn-block" href="<?php echo base_url('admin/commission/pay_now/'.$month)?>">PAY NOW</a>
                        </th>

                    </tr>
<?php } ?>
            </tfoot>

        </table>
    </div>
</div>
<script src="<?php echo base_url('assets/admin/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/admin/js/jquery.dataTables.bootstrap.min.js') ?>"></script>
<script>
    jQuery(function () {
        $('#dynamic-table').dataTable({
            bAutoWidth: false,scrollX: true,
            "aoColumns": [
                {"bSortable": true},
                {"bSortable": true}, null, null, null, null,
                {"bSortable": true}
            ],
            "aaSorting": []
        });
    });
</script>
