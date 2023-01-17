<?php $this->load->view('includes/profile_info'); ?>
<section class="container">
    <div class="heading_links clearfix">
        <h3 class="main_heading">Reporting</h3>
    </div>
    <br>
    <div class="white_box">
        <div class="filter_heading">
            <a href="#linksShared" class="collapse_arrow" data-toggle="collapse"></a>
            <h5><i class="fa fa-link fa-fw"></i> Total Links Shared <?php echo number_format($totalSharedLinks); ?></h5>
        </div>
        <div class="collapse in" id="linksShared">
            <div class="table-responsive table-height">

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th width="2%">#</th>
                            <th width="13%">Date & Time</th>
                            <th width="40%">URL</th>
                            <th>Shared On</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i        = 1;
                        $facebook = $twitter  = $email    = $links    = $linkedin = 0;
                        $user_id  = $this->session->userdata('user_id');
                        foreach ($sharedLinks as $lnk)
                        {
                            $date         = date('Y-m-d', $lnk['created']);
                            if ($this->session->userdata('account_type') == 2)
                            {
                                $shareds_urls = $this->reporting_model->getPublisherShareLinkCounter_withURL($lnk['product_id'], $date, $user_id);
                            }
                            if ($this->session->userdata('account_type') == 1)
                            {
                                $shareds_urls = $this->reporting_model->getShareLinkCounter_withURL($lnk['product_id'], $date, $user_id);
                            }
                            $shareds_html = '';
                            foreach(explode(',',$shareds_urls)  as $key => $value)
                            {
                                $shareds_html .= $value.'<br>';
                            }
                            ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo date('M d, Y G:i A', $lnk['created']) ?></td>
                                <td><?php echo $shareds_html; ?></td>
                                <?php
                                if ($this->session->userdata('account_type') == 2)
                                {
                                    $fb    = $this->reporting_model->getPublisherShareLinkCounter($lnk['product_id'], $date, 1, $user_id);
                                    $tw    = $this->reporting_model->getPublisherShareLinkCounter($lnk['product_id'], $date, 2, $user_id);
                                    $em    = $this->reporting_model->getPublisherShareLinkCounter($lnk['product_id'], $date, 3, $user_id);
                                    $ln    = $this->reporting_model->getPublisherShareLinkCounter($lnk['product_id'], $date, 4, $user_id);
                                    $lnkin = $this->reporting_model->getPublisherShareLinkCounter($lnk['product_id'], $date, 5, $user_id);
                                }
                                if ($this->session->userdata('account_type') == 1)
                                {
                                    $fb    = $this->reporting_model->getShareLinkCounter($lnk['product_id'], $date, 1, $user_id);
                                    $tw    = $this->reporting_model->getShareLinkCounter($lnk['product_id'], $date, 2, $user_id);
                                    $em    = $this->reporting_model->getShareLinkCounter($lnk['product_id'], $date, 3, $user_id);
                                    $ln    = $this->reporting_model->getShareLinkCounter($lnk['product_id'], $date, 4, $user_id);
                                    $lnkin = $this->reporting_model->getShareLinkCounter($lnk['product_id'], $date, 5, $user_id);
                                }

                                $facebook = $facebook + $fb;
                                $twitter  = $twitter + $tw;
                                $email    = $email + $em;
                                $links    = $links + $ln;
                                $linkedin = $linkedin + $lnkin;
                                ?>
                                <td>
                                    <?php
                                    if ($fb > 0)
                                    {
                                        ?>
                                        <i class="fa fa-facebook-square fa-fw"></i> Facebook (<?php echo $fb; ?>),
                                        &nbsp;&nbsp;&nbsp;
                                        <?php
                                    }
                                    if ($tw > 0)
                                    {
                                        ?>
                                        <i class="fa fa-twitter-square fa-fw"></i> Twitter (<?php echo $tw; ?>)
                                        &nbsp;&nbsp;&nbsp;
                                        <?php
                                    }
                                    if ($em > 0)
                                    {
                                        ?>
                                        <i class="fa fa-envelope fa-fw"></i> Email (<?php echo $em; ?>)
                                        &nbsp;&nbsp;&nbsp;
                                        <?php
                                    }
                                    if ($ln > 0)
                                    {
                                        ?>
                                        <i class="fa fa-link fa-fw"></i> Link (<?php echo $ln; ?>)
                                        <?php
                                    }
                                    if ($lnkin > 0)
                                    {
                                        ?>
                                        <i class="fa fa-linkedin fa-fw"></i> LinkedIn (<?php echo $lnkin; ?>)
                                    <?php } ?>

                                </td>
                            </tr>
                            <?php
                            $i++;
                        }
                        ?>



                    </tbody>
                </table>
            </div>
            <div class="graph">
                <div id="linkShared"></div>
            </div>
        </div>
    </div>
    <div class="white_box">
        <div class="filter_heading">
            <a href="#visitor" class="collapse_arrow" data-toggle="collapse"></a>
            <h5><i class="fa fa-user fa-fw"></i> Total Visitors <?php echo number_format($totalVisitors); ?></h5>
        </div>
        <div class="collapse in" id="visitor">
            <div class="table-responsive table-height">
                <table class="table table-striped">
                    <thead>
                        <tr>
                        <tr>
                            <th width="2%">#</th>
                            <th width="13%">Date & Time</th>
                            <th width="40%">URL</th>
                            <th>Shared On</th>
                        </tr>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i         = 1;
                        $fb_visit  = $tw_visit  = $em_visit  = $ln_visit  = $lnk_visit = 0;
