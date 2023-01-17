<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Reports extends CI_Controller {
    public function __construct() {
        parent::__construct();
//        error_reporting(E_ALL);
        $this->engineinit->_is_not_admin_logged_in_redirect('admin/login');
        // Check rights
        if (rights(96) != true ) {
            redirect(base_url('admin/dashboard'));
        }
        $this->load->model('reports_model');
    }
// End __construct
    /**
      @Method: index
      @Return: Listing
     */
    public function index() {
        $this->publisher();
    }

    function advertiser()
    {
        $data = array();
        $this->session->unset_userdata('advertiser_list');
        if ($this->input->post()) {

            if ($this->input->post('date_from') != '')
                $data['date_from'] = strtotime($this->input->post('date_from'));
            if ($this->input->post('date_to') != '')
                $data['date_to'] = strtotime($this->input->post('date_to'));

            $this->session->set_userdata('advertiser_list', $data);
        }
        $data['account_type'] = 1;
        $this->session->set_userdata('advertiser_list', $data);

        $data['result'] = $this->reports_model->get_user_list_report($data);

        if ($this->input->post('date_from') != '')
            $data['date_from'] = date('m/d/Y', strtotime($this->input->post('date_from')));
        if ($this->input->post('date_to') != '')
            $data['date_to'] = date('m/d/Y', strtotime($this->input->post('date_to')));

        $data ['content'] = $this->load->view('reports/advertiser_report', $data, true); //Return View as data
        $this->load->view('templete-view.php', $data);
    }

    function print_advertiser_report() {
        $data = array();
        $advtisr_data = $this->session->userdata('advertiser_list');
        $data["result"] = $this->reports_model->get_user_list_report($advtisr_data);
        $this->load->library('parser');
        $output = $this->parser->parse('reports/print/print_advertiser_report', $data);
        $this->_gen_pdf($output);
    }


    function publisher()
    {
        $data = array();
        $this->session->unset_userdata('publishr_list');
        if ($this->input->post()) {

            if ($this->input->post('date_from') != '')
                $data['date_from'] = strtotime($this->input->post('date_from'));
            if ($this->input->post('date_to') != '')
                $data['date_to'] = strtotime($this->input->post('date_to'));

            $this->session->set_userdata('publishr_list', $data);
        }
        $data['account_type'] = 2;
        $this->session->set_userdata('publishr_list', $data);

        $data['result'] = $this->reports_model->get_user_list_report($data);

        if ($this->input->post('date_from') != '')
            $data['date_from'] = date('m/d/Y', strtotime($this->input->post('date_from')));
        if ($this->input->post('date_to') != '')
            $data['date_to'] = date('m/d/Y', strtotime($this->input->post('date_to')));

        $data ['content'] = $this->load->view('reports/publisher_report', $data, true); //Return View as data
        $this->load->view('templete-view.php', $data);
    }

    function print_publisher_report() {
        $data = array();
        $publishr_list = $this->session->userdata('publishr_list');
        $data["result"] = $this->reports_model->get_user_list_report($publishr_list);
        $this->load->library('parser');
        $output = $this->parser->parse('reports/print/print_publisher_report', $data);
        $this->_gen_pdf($output);
    }




    function ads_list_report()
    {
        $data = array();
        $this->session->unset_userdata('ads_data');
        if ($this->input->post()) {
            $data['product_name'] = $this->input->post('product_name');

            if ($this->input->post('date_from') != '')
                $data['date_from'] = strtotime($this->input->post('date_from'));
            if ($this->input->post('date_to') != '')
                $data['date_to'] = strtotime($this->input->post('date_to'));
            $this->session->set_userdata('ads_data', $data);
        }
        $this->session->set_userdata('ads_data', $data);

        $data['result'] = $this->reports_model->get_listing_ads_report($data);
        
        // echo '<pre>';print_r($data['result']->result());die();

        if ($this->input->post('date_from') != '')
            $data['date_from'] = date('m/d/Y', strtotime($this->input->post('date_from')));
        if ($this->input->post('date_to') != '')
            $data['date_to'] = date('m/d/Y', strtotime($this->input->post('date_to')));



        $data ['content'] = $this->load->view('reports/listing_ads_report', $data, true); //Return View as data
        $this->load->view('templete-view.php', $data);
    }
    function print_ads_report() {
        $data = array();
        $sold_data = $this->session->userdata('ads_data');
        $data["result"] = $this->reports_model->get_listing_ads_report($sold_data);
        $this->load->library('parser');
        $output = $this->parser->parse('reports/print/print_ads_report', $data);
        $this->_gen_pdf($output);
    }


    function advertiser_commissions()
    {
        $data = array();
        $this->session->unset_userdata('adv_comm_data');
        if ($this->input->post()) {

            if ($this->input->post('date_from') != '')
                $data['date_from'] = strtotime($this->input->post('date_from'));
            if ($this->input->post('date_to') != '')
                $data['date_to'] = strtotime($this->input->post('date_to'));
            $this->session->set_userdata('adv_comm_data', $data);
        }
        $this->session->set_userdata('adv_comm_data', $data);

        $data['result'] = $this->reports_model->get_advertiser_commissions_report($data);

        if ($this->input->post('date_from') != '')
            $data['date_from'] = date('m/d/Y', strtotime($this->input->post('date_from')));
        if ($this->input->post('date_to') != '')
            $data['date_to'] = date('m/d/Y', strtotime($this->input->post('date_to')));



        $data ['content'] = $this->load->view('reports/advertiser_commissions_report', $data, true); //Return View as data
        $this->load->view('templete-view.php', $data);
    }

    function print_advertiser_commissions_report()
    {
        $data = array();
        $sold_data = $this->session->userdata('adv_comm_data');
        $data["result"] = $this->reports_model->get_advertiser_commissions_report($sold_data);
        $this->load->library('parser');
        $output = $this->parser->parse('reports/print/print_advertiser_commissions_report', $data);
        $this->_gen_pdf($output);
    }


    function publisher_commissions()
    {
        $data = array();
        $this->session->unset_userdata('pub_comm_data');
        if ($this->input->post()) {

            if ($this->input->post('date_from') != '')
                $data['date_from'] = strtotime($this->input->post('date_from'));
            if ($this->input->post('date_to') != '')
                $data['date_to'] = strtotime($this->input->post('date_to'));
            $this->session->set_userdata('pub_comm_data', $data);
        }
        $this->session->set_userdata('pub_comm_data', $data);

        $data['result'] = $this->reports_model->get_publisher_commissions_report($data);

        if ($this->input->post('date_from') != '')
            $data['date_from'] = date('m/d/Y', strtotime($this->input->post('date_from')));
        if ($this->input->post('date_to') != '')
            $data['date_to'] = date('m/d/Y', strtotime($this->input->post('date_to')));



        $data ['content'] = $this->load->view('reports/publisher_commissions_report', $data, true); //Return View as data
        $this->load->view('templete-view.php', $data);
    }

    function print_publisher_commissions_report()
    {
        $data = array();
        $sold_data = $this->session->userdata('pub_comm_data');
        $data["result"] = $this->reports_model->get_publisher_commissions_report($sold_data);
        $this->load->library('parser');
        $output = $this->parser->parse('reports/print/print_publisher_commissions_report', $data);
        $this->_gen_pdf($output);
    }


    function admin_commissions()
    {
        $data = array();
        $this->session->unset_userdata('pub_comm_data');
        if ($this->input->post()) {

            if ($this->input->post('date_from') != '')
                $data['date_from'] = strtotime($this->input->post('date_from'));
            if ($this->input->post('date_to') != '')
                $data['date_to'] = strtotime($this->input->post('date_to'));
            $this->session->set_userdata('pub_comm_data', $data);
        }
        $this->session->set_userdata('pub_comm_data', $data);

        $data['result'] = $this->reports_model->get_publisher_commissions_report($data);

        if ($this->input->post('date_from') != '')
            $data['date_from'] = date('m/d/Y', strtotime($this->input->post('date_from')));
        if ($this->input->post('date_to') != '')
            $data['date_to'] = date('m/d/Y', strtotime($this->input->post('date_to')));



        $data ['content'] = $this->load->view('reports/admin_commissions_report', $data, true); //Return View as data
        $this->load->view('templete-view.php', $data);
    }

    function print_admin_commissions_report()
    {
        $data = array();
        $sold_data = $this->session->userdata('pub_comm_data');
        $data["result"] = $this->reports_model->get_publisher_commissions_report($sold_data);
        $this->load->library('parser');
        $output = $this->parser->parse('reports/print/print_admin_commissions_report', $data);
        $this->_gen_pdf($output);
    }


     private function _gen_pdf($html, $paper = 'A4') {
        $this->load->library('MPDF54/mpdf');
        $mpdf = new mPDF('c');
        $mpdf->SetDisplayMode('fullpage', 'two');
        $mpdf->mirrorMargins = 1;
        $mpdf->setHeader();
        $mpdf->setHeader(SITE_NAME);
        $mpdf->setFooter(base_url().'||Page #{PAGENO}');
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }



}
//End Class