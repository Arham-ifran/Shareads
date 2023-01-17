<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Users extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
//        error_reporting(E_ALL);
        // check if admin login
        $this->engineinit->_is_not_admin_logged_in_redirect('admin/login');
        // Check rights
        if (rights(82) != true)
        {
            redirect(base_url('admin/dashboard'));
        }
        $this->load->library('emailutility');
        $this->load->model('users_model');
    }

// End __construct
    /**
      @Method: index
      @Return: Listing
     */
    public function index()
    {
//        $data['result']   = $this->users_model->loadListing_advertiser();
        $data ['content'] = $this->load->view('users/advertiser', $data, true);
        $this->load->view('templete-view', $data);
    }

    public function publisher()
    {

//        $data['result']   = $this->users_model->loadListing_publisher();
        $data ['content'] = $this->load->view('users/publisher', $data, true);
        $this->load->view('templete-view', $data);
    }

    function getRealIpAddr()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
        {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
        {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    /**
     * Method: add
     * Return: Load Add Form
     */
    public function add_advertiser()
    {
        $data = array();
        // Check rights
        if (rights(83) != true)
        {
            redirect(base_url('admin/dashboard'));
        }
        if ($this->input->post())
        {
            /// Profile Photo
            if ($_FILES['photo']['name'] != '')
            {
                unlink('uploads/users/pic/' . $_POST['old_photo']);
                unlink('uploads/users/small/' . $_POST['old_photo']);
                unlink('uploads/users/medium/' . $_POST['old_photo']);
                $extension = $this->common->getExtension($_FILES ['photo'] ['name']);
                $extension = strtolower($extension);
                if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif"))
                {
                    return false;
                }
                $path        = 'uploads/users/';
                $allow_types = 'gif|jpg|jpeg|png';
                $max_height  = '8000';
                $max_width   = '8000';
                $photo       = $this->common->do_upload_profile($path, $allow_types, $max_height, $max_width, $_FILES ['photo']['tmp_name'], $_FILES ['photo']['name']);
            }


            $insert_id = $this->users_model->saveItemAdvertiser($_POST, $photo);


            if ($insert_id)
            {

                if ($_POST['action'] == 'add')
                {

                    $usr                   = getValArray('email,full_name,orignal_password', 'c_users', 'user_id', $insert_id);
                    $data['receiver_name'] = $usr['full_name'];
                    $data['email_content'] = "This message is to inform you that your entry into the " . SITE_NAME . " for " . $usr['full_name'] . " has been submitted and activated.
The following is the summary information for your account in " . SITE_NAME . ":\n\n
User Name: " . $usr['full_name'] . "
\n
Email:        " . $_POST['email'] . "\n
\n
Description:  " . nl2br($_POST['about_me']) . "\n
<br><br>
    Following is the information regarding your account email and Password.
                            <br /><br />
                            Login Email: <b>" . $usr['email'] . "</b>
                            <br /><br />
                            Login Password: <b>" . $usr['orignal_password'] . "</b>
                            <br /><br />
                            You will be able to login.  Please remember that your password is case-sensitive, you must enter it exactly as it appears.  Send questions to " . ADMIN_EMAIL . ".    " . SITE_NAME . "<br>
                            <a  class='blue_btn' href='" . base_url('login') . "'>Login</a>
";

                    $email_tempData = get_email_tempData(1);

                    if (!empty($email_tempData))
                    {
                        $data['title']           = $email_tempData['title'];
                        $data['content']         = $email_tempData['content'];
                        $data['welcome_content'] = $email_tempData['welcome_content'];
                        $data['footer']          = $email_tempData['footer'];
                        $subject                 = $data['title'];
                        $email_content           = $this->load->view('includes/email_templates/email_template', $data, true);
                        $this->emailutility->accountVarification($email_content, $usr['email'], $subject);
                    }
                }

                $this->session->set_flashdata('success_message', 'Information successfully saved.');
                redirect('admin/users'); // due to flash data.
            }
            else
            {
                $this->session->set_flashdata('error_message', 'Opps! Error saving informtion. Please try again.');
            }
        }

        $data['action']    = 'add';
        $data['countries'] = get_all_countries();
        $data ['content']  = $this->load->view('users/advertiser_form', $data, true); //Return View as data
        $this->load->view('templete-view', $data);
    }

    /**
     * Method: edit
     * Return: Load Edit Form
     */
    public function edit_advertiser($id)
    {
        // Check rights
        if (rights(84) != true)
        {
            redirect(base_url('admin/dashboard'));
        }
        $itemId            = $this->common->decode($id);
        $data['id']        = $itemId;
        $data['row']       = $this->users_model->getRow($itemId);
        $data['action']    = 'edit';
        $data['countries'] = get_all_countries();
        $data ['content']  = $this->load->view('users/advertiser_form', $data, true); //Return View as data
        $this->load->view('templete-view', $data);
    }

    /**
      @Method: delete
      @Params: itemId
      @Retrun: True/False
     */
    public function delete_advertiser($id)
    {
        // Check rights
        if (rights(85) != true)
        {
            redirect(base_url('admin/dashboard'));
        }
        $itemId = $this->common->decode($id);
        $result = $this->users_model->deleteItem($itemId);
        if ($result)
        {
            $this->session->set_flashdata('success_message', 'Record deleted successfully.');
            redirect('admin/users'); // due to flash data.
        }
        else
        {
            $this->session->set_flashdata('error_message', 'Opps! Error occured while deleting record. Please try again.');
        }
    }

    /**
     * Method: add
     * Return: Load Add Form
     */
    public function add_publisher()
    {
        $data = array();
        // Check rights
        if (rights(83) != true)
        {
            redirect(base_url('admin/dashboard'));
        }
        if ($this->input->post())
        {
            /// Profile Photo
            if ($_FILES['photo']['name'] != '')
            {
                unlink('uploads/users/pic/' . $_POST['old_photo']);
                unlink('uploads/users/small/' . $_POST['old_photo']);
                unlink('uploads/users/medium/' . $_POST['old_photo']);
                $extension = $this->common->getExtension($_FILES ['photo'] ['name']);
                $extension = strtolower($extension);
                if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif"))
                {
                    return false;
                }
                $path        = 'uploads/users/';
                $allow_types = 'gif|jpg|jpeg|png';
                $max_height  = '8000';
                $max_width   = '8000';
                $photo       = $this->common->do_upload_profile($path, $allow_types, $max_height, $max_width, $_FILES ['photo']['tmp_name'], $_FILES ['photo']['name']);
            }


            $insert_id = $this->users_model->saveItemPublisher($_POST, $photo);


            if ($insert_id)
            {

                if ($_POST['action'] == 'add')
                {

                    $usr                   = getValArray('email,full_name,orignal_password', 'c_users', 'user_id', $insert_id);
                    $data['receiver_name'] = $usr['full_name'];

                    $data['email_content'] = "This message is to inform you that your entry into the " . SITE_NAME . " for " . $usr['full_name'] . " has been submitted and activated.
The following is the summary information for your account in " . SITE_NAME . ":\n\n

User Name: " . $usr['full_name'] . "
\n
Email:        " . $_POST['email'] . "\n
\n
Description:  " . nl2br($_POST['about_me']) . "\n
<br><br>
    Following is the information regarding your account email and Password.

                            <br /><br />
                            Login Email: <b>" . $usr['email'] . "</b>
                            <br /><br />
                            Login Password: <b>" . $usr['orignal_password'] . "</b>
                            <br /><br />
                            You will be able to login.  Please remember that your password is case-sensitive, you must enter it exactly as it appears.  Send questions to " . ADMIN_EMAIL . ".    " . SITE_NAME . "<br>
                            <a  class='blue_btn' href='" . base_url('login') . "'>Login</a>
";

                    $email_tempData = get_email_tempData(10);
//echo '<pre>';print_r($email_tempData);die();
                    if (!empty($email_tempData))
                    {

                        $data['title']   = $email_tempData['title'];
                        $data['content'] = $email_tempData['content'];

                        $data['welcome_content'] = $email_tempData['welcome_content'];
                        $data['footer']          = $email_tempData['footer'];

                        $subject = $data['title'];

                        $email_content = $this->load->view('includes/email_templates/email_template', $data, true);

                        $this->emailutility->accountVarification($email_content, $usr['email'], $subject);
                    }
                }

                $this->session->set_flashdata('success_message', 'Information successfully saved.');
                redirect('admin/users/publisher'); // due to flash data.
            }
            else
            {
                $this->session->set_flashdata('error_message', 'Opps! Error saving informtion. Please try again.');
            }
        }

        $data['action']    = 'add';
        $data['countries'] = get_all_countries();
        $data ['content']  = $this->load->view('users/publisher_form', $data, true); //Return View as data
        $this->load->view('templete-view', $data);
    }

    public function add_publisher_with_invitation()
    {

        $data = array();
        // Check rights
        if (rights(83) != true)
        {
            redirect(base_url('admin/dashboard'));
        }
        if ($this->input->post())
        {
            /// Profile Photo
            if ($_FILES['photo']['name'] != '')
            {
                unlink('uploads/users/pic/' . $_POST['old_photo']);
                unlink('uploads/users/small/' . $_POST['old_photo']);
                unlink('uploads/users/medium/' . $_POST['old_photo']);
                $extension = $this->common->getExtension($_FILES ['photo'] ['name']);
                $extension = strtolower($extension);
                if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif"))
                {
                    return false;
                }
                $path        = 'uploads/users/';
                $allow_types = 'gif|jpg|jpeg|png';
                $max_height  = '8000';
                $max_width   = '8000';
                $photo       = $this->common->do_upload_profile($path, $allow_types, $max_height, $max_width, $_FILES ['photo']['tmp_name'], $_FILES ['photo']['name']);
            }


            $insert_id = $this->users_model->saveItemPublisher($_POST, $photo);
            updateVal('is_invited', 1, 'users', 'user_id', $insert_id);

            $invitation = [
                'first_name' => $_POST['first_name'],
                'last_name' => $_POST['last_name'],
                'email' => $_POST['email'],
                'user_key' => getVal('user_key', 'c_users', 'user_id', $insert_id),
                'is_invited' => 1
            ];
            $this->users_model->saveItemInvitePublishers_new($invitation);

            if ($insert_id)
            {
                $this->session->set_flashdata('success_message', 'Information successfully saved.');
                redirect('admin/users/publisher'); // due to flash data.
            }
            else
            {
                $this->session->set_flashdata('error_message', 'Opps! Error saving informtion. Please try again.');
            }
        }

        $data['action']    = 'add';
        $data['countries'] = get_all_countries();
        $data ['content']  = $this->load->view('users/publisher_form', $data, true); //Return View as data
        $this->load->view('templete-view', $data);
    }

    /**
     * Method: edit
     * Return: Load Edit Form
     */
    public function edit_publisher($id)
    {
        // Check rights
        if (rights(84) != true)
        {
            redirect(base_url('admin/dashboard'));
        }
        $itemId            = $this->common->decode($id);
        $data['id']        = $itemId;
        $data['row']       = $this->users_model->getRow($itemId);
        $data['action']    = 'edit';
        $data['countries'] = get_all_countries();
        $data ['content']  = $this->load->view('users/publisher_form', $data, true); //Return View as data
        $this->load->view('templete-view', $data);
    }

    /**
      @Method: delete
      @Params: itemId
      @Retrun: True/False
     */
    public function delete_publisher($id)
    {
        // Check rights
        if (rights(85) != true)
        {
            redirect(base_url('admin/dashboard'));
        }
        $itemId = $this->common->decode($id);
        $result = $this->users_model->deleteItem($itemId);
        if ($result)
        {
            $this->session->set_flashdata('success_message', 'Record deleted successfully.');
            redirect('admin/users/publisher'); // due to flash data.
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

        $user      = getValArray('email,full_name,is_active,status,account_type', 'c_users', 'user_id', $itemId);
        $result    = $this->users_model->updateItemStatus($itemId, $status);
        $is_active = $user['is_active'];
        $is_status = $user['status'];

        if ($is_status == 0 && $is_active == 0)
        {

            $password              = $this->common->randomPassword();
            $this->users_model->updateUser($itemId, $password);
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

            $email_tempData = get_email_tempData(9);

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
        }

        echo $result;
    }

    /**
     * Method: checkEmail
     *
     */
    public function checkEmail()
    {


        $email = $this->input->post('email');
        $id    = $this->input->post('id');
        $name  = $this->users_model->checkEmail($email, $id);
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

    public function checkInvitationsEmail()
    {
        $email = $this->input->post('email');
        $id    = $this->input->post('id');
        $name  = $this->users_model->checkInvitationEmail($email, $id);
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

    public function varfyPaypalEmail()
    {
        $bodyparams = array("emailAddress" => $_POST['email']);

        $verify = $this->get_verified_status($bodyparams);
        if ($verify == 0)
        {
            echo 0;
        }
        else
        {
            echo 1;
        }
    }

    public function get_verified_status($data)
    {
        get_payment_intergration();

        $config = array(
            'Sandbox' => INTEGRATION_TYPE == 1 ? 'true' : 'false', // Sandbox / testing mode option.
            'APIUsername' => API_USERNAME, // PayPal API username of the API caller
            'APIPassword' => API_PASSWORD, // PayPal API password of the API caller
            'APISignature' => API_SIGNATURE, // PayPal API signature of the API caller
            'APISubject' => '', // PayPal API subject (email address of 3rd party user that has granted API permission for your app)
            'APIVersion' => '123.0', // API version you'd like to use for your call.  You can set a default version in the class and leave this blank if you want.
            'DeviceID' => '',
            'ApplicationID' => APPLICATION_ID,
            'DeveloperEmailAccount' => PAYPAL_ID
        );
        $this->load->library('paypal/Paypal_adaptive', $config);

        // Prepare request arrays
        $GetVerifiedStatusFields = array(
            'EmailAddress' => $data['emailAddress'], // Required.  The email address of the PayPal account holder.
            'FirstName' => '', // The first name of the PayPal account holder.  Required if MatchCriteria is NAME
            'LastName' => '', // The last name of the PayPal account holder.  Required if MatchCriteria is NAME
            'MatchCriteria' => 'NONE'     // Required.  The criteria must be matched in addition to EmailAddress.  Currently, only NAME is supported.  Values:  NAME, NONE   To use NONE you have to be granted advanced permissions
        );

        $PayPalRequestData = array('GetVerifiedStatusFields' => $GetVerifiedStatusFields);

        $PayPalResult = $this->paypal_adaptive->GetVerifiedStatus($PayPalRequestData);

        if (!$this->paypal_adaptive->APICallSuccessful($PayPalResult['Ack']))
        {
            $errors = array('Errors' => $PayPalResult['Errors']);

            return 0;
        }
        else
        {
            return 1;
        }
    }

    public function detail($id)
    {
        $itemId = $this->common->decode($id);

        $account_type     = getVal('account_type', 'c_users', 'user_id', $itemId);
        $data['usertype'] = $account_type;
        if ($account_type == 1)
        {
            $data['result']   = $this->users_model->loadOrders_advertiser($itemId);
            $data ['content'] = $this->load->view('users/advertiser_detail', $data, true); //Return View as data
            $this->load->view('templete-view', $data);
        }
        else
        {
            $data['result']   = $this->users_model->loadOrders_publisher($itemId);
            $data ['content'] = $this->load->view('users/publisher_detail', $data, true); //Return View as data
            $this->load->view('templete-view', $data);
        }
    }

    public function publisher_invitations()
    {

        $data['result']   = $this->users_model->loadListing_publisher_invitations();
        $data ['content'] = $this->load->view('users/publisher_invitations', $data, true);
        $this->load->view('templete-view', $data);
    }

    /**
     * Method: add
     * Return: Load Add Form
     */
    public function add_newpublisher_invitations()
    {
        $data = array();
        // Check rights
        if (rights(83) != true)
        {
            redirect(base_url('admin/dashboard'));
        }

        if ($this->input->post())
        {
            $email_exists = $this->users_model->checkEmail($this->input->post('email'));
            // if ($email_exists == true)
            // {
            //     $this->session->set_flashdata('error_message', 'User already Exists');
            //     redirect('admin/users/publisher_invitations'); // due to flash data.
            // }
            $insert_id    = $this->users_model->saveItemInvitePublishers($_POST);


            if ($insert_id)
            {

                if ($_POST['action'] == 'add')
                {

                    $usr                   = getValArray('full_name,email,user_key,email_title,email_content', 'c_publisher_invitations', 'id', $insert_id);
                    $link                  = '<a href="' . base_url() . 'register/ipublisher/' . $usr['user_key'] . '">' . base_url() . 'register/ipublisher/' . $usr['user_key'] . '</a>';
                    $data['receiver_name'] = $usr['full_name'];

                    $data['email_content'] = $_POST['email_content'];
                    if ($usr['full_name'] <> '')
                    {
                        $data['email_content'] .= "<p>Hi " . $usr['full_name'] . ",<br>";
                    }
                    $data['email_content'] .= "Please click the link to complete the signup:<br/>Link: " . $link . "</p>";



                    $email_tempData = get_email_tempData(18);
                    if (!empty($email_tempData))
                    {
                        $data['title']           = $usr['email_title'];
                        $data['content']         = $email_tempData['content'];
                        $data['welcome_content'] = $email_tempData['welcome_content'];
                        $data['footer']          = $email_tempData['footer'];
                        $subject                 = $data['title'];
                        $email_content           = $this->load->view('includes/email_templates/email_template', $data, true);
                        $this->emailutility->accountVarification($email_content, $usr['email'], $subject);
                    }
                }

                $this->session->set_flashdata('success_message', 'Information successfully saved.');
                redirect('admin/users/publisher_invitations'); // due to flash data.
            }
            else
            {
                $this->session->set_flashdata('error_message', 'Opps! Error saving informtion. Please try again.');
            }
        }

        $data['action']    = 'add';
        $data['countries'] = get_all_countries();
        $data ['content']  = $this->load->view('users/invite_publisher_form', $data, true); //Return View as data
        $this->load->view('templete-view', $data);
    }

    public function add_publisher_invitations()
    {
        $data = array();
        // Check rights
        if (rights(83) != true)
        {
            redirect(base_url('admin/dashboard'));
        }

        if ($this->input->post())
        {
            $email_exists = $this->users_model->checkEmail($this->input->post('email'));
            if ($email_exists == true)
            {
                $this->session->set_flashdata('error_message', 'User already Exists');
                redirect('admin/users/publisher_invitations'); // due to flash data.
            }
            $insert_id = $this->users_model->saveItemInvitePublishers($_POST);


            if ($insert_id)
            {

                if ($_POST['action'] == 'add')
                {

                    $usr                   = getValArray('full_name,email,user_key,email_title,email_content', 'c_publisher_invitations', 'id', $insert_id);
                    $link                  = '<a href="' . base_url() . 'register/ipublisher/' . $usr['user_key'] . '">' . base_url() . 'register/ipublisher/' . $usr['user_key'] . '</a>';
                    $data['receiver_name'] = $usr['full_name'];

                    $data['email_content'] = $_POST['email_content'];
                    if ($usr['full_name'] <> '')
                    {
                        $data['email_content'] .= "<p>Hi " . $usr['full_name'] . ",<br>";
                    }
                    $data['email_content'] .= "Please click the link to complete the signup:<br/>Link: " . $link . "</p>";



                    $email_tempData = get_email_tempData(10);
                    if (!empty($email_tempData))
                    {
                        $data['title']           = $usr['email_title'];
                        $data['content']         = $email_tempData['content'];
                        $data['welcome_content'] = $email_tempData['welcome_content'];
                        $data['footer']          = $email_tempData['footer'];
                        $subject                 = $data['title'];
                        $email_content           = $this->load->view('includes/email_templates/email_template', $data, true);
                        $this->emailutility->accountVarification($email_content, $usr['email'], $subject);
                    }
                }

                $this->session->set_flashdata('success_message', 'Information successfully saved.');
                redirect('admin/users/publisher_invitations'); // due to flash data.
            }
            else
            {
                $this->session->set_flashdata('error_message', 'Opps! Error saving informtion. Please try again.');
            }
        }

        $data['action']    = 'add';
        $data['countries'] = get_all_countries();
        $data ['content']  = $this->load->view('users/invite_publisher_form', $data, true); //Return View as data
        $this->load->view('templete-view', $data);
    }

    /**
      @Method: delete
      @Params: itemId
      @Retrun: True/False
     */
    public function delete_publisher_invitations($id)
    {
        // Check rights
        if (rights(85) != true)
        {
            redirect(base_url('admin/dashboard'));
        }
        $itemId = $this->common->decode($id);
        $result = $this->users_model->deleteInvitationPublisherItem($itemId);
        if ($result)
        {
            $this->session->set_flashdata('success_message', 'Record deleted successfully.');
            redirect('admin/users/publisher_invitations'); // due to flash data.
        }
        else
        {
            $this->session->set_flashdata('error_message', 'Opps! Error occured while deleting record. Please try again.');
        }
    }

    public function ajaxInvitationPublisherChangeStatus()
    {
        $itemId = $_POST['itemId'];
        $status = $_POST['status'];

        $result = $this->users_model->updateItemInvitationPublisherStatus($itemId, $status);

        echo $result;
    }

    /**
     * Method: edit
     * Return: Load Edit Form
     */
    public function edit_publisher_invitations($id)
    {
        // Check rights
        if (rights(84) != true)
        {
            redirect(base_url('admin/dashboard'));
        }
        $itemId           = $this->common->decode($id);
        $data['id']       = $itemId;
        $data['row']      = $this->users_model->getRowIP($itemId);
        $data['action']   = 'edit';
        $data ['content'] = $this->load->view('users/invite_publisher_form', $data, true); //Return View as data
        $this->load->view('templete-view', $data);
    }

    public function invitation_settings()
    {
        $data = array();
        // Check rights
        if (rights(83) != true)
        {
            redirect(base_url('admin/dashboard'));
        }

        if ($this->input->post())
        {
            $insert_id = $this->users_model->saveInvitation_settings($_POST);


            if ($insert_id)
            {
                $this->session->set_flashdata('success_message', 'Information successfully saved.');
                redirect('admin/users/publisher_invitations'); // due to flash data.
            }
            else
            {
                $this->session->set_flashdata('error_message', 'Opps! Error saving informtion. Please try again.');
            }
        }

        $data ['content'] = $this->load->view('users/invitation_settings', $data, true); //Return View as data
        $this->load->view('templete-view', $data);
    }

    public function import_invitations()
    {
        $array  = array();
        $target = 'uploads/temp_excel_files/' . basename($_FILES['excel_file']['name']);

        if (move_uploaded_file($_FILES['excel_file']['tmp_name'], $target))
        {
            $this->load->library('Spreadsheet_Excel_Reader');
            $excel  = new Spreadsheet_Excel_Reader();
            $excel->read('uploads/temp_excel_files/' . basename($_FILES['excel_file']['name'])); // set the excel file name here   
            $reader = $excel->sheets[0]['cells'];
            $i      = 0;
            foreach ($reader as $key => $row)
            {
                if ($key == 1)
                {
                    $hearderss = $row;
                    foreach ($hearderss as $k => $v)
                    {
                        $headers[$k] = str_replace(' ', '_', strtolower($v));
                    }
                    continue;
                }
                else
                {
                    foreach ($row as $kk => $vv)
                    {

                        $array[$i][$headers[$kk]] = $row[$kk];
                    }
                    $i++;
                }
            }
            foreach ($array as $key => $value)
            {
                if ($this->users_model->checkInvitationEmail($value['email']))
                {
                    continue;
                }
                $value['action'] = 'add';
                $insert_id       = $this->users_model->saveItemInvitePublishers($value);
                if ($insert_id)
                {
                    if ($value['action'] == 'add')
                    {
                        $usr                   = getValArray('full_name,email,user_key,email_title,email_content', 'c_publisher_invitations', 'id', $insert_id);
                        $link                  = '<a href="' . base_url() . 'register/ipublisher/' . $usr['user_key'] . '">' . base_url() . 'register/ipublisher/' . $usr['user_key'] . '</a>';
                        $data['receiver_name'] = $usr['full_name'];

                        $data['email_content'] = INVITATION_DEFAULT_CONTENT;
                        if ($usr['full_name'] <> '')
                        {
                            $data['email_content'] .= "<p>Hi " . $usr['full_name'] . ",<br>";
                        }
                        $data['email_content'] .= "Please click the link to complete the signup:<br/>Link: " . $link . "</p>";

                        $email_tempData = get_email_tempData(10);

                        if (!empty($email_tempData))
                        {

                            $data['title']           = $usr['email_title'];
                            $data['content']         = $email_tempData['content'];
                            $data['welcome_content'] = $email_tempData['welcome_content'];
                            $data['footer']          = $email_tempData['footer'];

                            $subject = INVITATION_DEFAULT_TITLE;

                            $email_content = $this->load->view('includes/email_templates/email_template', $data, true);
                            $this->emailutility->accountVarification($email_content, $usr['email'], $subject);
                        }
                    }
                }
                else
                {
                    continue;
                }
            }
            unlink($target);
            $this->session->set_flashdata('success_message', 'Information successfully uploaded.');
            redirect('admin/users/publisher_invitations'); // due to flash data.
        }
        else
        {
            $this->session->set_flashdata('error_message', 'Opps! Error saving informtion. Please try again.');
            redirect('admin/users/publisher_invitations'); // due to flash data.
        }
    }

    public function resendinvitations_selected()
    {
        $is_sended      = false;
        $selection_list = $this->input->post('selected_checkbox');
        foreach ($selection_list as $key => $value)
        {
            $usr = getValArray('full_name,email,user_key,email_title,email_content', 'c_publisher_invitations', 'id', $value);
            if (empty($usr))
            {
                continue;
            }
            $is_sended             = true;
            $link                  = '<a href="' . base_url() . 'register/ipublisher/' . $usr['user_key'] . '">' . base_url() . 'register/ipublisher/' . $usr['user_key'] . '</a>';
            $data['receiver_name'] = $usr['full_name'];
            $data['email_content'] = $usr['email_content'];
            if ($usr['full_name'] <> '')
            {
                $data['email_content'] .= "<p>Hi " . $usr['full_name'] . ",<br>";
            }
            $data['email_content'] .= "Please click the link to complete the signup:<br/>Link: " . $link . "</p>";

            $email_tempData = get_email_tempData(10);
            if (!empty($email_tempData))
            {
                $data['title']           = $usr['email_title'];
                $data['content']         = $email_tempData['content'];
                $data['welcome_content'] = $email_tempData['welcome_content'];
                $data['footer']          = $email_tempData['footer'];
                $subject                 = $data['title'];
                $email_content           = $this->load->view('includes/email_templates/email_template', $data, true);
                $this->emailutility->accountVarification($email_content, $usr['email'], $subject);
            }
        }

        if ($is_sended)
        {
            $this->session->set_flashdata('success_message', 'Information successfully resended.');
            redirect('admin/users/publisher_invitations');
        }
        else
        {
            $this->session->set_flashdata('error_message', 'Opps! Some Error Occured. Please try again.');
            redirect('admin/users/publisher_invitations');
        }
    }

    public function resendinvitations_all()
    {
        $is_sended      = false;
        $selection_list = $this->users_model->getInvitationList();
        foreach ($selection_list as $key => $value)
        {
            $usr = getValArray('full_name,email,user_key,email_title,email_content', 'c_publisher_invitations', 'id', $value['id']);
            if (empty($usr))
            {
                continue;
            }
            $is_sended             = true;
            $link                  = '<a href="' . base_url() . 'register/ipublisher/' . $usr['user_key'] . '">' . base_url() . 'register/ipublisher/' . $usr['user_key'] . '</a>';
            $data['receiver_name'] = $usr['full_name'];
            $data['email_content'] = $usr['email_content'];
            if ($usr['full_name'] <> '')
            {
                $data['email_content'] .= "<p>Hi " . $usr['full_name'] . ",<br>";
            }
            $data['email_content'] .= "Please click the link to complete the signup:<br/>Link: " . $link . "</p>";

            $email_tempData = get_email_tempData(10);
            if (!empty($email_tempData))
            {
                $data['title']           = $usr['email_title'];
                $data['content']         = $email_tempData['content'];
                $data['welcome_content'] = $email_tempData['welcome_content'];
                $data['footer']          = $email_tempData['footer'];
                $subject                 = $data['title'];
                $email_content           = $this->load->view('includes/email_templates/email_template', $data, true);
                $this->emailutility->accountVarification($email_content, $usr['email'], $subject);
            }
        }

        if ($is_sended)
        {
            $this->session->set_flashdata('success_message', 'Information successfully resended.');
            redirect('admin/users/publisher_invitations');
        }
        else
        {
            $this->session->set_flashdata('error_message', 'Opps! Some Error Occured. Please try again.');
            redirect('admin/users/publisher_invitations');
        }
    }

    public function send_publisher_email($id)
    {
        $data                   = array();
        $user_id                = $this->common->decode($id);
        $data['row']            = $user_obj               = getValArray('*', 'c_users', 'user_id', $user_id);
        $data['id']             = $user_obj['user_id'];
        $data['email_template'] = $email_tempData         = get_email_tempData(18);

        // Check rights
        if (rights(83) != true)
        {
            redirect(base_url('admin/dashboard'));
        }

        if ($this->input->post())
        {
            $user_id   = $_POST['id'];
            unset($_POST['id']);
            // $email_exists = $this->users_model->checkEmail($this->input->post('email'));
            // if ($email_exists == true)
            // {
            //     $this->session->set_flashdata('error_message', 'User already Exists');
            //     redirect('admin/users/publisher_invitations'); // due to flash data.
            // }
            $insert_id = $this->users_model->saveItemInvitePublishers($_POST);

            updateVal('status', 1, 'users', 'user_id', $user_id);
            updateVal('is_active', 1, 'users', 'user_id', $user_id);
            updateVal('invited_status', 1, 'users', 'user_id', $user_id);
            if ($insert_id)
            {

                if ($_POST['action'] == 'add')
                {
                    $usr = getValArray('*', 'c_users', 'user_id', $user_id);
                    $this->db->where('user_id', $user_id)->update('c_users', array('is_invited' => 1, 'status' => 1, 'is_active' => 1));
                    $this->db->where('id', $insert_id)->update('c_publisher_invitations', array('user_key' => $usr['user_key']));

                    $link      = '<a href="' . base_url() . 'login/iApublisher?u=' . $usr['user_key'] . '">' . base_url() . 'login/iApublisher?u=' . $usr['user_key'] . '</a>';
                    $full_name = $usr['first_name'] . ' ' . $usr['last_name'];
                    $email     = $usr['email'];
                    $password  = $usr['orignal_password'];
                    
                    

                    $email_tempData                  = get_email_tempData(18);
                    $email_tempData['title']         = $_POST['email_title'];
                    $email_tempData['content']       = $_POST['email_content'];
                    $email_tempData['content']       = str_replace("[PUBLISHER_FULL_NAME]", ucfirst($full_name), $email_tempData['content']);
                    $email_tempData['content']       = str_replace("[PUBLISHER_EMAIL]", $email, $email_tempData['content']);
                    $email_tempData['content']       = str_replace("[PUBLISHER_PASSWORD]", $password, $email_tempData['content']);
                    $email_tempData['content']       = str_replace("[PUBLISHER_LOGIN_LINK]", $link, $email_tempData['content']);
                    $email_tempData['email_content'] = 'Below are your ShareAds login credentials and Link.<br>';
                    $email_tempData['email_content'] .= 'Email:  ' . $email . '<br>';
                    $email_tempData['email_content'] .= 'Password:  ' . $password . '<br>';
                    $email_tempData['email_content'] .= 'Direct Link: ' . $link . '<br><br>';

                    $email_tempData['email_content'] .= 'Note: The given link can only be used once. Its recommended that after accessing your account Please change your password to avoid any inconvenience?';
                    $email_tempData['receiver_name'] = 'no_hi';
                    if (!empty($email_tempData))
                    {
                        $data['title']           = $email_tempData['title'];
                        $data['receiver_name'] = 'no_hi';
                        $data['content']         = $email_tempData['content'];
                        $data['email_content']   = $email_tempData['email_content'];
                        $data['welcome_content'] = $email_tempData['welcome_content'];
                        $data['footer']          = $email_tempData['footer'];
                        $subject                 = $data['title'];
                        $email_content           = $this->load->view('includes/email_templates/email_template', $data, true);
                        $this->emailutility->accountVarification($email_content, $usr['email'], $subject);
                    }
                }

                $this->session->set_flashdata('success_message', 'Invitation has been sent to Publisher Successfully');
                redirect('admin/users/publisher_invitations'); // due to flash data.
            }
            else
            {
                $this->session->set_flashdata('error_message', 'Opps! Error saving informtion. Please try again.');
            }
        }

        $data['action']    = 'add';
        $data['countries'] = get_all_countries();
        $data ['content']  = $this->load->view('users/firsttime_invite_publisher_form', $data, true); //Return View as data
        $this->load->view('templete-view', $data);
    }
    
    public function send_welcome_email($id)
    {
        $data                   = array();
        $user_id                = $this->common->decode($id);
        $data['row']            = $user_obj               = getValArray('*', 'c_users', 'user_id', $user_id);
        $data['id']             = $user_obj['user_id'];
        $data['email_template'] = $email_tempData         = get_email_tempData(19);

        // Check rights
        if (rights(83) != true)
        {
            redirect(base_url('admin/dashboard'));
        }

        if ($this->input->post())
        {
            $user_id   = $_POST['id'];
            unset($_POST['id']);
            updateVal('status', 1, 'users', 'user_id', $user_id);

            if (true)
            {

                if ($_POST['action'] == 'add')
                {
                    $usr = getValArray('*', 'c_users', 'user_id', $user_id);

                    $link      = '<a href="' . base_url() . 'login/iApublisher?u=' . $usr['user_key'] . '">' . base_url() . 'login/iApublisher?u=' . $usr['user_key'] . '</a>';
                    $full_name = $usr['first_name'] . ' ' . $usr['last_name'];
                    $email     = $usr['email'];
                    $password  = $usr['orignal_password'];
                    
                   
                    $email_tempData                  = get_email_tempData(19);
                    $email_tempData['title']         = $_POST['email_title'];
                    $email_tempData['content']       = $_POST['email_content'];
                    $email_tempData['content']       = str_replace("[PUBLISHER_FULL_NAME]", ucfirst($full_name), $email_tempData['content']);
                    $email_tempData['content']       = str_replace("[PUBLISHER_EMAIL]", $email, $email_tempData['content']);
                    $email_tempData['content']       = str_replace("[PUBLISHER_PASSWORD]", $password, $email_tempData['content']);
                    $email_tempData['content']       = str_replace("[PUBLISHER_LOGIN_LINK]", $link, $email_tempData['content']);
                    $email_tempData['email_content'] = 'Below are your ShareAds login credentials and Link.<br>';
                    $email_tempData['email_content'] .= 'Email:  ' . $email . '<br>';
                    $email_tempData['email_content'] .= 'Password:  ' . $password . '<br>';
                    $email_tempData['email_content'] .= 'Direct Link: ' . $link . '<br><br>';

                    $email_tempData['email_content'] .= 'Note: The given link can only be used once. Its recommended that after accessing your account Please change your password to avoid any inconvenience?';
                    $email_tempData['receiver_name'] = 'no_hi';
                    if (!empty($email_tempData))
                    {
                        $data['title']           = $email_tempData['title'];
                        $data['receiver_name'] = 'no_hi';
                        $data['content']         = '';
                        $data['email_content']   = $email_tempData['content'];
                        $data['welcome_content'] = $email_tempData['welcome_content'];
                        $data['footer']          = $email_tempData['footer'];
                        $subject                 = $data['title'];
                        $email_content           = $this->load->view('includes/email_templates/email_template', $data, true);
                        $this->emailutility->accountVarification($email_content, $usr['email'], $subject);
                    }
                }

                $this->session->set_flashdata('success_message', 'Information successfully saved.');
                redirect('admin/users/publisher'); // due to flash data.
            }
            else
            {
                $this->session->set_flashdata('error_message', 'Opps! Error saving informtion. Please try again.');
            }
        }

        $data['action']    = 'add';
        $data['countries'] = get_all_countries();
        $data ['content']  = $this->load->view('users/welcome_message', $data, true); //Return View as data
        $this->load->view('templete-view', $data);
    }

    public function pagination_adv()
    {
        $list = $this->users_model->get_datatables_adv();
    
        $data = array();
        $no   = $_POST['start'];
        foreach ($list as $rec)
        {
            $row                   = array();
            $query_no_of_sales     = $this->db->query('SELECT * FROM c_orders where advertiser_id = ' . $rec->user_id . ' and order_status = 2');
            $no_of_sales           = $query_no_of_sales->num_rows();
            $advertiser_commission = 0;
            if ($rec->product_ids <> '')
            {
                $product_ids = explode(',', $rec->product_ids);
                foreach ($product_ids as $key => $value)
                {
                    $product      = getValArray('commission,currency', 'c_products', array('product_id' => $value));
                    $p_currency   = $product['currency'];
                    $p_commission = $product['commission'];
                    $advertiser_commission += get_currency_rate($p_commission, $p_currency, CURRENCY);
                }
            }
            else
            {
                $advertiser_commission = 0;
            }

            $row[] = ucwords($rec->full_name);
            if ($rec->connected_by == 1)
            {
                $row[] = 'Facebook';
            }
            elseif ($rec->connected_by == 2)
            {
                $row[] = 'Twitter';
            }
            else
            {
                if($rec->site_refrence <> '')
                {
                    $row[] = $rec->site_refrence;
                }
                else
                {
                    $row[] = 'Site';
                }
            }
            $row[] = $rec->email;
            $row[] = number_format($no_of_sales);
            $row[] = getSiteCurrencySymbol() . number_format($advertiser_commission, 2);
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
                $actions .= '&nbsp;<a title="Status" href="javascript:void(0);" class="blue status_button' . $rec->user_id . '" onclick=updateStatus("users",' . $rec->user_id . ',1)><i class="ace-icon fa fa-plus bigger-130"></i></a>';
            }
            else
            {
                $actions .= '&nbsp;<a title="Status" href="javascript:void(0);" class="red status_button' . $rec->user_id . '" onclick=updateStatus("users",' . $rec->user_id . ',0)><i class="ace-icon fa fa-minus bigger-130"></i></a>';
            }
            $row[] = date('d-m-Y',$rec->created);
            $actions .= '&nbsp;<a title="Edit" class="green" href="' . base_url('admin/users/edit_advertiser/' . $this->common->encode($rec->user_id)) . '">';
            $actions .= '<i class="ace-icon fa fa-pencil bigger-130"></i>';
            $actions .= '</a>';
            if ($this->session->userdata('role_id') == 0)
            {
                $actions .= '&nbsp;<a  title="Delete" class="red" onclick="return delete_confirm();" href="' . base_url('admin/users/delete_advertiser/' . $this->common->encode($rec->user_id)) . '" onclick="return delete_confirm()">';
                $actions .= '<i class="ace-icon fa fa-trash-o bigger-130"></i>';
                $actions .= '</a>';
            }
            $actions .= '</div>';
            $row[] = $actions;
            $row[] = '<a href="' . base_url('admin/users/detail/' . $this->common->encode($rec->user_id)) . '">View Detail</a>';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->users_model->count_all_adv(),
            "recordsFiltered" => $this->users_model->count_filtered_adv(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function pagination_pub()
    {
        $list = $this->users_model->get_datatables_pub();

        $data = array();
        $no   = $_POST['start'];
        foreach ($list as $rec)
        {
            if($rec->user_id == 1){continue;}
            $row                  = array();
            $publisher_commission = 0;
            $view_invoice         = '';
            $add_products         = '';
            $count                = $this->db->where('publisher_id', $rec->user_id)->count_all_results('c_invoices');


            if ($count > 0 && $rec->user_id <> 1)
            {
                $view_invoice = '&nbsp;|&nbsp;<a href="' . base_url('admin/commission/manage_invoices?pid=' . $this->common->encode($rec->user_id)) . '"><i class="fa fa-file-pdf-o" aria-hidden="true"></i>&nbsp;View Invoices</a>';
            }
            if ($rec->is_invited == 1 && $rec->status == 1)
            {
                $add_products = '&nbsp;|&nbsp;<a href="' . base_url('admin/listings/add?uid=' . $this->common->encode($rec->user_id)) . '"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Add Products</a>';
            }
            if ($rec->product_ids <> '')
            {
                $product_ids = explode(',', $rec->product_ids);
                foreach ($product_ids as $key => $value)
                {
                    $product    = getValArray('price,currency', 'c_products', array('product_id' => $value));
                    $p_currency = $product['currency'];
                    $p_price    = $product['price'];
                    $publisher_commission += get_currency_rate($p_price, $p_currency, CURRENCY);
                }
            }
            else
            {
                $publisher_commission = 0;
            }
            if ($rec->is_invited == 1)
            {
                if ($rec->invited_status == 1)
                {
                    $row[] = ucwords($rec->full_name) . '&nbsp;&nbsp;<span class="label label-sm label-success">Invited</span>';
                }
                else
                {
                    $row[] = ucwords($rec->full_name) . '&nbsp;&nbsp;<span class="label label-sm label-danger">Invitation Pending</span>';
                }
            }
            else
            {
                $row[] = ucwords($rec->full_name);
            }

            $row[] = $rec->email;
            //$row[] = getSiteCurrencySymbol() . number_format($publisher_commission, 2);
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
                $actions .= '&nbsp;<a title="Status" href="javascript:void(0);" class="blue status_button' . $rec->user_id . '" onclick=updateStatus("users",' . $rec->user_id . ',1)><i class="ace-icon fa fa-plus bigger-130"></i></a>';
            }
            else
            {
                $actions .= '&nbsp;<a title="Status" href="javascript:void(0);" class="red status_button' . $rec->user_id . '" onclick=updateStatus("users",' . $rec->user_id . ',0)><i class="ace-icon fa fa-minus bigger-130"></i></a>';
            }
            $actions .= '&nbsp;<a title="Edit" class="green" href="' . base_url('admin/users/edit_publisher/' . $this->common->encode($rec->user_id)) . '">';
            $actions .= '<i class="ace-icon fa fa-pencil bigger-130"></i>';
            $actions .= '</a>';
            if ($this->session->userdata('role_id') == 0)
            {
                $actions .= '&nbsp;<a  title="Delete" class="red" onclick="return delete_confirm();" href="' . base_url('admin/users/delete_publisher/' . $this->common->encode($rec->user_id)) . '" onclick="return delete_confirm()">';
                $actions .= '<i class="ace-icon fa fa-trash-o bigger-130"></i>';
                $actions .= '</a>';
            }
            if ($rec->is_invited == 1)
            {
                $actions .= '&nbsp;<a  title="Invite Publisher" class="green" href="' . base_url('admin/users/send_publisher_email/' . $this->common->encode($rec->user_id)) . '"><i class="ace-icon fa fa-paper-plane bigger-130"></i></a>';
            }
            else
            {
//                $actions .= '&nbsp;<a  title="Invite Publisher" style="color:blue;" href="' . base_url('admin/users/send_welcome_email/' . $this->common->encode($rec->user_id)) . '"><i class="ace-icon fa fa-paper-plane bigger-130"></i></a>';
            }
            $actions .= '</div>';
            $row[] = $actions;
            $row[] = '<a href="' . base_url('admin/users/detail/' . $this->common->encode($rec->user_id)) . '"><i class="fa fa-info" aria-hidden="true"></i>&nbsp;View Detail</a>' . $view_invoice . $add_products;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->users_model->count_all_pub(),
            "recordsFiltered" => $this->users_model->count_filtered_pub(),
            "data" => $data,
        );
        echo json_encode($output);
    }

}

//End Class