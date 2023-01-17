<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pages extends CI_Controller {

    public function __construct() {
        parent::__construct();
        error_reporting(0);
        $this->load->model('pages_model');
    }

    public function index($slug,$type = '') {

        $data = array();
        if (isset($slug) && $slug != '') {

            $data['pages'] = fetchCmsPages($slug);
            $data['title'] = $data['pages']['title'];
            $data['meta_keywords'] = $data['pages']['meta_keywords'];
            $data['meta_description'] = $data['pages']['meta_description'];

            if(empty($data['pages']))
            {
                show_404();
            }
            
            $data ['content'] = $this->load->view('pages_default', $data, true);
            if($type == 'm'){
                $this->load->view('includes/template_fullbody_mobile.view.php', $data);
            }else{
                $this->load->view('includes/template_fullbody.view.php', $data);
            }
            
        } else {
            show_404();
        }
    }

    function contactus($slug) {

        $this->load->library('emailutility');
        $data['pages'] = fetchCmsPages($slug);

            $data = array();
            if ($_SERVER['HTTP_CLIENT_IP'])
                $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
            else if ($_SERVER['HTTP_X_FORWARDED_FOR'])
                $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            else if ($_SERVER['HTTP_X_FORWARDED'])
                $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
            else if ($_SERVER['HTTP_FORWARDED_FOR'])
                $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
            else if ($_SERVER['HTTP_FORWARDED'])
                $ipaddress = $_SERVER['HTTP_FORWARDED'];
            else if ($_SERVER['REMOTE_ADDR'])
                $ipaddress = $_SERVER['REMOTE_ADDR'];
            else
                $ipaddress = 'UNKNOWN';

            $data['name'] = $name = trim($this->input->post('user_name'));
            $data['email'] = $email = trim($this->input->post('email_address'));
            $data['subject'] = $subject = trim($this->input->post('subject'));
            $data['comments'] = $message = nl2br($this->input->post('comments'));

            $data['phone'] = $number = trim($this->input->post('phone'));
            $data['ip_address'] = $ipaddress;
            $data['status'] = 0;
            /*             * ** Send Email Start ***** */

            $data['receiver_name'] = 'Admin';
            $data['email_content'] = "You have received new contact inquiry from <strong>" . SITE_NAME . "</strong>.<br /><br />Please see the details below.<br /><br />

<strong>Name:</strong>&nbsp; " . ucwords($name) . " <br />
<strong>Email:</strong>&nbsp; " . $email . " <br />
<strong>Phone:</strong>&nbsp; " . $number . " <br />
<strong>IP address:</strong>&nbsp; " . $ipaddress . " <br />
<strong>Subject:</strong>&nbsp; " . $subject . " <br />
<strong>Message:</strong><br />" . $message . " <br /> <br />";

            $email_tempData = get_email_tempData(6);

            if (!empty($email_tempData)) {
                $data['title'] = $email_tempData['title'];
                $data['content'] = $email_tempData['content'];

                $data['welcome_content'] = $email_tempData['welcome_content'];
                $data['footer'] = $email_tempData['footer'];

                $subject = $data['title'];

                $email_content = $this->load->view('includes/email_templates/email_template', $data, true);
                $this->emailutility->send_contact_inquiry($email_content, $subject);
            }
            unset($data['email_content']);
            unset($data['title']);
            unset($data['content']);
            unset($data['welcome_content']);
            unset($data['footer']);
            unset($data['receiver_name']);

            $result = save_feedback($data);

            $this->session->set_flashdata('success_message', 'Contact inquiry sent successfully.');
                redirect(base_url($slug)); // due to flash data.


        $data['meta_keywords'] = $data['pages']['meta_keywords'];
        $data['meta_description'] = $data['pages']['meta_description'];

        $data ['content'] = $this->load->view('pages_default', $data, true);
        $this->load->view('includes/template_fullbody.view.php', $data);

    }

}

/* End of file pages.php */
