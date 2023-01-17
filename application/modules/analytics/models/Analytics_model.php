<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Analytics_model extends CI_Model {

    var $tbl = 'usertracking';

    public function __construct() {
        parent::__construct();
    }

//End __construct

    function saveUrlAnalytics($data)
    {
        $this->db->insert($this->db->dbprefix . $this->tbl, $data);
        return $this->db->insert_id();        
    }

    function getProductIdFromUrl($url)
    {
         $query = "SELECT
				  product_id
				FROM
				c_products
                    WHERE   url LIKE '%".$url."%'
          order by product_id DESC limit 1";
        $query = $this->db->query($query);
        if($query->num_rows() > 0 )
        {
            $row = $query->row();
        return $row->product_id;
        }else{
            return 0;
        }
    }


}

//End Class