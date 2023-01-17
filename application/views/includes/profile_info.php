<?php $currentPage  = $this->uri->segment(1); ?>
<?php $currentPage1 = $this->uri->segment(2); ?>

<section class="mobile-nav-btn profile-img">

    <div class="container">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <div class="row">
            <div class="col-md-12 col-sm-11 col-xs-12">
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav nav-tabs">
                        <li class="<?php echo $currentPage == 'dashboard' ? 'active' : '' ?>">
                            <a href="<?php echo base_url('dashboard') ?>"><i class="fa fa-dashboard"></i> Dashboard</a>
                        </li>
                        <?php
                        if ($this->session->userdata('account_type') == 2)
                        {
                            ?>
                            <li class="<?php echo $currentPage == 'products' ? 'active' : '' ?>">
                                <a href="<?php echo base_url('products') ?>"><i class="fa fa-list"></i> Products</a>
                            </li>
<?php }
else
{ ?>
                            <li class="<?php echo $currentPage == 'marketing' ? 'active' : '' ?>">
                                <a href="<?php echo base_url('marketing') ?>"><i class="fa fa-bullhorn"></i> Share Ads</a>
                            </li>
<?php } ?>
                        <li class="<?php echo $currentPage == 'reporting' ? 'active' : '' ?>">
                            <a href="<?php echo base_url('reporting') ?>"><i class="fa fa-file-text"></i> Reporting</i></a>
                        </li>
                        <li class="<?php echo $currentPage == 'settings' && $currentPage1 == '' ? 'active' : '' ?>">
                            <a href="<?php echo base_url('settings') ?>"><i class="fa fa-cog"></i> Profile Settings</a>
                        </li>
                        <?php if ($this->session->userdata('account_type') == 2 && false)
                        { ?>
                            <li class="<?php echo $currentPage == 'settings' && $currentPage1 == 'payment_settings' ? 'active' : '' ?>">
                                <a href="<?php echo base_url('settings/payment_settings') ?>"><i class="fa fa-money"></i> Payment Settings</a>
                            </li>
                        <?php } ?>
                        <?php if ($this->session->userdata('account_type') == 2 && $this->session->userdata('is_admin') == 0 && $this->session->userdata('user_id') <> 1)
                        {
                            $invoices_counter = getTotalUnpaidInvoices(); ?>
                            <li class="<?php echo $currentPage == 'invoices' && $currentPage1 == '' ? 'active' : '' ?>">
                                <a href="<?php echo base_url('invoices/') ?>"><i class="fa fa-file"></i> Invoices <?php echo ($invoices_counter > 0) ? '<span class="label label-success" style="font-size: 8px;">' . $invoices_counter . '</span>' : ''; ?> </a>
                            </li>
<?php } ?>

                    </ul>
                </div>
            </div>
            <!--<div class="col-md-5 col-sm-4 col-xs-12">
                <div class="profile_area">
                    <div class="pic">
                        <img src="<?php
$_photo = $this->session->userdata('photo');
if ($_photo == '')
{
    $_photo = 'abc.png';
}
echo $this->common->is_person_image_exist(base_url("uploads/users/medium/" . $_photo), $this->session->userdata('gender'));
?>" alt="<?php echo $this->session->userdata('full_name') ?>"/>
                    </div>
                    <h4>
<?php echo $this->session->userdata('full_name') ?>
<?php if ($this->session->userdata('account_type') == 1)
{ /* ?>
                                            <span class="user_id">(<?php echo $this->session->userdata('user_key') ?>)</span>
<?php */ } ?>
                    </h4>
                </div>
            </div>-->
        </div>
    </div>

</section>
<?php
$account_type = $this->session->userdata('account_type');
$payment_type = getVal('payment_type', 'c_users', 'user_id', $this->session->userdata('user_id'));
$paypal_email = getVal('paypal_email', 'c_users', 'user_id', $this->session->userdata('user_id'));
$wiretransfer = getValArray('account_holder_name,account_number,bank_name', 'c_users', 'user_id', $this->session->userdata('user_id'));

if ($paypal_email == '' && $payment_type == 1 && $account_type == 1 && false)
{
    echo '<section class="container" style="padding: 10px 0px 0px 0px;margin-bottom: -60px;"><div class="alert alert-danger">Please update your <b>PayPal Email</b> in order to receive commission. <a style="font-weight: bold;margin-left: 10px;" href="' . base_url('settings/edit') . '">Update Your Profile</a></div></section>';
}
if ($paypal_email == '' && $account_type == 2)
{
    echo '<section class="container" style="padding: 10px 0px 0px 0px;margin-bottom: -60px;"><div class="alert alert-danger">Please update your <b>PayPal Email</b>. <a style="font-weight: bold;margin-left: 10px;" href="' . base_url('settings/edit') . '">Update Your Profile</a></div></section>';
}
else if(($wiretransfer['account_holder_name'] == '' || $wiretransfer['account_number'] == '' || $wiretransfer['bank_name'] == '') && $payment_type == 2  && $account_type == 1 && false)
{
     echo '<section class="container" style="padding: 10px 0px 0px 0px;margin-bottom: -60px;"><div class="alert alert-danger">Please update your <b>Payment details</b> in order to receive commission. <a style="font-weight: bold;margin-left: 10px;" href="' . base_url('settings/edit') . '">Update Your Profile</a></div></section>';
}
else
{
    
}
?>