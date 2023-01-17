<?php
$withdraw_data = $withdraw_data[0];
//dd($withdraw_data);
?>

<div class="page-header">
    <h1>
        Withdraw Commission
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

        <table id="dynamic-table" class="table table-striped table-bordered table-hover">
            <tbody>
                <tr>
                    <th>Advertiser Name</th>
                    <td><?php echo ucfirst($withdraw_data['first_name'] . ' ' . $withdraw_data['last_name']); ?></td>
                </tr>
                <tr>
                    <th>Advertiser Commission</th>
                    <td><?php echo getSiteCurrencySymbol('',$withdraw_data['currency']); ?><?php echo number_format($withdraw_data['amount_requested'],2); ?></td>
                </tr>
                <tr>
                    <th>Payment Type</th>
                    <td><?php echo ucfirst(($withdraw_data['u_payment_type'] == 1) ? 'Paypal' : 'WireTransfer'); ?></td>
                </tr>
                <?php
                if ($withdraw_data['u_payment_type'] == 1)
                {
                    ?>
                    <tr>
                        <th>Paypal Email</th>
                        <td><?php echo $withdraw_data['paypal_email']; ?></td>
                    </tr>
                    <?php
                }
                else
                {
                    ?>
                    <tr>
                        <th>Account Title</th>
                        <td><?php echo ($withdraw_data['account_holder_name'] <> '') ? $withdraw_data['account_holder_name'] : 'N/A'; ?></td>
                    </tr>
                    <tr>
                        <th>Account Number</th>
                        <td><?php echo ($withdraw_data['account_number'] <> '') ? $withdraw_data['account_number'] : 'N/A'; ?></td>
                    </tr>
                    <tr>
                        <th>IBAN Code</th>
                        <td><?php echo ($withdraw_data['iban_code'] <> '') ? $withdraw_data['iban_code'] : 'N/A'; ?></td>
                    </tr>
                    <tr>
                        <th>Swift Code</th>
                        <td><?php echo ($withdraw_data['swift_code'] <> '') ? $withdraw_data['swift_code'] : 'N/A'; ?></td>
                    </tr>
                    <tr>
                        <th>SORT Code</th>
                        <td><?php echo ($withdraw_data['sort_code'] <> '') ? $withdraw_data['sort_code'] : 'N/A'; ?></td>
                    </tr>
                    <tr>
                        <th>Bank Name</th>
                        <td><?php echo ($withdraw_data['bank_name'] <> '') ? ucfirst($withdraw_data['bank_name']) : 'N/A'; ?></td>
                    </tr>
                    <tr>
                        <th>Bank Address</th>
                        <td><?php echo ($withdraw_data['bank_address'] <> '') ? $withdraw_data['bank_address'] : 'N/A'; ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td><?php echo $withdraw_data['status'] == 0 ? 'Pending' : '<span class="label label-success">Paid</span>'; ?></td>
                    </tr>

                <?php } ?>

            </tbody>
            <tfoot>
                <tr>
                    <th colspan="7" style="text-align:center;">&nbsp;</th>
                </tr>

                <?php
                if ($withdraw_data['u_payment_type'] == 1 && $withdraw_data['status'] == 0)
                {
                    ?>
                    <tr>
                        <th colspan="7" style="text-align:center;">
                            <a class="btn btn-primary btn-block" href="<?php echo base_url('admin/commission/withdraw_paypal/' . $this->common->encode($withdraw_data['id'])) ?>">PAY NOW</a>
                        </th>
                    </tr>
                <?php
                }
                else if ($withdraw_data['u_payment_type'] == 2 && $withdraw_data['status'] == 0)
                {
                    ?>
                    <tr>
                        <th colspan="7" style="text-align:center;">
                            <a class="btn btn-primary btn-block" href="<?php echo base_url('admin/commission/make_withdraw_wiretransfer/' . $this->common->encode($withdraw_data['id'])) ?>">Marked as Payed <small>for wiretransfer</small></a>
                        </th>
                    </tr>


                <?php
                }
                else
                {
                    ?>
                    <tr>
                        <th colspan="7" style="text-align:center;">
                            <a class="btn btn-danger btn-block" href="<?php echo base_url('admin/commission/manage_withdraw/'); ?>">Go Back</a>
                        </th>
                    </tr>  
<?php }
?>
            </tfoot>

        </table>
    </div>
</div>
