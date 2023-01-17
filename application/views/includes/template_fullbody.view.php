<?php if (isset($home_flag) && $home_flag == 1) { ?>

    <!----------------------Home header--------------------------------->
    <?php $this->load->view('includes/header_home'); ?>
    <!----------------------header--------------------------------->
<?php } else if (isset($login_flag) && $login_flag == 1) { ?>
    <!----------------------Login Header--------------------------------->
    <?php $this->load->view('includes/header_login'); ?>
    <!----------------------header--------------------------------->
<?php } else { ?>

    <!----------------------header--------------------------------->
    <?php $this->load->view('includes/header'); ?>
    <!----------------------header--------------------------------->
<?php } ?>

<!--main content start-->
<?php echo $content; ?>
<!--main content end-->

<?php if (isset($login_flag) && $login_flag == 1) { ?>

    <!----------------------login footer--------------------------------->
    <?php $this->load->view('includes/footer_login'); ?>
    <!----------------------footer--------------------------------->
<?php } else { ?>
    <!----------------------footer--------------------------------->
    <?php $this->load->view('includes/footer'); ?>
    <!----------------------footer--------------------------------->
    <?php
}?>