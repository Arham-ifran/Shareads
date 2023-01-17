<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Checkout extends CI_Controller {

    public function __construct() {
        parent::__construct();
        get_payment_intergration();
        $this->load->model('checkout_model');
        $this->load->library('emailutility');
        $this->load->helper('string');
    }

    public function index() {

        $pro_data = array();
        if (isset($_GET)) {
            
            
            
            $pro_data = array();

            $post = unserialize(base64_decode($_GET['post']));

            if (is_array($post) && !empty($post)) {

                $pro_data['price'] = $post['price'];
                $pro_data['url'] = $post['url'];
                $pro_data['product_id'] = $product_id = getProductIdFromUrl($post['url']);
                $pro_data['product_name'] = getVal('product_name', 'c_products', 'product_id', $product_id);
                $pro_data['affid'] = $post['affid'];

                if ($product_id <> '') {
                    $this->session->set_userdata('product_data', $pro_data);
                    $data['product_data'] = $pro_data;
                    $data['error'] = '';
                    $data['user_id'] = $this->session->userdata('user_id');

                    $data ['content'] = $this->load->view('checkout', $data, true);
                    $this->load->view('includes/template_dashboard.view.php', $data);
                } else {
                    $data['error'] = 1;
                    $this->session->set_flashdata('error_message', 'Product not exists. Please try again later.');
                    $data ['content'] = $this->load->view('checkout', $data, true);
                    $this->load->view('includes/template_dashboard.view.php', $data);
                }
            } else {
                $data['error'] = 1;
                $this->session->set_flashdata('error_message', 'Posted data not existed. Please try again later.');
                $data ['content'] = $this->load->view('checkout', $data, true);
                $this->load->view('includes/template_dashboard.view.php', $data);
            }
        }
//
    }

    function paynow() {
        $data = array();
        $order_no = array();

        if ($this->input->post()) {
            $product_data = $this->session->userdata('product_data');

            $order_data = array();
            $order_data['product_id'] = $product_id = $product_data['product_id'];
            $order_data['price'] = $product_data['price'];
            $order_data['url'] = $product_data['url'];
            $order_data['seller_id'] = $seller_id = getVal('user_id', 'c_products', 'product_id', $product_data['product_id']);
            $advertiser_id = getVal('user_id', 'c_users', 'user_key', $product_data['affid']);
            $order_data['advertiser_id'] = $advertiser_id <> '' ? $advertiser_id : 0;
            $order_data['created'] = time();
            $order_data['order_status'] = 1; // pending
            $db_query = $this->checkout_model->save_order($order_data);
            $order_id = $this->db->insert_id();

            $this->session->set_userdata('order_id', $order_id);

            //////////////COMMISSION//////////////
            $array = array();
            $array['total_commission'] = $total_commission = getVal('commission', 'c_products_commission', 'product_id', $product_id);
            $array['advertiser_commission'] = $advertiser_commission = getVal('commission', 'c_products', 'product_id', $product_id);
            $array['user_id'] = $advertiser_id <> '' ? $advertiser_id : 0;
            $array['product_id'] = $product_data['product_id'];
            $array['order_id'] = $order_id;
            $array['created'] = time();
            $this->checkout_model->save_commission($array);
            //////////////COMMISSION//////////////


            $product_detail = getValArray('user_id,product_name,short_description,url', 'c_products', 'product_id', $order_data['product_id']);

            $seller_data = get_user_data('user_id,email,full_name,user_name,gender', 'user_id', $product_detail['user_id']);


            ///////Send Email to Order creater///////////////

            /*             * ** Send Order Email Admin ***** */
            $data['receiver_name'] = 'Admin';
            $data['email_content'] = 'You have received a new Order. Please see the details below.<br /><br />
					<strong>Order NO :</strong>&nbsp; ' . $order_id . '<br /><br />

                                        <strong>Total Amount :</strong>&nbsp; <?php echo getSiteCurrencySymbol(); ?>' . $product_data['price'] . '<br /><br />
					<strong>Order Status :</strong>&nbsp; Pending<br /><br />
                                        <strong>Total Commission :</strong>&nbsp; <?php echo getSiteCurrencySymbol(); ?>' . $total_commission . '<br /><br />
                                        <strong>Advertiser Commission :</strong>&nbsp; <?php echo getSiteCurrencySymbol(); ?>' . $advertiser_commission . '<br /><br />
					<strong>Product Link :</strong>&nbsp;<a href="' . $product_data['url'] . '">' . $product_detail['product_name'] . '</a>';
            $email_tempData = get_email_tempData(8);
            if (!empty($email_tempData)) {
                $data['title'] = $email_tempData['title'];
                $data['content'] = $email_tempData['content'];

                $data['welcome_content'] = $email_tempData['welcome_content'];
                $data['footer'] = $email_tempData['footer'];
            } else {
                $data['title'] = 'New Order placed on ' . SITE_NAME;
                $data['welcome_content'] = '';
                $data['content'] = $this->load->view('includes/email_templates/email_content.php', $data, true);
                $data['footer'] = $this->load->view('includes/email_templates/email_footer.php', $data, true);
            }
            $subject_admin = $data['title'];
            $email_content_admin = $this->load->view('includes/email_templates/email_template', $data, true);
            $this->emailutility->send_email_admin($email_content_admin, $subject_admin);
            unset($data);
            /*             * ** Send Order Created Admin Email End ***** */
            /*             * ** Send Order Created Seller Email Start ***** */
            $data['receiver_name'] = $seller_data['full_name'];
            $data['email_content'] = 'You have a new Order. Please see the details below.<br /><br />
					<strong>Order NO :</strong>&nbsp; ' . $order_id . '<br /><br />
                                        <strong>Total Amount :</strong>&nbsp; <?php echo getSiteCurrencySymbol(); ?>' . $product_data['price'] . '<br /><br />
                                        <strong>Commission deducted:</strong>&nbsp; <?php echo getSiteCurrencySymbol(); ?>' . $total_commission . '<br /><br />
					<strong>Order Status :</strong>&nbsp; Pending<br /><br />
					<strong>Product Link :</strong>&nbsp;<a href="' . $product_data['url'] . '">' . $product_detail['product_name'] . '</a><br /><br />';



            $data['title'] = 'Your have a new order on ' . SITE_NAME;
            if (!empty($email_tempData)) {

                $data['content'] = $email_tempData['content'];
                $data['welcome_content'] = $email_tempData['welcome_content'];
                $data['footer'] = $email_tempData['footer'];
            } else {
                $data['welcome_content'] = '';
                $data['content'] = $this->load->view('includes/email_templates/email_content.php', $data, true);
                $data['footer'] = $this->load->view('includes/email_templates/email_footer.php', $data, true);
            }
            $subject = $data['title'];
            $email_content = $this->load->view('includes/email_templates/email_template', $data, true);

            $this->emailutility->send_email_user($email_content, $seller_data['email'], $subject);
            unset($data);
            /*             * ** Send Order Created Seller Email End ***** */
            $this->session->unset_userdata('product_data');

            redirect('checkout/payment_now');
            exit;
        }


        echo json_encode($data);
        exit;
    }

    function payment_now() {

        $data = array();
        $order_id = $this->session->userdata('order_id');
        if ($order_id <> '') {
            $order_data = $this->checkout_model->getOrderData($order_id);


            $config['business'] = PAYPAL_ID;
            $config['cpp_header_image'] = ''; //Image header url [750 pixels wide by 90 pixels high]
            $config['return'] = base_url('checkout/success');
            $config['cancel_return'] = base_url('checkout/cancel');
            $config['notify_url'] = base_url('checkout/notify'); //IPN Post
            $config['production'] = INTEGRATION_TYPE; //Its false by default and will use sandbox
            $config["invoice"] = random_string('numeric', 8); //The invoice id
            $config["currency_code"] = 'GBP';

            $config["custom"] = base64_encode(serialize(array('product_id' => $order_data['product_id'], 'order_id' => $order_id))); //The custom array

            $this->load->library('paypal', $config);

            $this->paypal->add($order_data['product_name'], $order_data['price'], 1);

            $this->paypal->pay();
        } else {
            redirect(base_url());
        }
    }

    function success() {

        $data = array();

        $this->session->unset_userdata('order_id');

        $data ['content'] = $this->load->view('success', $data, true);
        $this->load->view('includes/template_dashboard.view.php', $data);
    }

    function notify() {

//        if ($_POST) {
        if ($_POST["payment_status"] == 'Completed') {
            $data_array = unserialize(base64_decode($_POST ['custom']));

            $unique_session_id = $data_array['order_id'];

            if ($unique_session_id <> '') {

                $this->checkout_model->chengeOrderStatus($unique_session_id, $_POST['txn_id']);


                $order_data = $this->checkout_model->getOrderData($unique_session_id);

                $advertiser_id = $data_array['advertiser_id'];
                $user_data1 = get_user_data('full_name,email', 'user_id', $advertiser_id);

                foreach ($order_data as $ordr) {
                    $user_data2 = get_user_data('full_name,email', 'user_id', $ordr['seller_id']);


                    ///////Send Email to Order creater///////////////
                    /*                     * ** Send Order Email Start ***** */
                    $email_tempData = get_email_tempData(4);
                    if ($advertiser_id <> 0) {
                        $data['receiver_name'] = $user_data1['full_name'];
                        $data['email_content'] = 'Your Order has successfully completed. Please see the details below.<br /><br />
					<strong>Order NO :</strong>&nbsp; ' . $ordr['id'] . '<br /><br />
                                        <strong>Total Amount :</strong>&nbsp; <?php echo getSiteCurrencySymbol(); ?>' . $ordr['price'] . '<br /><br />
					<strong>Order Status :</strong>&nbsp; Paid<br /><br />
					<strong>Product :</strong>&nbsp;' . $ordr['product_name'] . '';

                        if (!empty($email_tempData)) {
                            $data['title'] = $email_tempData['title'];
                            $data['content'] = $email_tempData['content'];

                            $data['welcome_content'] = $email_tempData['welcome_content'];
                            $data['footer'] = $email_tempData['footer'];
                        } else {
                            $data['title'] = 'Your Order successfully completed at ' . SITE_NAME;
                            $data['welcome_content'] = '';
                            $data['content'] = $this->load->view('includes/email_templates/email_content.php', $data, true);
                            $data['footer'] = $this->load->view('includes/email_templates/email_footer.php', $data, true);
                        }
                        $subject = $data['title'];
                        $email_content = $this->load->view('includes/email_templates/email_template', $data, true);
                        $this->emailutility->send_email_user($email_content, $user_data1['email'], $subject);
                        unset($data);
                    }
                    /*                     * ** Send Order success Email End ***** */

                    /*                     * ** Send Order Email Admin ***** */
                    $data['receiver_name'] = $user_data2['full_name'];
                    $data['email_content'] = 'You have reveived a new Order. Please see the details below.<br /><br />
					<strong>Order NO :</strong>&nbsp; ' . $ordr['id'] . '<br /><br />

                                        <strong>Total Amount :</strong>&nbsp; <?php echo getSiteCurrencySymbol(); ?>' . $ordr['price'] . '<br /><br />
					<strong>Order Status :</strong>&nbsp; Paid<br /><br />
                                        <strong>Advertiser :</strong>&nbsp; ' . $user_data1['full_name'] . '<br /><br />
					<strong>Product :</strong>&nbsp;' . $ordr['product_name'] . '';

                    if (!empty($email_tempData)) {
                        $data['title'] = $email_tempData['title'];
                        $data['content'] = $email_tempData['content'];

                        $data['welcome_content'] = $email_tempData['welcome_content'];
                        $data['footer'] = $email_tempData['footer'];
                    } else {
                        $data['title'] = 'Order payment paid successfully on ' . SITE_NAME;
                        $data['welcome_content'] = '';
                        $data['content'] = $this->load->view('includes/email_templates/email_content.php', $data, true);
                        $data['footer'] = $this->load->view('includes/email_templates/email_footer.php', $data, true);
                    }
                    $subject_admin = $data['title'];
                    $email_content_seller = $this->load->view('includes/email_templates/email_template', $data, true);
                    $this->emailutility->send_email_user($email_content_seller, $user_data2['email'], $subject_admin);
                    unset($data);
                    /*                     * ** Send Order Success Seller Email End ***** */
                }
            }
        }
    }

    function cancel() {
        $data = array();

        $data ['content'] = $this->load->view('cancel', $data, true);
        $this->load->view('includes/template_dashboard.view.php', $data);
    }

    function lead_generation() {
        $pro_data = array();
        if (isset($_GET)) {
            $pro_data = array();

            $pro_id = $_GET['prd'];
            $product_id = $this->common->decode($pro_id);
            $affid = $_GET['affid'];
            if ($affid <> '') {
                $userid = getVal('user_id', 'c_users', 'user_key', $affid);
                $user_id = $userid <> '' ? $userid : 0;
            } else {
                if (isset($_COOKIE['affid'])) {
                    $affid = $_COOKIE['affid'];
                    $userid = getVal('user_id', 'c_users', 'user_key', $affid);
                    $user_id = $userid <> '' ? $userid : 0;
                } else {
                    $user_id = 0;
                }
            }

            $product_detail = getValArray('commission,user_id,product_name,short_description,url', 'c_products', 'product_id', $product_id);
            $pro_data['seller_id'] = $seller_id = $product_detail['user_id'];
            $pro_data['commission'] = $advertiser_commission = $product_detail['commission'];
            $pro_data['created'] = time();
            $pro_data['product_id'] = $product_id;
            $pro_data['url'] = $product_detail['url'];
            $pro_data['advertiser_id'] = $user_id;

            $db_query = $this->checkout_model->saveLeadGeneration($pro_data);

            $seller_data = get_user_data('user_id,email,full_name,user_name,gender', 'user_id', $product_detail['user_id']);


            ///////Send Email to Order creater///////////////

            /*             * ** Send Order Email Admin ***** */
            $data['receiver_name'] = $seller_data['full_name'];
            $data['email_content'] = 'You have received a new Lead Generation submittion. Please see the details below.<br /><br />
					<strong>Product NO :</strong>&nbsp; ' . $product_id . '<br /><br />
                			<strong>Status :</strong>&nbsp; Pending<br /><br />
                                        <strong>Advertiser Commission :</strong>&nbsp; <?php echo getSiteCurrencySymbol(); ?>' . $advertiser_commission . '<br /><br />
					<strong>Product Link :</strong>&nbsp;<a href="' . $product_detail['url'] . '">' . $product_detail['product_name'] . '</a>';
            $email_tempData = get_email_tempData(8);
            $data['title'] = 'New lead generation submittion on ' . SITE_NAME;
            if (!empty($email_tempData)) {

                $data['content'] = $email_tempData['content'];
                $data['welcome_content'] = $email_tempData['welcome_content'];
                $data['footer'] = $email_tempData['footer'];
            } else {
                $data['welcome_content'] = '';
                $data['content'] = $this->load->view('includes/email_templates/email_content.php', $data, true);
                $data['footer'] = $this->load->view('includes/email_templates/email_footer.php', $data, true);
            }
            $subject = $data['title'];
            $email_content = $this->load->view('includes/email_templates/email_template', $data, true);

            $this->emailutility->send_email_user($email_content, $seller_data['email'], $subject);
            unset($data);
        }
//        echo 'Now Check your Orders in admin panel.';
    }

    function detail() {

        $this->load->library('user_agent');
        $this->load->library('session');

        $product_id = $this->common->decode($_GET['prd']);
        if ($product_id) {

            $affid = $_GET['affid'];
            $data['row'] = $row = $this->checkout_model->getRow($product_id);
            
           
            if ($data['row']) {

                $obj = '';
                if(isset($_GET['type']) && $_GET['type'] <> '')
                {
                  $obj = $_GET['type'];  
                }

                if ($affid) {
                    $past = time() - 5;
                    //this makes the time 5 seconds ago
                    setcookie("affid", NULL, $past);
                    setcookie("affid", $affid, time() + 3600 * 24 * 30, "/", "");
                    setcookie("shType", NULL, $past);
                    setcookie("shType", $obj, time() + 3600 * 24 * 30, "/", "");
                    setcookie("pid", NULL, $past);
                    setcookie("pid", $product_id, time() + 3600 * 24 * 30, "/", "");
                }

//                if ($this->agent->referrer())
               // {
                    //////////////ANALYTICS//////////////
                    $input_data = array();
                    $input_data['user_identifier'] = $affid <> '' ? $affid : 0;
                    $input_data['product_id'] = $row['product_id'];
                    $input_data['request_uri'] = $this->input->server('REQUEST_URI');
                    if ($this->input->server('REDIRECT_URL') == '') {
                        $input_data['url'] = $this->input->server('HOST_NAME') . $this->input->server('REQUEST_URI');
                    } else {
                        $input_data['url'] = $this->input->server('REDIRECT_URL');
                    }
                    $input_data['timestamp'] = time();
                    $input_data['client_ip'] = $this->input->server('REMOTE_ADDR');
                    $input_data['client_user_agent'] = $this->agent->agent_string();

                    if (isset($_GET) && $_GET['type'] == 'fb') {
                        $input_data['referer_page'] = 'facebook';
                    } else if (isset($_GET) && $_GET['type'] == 'tw') {
                        $input_data['referer_page'] = 'twitter';
                    } else if (isset($_GET) && $_GET['type'] == 'ln') {
                        $input_data['referer_page'] = 'linkedin';
                    }else if (isset($_GET) && $_GET['type'] == 'em') {
                        $input_data['referer_page'] = 'email';
                    } else {
                        $input_data['referer_page'] = $this->agent->referrer();
                    }
//                    dd($input_data);
                    $userTracking_ID = $this->checkout_model->saveUrlAnalytics($input_data);

                    // ORDERS

                    $order_data = array();
                    $order_data['product_id'] = $product_id = $row['product_id'];
                    $order_data['price'] = $row['price'];
                    $order_data['url'] = $input_data['url'];
                    $order_data['seller_id'] = $seller_id = $row['user_id'];
                    $advertiser_id = getVal('user_id', 'c_users', 'user_key', $affid);
                    $order_data['advertiser_id'] = $advertiser_id <> '' ? $advertiser_id : 0;
                    $order_data['created'] = time();
                    $order_data['user_tracking'] = $userTracking_ID;
                    $order_data['order_status'] = 1; // pending
                    $db_query = $this->checkout_model->save_order($order_data);
                    $order_id = $this->db->insert_id();

                    //////////////COMMISSION//////////////
                    $array = array();
                    $array['total_commission'] = $total_commission = getVal('commission', 'c_products_commission', 'product_id', $product_id);
                    $array['advertiser_commission'] = $advertiser_commission = $row['commission'];
                    $array['user_id'] = $advertiser_id <> '' ? $advertiser_id : 0;
                    $array['product_id'] = $row['product_id'];
                    $array['order_id'] = $order_id;
                    $array['created'] = time();
                    $this->checkout_model->save_commission($array);
                    //////////////COMMISSION//////////////
                    //
                    if (parse_url($row['url'], PHP_URL_QUERY)) {
                        $newUrl = '&subid=' . $affid . '&affid=' . $affid.'&type='.$obj;
                        header('Location:' . $row['url'] . $newUrl);
                    } else {
                        $newUrl = '?subid=' . $affid . '&affid=' . $affid.'&type='.$obj;
                        header('Location:' . $row['url'] . $newUrl);
                    }
//                    redirect($row['url']);

                    die();
                    exit;
              //  }

                $data['product_images'] = $this->checkout_model->getProductImages($row['product_id']);
                $data['title'] = $row['product_name'];
                $data['meta_description'] = $row['meta_description'];
                $data ['content'] = $this->load->view('detail', $data, true); //Return View as data
                $this->load->view('includes/template_dashboard.view.php', $data);
            } else {
                show_404();
            }
        } else {
            show_404();
        }
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */