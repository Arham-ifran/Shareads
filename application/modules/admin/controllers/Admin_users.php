<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin_Users extends CI_Controller {

    public function __construct() {
        parent::__construct();
//        error_reporting(E_ALL);
        // check if admin login
        $this->engineinit->_is_not_admin_logged_in_redirect('admin/login');
        // Check rights
        if (rights(2) != true ) {
            redirect(base_url('admin/dashboard'));
        }
        $this->load->model('admin_users_model');
    }

// End __construct
    /**
      @Method: index
      @Return: Listing
     */
    public function index() {

//        $data['result'] = $this->admin_users_model->loadListing();
//        $data['pagination'] = $this->pagination->create_links();
        $data ['content'] = $this->load->view('admin_users/listing', $data, true);
        $this->load->view('templete-view', $data);
    }

    /**
     * Method: add
     * Return: Load Add Form
     */
    public function add() {
        $data = array();
        // Check rights
        if (rights(3) != true ) {
            redirect(base_url('admin/dashboard'));
        }
        if ($this->input->post()) {
            /// Profile Photo
            if ($_FILES['photo']['name'] != '') {
                unlink('uploads/admin_users/pic/' . $_POST['old_photo']);
                unlink('uploads/admin_users/small/' . $_POST['old_photo']);
                unlink('uploads/admin_users/medium/' . $_POST['old_photo']);
                $extension = $this->common->getExtension($_FILES ['photo'] ['name']);
                $extension = strtolower($extension);
                if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
                    return false;
                }
                $path = 'uploads/admin_users/';
                $allow_types = 'gif|jpg|jpeg|png';
                $max_height = '8000';
                $max_width = '8000';
                $photo = $this->common->do_upload_profile($path, $allow_types, $max_height, $max_width, $_FILES ['photo']['tmp_name'], $_FILES ['photo']['name']);
            }

                $db_query = $this->admin_users_model->saveItem($_POST, $photo);

            if ($db_query) {
                $this->session->set_flashdata('success_message', 'Information successfully saved.');
                redirect('admin/admin_users'); // due to flash data.
            } else {
                $this->session->set_flashdata('error_message', 'Opps! Error saving informtion. Please try again.');
            }
        }

        $data['action'] = 'add';
        $data["all_roles"] = $this->admin_users_model->get_roles();
        $data ['content'] = $this->load->view('admin_users/form', $data, true); //Return View as data
        $this->load->view('templete-view', $data);
    }

    /**
     * Method: edit
     * Return: Load Edit Form
     */
    public function edit($id) {
        // Check rights
        if (rights(4) != true ) {
            redirect(base_url('admin/dashboard'));
        }
        $itemId = $this->common->decode($id);
        $data['id'] = $itemId;
        $data['row'] = $this->admin_users_model->getRow($itemId);
        $data['action'] = 'edit';
        $data["all_roles"] = $this->admin_users_model->get_roles();
        $data ['content'] = $this->load->view('admin_users/form', $data, true); //Return View as data
        $this->load->view('templete-view', $data);
    }

    /**
      @Method: delete
      @Params: itemId
      @Retrun: True/False
     */
    public function delete($id) {
        // Check rights
        if (rights(5) != true ) {
            redirect(base_url('admin/dashboard'));
        }
        $itemId = $this->common->decode($id);
        $result = $this->admin_users_model->deleteItem($itemId);
        if ($result) {
                $this->session->set_flashdata('success_message', 'Record deleted successfully.');
                redirect('admin/admin_users'); // due to flash data.
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
        $result = $this->admin_users_model->updateItemStatus($itemId, $status);
        echo $result;
    }


    /**
     * Method: checkEmail
     *
     */
    public function checkEmail() {


        $email = $this->input->post('email');
        $name = $this->admin_users_model->checkEmail($email);
        if ($name == 0) {
            echo 0;
        } else {
            echo 1;
        }

        exit;
    }
    
    public function pagination()
    {
        $list = $this->admin_users_model->get_datatables();
        $data = array();
        $no   = $_POST['start'];
        foreach ($list as $rec)
        {
            $row   = array();
            $row[] = ucwords($rec->full_name);
            $row[] = ucwords($rec->role);
            $row[] = $rec->email;
            if ($rec->status == 1)
            {
                $row[] = '<span class="label label-sm label-info status_label' . $rec->user_id . '">Active</span>';
            }
            else
            {
                $row[] = '<span class="label label-sm label-danger status_label' . $rec->user_id . '">Inactive</span>';
            }
            $actions = '<div class="hidden-sm hidden-xs btn-group">';
            if ($rec->status == 1)
            {
                $actions .= '&nbsp;<a title="Status" href="javascript:void(0);" class="blue status_button' . $rec->user_id . '" onclick=updateStatus("admin_users",' . $rec->user_id . ',1)><i class="ace-icon fa fa-plus bigger-130"></i></a>';
            }
            else
            {
                $actions .= '&nbsp;<a title="Status" href="javascript:void(0);" class="red status_button' . $rec->user_id . '" onclick=updateStatus("admin_users",' . $rec->user_id . ',0)><i class="ace-icon fa fa-minus bigger-130"></i></a>';
            }
            $actions .= '&nbsp;<a title="Edit" class="green" href="' . base_url('admin/admin_users/edit/' . $this->common->encode($rec->user_id)) . '">';
            $actions .= '<i class="ace-icon fa fa-pencil bigger-130"></i>';
            $actions .= '</a>';
            if ($this->session->userdata('role_id') == 0)
            {
                $actions .= '&nbsp;<a  title="Delete" class="red" onclick="return delete_confirm();" href="' . base_url('admin/admin_users/delete/' . $this->common->encode($rec->user_id)) . '" onclick="return delete_confirm()">';
                $actions .= '<i class="ace-icon fa fa-trash-o bigger-130"></i>';
                $actions .= '</a>';
            }
            $actions .= '</div>';
            $row[] = $actions;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->admin_users_model->count_all(),
            "recordsFiltered" => $this->admin_users_model->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }

}

//End Class