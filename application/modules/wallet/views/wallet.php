<?php $this->load->view('includes/profile_info'); ?>
<?php
$totalPendingPayment = (isset($pendingwithdrawRequestsSUM['total_amount'])) ? $pendingwithdrawRequestsSUM['total_amount'] : 0;
$totalsuccessPayment = (isset($successwithdrawRequestsSUM['total_amount'])) ? $successwithdrawRequestsSUM['total_amount'] : 0;

//echo '->'.$totalsuccessPayment.'<br>';
//echo '->'.$totalPendingPayment.'<br>';
//echo '->'.$totalCommission.'<br>';
//echo '->'.abs(abs($totalCommission-($totalPendingPayment))-$totalsuccessPayment).'<br>';

$limit_withdraw = (double)unserialize(LIMIT_WITHDRAW)[$this->session->userdata('currency')];

?>
<section class="container" style="padding-top: 45px">
    <div class="heading_links clearfix">
        <h3 class="main_heading">Wallet</h3>
    </div>
    <div class="row">
        <div class="col-md-9 col-sm-12">
            <h1>My Wallet</h1>
            <?php
            if (!empty($lastWithdrawAmount))
            {
                ?>
                <br>
                <h4>Your last successfully withdrawn commission amount is <span class="blue"><?php echo getSiteCurrencySymbol('', $lastWithdrawAmount['wd_currency']); ?><?php echo number_format($lastWithdrawAmount['lastWithdrawAmount'], 2); ?></span>  </h4>
            <?php } ?>
            <br><br>
            Current balance: <span class="blue"><?php echo getSiteCurrencySymbol('', $this->session->userdata('currency')); ?><?php echo number_format($totalCommission, 2); ?></span>  <br>
            <?php
            if ((double) $totalCommission >= (double) $limit_withdraw)
            {
                ?>
                <a class=" pull-right" href="<?php echo base_url('wallet/request_refund'); ?>" onclick="if (confirm('Are you sure you want to withdraw your commission.'))
                            commentDelete(1);
                        return false"><i class="fa fa-outdent" aria-hidden="true"></i> Request withdraw</a>
               <?php } ?></h5> 
            <br>
            <h6>Minimum withdrawal amount of at least <?php echo getSiteCurrencySymbol('', $this->session->userdata('currency')); ?><?php echo number_format($limit_withdraw, 2); ?> </h6>            
            <hr>
            <div class="white_box">
                <div class="filter_heading"> <a href="#sales" class="collapse_arrow" data-toggle="collapse"></a>
                    <h5><i class="fa fa-money fa-fw"></i>Withdraw history <?php //echo getSiteCurrencySymbol('',$this->session->userdata('currency'));   ?><?php //echo number_format($successWithdrawn, 2);   ?></h5>
                </div>
                <div class="collapse in"  id="sales">
                    <div class="table-responsive table-height">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th width="2%">#</th>
                                    <th width="13%">Date & Time</th>
                                    <th width="15%">Amount</th>
                                    <th width="15%">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                foreach ($withdrawRequestsList as $withdrawRequest)
                                {
                                    if ($withdrawRequest['status'] == 1)
                                    {
                                        $status = '<span class="label label-sm label-info status_label">Completed</span>';
                                    }
                                    else
                                    {
                                        $status = '<span class="label label-sm label-danger status_label">Pending</span>';
                                    }
                                    ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo date('M d, Y G:i A', $withdrawRequest['created']) ?></td>
                                        <td><?php echo getSiteCurrencySymbol('', $withdrawRequest['currency']); ?><?php echo number_format($withdrawRequest['amount_requested'], 2); ?></td>
                                        <td><?php echo $status; ?></td>
                                    </tr>
                                    <?php
                                    $i++;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php $this->load->view('includes/right_bar') ?>
    </div>
</section>
<script>
    $(document).ready(function () {

    });
</script>