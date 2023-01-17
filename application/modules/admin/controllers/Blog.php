<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Blog extends CI_Controller {

    public function __construct() {
        parent::__construct();
//        error_reporting(E_ALL);
        // check if admin login
        $this->engineinit->_is_not_admin_logged_in_redirect('admin/login');
        // Check rights
        if (rights(97) != true) {
            redirect(base_url('admin/dashboard'));
        }
        $this->load->model('blog_model');
    }

// End __construct
    /**
      @Method: index
      @Return: Listing
     */
    public function index() {
        $data['result'] = $this->blog_model->loadListing();
        $data ['content'] = $this->load->view('blog/listing', $data, true);
        $this->load->view('templete-view', $data);
    }

    /**
     * Method: add
     * Return: Load Add Form
     */
    public function add() {
        $data = array();
        // Check rights
        if (rights(98) != true) {
            redirect(base_url('admin/dashboard'));
        }
        if ($this->input->post()) {
            
            if ($_FILES['photo']['name'] != '') {
                unlink('uploads/blogs/pic/' . $_POST['old_photo']);
                $extension = $this->common->getExtension($_FILES ['photo'] ['name']);
                $extension = strtolower($extension);
                if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
                    return false;
                }
                $path = 'uploads/blogs/';
                $allow_types = 'gif|jpg|jpeg|png';
                $max_height = '8000';
                $max_width = '8000';
                $photo = $this->common->do_upload_image($path, $allow_types, $max_height, $max_width, $_FILES ['photo']['tmp_name'], $_FILES ['photo']['name']);
                
                }
            $db_query = $this->blog_model->saveItem($_POST, $photo);

            if ($db_query) {
                $this->session->set_flashdata('success_message', 'Information successfully saved.');
                redirect('admin/blog'); // due to flash data.
            } else {
                $this->session->set_flashdata('error_message', 'Opps! Error saving informtion. Please try again.');
            }
        }

        $data['action'] = 'add';
        $data["blog_categories"] = $this->blog_model->get_blog_categories();
        $data ['content'] = $this->load->view('blog/form', $data, true); //Return View as data
        $this->load->view('templete-view', $data);
    }

    /**
     * Method: edit
     * Return: Load Edit Form
     */
    public function edit($id) {
        // Check rights
        if (rights(99) != true) {
            redirect(base_url('admin/dashboard'));
        }
        $itemId = $this->common->decode($id);
        $data['id'] = $itemId;
        $data['row'] = $this->blog_model->getRow($itemId);
        $data['action'] = 'edit';
        $data["blog_categories"] = $this->blog_model->get_blog_categories();
        $data ['content'] = $this->load->view('blog/form', $data, true); //Return View as data
        $this->load->view('templete-view', $data);
    }

    /**
      @Method: delete
      @Params: itemId
      @Retrun: True/False
     */
    public function delete($id) {
        // Check rights
        if (rights(100) != true) {
            redirect(base_url('admin/dashboard'));
        }
        $itemId = $this->common->decode($id);
        $result = $this->blog_model->deleteItem($itemId);
        if ($result) {
            $this->session->set_flashdata('success_message', 'Record deleted successfully.');
            redirect('admin/blog'); // due to flash data.
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
        $result = $this->blog_model->updateItemStatus($itemId, $status);
        echo $result;
    }

    /**
     * Method: Check Page & create Slug
     *
     */
    public function checkPage() {
        $data = array();
        $slug = $this->input->post('slug');
        $name = $this->blog_model->checkPage($slug);
        if ($name == 0) {
            $page_name = preg_replace('~[^\\pL\d]+~u', '-', trim($slug));
            $page_name = trim($page_name, '-');
            $page_name = iconv('utf-8', 'us-ascii//TRANSLIT', $page_name);
            $page_name = strtolower($page_name);
            $page_name = preg_replace('~[^-\w]+~', '', $page_name);
            $pageTitle = $this->blog_model->checkPage($page_name);
            if ($pageTitle == 1) {
                $data ['slug'] = $page_name . strtotime(date("Y-m-d H:i:s"));
            } else
                $data ['slug'] = $page_name;
        } else {
            $data ['slug'] = 1;
        }
        echo $data ['slug'];
        exit;
    }

    /**
     * Method: ajaxUpdateOrder
     * params: post array
     * return: msg
     */
    public function ajaxUpdateOrder() {
        $id = $this->input->post('ordId');
        foreach ($id as $res) {
            $val = $this->input->post('order_' . $res . '');
            $this->blog_model->updateOrder($res, $val);
        }
        echo 'blog order updated successfully.';
        exit();
    }

}

//End Class