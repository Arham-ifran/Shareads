<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tracking_model extends CI_Model
{

    var $tbl        = 'usertracking';
    var $tbl_orders = 'orders';

    public function __construct()
    {
        parent::__construct();
    }

//End __construct

    function getOrderStatus($user_id, $product_id)
    {

        $query = "select * from " . $this->db->dbprefix($this->tbl_orders) . " where advertiser_id = " . $user_id . " and product_id = " . $product_id . " and is_paid = 0 and order_status = 1 ORDER BY id desc limit 1";
        $query = $this->db->query($query);
        if ($query->num_rows() > 0)
        {
            return $query->row_array();
        }
        else
        {
            return 0;
        }
    }

    function updateOrderStatus($status)
    {
        $this->db->where('id', $status['id']);
        return $this->db->update('c_orders', array('order_status' => '2'));
    }

    function getProductIdFromUrl($url)
    {
        $query = "SELECT
				  product_id
				FROM
				c_products
                    WHERE   url LIKE '%" . $url . "%'
          order by product_id DESC limit 1";
        $query = $this->db->query($query);
        if ($query->num_rows() > 0)
        {
            $row = $query->row();
            return $row->product_id;
        }
        else
        {
            return 0;
        }
    }
    
    public function getOrderId($_pid)
    {
         $query = "SELECT
				  id
				FROM
				c_orders
                    WHERE   product_id = " . $_pid . "
          order by id DESC limit 1";
        $query = $this->db->query($query);
        if ($query->num_rows() > 0)
        {
            $row = $query->row();
            return $row->id;
        }
        else
        {
            return 0;
        }
    }

    public function updateOrder($update)
    {
        $this->db->where('id', $update['id']);
        return $this->db->update('c_orders', array('order_id' => $update['order_id'],'transaction_id' => $update['transaction_id'],'order_status' => 2));
    }

}

//End Class