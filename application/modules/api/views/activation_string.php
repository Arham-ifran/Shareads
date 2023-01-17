<p style="text-align: justify;font-size: 13px;">
    <small style="white-space: normal;word-break: break-all;">To activate the Product this is Mandatory to add the following script at the header of the Referral Website. If you will not add the script listed below the product site will not be activated and verified.  </small>
    <br/>
    <code style="font-size: 12px;white-space: normal;word-break: break-all;">&lt;script src="<?php echo base_url('assets/site/js/shareads.min.js'); ?>"&gt;&lt;/script&gt;</code>
</p>
<p style="text-align: justify;font-size: 13px;">
    <small style="white-space: normal;word-break: break-all;">The following script must be added at the header of success page where your sales get completed. Ensure to add this script before the activation of your product to make it verifiable.  </small>
    <br/>
    <code style="font-size: 12px;white-space: normal;word-break: break-all;">&lt;script src="<?php echo base_url('assets/site/js/shareads_catcher.min.js'); ?>"&gt;&lt;/script&gt;</code>
</p>
<p style="text-align: justify;font-size: 13px;">
    <small style="white-space: normal;word-break: break-all;">Pass the Transaction ID and Order ID parameters in the Script through function at the Success page to verify the sales. If you feel any difficulty adding the Script must consult your Developer otherwise your Product will not be verified.   </small>
    <br/>
    <code style="white-space: normal;word-break: break-all;">$(function(){orderCatcherForm.init('{YOUR_UNIQUE_ORDER_ID}','{YOUR_UNIQUE_TRANSACTION_ID}');});</code>
</p>