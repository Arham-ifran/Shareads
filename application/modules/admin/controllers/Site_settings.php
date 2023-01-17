<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Site_settings extends CI_Controller {

    public $tbl = 'site_settings';

    public function __construct() {
        parent::__construct();
        // check if admin login
        $this->engineinit->_is_not_admin_logged_in_redirect('admin/login');
        // Check rights
        if (rights(1) != true) {
            redirect(base_url('admin/dashboard'));
        }
        $this->load->model('site_settings_model');
//        ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
    }

// End __construct
    /**
     * Method: index
     */
    public function index() {
        
        
        $data['row'] = $this->site_settings_model->getRow(1);

        if ($this->input->post()) {

            $_POST['LIMIT_WITHDRAW'] = serialize($_POST['LIMIT_WITHDRAW']);
            $db_query = $this->site_settings_model->saveItem($_POST);

            if ($db_query) {
                $this->session->set_flashdata('success_message', 'Information successfully saved.');
                redirect('admin/site_settings'); // due to flash data.
            } else {
                $this->session->set_flashdata('error_message', 'Opps! Error saving informtion. Please try again.');
            }
        }
        $data['adExpiryDaysArr'] = $this->adExpiryDays();
        $data ['content'] = $this->load->view('site_settings/form', $data, true); //Return View as data
        $this->load->view('templete-view', $data);
    }

    /**
	 * Method: adExpiryDays
	 * Returns: array
	 */
	public function adExpiryDays(){
		$result = array();
		for($i=730; $i>=1; $i--){
			$result[$i] = $i;
		}
		return $result;
	}
}

//End Class