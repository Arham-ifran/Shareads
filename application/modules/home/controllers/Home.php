<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('home_model');
    }

    public function index()
    {

        $data['user_id'] = $this->session->userdata('user_id');

        $data ['content']  = $this->load->view('home', $data, true);
        $data['home_flag'] = 1;
        $this->load->view('includes/template_fullbody.view.php', $data);
    }

    function test_email()
    {
        $this->load->library('emailutility');
        $date      = date('Y-m-d');
        $full_name = 'admin';

        $subject             = 'New Order was created by ' . $full_name . ' at ' . $date;
        $admin_email_content = $subject;

        $data[0] = array('tmp_name' => 'cron_invoices/30-Jul-2018/INV-1532943251.pdf', 'file_name' => 'INV-1532943251.pdf');

        $this->emailutility->sendMail('buzzfli.project@gmail.com', SITE_NAME, ADMIN_EMAIL, $subject, $admin_email_content, '', '', $data);
        $date                  = date('Y-m-d');
        $full_name             = 'Mohsin';
        $subject               = 'New Order was created by ' . $full_name . ' at ' . $date;
        $admin_email_content   = $subject;
        $data['receiver_name'] = $full_name;
        $data['email_content'] = "<p>Hi " . $full_name . ",<br>";
        $data['email_content'] .= "You Invoice is generated for " . date('F Y') . ". Please pay the invoice before due date";


        $email_tempData = get_email_tempData(1);
        if (!empty($email_tempData))
        {
            $data['title']           = ' Invoice of month ' . date('F');
            $data['content']         = '';
            $data['welcome_content'] = $email_tempData['welcome_content'];
            $data['footer']          = $email_tempData['footer'];
            $subject                 = SITE_NAME . ' Invoice of month ' . date('F Y');
            $email_content           = $this->load->view('includes/email_templates/email_template', $data, true);
            $data_attachment[0]      = array('tmp_name' => 'shareads_invoices/30-Jul-2018/INV-1532943251.pdf', 'file_name' => 'INV-1532943251.pdf');
            $this->emailutility->sendMail('buzzfli.project@gmail.com', SITE_NAME, ADMIN_EMAIL, $subject, $email_content, '', '', $data_attachment);
        }
        echo '55';
    }

    public function dates()
    {
        $date = new DateTime('now');
        echo floor($date->modify('last day of this month')->format('d') / 2);
        die();
        dd(getBiweeklyRemainingDaysByMonth(date('m')));
    }

    public function decode()
    {
        echo $_GET['decode'] . '<br>';
        echo $this->common->decode($_GET['decode']);
    }

    public function encode()
    {
        echo $_GET['encode'] . '<br>';
        echo $this->common->encode($_GET['encode']);
    }

    public function md5_ip()
    {
        echo get_unique_device();
    }

    public function test_et($id)
    {
        $data['receiver_name'] = 'Recievers Name';
        $data['email_content'] = "Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ";

        $email_tempData            = get_email_tempData($id);
        $email_tempData['content'] = str_replace("[PRODUCT_NAME]", ucfirst('USB Fan'), $email_tempData['content']);
        $email_tempData['content'] = str_replace("[PRODUCT_COMMISSION]", "$20.00", $email_tempData['content']);
        $email_tempData['content'] = str_replace("[CLICK_NOW_TO_SHARE]", "<a class='blue_btn' target='blank' href='" . base_url('login') . "'>Click now to share</a>", $email_tempData['content']);
        if (!empty($email_tempData))
        {
            $data['title']           = $email_tempData['title'];
            $data['email_content']   = $email_tempData['content'];
            $data['welcome_content'] = $email_tempData['welcome_content'];
            $data['footer']          = $email_tempData['footer'];
            $subject                 = $data['title'];
            $this->load->view('includes/email_templates/email_template', $data);
            //$this->emailutility->accountVarification($email_content, 'mohsin.laeeque@arhamsoft.com', 'Subject');
        }
    }

    public function test_ettt()
    {
        echo generateRandomString();
    }

    function is_script_exists()
    {
        $is_script_exit = 0;

        $html  = file_get_contents('https://shareads.arhamsoft.com/demo_sale/success?order_id=dasd&transaction_id=asdsa');
        $dom   = new DOMDocument;
        @$dom->loadHTML($html);
        $links = $dom->getElementsByTagName('script');
        foreach ($links as $link)
        {
            if (strpos($link->getAttribute('src'), 'shareads_catcher.min.js') !== false)
            {
                echo 'yes';
                $is_script_exit = 1;
            }
        }
        return (bool) $is_script_exit;
    }

    public function testmail()
    {
//        $to_email = 'arslanakbar244@yahoo.com';
//        $sendor_name = 'Shareads';
//        $sendor_email = ADMIN_EMAIL;
//        $subject = 'Test';
//        $body_html = 'Body HTML';
//        
//        define("DOMAIN", 'shareads.co.uk');
//        define("MAILGUN_API", 'key-21c064eb161d94011e18ba0a0447d1f0');
//     $ch = curl_init();
////        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data',));
//
//    
////        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
//        curl_setopt($ch, CURLOPT_USERPWD, 'api:' . MAILGUN_API);
////        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_POST, 1);
//        $plain = strip_tags(nl2br($body_html));
//
//        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
//        curl_setopt($ch, CURLOPT_URL, 'https://api.mailgun.net/v3/' . DOMAIN . '/messages');
//
//        $data = array(
//            'to' => $to_email,
//            'from' => $sendor_name . '<' . $sendor_email . '>',
//            'subject' => $subject,
//            'html' => $body_html
//                //'text' => $plain
//        );
//    
//       
//   
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//
//        $j = json_decode(curl_exec($ch));
//
//        $info = curl_getinfo($ch);
//       
//        echo '<pre>';
//        print_r($j);
//
//
////         error ("Fel 313: Vänligen meddela detta via E-post till support@".DOMAIN);
//
//        curl_close($ch);
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        
        $this->load->library('emailutility');
//        ini_set('display_errors', 1);
//        ini_set('display_startup_errors', 1);
//        error_reporting(E_ALL);
        if (isset($_GET))
        {
            $emails = explode(',', $_GET['emails']);
            if (!empty($emails))
            {
                $email_tempData = get_email_tempData(10);
                foreach ($emails as $email)
                {
                    if (!empty($email_tempData))
                    {
                        $data['title']           = 'Test Mails';
                        $data['content']         = $email_tempData['content'];
                        $data['email_content']         = 'Test content ';
                        $data['welcome_content'] = $email_tempData['welcome_content'];
                        $data['footer']          = $email_tempData['footer'];
                        $subject                 = $data['title'];
                        $email_content           = $this->load->view('includes/email_templates/email_template', $data, true);
                        $this->emailutility->accountVarification($email_content, $email, $subject);
                    }
                }
                echo '<fieldset>
    <legend>Status:</legend>Email Send successfully
  </fieldset>';
                die();
            }
            else
            {
                echo '<fieldset>
    <legend>Status:</legend>Email not provided
  </fieldset>';
                die();
            }
        }
        else
        {
            echo '<fieldset>
    <legend>Status:</legend>Email not provided
  </fieldset>';
            die();
        }
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */