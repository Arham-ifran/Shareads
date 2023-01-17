<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tracking extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

//        if (!$this->input->is_ajax_request()) {
//            exit('No direct script access allowed');
//         }

        $this->load->model('tracking_model');
        $this->load->library('emailutility');
    }

    public function index()
    {
//        dd();
        if (isset($_GET))
        {
            $pro_id     = $_GET['prd'];
            $product_id = $this->common->decode($pro_id);
            $affid      = $_GET['affid'];
            if ($affid <> '')
            {
                $userid  = getVal('user_id', 'c_users', 'user_key', $affid);
                $user_id = $userid <> '' ? $userid : 0;
            }
            else
            {
                if (isset($_COOKIE['affid']))
                {
                    $affid   = $_COOKIE['affid'];
                    $userid  = getVal('user_id', 'c_users', 'user_key', $affid);
                    $user_id = $userid <> '' ? $userid : 0;
                }
                else
                {
                    $user_id = 0;
                }
            }

            if ($product_id <> '')
            {
                $status = $this->tracking_model->getOrderStatus($user_id, $product_id);

                if ($status <> 0)
                {
                    $this->tracking_model->updateOrderStatus($status);
                }
            }
        }

        header('Content-Type: image/png');
        echo base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAAApJREFUCNdjYAAAAAIAAeIhvDMAAAAASUVORK5CYII=');
    }

    public function order_input()
    {
        if ($_GET)
        {
            $prod  = json_decode(urldecode(urldecode($_GET['pro'])));
            $affid = $prod->affid;
            if ($affid == '' || $affid == 'undefined')
            {
                echo 'undefined';
                exit;
            }
            if ($affid <> '')
            {
                $userid  = getVal('user_id', 'c_users', 'user_key', $affid);
                $user_id = $userid <> '' ? $userid : 0;
            }
            else
            {
                if (isset($_COOKIE['affid']))
                {
                    $affid   = $_COOKIE['affid'];
                    $userid  = getVal('user_id', 'c_users', 'user_key', $affid);
                    $user_id = $userid <> '' ? $userid : 0;
                }
                else
                {
                    $user_id = 0;
                }
            }

            $data['advertiser_id']  = $user_id;
            $data['order_id']       = $prod->order_id;
            $data['transaction_id'] = $prod->transaction_id;
            $data['sale_ip']        = $prod->sale_ip;
            $success_page_url       = $prod->sale_success_url;
            $url                    = urldecode($prod->url);

            $parsed = parse_url($url);
            if ($parsed['scheme'] == '')
            {
                $url = 'http://' . $url;
            }
            $parse             = parse_url($url);
            $user_details      = getValArray('user_id,email,currency,first_name', 'c_users', array('user_id' => $user_id));
            $product_details   = getValArray('commission,currency,user_id,product_name', 'c_products', array('product_id' => $product_id));
            $publisher_id      = $product_details['user_id'];
            $publisher_details = getValArray('email,currency,first_name', 'c_users', array('user_id' => $publisher_id));

            $product_id = $this->tracking_model->getProductIdFromUrl($parse['host']);

            $this->db->where('product_id', $product_id)->update('c_products', array('sale_url' => $success_page_url, 'script_verified' => 1));



            $user_details      = getValArray('user_id,email,currency,first_name', 'c_users', array('user_id' => $user_id));
            $product_details   = getValArray('commission,currency,user_id,product_name', 'c_products', array('product_id' => $product_id));
            $publisher_id      = $product_details['user_id'];
            $publisher_details = getValArray('email,currency,first_name', 'c_users', array('user_id' => $publisher_id));




            $order_id   = $this->tracking_model->getOrderId($product_id);
            $data['id'] = $order_id;



            if (($publisher_id <> $user_id) && ($product_id <> '' && $product_id <> 'undefined') && ($order_id <> '' && $order_id <> 'undefined') && ($userid <> '' && $userid <> 'undefined'))
            {

                if ($product_details['user_id'] == $user_details['user_id'] || $order_id == 'undefined')
                {
                    die();
                }

                $this->db->where(array('product_id' => $product_id))->update('c_products', array('sale_url' => $success_page_url, 'script_verified' => 1));
                $response = $this->tracking_model->updateOrder($data);

                ///////////////////////////
                $pro_currency   = $product_details['currency'];
                $pro_commission = $product_details['commission'];
                $usr_currency   = $user_details['currency'];


                $email_tempData            = get_email_tempData(12);
                $email_tempData['content'] = str_replace("[ADVERTISER_NAME]", ucfirst($user_details['first_name']), $email_tempData['content']);
                $email_tempData['content'] = str_replace("[PRODUCT_COMMISSION]", getSiteCurrencySymbol('', $usr_currency) . number_format(get_currency_rate($pro_commission, $pro_currency, $usr_currency), 2), $email_tempData['content']);
                $email_tempData['content'] = str_replace("[LOGIN_LINK]", "<a class='blue_btn' style='color:white;' target='blank' href='" . base_url('login') . "'>Click now to share more</a>", $email_tempData['content']);
                if (!empty($email_tempData))
                {
                    $data['receiver_name']   = 'no_hi';
                    $data['title']           = $email_tempData['title'];
                    $data['email_content']   = $email_tempData['content'];
                    $data['welcome_content'] = $email_tempData['welcome_content'];
                    $data['footer']          = $email_tempData['footer'];
                    $subject                 = $data['title'];
                    $email_content           = $this->load->view('includes/email_templates/email_template', $data, true);
                    $this->emailutility->accountVarification($email_content, $user_details['email'], $subject);
                }
                /// TO PUBLISHER
                $email_tempData_2            = get_email_tempData(13);
                $email_tempData_2['content'] = str_replace("[PUBLSHER_NAME]", ucfirst($publisher_details['first_name']), $email_tempData_2['content']);
                $email_tempData_2['content'] = str_replace("[PRODUCT_NAME]", '<strong>' . ucfirst($product_details['product_name']) . '</strong>', $email_tempData_2['content']);
                $email_tempData_2['content'] = str_replace("[LOGIN_LINK]", "<a class='blue_btn' style='color:white;' target='blank' href='" . base_url('login') . "'>Login</a>", $email_tempData_2['content']);
                if (!empty($email_tempData_2))
                {
                    $data_2['receiver_name']   = 'no_hi';
                    $data_2['title']           = $email_tempData_2['title'];
                    $data_2['email_content']   = $email_tempData_2['content'];
                    $data_2['welcome_content'] = $email_tempData_2['welcome_content'];
                    $data_2['footer']          = $email_tempData_2['footer'];
                    $subject                   = $data_2['title'];
                    $email_content             = $this->load->view('includes/email_templates/email_template', $data_2, true);
                    $this->emailutility->accountVarification($email_content, $publisher_details['email'], $subject);
                    // $this->emailutility->accountVarification($email_content.'empty ord :'.$order_id.' --> user_id -> ('.$userid.'-'.$affid.') product -> '.$product_id.' commission -> '.$pro_commission,'mohsinlaeeque786@gmail.com', $subject);
                }
                //////////////
            }
            else
            {
                $this->db->where(array('product_id' => $product_id))->update('c_products', array('sale_url' => $success_page_url, 'script_verified' => 1));
            }
            //////////////////////////

            echo json_encode($response);
        }
    }

    // public function order_input()
    // {
    //     if ($_POST)
    //     {
    //         $affid = $_POST['affid'];
    //         if ($affid <> '')
    //         {
    //             $userid  = getVal('user_id', 'c_users', 'user_key', $affid);
    //             $user_id = $userid <> '' ? $userid : 0;
    //         }
    //         else
    //         {
    //             if (isset($_COOKIE['affid']))
    //             {
    //                 $affid   = $_COOKIE['affid'];
    //                 $userid  = getVal('user_id', 'c_users', 'user_key', $affid);
    //                 $user_id = $userid <> '' ? $userid : 0;
    //             }
    //             else
    //             {
    //                 $user_id = 0;
    //             }
    //         }
    //         $data['advertiser_id']  = $user_id;
    //         $data['order_id']       = $_POST['order_id'];
    //         $data['transaction_id'] = $_POST['transaction_id'];
    //         $url                    = urldecode($_POST['url']);
    //         $parsed = parse_url($url);
    //         if ($parsed['scheme'] == '')
    //         {
    //             $url = 'http://' . $url;
    //         }
    //         $parse = parse_url($url);
    //         $product_id = $this->tracking_model->getProductIdFromUrl($parse['host']);
    //         $order_id   = $this->tracking_model->getOrderId($product_id);
    //         $data['id'] = $order_id;
    //         if(($product_id <> '' && $product_id <> 'undefined') && ($order_id <> '' && $order_id <> 'undefined')  && ($userid <> '' && $userid <> 'undefined')){
    //         $response   = $this->tracking_model->updateOrder($data);
    //         ///////////////////////////
    //         $user_details              = getValArray('email,currency', 'c_users', array('user_id' => $user_id));
    //         $product_details           = getValArray('commission,currency', 'c_products', array('product_id' => $product_id));
    //         $pro_currency              = $product_details['currency'];
    //         $pro_commission            = $product_details['commission'];
    //         $usr_currency              = $user_details['currency'];
    //         $email_tempData            = get_email_tempData(12);
    //         $email_tempData['content'] = str_replace("[PRODUCT_COMMISSION]", getSiteCurrencySymbol('', $usr_currency) . number_format(get_currency_rate($pro_commission, $pro_currency, $usr_currency), 2), $email_tempData['content']);
    //         if (!empty($email_tempData))
    //         {
    //             $data['title']           = $email_tempData['title'];
    //             $data['email_content']   = $email_tempData['content'];
    //             $data['welcome_content'] = $email_tempData['welcome_content'];
    //             $data['footer']          = $email_tempData['footer'];
    //             $subject                 = $data['title'];
    //             $email_content           = $this->load->view('includes/email_templates/email_template', $data, true);
    //             $this->emailutility->accountVarification($email_content, $user_details['email'], $subject);
    //             $this->emailutility->accountVarification($email_content.'user_id -> '.$userid.' product -> '.$product_id.' commission -> '.$pro_commission,'mohsinlaeeque786@gmail.com', $subject);
    //         }
    //         }else{
    //             $this->emailutility->accountVarification('empty ','mohsinlaeeque786@gmail.com','oko');
    //         }
    //         //////////////////////////
    //         echo json_encode($response);
    //     }
    // }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */