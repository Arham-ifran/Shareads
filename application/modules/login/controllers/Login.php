<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        get_social_intergration();
        $this->load->library('twitteroauth');

        $this->load->model('login_model');
        $this->load->model('register/register_model');
        $this->load->library('emailutility');

        if ($this->session->userdata('access_token') && $this->session->userdata('access_token_secret'))
        {
            // If user already logged in
            $this->connection = $this->twitteroauth->create(TWITTER_CONSUMER_TOKEN, TWITTER_CONSUMER_SECRET, $this->session->userdata('access_token'), $this->session->userdata('access_token_secret'));
        }
        elseif ($this->session->userdata('request_token') && $this->session->userdata('request_token_secret'))
        {
            // If user in process of authentication
            $this->connection = $this->twitteroauth->create(TWITTER_CONSUMER_TOKEN, TWITTER_CONSUMER_SECRET, $this->session->userdata('request_token'), $this->session->userdata('request_token_secret'));
        }
        else
        {
            // Unknown user
            $this->connection = $this->twitteroauth->create(TWITTER_CONSUMER_TOKEN, TWITTER_CONSUMER_SECRET);
        }





    }

    public function index()
    {
$user_id = $this->session->userdata('user_id');
$email = $this->session->userdata('email');
        $is_logged_in = $this->session->userdata('user_is_logged_in');
        $is_admin = $this->session->userdata('is_admin');
        if (($is_logged_in == '0' || $is_logged_in == '') && $is_admin == 0 && ($DB_STATUS == 0 || $DB_STATUS == '')) {
            
        }else{
            if ($this->session->userdata('account_type') == 1)
                    {
                        redirect(base_url('marketing')); // due to flash data.
                    }
                    else
                    {
                        $first_time_invitor = [];
                        $first_time_invitor = $this->db->from('c_users')->where(array('email' => $email, 'invited_status' => 1, 'is_invited' => 1, 'show_welcome' => 1))->get()->row_array();
                        if (!empty($first_time_invitor))
                        {
                            redirect(base_url('welcome')); // due to flash data.
                        }
                        else
                        {
                            $first_time_invitor = $this->db->from('c_users')->where(array('email' => $email, 'show_welcome' => 1))->get()->row_array();
                            if (!empty($first_time_invitor))
                            {
                                redirect(base_url('welcome')); // due to flash data.
                            }
                            redirect(base_url('dashboard')); // due to flash data.
                        }
                    }
        }

        $data = array();

        if ($this->input->post())
        {
            $email    = trim($_POST['email']);
            $password = trim($_POST['password']);
            //VALIDATE FROM DATABASE

            $response = $this->login_model->ajaxLogin($email, $password);
            if ($response)
            {
                $remember = $this->input->get_post("rememberme");
                if ($remember == 1)
                {

                    $past = time() - 5;
                    //this makes the time 5 seconds ago
                    setcookie("userEmail", NULL, $past);
                    setcookie("userEmail", base64_encode(base64_encode($email)), time() + 3600 * 24, "/", "");
                    setcookie("userpassword", NULL, $past);
                    setcookie("userpassword", base64_encode(base64_encode($password)), time() + 3600 * 24, "/", "");
                }


                if ($this->input->get_post("last_url") <> '')
                {
                    $last_url = urldecode($this->input->get_post("last_url"));
                    redirect($last_url); // due to flash data.
                }
                else
                {
                    $this->session->set_flashdata('success_message', "You've logged in successfully.");
                    if ($this->session->userdata('account_type') == 1)
                    {
                        redirect(base_url('marketing')); // due to flash data.
                    }
                    else
                    {
                        $first_time_invitor = [];
                        $first_time_invitor = $this->db->from('c_users')->where(array('email' => $email, 'invited_status' => 1, 'is_invited' => 1, 'show_welcome' => 1))->get()->row_array();
                        if (!empty($first_time_invitor))
                        {
                            redirect(base_url('welcome')); // due to flash data.
                        }
                        else
                        {
                            $first_time_invitor = $this->db->from('c_users')->where(array('email' => $email, 'show_welcome' => 1))->get()->row_array();
                            if (!empty($first_time_invitor))
                            {
                                redirect(base_url('welcome')); // due to flash data.
                            }
                            redirect(base_url('dashboard')); // due to flash data.
                        }
                    }
                    exit;
                }
            }
            else
            {
                $this->session->set_flashdata('error_message', 'The email or password you entered is incorrect. Please try again (make sure your caps lock is off).');
            }
        }



        $this->load->library('fb');

        $fb = new Facebook\Facebook([
            'app_id' => FACEBOOK_APPID,
            'app_secret' => FACEBOOK_SECRET,
            'default_graph_version' => 'v2.11',
        ]);

        $helper = $fb->getRedirectLoginHelper();

        $permissions        = ['email', 'public_profile'];
        $data['fbLoginUrl'] = $helper->getLoginUrl(base_url('login/facebook'), $permissions);


        $data['last_url']   = $this->input->get_post("last_url");
        $data ['content']   = $this->load->view('login', $data, true);
        $data['login_flag'] = 1;
        $this->load->view('includes/template_fullbody.view.php', $data);
    }

    public function iApublisher()
    {
        if (!isset($_GET) || !isset($_GET['u']) || $_GET['u'] == '')
        {
            redirect('login');
        }
        else
        {
            $user_key = $_GET['u'];
        }

        if ($user_key <> '')
        {
            updateVal('status', 2, 'c_publisher_invitations', 'user_key', $user_key);
            $usr = getValArray('*', 'c_users', 'user_key', $user_key);

            $response = $this->login_model->ajaxLogin($usr['email'], $usr['orignal_password']);
            if ($response)
            {
                if ($this->input->get_post("last_url") <> '')
                {
                    $last_url = urldecode($this->input->get_post("last_url"));
                    redirect($last_url); // due to flash data.
                }
                else
                {
                    $user_key_new = $this->common->uniqueKey(5);
                    updateVal('user_key', $user_key_new, 'c_publisher_invitations', 'user_key', $user_key);
                    updateVal('user_key', $user_key_new, 'c_users', 'user_key', $user_key);
                    $this->session->set_flashdata('success_message', "You've logged in successfully.");
                    if ($this->session->userdata('account_type') == 1)
                    {
                        redirect(base_url('marketing')); // due to flash data.
                    }
                    else
                    {
                        $first_time_invitor = [];
                        $first_time_invitor = $this->db->from('c_users')->where(array('email' => $usr['email'], 'invited_status' => 1, 'is_invited' => 1, 'show_welcome' => 1))->get()->row_array();
                        if (!empty($first_time_invitor))
                        {
                            redirect(base_url('welcome')); // due to flash data.
                        }
                        else
                        {
                            $first_time_invitor = $this->db->from('c_users')->where(array('email' => $usr['email'], 'show_welcome' => 1))->get()->row_array();
                            if (!empty($first_time_invitor))
                            {
                                redirect(base_url('welcome')); // due to flash data.
                            }
                            redirect(base_url('dashboard')); // due to flash data.
                        }
                    }
                    exit;
                }
            }
            else
            {
                $this->session->set_flashdata('error_message', 'Invalid or expired link.');
                redirect('login');
            }
        }
        else
        {
            redirect('login');
        }
    }

    /**
     * Method: forgot_password
     * params:
     * Retruns:
     */
    public function forgot_password()
    {
        $data = array();

        if ($this->input->post())
        {
            $email = $this->input->post('email');
            $res   = $this->login_model->checkEmail($email);
            $res_1 = $this->login_model->checkStatusFromEmail($email);

            if ($res_1 == 0)
            {
                $this->session->set_flashdata('error_message', 'Email  "' . $this->input->post('email') . '" is not active.');
            }
            else if ($res == 1)
            {
                $password = $this->common->randomPassword();
                $db_query = $this->login_model->updateUser($email, $password);

                /*                 * ** Send password Email Start ***** */
                if ($db_query)
                {
                    $email = $this->input->post('email');

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


                    $email_tempData = get_email_tempData(2);
//                    dd($email_tempData);

                    if (!empty($email_tempData))
                    {
                        $data['title']   = $email_tempData['title'];
                        $data['content'] = $email_tempData['content'];

                        $data['welcome_content'] = $email_tempData['welcome_content'];
                        $data['footer']          = $email_tempData['footer'];
                        $subject                 = $data['title'];

                        $email_content = $this->load->view('includes/email_templates/email_template', $data, true);

                        $this->emailutility->send_email_user($email_content, $email, $subject);
                    }
                    unset($data);
                    /*                     * ** Send varification Email End ***** */

                    $this->session->set_flashdata('success_message', 'Please Check your email for password recovery.');
                    redirect(base_url('forgot-password')); // due to flash data.
                    exit;
                }
            }
            else
            {
                $this->session->set_flashdata('error_message', 'Email  "' . $this->input->post('email') . '" not exists. Please try another email.');
            }
        }

        $data ['content']   = $this->load->view('forgot_password', $data, true);
        $data['login_flag'] = 1;
        $this->load->view('includes/template_fullbody.view.php', $data);
    }

    /**
     * Method: ajaxLoginPopUp
     * params: $_POST
     * Retruns:
     */
    public function ajaxLoginPopUp()
    {
        $data     = array();
        $email    = trim($_POST['login_email']);
        $password = trim($_POST['login_password']);
        if ($email <> '' || $password <> '')
        {
            //VALIDATE FROM DATABASE
            $response = $this->login_model->ajaxLoginPopup($email, $password);
            if ($response == true)
            {

                $remember = $this->input->get_post("login_rememberme");
                if ($remember == 1)
                {

                    $past = time() - 5;
                    //this makes the time 5 seconds ago
                    setcookie("userEmail", NULL, $past);
                    setcookie("userEmail", base64_encode(base64_encode($email)), time() + 3600 * 24, "/", "");
                    setcookie("userpassword", NULL, $past);
                    setcookie("userpassword", base64_encode(base64_encode($password)), time() + 3600 * 24, "/", "");
                }
                $data['message'] = 'You have login successfully. Please wait...';
                $data['action']  = 'success';
            }
            else
            {

                $data['message'] = 'The email or password you entered is incorrect. Please try again (make sure your caps lock is off).';
                $data['action']  = 'error';
            }
        }
        echo json_encode($data);
        exit;
    }

    /**
     * Method: logout
     * params:
     * Retruns:
     */
    public function logout()
    {
        $this->session->sess_destroy();
        $this->session->unset_userdata(
                array(
                    'user_id',
                    'first_name',
                    'last_name',
                    'full_name',
                    'email',
                    'photo',
                    'gender',
                    'user_name',
                    'account_type',
                    'user_is_logged_in',
                    'user_key',
                    'is_admin'));

        $past = time() - 3600;
        setcookie("userEmail", "", $past);
        setcookie("userpassword", "", $past);
        setcookie("userEmail", '', $past, "/", "");
        setcookie("userpassword", '', $past, "/", "");

        session_unset();
        $this->session->unset_userdata('access_token');
        $this->session->unset_userdata('access_token_secret');
        $this->session->unset_userdata('request_token');
        $this->session->unset_userdata('request_token_secret');
        $this->session->unset_userdata('twitter_user_id');
        $this->session->unset_userdata('twitter_screen_name');

        $this->session->unset_userdata('oauth_request_token');
        $this->session->unset_userdata('session_id');
        $this->session->unset_userdata('oauth_verifier');
        $this->session->unset_userdata('oauth_request_token_secret');
        $this->session->unset_userdata('oauth_access_token_secret');

        $this->session->unset_userdata('fb_user_id');
        $this->session->unset_userdata('fb_code');
        $this->session->unset_userdata('fb_token');

        session_destroy();
        $this->session->unset_userdata('fb_access_token');

        redirect(base_url(), 'refresh');
    }

    function facebook()
    {

        $this->load->library('fb');
        $fb     = new Facebook\Facebook([
            'app_id' => FACEBOOK_APPID,
            'app_secret' => FACEBOOK_SECRET,
            'default_graph_version' => 'v2.11',
        ]);
        $helper = $fb->getRedirectLoginHelper();
        try
        {
            $accessToken = $helper->getAccessToken();
        }
        catch (Facebook\Exceptions\FacebookResponseException $e)
        {
            // When Graph returns an error
            $this->engineinit->forcefullyLogedOut('Graph returned an error: ' . $e->getMessage());
            exit;
        }
        catch (Facebook\Exceptions\FacebookSDKException $e)
        {
            // When validation fails or other local issues
            $this->engineinit->forcefullyLogedOut('Facebook SDK returned an error: ' . $e->getMessage());
            exit;
        }
        if (!isset($accessToken))
        {
            if ($helper->getError())
            {
//              header('HTTP/1.0 401 Unauthorized');
                $html .= "Error: " . $helper->getError() . "\n";
                $html .= "Error Code: " . $helper->getErrorCode() . "\n";
                $html .= "Error Reason: " . $helper->getErrorReason() . "\n";
                $html .= "Error Description: " . $helper->getErrorDescription() . "\n";

                $this->engineinit->forcefullyLogedOut($html);
            }
            else
            {
//                header('HTTP/1.0 400 Bad Request');
//                echo 'Bad request';
                $this->engineinit->forcefullyLogedOut('Bad request');
            }
            exit;
        }
// Logged in
// The OAuth 2.0 client handler helps us manage access tokens
        $oAuth2Client  = $fb->getOAuth2Client();
// Get the access token metadata from /debug_token
        $tokenMetadata = $oAuth2Client->debugToken($accessToken);
// Validation (these will throw FacebookSDKException's when they fail)
        $tokenMetadata->validateAppId(FACEBOOK_APPID); // Replace {app-id} with your app id
// If you know the user ID this access token belongs to, you can validate it here
//$tokenMetadata->validateUserId('123');
//        $tokenMetadata->validateExpiration();
        if (!$accessToken->isLongLived())
        {
            // Exchanges a short-lived access token for a long-lived one
            try
            {
                $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
            }
            catch (Facebook\Exceptions\FacebookSDKException $e)
            {

                $this->engineinit->forcefullyLogedOut("<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n");
                exit;
            }
        }
        $this->session->set_userdata('fb_access_token', (string) $accessToken);
        try
        {
            // Returns a `Facebook\FacebookResponse` object
            $response = $fb->get('/me?fields=id,first_name,last_name,email,gender,name,picture{url}', $accessToken);
        }
        catch (Facebook\Exceptions\FacebookResponseException $e)
        {
            $this->engineinit->forcefullyLogedOut('Graph returned an error: ' . $e->getMessage());
            exit;
        }
        catch (Facebook\Exceptions\FacebookSDKException $e)
        {
            $this->engineinit->forcefullyLogedOut('Facebook SDK returned an error: ' . $e->getMessage());
            exit;
        }
        $user = $response->getGraphUser();

        $password = 'facebook9211pass';

        $face_data = $user;

        $fb_token   = $_GET['state'];
        $fb_code    = $_GET['code'];
        $fb_user_id = $face_data['id'];

        $this->session->set_userdata('fb_user_id', $fb_user_id);
        $this->session->set_userdata('fb_code', $fb_code);
        $this->session->set_userdata('fb_token', $fb_token);

        $data1['first_name'] = $face_data['first_name'];
        $data1['last_name']  = $face_data['last_name'];

        $user_name = strtolower(str_replace(' ', '', $face_data['name']));

        /*         * ********USER NAME*********** */
        $user_ = preg_replace('~[^\\pL\d]+~u', '-', trim($user_name));
        $user_ = trim($user_, '-');
        $user_ = iconv('utf-8', 'us-ascii//TRANSLIT', $user_);
        $user_ = strtolower($user_);
        $user_ = preg_replace('~[^-\w]+~', '', $user_);

        $usrnm = $this->login_model->checkUserName($user_);
        if ($usrnm == 1)
        {
            $rand  = rand(1, 99999);
            $user_ = $user_ . '-' . $rand;

            $usrnme = $this->login_model->checkUserName($user_);
            if ($usrnme == 1)
            {
                $rand               = rand(1, 999999);
                $data1['user_name'] = $user_ . '-' . $rand;
            }
            else
            {
                $data1['user_name'] = $user_;
            }
        }
        else
        {
            $data1['user_name'] = $user_;
        }
        /*         * ******************* */


        $data1['password']         = md5($password);
        $data1['orignal_password'] = $password;

        if ($face_data['email'] <> '')
        {
            $email = $this->login_model->checkEmail($face_data['email']);

            if ($email == 0)
            {
                $data1['email'] = $face_data['email'];
            }
            else
            {
                $query = $this->login_model->loginTwitterFBlinkedIn_exist($face_data['email'], $password);
                if ($query == true)
                {
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
                exit;
            }
        }


        $user_key = $this->common->uniqueKey(5);
        $check    = getVal('user_key', 'c_users', 'user_key', $user_key);

        if ($check <> '')
        {
            $data1['user_key'] = $this->common->uniqueKey(5);
        }
        else
        {
            $data1['user_key'] = $user_key;
        }

        $data1['full_name']       = $face_data['name'];
        $data1['connected_by_id'] = $face_data['id'];
        $data1['gender']          = $face_data['gender'];
        $data1['connected_by']    = 1;
        $data1['status']          = 1;
        $data1['created']         = time();
        $data1['account_type']    = 1;
        $file_name                = $this->common->do_upload_FBpicture($face_data);
        if ($file_name <> '')
        {
            $data1['photo'] = $file_name;
        }

        $query = $this->login_model->ajaxLoginTwitterFB($data1, $password);


        if ($query == true)
        {

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
        else
        {

            $user = $this->login_model->register_user($data1);

            if (isset($_COOKIE['affid']))
            {
                $affid      = $_COOKIE['affid'];
                $userid     = getVal('user_id', 'c_users', 'user_key', $affid);
                $user_id    = $userid <> '' ? $userid : 0;
                $product_id = $_COOKIE['pid'];

                unset($_COOKIE['affid']);
                unset($_COOKIE['pid']);
                setcookie('affid', null, -1, '/');
                setcookie('pid', null, -1, '/');
                if ($product_id <> '' && $user_id <> 0)
                {
                    $status = $this->register_model->getOrderStatus($user_id, $product_id);

                    if ($status <> 0)
                    {
                        $this->register_model->updateOrderStatus($status);
                    }
                }
            }

            if ($this->login_model->ajaxLoginTwitterFB($data1, $password))
            { // user exist and not blocked
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
            else
            {
                // error
            }
        }

        // Facebook End.
    }

    /**
     * Here comes authentication process begin.
     * @access	public
     * @return	void
     */
    public function twitter()
    {

        if ($this->session->userdata('access_token') <> '' && $this->session->userdata('access_token_secret') <> '')
        {
            // User is already authenticated. Add your user notification code here.
            redirect(base_url(''));
        }
        else
        {
            // Making a request for request_token
            $request_token = $this->connection->getRequestToken(base_url('login/callback'));

            $this->session->set_userdata('request_token', $request_token['oauth_token']);
            $this->session->set_userdata('request_token_secret', $request_token['oauth_token_secret']);
//            echo $this->connection->http_code;die();
            if ($this->connection->http_code == 200)
            {
                $url = $this->connection->getAuthorizeURL($request_token);
                redirect($url);
            }
            else
            {
                // An error occured. Make sure to put your error notification code here.
                redirect(base_url());
            }
        }
    }

    /**
     * Callback function, landing page for twitter.
     * @access	public
     * @return	void
     */
    public function callback()
    {
        if ($this->input->get('oauth_token') && $this->session->userdata('request_token') !== $this->input->get('oauth_token'))
        {
            $this->reset_session();
            redirect(base_url('login/twitter'));
        }
        else
        {
            $access_token = $this->connection->getAccessToken($this->input->get('oauth_verifier'));

            if ($this->connection->http_code == 200)
            {
                $this->session->set_userdata('access_token', $access_token['oauth_token']);
                $this->session->set_userdata('access_token_secret', $access_token['oauth_token_secret']);
                $this->session->set_userdata('twitter_user_id', $access_token['user_id']);
                $this->session->set_userdata('twitter_screen_name', $access_token['screen_name']);

                $this->session->unset_userdata('request_token');
                $this->session->unset_userdata('request_token_secret');

                $content = $this->connection->get('account/verify_credentials');

                $data_array = array();
                $user_name  = strtolower($content->screen_name);

                /*                 * ********USER NAME*********** */
                $user_ = preg_replace('~[^\\pL\d]+~u', '-', trim($user_name));
                $user_ = trim($user_, '-');
                $user_ = iconv('utf-8', 'us-ascii//TRANSLIT', $user_);
                $user_ = strtolower($user_);
                $user_ = preg_replace('~[^-\w]+~', '', $user_);

                $usrnm = $this->login_model->checkUserName($user_);
                if ($usrnm == 1)
                {
                    $rand  = rand(1, 99999);
                    $user_ = $user_ . '-' . $rand;

                    $usrnme = $this->login_model->checkUserName($user_);
                    if ($usrnme == 1)
                    {
                        $rand                    = rand(1, 999999);
                        $data_array['user_name'] = $user_ . '-' . $rand;
                    }
                    else
                    {
                        $data_array['user_name'] = $user_;
                    }
                }
                else
                {
                    $data_array['user_name'] = $user_;
                }
                /*                 * ******************* */


//                if($content->email <> '')
//                {
//                $email = $this->login_model->checkEmail($content->email);
//                 if($email == 0)
//                 {
//                      $data_array['email'] = $content->email;
//                 }else{
//                     $query = $this->login_model->loginTwitterFBlinkedIn_exist($content->email, $password);
//                        if ($query == true) {
//                            redirect(base_url('dashboard'));
//                            exit;
//                        }
//                     exit;
//                 }
//                }

                $user_key = $this->common->uniqueKey(5);
                $check    = getVal('user_key', 'c_users', 'user_key', $user_key);

                if ($check <> '')
                {
                    $data_array['user_key'] = $this->common->uniqueKey(5);
                }
                else
                {
                    $data_array['user_key'] = $user_key;
                }

                $data_array['connected_by_id'] = $content->id;
                $full_name                     = explode(" ", $content->name);

                $full_name[0] == '' ? '' : $full_name[0];

                if (isset($full_name[1]))
                {
                    $full_name[1] == '' ? '' : $full_name[1];
                }
                else
                {
                    $full_name[1] = '';
                }

                $data_array['first_name'] = $full_name[0];
                $data_array['last_name']  = $full_name[1];

                $data_array['full_name'] = $content->name;
                $data_array['about_me']  = $content->description;

                if ($content->profile_image_url <> '')
                {
                    $file_name = $this->common->do_upload_TWpicture($content->profile_image_url, $content->id);
                    if ($file_name <> '')
                    {
                        $data_array['photo'] = $file_name;
                    }
                }

                $data_array['password']         = md5('twitter9211pass');
                $data_array['orignal_password'] = 'twitter9211pass';
                $data_array['gender']           = 'male';

                $data_array['email']        = '';
                $data_array['connected_by'] = 2;
                $data_array['status']       = 1;
                $data_array['account_type'] = 1;
                $data_array['address']      = $content->location;
                $data_array['created']      = time();
                $password                   = 'twitter9211pass';
                $query                      = $this->login_model->ajaxLoginTwitterFB($data_array, $password);

                if ($query == true)
                {
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
                else
                {
                    $user = $this->login_model->register_user($data_array);

                    if (isset($_COOKIE['affid']))
                    {
                        $affid      = $_COOKIE['affid'];
                        $userid     = getVal('user_id', 'c_users', 'user_key', $affid);
                        $user_id    = $userid <> '' ? $userid : 0;
                        $product_id = $_COOKIE['pid'];

                        unset($_COOKIE['affid']);
                        unset($_COOKIE['pid']);
                        setcookie('affid', null, -1, '/');
                        setcookie('pid', null, -1, '/');
                        if ($product_id <> '' && $user_id <> 0)
                        {
                            $status = $this->register_model->getOrderStatus($user_id, $product_id);

                            if ($status <> 0)
                            {
                                $this->register_model->updateOrderStatus($status);
                            }
                        }
                    }

                    if ($this->login_model->ajaxLoginTwitterFB($data_array, $password))
                    { // user exist and not blocked
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
                    else
                    {
                        // error
                    }
                }
                redirect(base_url());
                exit;
            }
            else
            {
                // An error occured. Add your notification code here.
                redirect(base_url());
                exit;
            }
        }
    }

    /**
     * Reset session data
     * @access	private
     * @return	void
     */
    private function reset_session()
    {
        $this->session->unset_userdata('access_token');
        $this->session->unset_userdata('access_token_secret');
        $this->session->unset_userdata('request_token');
        $this->session->unset_userdata('request_token_secret');
        $this->session->unset_userdata('twitter_user_id');
        $this->session->unset_userdata('twitter_screen_name');
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */