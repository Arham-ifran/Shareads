<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Lead_generation extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // check if admin login
        $this->engineinit->_is_not_admin_logged_in_redirect('admin/login');
        // Check rights
        if (rights(103) != true) {
            redirect(base_url('admin/dashboard'));
        }
        $this->load->model('lead_generation_model');
    }

// End __construct
    /**
      @Method: index
      @Return: Listing
     */
    public function index() {
        $data['result'] = $this->lead_generation_model->loadListing();
        $data ['content'] = $this->load->view('lead_generation/listing', $data, true);
        $this->load->view('templete-view', $data);
    }

    function change_status($id, $type) {
        $itemId = $this->common->decode($id);
        $advertiser_id = getVal('advertiser_id','c_lead_generation', 'id', $itemId);
        if($advertiser_id <> '' && $advertiser_id <> 0)
        {
            $this->lead_generation_model->updateItemStatus($itemId, $type);
            $this->session->set_flashdata('success_message', 'Status changed successfully.');
                redirect('admin/lead_generation'); // due to flash data.
        }
        if ($type == 2) {
            $this->lead_generation_model->updateItemStatus($itemId, $type);
            $this->session->set_flashdata('success_message', 'Status changed successfully.');
                redirect('admin/lead_generation'); // due to flash data.

        }if ($type == 1) {
            redirect('payment/lead_generation/' . $id);
        }
    }

    function success() {

        $data['type'] = 'Success';
        $data['msg'] = 'Commission transfered successfully.';
        $data ['content'] = $this->load->view('lead_generation/message', $data, true); //Return View as data
        $this->load->view('templete-view.php', $data);
    }

    function cancel() {

        $data['type'] = 'Error';
        $data['msg'] = 'Commission tranfered canceled due to some error.';
        $data ['content'] = $this->load->view('lead_generation/message', $data, true); //Return View as data
        $this->load->view('templete-view.php', $data);
    }

}

//End Class