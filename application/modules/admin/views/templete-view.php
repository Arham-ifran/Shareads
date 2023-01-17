<!----------------------header--------------------------------->
<?php $this->load->view('header'); ?>
<!----------------------header--------------------------------->
<!--main content start-->
<div class="main-content">
    <div class="main-content-inner">

        <div class="page-content">
            <?php // $this->load->view('settings'); ?><!-- /.ace-settings-container -->

            <?php echo $content; ?>

        </div>
    </div>
</div>
<!--main content end-->
<!----------------------footer--------------------------------->
<?php $this->load->view('footer'); ?>
<!----------------------footer--------------------------------->