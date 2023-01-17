<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Categories extends CI_Controller {

    public $tbl = 'categories';

    public function __construct() {
        parent::__construct();
        error_reporting(0);
        // check if admin login
        $this->engineinit->_is_not_admin_logged_in_redirect('admin/login');
        // Check rights
        if (rights(90) != true) {
            redirect(base_url('admin/dashboard'));
        }
        $this->load->model('categories_model');
    }

// End __construct
    /**
     * @Method: index
     * @Return: Listing
     */

    public function index() {
        $data ['results'] = $this->categories_model->getItems();
        $data ['content'] = $this->load->view('categories/listing', $data, true);
        $this->load->view('templete-view', $data);
    }


    /**
     * Method: add
     * Return: Load Add Form
     */
    public function add() {
        $data = array();
        // Check rights
        if (rights(91) != true) {
            redirect(base_url('admin/dashboard'));
        }

        if ($this->input->post()) {

            /// cat image
            if ($_FILES['image']['name'] != '') {
                unlink('uploads/categories/pic/' . $_POST['old_image']);
                unlink('uploads/categories/small/' . $_POST['old_image']);
                $extension = $this->common->getExtension($_FILES ['image'] ['name']);
                $extension = strtolower($extension);
                if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
                    return false;
                }
                $path = 'uploads/categories/';
                $allow_types = 'gif|jpg|jpeg|png';
                $max_height = '8000';
                $max_width = '8000';
                $image = $this->common->do_upload_category($path, $allow_types, $max_height, $max_width, $_FILES ['image']['tmp_name'], $_FILES ['image']['name']);
            }
                $db_query = $this->categories_model->saveItem($_POST, $image);

            if ($db_query) {
                $this->session->set_flashdata('success_message', 'Information successfully saved.');
                redirect('admin/categories'); // due to flash data.
            } else {
                $this->session->set_flashdata('error_message', 'Opps! Error saving informtion. Please try again.');
            }
        }

        $data ['status'] = 1;
        $data ['action'] = 'add';
        $data ['parent_id'] = 0;
        $data ['content'] = $this->load->view('categories/form', $data, true);
        $this->load->view('templete-view', $data);
    }

    /**
     * Method: edit
     * Return: Load Edit Form
     */
    public function edit($id) {
        // Check rights
        if (rights(92) != true) {
            redirect(base_url('admin/dashboard'));
        }
        $itemId = $this->common->decode($id);
        $data = getColumns($this->tbl);
        $data ['action'] = 'edit';
        $data ['id'] = $itemId;
        $data ['row'] = $this->categories_model->getRow($itemId);
        $data ['content'] = $this->load->view('categories/form', $data, true);
        $this->load->view('templete-view', $data);
    }

    /**
     * @Method: delete
     * @Params: itemId
     * @Retrun: True/False
     */
    public function delete($id) {
        // Check rights
        if (rights(93) != true) {
            redirect(base_url('admin/dashboard'));
        }
        $itemId = $this->common->decode($id);
        $result = $this->categories_model->deleteItem($itemId);
        if ($result) {
            $this->session->set_flashdata('success_message', 'Record deleted successfully.');
            redirect('admin/categories'); // due to flash data.
        } else {
            $this->session->set_flashdata('error_message', 'Opps! Error occured while deleting record. Please try again.');
        }
    }

    /**
     * Method: ajaxChangeStatus
     */
    public function ajaxChangeStatus() {
        $itemId = $_POST ['itemId'];
        $status = $_POST ['status'];
        $result = $this->categories_model->updateItemStatus($itemId, $status);

        echo $result;
    }

    /**
     * Method: generateSlug
     * Params: $inputString
     * Returns: $output
     */
    public function generateSlug() {
        $inputString = $_POST ['istr'];
        $output = $this->slug->create_slug($inputString);
        echo $output;
        exit();
    }

    /**
     * Method: isUniqueTitle
     */
    public function isUniqueTitle() {
        $id = $_POST ['id'];
        $title = $_POST ['field'];
        $result = $this->categories_model->isUniqueTitle($id, $title);
        echo $result;
        exit();
    }

    /**
     * Method: ajax_updateStatus
     */
    public function ajax_updateStatus() {
        echo '<pre>';
        print_r($_POST);
        exit();
    }

    /**
     * Method: Check Page & create Slug
     *
     */
    public function checkCatSlug() {
        $data = array();
        $slug = $this->input->post('slug');
        $name = $this->categories_model->checkCatSlug($slug);
        if ($name == 0) {
            $category_name = preg_replace('~[^\\pL\d]+~u', '-', trim($slug));
            $category_name = trim($category_name, '-');
            $category_name = iconv('utf-8', 'us-ascii//TRANSLIT', $category_name);
            $category_name = strtolower($category_name);
            $category_name = preg_replace('~[^-\w]+~', '', $category_name);
            $pageTitle = $this->categories_model->checkCatSlug($category_name);
            if ($pageTitle == 1) {
                $data ['category_slug'] = $category_name . strtotime(date("Y-m-d H:i:s"));
            } else
                $data ['category_slug'] = $category_name;
        } else {
            $data ['category_slug'] = 1;
        }
        echo $data ['category_slug'];
        exit;
    }

    /**
     * Method: ajaxUpdateOrder
     * params: post array
     * return: msg
     */

    public function ajaxUpdateOrder() {
        $id = $this->input->post('service_category');

        foreach ($id as $key => $res) {
            $this->categories_model->updateOrder($res, 1);
        }
        echo 'Service categories updated successfully.';
        exit();
    }

}

//End Class