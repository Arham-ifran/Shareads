<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Blog_categories extends CI_Controller {
    public function __construct() {
        parent::__construct();
//        error_reporting(E_ALL);
        $this->engineinit->_is_not_admin_logged_in_redirect('admin/login');
        // Check rights
        if (rights(97) != true ) {
            redirect(base_url('admin/dashboard'));
        }
        $this->load->model('blog_categories_model');
    }
// End __construct
    /**
      @Method: index
      @Return: Listing
     */
    public function index() {
        $data['result'] = $this->blog_categories_model->loadListing();
        $data ['content'] = $this->load->view('blog_categories/listing', $data, true);
        $this->load->view('templete-view.php', $data);
    }
    /**
     * Method: add
     * Return: Load Add Form
     */
    public function add() {
        $data = array();
        // Check rights
        if (rights(98) != true ) {
            redirect(base_url('admin/dashboard'));
        }
        if ($this->input->post()) {

                $db_query = $this->blog_categories_model->saveItem($_POST);

            if ($db_query) {
                $this->session->set_flashdata('success_message', 'Information successfully saved.');
                redirect('admin/blog_categories'); // due to flash data.
            } else {
                $this->session->set_flashdata('error_message', 'Opps! Error saving informtion. Please try again.');
            }
        }
        $data['action'] = 'add';
        $data ['content'] = $this->load->view('blog_categories/form', $data, true);//Return View as data
        $this->load->view('templete-view.php', $data);
    }
    /**
     * Method: edit
     * Return: Load Edit Form
     */
    public function edit($id) {
        // Check rights
        if (rights(99) != true ) {
            redirect(base_url('admin/dashboard'));
        }
         $itemId = $this->common->decode($id);
        $data['id'] = $itemId;
        $data['row'] = $this->blog_categories_model->getRow($itemId);
       $data['action'] = 'edit';
       $data ['content'] = $this->load->view('blog_categories/form', $data, true);//Return View as data
        $this->load->view('templete-view.php', $data);
    }
    /**
      @Method: delete
      @Params: itemId
      @Retrun: True/False
     */
    public function delete($id) {
        // Check rights
        if (rights(100) != true ) {
            redirect(base_url('admin/dashboard'));
        }
        $itemId = $this->common->decode($id);
        $result = $this->blog_categories_model->deleteItem($itemId);
        if ($result) {
                $this->session->set_flashdata('success_message', 'Record deleted successfully.');
                redirect('admin/blog_categories'); // due to flash data.
            } else {
                $this->session->set_flashdata('error_message', 'Opps! Error occured while deleting record. Please try again.');
            }
    }
    /**
     * Method: ajaxChangeStatus
     *
     */
    public function ajaxChangeStatus() {
        $itemId = $_POST['itemId'];
        $status = $_POST['status'];
        $result = $this->blog_categories_model->updateItemStatus($itemId, $status);
        echo $result;
    }


}
//End Class