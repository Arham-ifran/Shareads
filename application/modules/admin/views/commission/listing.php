<style>
    .card-counter{position: relative;margin: 5px;padding: 20px 10px;background-color: #fff;height: 120px; border-radius: 5px; transition: .3s linear all;}
    .card-counter:hover{transition: .3s linear all;}
    .card-counter.primary{ background-color: #007bff; color: #FFF; }
    .card-counter.danger{background-color: #ef5350;color: #FFF;}  
    .card-counter.success{background-color: #66bb6a; color: #FFF;}  
    .card-counter.info{background-color: #26c6da;color: #FFF; }  
    .card-counter i{font-size: 5em;opacity: 0.2;}
    .card-counter .count-numbers{position: absolute;right: 35px;top: 20px;font-size: 32px;display: block;}
    .card-counter .count-name{ position: absolute;right: 35px;top: 65px;font-style: italic;text-transform: capitalize;display: block; font-size: 18px;}
    .btn{padding: 2px;}
    .custom-toltip{position: absolute;top: 10px;right: 10px;}
    .custom-toltip .tooltip i{font-size: 22px; color: white; opacity: 1;}
    .custom-toltip .tooltip {
    position: relative;
    display: inline-block;
    opacity: 1;
}

.custom-toltip .tooltip .tooltiptext {
    visibility: hidden;
    min-width: 230px;
    background-color: black;
    color: #fff;
    text-align: center;
    border-radius: 6px;
    font-size: 16px;
    padding: 5px 10px;
    position: absolute;
    right: 0;
    z-index: 1;
}

.custom-toltip .tooltip:hover .tooltiptext {
    visibility: visible;
}
</style>
<div class="page-header">
    <h1>
        Commission
    </h1>
</div><!-- /.page-header -->
<div class="row">
    <div class="col-xs-12">
    </div>
    <div class="clearfix space-8"></div>
    <div class="col-sm-8">
    </div>
    <div class="col-sm-4">
    </div>
</div>
<div class="clearfix space-8"></div>
<div class="row">
    <div class="col-xs-12">
        <form class="form-inline" role="form" id="searchListing" method="post" action="<?php echo base_url('admin/commission/') ?>">
            <input type="text" class="form-control date-picker_onlymY" name="date_from" id="date_from" placeholder="Search Date From" value="<?php echo ($date_from <> '')?date('m/01/Y', $date_from) : ''; ?>">
            <input type="text" class="form-control date-picker_onlymY" name="date_to" id="date_to" placeholder="Search Date To" value="<?php echo ($date_to <> '')?date('m/01/Y', $date_to) : ''; ?>">
            <button type="submit" class="btn btn-danger">Search</button>
            <button type="button" class="btn btn-info" onclick="$('#date_from').val('');$('#date_to').val('');$('form').submit();">Clear Filters</button>
        </form>
    </div>
    <div class="clearfix space-8"></div>
    <div class="clearfix space-8"></div>
    <div class="col-xs-12">
        <?php
        $totalSales   = $total        = $total_orders = $totalComm    = $totalAdvComm = 0;
        // echo '<pre>';print_r($result_for_total_orders);die();
        foreach($result_for_total_orders->result_array() as $rfto)
        {
            $sql__             = 'SELECT c_orders.* FROM c_orders where c_orders.product_id = '.$rfto['product_id'].' and c_orders.order_status = 2 ';
            $total_orders += $this->db->query($sql__)->num_rows();
        }
        foreach ($all_orders as $ordr)
        {
            $date_filter  = explode('-', $ordr['monthDate'])
            ?>
            <?php
            $totalSales   = $totalSales + $ordr['overall_price'];
            $totalComm    = $totalComm + $ordr['overall_commission'];
            $totalAdvComm = $totalAdvComm + $ordr['all_advertiser_commission'];
            // $total_orders = $total_orders + $ordr['total_orders'];
            //SELECT pro.*,usr.full_name,typ.product_type, cat.category_name FROM c_products pro INNER JOIN c_users usr ON `usr`.`user_id` = pro.`user_id` INNER join c_products_types typ on pro.product_type = typ.id INNER JOIN c_categories cat on cat.category_id = pro.category_id WHERE pro.status = 1 ORDER BY pro.product_id DESC
            // $sql__             = 'SELECT c_orders.* FROM c_orders where c_orders.product_id IN(' . $ordr['product_ids'] . ') and c_orders.order_status = 2 ';
            // $total_orders = $total_order + $this->db->query($sql__)->num_rows();
            // $total_orders = $total_order + $this->db->query($sql__)->num_rows();
            $total        = $total + ($ordr['overall_commission'] - $ordr['all_advertiser_commission']);
            ?>
        <?php } ?>
        <hr/>
        <div class="row">
            <div class="col-xs-12">
                <h4><strong>Month: </strong> <?php echo $ordr['monthName']; ?></h4>
            </div>
            <div class="col-md-4 col-xs-12">
                <div class="card-counter info">
                    <div class="custom-toltip">
                        <div class="tooltip"><i class="fa fa-info-circle"></i>
                            <span class="tooltiptext">These are the total number of sales.</span>
                          </div>
                    </div>
                    <i class="fa fa-file-text" aria-hidden="true"></i>
                    <span class="count-numbers"><?php echo number_format($total_orders) ?></span>
                    <span class="count-name">Total Orders <br><a href="<?php echo base_url('admin/reports/ads_list_report'); ?>" style="color: white; font-size: 12px; float: right;    padding-right: 4%;margin-top: 6px;    text-decoration: underline;">details</a></span>
                </div>
            </div>
            <div class="col-md-4 col-xs-12">
                <div class="card-counter info">
                    <div class="custom-toltip">
                        <div class="tooltip"><i class="fa fa-info-circle"></i>
                            <span class="tooltiptext">This is the Total Earned Commission by Advertisers</span>
                          </div>
                    </div>

                    <i class="fa fa-users" aria-hidden="true"></i>
                    <span class="count-numbers"><?php echo getSiteCurrencySymbol(); ?><?php echo number_format($totalAdvComm, 2) ?></span>
                    <span class="count-name">Advertisers commission <br><a href="<?php echo base_url('admin/reports/advertiser_commissions'); ?>" style="    color: white; font-size: 12px; float: right;    padding-right: 4%;margin-top: 6px;    text-decoration: underline;">details</a></span>
                </div>
            </div>
            <div class="col-md-4 col-xs-12">
                <div class="card-counter info">
                    <div class="custom-toltip">
                        <div class="tooltip"><i class="fa fa-info-circle"></i>
                            <span class="tooltiptext">This is the Total Earned Commission of <?php echo SITE_NAME; ?></span>
                          </div>
                    </div>
                    <i class="fa fa-bullhorn" aria-hidden="true"></i>
                    <span class="count-numbers"><?php echo getSiteCurrencySymbol(); ?><?php echo number_format($total, 2) ?></span>
                    <span class="count-name"><?php echo SITE_NAME; ?> commission <br><a href="<?php echo base_url('admin/reports/admin_commissions'); ?>" style="    color: white; font-size: 12px; float: right;    padding-right: 4%;margin-top: 6px;    text-decoration: underline;">details</a></span>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    jQuery(function () {
        $("#date_from,#date_to").datepicker({
            format: "mm/01/yyyy",
            viewMode: "months",
            minViewMode: "months"
        });
    });
</script>
