<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Marketing_model extends CI_Model {

    var $tbl = 'products';
    var $tbl_share_link = 'products_links_share';
     var $tbl_categories = 'categories';
    public function __construct() {
        parent::__construct();
    }

//End __construct
    function getAllCategories()
    {
        $sql_ = 'SELECT c.category_id 	, c.category_name, c.category_slug , '
                . ' c.parent_id'
                . ' FROM c_categories AS c'
                . ''
                . ' WHERE c.status=1 ORDER BY c.category_name,c.parent_id ASC';

        $query = $this->db->query($sql_);
        $result = $query->result();

        $children = array();
        $link = base_url();
        if ($result) {
            foreach ($result as $item) {
                $slug = $item->category_slug;
                $item->category_name = $item->category_name;
                $item->parent_id = $item->parent_id;
                $pt = $item->parent_id;
                $list = @$children[$pt] ? $children[$pt] : array();
                array_push($list, $item);
                $children[$pt] = $list;
            }
        }
        return $children;

    }

    function loadListings($data) {

        $where = ' pro.status = 1 AND user.status = 1 AND user.is_active = 1 AND pro.publisher_status = 1 AND (pro.script_verified = 1 OR pro.script_verified_by_admin = 1) ';

        if ($data['category_id'] <> '' && $data['category_id'] <> 'all') {
            $where .= ' AND FIND_IN_SET(' . $data['category_id'] . ',parent_categories)';
        }

        if ($data['product_type'] <> '' ) {
            $where .= ' AND product_type IN (' . $data['product_type'] . ')';
        }

        if ($data['product_type'] <> '' ) {
            $where .= ' AND product_type IN (' . $data['product_type'] . ')';
        }

        if ($data['avg_sale'] <> '' ) {
            $where .= ' AND ordr.price > ' . $data['avg_sale'] . '';
        }

        if ($data['avg_percentage'] <> '' ) {
            $where .= ' AND comm.advertiser_commission > ' . $data['avg_percentage'] . '';
        }

        if ($data['query'] <> '') {
            $where .= ' AND ( pro.product_name  LIKE "%' . $data['query'] . '%" ||  pro.short_description LIKE "%' . $data['query'] . '%" ||  pro.long_description LIKE "%' . $data['query'] . '%" )';
        }

         $sql_ = 'SELECT
                    pro.*,(select count(lnk.id) from
                        ' . $this->db->dbprefix . $this->tbl_share_link . ' lnk where lnk.product_id = pro.product_id
                        ) as counter
                         
                FROM
                    ' . $this->db->dbprefix . $this->tbl . ' as pro

                    LEFT JOIN c_users user ON user.user_id = pro.user_id
                    LEFT JOIN c_orders ordr ON ordr.product_id = pro.product_id
                    LEFT JOIN c_user_commissions comm ON comm.order_id = ordr.id

                        where '.$where.'
                            GROUP BY pro.product_id
                            
		';
         if($data['limit'] <> '') {
             $perpage = $data['limit'];
        } else {
            $perpage = 10;
        }
        $offset = 0;
        if ($this->uri->segment(3) > 0) {
            $offset = $this->uri->segment(3);
        } else {
            $offset = 0;
        }
        // echo $sql_;die();
        $query = $this->db->query($sql_);
        $total_records = $query->num_rows();
        init_front_pagination('marketing/index', $total_records, $perpage);

        if ($data['type'] == 'all') {
            $sql_.= "ORDER BY pro.product_id DESC";
        } elseif ($data['type'] == 'popular') {
            $sql_.= "ORDER BY counter DESC";
        } elseif ($data['type'] == 'highest_paying') {
            $sql_.= "ORDER BY pro.commission DESC";
        } elseif ($data['type'] == 'lowest_paying') {
           $sql_.= "ORDER BY pro.commission ASC";
        }else{
            $sql_.= "ORDER BY pro.sequence DESC,pro.product_id DESC";
        }


        $sql_.=" LIMIT " . $offset . ", " . $perpage . "";
       
        $query = $this->db->query($sql_);
        // echo '<pre>';print_r($query->result_array());die();
        return $query;
    }

function loadListings_withoutFilter($data) {

        $where = ' pro.status = 1 AND user.status = 1 AND user.is_active = 1 AND pro.publisher_status = 1 AND (pro.script_verified = 1 OR pro.script_verified_by_admin = 1) ';

        if ($data['category_id'] <> '' && $data['category_id'] <> 'all') {
            $where .= ' AND FIND_IN_SET(' . $data['category_id'] . ',parent_categories)';
        }

        if ($data['product_type'] <> '' ) {
            $where .= ' AND product_type IN (' . $data['product_type'] . ')';
        }

        if ($data['product_type'] <> '' ) {
            $where .= ' AND product_type IN (' . $data['product_type'] . ')';
        }

        if ($data['query'] <> '') {
            $where .= ' AND ( pro.product_name  LIKE "%' . $data['query'] . '%" ||  pro.short_description LIKE "%' . $data['query'] . '%" ||  pro.long_description LIKE "%' . $data['query'] . '%" )';
        }

         $sql_ = 'SELECT
                    pro.*,(select count(lnk.id) from
                        ' . $this->db->dbprefix . $this->tbl_share_link . ' lnk where lnk.product_id = pro.product_id
                        ) as counter
                         
                FROM
                    ' . $this->db->dbprefix . $this->tbl . ' as pro

                    LEFT JOIN c_users user ON user.user_id = pro.user_id

                        where '.$where.'
                            GROUP BY pro.product_id
                            
		';
         if($data['limit'] <> '') {
             $perpage = $data['limit'];
        } else {
            $perpage = 10;
        }
        $offset = 0;
        if ($this->uri->segment(3) > 0) {
            $offset = $this->uri->segment(3);
        } else {
            $offset = 0;
        }
        // echo $sql_;die();
        $query = $this->db->query($sql_);
        $total_records = $query->num_rows();
        init_front_pagination('marketing/index', $total_records, $perpage);

        if ($data['type'] == 'all') {
            $sql_.= "ORDER BY pro.product_id DESC";
        } elseif ($data['type'] == 'popular') {
            $sql_.= "ORDER BY counter DESC";
        } elseif ($data['type'] == 'highest_paying') {
            $sql_.= "ORDER BY pro.commission DESC";
        } elseif ($data['type'] == 'lowest_paying') {
           $sql_.= "ORDER BY pro.commission ASC";
        }else{
            $sql_.= "ORDER BY pro.sequence DESC,pro.product_id DESC";
        }


        $sql_.=" LIMIT " . $offset . ", " . $perpage . "";
       
        $query = $this->db->query($sql_);
        // echo '<pre>';print_r($query->result_array());die();
        return $query;
    }

    function saveSharedLinkCopy($data)
    {

//        $this->db->select('id');
//        $this->db->where('share_type', 4);
//        $this->db->where('product_id', $data['product_id']);
//        $this->db->where('user_id', $data['user_id']);
//        $this->db->limit(1);
//        $qry = $this->db->get($this->db->dbprefix($this->tbl_share_link));
//        if ($qry->num_rows() > 0) {
//            $result = $qry->row_array();
//
//            $query = "update " . $this->db->dbprefix($this->tbl_share_link) . " as a set share_counter = (share_counter + 1),created = ".time()." where a.id = '" . $result['id'] . "' LIMIT 1";
//        $this->db->query($query);
//
//        }else{
         return $this->db->insert($this->db->dbprefix . $this->tbl_share_link, $data);
//        }
    }

    function getAveragePrice($product_id)
    {
        $query = 'select AVG(comm.advertiser_commission) as counter from
                        c_user_commissions comm
                            INNER JOIN c_products pro on comm.product_id = pro.product_id
                    where comm.product_id = '.$product_id.'

                    limit 1
                         ';
        $query = $this->db->query($query);
        $row = $query->row();
        return $row->counter;
    }
    
    function getPercentPrice($product_id)
    {
        $query = 'SELECT
                        SUM(comm.advertiser_commission) as counter ,SUM(ordr.price) as total

                        from c_orders ordr


                        INNER JOIN c_products pro on ordr.product_id = pro.product_id

                        INNER JOIN  c_user_commissions comm on ordr.id = comm.order_id
                    where comm.product_id = '.$product_id.'

                         ';
        $query = $this->db->query($query);
        return  $query->row_array();
    }

    /**
     * Method: getCategories
     * Params: $parent,$level,$sel
     * Return: categories
     */

    function getCategories($parent, $level, $sel) {
        $this->db->where('parent_id', $parent);
        $this->db->select('category_id,category_name');
        $this->db->where('status', 1);
        $query = $this->db->get($this->tbl_categories);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                if ($row->category_id == $sel) {
                    $seletd = 'selected="selected"';
                } else {
                    $seletd = '';
                }
                echo '<option value="' . $row->category_id . '" ' . $seletd . '>' . str_repeat('-', $level) . ' ' . $row->category_name . '</option>';
                $this->getCategories($row->category_id, $level + 1, $sel);
            }
        }
    }

}

//End Class