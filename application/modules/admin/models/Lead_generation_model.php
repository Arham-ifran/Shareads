<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Lead_generation_model extends CI_Model {

    var $tbl = 'lead_generation';
    var $tbl_categories = 'categories';
    var $tbl_products_commission = 'products_commission';
    var $tbl_product_type = 'products_types';

    public function __construct() {
        parent::__construct();
    }

//End __construct
    // Common Functions
    public function loadListing() {
        $sql_ = 'SELECT
                    lead.*,cat.category_name,full_name,pro.product_name
                FROM
                    ' . $this->db->dbprefix . $this->tbl . ' as lead
                        INNER join  c_users on c_users.user_id = lead.advertiser_id
                        INNER JOIN c_products as pro ON pro.product_id = lead.product_id
                        INNER JOIN c_categories as cat ON pro.category_id = cat.category_id

		';
        $sql_.= "ORDER BY lead.status ASC ,lead.id DESC";
        return $this->db->query($sql_);
    }

    /**
     * Method: updateItemStatus
     * Params: $itemId, $status
     */
    public function updateItemStatus($itemId, $status) {

        $data_insert = array('status' => $status);
        $this->db->where('id', $itemId);
        $this->db->update($this->tbl, $data_insert);
        return true;
    }



}

//End Class