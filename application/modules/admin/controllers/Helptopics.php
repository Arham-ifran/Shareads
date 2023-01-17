<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Helptopics extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->engineinit->_is_not_admin_logged_in_redirect('admin/login');
        if (false)
        {
            redirect(base_url('admin/dashboard'));
        }
        $this->load->model('helptopics_model');
    }

    public function index()
    {
        $data['result']   = $this->helptopics_model->loadListing();
        $data ['content'] = $this->load->view('helptopics/listing', $data, true);
        $this->load->view('templete-view', $data);
    }

    public function add()
    {
        $data = array();
        if (false)
        {
            redirect(base_url('admin/dashboard'));
        }
        if ($this->input->post())
        {

            $db_query = $this->helptopics_model->saveItem($_POST);

            if ($db_query)
            {
                $this->session->set_flashdata('success_message', 'Information successfully saved.');
                redirect('admin/helptopics'); // due to flash data.
            }
            else
            {
                $this->session->set_flashdata('error_message', 'Opps! Error saving informtion. Please try again.');
            }
        }
        $data['action']                = 'add';
        $data ['content']              = $this->load->view('helptopics/form', $data, true); //Return View as data
        $this->load->view('templete-view', $data);
    }

    public function edit($id)
    {
        if (false)
        {
            redirect(base_url('admin/dashboard'));
        }
        $itemId                        = $this->common->decode($id);
        $data['id']                    = $itemId;
        $data['row']                   = $this->helptopics_model->getRow($itemId);
        $data['action']                = 'edit';
        $data ['content']              = $this->load->view('helptopics/form', $data, true); //Return View as data
        $this->load->view('templete-view', $data);
    }

    public function delete($id)
    {
        if (false)
        {
            redirect(base_url('admin/dashboard'));
        }
        $itemId = $this->common->decode($id);
        $result = $this->helptopics_model->deleteItem($itemId);
        if ($result)
        {
            $this->session->set_flashdata('success_message', 'Record deleted successfully.');
            redirect('admin/helptopics'); // due to flash data.
        }
        else
        {
            $this->session->set_flashdata('error_message', 'Opps! Error occured while deleting record. Please try again.');
        }
    }

    public function ajaxChangeStatus()
    {
        $itemId = $_POST['itemId'];
        $status = $_POST['status'];
        $result = $this->helptopics_model->updateItemStatus($itemId, $status);
        echo $result;
    }

    public function checkPage()
    {
        $data = array();
        $slug = $this->input->post('slug');
        $name = $this->helptopics_model->checkPage($slug);
        if ($name == 0)
        {
            $page_name = preg_replace('~[^\\pL\d]+~u', '-', trim($slug));
            $page_name = trim($page_name, '-');
            $page_name = iconv('utf-8', 'us-ascii//TRANSLIT', $page_name);
            $page_name = strtolower($page_name);
            $page_name = preg_replace('~[^-\w]+~', '', $page_name);
            $pageTitle = $this->helptopics_model->checkPage($page_name);
            if ($pageTitle == 1)
            {
                $data ['slug'] = $page_name . strtotime(date("Y-m-d H:i:s"));
            }
            else
                $data ['slug'] = $page_name;
        } else
        {
            $data ['slug'] = 1;
        }
        echo $data ['slug'];
        exit;
    }

    public function ajaxUpdateOrder()
    {
        $id = $this->input->post('ordId');
        foreach ($id as $res)
        {
            $val = $this->input->post('order_' . $res . '');
            $this->helptopics_model->updateOrder($res, $val);
        }
        echo 'helptopics order updated successfully.';
        exit();
    }

}
