<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Announcements extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->engineinit->_is_not_admin_logged_in_redirect('admin/login');
        // Check rights
        if (rights(69) != true ) {
            redirect(base_url('admin/dashboard'));
        }
        $this->load->model('announcements_model');
    }
// End __construct
    /**
      @Method: index
      @Return: Listing
     */
    public function index() {
        $data['result'] = $this->announcements_model->loadListing();
        $data ['content'] = $this->load->view('announcements/listing', $data, true);
        $this->load->view('templete-view.php', $data);
    }
    /**
     * Method: add
     * Return: Load Add Form
     */
    public function add() {
        $data = array();
        // Check rights
        if (rights(69) != true ) {
            redirect(base_url('admin/dashboard'));
        }
        if ($this->input->post()) {

            /// Banner Photo
            $type = $_POST['announcements_destination_id'];
        if ($_FILES['images']['name'] != '') {
             unlink('uploads/announcements/pic/' . $_POST['old_image']);
            $extension = $this->common->getExtension($_FILES ['images'] ['name']);
            $extension = strtolower($extension);
            if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
                return false;
            }
            $path = 'uploads/announcements/';
            $allow_types = 'gif|jpg|jpeg|png';
            $max_height = '8000';
            $max_width = '8000';
            $image = $this->common->do_upload_banner($path, $allow_types, $max_height, $max_width, $_FILES ['images']['tmp_name'], $_FILES ['images']['name'], $type);
        }

                $db_query = $this->announcements_model->saveItem($_POST, $image);

            if ($db_query) {
                $this->session->set_flashdata('success_message', 'Information successfully saved.');
                redirect('admin/announcements'); // due to flash data.
            } else {
                $this->session->set_flashdata('error_message', 'Opps! Error saving informtion. Please try again.');
            }
        }
        $data['action'] = 'add';
        $data['all_locations'] = $this->announcements_model->allannouncementsLocations();
        $data ['content'] = $this->load->view('announcements/form', $data, true); //Return View as data
        $this->load->view('templete-view.php', $data);
    }
    /**
     * Method: edit
     * Return: Load Edit Form
     */
    public function edit($id) {
        // Check rights
        if (rights(70) != true ) {
            redirect(base_url('admin/dashboard'));
        }
         $itemId = $this->common->decode($id);
        $data['id'] = $itemId;
        $data['row'] = $this->announcements_model->getRow($itemId);
        $data['action'] = 'edit';
        $data['all_locations'] = $this->announcements_model->allannouncementsLocations();
        $data ['content'] = $this->load->view('announcements/form', $data, true); //Return View as data
        $this->load->view('templete-view.php', $data);
    }
    /**
      @Method: delete
      @Params: itemId
      @Retrun: True/False
     */
    public function delete($id) {
        // Check rights
        if (rights(71) != true ) {
            redirect(base_url('admin/dashboard'));
        }
        $itemId = $this->common->decode($id);
        $row = $this->announcements_model->getRow($itemId);
            unlink('uploads/announcements/pic/' . $row['images']);
        $result = $this->announcements_model->deleteItem($itemId);

        if ($result) {
                $this->session->set_flashdata('success_message', 'Record deleted successfully.');
                redirect('admin/announcements'); // due to flash data.
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
        $result = $this->announcements_model->updateItemStatus($itemId, $status);
        echo $result;
    }

}
//End Class