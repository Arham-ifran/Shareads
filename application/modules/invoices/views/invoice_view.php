<?php $this->load->view('includes/profile_info'); ?>
<?php

$total_commission_sum = $total_share_counter  = $prd_commission       = 0;
?>
<style>
    h3{
        font-weight: unset;
    }
</style>
<section class="container" style="padding: 45px">
    <div class="heading_links clearfix">
        <h3 class="main_heading">Invoices</h3>
        <p></p>
    </div>

    <div class="row">
        <div class="col-md-9 col-sm-12">
            <?php
            if ($invoice_details['status'] == 1)
            {
                $status = '<span class="label label-sm label-info">Paid</span>';
            }
            else
            {
                $status = '<span class="label label-sm label-danger">Pending</span>';
            }

            ?>
            <h1>Invoice: <strong><?php echo $invoice_details['invoice_number']; ?></strong></h1>
            <h3>Publisher Name: <strong><?php echo ucfirst($this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name')); ?></strong></h3>
            <h3>Publisher Email: <strong><?php echo $this->session->userdata('email'); ?></strong></h3>
            <h3>Date from: <strong><?php echo date("d M, Y", $invoice_details['from_datetime']); ?></strong></h3>
            <h3>Date to: <strong><?php echo date("d M, Y", $invoice_details['to_datetime']); ?></strong></h3>
            <h3>Status: <strong><?php echo $status; ?></strong></h3>
            <div class="clearfix"></div>

            <div class="table-responsive">

                <div class="main">
                    <br/>
                    <table  class="table table-bordered table-striped" style="width:100%;">
                        <tr role="row" style="background: #89c028;color: white;">
                            <th class="tbl-header ">Product Name</th>
                            <th class="tbl-header ">Product Commission</th>
                            <th class="tbl-header ">Sales Counter</th>
                            <th class="tbl-header ">User Commission</th>
                        </tr>
                        <?php
//                       dd($list_result);
//                       dd($invoice_details);
                        foreach ($list_result as $key => $value)
                        {
                            ?>
                            <?php $prd_commission += (double) get_currency_rate($value['prd_commission'],$value['p_currency'],$invoice_details['invoice_currency']); ?>
                            <?php $total_commission_sum += (double) get_currency_rate($value['total_commision_sum'],$value['p_currency'],$invoice_details['invoice_currency']); ?>
                            <?php $total_share_counter += (int) $value['counter']; ?>
                            <tr>
                                <td align="left"><?php echo ucfirst($value['product_name']); ?></td>
                                <td align="left"><?php echo getSiteCurrencySymbol('',$invoice_details['invoice_currency']).' ' . number_format(get_currency_rate($value['prd_commission'],$value['p_currency'],$invoice_details['invoice_currency']), 2); ?></td>
                                <td align="left"><?php echo $value['counter']; ?></td>
                                <td align="left"><?php echo getSiteCurrencySymbol('',$invoice_details['invoice_currency']).' ' . number_format(get_currency_rate($value['total_commision_sum'],$value['p_currency'],$invoice_details['invoice_currency']), 2); ?></td>
                            </tr>
                        <?php } ?>
                        <tr role="row">
                            <td colspan="1"><strong>Total</strong></td>
                            <td align="left"><strong><?php echo getSiteCurrencySymbol('',$invoice_details['invoice_currency']).' ' . number_format($prd_commission, 2); ?></strong></td>
                            <td align="left"><strong><?php echo $total_share_counter; ?></strong></td>
                            <td align="left"><strong><?php echo getSiteCurrencySymbol('',$invoice_details['invoice_currency']).' ' . number_format($total_commission_sum, 2); ?></strong></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <br/>
                <div class="col-xs-12">
                    <div class="row">

                        <a href="<?php echo base_url('invoices'); ?>" class="btn btn-danger pull-right"><i class="fa fa-arrow-left"></i> Go Back</a>
                        <a href="<?php echo base_url($invoice_details['file']); ?>" class="btn btn-success pull-right"  style="margin-right: 1%;background: #89c028;border: #89c028;" download><i class="fa fa-download"></i> Download</a>
                        <?php if ($invoice_details['status'] == 0)
                        {
                            ?>
                        <a href="<?php echo base_url('invoices/proceed_payment') . '/' . $this->common->encode($invoice_id); ?>" class="btn btn-primary pull-right" style="margin-right: 1%;background: #89c028;border: #89c028;"> <i class="fa fa-credit-card"></i> Pay Invoice</a>
<?php } ?>
                    </div>
                </div>
            </div>
        </div>

        <?php $this->load->view('includes/right_bar') ?>
<?php $this->load->view('includes/share_popup') ?>
    </div>


</section>
<script>
    function copyLink(txt)
    {
        $('#sharedLink').html('<img  height="1" width="1" style="border-style:none;display:none;"  src="' + txt + '" />');
    }
</script>
