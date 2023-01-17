<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Reporting extends CI_Controller {

    public function __construct() {
        parent::__construct();
                // check if admin login
        $this->engineinit->_is_not_logged_in_redirect('login');
        $this->load->model('reporting_model');
    }

    public function index() {

        $data = array();
        $data['user_id'] = $user_id = $this->session->userdata('user_id');
        if (isset($_GET) && $_GET['time'] <> '') {
            if ($_GET['time'] == 'today')
                $var = 'Today';
            else if ($_GET['time'] == '7days')
                $var = '7 Days';
            else if ($_GET['time'] == '30days')
                $var = '30 Days';
            else if ($_GET['time'] == '6months')
                $var = '6 Months';
            else if ($_GET['time'] == '1year')
                $var = '1 Year';
            else if ($_GET['time'] == 'custom')
                $var = $_GET['start'] . ' to ' . $_GET['end'];
            else
                $var = 'Today';
            $data['time'] = $_GET['time'];
            $filter = array();
            foreach ($_GET as $k => $v) {
                $filter[$k] = $v;
            }
            $data['time'] = $_GET['time'];
            $data['start'] = $filter['start'];
            $data['end'] = $filter['end'];
            $this->session->set_userdata('r_time', $filter);
        } else {
            $var = 'Today';
            $data['time'] = 'today';
            $this->session->set_userdata('r_time', '');
        }


        $data['daytype'] = $var;
        if ($this->session->userdata('account_type') == 2) {
            $data['totalProducts'] = $this->reporting_model->getTotalProducts($user_id,$filter);
            $data['totalSharedLinks'] = $this->reporting_model->getTotalPublisherLinksSharedCounter($user_id, $filter);
            $data['totalVisitors'] = $this->reporting_model->getTotalPublisherVisitorsCounter($user_id, $filter);
            $data['totalSales'] = $this->reporting_model->getTotalPublisherSaleCounter($user_id, $filter);
            $data['totalSuccessLeads'] = $this->reporting_model->getTotalPublisherSuccessLeadCounter($user_id, $filter);
            $data['totalCommission'] = $this->reporting_model->getTotalPublisherCommissionCounter($user_id, $filter);
            $data['totalSuccessSales'] = $this->reporting_model->getTotalSuccessSalesCommissionCounter($user_id, $filter);
            $data['totalPendingSales'] = $this->reporting_model->getTotalPendingSalesCommissionCounter($user_id, $filter);
        }
        if ($this->session->userdata('account_type') == 1) {
            $data['totalSharedLinks'] = $this->reporting_model->getTotalLinksSharedCounter($user_id, $filter);
            $data['totalVisitors'] = $this->reporting_model->getTotalVisitorsCounter($user_id, $filter);
            $data['totalSales'] = $this->reporting_model->getTotalSaleCounter($user_id, $filter);
            $data['totalSuccessLeads'] = $this->reporting_model->getTotalSuccessLeadCounter($user_id, $filter);
            $data['totalCommission'] = $this->reporting_model->getTotalCommissionCounter($user_id, $filter);
            $data['totalCommissionEarned'] = $this->reporting_model->getTotaltotalCommissionEarnedCounter($user_id, $filter);
            $data['totalUnsuccessfullCommision'] = $this->reporting_model->getTotalUnsuccessfullCommisionCounter($user_id, $filter);
//            dd($data);
        }
       
//dd($data);

        $data ['content'] = $this->load->view('reporting', $data, true);
        $this->load->view('includes/template_dashboard.view.php', $data);
    }

    function detail() {
        $data['user_id'] = $user_id = $this->session->userdata('user_id');

        $filter = $this->session->userdata('r_time');

        if ($this->session->userdata('account_type') == 2) {
            $data['totalSharedLinks'] = $this->reporting_model->getTotalPublisherLinksSharedCounter($user_id, $filter);
            $data['totalVisitors'] = $this->reporting_model->getTotalPublisherVisitorsCounter($user_id, $filter);
            $data['totalSales'] = $this->reporting_model->getTotalPublisherSaleCounter($user_id, $filter);

            $data['totalSuccessSales'] = $this->reporting_model->getTotalPublisherSuccessLeadCounter($user_id, $filter);

            $data['totalCommission'] = $this->reporting_model->getTotalPublisherCommissionCounter($user_id, $filter);
            
            $data['totalSuccessSales'] = $this->reporting_model->getTotalSuccessSalesCommissionCounter($user_id, $filter);
            $data['totalPendingSales'] = $this->reporting_model->getTotalPendingSalesCommissionCounter($user_id, $filter);

            $data['sharedLinks'] = $this->reporting_model->getTotalPublisherLinksShared($user_id, $filter);
            $data['visitors'] = $this->reporting_model->getTotalPublisherVisitors($user_id, $filter);
            $data['sales'] = $this->reporting_model->getTotalPublisherSales($user_id, $filter);

            $data['successLeadSales'] = $this->reporting_model->getTotalPublisherLeadSales($user_id, $filter);

            $data['commission'] = $this->reporting_model->getTotalPublisherCommission($user_id, $filter);
            $data['successSalesCommission'] = $this->reporting_model->getTotalSuccessSalesCommission($user_id, $filter);
            $data['pendingSalesCommission'] = $this->reporting_model->getTotalPendingSalesCommission($user_id, $filter);
        }
        if ($this->session->userdata('account_type') == 1) {
            $data['totalSharedLinks'] = $this->reporting_model->getTotalLinksSharedCounter($user_id, $filter);
            $data['totalVisitors'] = $this->reporting_model->getTotalVisitorsCounter($user_id, $filter);
            $data['totalSales'] = $this->reporting_model->getTotalSaleCounter($user_id, $filter);

            $data['totalSuccessSales'] = $this->reporting_model->getTotalSuccessLeadCounter($user_id, $filter);

            $data['totalCommission'] = $this->reporting_model->getTotalCommissionCounter($user_id, $filter);

            $data['sharedLinks'] = $this->reporting_model->getTotalLinksShared($user_id, $filter);
            $data['visitors'] = $this->reporting_model->getTotalVisitors($user_id, $filter);
            $data['sales'] = $this->reporting_model->getTotalSales($user_id, $filter);
            $data['successLeadSales'] = $this->reporting_model->getTotalSuccessLeadSales($user_id, $filter);


            $data['commission'] = $this->reporting_model->getTotalCommission($user_id, $filter);
            
//            dd($data);
        }

        $data ['content'] = $this->load->view('detail', $data, true);
        $this->load->view('includes/template_dashboard.view.php', $data);
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */