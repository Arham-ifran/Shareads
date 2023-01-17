
  $total_commission_sum = $total_share_counter  = $prd_commission       = 0;
  ?>
  <html>
  <head>
  <title> <?php echo SITE_NAME ?> Invoice</title>
  <link href="<?php echo base_url(); ?>assets/pdf/css/pdf.css" rel="stylesheet" type="text/css"/>
  <style>
  .main{
  padding: 2%;
  box-shadow: 0px 0px 3px #ccc;
  border-radius: 2px;
  width: 75% !important;
  }
  table tr td,table tr th{
  font-size: 11px;
  }
  </style>
  </head>
  <body>
  <div class="main">
  <?php $this->load->view('commission/invoice/print_invoice_header'); ?>
  <div class="clear"></div>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
  <td width="100%" align="left">
  <table width="50%" align="left" class="gridtable">
  <tbody>
  <tr>
  <th width="100">Invoice Number:</th>
  <td width="150"><?php echo $invoice_details['invoice_number']; ?></td>
  </tr>
  </tbody>
  </table>
  </td>
  </tr>
  </table>
  <div class="clear" style="height:10px;"></div>
  <table  class="gridtable" style="width:100%;">
  <tr role="row">
  <td>Publisher Name</td>
  <td><?php echo $user_details['first_name'] . ' ' . $user_details['last_name']; ?></td>
  <td>Publisher Email</td>
  <td><?php echo $user_details['email']; ?></td>
  </tr>
  <tr role="row">
  <td>Date from</td>
  <td><?php echo date("d M, Y", $user_invoice_details['from_invoice_date']); ?></td>
  <td>Date to</td>
  <td><?php echo date("d M, Y", $user_invoice_details['to_invoice_date']); ?></td>
  </tr>
  </table>
  <br/>
  <table  class="gridtable" style="width:100%;">
  <tr role="row">
  <th width="20%" class="tbl-header ">Product Type</th>
  <th width="20%" class="tbl-header ">Product Name</th>
  <th width="30%" class="tbl-header ">Product Commission</th>
  <th width="20%" class="tbl-header ">Sales Counter</th>
  <th width="30%" class="tbl-header ">User Commission</th>
  </tr>
  <?php
  foreach ($list_result as $key => $value)
  {
  ?>
  <?php $prd_commission += (double) get_currency_rate($value['prd_commission'], $value['p_currency'], $user_details['currency']); ?>
  <?php $total_commission_sum += (double) get_currency_rate($value['total_commision_sum'], $value['p_currency'], $user_details['currency']); ?>
  <?php $total_share_counter += (int) $value['counter']; ?>
  <tr>
  <td align="left"><?php echo ucfirst($value['pro_type']); ?></td>
  <td align="left"><?php echo ucfirst($value['product_name']); ?></td>
  <td align="right"><?php echo getSiteCurrencySymbol('', $user_details['currency']) . ' ' . number_format(get_currency_rate($value['prd_commission'], $value['p_currency'], $user_details['currency']), 2); ?></td>
  <td align="right"><?php echo number_format($value['counter'], 2); ?></td>
  <td align="right"><?php echo getSiteCurrencySymbol('', $user_details['currency']) . ' ' . number_format(get_currency_rate($value['total_commision_sum'], $value['p_currency'], $user_details['currency']), 2); ?></td>
  </tr>
  <?php } ?>
  <tr role="row">
  <td colspan="2"><strong>Total</strong></td>
  <td align="right"><strong><?php echo getSiteCurrencySymbol('', $user_details['currency']) . ' ' . number_format($prd_commission, 2); ?></strong></td>
  <td align="right"><strong><?php echo number_format($total_share_counter, 2); ?></strong></td>
  <td align="right"><strong><?php echo getSiteCurrencySymbol('', $user_details['currency']) . ' ' . number_format($total_commission_sum, 2); ?></strong></td>
  </tr>
  </tbody>
  </table>
  </div>
  </body>

