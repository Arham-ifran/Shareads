<style>
    .custom_ul{list-style: disc;}
    .custom_ul li{margin-left: 36px;font-size: 13px;}
</style>
<div class="container-fluid">
    <div>
        <h3>Product Activation Failed</h3>
        <br>
        <ul class="custom_ul">
            <li>Share Ads is not able to verify your Product. The reason can be one of the followings</li>
            <li>Success Page URL is incorrect/ Inaccessible </li>
            <li>You did’t add the first Script in the header of referral web site or place a wrong script</li>
            <li>You did’t pass script in the header of success page or did’t pass the Transaction ID or Order ID Parameters through function properly.</li>
        </ul>
        <hr/>
        <h5>1) Kindly Recheck the mentioned reasons or consult the developer to make your product verifiable <br>2) Make a <a id="test_sale_btn_<?php echo $this->common->encode($product_id); ?>" href="<?php echo $row->url  . '?prd=' . $this->common->encode($product_id) . '&affid=' . $this->common->encode($this->session->userdata('user_id')); ?>" target="_blank" onclick="window.location='<?php echo base_url('dashboard'); ?>'">test sale</a>.</h5
    </div>
</div>