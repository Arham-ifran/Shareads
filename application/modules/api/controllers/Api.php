<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Api extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
//        if (!isset($_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER'] != 'admin' || $_SERVER['PHP_AUTH_PW'] != '123456') {
//            header('WWW-Authenticate: Basic realm="JYM Buddy"');
//            header('HTTP/1.0 401 Unauthorized');
//            die('Access Denied');
//        }
        $this->load->model('api_model');
        $this->load->model('wallet/wallet_model');
        $this->load->model('dashboard/dashboard_model');
        $this->load->model('support/support_model');
        $this->load->library('emailutility');
    }

    /**
     * Method: register
     * Params: $post
     * Return: Json
     */
    function login()
    {
        $post_data = array();
        $data      = array();
        if ($_POST['email'] <> '' && $_POST['password'] <> '')
        {
            $email    = trim($_POST['email']);
            $password = trim($_POST['password']);
            $response = $this->api_model->ajaxLogin($email, $password);
            if ($response <> 0)
            {
                $data['status'] = 1;
                $data['user']   = $this->api_model->get_user_data($response);
            }
            else
            {
                $data['status']  = 0;
                $data['message'] = 'The email or password you entered is incorrect. Please try again (make sure your caps lock is off).';
            }
        }
        else if ($_POST['connected_by_id'] <> '' || $_POST['connected_by_id'] <> 0)
        {
            $verify = $this->api_model->verify_user($_POST['connected_by_id']);
            if ($verify == 1)
            {
                $post_data['connected_by_id'] = $_POST['connected_by_id'];
                $post_data['connected_by']    = $_POST['connected_by'];
                $post_data['first_name']      = $_POST['first_name'];
                $post_data['last_name']       = $_POST['last_name'];
                $post_data['full_name']       = $_POST['first_name'] . ' ' . $_POST['last_name'];
                if ($_POST['email'] <> '')
                {
                    $post_data['email'] = $_POST['email'];
                }
                else
                {
                    $post_data['email'] = '';
                }
                $post_data['status']       = 1;
                $post_data['account_type'] = 1;
                $results                   = $this->api_model->register($post_data);
                $insert_id                 = $this->db->insert_id();
                if ($results)
                {
                    $data['status'] = 1;
                    $data['user']   = $this->api_model->get_user_data($insert_id);
                }
                else
                {
                    $data['status']  = 0;
                    $data['message'] = 'Not able to register. Try again later.';
                }
            }
            else
            {
                $insert_id      = getVal('user_id', 'c_users', 'connected_by_id', $_POST['connected_by_id']);
                $data['user']   = $this->api_model->get_user_data($insert_id);
                $data['status'] = 1;
            }
        }
        else
        {
            $data['status']  = 0;
            $data['message'] = 'Invalid data provided.';
        }
//      $this->destroy_session();
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    function getSharedOn($array)
    {

        foreach ($array as $key => $sale)
        {
            if (strpos($sale['referer_page'], 'facebook') !== false)
            {
                $array[$key]['referer_page'] = 'Facebook';
            }
            else if (strpos($sale['referer_page'], 'twitter') !== false)
            {
                $array[$key]['referer_page'] = 'Twitter';
            }
            else if ($sale['referer_page'] == 'linkedin')
            {
                $array[$key]['referer_page'] = 'Linkedin';
            }
            else if ($sale['referer_page'] == 'email')
            {
                echo '<td><i class="fa fa-envelope-square fa-fw"></i> Email</td>';
            }
            else
            {
                $array[$key]['referer_page'] = 'Direct Link / Shared Link';
            }
        }
        return $array;
    }

    /**
     * Method: register
     * Params: $post
     * Return: Json
     */
    function register()
    {
        $post_data = array();
        $data      = array();
        if ($_POST['email'] <> '' || $_POST['email'] <> 0)
        {
            $verify = $this->api_model->verify_email($_POST['email']);
            if ($verify == 0)
            {
                $post_data['first_name']   = $_POST['first_name'];
                $post_data['last_name']    = $_POST['last_name'];
                $post_data['full_name']    = $_POST['first_name'] . ' ' . $_POST['last_name'];
                $post_data['email']        = $_POST['email'];
                $post_data['account_type'] = $account_type              = $_POST['account_type'];
                       $post_data['status']       = 1;
                $post_data['is_active']       = 1;
                $results                   = $this->api_model->register($post_data);
                $insert_id                 = $this->db->insert_id();
                if ($results)
                {
                    $result         = $this->api_model->checkSubscribeEmail($_POST['email']);
                    $data1['email'] = $chimp_email    = $_POST['email'];
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
                                $result                = $this->api_model->subscribe_now($data1);
                                $data['status']        = 1;
                                $data['message']       = 'Successfully subscribed for Newsletter.';
                                $data['receiver_name'] = 'Subscriber';
                                $data['email_content'] = "You successfully subscribed for the newsletter on " . SITE_NAME . ".";
                                $email_tempData        = get_email_tempData(6);
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
                                    $result = $this->api_model->subscribe_now($data1);
                                }
                            }
                        }
                    }
// EMAIL SEND
//                    $_account_type         = getVal('type', 'c_users_types', 'id', $account_type);
//                    $data['receiver_name'] = $_POST['first_name'];
//                    $data['email_content'] = "This message is to inform you that your entry into the " . SITE_NAME . " has been submitted for review.   We will send you a confirmation message when your entry has been approved and activated.
//                            The following is the summary information for your account in " . SITE_NAME . ":<br><br>
//                            Account Type: " . $_account_type . "<br>
//                            First Name: " . $_POST['first_name'] . "<br>
//                            Last Name: " . $_POST['last_name'] . "<br>
//                            Email:        " . $_POST['email'] . "<br>
//                             ";
                    
                      if ($account_type == 2)
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
                    }
                    else
                    {
                        $user = getValArray('user_id,email,full_name,is_active,status,account_type,orignal_password', 'c_users', 'email', $_POST['email']);
                        updateVal('status', 1, 'users', 'email', $_POST['email']);

                    $password              = $this->common->randomPassword();
                    updateVal('orignal_password', $password, 'users', 'email', $_POST['email']);
                    updateVal('password', md5($password), 'users', 'email', $_POST['email']);
                        $data['receiver_name'] = $user['full_name'];

                        $data['email_content'] = "Following is the information regarding your email and password.
                                <br /><br />User Name: <b>" . $user['full_name'] . "</b>
                            <br /><br />
                            Login Email: <b>" . $_POST['email'] . "</b>
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
                    }
//                    $email_tempData        = get_email_tempData(1);
//                    if (!empty($email_tempData))
//                    {
//                        $data['title']           = $email_tempData['title'];
//                        $data['content']         = $email_tempData['content'];
//                        $data['welcome_content'] = $email_tempData['welcome_content'];
//                        $data['footer']          = $email_tempData['footer'];
//                        $subject                 = $data['title'];
//                        $email_content           = $this->load->view('includes/email_templates/email_template', $data, true);
//                        $this->emailutility->accountVarification($email_content, $_POST['email'], $subject);
//                    }
// END EMAIL SEND
                    unset($data);
                    $data            = array();
                    $data['status']  = 1;
