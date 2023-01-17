<!-- bootstrap & fontawesome -->
<link rel="stylesheet" href="<?php echo base_url('assets/admin/css/bootstrap.min.css'); ?>" />
<style>
    .page-header h2 {
        font-size:18px;
        text-align: center;
        color: #333 !important;
    }

    .table-bordered
    {
        border: 1px solid #000 !important;

    }

    thead th
    {
        font-weight:500;
        text-align: center; font-size:14px;
        padding: 5px;
        background-color: #cccccc;
        border: 1px solid #666 !important;
        border-collapse: collapse;

    }


    td{text-align: center;padding: 5px;font-size:13px}
</style>
<div class="page-header">
    <h2>
        Admin Commission Report

    </h2>
</div><!-- /.page-header -->

<div class="row">
    <div class="col-xs-12">

        <table id="" class="table table-striped table-bordered table-hover">

            <thead>
                <tr>
                    <th>Publisher</th>
                    <th>Listing Name</th>
                    <th>Category</th>
                     <th>Is Confirmed</th>
                    <th>Price</th>
                    <th>Commission</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($result->result() as $row) {
                    ?>
                    <tr>
                        <td><?php echo $row->full_name; ?></td>
                        <td><?php echo $row->product_name; ?></td>
                        <td><?php echo $row->category_name; ?></td>
 <td><?php echo (($row->is_confirmed)? '<span class="label label-success">Confirmed</span>' : '<span class="label label-danger">Not Confirmed</span>'); ?></td>
                            <td><?php echo getSiteCurrencySymbol(); ?><?php echo number_format(get_currency_rate($row->price,$row->p_cy,CURRENCY),2); ?></td>
                        <td><?php echo getSiteCurrencySymbol(); ?><?php echo number_format(get_currency_rate($row->total_commission,$row->p_cy,CURRENCY) - get_currency_rate($row->advertiser_commission,$row->p_cy,CURRENCY),2); ?></td>


                            <td><?php echo date('d M, Y', $row->created); ?></td>
                            <?php
                            $comm = get_currency_rate($row->total_commission,$row->p_cy,CURRENCY) - get_currency_rate($row->advertiser_commission,$row->p_cy,CURRENCY);
                                $total = $total+$comm;

                            ?>


                    </tr>
    <?php
}
?>



            </tbody>
            <tfoot>
                <tr>
                    <th colspan="6" style="text-align:center;">&nbsp;</th>


                </tr>
                 <tr>
                    <th colspan="6" style="text-align:center;">Total Commission</th>
                    <th  style="text-align:center;"><?php echo getSiteCurrencySymbol(); ?><?php echo number_format($total,2) ?></th>

                </tr>

            </tfoot>

        </table>
    </div>
</div>

<!-- page specific plugin scripts -->
