<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Register extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        get_social_intergration();
        $this->engineinit->_is_logged_in_redirect('/home');
        $this->load->model('register_model');
        $this->load->model('login/login_model');
        $this->load->library('emailutility');
    }

// End __construct
    /**
      @Method: index
      @Return: View
     */
    public function index($page, $user_key = '')
    {
        //echo $page.' - '.$user_key;die();
        $data = array();
            


        if ($this->input->post())
        {
            
            $is_from_campaign = false;
            if(isset($_POST) && isset($_POST['is_from_campaign']) && $_POST['is_from_campaign'] == 1)
            {
                $is_from_campaign = true;
                unset($_POST['is_from_campaign']);
            }

            $email        = $this->register_model->checkEmail($this->input->post('email'));
            $deviceUnique = $this->register_model->checkDeviceUnique($this->input->post('email'));
            if ($email == 0)
            {

                $data['email'] = $chimp_email   = $this->input->post('email');


                if (isset($_COOKIE['affid']))
                {
                    $affid      = $_COOKIE['affid'];
                    $userid     = getVal('user_id', 'c_users', 'user_key', $affid);
                    $user_id    = $userid <> '' ? $userid : 0;
                    $product_id = $_COOKIE['pid'];
                    $product_id = $this->common->decode($product_id);
                    

                    unset($_COOKIE['affid']);
                    unset($_COOKIE['pid']);
                    setcookie('affid', null, -1, '/');
                    setcookie('pid', null, -1, '/');
                    if ($product_id <> '' && $user_id <> 0)
                    {
                        $status = $this->register_model->getOrderStatus($user_id, $product_id);
                        if (!empty($status))
                        {
                            $this->register_model->updateOrderStatus($status);
                        }
                    }
                }
                $db_query = '';
                if (trim($user_key) <> '' && trim($page) == 'ipublisher')
                {
                    $_POST['is_invited'] = 1;
                    $_POST['status']     = 1;
                    unset($_POST['confirm_password']);
                    $db_query            = $this->register_model->saveItemInvitors($_POST);
                    unset($_COOKIE['link_signup_userkey']);
                    setcookie('link_signup_userkey', null, -1, '/');
                }
                else
                {
                    $db_query = $this->register_model->saveItem($_POST);
                }



                $result = $this->register_model->checkSubscribeEmail($this->input->post('email'));

                if ($result == 0)
                {
                    $list_id = MAIL_CHIMP_ID;
                    $api_key = MAIL_CHIMP_KEY;
                    if ($list_id <> '' && $api_key <> '')
                    {
                        $this->load->library('MCAPI', array('apikey' => $api_key));
                        if ($this->mcapi->listSubscribe($list_id, $chimp_email) === true)
                        {
                            $this->mcapi->listSubscribe($list_id, $chimp_email);
                            $result                = $this->register_model->subscribe_now($data);
                            $data['status']        = 1;
                            $data['message']       = 'Successfully subscribed for Newsletter.';
                            $data['receiver_name'] = 'Subscriber';
                            $data['email_content'] = "You successfully subscribed for the newsletter on " . SITE_NAME . ".";

                            $email_tempData = get_email_tempData(6);
                            if (!empty($email_tempData))
                            {
                                $data['title']           = $email_tempData['title'];
                                $data['content']         = $email_tempData['content'];
                                $data['welcome_content'] = $email_tempData['welcome_content'];
                                $data['footer']          = $email_tempData['footer'];
                            }
                            else
                            {
                                $data['title']           = 'Newsletter Subscription ' . SITE_NAME;
                                $data['welcome_content'] = '';
                                $data['content']         = $this->load->view('includes/email_templates/email_content.php', $data, true);
                                $data['footer']          = $this->load->view('includes/email_templates/email_footer.php', $data, true);
                            }
                            $subject       = $data['title'];
                            $email_content = $this->load->view('includes/email_templates/email_template', $data, true);
                            $this->emailutility->accountVarification($email_content, $chimp_email, $subject);
                        }
                        else
                        {
                            if ($result == 0)
                            {
                                $result = $this->register_model->subscribe_now($data);
                            }
                        }
                    }
                }

                if ($this->uri->segment(2) == 'publisher' || $this->uri->segment(2) == 'ipublisher')
                {
                    $account_type = 2;
                }
                else
                {
                    $account_type = 1;
                }
                if ($db_query && $account_type == 2)
                {
                    if ($user_key <> '' && $page == 'ipublisher')
                    {
                        updateVal('status', 2, 'c_publisher_invitations', 'user_key', $user_key);
                        $response = $this->login_model->ajaxLogin($this->input->post('email'), $this->input->post('password'));
                        if ($response)
                        {
                            if ($this->input->get_post("last_url") <> '')
                            {
                                $last_url = urldecode($this->input->get_post("last_url"));
                                redirect($last_url); // due to flash data.
                            }
                            else
                            {
                                $this->session->set_flashdata('success_message', 'You have login successfully.');
                                if ($this->session->userdata('account_type') == 1)
                                {
                                    redirect(base_url('marketing')); // due to flash data.
                                }
                                else
                                {
                                    redirect(base_url('dashboard')); // due to flash data.
                                }
                                exit;
                            }
                        }
                    }
                    else
                    {
                        $_account_type         = getVal('type', 'c_users_types', 'id', $account_type);
                        $data['receiver_name'] = $_POST['first_name'];

                        $data['email_content'] = "This message is to inform you that your entry into the " . SITE_NAME . " has been submitted for review.   We will send you a confirmation message when your entry has been approved and activated.
                            The following is the summary information for your account in " . SITE_NAME . ":<br/><br/>

                            Account Type: " . $_account_type . "<br/>
                            First Name: " . $_POST['first_name'] . "<br/>
                            Last Name: " . $_POST['last_name'] . "<br/>
                            Email:        " . $_POST['email'] . "<br/>";

                        $email_tempData = get_email_tempData(1);

                        if (!empty($email_tempData))
                        {

                            $data['title']   = $email_tempData['title'];
                            $data['content'] = $email_tempData['content'];

                            $data['welcome_content'] = $email_tempData['welcome_content'];
                            $data['footer']          = $email_tempData['footer'];

                            $subject = $data['title'];

                            $email_content = $this->load->view('includes/email_templates/email_template', $data, true);
                            $this->emailutility->accountVarification($email_content, $_POST['email'], $subject);
                        }

                        $this->session->set_flashdata('success_message', 'Congratulation! Your account has been submitted for review.</h5>. We will send you a confirmation email when your entry has been approved and activated.');
                        redirect('register'); // due to flash data.
                    }
                }
                else if ($db_query && $account_type == 1)
                {

                    $user = getValArray('user_id,email,full_name,is_active,status,account_type', 'c_users', 'email', $_POST['email']);
                    updateVal('status', 1, 'users', 'email', $_POST['email']);
                    $password              = $this->common->randomPassword();
                    updateVal('orignal_password', $password, 'users', 'email', $_POST['email']);
                    updateVal('password', md5($password), 'users', 'email', $_POST['email']);
                    $data['receiver_name'] = $user['full_name'];
                    $data['email_content'] = "Following is the information regarding your email and password.
                                <br /><br />User Name: <b>" . $user['full_name'] . "</b>
                            <br /><br />
                            Login Email: <b>" . $user['email'] . "</b>
                            <br /><br />
                            Login Password: <b>" . $password . "</b>
                            <br /><br />
                            You will be able to login  shortly, as soon as our database is updated with your information.  Please remember that your password is case-sensitive, you must enter it exactly as it appears.  Send questions to " . ADMIN_EMAIL . ".    " . SITE_NAME . "<br>
                            <a  class='blue_btn' href='" . base_url('login') . "'>Login</a>
                            ";
                    $email_tempData = get_email_tempData(1);
                    if (!empty($email_tempData))
                    {
                        $data['title']   = $email_tempData['title'];
                        $data['content'] = $email_tempData['content'];
                        $data['welcome_content'] = $email_tempData['welcome_content'];
                        $data['footer']          = $email_tempData['footer'];
                        $subject = $data['title'];
                        $email_content = $this->load->view('includes/email_templates/email_template', $data, true);
                        $this->emailutility->send_email_user($email_content, $user['email'], $subject);
                    }
                    
                    if($is_from_campaign)
                    {
                        $user = getValArray('user_id,email,full_name,is_active,status,account_type,orignal_password', 'c_users', 'email', $_POST['email']);
                        $response = $this->login_model->ajaxLogin($user['email'], $user['orignal_password']);
                        if ($response)
                        {
                            if ($this->input->get_post("last_url") <> '')
                            {
                                $last_url = urldecode($this->input->get_post("last_url"));
                                redirect($last_url); // due to flash data.
                            }
                            else
                            {
                                $this->session->set_flashdata('success_message', 'Congratulation! Your account has been created and you are logged in successfully.</h5>. Please check your email for account information.');
                                if ($this->session->userdata('account_type') == 1)
                                {
                                    redirect(base_url('marketing')); // due to flash data.
                                }
                                else
                                {
                                    redirect(base_url('dashboard')); // due to flash data.
                                }
                                exit;
                            }
                        }
                    }
                    else
                    {
                        $this->session->set_flashdata('success_message', 'Congratulation! Your account has been created.</h5>. Please check your email for account information.');
                        redirect('register');
                    }
                }
                else
                {
                    $this->session->set_flashdata('error_message', 'Opps! Error occured while registering. Please try again.');
                }
            }
            else
            {
                $this->session->set_flashdata('error_message', 'Opps! Email already exists. Please try another one.');
            }
        }


        $this->load->library('fb');

        $fb = new Facebook\Facebook([
            'app_id' => FACEBOOK_APPID,
            'app_secret' => FACEBOOK_SECRET,
            'default_graph_version' => 'v2.11',
        ]);

        $helper             = $fb->getRedirectLoginHelper();
        $permissions        = ['email', 'public_profile'];
        $data['fbLoginUrl'] = $helper->getLoginUrl(base_url('login/facebook'), $permissions);
        $data['login_flag'] = 1;
        if ($this->input->cookie('link_signup_userkey') <> '' && ($page == 'publisher' || $page == 'ipublisher'))
        {
            $user_key         = $this->input->cookie('link_signup_userkey');
            $user_invite_data = $this->register_model->getInvitationUser($user_key);
            if (empty($user_invite_data))
            {
                $this->session->set_flashdata('error_message', 'Link is Expired');
                redirect(base_url('login'));
            }

            $data['row']      = $user_invite_data;
            $data['user_key'] = $user_key;
            $data['type']     = 'ipublisher';
            $data ['content'] = $this->load->view('register_ipublisher', $data, true);
            $this->load->view('includes/template_fullbody.view.php', $data);
        }
        else if ($page == 'publisher' && $this->input->cookie('link_signup_userkey') == '')
        {
            $data['type']     = 'publisher';
            $data ['content'] = $this->load->view('register_publisher', $data, true);
            $this->load->view('includes/template_fullbody.view.php', $data);
        }
        elseif ($page == 'ipublisher' && $user_key <> '' && $this->input->cookie('link_signup_userkey') == '')
        {

            setcookie('link_signup_userkey', $user_key, time() + (86400 * 30), "/");
            $user_invite_data = $this->register_model->getInvitationUser($user_key);
            if (empty($user_invite_data))
            {
                $this->session->set_flashdata('error_message', 'Link is Expired');
                redirect(base_url('login'));
            }
            updateVal('status', 1, 'c_publisher_invitations', 'user_key', $user_key);


            $data['row']      = $user_invite_data;
            $data['user_key'] = $user_key;
            $data['type']     = 'ipublisher';
            $data ['content'] = $this->load->view('register_ipublisher', $data, true);
            $this->load->view('includes/template_fullbody.view.php', $data);
        }
        else
        {
            $data['type']     = 'advertiser';
            $data ['content'] = $this->load->view('register_advertiser', $data, true);
            $this->load->view('includes/template_fullbody.view.php', $data);
        }
    }

    function success($id)
    {
        $user_id = $this->common->decode($id);
        $data    = getValArray('user_id,email,first_name,last_name,account_type,user_key', 'c_users', 'user_id', $user_id);

        if ($data['account_type'] == 1)
        {
            $data['type'] = 'publisher';
        }
        else
        {
            $data['type'] = 'advertiser';
        }
        $data['login_flag'] = 1;
        $data ['content']   = $this->load->view('register_success', $data, true);
        $this->load->view('includes/template_fullbody.view.php', $data);
    }

    /**
     * Method: checkEmail
     *
     */
    public function checkEmail()
    {


        $email = $this->input->post('email');
        $name  = $this->register_model->checkEmail($email);
        if ($name == 0)
        {
            echo 0;
        }
        else
        {
            echo 1;
        }

        exit;
    }

    function getCookie()
    {
        echo '<pre>';
        print_r($_COOKIE);
    }

}
