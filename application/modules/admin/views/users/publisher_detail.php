<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css"/>
<div class="page-header">
    <h1>
        Publisher
        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            Publisher  Details
        </small>
    </h1>
</div><!-- /.page-header -->



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

        <table id="dynamic-table" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Product Name</th>
                    <th class="">Advertiser Name</th>
                    <th>Date</th>
                    <th>Commission</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows() > 0)
                {
                    $total    = 0;
                    $comTotal = 0;
                    $PubTotal = 0;
                    foreach ($result->result() as $row)
                    {
                        $pro_currecny = getVal('currency', 'c_products', array('product_id' => $row->product_id));
                        ?>
                        <tr id="users_<?php echo $row->user_id; ?>">
                            <td><?php echo $row->order_id; ?></td>
                            <td><?php echo $row->product_name ?></td>
                            <td class=""><?php echo $row->full_name ?></td>


                            <td><?php echo date('d M, Y', $row->created); ?></td>

                                <?php if ($usertype == 1)
                                { ?>
                                <td><?php echo getSiteCurrencySymbol(); ?><?php
                                    echo number_format(get_currency_rate($row->advertiser_commission, $pro_currecny, CURRENCY), 2);
                                    $total    = $total + get_currency_rate($row->price, $pro_currecny, CURRENCY);
                                    $comTotal = $comTotal + get_currency_rate($row->advertiser_commission, $pro_currecny, CURRENCY);
                                    ?></td>
                        <?php }
                        else if ($usertype == 2)
                        { ?>
                                <td><?php echo getSiteCurrencySymbol(); ?><?php
                echo number_format(get_currency_rate($row->total_commission, $pro_currecny, CURRENCY), 2);
                $total    = $total + get_currency_rate($row->price, $pro_currecny, CURRENCY);
                $comTotal = $comTotal + get_currency_rate($row->total_commission, $pro_currecny, CURRENCY);
                ?></td>
        <?php } ?>
                        </tr>
        <?php
    }
    ?>
<?php }
?>
            </tbody>

            <tfoot>
                <tr>
                    <th colspan="5" style="text-align:center;">&nbsp;</th>


                </tr>
                <tr>
                    <th colspan="4" style="text-align:center;">Total</th>
                    <th><?php echo getSiteCurrencySymbol(); ?><?php echo number_format($comTotal, 2) ?></th>
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
    jQuery(function ()
    {

        try
        {
            $('.table').dataTable({
                bAutoWidth: false, scrollX: true,
                dom: '<"top"B<"clear">f>rt<"bottom"lp><"clear">',
                buttons: [
                    {
                        title: 'Publisher Detials Users | ShareAds Admin Panel',
                        text: 'Export to excel', extend: 'excel', footer: false,
                        exportOptions: {columns: [0, 1, 2, 3, 4]}
                    }
                ],
                "aoColumns": [
                    {"bSortable": true},
                    null, null, null,
                    {"bSortable": true}
                ],
                "aaSorting": []

            });
        }
        catch (e)
        {
            console.log(e);
        }

    });
</script>