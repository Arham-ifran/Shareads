<?php $this->load->view('includes/profile_info'); ?>


<?php
$sales_total      = 0;
$commission_total = 0;
foreach ($sales as $sale)
{
    $pro_currecny = getVal('currency', 'c_products', array('product_id' => $sale['product_id']));
    $sales_total  = $sales_total + get_currency_rate($sale['price'], $pro_currecny, $this->session->userdata('currency'));
}
foreach ($commission as $comm)
{
    $pro_currecny = getVal('currency', 'c_products', array('product_id' => $comm['product_id']));
    if ($this->session->userdata('account_type') == 1)
    {
        $commission_total = $commission_total + get_currency_rate($comm['advertiser_commission'], $pro_currecny, $this->session->userdata('currency'));
    }
    else
    {
        $commission_total = $commission_total + get_currency_rate($comm['total_commission'], $pro_currecny, $this->session->userdata('currency'));
    }
}

$sales_total      = getSiteCurrencySymbol('', $this->session->userdata('currency')) . number_format($sales_total, 2);
$commission_total = getSiteCurrencySymbol('', $this->session->userdata('currency')) . number_format($commission_total, 2);
?>
<section class="container" style="padding-top: 45px">
    <div class="heading_links clearfix">
        <h3 class="main_heading">Dashboard</h3>
    </div>
    <div class="row">
        <div class="col-md-9 col-sm-12">
            <h4>Total Account Summary For <span class="blue">Current Month</span></h4>
            <div class="row">
                <?php
                if ($this->session->userdata('account_type') == 2)
                {
                    ?>

                    <div class="col-md-4 col-sm-6">
                        <div class="total_box bg-danger clearfix">
                            <div class="pull-left">
                                <h5>Total Products</h5>
                                <span class="total_value"><?php echo number_format($totalProducts); ?></span> </div>
                            <span class="pull-right bg-gray-light-o"><i class="fa fa-gift"></i></span> </div>
                    </div>

                    <div class="col-md-4 col-sm-6">
                        <div class="total_box bg-green clearfix">
                            <div class="pull-left">
                                <h5>Links Shared</h5>
                                <span class="total_value"><?php echo number_format($totalSharedLinks); ?></span> </div>
                            <span class="pull-right bg-gray-light-o"><i class="fa fa-link"></i></span> </div>
                    </div>

                    <div class="col-md-4 col-sm-6">
                        <div class="total_box bg-blue clearfix">
                            <div class="pull-left">
                                <h5>Visitors</h5>
                                <span class="total_value"><?php echo number_format($totalVisitors); ?></span> </div>
                            <span class="pull-right bg-gray-light-o"><i class="fa fa-user"></i></span> </div>
                    </div>
<?php /* ?>
                    <div class="col-md-3 col-sm-6">
                        <div class="total_box bg-purple clearfix">
                            <div class="pull-left">
                                <h5>Sales</h5>
                                <span class="total_value"><?php echo number_format($totalSales, 2); ?></span> </div>
                            <span class="pull-right bg-gray-light-o"><i class="fa"><b><?php echo getSiteCurrencySymbol('', $this->session->userdata('currency')); ?></b></i></span> </div>
                    </div>
<?php */ ?>
                    <div class="col-md-6 col-sm-9">
                        <div class="total_box bg-cyan clearfix">
                            <div class="pull-left">
                                <h5>Total Successful Commission</h5>
                                <span class="total_value"><?php echo number_format($totalCommission, 2); ?></span> </div>
                            <span class="pull-right bg-gray-light-o"><i class="fa"><b><?php echo getSiteCurrencySymbol('', $this->session->userdata('currency')); ?></b></i></span> </div>
                    </div>

                    <div class="col-md-6 col-sm-9">
                        <div class="total_box bg-purple clearfix">
                            <div class="pull-left">
                                <h5>Total Number of Sales</h5>
                                <span class="total_value"><?php echo number_format($totalSuccessLeads); ?></span>
                            </div>
                            <span class="pull-right bg-gray-light-o"><b><i class="fa fa-check"></i></b></span>
                        </div>
                    </div>

                    <?php
                }
                else if ($this->session->userdata('account_type') == 1)
                {
                    ?>
                    <div class="col-md-6 col-sm-6">
                        <div class="total_box bg-green clearfix">
                            <div class="pull-left">
                                <h5>Links Shared</h5>
                                <span class="total_value"><?php echo number_format($totalSharedLinks); ?></span> </div>
                            <span class="pull-right bg-gray-light-o"><i class="fa fa-link"></i></span> </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="total_box bg-blue clearfix">
                            <div class="pull-left">
                                <h5>Visitors</h5>
                                <span class="total_value"><?php echo number_format($totalVisitors); ?></span> </div>
                            <span class="pull-right bg-gray-light-o"><i class="fa fa-user"></i></span> </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="total_box bg-cyan clearfix">
                            <div class="pull-left">
                                <h5>Commission Earned</h5>
                                <span class="total_value"><?php echo number_format($totalCommission, 2); ?></span> </div>
                            <span class="pull-right bg-gray-light-o money_icon"><i class="fa"><b><?php echo getSiteCurrencySymbol('', $this->session->userdata('currency')); ?></b></i></span> </div>
                    </div>

                    <div class="col-md-6 col-sm-6">
                        <div class="total_box bg-purple clearfix">
                            <div class="pull-left">
                                <h5>Total Successful Sales</h5>
                                <span class="total_value"><?php echo $totalSuccessLeads; ?></span>
                            </div>
                            <span class="pull-right bg-gray-light-o"><b><i class="fa fa-user"></i></b></span>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <?php /* ?>
            <hr>
            <div class="white_box">
                <div class="filter_heading"> <a href="#sales" class="collapse_arrow" data-toggle="collapse"></a>
                    <h5><i class="fa"></i>Total Sales <?php echo $sales_total; ?></h5>
                </div>
                <div class="collapse in"  id="sales">
                    <div class="table-responsive table-height">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th width="2%">#</th>
                                    <th width="13%">Date & Time</th>
                                    <th width="40%">URL</th>
                                    <th width="15%">Amount</th>
                                    <th>Shared On</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;

                                $fb_sales       = $tw_sales       = $em_sales       = $ln_sales       = $linkedin_sales = $total_sales    = 0;
//dd($sales);
                                foreach ($sales as $sale)
                                {
                                    $pro_currecny = getVal('currency', 'c_products', array('product_id' => $sale['product_id']));
                                    $total_sales++;
                                    ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo date('M d, Y G:i A', $sale['created']) ?></td>
                                        <td><?php echo $sale['url']; ?></td>
                                        <td><?php echo getSiteCurrencySymbol('', $this->session->userdata('currency')); ?><?php echo number_format(get_currency_rate($sale['price'], $pro_currecny, $this->session->userdata('currency')), 2); ?></td>
                                        <?php
                                        if (strpos($sale['referer_page'], 'facebook') !== false)
                                        {

                                            echo '<td><i class="fa fa-facebook-square fa-fw"></i> Facebook</td>';

                                            $fb_sales++;
                                        }
                                        else if (strpos($sale['referer_page'], 'twitter') !== false)
                                        {

                                            echo '<td><i class="fa fa-twitter-square fa-fw"></i> Twitter</td>';

                                            $tw_sales++;
                                        }
                                        else if ($sale['referer_page'] == 'email')
                                        {

                                            echo '<td><i class="fa fa-envelope-square fa-fw"></i> Email</td>';

                                            $em_sales++;
                                        }
                                        else if ($sale['referer_page'] == 'linkedin')
                                        {
                                            echo '<td><i class="fa fa-envelope-square fa-fw"></i> Linkedin</td>';
                                            $linkedin_sales++;
                                        }
                                        else
                                        {

                                            echo '<td><i class="fa fa-link fa-fw"></i> Direct Link / Shared Link</td>';

                                            $ln_sales++;
                                        }
                                        ?>
                                    </tr>
                                    <?php
                                    $i++;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php */ ?>
            <div class="white_box">
                <div class="filter_heading"> <a href="#commission" class="collapse_arrow"  data-toggle="collapse"></a>
                    <h5><i class="fa"><b></b></i> Total Commission <?php
                        if ($_SESSION['account_type'] == '1')
                            echo 'Earned';
                        else
                            echo 'Details';
                        ?> <?php echo $commission_total; ?></h5>
                </div>
                <div class="collapse in" id="commission">
                    <div class="table-responsive table-height">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th width="2%">#</th>
                                    <th width="13%">Date & Time</th>
                                    <th width="40%">URL</th>
                                    <th width="15%">Commission <?php
                                        if ($_SESSION['account_type'] == '1')
                                            echo 'Earned';
                                        else
                                            echo 'Amount';
                                        ?></th>
                                    <th>Shared On</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;

                                $fb_comm       = $tw_comm       = $em_comm       = $ln_comm       = $linkedin_comm = 0;

                                foreach ($commission as $comm)
                                {
                                    $pro_currecny = getVal('currency', 'c_products', array('product_id' => $comm['product_id']));
                                    ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo date('M d, Y G:i A', $comm['created']) ?></td>
                                        <td><?php echo '<span title="'.$comm['url'].'">'.substr($comm['url'],0,50).' </span>'; ?></td>
                                        <?php
                                        if ($this->session->userdata('account_type') == 2)
                                        {
                                            ?>
                                            <td><?php echo getSiteCurrencySymbol('', $this->session->userdata('currency')); ?><?php echo number_format(get_currency_rate($comm['total_commission'], $pro_currecny, $this->session->userdata('currency')), 2); ?></td>
                                            <?php
                                        }
                                        else
                                        {
                                            ?>
                                            <td><?php echo getSiteCurrencySymbol('', $this->session->userdata('currency')); ?><?php echo number_format(get_currency_rate($comm['advertiser_commission'], $pro_currecny, $this->session->userdata('currency')), 2); ?></td>
                                        <?php } ?>
                                        <?php
                                        if (strpos($comm['referer_page'], 'facebook') !== false)
                                        {

                                            echo '<td><i class="fa fa-facebook-square fa-fw"></i> Facebook</td>';

                                            $fb_comm++;
                                        }
                                        else if (strpos($comm['referer_page'], 'twitter') !== false)
                                        {

                                            echo '<td><i class="fa fa-twitter-square fa-fw"></i> Twitter</td>';

                                            $tw_comm++;
                                        }
                                        else if ($comm['referer_page'] == 'email')
                                        {

                                            echo '<td><i class="fa fa-envelope-square fa-fw"></i> Email</td>';

                                            $em_comm++;
                                        }
                                        else if ($comm['referer_page'] == 'linkedin')
                                        {
                                            echo '<td><i class="fa fa-envelope-square fa-fw"></i> Linkedin</td>';
                                            $linkedin_comm++;
                                        }
                                        else
                                        {

                                            echo '<td><i class="fa fa-link fa-fw"></i> Direct Link / Shared Link</td>';

                                            $ln_comm++;
                                        }
                                        ?>
                                    </tr>
                                    <?php
                                    $i++;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php $this->load->view('includes/right_bar') ?>
    </div>
</section>
