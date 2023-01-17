<link rel="stylesheet" href="<?php echo base_url('assets/admin/css/datepicker.min.css'); ?>" />
<?php $this->load->view('includes/profile_info'); ?>
<style></style>

<section class="container" style="padding: 45px">
    <div class="heading_links clearfix"><h3 class="main_heading">Reporting</h3></div>

    <form id="reportingFilter" action="<?php echo base_url('reporting'); ?>" action="get">
        <input type="hidden" id="r_time" name="time" value="<?php echo $time; ?>"/>
        <div class="white_box" style="padding-bottom:0;">
            <h5 class="filter_heading"><i class="fa fa-filter fa-fw"></i> Filters</h5>
            <ul class="sub_links">
                <li class="<?php echo $time == 'today' ? 'active' : ''; ?>"><a href="javascript:void(0)" data-value="today" class="rep_filter">Today</a></li>
                <li class="<?php echo $time == '7days' ? 'active' : ''; ?>"><a href="javascript:void(0)" data-value="7days" class="rep_filter">Last 7 days</a></li>
                <li class="<?php echo $time == '30days' ? 'active' : ''; ?>"><a href="javascript:void(0)" data-value="30days" class="rep_filter">30 Days</a></li>
                <li class="<?php echo $time == '6months' ? 'active' : ''; ?>"><a href="javascript:void(0)" data-value="6months" class="rep_filter">6 Months</a></li>
                <li class="<?php echo $time == '1year' ? 'active' : ''; ?>"><a href="javascript:void(0)" data-value="1year" class="rep_filter">1 Year</a></li>
                <!--<li class="<?php echo $time == 'custom' ? 'active' : ''; ?>"><a href="javascript:void(0)" data-value="custom" class="rep_filter">Custom Date</a></li>-->
                <li class="<?php echo $time == 'custom' ? 'active' : ''; ?>"><a href="javascript:void(0)" data-value="custom" data-toggle="collapse" class="rep_filter1" data-target="#cusotm">Custom Date <span class="caret"></span></a></li>
            </ul>
            <div id="cusotm" class="collapse customDate">
                <div class="row">
                    <div class="col-sm-2">
                        <div class="form-group">
                            <input id="start_date" type="text" placeholder="Start Date" class="form-control datepicker" name="start" value="<?php echo $start; ?>" />
                            <small  id="start_date_error" style="display: none;color: red; font-size: 12px;">Start date is required</small>
                        </div>

                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <input id="end_date" type="text" placeholder="End Date" class="form-control datepicker" name="end" value="<?php echo $end; ?>" />
                            <small id="end_date_error" style="color: red; font-size: 12px;display: none">End date is required</small>
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="form-group clearfix">
                            <input type="button" onclick="sendData()"  value="GO" class="btn btn-primary col-xs-12"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="row">
        <div class="col-xs-12">
            <h4>Account summary of <span class="blue"><?php echo $daytype; ?></span></h4>
            <div class="row">

                <?php
                if ($this->session->userdata('account_type') == 2)
                {
                    ?>
                    <div class="col-md-4 col-sm-4">
                        <div class="total_box bg-danger clearfix">
                            <div class="pull-left">
                                <h5>Total Products</h5>
                                <span class="total_value"><?php echo number_format($totalProducts); ?></span>
                            </div>
                            <span class="pull-right bg-gray-light-o"><i class="fa fa-gift"></i></span>
                        </div>
                    </div>
                    <?php
                }
                ?>

                <div class="col-md-4 col-sm-4">
                    <div class="total_box bg-green clearfix">
                        <div class="pull-left">
                            <h5>Total Links Shared</h5>
                            <span class="total_value"><?php echo number_format($totalSharedLinks); ?></span>
                        </div>
                        <span class="pull-right bg-gray-light-o"><i class="fa fa-link"></i></span>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4">
                    <div class="total_box bg-blue clearfix">
                        <div class="pull-left">
                            <h5>Total Visitors</h5>
                            <span class="total_value"><?php echo number_format($totalVisitors); ?></span>
                        </div>
                        <span class="pull-right bg-gray-light-o"><i class="fa fa-user"></i></span>
                    </div>
                </div>
                <?php
                if ($this->session->userdata('account_type') == 2)
                {
                    ?>
                    <?php /* ?>
                    <div class="col-md-3 col-sm-3">
                        <div class="total_box bg-purple clearfix">
                            <div class="pull-left">
                                <h5>Total Sales</h5>
                                <span class="total_value"><?php echo number_format($totalSales,2); ?></span>
                            </div>
                            <span class="pull-right bg-gray-light-o"><i class="fa"><b><?php echo getSiteCurrencySymbol('',$this->session->userdata('currency')); ?></b></i></span>
                        </div>
                    </div>
                    <?php */ ?>
                    <?php
                }
                if ($this->session->userdata('account_type') == 1)
                {
                    ?>
                    <div class="col-md-4 col-sm-4">
                        <div class="total_box bg-purple clearfix">
                            <div class="pull-left">
                                <h5>Total Successfull Sales</h5>
                                <span class="total_value"><?php echo number_format($totalSuccessLeads); ?></span>
                            </div>
                            <span class="pull-right bg-gray-light-o"><b><i class="fa fa-check"></i></b></span>
                        </div>
                    </div>
                    <?php
                }
                if ($this->session->userdata('account_type') == 2)
                {
                    ?>

                    <div class="col-md-3 col-sm-3">
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
                if ($this->session->userdata('account_type') == 1)
                {
                    ?>
                    <div class="col-md-3 col-sm-3">
                        <div class="total_box bg-cyan clearfix">
                            <div class="pull-left">
                                <h5>Total Commission Earned</h5>
                                <span class="total_value"><?php echo $totalCommission; ?></span>
                            </div>
                            <span class="pull-right bg-gray-light-o money_icon"><i class="fa"><b><?php echo getSiteCurrencySymbol('',$this->session->userdata('currency')); ?></b></i></span>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-3">
                        <div class="total_box bg-cyan clearfix">
                            <div class="pull-left">
                                <h5 style="font-size: 12px;">Total Unsuccessful Commission</h5>
                                <span class="total_value"><?php echo $totalUnsuccessfullCommision; ?></span>
                            </div>
                            <span class="pull-right bg-gray-light-o"><i class="fa"><b><?php echo getSiteCurrencySymbol('',$this->session->userdata('currency')); ?></b></i></span>
                        </div>
                    </div>
                    <?php
                }
                if ($this->session->userdata('account_type') == 2)
                {
                    ?>
                    <div class="col-md-3 col-sm-3">
                        <div class="total_box bg-cyan clearfix">
                            <div class="pull-left">
                                <h5>Total Commission Paid</h5>
                                <span class="total_value"><?php echo $totalCommission; ?></span>
                            </div>
                            <span class="pull-right bg-gray-light-o"><i class="fa"><b><?php echo getSiteCurrencySymbol('',$this->session->userdata('currency')); ?></b></i></span>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div class="total_box bg-cyan clearfix">
                            <div class="pull-left">
                                <h5 style="font-size: 13px;">Successful Sales Commission</h5>
                                <span class="total_value"><?php echo $totalSuccessSales; ?></span>
                            </div>
                            <span class="pull-right bg-gray-light-o"><i class="fa"><b><?php echo getSiteCurrencySymbol('',$this->session->userdata('currency')); ?></b></i></span>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div class="total_box bg-cyan clearfix">
                            <div class="pull-left">
                                <h5 style="font-size: 14px;">Pending sales Commission</h5>
                                <span class="total_value"><?php echo $totalPendingSales; ?></span>
                            </div>
                            <span class="pull-right bg-gray-light-o"><i class="fa"><b><?php echo getSiteCurrencySymbol('',$this->session->userdata('currency')); ?></b></i></span>
                        </div>
                    </div>
                <?php } ?>




            </div>
        </div>
    </div>
    <hr>
    <div class="text-center"><a class="btn btn-primary" href="<?php echo base_url('reporting/detail') ?>"><i class="fa fa-eye fa-fw"></i> View Detail</a></div>
</section>

<script src="<?php echo base_url('assets/admin/js/bootstrap-datepicker.min.js'); ?>"></script>
<script>
                                $(function () {

                                    var date = new Date();
                                    var currentMonth = date.getMonth();
                                    var currentDate = date.getDate();
                                    var currentYear = date.getFullYear();
                                    console.log('currentMonth -> '+currentMonth);
                                    console.log('currentDate -> '+currentDate);
                                    console.log('currentYear -> '+currentYear);
                                    console.log('parse -> '+new Date(currentYear, currentMonth, currentDate));

                                    $('.datepicker').datepicker({
                                        autoclose: true,
                                        todayHighlight: true,
                                        format: 'yyyy-mm-dd',
                                        endDate: '+0d'
                                    });
                                    $('.rep_filter').click(function () {
                                        $("#end_date_error").hide();
                                        $("#start_date_error").hide();
                                        $('#r_time').val($(this).attr('data-value'));
                                        $('#reportingFilter').submit();
                                    });

                                    $('.rep_filter1').click(function () {
                                        $('#r_time').val('custom');
                                    });
                                });

                                function sendData() {

                                    if ($("#start_date").val() == "") {
                                        $("#start_date_error").show();
                                        $("#end_date_error").hide();
                                    } else if ($("#end_date").val() == "") {
                                        $("#end_date_error").show();
                                        $("#start_date_error").hide();
                                    } else {
                                        $("#end_date_error").hide();
                                        $("#start_date_error").hide();
                                        $('#r_time').val('custom');
                                        $('#reportingFilter').submit();
                                    }

                                }
</script>