<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Reports_model extends CI_Model {

    var $tbl = '';

    public function __construct() {
        parent::__construct();
    }

//End __construct
    // Common Functions

    function get_user_list_report($data) {
        $where = ' usr.status = 1 ';
        if (trim($data['date_from']) != '' && trim($data['date_to']) == '') {
            $where .=' AND usr.created  >="' . $data['date_from'] . '"';
        }
        if (trim($data['date_from']) == '' && trim($data['date_to']) != '') {
            $where .=' AND usr.created  <="' . $data['date_to'] . '"';
        }
        if (trim($data['date_from']) != '' && trim($data['date_to']) != '') {
            $where .=' AND usr.created  >="' . $data['date_from'] . '"';
            $where .=' AND usr.created  <="' . $data['date_to'] . '"';
        }
        if ($data['account_type'] <>  '') {
            $where .=' AND usr.account_type  ="' . $data['account_type'] . '"';
        }


         $sql_ = 'SELECT
			  usr.*,

			 type.type as user_type'
                . ''
                . ' FROM c_users usr '
                . ''

                . ' INNER JOIN c_users_types type on usr.account_type = type.id'
                . ' WHERE '
                . $where
                . ' ';

        $sql_.= " ORDER BY usr.user_id DESC";

        $query = $this->db->query($sql_);
        return $query;
    }

    function get_listing_ads_report($data) {
        $where = ' pro.status = 1 ';
        if (trim($data['date_from']) != '' && trim($data['date_to']) == '') {
            $where .=' AND pro.created  >="' . $data['date_from'] . '"';
        }
        if (trim($data['date_from']) == '' && trim($data['date_to']) != '') {
            $where .=' AND pro.created  <="' . $data['date_to'] . '"';
        }
        if (trim($data['date_from']) != '' && trim($data['date_to']) != '') {
            $where .=' AND pro.created  >="' . $data['date_from'] . '"';
            $where .=' AND pro.created  <="' . $data['date_to'] . '"';
        }
        if (trim($data['product_name']) != '') {
            $where .=' AND (pro.product_name   LIKE "%' . $data['product_name'] . '%")';
        }

        $sql_ = 'SELECT
			  pro.*,usr.full_name,typ.product_type,

			 cat.category_name'
                . ''
                . ' FROM c_products pro '
                . ''
                . ' INNER JOIN c_users usr ON `usr`.`user_id` = pro.`user_id`'
                . ' INNER join  c_products_types typ on pro.product_type = typ.id'
                . ' INNER JOIN c_categories cat on cat.category_id = pro.category_id'
                . ' WHERE '
                . $where
                . ' ';

        $sql_.= " ORDER BY pro.product_id DESC";
// echo $sql_;die();
        $query = $this->db->query($sql_);
        return $query;
    }

    function get_advertiser_commissions_report($data) {
        $where = '  ordr.order_status = 2';
        if (trim($data['date_from']) != '' && trim($data['date_to']) == '') {
            $where .=' AND comm.created  >="' . $data['date_from'] . '"';
        }
        if (trim($data['date_from']) == '' && trim($data['date_to']) != '') {
            $where .=' AND comm.created  <="' . $data['date_to'] . '"';
        }
        if (trim($data['date_from']) != '' && trim($data['date_to']) != '') {
            $where .=' AND comm.created  >="' . $data['date_from'] . '"';
            $where .=' AND pro.created  <="' . $data['date_to'] . '"';
        }


        $sql_ = 'SELECT
			  comm.*,usr.full_name,
                          pro.product_name,ordr.price,pro.currency as p_cy,
			 cat.category_name,ordr.is_confirmed '
                . ''
                . ' FROM c_user_commissions comm '
                . ' LEFT JOIN c_users usr ON `usr`.`user_id` = comm.`user_id`'
                . ' LEFT JOIN c_products pro ON `pro`.`product_id` = comm.`product_id`'
                . ' LEFT JOIN c_orders ordr ON `ordr`.`id` = comm.`order_id`'

                . ' INNER JOIN c_categories cat on cat.category_id = pro.category_id'
                . ' WHERE '
                . $where
                . ' ';

        $sql_.= " ORDER BY comm.id DESC";

        $query = $this->db->query($sql_);
        return $query;
    }

    function get_publisher_commissions_report($data) {
        $where = ' usr.account_type = 2  AND ordr.order_status > 1 ';
        if (trim($data['date_from']) != '' && trim($data['date_to']) == '') {
            $where .=' AND comm.created  >="' . $data['date_from'] . '"';
        }
        if (trim($data['date_from']) == '' && trim($data['date_to']) != '') {
            $where .=' AND comm.created  <="' . $data['date_to'] . '"';
        }
        if (trim($data['date_from']) != '' && trim($data['date_to']) != '') {
            $where .=' AND comm.created  >="' . $data['date_from'] . '"';
            $where .=' AND pro.created  <="' . $data['date_to'] . '"';
        }


        $sql_ = 'SELECT
			  comm.*,usr.full_name,
                          pro.product_name,ordr.price,ordr.seller_id,pro.currency as p_cy,
			 cat.category_name,ordr.is_confirmed '
                . ''
                . ' FROM c_user_commissions comm '

                . ' INNER JOIN c_products pro ON `pro`.`product_id` = comm.`product_id`'
                . ' INNER JOIN c_users usr ON `usr`.`user_id` = pro.`user_id`'
                . ' INNER JOIN c_categories cat on cat.category_id = pro.category_id'
                . ' INNER JOIN c_orders ordr ON `ordr`.`id` = comm.`order_id`'
                . ' WHERE '
                . $where
                . ' ';

        $sql_.= " ORDER BY comm.id DESC";

        $query = $this->db->query($sql_);
        return $query;
    }
}

//End Class