<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Analytics extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

//        if (!$this->input->is_ajax_request()) {
//            exit('No direct script access allowed');
//         }

        $this->load->model('analytics_model');
        $this->load->model('checkout/checkout_model');
    }

    public function index()
    {

        $data = array();

        if ($_GET)
        {

            

            $prod                    = json_decode(urldecode(urldecode($_GET['pro'])));
            $data['user_identifier'] = $prod->affid;
            if ($data['user_identifier'] == '' || $data['user_identifier'] == 'undefined')
            {
                echo 'undefined';
                exit;
            }
            $data['request_uri'] = urldecode($prod->uri);

            $data['timestamp']         = time();
            $data['client_ip']         = $prod->userIp;
            $data['client_user_agent'] = $prod->userAgent;

            $url    = urldecode($prod->url);
            $parsed = parse_url($url);
            if ($parsed['scheme'] == '')
            {
                $url = 'http://' . $url;
            }
            $data['url'] = $url;
            $parse       = parse_url($url);

            $product_id         = $this->analytics_model->getProductIdFromUrl($parse['host']);
            $data['product_id'] = $product_id;
            if (strpos(urldecode($prod->url), '&type=fb') !== false)
            {
                $data['referer_page'] = 'facebook';
            }
            else if (strpos(urldecode($prod->url), '&type=tw') !== false)
            {
                $data['referer_page'] = 'twitter';
            }
            else if (strpos(urldecode($prod->url), '&type=ln') !== false)
            {
                $data['referer_page'] = 'linkedin';
            }
            else if (strpos(urldecode($prod->url), '&type=em') !== false)
            {
                $data['referer_page'] = 'email';
            }
            else
            {
                $data['referer_page'] = $prod->referrer <> '' ? urldecode($prod->referrer) : '';
            }

            $res = $this->analytics_model->saveUrlAnalytics($data);
            
            $this->detail($data,$res);
            
            header('Content-Type: image/png');
            echo base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAAApJREFUCNdjYAAAAAIAAeIhvDMAAAAASUVORK5CYII=');
        }
        else
        {
            
        }
    }

    public function detail($data,$user_tracking_id)
    {
        
        $this->load->library('user_agent');
        $this->load->library('session');
        
        $product_id = $data['product_id'];
        if ($product_id)
        {
            $affid       = $data['user_identifier'];
            $row         = $this->checkout_model->getRow($product_id);

            if (!empty($row))
            {
                // ORDERS
                $order_data                  = array();
                $order_data['product_id']    = $product_id                  = $row['product_id'];
                $order_data['price']         = $row['price'];
                $order_data['url']           = $data['url'];
                $order_data['seller_id']     = $seller_id                   = $row['user_id'];
                $advertiser_id               = getVal('user_id', 'c_users', 'user_key', $affid);
                $order_data['advertiser_id'] = $advertiser_id <> '' ? $advertiser_id : 0;
                $order_data['created']       = time();
                $order_data['user_tracking'] = $user_tracking_id;
                $order_data['order_status']  = 1; // pending
                $db_query                    = $this->checkout_model->save_order($order_data);
                $order_id                    = $this->db->insert_id();

                //////////////COMMISSION//////////////
                $array                          = array();
                $array['total_commission']      = $total_commission               = getVal('commission', 'c_products_commission', 'product_id', $product_id);
                $array['advertiser_commission'] = $advertiser_commission          = $row['commission'];
                $array['user_id']               = $advertiser_id <> '' ? $advertiser_id : 0;
                $array['product_id']            = $row['product_id'];
                $array['order_id']              = $order_id;
                $array['created']               = time();
                $this->checkout_model->save_commission($array);
                //////////////COMMISSION//////////////
            }
            else
            {
                show_404();
            }
        }
        else
        {
            show_404();
        }
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */