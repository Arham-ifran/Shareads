<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Roles extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
//        error_reporting(E_ALL);
        // check if admin login
        $this->engineinit->_is_not_admin_logged_in_redirect('admin/login');
        $this->engineinit->_is_not_super_admin_redirect('admin/dashboard');
        $this->load->model('roles_model');
    }

// End __construct
    /**
      @Method: index
      @Return: Listing
     */
    public function index()
    {

//        $data['result']   = $this->roles_model->loadListing();
//        $data['pagination'] = $this->pagination->create_links();
        $data ['content'] = $this->load->view('roles/listing', $data, true);
        $this->load->view('templete-view', $data);
    }

    public function pagination()
    {
        $list = $this->roles_model->get_datatables();
        $data = array();
        $no   = $_POST['start'];
        foreach ($list as $rec)
        {
            $row   = array();
            $row[] = ucfirst($rec->role);
            if ($rec->status == 1)
            {
                $row[] = '<span class="label label-sm label-info status_label' . $rec->role_id . '">Active</span>';
            }
            else
            {
                $row[] = '<span class="label label-sm label-danger status_label' . $rec->role_id . '">Inactive</span>';
            }
            $actions = '<div class="hidden-sm hidden-xs btn-group">';
            if ($rec->status == 1)
            {
                $actions .= '&nbsp;<a title="Status" href="javascript:void(0);" class="blue status_button' . $rec->role_id . '" onclick=updateStatus("roles",' . $rec->role_id . ',1)><i class="ace-icon fa fa-plus bigger-130"></i></a>';
            }
            else
            {
                $actions .= '&nbsp;<a title="Status" href="javascript:void(0);" class="red status_button' . $rec->role_id . '" onclick=updateStatus("roles",' . $rec->role_id . ',0)><i class="ace-icon fa fa-minus bigger-130"></i></a>';
            }
            $actions .= '&nbsp;<a title="Edit" class="green" href="' . base_url("admin/roles/edit/" . $this->common->encode($rec->role_id)) . '">';
            $actions .= '<i class="ace-icon fa fa-pencil bigger-130"></i>';
            $actions .= '</a>';
            if ($this->session->userdata('role_id') == 0 && $this->session->userdata('role_id') <> $rec->role_id)
            {
                $actions .= '&nbsp;<a  title="Delete" class="red" onclick="return delete_confirm();" href="' . base_url("admin/roles/delete/" . $this->common->encode($rec->role_id)) . '" onclick="return delete_confirm()">';
                $actions .= '<i class="ace-icon fa fa-trash-o bigger-130"></i>';
                $actions .= '</a>';
            }
            $actions .= '</div>';
            $row[] = $actions;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->roles_model->count_all(),
            "recordsFiltered" => $this->roles_model->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    /**
     * Method: add
     * Return: Load Add Form
     */
    public function add()
    {
        $data = array();

        if ($this->input->post())
        {

            $db_query = $this->roles_model->saveItem($_POST);

            if ($db_query)
            {
                $this->session->set_flashdata('success_message', 'Role successfully saved.');
                redirect('admin/roles'); // due to flash data.
            }
            else
            {
                $this->session->set_flashdata('error_message', 'Opps! Error saving informtion. Please try again.');
            }
        }

        $data['action']   = 'add';
        $data ['rights']  = $this->roles_model->rights();
        $data ['content'] = $this->load->view('roles/form', $data, true); //Return View as data
        $this->load->view('templete-view', $data);
    }

    /**
     * Method: edit
     * Return: Load Edit Form
     */
    public function edit($id)
    {
        $itemId           = $this->common->decode($id);
        $data['id']       = $itemId;
        $data['row']      = $this->roles_model->getRow($itemId);
        $data['action']   = 'edit';
        $data ['rights']  = $this->roles_model->rights();
        $data ['content'] = $this->load->view('roles/form', $data, true); //Return View as data
        $this->load->view('templete-view', $data);
    }

    /**
      @Method: delete
      @Params: itemId
      @Retrun: True/False
     */
    public function delete($id)
    {
        $itemId = $this->common->decode($id);
        $result = $this->roles_model->deleteItem($itemId);
        if ($result)
        {
            $this->session->set_flashdata('success_message', 'Record deleted successfully.');
            redirect('admin/roles'); // due to flash data.
        }
        else
        {
            $this->session->set_flashdata('error_message', 'Opps! Error occured while deleting record. Please try again.');
        }
    }

    /**
     * Method: ajaxChangeStatus
     *
     */
    public function ajaxChangeStatus()
    {
        $itemId = $_POST['itemId'];
        $status = $_POST['status'];
        $result = $this->roles_model->updateItemStatus($itemId, $status);
        echo $result;
    }

}

//End Class