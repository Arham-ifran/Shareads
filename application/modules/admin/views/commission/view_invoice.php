
<div class="page-header">
    <h1>
        View Invoice
    </h1>
</div><!-- /.page-header -->
<div class="row">
    <div class="col-xs-12">
        <?php
        if (true)
        {
            ?>
            <div class="col-sm-8"></div>
            <div class="col-sm-4">
                <a download href="<?php echo base_url($invoice_details['file']); ?>"><button type="button" class="btn btn-primary"   style="float: right; margin-right: 15px;">Download Invoice</button></a>
                <a href="<?php echo base_url('admin/commission/manage_invoices'); ?>"><button type="button" class="btn btn-danger"   style="float: right; margin-right: 15px;">Go Back</button></a>
            </div>
        <?php } ?>
    </div>
</div>
<div class="clearfix space-8"></div>
<div class="row">
    <div class="col-xs-12">
        <div class="space-4"></div>
        <!--INVOICE VIEW-->
        <object data="<?php echo base_url($invoice_details['file']); ?>" type="application/pdf"  width="100%" height="500">
            <embed src="<?php echo base_url($invoice_details['file']); ?>" type="application/pdf" />
        </object>
        <!--INVOICE VIEW-->
    </div>
</div>