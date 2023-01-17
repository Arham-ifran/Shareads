<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // check if admin login
        $this->engineinit->_is_not_logged_in_redirect('login');
        $this->load->model('dashboard_model');
        $this->load->library('emailutility');
    }

    public function decode()
    {
        echo $_GET['decode'] . '<br>';
        echo $this->common->decode($_GET['decode']);
    }

    public function encode()
    {
        echo $_GET['encode'] . '<br>';
        echo $this->common->decode($_GET['encode']);
    }

// End __construct
    /**
      @Method: index
      @Return: View
     */
    public function index()
    {
        $data             = array();
        $user_id          = $this->session->userdata('user_id');
        $data['userdata'] = getUserData($user_id);

        if ($this->session->userdata('account_type') == 2)
        {
            $data['totalProducts']    = $this->dashboard_model->getTotalProducts($user_id);
            $data['totalSharedLinks'] = $this->dashboard_model->getTotalPublisherLinksShared($user_id);
            $data['totalVisitors']    = $this->dashboard_model->getTotalPublisherVisitors($user_id);
            $data['totalCommission']  = $this->dashboard_model->getPublisherTotalCommission($user_id);
            $data['totalSales']       = $this->dashboard_model->getTotalPublisherSale($user_id);

            $data['totalSuccessLeads'] = $this->dashboard_model->getTotalPublisherSuccessLeadCounter($user_id, $filter);
            $data['sales']             = $this->dashboard_model->getTotalPublisherSales($user_id, $filter);
            $data['commission']        = $this->dashboard_model->getTotalPublisherCommission($user_id, $filter);
        }

        if ($this->session->userdata('account_type') == 1)
        {
            $data['totalCommission']   = $this->dashboard_model->getTotalCommission($user_id);
            $data['totalSharedLinks']  = $this->dashboard_model->getTotalLinksShared($user_id);
            $data['totalVisitors']     = $this->dashboard_model->getTotalVisitors($user_id);
            $data['totalSales']        = $this->dashboard_model->getTotalSale($user_id);
//dd($data['totalCommission']);
            $data['totalSuccessLeads'] = $this->dashboard_model->getTotalSuccessLeadCounter($user_id, $filter);
            $data['sales']             = $this->dashboard_model->getTotalSales($user_id, $filter);
            $data['commission']        = $this->dashboard_model->getTotalCommissions($user_id, $filter);
//            dd($data);
        }






        $data ['content'] = $this->load->view('dashboard', $data, true);
        $this->load->view('includes/template_dashboard.view.php', $data);
    }

    public function welcome()
    {
        $user_type = $this->session->userdata('account_type');
        if ($user_type == 1)
        {
            redirect(base_url('marketing'));
        }
        
        $data = [];

        $first_time_invitor = [];
        $first_time_invitor = $this->db->from('c_users')->where(array('email' => $this->session->userdata('email'), 'invited_status' => 1, 'is_invited' => 1, 'show_welcome' => 1))->get()->row_array();
        if (!empty($first_time_invitor))
        {
            $data['template'] = $this->db->from('c_templates')->where(array('user_type' => 2))->get()->row_array();
        }
        else
        {
            $first_time_invitor = $this->db->from('c_users')->where(array('email' => $this->session->userdata('email'), 'show_welcome' => 1))->get()->row_array();
            if (!empty($first_time_invitor))
            {
                $data['template'] = $this->db->from('c_templates')->where(array('user_type' => 3))->get()->row_array();
            }
            else{
                redirect(base_url('dashboard'));
            }
        }

        

        $this->load->view('welcome', $data);
    }

    public function change_welcome_show()
    {
        $itemId = $this->session->userdata('user_id');
        $status = $_POST['status'];
        $result = $this->db->where('user_id', $itemId)->update('c_users', array('show_welcome' => $status));
        echo $result;
    }

    function payment_settings()
    {
        $data             = array();
        $user_id          = $this->session->userdata('user_id');
        $data['userdata'] = getUserData($user_id);
        if ($this->input->post())
        {
            $post = $this->input->post();
            $db   = $this->dashboard_model->savePaymentSettings($post);
            if ($db)
            {
                $this->session->set_flashdata('success_message', 'Information successfully saved.');
                redirect('settings/payment_settings'); // due to flash data.
            }
            else
            {
                $this->session->set_flashdata('error_message', 'Opps! Error saving informtion. Please try again.');
            }
        }
        $data ['content'] = $this->load->view('payment_settings', $data, true);
        $this->load->view('includes/template_dashboard.view.php', $data);
    }

    function settings($type)
    {
        $data = array();

        $user_id           = $this->session->userdata('user_id');
        $data['userdata']  = getUserData($user_id);
        $data['countries'] = get_all_countries();
        if ($type == 'edit')
        {

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

                $insert_id = $this->dashboard_model->updateProfileDetail($_POST, $photo);


                if ($insert_id)
                {

                    $this->session->set_flashdata('success_message', 'Information successfully saved.');
                    redirect('settings'); // due to flash data.
                }
                else
                {
                    $this->session->set_flashdata('error_message', 'Opps! Error saving informtion. Please try again.');
                }
            }


            $data ['content'] = $this->load->view('profile_edit', $data, true);
            $this->load->view('includes/template_dashboard.view.php', $data);
        }
        else
        {
            $data ['content'] = $this->load->view('settings', $data, true);
            $this->load->view('includes/template_dashboard.view.php', $data);
        }
    }

    function changepassword()
    {
        $data = array();

        $user_id = $this->session->userdata('user_id');
        if ($this->input->post())
        {
            /// Profile Photo
            $user_id  = $this->session->userdata('user_id');
            $password = md5($_POST['password']);
            $sql      = "select user_id from c_users where password = '" . $password . "'";
            $query    = $this->db->query($sql);
            if ($query->num_rows() > 0)
            {
                $data_update['orignal_password'] = $_POST['new_password'];
                $data_update['password']         = md5($_POST['new_password']);
                $this->db->where('user_id', $user_id);
                $this->db->update('c_users', $data_update);
                $this->session->set_flashdata('success_message', 'Password updated successfully');
            }
            else
            {
                $this->session->set_flashdata('error_message', 'Old Password is incorrect');
            }
            redirect('settings/changepassword'); // due to flash data.
        }


        $data ['content'] = $this->load->view('change_password', $data, true);
        $this->load->view('includes/template_dashboard.view.php', $data);
    }

    function varfyPaypalEmail()
    {
        $bodyparams = array("emailAddress" => $_POST['paypal_email'], "paypal_first_name" => $_POST['paypal_first_name'], "paypal_last_name" => $_POST['paypal_last_name']);

        $verify = $this->get_verified_status($bodyparams);

//        if ($verify == 1)
//        {
//            echo 0;
//        }
//        else
//        {
//            echo 1;
//        }
        if ($verify == 1)
        {
            echo 1;
        }
        else
        {
            echo 0;
        }
    }

    function get_verified_status()
    {
        $paypal_email        = $_POST['paypal_email'];
        unset($_POST['paypal_email']);
        $user_id             = $this->session->userdata('user_id');
        $payment_credentials = "";

        if ($paypal_email != '')
        {

            $payment_credentials = (array) get_payment_intergration();

            $config = array(
                'Sandbox' => $payment_credentials['integration_type'] == 0 ? true : false, // Sandbox / testing mode option.
                'APIUsername' => trim($payment_credentials['api_username']), // PayPal API username of the API caller
                'APIPassword' => trim($payment_credentials['api_password']), // PayPal API password of the API caller
                'APISignature' => trim($payment_credentials['api_signature']), // PayPal API signature of the API caller
                'APISubject' => '', // PayPal API subject (email address of 3rd party user that has granted API permission for your app)
                'APIVersion' => '85.0', // API version you'd like to use for your call. You can set a default version in the class and leave this blank if you want.
                'DeviceID' => '',
                'ApplicationID' => $payment_credentials['application_id'],
                'DeveloperEmailAccount' => $payment_credentials['paypal_id']
            );
            dd($config);
            $this->load->library('paypal/Paypal_adaptive', $config);

            $GetVerifiedStatusFields = array(
                'EmailAddress' => trim($paypal_email), // Required. The email address of the PayPal account holder.
                'FirstName' => $_POST['paypal_first_name'], // The first name of the PayPal account holder. Required if MatchCriteria is NAME
                'LastName' => $_POST['paypal_last_name'], // The last name of the PayPal account holder. Required if MatchCriteria is NAME
                'MatchCriteria' => 'NAME' // Required. The criteria must be matched in addition to EmailAddress. Currently, only NAME is supported. Values: NAME, NONE To use NONE you have to be granted advanced permissions
            );

            $PayPalRequestData = array('GetVerifiedStatusFields' => $GetVerifiedStatusFields);

            $PayPalResult = $this->paypal_adaptive->GetVerifiedStatus($PayPalRequestData);
            dd($PayPalResult);
            if (!$this->paypal_adaptive->APICallSuccessful($PayPalResult['Ack']))
            {
                $data_update['paypal_email']      = '';
                $data_update['paypal_first_name'] = '';
                $data_update['paypal_last_name']  = '';
                $data_update['paypal_verified']   = 0;
                $result['msg']                    = $PayPalResult['Errors'][0]['Message'];
                $result['code']                   = 1;
                return $result;
            }
            else
            {
                $data_update['paypal_email']      = $paypal_email;
                $data_update['paypal_verified']   = 1;
                $data_update['paypal_first_name'] = $_POST['paypal_first_name'];
                $data_update['paypal_last_name']  = $_POST['paypal_last_name'];
                $result['msg']                    = "Information has been saved successfully";
                $result['code']                   = 2;
                return $result;
            }
        }
    }

