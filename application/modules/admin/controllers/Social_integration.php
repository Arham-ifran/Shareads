<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Social_integration extends CI_Controller {
    public function __construct() {
        parent::__construct();
       // check if admin login
        $this->engineinit->_is_not_admin_logged_in_redirect('admin/login');
        // Check rights
        if (rights(7) != true) {
            redirect(base_url('admin/dashboard'));
        }
        $this->load->model('social_integration_model');
    }
// End __construct
    /**
      @Method: index
      @Return: vehicles Listing
     */
    public function index() {
        $data = array();
        $data['result'] = $this->social_integration_model->loadListing($data);
        $data['pagination'] = $this->pagination->create_links();
        $data ['content'] = $this->load->view('social_integration/listing', $data, true); //Return View as data
        $this->load->view('templete-view.php', $data);
    }

    /**
      @Method: add
      @return: true/false
     */
    public function add() {
        $data = array();

        if ($this->input->post()) {

                $db_query = $this->social_integration_model->saveItem($_POST);

            if ($db_query) {
                $this->session->set_flashdata('success_message', 'Information successfully saved.');
                redirect('admin/social_integration'); // due to flash data.
            } else {
                $this->session->set_flashdata('error_message', 'Opps! Error saving informtion. Please try again.');
            }
        }
        $data['action'] = 'add';
        $data ['content'] = $this->load->view('social_integration/form', $data, true); //Return View as data
        $this->load->view('templete-view.php', $data);
    }
    /**
     * Method: edit
     * Return: Load Edit Form
     */
    public function edit($id) {
        $itemId = $this->common->decode($id);
        $data['id'] = $itemId;
        $data['row'] = $this->social_integration_model->getRow($itemId);
        $data['action'] = 'edit';
        $data ['content'] = $this->load->view('social_integration/form', $data, true); //Return View as data
        $this->load->view('templete-view.php', $data);
    }
    /**
      @Method: deleteItem
      @Params: itemId
      @Retrun: True/False
     */
    public function deleteItem($id) {
        $itemId = $this->common->decode($id);

        $result = $this->social_integration_model->deleteItem($itemId);
        if ($result) {
            $this->session->set_flashdata('success_message', 'Record deleted successfully.');
            redirect('admin/pages'); // due to flash data.
        } else {
            $this->session->set_flashdata('error_message', 'Opps! Error occured while deleting record. Please try again.');
        }
    }
    /**
     * Method: ajaxChangeStatus
     *
     */
}
//End Class