//                            dd($visitors);
                        foreach ($visitors as $visit)
                        {
                            ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo date('M d, Y G:i A', $visit['timestamp']) ?></td>
                                <td><?php echo $visit['url'] ?></td>
                                <?php
                                if (strpos($visit['referer_page'], 'facebook') !== false)
                                {
                                    echo '<td><i class="fa fa-facebook-square fa-fw"></i> Facebook</td>';
                                    $fb_visit++;
                                }
                                else if (strpos($visit['referer_page'], 'twitter') !== false)
                                {
                                    echo '<td><i class="fa fa-twitter-square fa-fw"></i> Twitter</td>';
                                    $tw_visit++;
                                }
                                else if ($visit['referer_page'] == 'email')
                                {
                                    echo '<td><i class="fa fa-envelope-square fa-fw"></i> Email</td>';
                                    $em_visit++;
                                }
                                else if ($visit['referer_page'] == 'linkedin')
                                {
                                    echo '<td><i class="fa fa-linkedin fa-fw"></i> LinkedIn</td>';
                                    $lnk_visit++;
                                }
                                else
                                {
                                    echo '<td><i class="fa fa-link fa-fw"></i> Direct Link / Shared Link</td>';
                                    $ln_visit++;
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
            <div class="graph">
                <div id="visitors"></div>
            </div>
        </div>
    </div>
    <?php if ($this->session->userdata('account_type') == 2)
    {
        ?>
<?php /* ?>
        <div class="white_box">
            <div class="filter_heading">
                <a href="#sales" class="collapse_arrow" data-toggle="collapse"></a>
                <h5><i class="fa"></i> Total Sales <?php echo getSiteCurrencySymbol('', $this->session->userdata('currency')); ?><?php echo number_format($totalSales, 2); ?></h5>
            </div>
            <div class="collapse in" id="sales">
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
                            $i           = 1;
                            $fb_sales    = $tw_sales    = $em_sales    = $ln_sales    = $lnk_sales   = $total_sales = 0;
                            foreach ($sales as $sale)
                            {
                                $total_sales++;
                                ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo date('M d, Y G:i A', $sale['created']) ?></td>
                                    <td><?php echo $sale['referer_page']; ?></td>
                                    <td><?php echo $sale['price']; ?></td>
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
                                        echo '<td><i class="fa fa-linkedin fa-fw"></i> Linkedin</td>';
                                        $lnk_sales++;
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
                <div class="graph">
                    <div id="TSales"></div>
                </div>
            </div>
        </div>
        <?php */ ?>
<?php } ?>
    
    <?php  ?>
    
    <div class="white_box">
        <div class="filter_heading">
            <a href="#sales_success" class="collapse_arrow" data-toggle="collapse"></a>
            <h5><i class="fa"></i>  <?php echo $this->session->userdata('account_type') == 1 ? 'Total Number of Sales' : 'Total Number of Sales'; ?> <?php if ($this->session->userdata('account_type') == 1)
{
//    echo getSiteCurrencySymbol('', $this->session->userdata('currency')); ?><?php //echo number_format($totalSales, 2);
echo count($successLeadSales);
}
else
{
    echo count($successLeadSales);
} ?></h5>
        </div>
        <div class="collapse in" id="sales_success">
            <div class="table-responsive table-height">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th width="2%">#</th>
                            <th width="13%">Date & Time</th>
                            <th width="40%">URL</th>
                            <th>Shared On</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
$i            = 1;
$fbb_sales    = $tww_sales    = $emm_sales    = $lnn_sales    = $lnkk_sales   = $totall_sales = 0;
foreach ($successLeadSales as $sale)
{
    $totall_sales++;
    ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo date('M d, Y G:i A', $sale['created']) ?></td>
                                 <td><?php echo $sale['referer_page']; ?></td>
                                <?php
                                if (strpos($sale['referer_page'], 'facebook') !== false)
                                {
                                    echo '<td><i class="fa fa-facebook-square fa-fw"></i> Facebook</td>';
                                    $fbb_sales++;
                                }
                                else if (strpos($sale['referer_page'], 'twitter') !== false)
                                {
                                    echo '<td><i class="fa fa-twitter-square fa-fw"></i> Twitter</td>';
                                    $tww_sales++;
                                }
                                else if ($sale['referer_page'] == 'email')
                                {
                                    echo '<td><i class="fa fa-envelope-square fa-fw"></i> Email</td>';
                                    $emm_sales++;
                                }
                                else if ($sale['referer_page'] == 'linkedin')
                                {
                                    echo '<td><i class="fa fa-linkedin fa-fw"></i> Linkedin</td>';
                                    $lnkk_sales++;
                                }
                                else
                                {
                                    echo '<td><i class="fa fa-link fa-fw"></i> Direct Link / Shared Link</td>';
                                    $lnn_sales++;
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
            <div class="graph">
                <div id="TLSales"></div>
            </div>
        </div>
    </div>
    
    <?php  ?>
    
    
    <div class="white_box">
        <div class="filter_heading">
            <a href="#commission" class="collapse_arrow" data-toggle="collapse"></a>
            <h5><i class="fa"></i> Total Commission <?php
if ($_SESSION['account_type'] == '1')
    echo 'Earned';
else
    echo 'Paid';
?> <?php echo $totalCommission; ?></h5>
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
    echo 'Paid';
?></th>
                            <th>Shared On</th>

                        </tr>
                    </thead>
                    <tbody>
<?php
$i        = 1;
$fb_comm  = $tw_comm  = $em_comm  = $ln_comm  = $lnk_comm = 0;
//                        dd($commission);
foreach ($commission as $comm)
{
    ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo date('M d, Y G:i A', $comm['created']) ?></td>
                                <td><?php echo $comm['url']; ?></td>
                                <?php
                                if ($this->session->userdata('account_type') == 2)
                                {
                                    ?>
                                    <td><?php echo $comm['total_commission']; ?></td>
                                <?php
                                }
                                else
                                {
                                    ?>
                                    <td><?php echo $comm['advertiser_commission']; ?></td>
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
                                    echo '<td><i class="fa fa-linkedin fa-fw"></i> Linkedin</td>';
                                    $lnk_comm++;
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
            <div class="graph"><div id="SaleCommission"></div></div>
        </div>
    </div>
<?php if ($this->session->userdata('account_type') == 2)
{
    ?>

        <div class="white_box">
            <div class="filter_heading">
                <a href="#commission_successfull_sales" class="collapse_arrow" data-toggle="collapse"></a>
                <h5><i class="fa"></i> Successful Sales Commission <?php echo $totalSuccessSales; ?></h5>
            </div>
            <div class="collapse in" id="commission_successfull_sales">
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
                                $i          = 1;
                                $fb_comm_1  = $tw_comm_1  = $em_comm_1  = $ln_comm_1  = $lnk_comm_1 = 0;
                                foreach ($successSalesCommission as $comm)
                                {
                                    ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo date('M d, Y G:i A', $comm['created']) ?></td>
                                    <td><?php echo $comm['url']; ?></td>
                                    <?php
                                    if ($this->session->userdata('account_type') == 2)
                                    {
                                        ?>
                                        <td><?php echo $comm['total_commission']; ?></td>
                                    <?php
                                    }
                                    else
                                    {
                                        ?>
                                        <td><?php echo $comm['advertiser_commission']; ?></td>
                                    <?php } ?>

                                    <?php
                                    if (strpos($comm['referer_page'], 'facebook') !== false)
                                    {
                                        echo '<td><i class="fa fa-facebook-square fa-fw"></i> Facebook</td>';
                                        $fb_comm_1++;
                                    }
                                    else if (strpos($comm['referer_page'], 'twitter') !== false)
                                    {
                                        echo '<td><i class="fa fa-twitter-square fa-fw"></i> Twitter</td>';
                                        $tw_comm_1++;
                                    }
                                    else if ($comm['referer_page'] == 'email')
                                    {
                                        echo '<td><i class="fa fa-envelope-square fa-fw"></i> Email</td>';
                                        $em_comm_1++;
                                    }
                                    else if ($comm['referer_page'] == 'linkedin')
                                    {
                                        echo '<td><i class="fa fa-linkedin fa-fw"></i> Linkedin</td>';
                                        $lnk_comm_1++;
                                    }
                                    else
                                    {
                                        echo '<td><i class="fa fa-link fa-fw"></i> Direct Link / Shared Link</td>';
                                        $ln_comm_1++;
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
                <div class="graph"><div id="successSales"></div></div>
            </div>
        </div>
        <div class="white_box">
            <div class="filter_heading">
                <a href="#commission_pending_sales" class="collapse_arrow" data-toggle="collapse"></a>
                <h5><i class="fa"></i> Pending sales Commission <?php echo $totalPendingSales; ?></h5>
            </div>
            <div class="collapse in" id="commission_pending_sales">
                <div class="table-responsive table-height">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th width="2%">#</th>
                                <th width="13%">Date & Time</th>
                                <th width="40%">URL</th>
                                <th width="15%">Amount </th>
                                <th>Shared On</th>

                            </tr>
                        </thead>
                        <tbody>
                                <?php
                                $i          = 1;
                                $fb_comm_2  = $tw_comm_2  = $em_comm_2  = $ln_comm_2  = $lnk_comm_2 = 0;
                                foreach ($pendingSalesCommission as $comm)
                                {
                                    ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo date('M d, Y G:i A', $comm['created']) ?></td>
                                    <td><?php echo $comm['url']; ?></td>
                                    <?php
                                    if ($this->session->userdata('account_type') == 2)
                                    {
                                        ?>
                                        <td><?php echo $comm['total_commission']; ?></td>
                                    <?php
                                    }
                                    else
                                    {
                                        ?>
                                        <td><?php echo $comm['advertiser_commission']; ?></td>
                                    <?php } ?>

                                    <?php
                                    if (strpos($comm['referer_page'], 'facebook') !== false)
                                    {
                                        echo '<td><i class="fa fa-facebook-square fa-fw"></i> Facebook</td>';
                                        $fb_comm_2++;
                                    }
                                    else if (strpos($comm['referer_page'], 'twitter') !== false)
                                    {
                                        echo '<td><i class="fa fa-twitter-square fa-fw"></i> Twitter</td>';
                                        $tw_comm_2++;
                                    }
                                    else if ($comm['referer_page'] == 'email')
                                    {
                                        echo '<td><i class="fa fa-envelope-square fa-fw"></i> Email</td>';
                                        $em_comm_2++;
                                    }
                                    else if ($comm['referer_page'] == 'linkedin')
                                    {
                                        echo '<td><i class="fa fa-linkedin fa-fw"></i> Linkedin</td>';
                                        $lnk_comm_2++;
                                    }
                                    else
                                    {
                                        echo '<td><i class="fa fa-link fa-fw"></i> Direct Link / Shared Link</td>';
                                        $ln_comm_2++;
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
                <div class="graph"><div id="pendingCommission"></div></div>
            </div>
        </div>

                    <?php } ?>
    <div class="white_box">
        <div class="filter_heading">
            <a href="#conversion" class="collapse_arrow" data-toggle="collapse"></a>
            <h5><i class="fa"></i> Conversion Rate</h5>
        </div>
        <div class="collapse in" id="conversion">
            <h4 style="padding:10px 15px 10px;">Total Visitors</h4>
            <div class="row">
                <div class=" col-xs-12 col-md-6 col-sm-6">

<?php
$total_visits = $fb_visit + $tw_visit + $em_visit + $ln_visit + $lnk_visit;
$fb_percent   = ($fb_visit / $total_visits) * 100;
$tw_percent   = ($tw_visit / $total_visits) * 100;
$em_percent   = ($em_visit / $total_visits) * 100;
$ln_percent   = ($ln_visit / $total_visits) * 100;
$lnk_percent  = ($lnk_visit / $total_visits) * 100;
?>

                    <div class="total_list">
                        <span><i class="fa fa-facebook-square fa-fw"></i> Facebook= <?php echo number_format($fb_visit); ?> &nbsp;&nbsp;&nbsp;(<?php echo round($fb_percent, 1); ?>%)</span>
                        <span><i class="fa fa-twitter-square fa-fw"></i> Twitter= <?php echo number_format($tw_visit); ?> &nbsp;&nbsp;&nbsp;(<?php echo round($tw_percent, 1); ?>%)</span>
                        <span><i class="fa fa-envelope fa-fw"></i> Email= <?php echo number_format($em_visit); ?> &nbsp;&nbsp;&nbsp;(<?php echo round($em_percent, 1); ?>%)</span>
                        <span><i class="fa fa-link fa-fw"></i> Link= <?php echo number_format($ln_visit); ?> &nbsp;&nbsp;&nbsp;(<?php echo round($ln_percent, 1); ?>%)</span>
                        <span><i class="fa fa-linkedin fa-fw"></i> LinkedIn= <?php echo number_format($lnk_visit); ?> &nbsp;&nbsp;&nbsp;(<?php echo round($lnk_percent, 1); ?>%)</span>
                    </div>
                    <h5 class="conversion_total">Total: 100%</h5>
                </div>
                <div class="col-xs-12 col-md-6 col-sm-6  text-center">
                    <div class="graph">
                        <div id="container1" style="min-width: 210px; height: 400px; max-width: 300px; margin: 0 auto"></div>
                    </div>
                </div>
            </div>
            <h4 style="padding:10px 15px 10px;">Sales Conversion (No of sales)</h4>
            <div class="row">
                <div class="col-xs-12 col-md-6 col-sm-6">
                    <div class="total_list">
<?php
$fb_sale_percent  = ($fbb_sales / $totall_sales) * 100;
$tw_sale_percent  = ($tww_sales / $totall_sales) * 100;
$em_sale_percent  = ($emm_sales / $totall_sales) * 100;
$ln_sale_percent  = ($lnn_sales / $totall_sales) * 100;
$lnk_sale_percent = ($lnkk_sales / $totall_sales) * 100;
?>
                        <span><i class="fa fa-facebook-square fa-fw"></i> Facebook= <?php echo number_format($fbb_sales) ?> &nbsp;&nbsp;&nbsp;(<?php echo round($fb_sale_percent, 1); ?>%)</span>
                        <span><i class="fa fa-twitter-square fa-fw"></i> Twitter= <?php echo number_format($tww_sales) ?> &nbsp;&nbsp;&nbsp;(<?php echo round($tw_sale_percent, 1); ?>%)</span>
                        <span><i class="fa fa-envelope fa-fw"></i> Email= <?php echo number_format($emm_sales) ?> &nbsp;&nbsp;&nbsp;(<?php echo round($em_sale_percent, 1); ?>%)</span>
                        <span><i class="fa fa-link fa-fw"></i> Link= <?php echo number_format($lnn_sales) ?> &nbsp;&nbsp;&nbsp;(<?php echo round($ln_sale_percent, 1); ?>%)</span>
                        <span><i class="fa fa-linkedin fa-fw"></i> LinkedIn= <?php echo number_format($lnkk_sales) ?> &nbsp;&nbsp;&nbsp;(<?php echo round($lnk_sale_percent, 1); ?>%)</span>
                    </div>
                    <h5 class="conversion_total">Total: 100%</h5>
                </div>
                <div class="col-xs-12 col-md-6 col-sm-6 text-center">
                    <div class="graph">
                        <div id="container2" style="min-width: 210px; height: 400px; max-width: 300px; margin: 0 auto"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script>
    $(function ()
    {
        $(document).ready(function ()
        {

            $('#container1').highcharts({
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {
                    text: 'Total Visitors'
                },
                credits: {
                    enabled: false
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: false
                        },
                        showInLegend: true
                    }
                },
                series: [{
                        name: 'Visitors',
                        colorByPoint: true,
                        data: [{
                                name: 'Facebook',
                                y: <?php echo $fb_visit; ?>,
                                sliced: true,
                                selected: true
                            }, {
                                name: 'Twitter',
                                y: <?php echo $tw_visit; ?>
                            }, {
                                name: 'Email',
                                y: <?php echo $em_visit; ?>
                            }, {
                                name: 'Link',
                                y: <?php echo $ln_visit; ?>
                            }, {
                                name: 'LinkedIn',
                                y: <?php echo $lnk_visit; ?>
                            }]
                    }]
            });

            $('#container2').highcharts({
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {
                    text: 'Total Sales'
                },
                credits: {
                    enabled: false
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: false
                        },
                        showInLegend: true
                    }
                },
                series: [{
                        name: 'Sales',
                        colorByPoint: true,
                        data: [{
                                name: 'Facebook',
                                y: <?php echo $fbb_sales; ?>,
                                sliced: true,
                                selected: true
                            }, {
                                name: 'Twitter',
                                y: <?php echo $tww_sales; ?>
                            }, {
                                name: 'Email',
                                y: <?php echo $emm_sales; ?>
                            }, {
                                name: 'Link',
                                y: <?php echo $lnn_sales; ?>
                            }, {
                                name: 'LinkedIn',
                                y: <?php echo $lnkk_sales; ?>
                            }]
                    }]
            });

        });


        $('#linkShared').highcharts({
            chart: {
                type: 'spline'
            },
            title: {
                text: 'Total Links Shared',
                x: -20
            },
            credits: {
                enabled: false
            },
            xAxis: {
                categories: ['Facebook', 'Twitter', 'Email', 'Link', 'LinkedIn']
            },
            yAxis: {
                title: {
                    text: 'Counter'
                },
                plotLines: [{
                        value: 0,
                        width: 1,
                        color: '#808080'
                    }]
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [{
                    name: '',
                    data: [<?php echo $facebook; ?>, <?php echo $twitter; ?>, <?php echo $email; ?>, <?php echo $links; ?>, <?php echo $linkedin; ?>]
                }]
        });

        $('#visitors').highcharts({
            chart: {
                type: 'spline'
            },
            title: {
                text: 'Total Visitors',
                x: -20
            },
            credits: {
                enabled: false
            },
            xAxis: {
                categories: ['Facebook', 'Twitter', 'Email', 'Link', 'LinkedIn']
            },
            yAxis: {
                title: {
                    text: 'Counter'
                },
                plotLines: [{
                        value: 0,
                        width: 1,
                        color: '#808080'
                    }]
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [{
                    name: '',
                    data: [<?php echo $fb_visit; ?>, <?php echo $tw_visit; ?>, <?php echo $em_visit; ?>, <?php echo $ln_visit; ?>, <?php echo $lnk_visit; ?>]
                }]
        });
        $('#TSales').highcharts({
            chart: {
                type: 'spline'
            },
            title: {
                text: 'Total Sales',
                x: -20
            },
            credits: {
                enabled: false
            },
            xAxis: {
                categories: ['Facebook', 'Twitter', 'Email', 'Link', 'Linkedin']
            },
            yAxis: {
                title: {
                    text: 'Counter'
                },
                plotLines: [{
                        value: 0,
                        width: 1,
                        color: '#808080'
                    }]
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [{
                    name: '',
                    data: [<?php echo $fb_sales; ?>, <?php echo $tw_sales; ?>, <?php echo $em_sales; ?>, <?php echo $ln_sales; ?>, <?php echo $lnk_sales; ?>]
                }]
        });

        $('#TLSales').highcharts({
            chart: {
                type: 'spline'
            },
            title: {
                text: 'Total Number of Sales',
                x: -20
            },
            credits: {
                enabled: false
            },
            xAxis: {
                categories: ['Facebook', 'Twitter', 'Email', 'Link', 'Linkedin']
            },
            yAxis: {
                title: {
                    text: 'Counter'
                },
                plotLines: [{
                        value: 0,
                        width: 1,
                        color: '#808080'
                    }]
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [{
                    name: '',
                    data: [<?php echo $fbb_sales; ?>, <?php echo $tww_sales; ?>, <?php echo $emm_sales; ?>, <?php echo $lnn_sales; ?>, <?php echo $lnkk_sales; ?>]
                }]
        });

        $('#SaleCommission').highcharts({
            chart: {
                type: 'spline'
            },
            title: {
                text: 'Total Sales Commission',
                x: -20
            },
            credits: {
                enabled: false
            },
            xAxis: {
                categories: ['Facebook', 'Twitter', 'Email', 'Link', 'Linkedin']
            },
            yAxis: {
                title: {
                    text: 'Counter'
                },
                plotLines: [{
                        value: 0,
                        width: 1,
                        color: '#808080'
                    }]
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [{
                    name: '',
                    data: [<?php echo $fb_comm; ?>, <?php echo $tw_comm; ?>, <?php echo $em_comm; ?>, <?php echo $ln_comm; ?>, <?php echo $lnk_comm; ?>]
                }]
        });
<?php if ($_SESSION['account_type'] == 2)
{
    ?>
            $('#successSales').highcharts({
                chart: {
                    type: 'spline'
                },
                title: {
                    text: 'Total Success Sales Commission',
                    x: -20
                },
                credits: {
                    enabled: false
                },
                xAxis: {
                    categories: ['Facebook', 'Twitter', 'Email', 'Link', 'Linkedin']
                },
                yAxis: {
                    title: {
                        text: 'Counter'
                    },
                    plotLines: [{
                            value: 0,
                            width: 1,
                            color: '#808080'
                        }]
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle',
                    borderWidth: 0
                },
                series: [{
                        name: '',
                        data: [<?php echo $fb_comm_1; ?>, <?php echo $tw_comm_1; ?>, <?php echo $em_comm_1; ?>, <?php echo $ln_comm_1; ?>, <?php echo $lnk_comm_1; ?>]
                    }]
            });
            $('#pendingCommission').highcharts({
                chart: {
                    type: 'spline'
                },
                title: {
                    text: 'Total Pending Sales Commission',
                    x: -20
                },
                credits: {
                    enabled: false
                },
                xAxis: {
                    categories: ['Facebook', 'Twitter', 'Email', 'Link', 'Linkedin']
                },
                yAxis: {
                    title: {
                        text: 'Counter'
                    },
                    plotLines: [{
                            value: 0,
                            width: 1,
                            color: '#808080'
                        }]
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle',
                    borderWidth: 0
                },
                series: [{
                        name: '',
                        data: [<?php echo $fb_comm_2; ?>, <?php echo $tw_comm_2; ?>, <?php echo $em_comm_2; ?>, <?php echo $ln_comm_2; ?>, <?php echo $lnk_comm_2; ?>]
                    }]
            });
<?php } ?>

    });

</script>