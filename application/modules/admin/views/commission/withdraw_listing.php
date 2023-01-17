<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
?>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css"/>
<div class="page-header">
    <h1>
        Withdraw requests
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
        <div class="table-header"> Results for "All Withdraws" </div>
        <table id="dynamic-table" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>Request Time</th>
                    <th>Affiliator Name</th>
                    <th>Payment Type</th>
                    <th>Amount Requested</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($all_withdraws as $withdrawals)
                {
                    $total += (double) get_currency_rate($withdrawals['amount_requested'], $withdrawals['currency'], CURRENCY);
                    ?>
                    <tr>
                        <td><?php echo date('d-M, Y', $withdrawals['created']); ?></td>
                        <td><?php echo ucfirst($withdrawals['affiliater_name']); ?></td>
                        <td><?php echo ucfirst($withdrawals['payment_type_text']); ?> <a data-toggle="modal" data-target="#<?php echo $this->common->encode($withdrawals['id']); ?>"><i class="fa fa-info-circle" aria-hidden="true"></i></a></td>
                        <td><?php echo getSiteCurrencySymbol('', $withdrawals['currency']); ?><?php echo number_format($withdrawals['amount_requested'], 2); ?></td>
                        <td><?php echo $withdrawals['status'] == 0 ? 'Pending' : '<span class="label label-success">Paid</span>'; ?> </td>
                        <td>
                            <?php
                            if ($withdrawals['status'] == 0)
                            {
                                ?>
                                <a class="label label-primary" href="<?php echo base_url('admin/commission/detail_withdraw/' . $this->common->encode($withdrawals['id'])) ?>">Pay Now</a>
                                <?php
                            }
                            else
                            {
                                ?>
                                <a class="label label-primary" href="<?php echo base_url('admin/commission/view_withdraw/' . $this->common->encode($withdrawals['id'])) ?>">view information</a>
                                <?php
                            }
                            ?>
                            <div id="<?php echo $this->common->encode($withdrawals['id']); ?>" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Payment details</h4>
                                        </div>
                                        <div class="modal-body">
                                            <table class="table table-bordered"><tbody>
                                                    <tr>
                                                        <th>Affiliator Name</th>
                                                        <td><?php echo ucfirst($withdrawals['affiliater_name']); ?></td>
                                                    </tr>
                                                    <?php
                                                    if ($withdrawals['payment_type'] == 2)
                                                    {
                                                        ?>
                                                        <tr>
                                                            <th>Account Title</th>
                                                            <td><?php echo ($withdrawals['account_holder_name'] <> '') ? $withdrawals['account_holder_name'] : 'N/A'; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Account Number</th>
                                                            <td><?php echo ($withdrawals['account_number'] <> '') ? $withdrawals['account_number'] : 'N/A'; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>IBAN Code</th>
                                                            <td><?php echo ($withdrawals['iban_code'] <> '') ? $withdrawals['iban_code'] : 'N/A'; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Swift Code</th>
                                                            <td><?php echo ($withdrawals['swift_code'] <> '') ? $withdrawals['swift_code'] : 'N/A'; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>SORT Code</th>
                                                            <td><?php echo ($withdrawals['sort_code'] <> '') ? $withdrawals['sort_code'] : 'N/A'; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Bank Name</th>
                                                            <td><?php echo ($withdrawals['bank_name'] <> '') ? ucfirst($withdrawals['bank_name']) : 'N/A'; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Bank Address</th>
                                                            <td><?php echo ($withdrawals['bank_address'] <> '') ? $withdrawals['bank_address'] : 'N/A'; ?></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                        <tr>
                                                            <th>Paypal Email ID</th>
                                                            <td><?php echo $withdrawals['paypal_email']; ?></td>
                                                        </tr>
                                                    <?php }
                                                    ?>
                                                </tbody></table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="8" style="text-align:center;">&nbsp;</th>
                </tr>
                <tr>
                    <th style="text-align:center;">TOTAL</th>
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
                        title: 'Withdraws | ShareAds Admin Panel',
                        text: 'Export to excel', extend: 'excel', footer: false,
                        exportOptions: {columns: [0, 1, 2, 3, 4]}
                    }
                ],
                "aoColumns": [null, null, null, null, null, {"bSortable": false}],
                "aaSorting": []
            });
        } catch (e)
        {

        }
    });

</script>

