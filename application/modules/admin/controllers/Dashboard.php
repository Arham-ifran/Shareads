<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        //check if admin login
        $this->engineinit->_is_not_admin_logged_in_redirect('admin/login');

        $this->load->model('dashboard_model');
    }

// End __construct
    /**
     * Method: index
     */

    public function index() {

        $data = array();
        $data['listing_counter'] = $this->dashboard_model->load_listing_counter();
        $data['new_publishers'] = $this->dashboard_model->load_new_publishers_counter();
        $data['new_advertiser'] = $this->dashboard_model->load_new_advertiser_counter();
        $data['new_advertiser_bankclaimfund'] = $this->dashboard_model->load_new_advertiser_bankclaimfund_counter();

        $data['total_ads'] = $this->dashboard_model->total_ads_counter();
        $data['categories'] = $this->dashboard_model->total_categories_counter();
        $data['subcategories'] = $this->dashboard_model->total_subcategories_counter();
        $data['total_cms'] = $this->dashboard_model->total_cms_counter();
        $data['total_invitation_sent'] = $this->dashboard_model->total_invitation_sent();
        $data['total_accepted_invitations'] = $this->dashboard_model->total_accepted_invitations();
        

        $data['content'] = $this->load->view('dashboard', $data, true);
        $this->load->view('templete-view', $data);
    }

}

//End Class