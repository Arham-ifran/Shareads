<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Email_templates extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        //        error_reporting(E_ALL);
        $this->engineinit->_is_not_admin_logged_in_redirect('admin/login');
        // Check rights
        if (rights(76) != true)
        {
            redirect(base_url('admin/dashboard'));
        }
        $this->load->model('email_templates_model');
    }

// End __construct
    /**
      @Method: index
      @Return:  Listing
     */
    public function index()
    {
        $this->signup_template();
    }

    //1
    function signup_email_templete()
    {
        $data                = array();
        $posted_data         = array();
        $data['emails_data'] = $this->email_templates_model->get_all_active_email_template(1);
        $id                  = 0;
        if (empty($data['emails_data']))
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
            $data['status']          = 1;
        }
        else
        {
            $data['title']           = $data['emails_data']['title'];
            $data['welcome_content'] = $data['emails_data']['welcome_content'];
            $data['content_data']    = $data['emails_data']['content'];
            $data['footer_data']     = $data['emails_data']['footer'];
            $data['id']              = $id                      = $data['emails_data']['id'];
            $data['status']          = $data['emails_data']['status'];
        }
        if ($this->input->post())
        {
            $this->session->set_flashdata('success_message', '');
            $this->session->set_flashdata('error_message', '');
            $posted_data                        = array();
            $posted_data['title']               = $this->input->post('title');
            $posted_data['welcome_content']     = $this->input->post('welcome_content');
            $posted_data['content']             = $this->input->post('content');
            $posted_data['footer']              = $this->input->post('footer');
            $posted_data['status']              = $this->input->post('status');
            $posted_data['email_template_type'] = 1;
            if ($id == 0)
            {
                $db_query = $this->email_templates_model->add_email_template($posted_data);
            }
            else
            {
                $posted_data['id'] = $id;
                $db_query          = $this->email_templates_model->update_email_template($posted_data);
            }
            if ($db_query)
            {
                $this->session->set_flashdata('success_message', 'Information successfully saved.');
                redirect('admin/email_templates/signup_email_templete'); // due to flash data.
            }
            else
            {
                $this->session->set_flashdata('error_message', 'Opps! Error saving informtion. Please try again.');
            }
        }
        $data ['controller'] = 'signup_email_templete';
        $data['shw_msg']     = 1;
        $data ['content']    = $this->load->view('email_templates/signup_template_form', $data, true);
        $this->load->view('templete-view.php', $data);
    }

    //2
    function forgot_password_email_templete()
    {
        $data                = array();
        $posted_data         = array();
        $data['emails_data'] = $this->email_templates_model->get_all_active_email_template(2);
        $id                  = 0;
        if (empty($data['emails_data']))
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
            $data['status']          = 1;
        }
        else
        {
            $data['title']           = $data['emails_data']['title'];
            $data['welcome_content'] = $data['emails_data']['welcome_content'];
            $data['content_data']    = $data['emails_data']['content'];
            $data['footer_data']     = $data['emails_data']['footer'];
            $data['id']              = $id                      = $data['emails_data']['id'];
            $data['status']          = $data['emails_data']['status'];
        }
        if ($this->input->post())
        {
            $this->session->set_flashdata('success_message', '');
            $this->session->set_flashdata('error_message', '');
            $posted_data                        = array();
            $posted_data['title']               = $this->input->post('title');
            $posted_data['welcome_content']     = $this->input->post('welcome_content') ? $this->input->post('welcome_content') : '';
            $posted_data['content']             = $this->input->post('content');
            $posted_data['footer']              = $this->input->post('footer');
            $posted_data['status']              = $this->input->post('status');
            $posted_data['email_template_type'] = 2;
            if ($id == 0)
            {
                $db_query = $this->email_templates_model->add_email_template($posted_data);
            }
            else
            {
                $posted_data['id'] = $id;
                $db_query          = $this->email_templates_model->update_email_template($posted_data);
            }
            if ($db_query)
            {
                $this->session->set_flashdata('success_message', 'Information successfully saved.');
                redirect('admin/email_templates/forgot_password_email_templete'); // due to flash data.
            }
            else
            {
                $this->session->set_flashdata('error_message', 'Opps! Error saving informtion. Please try again.');
            }
        }
        $data ['controller'] = 'forgot_password_email_templete';
        $data ['content']    = $this->load->view('email_templates/signup_template_form', $data, true);
        $this->load->view('templete-view.php', $data);
    }

    function publisher_invitation()
    {
        $data                = array();
        $posted_data         = array();
        $data['emails_data'] = $this->email_templates_model->get_all_active_email_template(10);
        $id                  = 0;
        if (empty($data['emails_data']))
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
            $data['status']          = 1;
        }
        else
        {
            $data['title']           = $data['emails_data']['title'];
            $data['welcome_content'] = $data['emails_data']['welcome_content'];
            $data['content_data']    = $data['emails_data']['content'];
            $data['footer_data']     = $data['emails_data']['footer'];
            $data['id']              = $id                      = $data['emails_data']['id'];
            $data['status']          = $data['emails_data']['status'];
        }
        if ($this->input->post())
        {
            $this->session->set_flashdata('success_message', '');
            $this->session->set_flashdata('error_message', '');
            $posted_data                        = array();
            $posted_data['title']               = $this->input->post('title');
            $posted_data['welcome_content']     = $this->input->post('welcome_content') ? $this->input->post('welcome_content') : '';
            $posted_data['content']             = $this->input->post('content');
            $posted_data['footer']              = $this->input->post('footer');
            $posted_data['status']              = $this->input->post('status');
            $posted_data['email_template_type'] = 10;
            if ($id == 0)
            {
                $db_query = $this->email_templates_model->add_email_template($posted_data);
            }
            else
            {
                $posted_data['id'] = $id;
                $db_query          = $this->email_templates_model->update_email_template($posted_data);
            }
            if ($db_query)
            {
                $this->session->set_flashdata('success_message', 'Information successfully saved.');
                redirect('admin/email_templates/publisher_invitation'); // due to flash data.
            }
            else
            {
                $this->session->set_flashdata('error_message', 'Opps! Error saving informtion. Please try again.');
            }
        }
        $data ['controller'] = 'publisher_invitation';
        $data ['content']    = $this->load->view('email_templates/signup_template_form', $data, true);
        $this->load->view('templete-view.php', $data);
    }

    //3
    function feedback_email_templete()
    {
        $data                = array();
        $data['action']      = 'add';
        $posted_data         = array();
        $data['emails_data'] = $this->email_templates_model->get_all_active_email_template(3);
        $id                  = 0;
        if (empty($data['emails_data']))
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
            $data['status']          = 1;
        }
        else
        {
            $data['title']           = $data['emails_data']['title'];
            $data['welcome_content'] = $data['emails_data']['welcome_content'];
            $data['content_data']    = $data['emails_data']['content'];
            $data['footer_data']     = $data['emails_data']['footer'];
            $data['id']              = $id                      = $data['emails_data']['id'];
            $data['status']          = $data['emails_data']['status'];
        }
        if ($this->input->post())
        {
            $this->session->set_flashdata('success_message', '');
            $this->session->set_flashdata('error_message', '');
            $posted_data                        = array();
            $posted_data['title']               = $this->input->post('title');
            $posted_data['welcome_content']     = $this->input->post('welcome_content') ? $this->input->post('welcome_content') : '';
            $posted_data['content']             = $this->input->post('content');
            $posted_data['footer']              = $this->input->post('footer');
            $posted_data['status']              = $this->input->post('status');
            $posted_data['email_template_type'] = 3;
            if ($id == 0)
            {
                $db_query = $this->email_templates_model->add_email_template($posted_data);
            }
            else
            {
                $posted_data['id'] = $id;
                $db_query          = $this->email_templates_model->update_email_template($posted_data);
            }
            if ($db_query)
            {
                $this->session->set_flashdata('success_message', 'Information successfully saved.');
                redirect('admin/email_templates/feedback_email_templete'); // due to flash data.
            }
            else
            {
                $this->session->set_flashdata('error_message', 'Opps! Error saving informtion. Please try again.');
            }
        }
        $data ['controller'] = 'feedback_email_templete';
        $data ['content']    = $this->load->view('email_templates/signup_template_form', $data, true);
        $this->load->view('templete-view.php', $data);
    }

    //4
    function newsletter_email_templete()
    {
        $data                = array();
        $data['action']      = 'add';
        $posted_data         = array();
        $data['emails_data'] = $this->email_templates_model->get_all_active_email_template(4);
        $id                  = 0;
        if (empty($data['emails_data']))
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
            $data['status']          = 1;
        }
        else
        {
            $data['title']           = $data['emails_data']['title'];
            $data['welcome_content'] = $data['emails_data']['welcome_content'];
            $data['content_data']    = $data['emails_data']['content'];
            $data['footer_data']     = $data['emails_data']['footer'];
            $data['id']              = $id                      = $data['emails_data']['id'];
            $data['status']          = $data['emails_data']['status'];
        }
        if ($this->input->post())
        {
            $this->session->set_flashdata('success_message', '');
            $this->session->set_flashdata('error_message', '');
            $posted_data                        = array();
            $posted_data['title']               = $this->input->post('title');
            $posted_data['welcome_content']     = $this->input->post('welcome_content') ? $this->input->post('welcome_content') : '';
            $posted_data['content']             = $this->input->post('content');
            $posted_data['footer']              = $this->input->post('footer');
            $posted_data['status']              = $this->input->post('status');
            $posted_data['email_template_type'] = 4;
            if ($id == 0)
            {
                $db_query = $this->email_templates_model->add_email_template($posted_data);
            }
            else
            {
                $posted_data['id'] = $id;
                $db_query          = $this->email_templates_model->update_email_template($posted_data);
            }
            if ($db_query)
            {
                $this->session->set_flashdata('success_message', 'Information successfully saved.');
                redirect('admin/email_templates/newsletter_email_templete'); // due to flash data.
            }
            else
            {
                $this->session->set_flashdata('error_message', 'Opps! Error saving informtion. Please try again.');
            }
        }
        $data ['controller'] = 'newsletter_email_templete';
        $data ['content']    = $this->load->view('email_templates/signup_template_form', $data, true);
        $this->load->view('templete-view.php', $data);
    }

    //5
    function product_email_templete()
    {
        $data                = array();
        $data['action']      = 'add';
        $posted_data         = array();
        $data['emails_data'] = $this->email_templates_model->get_all_active_email_template(5);
        $id                  = 0;
        if (empty($data['emails_data']))
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
            $data['status']          = 1;
        }
        else
        {
            $data['title']           = $data['emails_data']['title'];
            $data['welcome_content'] = $data['emails_data']['welcome_content'];
            $data['content_data']    = $data['emails_data']['content'];
            $data['footer_data']     = $data['emails_data']['footer'];
            $data['id']              = $id                      = $data['emails_data']['id'];
            $data['status']          = $data['emails_data']['status'];
        }
        if ($this->input->post())
        {
            $this->session->set_flashdata('success_message', '');
            $this->session->set_flashdata('error_message', '');
            $posted_data                        = array();
            $posted_data['title']               = $this->input->post('title');
            $posted_data['welcome_content']     = $this->input->post('welcome_content') ? $this->input->post('welcome_content') : '';
            $posted_data['content']             = $this->input->post('content');
            $posted_data['footer']              = $this->input->post('footer');
            $posted_data['status']              = $this->input->post('status');
            $posted_data['email_template_type'] = 5;
            if ($id == 0)
            {
                $db_query = $this->email_templates_model->add_email_template($posted_data);
            }
            else
            {
                $posted_data['id'] = $id;
                $db_query          = $this->email_templates_model->update_email_template($posted_data);
            }
            if ($db_query)
            {
                $this->session->set_flashdata('success_message', 'Information successfully saved.');
                redirect('admin/email_templates/product_email_templete'); // due to flash data.
            }
            else
            {
                $this->session->set_flashdata('error_message', 'Opps! Error saving informtion. Please try again.');
            }
        }
        $data ['controller'] = 'product_email_templete';
        $data ['content']    = $this->load->view('email_templates/signup_template_form', $data, true);
        $this->load->view('templete-view.php', $data);
    }

    //6
    function contact_us_email()
    {
        $data                = array();
        $data['action']      = 'add';
        $posted_data         = array();
        $data['emails_data'] = $this->email_templates_model->get_all_active_email_template(6);
        $id                  = 0;
        if (empty($data['emails_data']))
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
            $data['status']          = 1;
        }
        else
        {
            $data['title']           = $data['emails_data']['title'];
            $data['welcome_content'] = $data['emails_data']['welcome_content'];
            $data['content_data']    = $data['emails_data']['content'];
            $data['footer_data']     = $data['emails_data']['footer'];
            $data['id']              = $id                      = $data['emails_data']['id'];
            $data['status']          = $data['emails_data']['status'];
        }
        if ($this->input->post())
        {
            $this->session->set_flashdata('success_message', '');
            $this->session->set_flashdata('error_message', '');
            $posted_data                        = array();
            $posted_data['title']               = $this->input->post('title');
            $posted_data['welcome_content']     = $this->input->post('welcome_content') ? $this->input->post('welcome_content') : '';
            $posted_data['content']             = $this->input->post('content');
            $posted_data['footer']              = $this->input->post('footer');
            $posted_data['status']              = $this->input->post('status');
            $posted_data['email_template_type'] = 6;
            if ($id == 0)
            {
                $db_query = $this->email_templates_model->add_email_template($posted_data);
            }
            else
            {
                $posted_data['id'] = $id;
                $db_query          = $this->email_templates_model->update_email_template($posted_data);
            }
            if ($db_query)
            {
                $this->session->set_flashdata('success_message', 'Information successfully saved.');
                redirect('admin/email_templates/contact_us_email'); // due to flash data.
            }
            else
            {
                $this->session->set_flashdata('error_message', 'Opps! Error saving informtion. Please try again.');
            }
        }
        $data ['controller'] = 'contact_us_email';
        $data ['content']    = $this->load->view('email_templates/signup_template_form', $data, true);
        $this->load->view('templete-view.php', $data);
    }

    //7
    function payment_email_templete()
    {
        $data                = array();
        $data['action']      = 'add';
        $posted_data         = array();
        $data['emails_data'] = $this->email_templates_model->get_all_active_email_template(7);
        $id                  = 0;
        if (empty($data['emails_data']))
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
            $data['status']          = 1;
        }
        else
        {
            $data['title']           = $data['emails_data']['title'];
            $data['welcome_content'] = $data['emails_data']['welcome_content'];
            $data['content_data']    = $data['emails_data']['content'];
            $data['footer_data']     = $data['emails_data']['footer'];
            $data['id']              = $id                      = $data['emails_data']['id'];
            $data['status']          = $data['emails_data']['status'];
        }
        if ($this->input->post())
        {
            $this->session->set_flashdata('success_message', '');
            $this->session->set_flashdata('error_message', '');
            $posted_data                        = array();
            $posted_data['title']               = $this->input->post('title');
            $posted_data['welcome_content']     = $this->input->post('welcome_content') ? $this->input->post('welcome_content') : '';
            $posted_data['content']             = $this->input->post('content');
            $posted_data['footer']              = $this->input->post('footer');
            $posted_data['status']              = $this->input->post('status');
            $posted_data['email_template_type'] = 7;
            if ($id == 0)
            {
                $db_query = $this->email_templates_model->add_email_template($posted_data);
            }
            else
            {
                $posted_data['id'] = $id;
                $db_query          = $this->email_templates_model->update_email_template($posted_data);
            }
            if ($db_query)
            {
                $this->session->set_flashdata('success_message', 'Information successfully saved.');
                redirect('admin/email_templates/payment_email_templete'); // due to flash data.
            }
            else
            {
                $this->session->set_flashdata('error_message', 'Opps! Error saving informtion. Please try again.');
            }
        }
        $data ['controller'] = 'payment_email_templete';
        $data ['content']    = $this->load->view('email_templates/signup_template_form', $data, true);
        $this->load->view('templete-view.php', $data);
    }

    //8
    function order_email_templete()
    {
        $data                = array();
        $data['action']      = 'add';
        $posted_data         = array();
        $data['emails_data'] = $this->email_templates_model->get_all_active_email_template(8);
        $id                  = 0;
        if (empty($data['emails_data']))
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
            $data['status']          = 1;
        }
        else
        {
            $data['title']           = $data['emails_data']['title'];
            $data['welcome_content'] = $data['emails_data']['welcome_content'];
            $data['content_data']    = $data['emails_data']['content'];
            $data['footer_data']     = $data['emails_data']['footer'];
            $data['id']              = $id                      = $data['emails_data']['id'];
            $data['status']          = $data['emails_data']['status'];
        }
        if ($this->input->post())
        {
            $this->session->set_flashdata('success_message', '');
            $this->session->set_flashdata('error_message', '');
            $posted_data                        = array();
            $posted_data['title']               = $this->input->post('title');
            $posted_data['welcome_content']     = $this->input->post('welcome_content') ? $this->input->post('welcome_content') : '';
            $posted_data['content']             = $this->input->post('content');
            $posted_data['footer']              = $this->input->post('footer');
            $posted_data['status']              = $this->input->post('status');
            $posted_data['email_template_type'] = 8;
            if ($id == 0)
            {
                $db_query = $this->email_templates_model->add_email_template($posted_data);
            }
            else
            {
                $posted_data['id'] = $id;
                $db_query          = $this->email_templates_model->update_email_template($posted_data);
            }
            if ($db_query)
            {
                $this->session->set_flashdata('success_message', 'Information successfully saved.');
                redirect('admin/email_templates/order_email_templete'); // due to flash data.
            }
            else
            {
                $this->session->set_flashdata('error_message', 'Opps! Error saving informtion. Please try again.');
            }
        }
        $data ['controller'] = 'order_email_templete';
        $data ['content']    = $this->load->view('email_templates/signup_template_form', $data, true);
        $this->load->view('templete-view.php', $data);
    }

    //9
    function account_activation_email()
    {
        $data                = array();
        $data['action']      = 'add';
        $posted_data         = array();
        $data['emails_data'] = $this->email_templates_model->get_all_active_email_template(9);
        $id                  = 0;
        if (empty($data['emails_data']))
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
            $data['status']          = 1;
        }
        else
        {
            $data['title']           = $data['emails_data']['title'];
            $data['welcome_content'] = $data['emails_data']['welcome_content'];
            $data['content_data']    = $data['emails_data']['content'];
            $data['footer_data']     = $data['emails_data']['footer'];
            $data['id']              = $id                      = $data['emails_data']['id'];
            $data['status']          = $data['emails_data']['status'];
        }
        if ($this->input->post())
        {
            $this->session->set_flashdata('success_message', '');
            $this->session->set_flashdata('error_message', '');
            $posted_data                        = array();
            $posted_data['title']               = $this->input->post('title');
            $posted_data['welcome_content']     = $this->input->post('welcome_content') ? $this->input->post('welcome_content') : '';
            $posted_data['content']             = $this->input->post('content');
            $posted_data['footer']              = $this->input->post('footer');
            $posted_data['status']              = $this->input->post('status');
            $posted_data['email_template_type'] = 9;
            if ($id == 0)
            {
                $db_query = $this->email_templates_model->add_email_template($posted_data);
            }
            else
            {
                $posted_data['id'] = $id;
                $db_query          = $this->email_templates_model->update_email_template($posted_data);
            }
            if ($db_query)
            {
                $this->session->set_flashdata('success_message', 'Information successfully saved.');
                redirect('admin/email_templates/account_activation_email'); // due to flash data.
            }
            else
            {
                $this->session->set_flashdata('error_message', 'Opps! Error saving informtion. Please try again.');
            }
        }
        $data ['controller'] = 'account_activation_email';
        $data ['content']    = $this->load->view('email_templates/signup_template_form', $data, true);
        $this->load->view('templete-view.php', $data);
    }

    //11
    function new_product()
    {
        $data                = array();
        $data['action']      = 'add';
        $posted_data         = array();
        $data['emails_data'] = $this->email_templates_model->get_all_active_email_template(11);
        $id                  = 0;
        if (empty($data['emails_data']))
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
            $data['status']          = 1;
        }
        else
        {
            $data['title']           = $data['emails_data']['title'];
            $data['welcome_content'] = $data['emails_data']['welcome_content'];
            $data['content_data']    = $data['emails_data']['content'];
            $data['footer_data']     = $data['emails_data']['footer'];
            $data['id']              = $id                      = $data['emails_data']['id'];
            $data['status']          = $data['emails_data']['status'];
        }
        if ($this->input->post())
        {
            $this->session->set_flashdata('success_message', '');
            $this->session->set_flashdata('error_message', '');
            $posted_data                        = array();
            $posted_data['title']               = $this->input->post('title');
            $posted_data['welcome_content']     = $this->input->post('welcome_content') ? $this->input->post('welcome_content') : '';
            $posted_data['content']             = $this->input->post('content');
            $posted_data['footer']              = $this->input->post('footer');
            $posted_data['status']              = $this->input->post('status');
            $posted_data['email_template_type'] = 11;
            if ($id == 0)
            {
                $db_query = $this->email_templates_model->add_email_template($posted_data);
            }
            else
            {
                $posted_data['id'] = $id;
                $db_query          = $this->email_templates_model->update_email_template($posted_data);
            }
            if ($db_query)
            {
                $this->session->set_flashdata('success_message', 'Information successfully saved.');
                redirect('admin/email_templates/new_product'); // due to flash data.
            }
            else
            {
                $this->session->set_flashdata('error_message', 'Opps! Error saving informtion. Please try again.');
            }
        }
        $data ['controller'] = 'new_product';
        $data ['content']    = $this->load->view('email_templates/signup_template_form', $data, true);
        $this->load->view('templete-view.php', $data);
    }

    //12
    function on_sale()
    {
        $data                = array();
        $data['action']      = 'add';
        $posted_data         = array();
        $data['emails_data'] = $this->email_templates_model->get_all_active_email_template(12);
        $id                  = 0;
        if (empty($data['emails_data']))
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
            $data['status']          = 1;
        }
        else
        {
            $data['title']           = $data['emails_data']['title'];
            $data['welcome_content'] = $data['emails_data']['welcome_content'];
            $data['content_data']    = $data['emails_data']['content'];
            $data['footer_data']     = $data['emails_data']['footer'];
            $data['id']              = $id                      = $data['emails_data']['id'];
            $data['status']          = $data['emails_data']['status'];
        }
        if ($this->input->post())
        {
            $this->session->set_flashdata('success_message', '');
            $this->session->set_flashdata('error_message', '');
            $posted_data                        = array();
            $posted_data['title']               = $this->input->post('title');
            $posted_data['welcome_content']     = $this->input->post('welcome_content') ? $this->input->post('welcome_content') : '';
            $posted_data['content']             = $this->input->post('content');
            $posted_data['footer']              = $this->input->post('footer');
            $posted_data['status']              = $this->input->post('status');
            $posted_data['email_template_type'] = 12;
            if ($id == 0)
            {
                $db_query = $this->email_templates_model->add_email_template($posted_data);
            }
            else
            {
                $posted_data['id'] = $id;
                $db_query          = $this->email_templates_model->update_email_template($posted_data);
            }
            if ($db_query)
            {
                $this->session->set_flashdata('success_message', 'Information successfully saved.');
                redirect('admin/email_templates/on_sale'); // due to flash data.
            }
            else
            {
                $this->session->set_flashdata('error_message', 'Opps! Error saving informtion. Please try again.');
            }
        }
        $data ['controller'] = 'on_sale';
        $data ['content']    = $this->load->view('email_templates/signup_template_form', $data, true);
        $this->load->view('templete-view.php', $data);
    }
    //13
    function on_sale_publisher()
    {
        $data                = array();
        $data['action']      = 'add';
        $posted_data         = array();
        $data['emails_data'] = $this->email_templates_model->get_all_active_email_template(13);
        $id                  = 0;
        if (empty($data['emails_data']))
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
            $data['status']          = 1;
        }
        else
        {
            $data['title']           = $data['emails_data']['title'];
            $data['welcome_content'] = $data['emails_data']['welcome_content'];
            $data['content_data']    = $data['emails_data']['content'];
            $data['footer_data']     = $data['emails_data']['footer'];
            $data['id']              = $id                      = $data['emails_data']['id'];
            $data['status']          = $data['emails_data']['status'];
        }
        if ($this->input->post())
        {
            $this->session->set_flashdata('success_message', '');
            $this->session->set_flashdata('error_message', '');
            $posted_data                        = array();
            $posted_data['title']               = $this->input->post('title');
            $posted_data['welcome_content']     = $this->input->post('welcome_content') ? $this->input->post('welcome_content') : '';
            $posted_data['content']             = $this->input->post('content');
            $posted_data['footer']              = $this->input->post('footer');
            $posted_data['status']              = $this->input->post('status');
            $posted_data['email_template_type'] = 13;
            if ($id == 0)
            {
                $db_query = $this->email_templates_model->add_email_template($posted_data);
            }
            else
            {
                $posted_data['id'] = $id;
                $db_query          = $this->email_templates_model->update_email_template($posted_data);
            }
            if ($db_query)
            {
                $this->session->set_flashdata('success_message', 'Information successfully saved.');
                redirect('admin/email_templates/on_sale_publisher'); // due to flash data.
            }
            else
            {
                $this->session->set_flashdata('error_message', 'Opps! Error saving informtion. Please try again.');
            }
        }
        $data ['controller'] = 'on_sale_publisher';
        $data ['content']    = $this->load->view('email_templates/signup_template_form', $data, true);
        $this->load->view('templete-view.php', $data);
    }
    
    //14
    function on_invoice_sending()
    {
        $data                = array();
        $data['action']      = 'add';
        $posted_data         = array();
        $data['emails_data'] = $this->email_templates_model->get_all_active_email_template(14);
        $id                  = 0;
        if (empty($data['emails_data']))
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
            $data['status']          = 1;
        }
        else
        {
            $data['title']           = $data['emails_data']['title'];
            $data['welcome_content'] = $data['emails_data']['welcome_content'];
            $data['content_data']    = $data['emails_data']['content'];
            $data['footer_data']     = $data['emails_data']['footer'];
            $data['id']              = $id                      = $data['emails_data']['id'];
            $data['status']          = $data['emails_data']['status'];
        }
        if ($this->input->post())
        {
            $this->session->set_flashdata('success_message', '');
            $this->session->set_flashdata('error_message', '');
            $posted_data                        = array();
            $posted_data['title']               = $this->input->post('title');
            $posted_data['welcome_content']     = $this->input->post('welcome_content') ? $this->input->post('welcome_content') : '';
            $posted_data['content']             = $this->input->post('content');
            $posted_data['footer']              = $this->input->post('footer');
            $posted_data['status']              = $this->input->post('status');
            $posted_data['email_template_type'] = 14;
            if ($id == 0)
            {
                $db_query = $this->email_templates_model->add_email_template($posted_data);
            }
            else
            {
                $posted_data['id'] = $id;
                $db_query          = $this->email_templates_model->update_email_template($posted_data);
            }
            if ($db_query)
            {
                $this->session->set_flashdata('success_message', 'Information successfully saved.');
                redirect('admin/email_templates/on_invoice_sending'); // due to flash data.
            }
            else
            {
                $this->session->set_flashdata('error_message', 'Opps! Error saving informtion. Please try again.');
            }
        }
        $data ['controller'] = 'on_invoice_sending';
        $data ['content']    = $this->load->view('email_templates/signup_template_form', $data, true);
        $this->load->view('templete-view.php', $data);
    }
    
     //15
    function on_withdraw_request()
    {
        $data                = array();
        $data['action']      = 'add';
        $posted_data         = array();
        $data['emails_data'] = $this->email_templates_model->get_all_active_email_template(15);
        $id                  = 0;
        if (empty($data['emails_data']))
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
            $data['status']          = 1;
        }
        else
        {
            $data['title']           = $data['emails_data']['title'];
            $data['welcome_content'] = $data['emails_data']['welcome_content'];
            $data['content_data']    = $data['emails_data']['content'];
            $data['footer_data']     = $data['emails_data']['footer'];
            $data['id']              = $id                      = $data['emails_data']['id'];
            $data['status']          = $data['emails_data']['status'];
        }
        if ($this->input->post())
        {
            $this->session->set_flashdata('success_message', '');
            $this->session->set_flashdata('error_message', '');
            $posted_data                        = array();
            $posted_data['title']               = $this->input->post('title');
            $posted_data['welcome_content']     = $this->input->post('welcome_content') ? $this->input->post('welcome_content') : '';
            $posted_data['content']             = $this->input->post('content');
            $posted_data['footer']              = $this->input->post('footer');
            $posted_data['status']              = $this->input->post('status');
            $posted_data['email_template_type'] = 15;
            if ($id == 0)
            {
                $db_query = $this->email_templates_model->add_email_template($posted_data);
            }
            else
            {
                $posted_data['id'] = $id;
                $db_query          = $this->email_templates_model->update_email_template($posted_data);
            }
            if ($db_query)
            {
                $this->session->set_flashdata('success_message', 'Information successfully saved.');
                redirect('admin/email_templates/on_withdraw_request'); // due to flash data.
            }
            else
            {
                $this->session->set_flashdata('error_message', 'Opps! Error saving informtion. Please try again.');
            }
        }
        $data ['controller'] = 'on_withdraw_request';
        $data ['content']    = $this->load->view('email_templates/signup_template_form', $data, true);
        $this->load->view('templete-view.php', $data);
    }
    
     //16
    function on_withdrawal()
    {
        $data                = array();
        $data['action']      = 'add';
        $posted_data         = array();
        $data['emails_data'] = $this->email_templates_model->get_all_active_email_template(16);
        $id                  = 0;
        if (empty($data['emails_data']))
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
            $data['status']          = 1;
        }
        else
        {
            $data['title']           = $data['emails_data']['title'];
            $data['welcome_content'] = $data['emails_data']['welcome_content'];
            $data['content_data']    = $data['emails_data']['content'];
            $data['footer_data']     = $data['emails_data']['footer'];
            $data['id']              = $id                      = $data['emails_data']['id'];
            $data['status']          = $data['emails_data']['status'];
        }
        if ($this->input->post())
        {
            $this->session->set_flashdata('success_message', '');
            $this->session->set_flashdata('error_message', '');
            $posted_data                        = array();
            $posted_data['title']               = $this->input->post('title');
            $posted_data['welcome_content']     = $this->input->post('welcome_content') ? $this->input->post('welcome_content') : '';
            $posted_data['content']             = $this->input->post('content');
            $posted_data['footer']              = $this->input->post('footer');
            $posted_data['status']              = $this->input->post('status');
            $posted_data['email_template_type'] = 16;
            if ($id == 0)
            {
                $db_query = $this->email_templates_model->add_email_template($posted_data);
            }
            else
            {
                $posted_data['id'] = $id;
                $db_query          = $this->email_templates_model->update_email_template($posted_data);
            }
            if ($db_query)
            {
                $this->session->set_flashdata('success_message', 'Information successfully saved.');
                redirect('admin/email_templates/on_withdrawal'); // due to flash data.
            }
            else
            {
                $this->session->set_flashdata('error_message', 'Opps! Error saving informtion. Please try again.');
            }
        }
        $data ['controller'] = 'on_withdrawal';
        $data ['content']    = $this->load->view('email_templates/signup_template_form', $data, true);
        $this->load->view('templete-view.php', $data);
    }
    
    
     //16
    function on_first_invitation_email()
    {
        $data                = array();
        $data['action']      = 'add';
        $posted_data         = array();
        $data['emails_data'] = $this->email_templates_model->get_all_active_email_template(18);
        $id                  = 0;
        if (empty($data['emails_data']))
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
            $data['status']          = 1;
        }
        else
        {
            $data['title']           = $data['emails_data']['title'];
            $data['welcome_content'] = $data['emails_data']['welcome_content'];
            $data['content_data']    = $data['emails_data']['content'];
            $data['footer_data']     = $data['emails_data']['footer'];
            $data['id']              = $id                      = $data['emails_data']['id'];
            $data['status']          = $data['emails_data']['status'];
        }
        if ($this->input->post())
        {
            $this->session->set_flashdata('success_message', '');
            $this->session->set_flashdata('error_message', '');
            $posted_data                        = array();
            $posted_data['title']               = $this->input->post('title');
            $posted_data['welcome_content']     = $this->input->post('welcome_content') ? $this->input->post('welcome_content') : '';
            $posted_data['content']             = $this->input->post('content');
            $posted_data['footer']              = $this->input->post('footer');
            $posted_data['status']              = $this->input->post('status');
            $posted_data['email_template_type'] = 18;
            if ($id == 0)
            {
                $db_query = $this->email_templates_model->add_email_template($posted_data);
            }
            else
            {
                $posted_data['id'] = $id;
                $db_query          = $this->email_templates_model->update_email_template($posted_data);
            }
            if ($db_query)
            {
                $this->session->set_flashdata('success_message', 'Information successfully saved.');
                redirect('admin/email_templates/on_first_invitation_email'); // due to flash data.
            }
            else
            {
                $this->session->set_flashdata('error_message', 'Opps! Error saving informtion. Please try again.');
            }
        }
        $data ['controller'] = 'on_first_invitation_email';
        $data ['other'] = '<h6>Use [PUBLISHER_FULL_NAME] for Full name , ';
        $data ['other'] .= 'Use [PUBLISHER_EMAIL] for email , ';
        $data ['other'] .= 'Use [PUBLISHER_PASSWORD] for password , ';
        $data ['other'] .= 'Use [PUBLISHER_LOGIN_LINK] for login link</h6>';
        $data ['content']    = $this->load->view('email_templates/signup_template_form', $data, true);
        $this->load->view('templete-view.php', $data);
    }
    
    //17
    function on_welcome_message_email()
    {
        $data                = array();
        $data['action']      = 'add';
        $posted_data         = array();
        $data['emails_data'] = $this->email_templates_model->get_all_active_email_template(19);
        $id                  = 0;
        if (empty($data['emails_data']))
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
            $data['status']          = 1;
        }
        else
        {
            $data['title']           = $data['emails_data']['title'];
            $data['welcome_content'] = $data['emails_data']['welcome_content'];
            $data['content_data']    = $data['emails_data']['content'];
            $data['footer_data']     = $data['emails_data']['footer'];
            $data['id']              = $id                      = $data['emails_data']['id'];
            $data['status']          = $data['emails_data']['status'];
        }
        if ($this->input->post())
        {
            $this->session->set_flashdata('success_message', '');
            $this->session->set_flashdata('error_message', '');
            $posted_data                        = array();
            $posted_data['title']               = $this->input->post('title');
            $posted_data['welcome_content']     = $this->input->post('welcome_content') ? $this->input->post('welcome_content') : '';
            $posted_data['content']             = $this->input->post('content');
            $posted_data['footer']              = $this->input->post('footer');
            $posted_data['status']              = $this->input->post('status');
            $posted_data['email_template_type'] = 19;
            if ($id == 0)
            {
                $db_query = $this->email_templates_model->add_email_template($posted_data);
            }
            else
            {
                $posted_data['id'] = $id;
                $db_query          = $this->email_templates_model->update_email_template($posted_data);
            }
            if ($db_query)
            {
                $this->session->set_flashdata('success_message', 'Information successfully saved.');
                redirect('admin/email_templates/on_welcome_message_email'); // due to flash data.
            }
            else
            {
                $this->session->set_flashdata('error_message', 'Opps! Error saving informtion. Please try again.');
            }
        }
        $data ['controller'] = 'on_welcome_message_email';
        $data ['other'] = '';
        $data ['content']    = $this->load->view('email_templates/signup_template_form', $data, true);
        $this->load->view('templete-view.php', $data);
    }
    
    function on_product_activation_email()
    {
        $data                = array();
        $data['action']      = 'add';
        $posted_data         = array();
        $data['emails_data'] = $this->email_templates_model->get_all_active_email_template(20);
        $id                  = 0;
        if (empty($data['emails_data']))
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
            $data['status']          = 1;
        }
        else
        {
            $data['title']           = $data['emails_data']['title'];
            $data['welcome_content'] = $data['emails_data']['welcome_content'];
            $data['content_data']    = $data['emails_data']['content'];
            $data['footer_data']     = $data['emails_data']['footer'];
            $data['id']              = $id                      = $data['emails_data']['id'];
            $data['status']          = $data['emails_data']['status'];
        }
        if ($this->input->post())
        {
            $this->session->set_flashdata('success_message', '');
            $this->session->set_flashdata('error_message', '');
            $posted_data                        = array();
            $posted_data['title']               = $this->input->post('title');
            $posted_data['welcome_content']     = $this->input->post('welcome_content') ? $this->input->post('welcome_content') : '';
            $posted_data['content']             = $this->input->post('content');
            $posted_data['footer']              = $this->input->post('footer');
            $posted_data['status']              = $this->input->post('status');
            $posted_data['email_template_type'] = 20;
            if ($id == 0)
            {
                $db_query = $this->email_templates_model->add_email_template($posted_data);
            }
            else
            {
                $posted_data['id'] = $id;
                $db_query          = $this->email_templates_model->update_email_template($posted_data);
            }
            if ($db_query)
            {
                $this->session->set_flashdata('success_message', 'Information successfully saved.');
                redirect('admin/email_templates/on_product_activation_email'); // due to flash data.
            }
            else
            {
                $this->session->set_flashdata('error_message', 'Opps! Error saving informtion. Please try again.');
            }
        }
        $data ['controller'] = 'on_product_activation_email';
        $data ['other'] = '';
        $data ['content']    = $this->load->view('email_templates/signup_template_form', $data, true);
        $this->load->view('templete-view.php', $data);
    }
    
    function on_product_deactivation_email()
    {
        $data                = array();
        $data['action']      = 'add';
        $posted_data         = array();
        $data['emails_data'] = $this->email_templates_model->get_all_active_email_template(21);
        $id                  = 0;
        if (empty($data['emails_data']))
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
            $data['status']          = 1;
        }
        else
        {
            $data['title']           = $data['emails_data']['title'];
            $data['welcome_content'] = $data['emails_data']['welcome_content'];
            $data['content_data']    = $data['emails_data']['content'];
            $data['footer_data']     = $data['emails_data']['footer'];
            $data['id']              = $id                      = $data['emails_data']['id'];
            $data['status']          = $data['emails_data']['status'];
        }
        if ($this->input->post())
        {
            $this->session->set_flashdata('success_message', '');
            $this->session->set_flashdata('error_message', '');
            $posted_data                        = array();
            $posted_data['title']               = $this->input->post('title');
            $posted_data['welcome_content']     = $this->input->post('welcome_content') ? $this->input->post('welcome_content') : '';
            $posted_data['content']             = $this->input->post('content');
            $posted_data['footer']              = $this->input->post('footer');
            $posted_data['status']              = $this->input->post('status');
            $posted_data['email_template_type'] = 21;
            if ($id == 0)
            {
                $db_query = $this->email_templates_model->add_email_template($posted_data);
            }
            else
            {
                $posted_data['id'] = $id;
                $db_query          = $this->email_templates_model->update_email_template($posted_data);
            }
            if ($db_query)
            {
                $this->session->set_flashdata('success_message', 'Information successfully saved.');
                redirect('admin/email_templates/on_product_deactivation_email'); // due to flash data.
            }
            else
            {
                $this->session->set_flashdata('error_message', 'Opps! Error saving informtion. Please try again.');
            }
        }
        $data ['controller'] = 'on_product_deactivation_email';
        $data ['other'] = '';
        $data ['content']    = $this->load->view('email_templates/signup_template_form', $data, true);
        $this->load->view('templete-view.php', $data);
    }

}

//End Class