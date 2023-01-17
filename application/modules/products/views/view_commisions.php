<?php $this->load->view('includes/profile_info'); ?>
<style>
.demo-product .tooltip.top {
    padding: 5px 0;
    margin-top: 0;
    position: absolute;
}
.demo-product .toltip_btn{
    position: relative;
}

    
</style>
<section class="container" style="padding: 45px">
    <div class="heading_links clearfix">
        <h3 class="main_heading">Product Commission Information</h3>
        <p></p>
    </div>

    <div class="row">
        <div class="col-md-9 col-sm-12">
            <div class="clearfix"></div>
            <h1><?php echo $results->result()[0]->product_name; ?></h1>
            <h3><?php echo $results->result()[0]->pro_type; ?></h3>
            <div class="clearfix"></div>
            <div class="table-responsive demo-product">



                <?php
                if (count($results->result()) > 0)
                {
                    ?>

                    <table class="table table-striped"  style="border:solid 1px #ccc;">

                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Transaction ID</th>
                                <!--<th>Share Counter</th>-->
                                <th width="30%"><?php echo ucfirst(SITE_NAME); ?> Commission</th>
                                <th width="15%">Confirmed Status <a href="#" type="button" class="btn btn-secondary toltip_btn test" data-toggle="tooltip" data-placement="top" title="" data-original-title="The order is by default confirmed, but you can unconfirmed it."><i class="fa fa-question-circle" aria-hidden="true"></i></a> </th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php
                            $i = 0;

                            foreach ($results->result() as $row)
                            {
                                $is_generated = $this->db->query('SELECT * FROM `c_invoice_orders` where FIND_IN_SET('.$row->order_id.',order_ids)')->result_array();
                                ?>
                                <tr>
                                    <td><label class="label label-primary"><?php echo ($row->unique_order_id <> '') ? $row->unique_order_id : 'N/A' ?></label></td>
                                    <td><label class="label label-primary"><?php echo ($row->unique_transaction_id <> '') ? $row->unique_transaction_id : 'N/A' ?></label></td>
                                    <!--<td><?php echo number_format($row->counter); ?></td>-->
                                    <td><?php echo ($row->currency_symbol <> getSiteCurrencySymbol()) ? $row->currency_symbol : getSiteCurrencySymbol(); ?><?php echo number_format($row->affiliator_commision, 2); ?></td>
                                    <?php if (sizeof($is_generated) == 0)
                                    { ?>
                                        <td>
                                            <div class="form-group">
                                                <label class="switch">
                                                    <input type="checkbox" <?php echo $row->is_confirmed == 1 ? 'checked' : '' ?> value="1" name="is_confirmed" onchange="updateAffiliateStatus(this, '<?php echo $this->common->encode($row->order_id); ?>')">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </td>
                                    <?php }
                                    else
                                    {
                                        echo '<td><label class="label label-primary">Invoice Generated</label></td>';
                                    } ?>
                                </tr>
        <?php
        $i++;
    }
    ?>

                        </tbody>
                    </table>
                    <?php
                }
                else
                {
                    ?>
                    <div class="alert alert-warning">No commission information found.</div>
<?php } ?>

            </div>

            <div class="row clearfix pull-left">
                <ul class="pagination">
<?php echo $pagination; ?>
                </ul>
            </div>

<!--            <div class="pull-right" style="margin-top:10px;"><a href="<?php echo base_url('products/add') ?>" class="btn btn-primary">Add New</a></div>-->

        </div>

<?php $this->load->view('includes/right_bar') ?>
<?php $this->load->view('includes/share_popup') ?>
    </div>


</section>
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>
<script>
    function copyLink(txt)
    {
        $('#sharedLink').html('<img  height="1" width="1" style="border-style:none;display:none;"  src="' + txt + '" />');
    }
</script>
