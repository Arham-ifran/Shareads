<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Newsletter extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->engineinit->_is_not_admin_logged_in_redirect('admin/login');
        // Check rights
        if (rights(29) != true)
        {
            redirect(base_url('admin/dashboard'));
        }
        $this->load->model('newsletter_model');
        $this->load->library('emailutility');
//        ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
    }

// End __construct
    /**
      @Method: index
      @Return: Listing
     */
    public function index()
    {
//        $data['result'] = $this->newsletter_model->loadListing();
        $data ['content'] = $this->load->view('newsletter/listing', $data, true);
        $this->load->view('templete-view.php', $data);
    }

    /**
      @Method:  Delete subscribe user email address from mailchimp and also
     * form database change newsletter_subscriber to 0
      @Params: itemId
      @Retrun: True/False
     */
    public function delete($itemId, $type)
    {
        $user_id = $this->common->decode($itemId);
        if ($type == 1)
        {
            $user = $this->newsletter_model->getNewsletterData($user_id);
        }
        else
        {
            $user = getUserData($user_id);
        }
        $chimp_email = $user['email'];
        $status      = 0;
        if ($type == 1)
        {
            $result = $this->newsletter_model->deleteNewsLetter($user_id, $status);
        }
        else
        {
            $result = $this->newsletter_model->updateItemStatus($user_id, $status);
        }

        $list_id = MAIL_CHIMP_ID;
        $api_key = MAIL_CHIMP_KEY;
        $this->load->library('MCAPI', array('apikey' => $api_key));
        if ($this->mcapi->listUnsubscribe($list_id, $chimp_email) === true)
        {
            $this->mcapi->listUnsubscribe($list_id, $chimp_email);
        }
        else
        {
            $message = 'Error: ' . $this->mcapi->errorMessage;
        }

        if ($result == true)
        {
            $this->session->set_flashdata('success_message', 'Record deleted successfully.');
            redirect('admin/newsletter'); // due to flash data.
        }
        else
        {
            $this->session->set_flashdata('error_message', $message);
            redirect('admin/newsletter'); // due to flash data.
        }
    }

    function export_excel()
    {
        $result = $this->newsletter_model->get_export_newsLetters();
        $this->load->library('excel_xml');
        $this->excel_xml->to_excel($result, 'Yatching-Newsletter');
    }

    function export_csv()
    {

        header("Content-Type: application/csv");
        header("Content-Disposition: attachment;Filename=Shareads-Newsletter.csv");
        $query     = $this->newsletter_model->get_export_newsLetters();
        $this->load->dbutil();
        $delimiter = ",";
        $newline   = "\r\n";
        echo $this->dbutil->csv_from_result($query, $delimiter, $newline);
    }

    function send_newsletter()
    {
        $user_data = $this->newsletter_model->loadAllSubscribers();
        if ($this->input->post())
        {
            foreach ($user_data->result_array() as $row)
            {

                $email = $row['email'];
                if ($this->input->post('title') <> '')
                {
                    $data['title']   = $this->input->post('title');
                    $data['content'] = $this->input->post('content');
                    $data['footer']  = $this->input->post('footer');
                }
                else
                {
                    $data['title']   = SITE_NAME;
                    $data['content'] = $this->load->view('includes/email_templates/email_content.php', $data, true);
                    $data['footer']  = $this->load->view('includes/email_templates/email_footer.php', $data, true);
                }
                /*                 * *****************WELCOME EMAIL********************** */
                /*                 * ** Send NEWSLETTER Email Start ***** */
                $data['email_content'] = "We hope you enjoy " . SITE_NAME . " and if there's anything you would like to ask or leave a feedback, please contact us <a href='mailto:" . ADMIN_EMAIL . "'>directly via mail</a>.
                      <br /><br />Thank You,<br />";
                $subjects              = $data['title'];
                $email_contents        = $this->load->view('includes/email_templates/email_template', $data, true);
                $db_query              = $this->emailutility->send_email_user($email_contents, $email, $subjects);
                unset($data);
                /*                 * ** Send WELCOME Email End ***** */
            }
            $this->session->set_flashdata('success_message', 'Newsletter sent successfully.');
            redirect('admin/newsletter'); // due to flash data.
        }
        $email_tempData = get_email_tempData(4);
        $id             = 0;
        if (empty($email_tempData))
        {
            $email_footer            = './application/views/includes/email_templates/email_footer.php';
            $email_content           = './application/views/includes/email_templates/email_content.php';
            $handl                   = fopen($email_content, "rb");
            $content                 = fread($handl, filesize($email_content));
            fclose($handl);
            $data['content_data']    = ($content);
            $handle                  = fopen($email_footer, "rb");
            $contents                = fread($handle, filesize($email_footer));
            fclose($handle);
            $data['footer_data']     = ($contents);
            $data['welcome_content'] = '';
        }
        else
        {
            $data['title']           = $email_tempData['title'];
            $data['welcome_content'] = $email_tempData['welcome_content'];
            $data['content_data']    = $email_tempData['content'];
            $data['footer_data']     = $email_tempData['footer'];
            $data['id']              = $id                      = $email_tempData['id'];
            $data['is_active']       = $email_tempData['is_active'];
        }
        $data ['content'] = $this->load->view('newsletter/form', $data, true);
        $this->load->view('templete-view.php', $data);
    }

    public function pagination()
    {
        $list = $this->newsletter_model->get_datatables();
        $data = array();
        $no   = $_POST['start'];
        foreach ($list as $rec)
        {
            $row     = array();
            $row[]   = ucwords($rec->full_name);
            $row[]   = $rec->email;
            $actions = '<div class="hidden-sm hidden-xs btn-group">';
            if (rights(31) == true)
            {
                $actions .= '&nbsp;<a  title="Delete" class="red" onclick="return delete_confirm();" href="' . base_url('admin/feedback/delete/' . $this->common->encode($rec->id)) . '" onclick="return delete_confirm()">';
                $actions .= '<i class="ace-icon fa fa-trash-o bigger-130"></i>';
                $actions .= '</a>';
            }
            $actions .= '</div>';
            $row[] = $actions;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->newsletter_model->count_all(),
            "recordsFiltered" => $this->newsletter_model->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }

}

//End Class