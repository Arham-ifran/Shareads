<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Checkout_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

//End __construct

    function save_order($data) {
        return $this->db->insert('c_orders', $data);
    }

    function saveLeadGeneration($data) {
        return $this->db->insert('c_lead_generation', $data);
    }

    function save_commission($data) {
        $inserts = $this->db->insert('c_user_commissions', $data);
        return $inserts;
    }


    function getOrderData($order_id) { // for payment

        $query = "SELECT
			ordr.*,pro.product_name,pro.url
				FROM
				c_orders ordr
                                inner join c_products pro on ordr.product_id = pro.product_id
                                where ordr.id = '".$order_id."'
                  limit 1";
        $query = $this->db->query($query);
            return $result = $query->row_array();
    }

    function chengeOrderStatus($unique_session_id,$txn_id) {

        $this->db->where('id', $unique_session_id);
        return $this->db->update('c_orders', array('order_status' => '2','transaction_id' => $txn_id));
    }

    function getRow($product_id) {
        $query = $this->db->select('c_products.*')->join('c_users', 'c_users.user_id = c_products.user_id', 'inner')->get_where('c_products', array('c_products.product_id' => $product_id,'c_products.status' => 1,'c_products.publisher_status' => 1,'c_users.status' => 1,'c_users.is_active' => 1));
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
    }
    function getProductImages($id) {

        $query = $this->db->get_where('c_product_images', array('product_id' => $id));

        if ($query->num_rows() > 0) {

            return $query->result_array();
        }
    }

    function saveUrlAnalytics($data)
    {
        $this->db->insert('c_usertracking', $data);
        return $this->db->insert_id();
    }

}

//End Class