//    function get_verified_status($data)
//    {
//        $credentials = get_payment_intergration();
//
//        $config                  = array(
//            'Sandbox' => INTEGRATION_TYPE == 1 ? 'false' : 'true', // Sandbox / testing mode option.
//            'APIUsername' => API_USERNAME, // PayPal API username of the API caller
//            'APIPassword' => API_PASSWORD, // PayPal API password of the API caller
//            'APISignature' => API_SIGNATURE, // PayPal API signature of the API caller
//            'APISubject' => '', // PayPal API subject (email address of 3rd party user that has granted API permission for your app)
//            'APIVersion' => '123.0', // API version you'd like to use for your call.  You can set a default version in the class and leave this blank if you want.
//            'DeviceID' => '',
////            'ApplicationID' => APPLICATION_ID,
//            'DeveloperEmailAccount' => PAYPAL_ID
//        );
//        
//        $this->load->library('paypal/Paypal_adaptive', $config);
////dd($config);
//        // Prepare request arrays
//        $GetVerifiedStatusFields = array(
//            'EmailAddress' => $data['emailAddress'], // Required.  The email address of the PayPal account holder.
//            'FirstName' => $data['paypal_first_name'], // The first name of the PayPal account holder.  Required if MatchCriteria is NAME
//            'LastName' => $data['paypal_last_name'], // The last name of the PayPal account holder.  Required if MatchCriteria is NAME
//            'MatchCriteria' => 'NONE'     // Required.  The criteria must be matched in addition to EmailAddress.  Currently, only NAME is supported.  Values:  NAME, NONE   To use NONE you have to be granted advanced permissions
//        );
//
//        $PayPalRequestData = array('GetVerifiedStatusFields' => $GetVerifiedStatusFields);
//
//        $PayPalResult = $this->paypal_adaptive->GetVerifiedStatus($PayPalRequestData);
//        
//        dd($PayPalResult);
//
//        if (!$this->paypal_adaptive->APICallSuccessful($PayPalResult['Ack']))
//        {
//            $errors = array('Errors' => $PayPalResult['Errors']);
//
//            return 0;
//        }
//        else
//        {
//            return 1;
//        }
//    }

    /**
     * Method: checkEmail
     *
     */
    public function checkEmail()
    {


        $email = $this->input->post('email');
        $name  = $this->dashboard_model->checkEmail($email);
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

    function saveImage()
    {
        if ($_FILES['file']['name'] != '')
        {
//            unlink('uploads/users/pic/' . $_POST['old_photo']);
//            unlink('uploads/users/small/' . $_POST['old_photo']);
//            unlink('uploads/users/medium/' . $_POST['old_photo']);

            $extension = $this->common->getExtension($_FILES ['file']['name']);
            $extension = strtolower($extension);
            if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif"))
            {
                $data1['flag'] = 0;
                echo json_encode($data1);
            }
            $path          = 'uploads/users/';
            $allow_types   = 'gif|jpg|jpeg|png';
            $max_height    = '8000';
            $max_width     = '8000';
            $photo         = $this->common->do_upload_profile($path, $allow_types, $max_height, $max_width, $_FILES ['file']['tmp_name'], $_FILES ['file']['name']);
            $this->session->set_userdata('photo', $photo);
            updateVal('photo', $photo, 'c_users', 'user_id', $this->session->userdata('user_id'));
            $data1['img']  = base_url() . "uploads/users/medium/" . $photo;
            $data1['flag'] = 1;
            echo json_encode($data1);
        }
    }

    function saveCropImage()
    {
        //echo APPPATH; exit;

        $data         = $_POST['img'];
        define('UPLOAD_DIR', 'uploads/temp/'); // image dir path
        list($type, $data) = explode(';', $data);
        list(, $data) = explode(',', $data);
        $data         = str_replace(' ', '+', $data);
        $data         = base64_decode($data); // base 64 decoding
        $temp_path    = UPLOAD_DIR . time() . ".png";
        file_put_contents($temp_path, $data);
        $path         = 'uploads/users/';
        $allow_types  = 'gif|jpg|jpeg|png';
        $max_height   = '8000';
        $max_width    = '8000';
        $photo        = $this->common->do_upload_ajax_profile($path, $allow_types, $max_height, $max_width, $temp_path, "proimg.png");
        $this->session->set_userdata('photo', $photo);
        updateVal('photo', $photo, 'c_users', 'user_id', $this->session->userdata('user_id'));
        $data1['img'] = base_url() . "uploads/users/medium/" . $photo;

        echo json_encode($data1);
    }

    function deImage()
    {
        // var_dump($_POST);
        unlink('uploads/users/pic/' . $_POST['image']);
        unlink('uploads/users/small/' . $_POST['image']);
        unlink('uploads/users/medium/' . $_POST['image']);
        updateVal('photo', '', 'c_users', 'user_id', $this->session->userdata('user_id'));
        $this->session->set_userdata('photo', '');
        if ($_POST['gender'] == 'male')
            $data1['img'] = base_url('assets/site/img/unknown_male.jpg');
        else if ($_POST['gender'] == 'female')
            $data1['img'] = base_url('assets/site/img/unknown_female.jpg');;
        echo json_encode($data1);
    }

}
