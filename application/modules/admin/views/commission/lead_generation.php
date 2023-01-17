<div class="page-header">
    <h1>
        Lead Generation Commission
<!--        <small>
           <i class="ace-icon fa fa-angle-double-right"></i>

       </small>-->
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
        <div class="space-4"></div>
        <div class="table-header"> Results for "All Lead Generation Commission" </div>
        <table id="dynamic-table" class="table table-striped table-bordered table-hover">

            <thead>
                <tr>
                    <th>Month</th>
                    <th>Advertiser Total</th>
                    <th>Status</th>

                </tr>
            </thead>
            <tbody>
                <?php
                $totalSales = $total_orders = $totalComm = $totalAdvComm = 0;
                foreach ($all_orders as $ordr) {
                    ?>
                    <tr>
                        <td><?php echo $ordr['monthName']; ?></td>



                        <td><?php echo getSiteCurrencySymbol(); ?><?php echo number_format($ordr['all_advertiser_commission']); ?></td>


                        <?php
                        $totalAdvComm = $totalAdvComm + $ordr['all_advertiser_commission'];
                        ?>

                        <td>
                            <?php
                            if ($ordr['status'] == 0) {
                                echo '<span class="label label-warning">Pending</span>';
                            } elseif ($ordr['status'] == 1) {
                                echo '<span class="label label-success">Confirmed</span>';
                            } else {
                                echo '<span class="label label-danger">Canceled</span>';
                            }
                            ?>
                        </td>
                    </tr>
<?php } ?>


            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" style="text-align:center;">&nbsp;</th>
                </tr>
                <tr>
                    <th colspan="" style="text-align:center;">TOTAL</th>
                    <th  style="text-align:left;"><?php echo getSiteCurrencySymbol(); ?><?php echo number_format($totalAdvComm, 2) ?></th>
                    <th  style="text-align:left;"></th>
                </tr>

            </tfoot>
        </table>
    </div>
</div>
<!-- page specific plugin scripts -->

<script src="<?php echo base_url('assets/admin/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/admin/js/jquery.dataTables.bootstrap.min.js') ?>"></script>
<script>
    jQuery(function () {
        $('#dynamic-table').dataTable({
            bAutoWidth: false, scrollX: true,
            "aoColumns": [
                {"bSortable": true},
                {"bSortable": true},
                {"bSortable": true}
            ],
            "aaSorting": []

        });


    });
</script>
