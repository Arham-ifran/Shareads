<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Feedback extends CI_Controller
{

    public function __construct()
    {
//        error_reporting(E_ALL);
        parent::__construct();
        // check if admin login
        $this->engineinit->_is_not_admin_logged_in_redirect('admin/login');
        // Check rights
        if (rights(48) != true)
        {
            redirect(base_url('admin/dashboard'));
        }
        $this->load->model('feedback_model');
        $this->load->library('emailutility');
    }

// End __construct
    /**
      @Method: index
      @Return: feedback Listing
     */
    public function index()
    {
//        $data['result']   = $this->feedback_model->loadListing();
//        $data['pagination'] = $this->pagination->create_links();
        $data ['content'] = $this->load->view('feedback/listing', $data, true);
        $this->load->view('templete-view.php', $data);
    }

    /**
     * Method: reply
     * Return: Load reply Form
     */
    public function reply_user($id)
    {
        $data           = array();
        $data['action'] = 'edit';
        $data['id']     = $id;
        $data['row']    = $this->feedback_model->getRow($id);

        $data ['content'] = $this->load->view('feedback/form', $data, true); //Return View as data
        $this->load->view('templete-view.php', $data);
    }

    public function reply_user_ajax($id)
    {
        $data           = array();
        $data['action'] = 'edit';
        $data['id']     = $id;
        $data['row']    = $this->feedback_model->getRow($id);

        $html             = $data ['content'] = $this->load->view('feedback/form_ajax', $data, true); //Return View as data
        echo json_encode(array('status' => 1, 'form' => $html));
    }

    /**
      @Method: delete
      @Params: itemId
      @Retrun: True/False
     */
    public function delete($id)
    {
        // Check rights
        if (rights(50) != true)
        {
            redirect(base_url('admin/dashboard'));
        }
        $itemId = $this->common->decode($id);
        $result = $this->feedback_model->deleteItem($itemId);
        if ($result)
        {
            $this->session->set_flashdata('success_message', 'Record deleted successfully.');
            redirect('admin/feedback'); // due to flash data.
        }
        else
        {
            $this->session->set_flashdata('error_message', 'Opps! Error occured while deleting record. Please try again.');
        }
    }

    /**
      @Method: send
      @return: true/false
     */
    public function send()
    {
        $data['feedId']     = $feedId             = trim($this->input->post('feedId'));
        $data['name']       = trim($this->input->post('userName'));
        $data['from_email'] = trim($this->input->post('from_email'));
        $data['subject']    = trim($this->input->post('subject'));
        $data['message']    = nl2br($this->input->post('message'));
        $data['to_email']   = trim($this->input->post('to_email'));
        $result             = $this->feedback_model->updateFeedbackStatus($feedId);

        $data['receiver_name'] = $data['name'];
        $data['email_content'] = "You have received new contact inquiry from <strong>" . SITE_NAME . "</strong>.<br /><br />Please see the details below.<br /><br />

<strong>Subject:</strong>&nbsp; " . $data['subject'] . " <br />
<strong>Message:</strong><br />" . $data['message'] . " <br /> <br />";

        $email_tempData = get_email_tempData(3);

        if (!empty($email_tempData))
        {
            $data['title']   = $email_tempData['title'];
            $data['content'] = $email_tempData['content'];

            $data['footer'] = $email_tempData['footer'];

            $subject = $data['subject'];

            $email_content = $this->load->view('includes/email_templates/email_template', $data, true);
            $this->emailutility->send_email_user($email_content, $data['to_email'], $subject);
        }

        if ($result)
        {
            $this->session->set_flashdata('success_message', 'Email has been sent to user.');
            redirect('admin/feedback'); // due to flash data.
        }
        else
        {
            $this->session->set_flashdata('error_message', 'Opps! Error occured while deleting record. Please try again.');
        }
    }

    public function send_ajax()
    {
        $data['feedId']     = $feedId             = trim($this->input->post('feedId'));
        $data['name']       = trim($this->input->post('userName'));
        $data['from_email'] = trim($this->input->post('from_email'));
        $data['subject']    = trim($this->input->post('subject'));
        $data['message']    = nl2br($this->input->post('message'));
        $data['to_email']   = trim($this->input->post('to_email'));
        $result             = $this->feedback_model->updateFeedbackStatus($feedId);

        $data['receiver_name'] = $data['name'];
        $data['email_content'] = "You have received new contact inquiry from <strong>" . SITE_NAME . "</strong>.<br /><br />Please see the details below.<br /><br />

<strong>Subject:</strong>&nbsp; " . $data['subject'] . " <br />
<strong>Message:</strong><br />" . $data['message'] . " <br /> <br />";

        $email_tempData = get_email_tempData(3);

        if (!empty($email_tempData))
        {
            $data['title']   = $email_tempData['title'];
            $data['content'] = $email_tempData['content'];

            $data['footer'] = $email_tempData['footer'];

            $subject = $data['subject'];

            $email_content = $this->load->view('includes/email_templates/email_template', $data, true);
            $this->emailutility->send_email_user($email_content, $data['to_email'], $subject);
        }

        if ($result)
        {
            $this->session->set_flashdata('success_message', 'Email has been sent to user.');
            echo 1;
        }
        else
        {
            echo 0;
        }
    }

    public function pagination()
    {
        $list = $this->feedback_model->get_datatables();
        $data = array();
        $no   = $_POST['start'];
        foreach ($list as $rec)
        {
            $row   = array();
            $row[] = ucwords($rec->name);
            $row[] = ucwords($rec->email);
            $row[] = ucwords($rec->phone);
            $row[] = $rec->email;
            if ($rec->status == 1)
            {
                $row[] = '<span class="label_' . $rec->feedId . ' label label-success">Provided</span>';
            }
            else
            {
                $row[] = '<span class="label_' . $rec->feedId . ' label label-warning">Pending</span>';
            }
            $actions = '<div class="hidden-sm hidden-xs btn-group">';
            if ($rec->status == 1)
            {
                $actions .= '&nbsp;<a title="Status" href="javascript:void(0);" class="blue status_button' . $rec->feedId . '" onclick=updateStatus("admin_users",' . $rec->feedId . ',1)><i class="ace-icon fa fa-plus bigger-130"></i></a>';
            }
            else
            {
                $actions .= '&nbsp;<a title="Status" href="javascript:void(0);" class="red status_button' . $rec->feedId . '" onclick=updateStatus("admin_users",' . $rec->feedId . ',0)><i class="ace-icon fa fa-minus bigger-130"></i></a>';
            }
            $row[] = '<button type="button" style="background: transparent;border: none;" onclick="reply_user(' . $rec->feedId . ')"><span class="label label-primary" style="cursor: pointer;">Reply Customer</span></button>';
            if ($this->session->userdata('role_id') == 0)
            {
                $actions .= '&nbsp;<a  title="Delete" class="red" onclick="return delete_confirm();" href="' . base_url('admin/feedback/delete/' . $this->common->encode($rec->feedId)) . '" onclick="return delete_confirm()">';
                $actions .= '<i class="ace-icon fa fa-trash-o bigger-130"></i>';
                $actions .= '</a>';
            }
            $actions .= '</div>';
            $row[] = $actions;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->feedback_model->count_all(),
            "recordsFiltered" => $this->feedback_model->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }

}

//End Class