//                    $data['user_id'] = $insert_id;
                    if ($account_type == 1)
                    {
                        $data['message'] = 'Congratulation! Your account has been created Successfully, Please check your email to get login credentials.';
                    }
                    else
                    {
                        $data['message'] = 'Congratulation! Your account has been submitted for review. We will send you a confirmation email when your entry has been approved and activated.';
                    }
                }
                else
                {
                    $data['status']  = 0;
                    $data['message'] = 'Not able to register. Try again later.';
                }
            }
            else
            {
                $data['status']  = 0;
                $data['message'] = 'Email already exists. Please try another one.';
            }
        }
        else
        {
            $data['status']  = 0;
            $data['message'] = 'Invalid data provided.';
        }
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Method: update_profile
     * Params: $post
     * Return: Json
     */
    function update_profile()
    {
        $data      = array();
        $post_data = array();
        if ($_POST['user_id'] <> '')
        {
            $verify = $this->api_model->verify_user_id($_POST['user_id']);
            if ($verify == 1)
            {
                $user_id                          = $_POST['user_id'];
                $post_data['first_name']          = $_POST['first_name'];
                $post_data['last_name']           = $_POST['last_name'];
                $post_data['full_name']           = $_POST['first_name'] . ' ' . $_POST['last_name'];
                $post_data['email']               = $_POST['email'];
                $post_data['about_me']            = $_POST['about_me'];
                $post_data['phone']               = $_POST['phone'];
                $post_data['fax']                 = $_POST['fax'];
                $post_data['city']                = $_POST['city'];
                $post_data['state']               = $_POST['state'];
                $post_data['country']             = $_POST['country'];
                $post_data['address']             = $_POST['address'];
                $post_data['paypal_email']        = $_POST['paypal_email'];
                $post_data['zip_code']            = $_POST['zip_code'];
                $post_data['payment_type']        = $_POST['payment_type'];
                $post_data['account_holder_name'] = $_POST['account_holder_name'];
                $post_data['account_number']      = $_POST['account_number'];
                $post_data['iban_code']           = $_POST['iban_code'];
                $post_data['swift_code']          = $_POST['swift_code'];
                $post_data['sort_code']           = $_POST['sort_code'];
                $post_data['bank_name']           = $_POST['bank_name'];
                $post_data['bank_address']        = $_POST['bank_address'];
                $post_data['payment_schedule']    = $_POST['payment_schedule'];
                $post_data['currency']            = $_POST['currency'];

                $results = $this->api_model->update_profile($post_data, $user_id);
                if ($results > 0)
                {
                    $data['status'] = 1;
                }
                else
                {
                    $data['status']  = 0;
                    $data['message'] = 'Profile not updated. Please try again later.';
                }
            }
            else
            {
                $data['status']  = 0;
                $data['message'] = 'User not exist.';
            }
        }
        else
        {
            $data['status']  = 0;
            $data['message'] = 'Invalid data provided.';
        }
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Method: change_password
     * Params: $post
     * Return: Json
     */
    function change_password()
    {
        $data      = array();
        $post_data = array();
        if ($_POST['user_id'] <> '')
        {
            $verify = $this->api_model->verify_user_id($_POST['user_id']);
            if ($verify == 1)
            {
                $user_id = $_POST['user_id'];

                $post_data['orignal_password'] = $_POST['password'];
                $post_data['password']         = md5($_POST['password']);
                $results                       = $this->api_model->update_profile($post_data, $user_id);
                if ($results > 0)
                {
                    $data['status'] = 1;
                }
                else
                {
                    $data['status']  = 0;
                    $data['message'] = 'Password not updated. Please try again later.';
                }
            }
            else
            {
                $data['status']  = 0;
                $data['message'] = 'User not exist.';
            }
        }
        else
        {
            $data['status']  = 0;
            $data['message'] = 'Invalid data provided.';
        }
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    function get_user_profile()
    {
        $data = array();
        if ($_GET['user_id'] <> '' || $_GET['user_id'] <> 0)
        {
            $user_id = $_GET['user_id'];
            $verify  = $this->api_model->verify_user_id($user_id);
            if ($verify == 1)
            {
                $data['status'] = 1;
                $data['user']   = $this->api_model->get_user_data($user_id);
            }
            else
            {
                $data['status']  = 0;
                $data['message'] = 'User not exist.';
            }
        }
        else
        {
            $data['status']  = 0;
            $data['message'] = 'Invalid data provided.';
        }
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    function update_photo()
    {
        $data      = array();
        $post_data = array();
        if ($_POST['user_id'] <> '')
        {
            $verify = $this->api_model->verify_user_id($_POST['user_id']);
            if ($verify == 1)
            {
                $user_id = $_POST['user_id'];
                $photo   = '';
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
                $post_data['photo'] = $photo;
                $results            = $this->api_model->update_profile($post_data, $user_id);
                if ($results > 0)
                {
                    $data['status'] = 1;
                    if ($photo <> '')
                    {
                        $data['photo'] = base_url("uploads/users/medium/" . $photo);
                    }
                }
                else
                {
                    $data['status']  = 0;
                    $data['message'] = 'Profile not updated. Please try again later.';
                }
            }
            else
            {
                $data['status']  = 0;
                $data['message'] = 'User not Exists.';
            }
        }
        else
        {
            $data['status']  = 0;
            $data['message'] = 'Invalid data provided.';
        }
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Method: forgot_password
     * params:
     * Retruns:
     */
    function forgot_password()
    {
        $data = array();
        if ($this->input->post())
        {
            $email = $_POST['email'];
            $res   = $this->api_model->verify_email($email);
            if ($res == 1)
            {
                $password = $this->common->randomPassword();
                $db_query = $this->api_model->updateUser($email, $password);
                /*                 * ** Send password Email Start ***** */
                if ($db_query)
                {
                    $email                 = $_POST['email'];
                    $name                  = get_user_col_value('full_name', 'email', $email);
                    $data['receiver_name'] = $name['full_name'];
                    $data['email_content'] = "Following is the information regarding your Username and Password.
                            <br /><br />
                            Login Email: <b>" . $email . "</b>
                            <br /><br />
                            Login Password: <b>" . $password . "</b>
                            <br /><br />
                            You can change the password from edit profile after&nbsp;<a  class='blue_btn' href='" . base_url('login') . "'>Login</a>
                            ";
                    $email_tempData        = get_email_tempData(2);
                    if (!empty($email_tempData))
                    {
                        $data['title']           = $email_tempData['title'];
                        $data['content']         = $email_tempData['content'];
                        $data['welcome_content'] = $email_tempData['welcome_content'];
                        $data['footer']          = $email_tempData['footer'];
                        $subject                 = $data['title'];
                        $email_content           = $this->load->view('includes/email_templates/email_template', $data, true);
                        $this->emailutility->send_email_user($email_content, $email, $subject);
                    }
                    unset($data);
                    /*                     * ** Send varification Email End ***** */
                    $data['status']  = 1;
                    $data['message'] = 'Please Check your email for password recovery.';
                }
            }
            else
            {
                $data['status']  = 0;
                $data['message'] = 'Email "' . $_POST['email'] . '" not exists. Please try another email.';
            }
        }

        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    // DASHBOARD
    function get_dashboard()
    {
//        ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
        $this->load->model('dashboard/dashboard_model');
        $this->load->model('reporting/reporting_model');
        $data = array();
        if ($_GET['user_id'] <> '' || $_GET['user_id'] <> 0)
        {

            $user_id       = $_GET['user_id'];
            $user_currency = getVal('currency', 'c_users', 'user_id', $user_id);
            $verify        = $this->api_model->verify_user_id($user_id);
            if ($verify == 1)
            {
                $data['status'] = 1;
                $account_type   = getVal('account_type', 'c_users', 'user_id', $user_id);

                if ($account_type == 2)
                {
                    $data['total_no_of_sales'] = $this->dashboard_model->getTotalPublisherSuccessLeadCounter($user_id, $filter);
                    $data['currency']          = $user_currency;
                    $data['totalProducts']     = (float) $this->dashboard_model->getTotalProducts($user_id);
                    $data['totalSharedLinks']  = (float) $this->dashboard_model->getTotalPublisherLinksShared($user_id);
                    $data['totalVisitors']     = (float) $this->dashboard_model->getTotalPublisherVisitors($user_id);
                    $data['totalCommission']   = getSiteCurrencySymbol('', $user_currency) . number_format((float) $this->dashboard_model->getPublisherTotalCommission($user_id), 2);
                    $data['totalSales']        = getSiteCurrencySymbol('', $user_currency) . number_format((float) $this->dashboard_model->getTotalPublisherSale($user_id), 2);

                    $sales = $this->dashboard_model->getTotalPublisherSales($user_id);

                    $data['sales'] = $this->getSharedOn($sales);
//                    dd($data['sales']);
                    $commission    = $this->dashboard_model->getTotalPublisherCommission($user_id);

                    $data['commission'] = $this->getSharedOn($commission);

                    foreach ($data['sales'] as $key => $value)
                    {
                        $pro_currecny                 = getVal('currency', 'c_products', 'product_id', $value['product_id']);
                        $data['sales'][$key]['price'] = getSiteCurrencySymbol('', $user_currency) . number_format(get_currency_rate($data['sales'][$key]['price'], $pro_currecny, $user_currency), 2);
                    }
                    foreach ($data['commission'] as $key => $value)
                    {
                        $pro_currecny = getVal('currency', 'c_products', 'product_id', $value['product_id']);

                        $data['commission'][$key]['total_commission']      = getSiteCurrencySymbol('', $user_currency) . number_format(get_currency_rate($data['commission'][$key]['total_commission'], $pro_currecny, $user_currency), 2);
//                        $data['commission'][$key]['advertiser_commission'] = getSiteCurrencySymbol('', $user_currency) . number_format(get_currency_rate($data['commission'][$key]['advertiser_commission'], $pro_currecny, $user_currency), 2);
                        $data['commission'][$key]['advertiser_commission'] = $data['commission'][$key]['total_commission'];
                        $data['commission'][$key]['price']                 = getSiteCurrencySymbol('', $user_currency) . number_format(get_currency_rate($data['commission'][$key]['price'], $pro_currecny, $user_currency), 2);
                    }
                }

                if ($account_type == 1)
                {
                    $data['total_no_of_sales'] = $this->dashboard_model->getTotalSuccessLeadCounter($user_id, $filter);
                    $data['currency']          = $user_currency;
                    $data['totalSharedLinks']  = (float) $this->dashboard_model->getTotalLinksShared($user_id);
                    $data['totalVisitors']     = (float) $this->dashboard_model->getTotalVisitors($user_id);
                    $data['totalCommission']   = getSiteCurrencySymbol('', $user_currency) . number_format((float) $this->dashboard_model->getTotalCommission($user_id), 2);
                    $data['totalSales']        = getSiteCurrencySymbol('', $user_currency) . number_format((float) $this->dashboard_model->getTotalSale($user_id), 2);
                    $sales                     = $this->dashboard_model->getTotalSales($user_id);

                    $data['sales']      = $this->getSharedOn($sales);
                    $commission         = $this->dashboard_model->getTotalCommissions($user_id);
                    $data['commission'] = $this->getSharedOn($commission);


//                    echo $this->dashboard_model->getTotalCommission($user_id);die();

                    foreach ($data['sales'] as $key => $value)
                    {
                        $pro_currecny                 = getVal('currency', 'c_products', 'product_id', $value['product_id']);
                        $data['sales'][$key]['price'] = getSiteCurrencySymbol('', $user_currency) . number_format(get_currency_rate($data['sales'][$key]['price'], $pro_currecny, $user_currency), 2);
                    }
                    foreach ($data['commission'] as $key => $value)
                    {
                        $pro_currecny = getVal('currency', 'c_products', 'product_id', $value['product_id']);

                        $data['commission'][$key]['advertiser_commission'] = getSiteCurrencySymbol('', $user_currency) . number_format(get_currency_rate($data['commission'][$key]['advertiser_commission'], $pro_currecny, $user_currency), 2);
//                        $data['commission'][$key]['total_commission']      = $data['commission'][$key]['advertiser_commission'];
                        $data['commission'][$key]['total_commission']      = $data['commission'][$key]['advertiser_commission'];
                        $data['commission'][$key]['price']                 = getSiteCurrencySymbol('', $user_currency) . number_format(get_currency_rate($data['commission'][$key]['price'], $pro_currecny, $user_currency), 2);
                    }
                }
            }
            else
            {
                $data['status']  = 0;
                $data['message'] = 'User not exist.';
            }
        }
        else
        {
            $data['status']  = 0;
            $data['message'] = 'Invalid data provided.';
        }
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    // MARKETING

    function get_all_categories()
    {
        $data       = array();
        $categories = $this->api_model->getAllCategories(0);
        if (count($categories) > 0)
        {
            $data['status'] = 1;
            $i              = 0;
            foreach ($categories as $key => $cat)
            {
                $data['data'][$key]              = $cat;
                $data['data'][$i]['subcategory'] = $this->api_model->getAllCategories($cat['category_id']);

                $i++;
            }
        }
        else
        {
            $data['status']  = 0;
            $data['message'] = 'Categories not exist.';
        }

        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    function get_all_marketing()
    {
        $data    = $data1   = array();
        $user_id = $_POST['user_id'];

        if ($user_id <> '' || $user_id <> 0)
        {
            $verify = $this->api_model->verify_user_id($user_id);
            if ($verify == 1)
            {
                $user_key = getVal('user_key', 'c_users', 'user_id', $user_id);

                foreach ($_POST as $k => $v)
                {
                    if (is_array($v))
                    {
                        $data1[$k] = implode(',', $v);
                    }
                    else
                    {
                        $data1[$k] = $v;
                    }
                }

                $results = $this->api_model->loadListings($data1);

                $user_currency = getVal('currency', 'c_users', 'user_id', $user_id);

                foreach ($results as $key => $value)
                {
                    if ($user_currency <> $value['currency'])
                    {
                        $results[$key]['commission'] = number_format((float) get_currency_rate($value['commission'], $value['currency'], $user_currency), 2);
                    }
                }
                $array = $results;
//dd($array);
                if (isset($_POST['type']) && $_POST['type'] <> '')
                {
                    // Lower to higher 
                    if ($_POST['type'] == 4)
                    {
                        for ($j = 0; $j < count($array); $j ++)
                        {
                            for ($i = 0; $i < count($array) - 1; $i ++)
                            {
                                $smbl  = $array[$i]['commission'];
                                $smbl1 = $array[$i + 1]['commission'];
                                if ($smbl > $smbl1)
                                {
                                    $temp          = $array[$i + 1];
                                    $array[$i + 1] = $array[$i];
                                    $array[$i]     = $temp;
                                }
                            }
                        }
                    }
                    if ($_POST['type'] == 3)
                    {
                        for ($j = 0; $j < count($array); $j ++)
                        {
                            for ($i = 0; $i < count($array) - 1; $i ++)
                            {
                                $smbl  = $array[$i]['commission'];
                                $smbl1 = $array[$i + 1]['commission'];
                                if ($smbl < $smbl1)
                                {
                                    $temp          = $array[$i + 1];
                                    $array[$i + 1] = $array[$i];
                                    $array[$i]     = $temp;
                                }
                            }
                        }
                    }
                }
                $results = $array;
                foreach ($results as $key => $value)
                {
                    $results[$key]['commission'] = getSiteCurrencySymbol('', $user_currency) . number_format((float) $value['commission'], 2);
                }


                if (count($results) > 0)
                {
                    $data['status'] = 1;

                    $i = 0;
                    foreach ($results as $re)
                    {

                        if ($re['product_type'] == 3)
                        {
                            $id        = $this->common->encode($re['product_id']);
                            $share_url = base_url('detail') . '?prd=' . $id . '&affid=' . $user_key;
                        }
                        else
                        {
                            $share_url = $re['url'] . '?affid=' . $user_key;
                        }
                        $photo = getVal('image', 'c_product_images', 'product_id', $re['product_id']);
                        if ($photo)
                        {
                            $results[$i]['product_image'] = base_url("uploads/products/pic/" . $photo);
                        }
                        else
                        {
                            $results[$i]['product_image'] = '';
                        }
                        $results[$i]['share_url'] = $share_url;
                        $i++;
                    }

//dd($results);
                    $data['data'] = $results;
                }
                else
                {
                    $data['status']  = 1;
                    $data['message'] = 'Products not exist.';
                }
            }
            else
            {
                $data['status']  = 0;
                $data['message'] = 'User not exist.';
            }
        }
        else
        {
            $data['status']  = 0;
            $data['message'] = 'Invalid data provided.';
        }
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    function getProducts()
    {
        $data    = $data1   = array();
        $user_id = $_GET['user_id'];
        $offset  = $_GET['offset'];
        $limit   = $_GET['limit'];

        if ($user_id <> '' || $user_id <> 0)
        {
            $user_currency     = getVal('currency', 'c_users', 'user_id', $user_id);
            $user_account_type = getVal('account_type', 'c_users', 'user_id', $user_id);
//            echo $user_account_type;die();
            $verify            = $this->api_model->verify_user_id($user_id);
            if ($verify == 1)
            {
                $user_key = getVal('user_key', 'c_users', 'user_id', $user_id);

                $results = $this->api_model->loadProducts($user_id, $offset, $limit);

                foreach ($results as $key => $value)
                {
                    $query_no_of_sales = $this->db->query('SELECT * FROM c_orders where product_id = ' . $value['product_id'] . ' and order_status = 2');
                    $no_of_sales       = $query_no_of_sales->num_rows();
                    if ($no_of_sales > 0)
                    {
                        $results[$key]['can_deleted'] = 0;
                    }
                    else
                    {
                        $results[$key]['can_deleted'] = 1;
                    }

                    $images_array = [];
                    $images       = getValArray('image', 'product_images', array('product_id' => $value['product_id']));
                    foreach ($images as $key_image => $image)
                    {
                        array_push($images_array, base_url('uploads/products/pic/' . $image));
                    }
                    $results[$key]['images'] = $images_array;

                    if ($user_account_type == 1)
                    {

                        $results[$key]['price']      = getSiteCurrencySymbol('', $user_currency) . number_format(get_currency_rate($results[$key]['price'], $results[$key]['currency'], $user_currency), 2);
                        $results[$key]['commission'] = getSiteCurrencySymbol('', $user_currency) . number_format(get_currency_rate($results[$key]['commission'], $results[$key]['currency'], $user_currency), 2);
                    }
                    else
                    {
                        $results[$key]['price']      = getSiteCurrencySymbol('', $results[$key]['currency']) . number_format($results[$key]['price'], 2);
                        $full_commission             = 0;
                        $full_commission             = getVal('commission', 'c_products_commission', 'product_id', $value['product_id']);
                        $results[$key]['commission'] = getSiteCurrencySymbol('', $results[$key]['currency']) . number_format($full_commission, 2);
                    }
//                    $results[$key]['price']      = getSiteCurrencySymbol('', $results[$key]['currency']).number_format($results[$key]['price'], 2);
//                    $results[$key]['commission'] = getSiteCurrencySymbol('', $results[$key]['currency']).number_format($results[$key]['commission'], 2);
//                    $results[$key]['price']      = getSiteCurrencySymbol('', $user_currency) . number_format(get_currency_rate($results[$key]['price'], $results[$key]['currency'], $user_currency), 2);
//                    $results[$key]['commission'] = getSiteCurrencySymbol('', $user_currency) . number_format(get_currency_rate($results[$key]['commission'], $results[$key]['currency'], $user_currency), 2);
                    if (($results[$key]['script_verified'] == 0 || $results[$key]['script_verified'] == 1) && $results[$key]['script_verified_by_admin'] == 1)
                    {
                        $results[$key]['is_product_activated'] = 1;
                        $results[$key]['demo_sale_link']       = $results[$key]['url'] . '?prd=' . $this->common->encode($results[$key]['product_id']) . '&affid=' . $this->common->encode($user_id);
                    }
                    else if ($results[$key]['script_verified'] == 1 && $results[$key]['script_verified_by_admin'] == 0)
                    {
                        $results[$key]['is_product_activated'] = 1;
                        $results[$key]['demo_sale_link']       = $results[$key]['url'] . '?prd=' . $this->common->encode($results[$key]['product_id']) . '&affid=' . $this->common->encode($user_id);
                    }
                    else
                    {
                        $results[$key]['is_product_activated'] = 0;
                        $results[$key]['demo_sale_link']       = $results[$key]['url'] . '?prd=' . $this->common->encode($results[$key]['product_id']) . '&affid=' . $this->common->encode($user_id);
                    }
                }
                if (count($results) > 0)
                {
                    $data['status'] = 1;

                    $i = 0;
                    foreach ($results as $re)
                    {

                        if ($re['product_type'] == 3)
                        {
                            $id        = $this->common->encode($re['product_id']);
                            $share_url = base_url('tracking?prd=' . $this->common->encode($re['product_id']) . '&affid=');
                        }
                        else
                        {
                            $share_url = '';
                        }

                        $results[$i]['onePixel'] = $share_url;
                        $i++;
                    }
                    $data['data']              = $results;
                    $data['activation_string'] = trim($this->load->view('activation_string', $data, true));
                }
                else
                {
                    $data['status']  = 0;
                    $data['message'] = 'Products not exist.';
                }
            }
            else
            {
                $data['status']  = 0;
                $data['message'] = 'User not exist.';
            }
        }
        else
        {
            $data['status']  = 0;
            $data['message'] = 'Invalid data provided.';
        }
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    function getProductsTypes()
    {
        $data  = array();
        $types = $this->api_model->getProductsTypes();
        if (count($types) > 0)
        {
            $data['status'] = 1;
            $data['data']   = $types;
        }
        else
        {
            $data['status']  = 0;
            $data['message'] = 'Product types not exist.';
        }

        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    function contactus()
    {

        $data = array();
        if ($_POST['email_address'] <> '')
        {
            $ipaddress = '';
            if ($_POST['ip_address'] <> '')
                $ipaddress = $_POST['ip_address'];

            $data['name']     = $name             = trim($_POST['user_name']);
            $data['email']    = $email            = trim($_POST['email_address']);
            $data['subject']  = $subject          = trim($_POST['subject']);
            $data['comments'] = $message          = nl2br($_POST['comments']);

            $data['phone']      = $number             = trim($_POST['phone']);
            $data['ip_address'] = $ipaddress;
            $data['status']     = 0;
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

            if (!empty($email_tempData))
            {
                $data['title']   = $email_tempData['title'];
                $data['content'] = $email_tempData['content'];

                $data['welcome_content'] = $email_tempData['welcome_content'];
                $data['footer']          = $email_tempData['footer'];

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

            $result = $this->api_model->save_feedback($data);
            unset($data);
            if ($result)
            {
                $data['status']  = 1;
                $data['message'] = 'Your query has been submitted to admin.';
            }
            else
            {
                $data['status']  = 0;
                $data['message'] = 'Opps error occured. Please try again.';
            }
        }
        else
        {
            $data['status']  = 0;
            $data['message'] = 'Invalid data posted.';
        }

        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    function getAllPages()
    {
        $data = array();

        $resuts = $this->api_model->get_allcmspage();

        if (count($resuts) > 0)
        {
            $i              = 0;
//            foreach ($resuts as $r) {
//
//                $resuts[$i]['page_url'] = base_url() . 'api/get_pages/' . $r['cmId'];
//                $i++;
//            }
            $data['data']   = $resuts;
            $data['status'] = 1;
        }
        else
        {
            $data['status']  = 0;
            $data['message'] = 'You dont have any CMS page yet.';
        }


        header('Content-Type: application/json');
        echo json_encode($data);
    }

    function getSocialNetworks()
    {
        $data = array();

        $resuts = $this->api_model->getSocialNetworks();

        if (count($resuts) > 0)
        {
            $data['status'] = 1;
            $data['data']   = $resuts;
        }
        else
        {
            $data['status']  = 0;
            $data['message'] = 'No social networks found.';
        }
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    function reports()
    {
        $this->load->model('reporting/reporting_model');
        $data = array();
        if ($_GET['user_id'] <> '' || $_GET['user_id'] <> 0)
        {

            $filter = array();
            foreach ($_GET as $k => $v)
            {
                $filter[$k] = $v;
            }
            $user_id = $_GET['user_id'];
            $verify  = $this->api_model->verify_user_id($user_id);
            if ($verify == 1)
            {
                $data['status'] = 1;
                $account_type   = getVal('account_type', 'c_users', 'user_id', $user_id);


                if ($account_type == 2)
                {
                    $data['totalProducts']    = (float) $this->reporting_model->getTotalProducts($user_id, $filter);
                    $data['totalSharedLinks'] = (float) $this->reporting_model->getTotalPublisherLinksSharedCounter($user_id, $filter);
                    $data['totalVisitors']    = (float) $this->reporting_model->getTotalPublisherVisitorsCounter($user_id, $filter);
                    $data['total_leads']      = $this->reporting_model->getTotalPublisherSuccessLeadCounter($user_id, $filter);
                    $data['totalSales']       = $this->reporting_model->getTotalPublisherSaleCounter($user_id, $filter);
                    $data['totalCommission']  = $this->reporting_model->getTotalPublisherCommissionCounter($user_id, $filter);
                    $data['successSales']     = $this->reporting_model->getTotalSuccessSalesCommissionCounter($user_id, $filter);
                    $data['pendingSales']     = $this->reporting_model->getTotalPendingSalesCommissionCounter($user_id, $filter);
                }
                if ($account_type == 1)
                {
                    $data['totalSharedLinks'] = (float) $this->reporting_model->getTotalLinksSharedCounter($user_id, $filter);
                    $data['totalVisitors']    = (float) $this->reporting_model->getTotalVisitorsCounter($user_id, $filter);
                    $data['totalSales']       = (float) $this->reporting_model->getTotalSuccessLeadCounter($user_id, $filter);
                    $data['totalCommission']  = $this->reporting_model->getTotalCommissionCounter($user_id, $filter);
                    $data['total_leads']      = '';
                    $data['successSales']     = $this->reporting_model->getTotaltotalCommissionEarnedCounter($user_id, $filter);
                    $data['pendingSales']     = $this->reporting_model->getTotalUnsuccessfullCommisionCounter($user_id, $filter);
                }
//                dd($data);
            }
            else
            {
                $data['status']  = 0;
                $data['message'] = 'User not exist.';
            }
        }
        else
        {
            $data['status']  = 0;
            $data['message'] = 'Invalid data provided.';
        }
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    function report_detail()
    {
        $this->load->model('reporting/reporting_model');
        $data = array();
        if ($_GET['user_id'] <> '' || $_GET['user_id'] <> 0)
        {
            $filter = array();
            foreach ($_GET as $k => $v)
            {
                $filter[$k] = $v;
            }
            $user_id = $_GET['user_id'];
            $verify  = $this->api_model->verify_user_id($user_id);
            if ($verify == 1)
            {
                $data['status'] = 1;
                $account_type   = getVal('account_type', 'c_users', 'user_id', $user_id);
                $user           = $this->api_model->get_user_data($user_id);
                $user_currency  = getSiteCurrencySymbol('', $user['currency']);

                if ($account_type == 2)
                {
                    $data['totalSharedLinks'] = (float) $this->reporting_model->getTotalPublisherLinksSharedCounter($user_id, $filter);
                    $data['totalVisitors']    = (float) $this->reporting_model->getTotalPublisherVisitorsCounter($user_id, $filter);
                    $data['totalSales']       = getSiteCurrencySymbol('', $user['currency']) . number_format((double) $this->reporting_model->getTotalPublisherSaleCounter($user_id, $filter), 2);
                    $data['totalCommission']  = getSiteCurrencySymbol('', $user['currency']) . number_format((double) $this->reporting_model->getTotalPublisherCommissionCounter($user_id, $filter), 2);
                    $data['sharedLinks']      = $sharedLinks              = $this->reporting_model->getTotalPublisherLinksShared($user_id, $filter);
//                   

                    $data['visitors']         = $this->reporting_model->getTotalPublisherVisitors($user_id, $filter);
                    $data['total_leads']      = $this->reporting_model->getTotalPublisherSuccessLeadCounter($user_id, $filter);
                    $data['sales']            = $this->reporting_model->getTotalPublisherSales($user_id, $filter);
                  
                    $data['commission']       = getSiteCurrencySymbol('', $user['currency']) . number_format((double) $this->reporting_model->getTotalPublisherCommission($user_id, $filter), 2);
                    $data['total_leads_list'] = $this->reporting_model->getTotalPublisherLeadSales($user_id, $filter);
                    $data['successSales']     = $this->reporting_model->getTotalSuccessSalesCommissionCounter($user_id, $filter);
                    $data['pendingSales']     = $this->reporting_model->getTotalPendingSalesCommissionCounter($user_id, $filter);
                    // successSalesCommission & totalUnsuccessfullCommision

                    $data['successSalesCommission'] = $this->reporting_model->getTotalSuccessSalesCommission($user_id, $filter);
                    $data['pendingSalesCommission'] = $this->reporting_model->getTotalPendingSalesCommission($user_id, $filter);
                    foreach ($data['sales'] as $key => $value)
                    {
                        $data['sales'][$key]['url'] = $data['sales'][$key]['referer_page'];
                        unset($data['sales'][$key]['order_id']);
                        unset($data['sales'][$key]['order_status']);
                        unset($data['sales'][$key]['is_confirmed']);
                        unset($data['sales'][$key]['transaction_id']);
                    }
                    foreach ($data['commission'] as $key => $value){
                    
                        $data['commission'][$key]['url'] = $data['commission'][$key]['referer_page'];
                        $data['commission'][$key]['total_commission']      = getSiteCurrencySymbol('', $user['currency']) . $data['commission'][$key]['total_commission'];
                        $data['commission'][$key]['advertiser_commission'] = getSiteCurrencySymbol('', $user['currency']) . $data['commission'][$key]['advertiser_commission'];
                    }
                    foreach ($data['successSalesCommission'] as $key => $value)
                    {
                        $data['successSalesCommission'][$key]['url'] = $data['successSalesCommission'][$key]['referer_page'];
                        $data['successSalesCommission'][$key]['seller_id']     = $user_id;
                        $data['successSalesCommission'][$key]['advertiser_id'] = $data['successSalesCommission'][$key]['user_id'];
                        if ($user['account_type'] == 1)
                        {
                            $data['successSalesCommission'][$key]['price'] = $data['successSalesCommission'][$key]['advertiser_commission'];
                        }
                        else
                        {
                            $data['successSalesCommission'][$key]['price'] = $data['successSalesCommission'][$key]['total_commission'];
                        }
                        unset($data['successSalesCommission'][$key]['user_id']);
                        unset($data['successSalesCommission'][$key]['order_id']);
                        unset($data['successSalesCommission'][$key]['advertiser_commission']);
                        unset($data['successSalesCommission'][$key]['total_commission']);
                    }
                    foreach ($data['pendingSalesCommission'] as $key => $value)
                    {
                        $data['pendingSalesCommission'][$key]['url'] = $data['pendingSalesCommission'][$key]['referer_page'];
                        $data['pendingSalesCommission'][$key]['seller_id']     = $user_id;
                        $data['pendingSalesCommission'][$key]['advertiser_id'] = $data['pendingSalesCommission'][$key]['user_id'];
                        if ($user['account_type'] == 1)
                        {
                            $data['pendingSalesCommission'][$key]['price'] = $data['pendingSalesCommission'][$key]['advertiser_commission'];
                        }
                        else
                        {
                            $data['pendingSalesCommission'][$key]['price'] = $data['pendingSalesCommission'][$key]['total_commission'];
                        }
                        unset($data['pendingSalesCommission'][$key]['user_id']);
                        unset($data['pendingSalesCommission'][$key]['order_id']);
                        unset($data['pendingSalesCommission'][$key]['advertiser_commission']);
                        unset($data['pendingSalesCommission'][$key]['total_commission']);
                    }
                    
                }
                if ($account_type == 1)
                {
                    $data['totalSharedLinks'] = (float) $this->reporting_model->getTotalLinksSharedCounter($user_id, $filter);
                    $data['totalVisitors']    = (float) $this->reporting_model->getTotalVisitorsCounter($user_id, $filter);
                    $data['totalSales']       = getSiteCurrencySymbol('', $user['currency']) . number_format((double) $this->reporting_model->getTotalSaleCounter($user_id, $filter), 2);
                    $data['totalCommission']  = $this->reporting_model->getTotalCommissionCounter($user_id, $filter);
                    $data['sharedLinks']      = $sharedLinks              = $this->reporting_model->getTotalLinksShared($user_id, $filter);
                    $data['visitors']         = $this->reporting_model->getTotalVisitors($user_id, $filter);
                    $data['total_leads']      = '';
                    $sales                    = $this->reporting_model->getTotalSuccessLeadSales($user_id, $filter);
//                    dd($sales);
                    $data['sales']            = $this->getSharedOn($sales);
                    $data['total_leads_list'] = [];
                    $data['commission']       = $this->reporting_model->getTotalCommission($user_id, $filter);
                    foreach ($data['commission'] as $key => $value)
                    {
                        $data['commission'][$key]['url'] = $data['commission'][$key]['referer_page'];
                        $data['commission'][$key]['total_commission']      = getSiteCurrencySymbol('', $user['currency']) . $data['commission'][$key]['total_commission'];
                        $data['commission'][$key]['advertiser_commission'] = $data['commission'][$key]['advertiser_commission'];
                    }
                    //totalCommissionEarned & totalUnsuccessfullCommision
                    $data['successSales']           = $this->reporting_model->getTotaltotalCommissionEarnedCounter($user_id, $filter);
                    $data['pendingSales']           = $this->reporting_model->getTotalUnsuccessfullCommisionCounter($user_id, $filter);
                    $data['successSalesCommission'] = [];
                    $data['pendingSalesCommission'] = [];
//                    dd($data);
                }

                $shared_link_arr = array();
                foreach ($sharedLinks as $key => $lnk)
                {
                    $facebook = $twitter  = $email    = $links    = $linkedin = 0;
                    $date     = date('Y-m-d', $lnk['created']);
                    if ($account_type == 2)
                    {
                        $fb   = $this->reporting_model->getPublisherShareLinkCounter($lnk['product_id'], $date, 1, $user_id);
                        $tw   = $this->reporting_model->getPublisherShareLinkCounter($lnk['product_id'], $date, 2, $user_id);
                        $em   = $this->reporting_model->getPublisherShareLinkCounter($lnk['product_id'], $date, 3, $user_id);
                        $ln   = $this->reporting_model->getPublisherShareLinkCounter($lnk['product_id'], $date, 4, $user_id);
                        $lnk1 = $this->reporting_model->getPublisherShareLinkCounter($lnk['product_id'], $date, 5, $user_id);
                    }
                    if ($account_type == 1)
                    {
                        $fb   = $this->reporting_model->getShareLinkCounter($lnk['product_id'], $date, 1, $user_id);
                        $tw   = $this->reporting_model->getShareLinkCounter($lnk['product_id'], $date, 2, $user_id);
                        $em   = $this->reporting_model->getShareLinkCounter($lnk['product_id'], $date, 3, $user_id);
                        $ln   = $this->reporting_model->getShareLinkCounter($lnk['product_id'], $date, 4, $user_id);
                        $lnk1 = $this->reporting_model->getShareLinkCounter($lnk['product_id'], $date, 5, $user_id);
                    }
                    $facebook = $facebook + $fb;
                    $twitter  = $twitter + $tw;
                    $email    = $email + $em;
                    $links    = $links + $ln;
                    $linkedin = $linkedin + $lnk1;


                    if ($facebook > 0)
                    {
                        for ($a = 0; $a < $facebook; $a++)
                        {
                            $data['sharedLinks'][$key]['share_type']   = $data['sharedLinks'][$key]['referer_page'] = 'facebook';
                            array_push($shared_link_arr, $data['sharedLinks'][$key]);
                        }
                    }
                    if ($twitter > 0)
                    {
                        for ($b = 0; $b < $twitter; $b++)
                        {
                            $data['sharedLinks'][$key]['share_type']   = $data['sharedLinks'][$key]['referer_page'] = 'twitter';
                            array_push($shared_link_arr, $data['sharedLinks'][$key]);
                        }
                    }
                    if ($email > 0)
                    {
                        for ($c = 0; $c < $email; $c++)
                        {
                            $data['sharedLinks'][$key]['share_type']   = $data['sharedLinks'][$key]['referer_page'] = 'email';
                            array_push($shared_link_arr, $data['sharedLinks'][$key]);
                        }
                    }
                    if ($links > 0)
                    {
                        for ($d = 0; $d < $links; $d++)
                        {
                            $data['sharedLinks'][$key]['share_type']   = $data['sharedLinks'][$key]['referer_page'] = '';
                            array_push($shared_link_arr, $data['sharedLinks'][$key]);
                        }
                    }
                    if ($linkedin > 0)
                    {
                        for ($e = 0; $e < $linkedin; $e++)
                        {
                            $data['sharedLinks'][$key]['share_type']   = $data['sharedLinks'][$key]['referer_page'] = 'linkedin';
                            array_push($shared_link_arr, $data['sharedLinks'][$key]);
                        }
                    }
                }

                $data['sharedLinks'] = $shared_link_arr;


                $fb_visit  = $tw_visit  = $em_visit  = $ln_visit  = $lnk_visit = 0;
                foreach ($data['visitors'] as $lnk)
                {
                    $date = date('Y-m-d', $lnk['created']);
                    if (strpos($lnk['referer_page'], 'facebook') !== false)
                    {
                        $fb_visit++;
                    }
                    else if (strpos($lnk['referer_page'], 'twitter') !== false)
                    {
                        $tw_visit++;
                    }
                    else if ($lnk['referer_page'] == 'email')
                    {
                        $em_visit++;
                    }
                    else if ($lnk['referer_page'] == 'linkedin')
                    {
                        $lnk_visit++;
                    }
                    else
                    {
                        $ln_visit++;
                    }
                }
                $arrd                    = array();
                $arrd['facebook']        = $fb_visit;
                $arrd['twitter']         = $tw_visit;
                $arrd['email']           = $em_visit;
                $arrd['link']            = $ln_visit;
                $arrd['linkedin']        = $lnk_visit;
                $data['linkSharedGraph'] = $arrd;
            }
            else
            {
                $data['status']  = 0;
                $data['message'] = 'User not exist.';
            }
        }
        else
        {
            $data['status']  = 0;
            $data['message'] = 'Invalid data provided.';
        }
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    function delete_product()
    {
        $data = array();
        if ($_GET['user_id'] <> '' || $_GET['user_id'] <> 0)
        {
            $user_id = $_GET['user_id'];
            $verify  = $this->api_model->verify_user_id($user_id);
            if ($verify == 1)
            {
                $product_id = $_GET['product_id'];
                $res        = $this->api_model->delete_products($product_id, $user_id);
                if ($res)
                {
                    $data['status']  = 1;
                    $data['message'] = 'Product deleted successfully.';
                }
                else
                {
                    $data['status']  = 0;
                    $data['message'] = 'Error occured during product deletion. Please try again later.';
                }
            }
            else
            {
                $data['status']  = 0;
                $data['message'] = 'User not exist.';
            }
        }
        else
        {
            $data['status']  = 0;
            $data['message'] = 'Invalid data provided.';
        }
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    function add_product()
    {
        $data = array();
//         @mail ( "saeed@arhamsoft.com", "Message", "Done<br /><pre>" . print_r ( $_POST, true ) . "</pre>" );

        if ($_POST['user_id'] <> '' || $_POST['user_id'] <> 0)
        {
            $user_id = $_POST['user_id'];
            $verify  = $this->api_model->verify_user_id($user_id);
            if ($verify == 1)
            {

                $insert_id = $this->api_model->saveItem($_POST);

                $_product_name = $_POST['product_name'];
                $_product_name = preg_replace('~[^\\pL\d]+~u', '-', trim($_product_name));
                $_product_name = trim($_product_name, '-');
                $_product_name = iconv('utf-8', 'us-ascii//TRANSLIT', $_product_name);
                $_product_name = strtolower($_product_name);
                $_product_name = preg_replace('~[^-\w]+~', '', $_product_name);

                $slug = $_product_name . '-' . $insert_id['id'];

                $this->api_model->update_product_slug($slug, $insert_id['id']);

//             for ($i = 0; $i < count($_FILES['images']['name']); $i++) {

                if ($_FILES ['images']['name'] != "")
                {

                    $this->api_model->delete_product_images($insert_id['id']);
                    $extension   = $this->common->getExtension($_FILES ['images'] ['name']);
                    $extension   = strtolower($extension);
//                            if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
//                                return false;
//                            }
                    $path        = 'uploads/products/';
                    $allow_types = 'gif|jpg|jpeg|png';
                    $max_height  = '8000';
                    $max_width   = '8000';

                    $post_image['image'] = $this->common->do_upload_image_product($path, $allow_types, $max_height, $max_width, $_FILES ['images']['tmp_name'], $_FILES ['images']['name']);

                    $post_image['product_id'] = $insert_id['id'];
                    $post_image['status']     = 1;

                    $this->api_model->add_product_images($post_image);
                }
//                    }


                if ($insert_id['msg'])
                {
                    $data['status']  = 1;
                    $data['message'] = 'Product successfully saved.';
                }
                else
                {
                    $data['status']  = 0;
                    $data['message'] = 'Opps! Error saving informtion. Please try again.';
                }
            }
            else
            {
                $data['status']  = 0;
                $data['message'] = 'User not exist.';
            }
        }
        else
        {
            $data['status']  = 0;
            $data['message'] = 'Invalid data provided.';
        }
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    function edit_product()
    {
        $data = array();
        if ($_POST['user_id'] <> '' || $_POST['user_id'] <> 0)
        {
            $user_id = $_POST['user_id'];
            $verify  = $this->api_model->verify_user_id($user_id);
            if ($verify == 1)
            {
                $data['status'] = 1;
                $product_id     = $_POST['product_id'];
                $data['data']   = $this->api_model->getRow($product_id);
            }
            else
            {
                $data['status']  = 0;
                $data['message'] = 'User not exist.';
            }
        }
        else
        {
            $data['status']  = 0;
            $data['message'] = 'Invalid data provided.';
        }
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    function shareLinkCopy()
    {
        $data                  = array();
        $data['product_id']    = $_POST['product_id'];
        $data['link']          = $_POST['link'];
        $data['user_id']       = $_POST['user_id'];
        $data['share_type']    = $_POST['type'];
        $data['share_counter'] = 1;
        $data['created']       = time();
        $this->api_model->saveSharedLinkCopy($data);

        unset($data);
        $data['status'] = 1;

        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    // NEW APIS :: MOHSIN :: START

    /**
     * Method: get_wallet
     * Params: $get
     * Return: Json
     */
    function get_wallet()
    {
        $data = array();
        if (isset($_GET['user_id']) && $_GET['user_id'] <> '')
        {
            $user_id        = $_GET['user_id'];
            $user           = $this->api_model->get_user_data($user_id);
            $user_currency  = getSiteCurrencySymbol('', $user['currency']);
            $limit_withdraw = (double) unserialize(LIMIT_WITHDRAW)[$user['currency']];
            ////////////////

            $totalCommissionOrders = $this->wallet_model->getTotalCommissionOrders($user_id);
            $totalCommission       = $this->wallet_model->getTotalCommission($user_id);
            $totalCommissionAdmin  = $this->wallet_model->getTotalCommissionAdmin($user_id);
            $totalCommission       = $totalCommission_bk    = $totalCommissionAdmin + $totalCommission;

            $totalUnconfirmedCommission = $this->wallet_model->getTotalUnconfirmedCommission($user_id);


            $lastWithdrawAmount         = $this->wallet_model->getLastSuccessWidthdraw($user_id);
            $lastWithdrawAmount_desc    = $this->wallet_model->getLastsWidthdraw($user_id);
            $wallet_list                = $this->api_model->getWithdrawRequests($user_id);
            $pendingwithdrawRequestsSUM = $this->wallet_model->getWithdrawRequestsSUMByStatus(0, $user_id);
            $successwithdrawRequestsSUM = $this->wallet_model->getWithdrawRequestsSUMByStatus(1, $user_id);

            $totalPendingPayment = ((empty($pendingwithdrawRequestsSUM['total_amount']) || $pendingwithdrawRequestsSUM['total_amount'] > 0) ? $pendingwithdrawRequestsSUM['total_amount'] : 0);
            $totalsuccessPayment = ((empty($successwithdrawRequestsSUM['total_amount']) || $successwithdrawRequestsSUM['total_amount'] > 0) ? $successwithdrawRequestsSUM['total_amount'] : 0);

            $totalCommission    = $totalCommission - $totalPendingPayment;
            // $totalCommission = $totalCommission - $totalsuccessPayment;
            // if ($totalCommission < 0)
            // {
            //     $totalCommission = $totalsuccessPayment + $totalCommission;
            // }
            $totalCommission    = number_format(($totalCommission), 2);
            $totalCommission_bk = number_format(($totalCommission_bk), 2);

            if ($totalCommission >= 0)
            {
                $totalCommission = $totalCommission;
            }
            else
            {
                $totalCommission = 0;
            }


            if ($totalCommission < 0)
            {
                $totalCommission = number_format(0, 2);
            }
            $amount_withdraw = $totalCommission;
            $wallet_ballance = $totalCommission;

            ///////////////
//            $wallet_list                = $this->api_model->getWithdrawRequests($user_id);
//            $wallet_ballance            = $this->api_model->getTotalWallet($user_id);
//            $wallet_withdraw_history    = $this->api_model->getWithdrawRequestsSUM($user_id);
//            $successwithdrawRequestsSUM = $this->api_model->getWithdrawRequestsSUM($user_id, 1);
//            $totalUnconfirmedCommission = $this->api_model->getTotalUnconfirmedCommission($user_id);
//            $totalsuccessPayment        = (isset($successwithdrawRequestsSUM)) ? $successwithdrawRequestsSUM : 0;
//            $totalPendingPayment        = (isset($pendingwithdrawRequestsSUM)) ? $pendingwithdrawRequestsSUM : 0;
//            $lastWithdrawAmount         = $this->wallet_model->getLastSuccessWidthdraw($user_id);
//
//            $TunBalance = $totalUnconfirmedCommission - $pendingwithdrawRequestsSUM - $totalsuccessPayment;
//
//            if ($pendingwithdrawRequestsSUM > 0)
//            {
//                $wallet_ballance = abs(($wallet_ballance) - $pendingwithdrawRequestsSUM);
//            }
//            else
//            {
//                $wallet_ballance = abs(($wallet_ballance) - $totalsuccessPayment);
//            }


            $data['status']               = 1;
            $data['wallet_list']          = ($wallet_list <> null) ? $wallet_list : [];
            $data['current_balance']      = ($wallet_ballance <> null) ? $user_currency . number_format($wallet_ballance, 2) : $user_currency . '0.00';
            $data['min_withdraw_amount']  = ($limit_withdraw <> null) ? $user_currency . number_format($limit_withdraw, 2) : $user_currency . '0.00';
            $data['withdraw_history']     = ($wallet_withdraw_history <> null) ? $user_currency . number_format($wallet_withdraw_history, 2) : $user_currency . '0.00';
            $data['can_withdraw_amount']  = ($wallet_ballance <> null && (double) $wallet_ballance >= (double) $limit_withdraw) ? 1 : 0;
            $data['last_withdraw_amount'] = ($lastWithdrawAmount <> null) ? getSiteCurrencySymbol('', $lastWithdrawAmount['wd_currency']) . number_format($lastWithdrawAmount['lastWithdrawAmount'], 2) : $user_currency . '0.00';
        }
        else
        {
            $system_currency              = getSiteCurrencySymbol('', CURRENCY);
            $data['status']               = 1;
            $data['wallet_list']          = [];
            $data['current_balance']      = '';
            $data['min_withdraw_amount']  = $system_currency . number_format($limit_withdraw, 2);
            $data['withdraw_history']     = '';
            $data['can_withdraw_amount']  = 0;
            $data['last_withdraw_amount'] = 0;
        }

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    /**
     * Method: withdraw_request
     * Params: $post
     * Return: Json
     */
    function withdraw_request()
    {
        $data = array();
        if (isset($_POST['user_id']) && $_POST['user_id'] <> '')
        {
            $user_id        = $_POST['user_id'];
            $user           = $this->api_model->get_user_data($user_id);
            $user_currency  = getSiteCurrencySymbol('', $user['currency']);
            $limit_withdraw = (double) unserialize(LIMIT_WITHDRAW)[$user['currency']];

            ////////////
            $totalCommissionOrders = $this->wallet_model->getTotalCommissionOrders($user_id);
            $totalCommission       = $this->wallet_model->getTotalCommission($user_id);
            $totalCommissionAdmin  = $this->wallet_model->getTotalCommissionAdmin($user_id);
            $totalCommission       = $totalCommission_bk    = $totalCommissionAdmin + $totalCommission;

            $totalUnconfirmedCommission = $this->wallet_model->getTotalUnconfirmedCommission($user_id);

            $data['lastWithdrawAmount'] = $lastWithdrawAmount         = $this->wallet_model->getLastSuccessWidthdraw($user_id);
            $wallet_list                = $this->api_model->getWithdrawRequests($user_id);
            $pendingwithdrawRequestsSUM = $this->wallet_model->getWithdrawRequestsSUMByStatus(0, $user_id);
            $successwithdrawRequestsSUM = $this->wallet_model->getWithdrawRequestsSUMByStatus(1, $user_id);

            $totalPendingPayment = ((empty($pendingwithdrawRequestsSUM['total_amount']) || $pendingwithdrawRequestsSUM['total_amount'] > 0) ? $pendingwithdrawRequestsSUM['total_amount'] : 0);
            $totalsuccessPayment = ((empty($successwithdrawRequestsSUM['total_amount']) || $successwithdrawRequestsSUM['total_amount'] > 0) ? $successwithdrawRequestsSUM['total_amount'] : 0);

            $totalCommission    = $totalCommission - $totalPendingPayment;
            // $totalCommission = $totalCommission - $totalsuccessPayment;
            // if ($totalCommission < 0)
            // {
            //     $totalCommission = $totalsuccessPayment + $totalCommission;
            // }
            $totalCommission    = number_format(($totalCommission), 2);
            $totalCommission_bk = number_format(($totalCommission_bk), 2);

            if ($totalCommission >= 0)
            {
                $totalCommission = $totalCommission;
            }
            else
            {
                $totalCommission = 0;
            }

            if ($totalCommission < 0)
            {
                $totalCommission = number_format(0, 2);
            }
            $amount_withdraw = $totalCommission;
            $wallet_ballance = $totalCommission;
            /////////////
//            $amount_withdraw            = $this->api_model->getTotalWallet($user_id);
//            $totalCommissionOrders      = $this->api_model->getTotalCommissionOrders($user_id);
//            $pendingwithdrawRequestsSUM = $this->api_model->getWithdrawRequestsSUM($user_id, 0);
//            $successwithdrawRequestsSUM = $this->api_model->getWithdrawRequestsSUM($user_id, 1);
//            $totalsuccessPayment        = (isset($successwithdrawRequestsSUM)) ? $successwithdrawRequestsSUM : 0;
//            $totalPendingPayment        = (isset($pendingwithdrawRequestsSUM)) ? $pendingwithdrawRequestsSUM : 0;
//
//            $wallet_list                = $this->api_model->getWithdrawRequests($user_id);
//            $wallet_ballance            = $this->api_model->getTotalWallet($user_id);
//            $wallet_withdraw_history    = $this->api_model->getWithdrawRequestsSUM($user_id);
//            $successwithdrawRequestsSUM = $this->api_model->getWithdrawRequestsSUM($user_id, 1);
//            $pendingwithdrawRequestsSUM = $this->api_model->getWithdrawRequestsSUM($user_id, 0);
//            $totalUnconfirmedCommission = $this->api_model->getTotalUnconfirmedCommission($user_id);
//            $totalsuccessPayment        = (isset($successwithdrawRequestsSUM)) ? $successwithdrawRequestsSUM : 0;
//            $totalPendingPayment        = (isset($pendingwithdrawRequestsSUM)) ? $pendingwithdrawRequestsSUM : 0;
//
//            $TunBalance = $totalUnconfirmedCommission - $pendingwithdrawRequestsSUM - $totalsuccessPayment;
//            
//            if ($pendingwithdrawRequestsSUM > 0)
//            {
//                $amount_withdraw = abs(($amount_withdraw) - $pendingwithdrawRequestsSUM);
//            }
//            else
//            {
//                $amount_withdraw = abs(($amount_withdraw) - $totalsuccessPayment);
//            }
//dd();
            if ($amount_withdraw < $limit_withdraw)
            {
                $data['status']               = 1;
                $data['wallet_list']          = ($wallet_list <> null) ? $wallet_list : [];
                $data['current_balance']      = ($wallet_ballance <> null) ? $user_currency . number_format($wallet_ballance, 2) : $user_currency . '0.00';
                $data['min_withdraw_amount']  = ($limit_withdraw <> null) ? $user_currency . number_format($limit_withdraw, 2) : $user_currency . '0.00';
                $data['withdraw_history']     = ($wallet_withdraw_history <> null) ? $user_currency . number_format($wallet_withdraw_history, 2) : $user_currency . '0.00';
                $data['can_withdraw_amount']  = ($wallet_ballance <> null && (double) $wallet_ballance >= (double) $limit_withdraw) ? 1 : 0;
                $data['last_withdraw_amount'] = ($lastWithdrawAmount <> null) ? getSiteCurrencySymbol('', $lastWithdrawAmount['wd_currency']) . number_format($lastWithdrawAmount['lastWithdrawAmount'], 2) : $user_currency . '0.00';
                $data['message']              = 'You commission withdraw request must be greater or equal to ' . $user_currency . number_format($limit_withdraw, 2);
                header('Content-Type: application/json');
                echo json_encode($data);
                exit;
            }
            $db = $this->api_model->saveWithdrawRequest($amount_withdraw, $totalCommissionOrders, $user);
            if ($db)
            {
                $data['status']               = 1;
                $data['wallet_list']          = ($wallet_list <> null) ? $wallet_list : [];
                $data['current_balance']      = ($wallet_ballance <> null) ? $user_currency . number_format($wallet_ballance, 2) : $user_currency . '0.00';
                $data['min_withdraw_amount']  = ($limit_withdraw <> null) ? $user_currency . number_format($limit_withdraw, 2) : $user_currency . '0.00';
                $data['withdraw_history']     = ($wallet_withdraw_history <> null) ? $user_currency . number_format($wallet_withdraw_history, 2) : $user_currency . '0.00';
                $data['can_withdraw_amount']  = ($wallet_ballance <> null && (double) $wallet_ballance >= (double) $limit_withdraw) ? 1 : 0;
                $data['last_withdraw_amount'] = ($lastWithdrawAmount <> null) ? getSiteCurrencySymbol('', $lastWithdrawAmount['wd_currency']) . number_format($lastWithdrawAmount['lastWithdrawAmount'], 2) : $user_currency . '0.00';
                $data['message']              = 'You have successfully made you withdraw request.';
            }
            else
            {
                $data['status']               = 1;
                $data['wallet_list']          = ($wallet_list <> null) ? $wallet_list : [];
                $data['current_balance']      = ($wallet_ballance <> null) ? $user_currency . number_format($wallet_ballance, 2) : $user_currency . '0.00';
                $data['min_withdraw_amount']  = ($limit_withdraw <> null) ? $user_currency . number_format($limit_withdraw, 2) : $user_currency . '0.00';
                $data['withdraw_history']     = ($wallet_withdraw_history <> null) ? $user_currency . number_format($wallet_withdraw_history, 2) : $user_currency . '0.00';
                $data['can_withdraw_amount']  = ($wallet_ballance <> null && (double) $wallet_ballance >= (double) $limit_withdraw) ? 1 : 0;
                $data['last_withdraw_amount'] = 0;
                $data['message']              = 'Your withdraw request cannot be completed at this time.';
            }
        }
        else
        {
            $data['status']  = 0;
            $data['message'] = 'Invalid data provided.';
        }

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    /**
     * Method: get_invoices
     * Params: $post
     * Return: Json
     */
    function get_invoices()
    {
        $data = array();
        if (isset($_GET['user_id']) && $_GET['user_id'] <> '')
        {
            $count         = $_GET['count'];
            $offset        = $_GET['offset'];
            $user_id       = $_GET['user_id'];
            $user          = $this->api_model->get_user_data($user_id);
            $user_currency = getSiteCurrencySymbol('', $user['currency']);
            $invoices      = $this->api_model->getInvoices($user_id, $count, $offset);

            $data['status']   = 1;
            $data['invoices'] = ($invoices <> null) ? $invoices : [];
        }
        else
        {
            $system_currency  = getSiteCurrencySymbol('', CURRENCY);
            $data['status']   = 1;
            $data['invoices'] = [];
        }

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    /**
     * Method: get_invoice
     * Params: $post
     * Return: Json
     */
    function get_invoice_details()
    {
        $data = array();
        if (isset($_GET['user_id']) && $_GET['user_id'] <> '' && isset($_GET['invoice_id']) && $_GET['invoice_id'] <> '')
        {
            $user_id       = $_GET['user_id'];
            $invoice_id    = $_GET['invoice_id'];
            $check_invoice = getVal('invoice_id', 'c_invoices', array('invoice_id' => $invoice_id, 'publisher_id' => $user_id));
            if (trim($check_invoice) == '')
            {
                $system_currency                  = getSiteCurrencySymbol('', CURRENCY);
                $data['status']                   = 0;
                $data['invoice_id']               = 0;
                $data['invoice_number']           = '';
                $data['product_names']            = '';
                $data['invoice_currency']         = '';
                $data['payment_status']           = '';
                $data['date_from']                = '';
                $data['date_to']                  = '';
                $data['total_product_commission'] = '';
                $data['total_sales_counter']      = '';
                $data['total_user_commission']    = '';
                $data['invoices']                 = [];
                header('Content-Type: application/json');
                echo json_encode($data);
                exit;
            }

            $user          = $this->api_model->get_user_data($user_id);
            $user_currency = getSiteCurrencySymbol('', $user['currency']);

            $invoice        = $this->api_model->getInvoice($user_id, $invoice_id);
            $invoice_detial = $this->api_model->getPreviousOrders($invoice['publisher_id'], $invoice['payment_schedule'], $user, $invoice_id);

            $CURRENCY_CHAR = getVal('currency_name', 'c_currencies', array('currency_id' => $invoice_detial['invoice_currency']));
            $CURRENCY_CHAR = strtoupper(trim($CURRENCY_CHAR));

            $data['status']           = 1;
            $data['invoice_id']       = $invoice['invoice_id'];
            $data['invoice_number']   = $invoice['invoice_number'];
            $data['product_names']    = $invoice_detial['item_names'];
            $data['invoice_currency'] = $CURRENCY_CHAR;
            $data['payment_status']   = (($invoice['status'] == 1) ? 'Paid' : 'Pending');
            $data['date_from']        = date('Y-m-d', $invoice['from_datetime']);
            $data['date_to']          = date('Y-m-d', $invoice['to_datetime']);

            $data['total_product_commission'] = $invoice_detial['prd_commission'];
            $data['total_sales_counter']      = $invoice_detial['total_share_counter'];
            $data['total_user_commission']    = $invoice_detial['total_commission_sum'];

            $data['invoices'] = ($invoice_detial['list'] <> null) ? $invoice_detial['list'] : [];
        }
        else
        {
            $system_currency                  = getSiteCurrencySymbol('', CURRENCY);
            $data['status']                   = 1;
            $data['invoice_id']               = 0;
            $data['invoice_number']           = '';
            $data['product_names']            = '';
            $data['invoice_currency']         = '';
            $data['payment_status']           = '';
            $data['date_from']                = '';
            $data['date_to']                  = '';
            $data['total_product_commission'] = '';
            $data['total_sales_counter']      = '';
            $data['total_user_commission']    = '';
            $data['invoices']                 = [];
        }

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    /**
     * Method: product_commission_details
     * Params: $post
     * Return: Json
     */
    function product_commission_details()
    {

        $data = array();
        if (isset($_GET['user_id']) && $_GET['user_id'] <> '' && isset($_GET['product_id']) && $_GET['product_id'] <> '')
        {
            $user_id       = $_GET['user_id'];
            $product_id    = $_GET['product_id'];
            $count         = $_GET['count'];
            $offset        = $_GET['offset'];
            $check_product = getVal('product_id', 'c_products', array('product_id' => $product_id, 'user_id' => $user_id));

            if (trim($check_product) == '')
            {
                $system_currency                  = getSiteCurrencySymbol('', CURRENCY);
                $data['status']                   = 1;
                $data['product_commisssion_list'] = [];
                header('Content-Type: application/json');
                echo json_encode($data);
                exit;
            }

            $user          = $this->api_model->get_user_data($user_id);
            $user_currency = getSiteCurrencySymbol('', $user['currency']);

            $commission_list = $this->api_model->loadCommisionListings($user_id, $user, $product_id, $count, $offset);

            foreach ($commission_list as $key => $value)
            {
                $is_generated = $this->db->query('SELECT * FROM `c_invoice_orders` where FIND_IN_SET(' . $value['unique_id'] . ',order_ids)')->result_array();
                if (sizeof($is_generated) == 0)
                {
                    $commission_list[$key]['invoice_generated'] = 0;
                }
                else
                {
                    $commission_list[$key]['invoice_generated'] = 1;
                }
            }

            $data['status']                   = 1;
            $data['product_commisssion_list'] = ($commission_list <> null) ? $commission_list : [];
        }
        else
        {
            $system_currency                  = getSiteCurrencySymbol('', CURRENCY);
            $data['status']                   = 0;
            $data['product_commisssion_list'] = [];
        }

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    function product_commission_status()
    {
        $data = array();
        if (isset($_GET['user_id']) && $_GET['user_id'] <> '' && isset($_GET['order_id']) && $_GET['order_id'] <> '' && isset($_GET['sales_status']) && ($_GET['sales_status'] == 1 || $_GET['sales_status'] == 0))
        {
            $user_id             = $_GET['user_id'];
            $order_id            = $_GET['order_id'];
            $order_status_before = $sales_status        = (int) $_GET['sales_status'];

            $user          = $this->api_model->get_user_data($user_id);
            $user_currency = getSiteCurrencySymbol('', $user['currency']);
            $DB            = $this->api_model->changeAffiliateStatus($user_id, $order_id, $sales_status);

            $order_status_after = getVal('is_confirmed', 'c_orders', array('id' => $order_id));
            $order_status_after = (int) $order_status_after;


            if ($DB)
            {
                $data['status']       = 1;
                $data['sales_status'] = (int) $order_status_after;
            }
            else
            {
                $data['status']  = 1;
                $data['message'] = 'Unable to change the status this time , Try again later .';
            }
        }
        else
        {
            $data['status']  = 0;
            $data['message'] = 'Invalid/Incomplete data provided.';
        }

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    function confirm_payment()
    {
        $data = array();
        if (isset($_POST['user_id']) && $_POST['user_id'] <> '' && isset($_POST['invoice_id']) && $_POST['invoice_id'] <> '' && isset($_POST['payment_id']) && $_POST['payment_id'] <> '' && isset($_POST['product_names']) && $_POST['product_names'] <> '')
        {
            $user_id    = $_POST['user_id'];
            $invoice_id = $_POST['invoice_id'];
            $payment_id = $_POST['payment_id'];

            $invoice_detial = getValArray('*', 'c_invoices', array('invoice_id' => $invoice_id, 'publisher_id' => $user_id));

            if (empty($invoice_detial))
            {
                $data['status']  = 1;
                $data['message'] = 'Invoice not found.';
                header('Content-Type: application/json');
                echo json_encode($data);
                exit;
            }

            $CURRENCY_CHAR = getVal('currency_name', 'c_currencies', array('currency_id' => $invoice_detial['invoice_currency']));
            $CURRENCY_CHAR = strtoupper(trim($CURRENCY_CHAR));

            $invoice_payed_count = (int) $this->db->where(array('invoice_number' => $invoice_detial['invoice_number']))->count_all('payment');
            if ($invoice_payed_count > 0)
            {
                $data['status']  = 1;
                $data['message'] = 'Invoice already payed.';
                header('Content-Type: application/json');
                echo json_encode($data);
                exit;
            }
//            dd($CURRENCY_CHAR);
            $user                       = $this->api_model->get_user_data($user_id);
            $user_currency              = getSiteCurrencySymbol('', $user['currency']);
            $payment                    = [];
            $payment['tx']              = $payment_id;
            $payment['st']              = 'Completed';
            $payment['cc']              = $CURRENCY_CHAR;
            $payment['item_name']       = $_POST['product_names'];
            $payment['item_number']     = $invoice_detial['invoice_number'];
            $payment['user_id']         = $user_id;
            $payment['mobile_response'] = serialize($_POST);
            $DB                         = $this->api_model->save_payment($payment);


            if ($DB)
            {
                $data['status']  = 1;
                $data['message'] = 'Thank you! Your payment is recieved successfully.';
            }
            else
            {
                $data['status']  = 1;
                $data['message'] = 'Payment recieved failed';
            }
        }
        else
        {
            $data['status']  = 0;
            $data['message'] = 'Invalid/Incomplete data provided.';
        }

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    function get_site_social_links()
    {
        $data = array();

        $data['social_link_facebook'] = FACEBOOK;
        $data['social_link_twitter']  = TWITTER;
        $data['social_link_linkedin'] = LINKEDIN;
        $data['social_link_google']   = GOOGLE;
        $data['status']               = 1;

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    function get_all_support_list()
    {
        $data = array();
        if (isset($_GET['keyword']) && $_GET['keyword'] <> '')
        {
            $support_list = $this->api_model->loadSupportListing(trim($_GET['keyword']));
        }
        else
        {
            $support_list = $this->api_model->loadSupportListing(trim($_GET['keyword']));
        }
        if (count($support_list) > 0)
        {
            $data['status']       = 1;
            $data['message']      = 'Success';
            $data['support_list'] = $support_list;
        }
        else
        {
            $data['status']  = 0;
            $data['message'] = 'Support not exist.';
        }

        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    function get_support_by_id()
    {
        $data = array();
        if (isset($_GET['support_id']) && $_GET['support_id'] <> '')
        {
            $data['status']  = 1;
            $data['message'] = 'Success';
            $response        = $this->api_model->loadSupportDetails(trim($_GET['support_id']));
            if (empty($response))
            {
                $data['status']  = 0;
                $data['message'] = 'Support not exist.';
                header('Content-Type: application/json');
                echo json_encode($data);
                exit;
            }
            else
            {
                $data['status']          = 1;
                $response['description'] = strip_tags($response['description']);
                $data['support']         = $response;
            }
        }
        else
        {
            $data['status']  = 0;
            $data['message'] = 'Support not exist.';
        }

        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    function getWebUrls()
    {
        $data = array();

        $resuts = $this->api_model->get_allcmspageLinks();

        if (count($resuts) > 0)
        {
            $i              = 0;
//            foreach ($resuts as $r) {
//
//                $resuts[$i]['page_url'] = base_url() . 'api/get_pages/' . $r['cmId'];
//                $i++;
//            }
            array_push($resuts, array('title' => 'Support', 'url' => base_url('support/m')));
            $data['urls']   = $resuts;
            $data['status'] = 1;
        }
        else
        {
            $data['status']  = 0;
            $data['message'] = 'Failed to load urls.';
        }


        header('Content-Type: application/json');
        echo json_encode($data);
    }

    function get_product_activate_status()
    {
        $data = array();

        $resuts = $this->api_model->get_allcmspageLinks();

        if (isset($_GET['product_id']) && $_GET['product_id'] <> '')
        {
            $_status                                = getVal('script_verified', 'c_products', 'product_id', $_GET['product_id']);
            $data['product_script_activate_status'] = (int) $_status;
            $data['status']                         = 1;
        }
        else
        {
            $data['status']  = 0;
            $data['message'] = 'Invalid Data Provided';
        }


        header('Content-Type: application/json');
        echo json_encode($data);
    }

    // NEW APIS :: MOHSIN :: END
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */