<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Products_model extends CI_Model {

    var $tbl = 'products';
    var $tbl_share_link = 'products_links_share';
    var $tbl_categories = 'categories';
    var $tbl_products_commission = 'products_commission';
    var $tbl_product_type = 'products_types';

    public function __construct() {
        parent::__construct();
    }

//End __construct


    function loadListings($data) {

        $where = ' user_id = ' . $this->session->userdata('user_id');


        $sql_ = 'SELECT
            p_currency.currency_name,p_currency.currency_symbol,
                    pro.*,typ.product_type as pro_type,(select count(lnk.id) from
                        ' . $this->db->dbprefix . $this->tbl_share_link . ' lnk where lnk.product_id = pro.product_id
                        ) as counter,cpType.commission as orignal_commision
                FROM
                    ' . $this->db->dbprefix . $this->tbl . ' as pro
                        INNER join  c_products_types typ on pro.product_type = typ.id
                        INNER join  c_products_commission as cpType on cpType.product_id = pro.product_id
                        INNER join  c_currencies as p_currency on p_currency.currency_id = pro.currency
                        where ' . $where . '
		';

        $perpage = 10;
        $offset = 0;
        if ($this->uri->segment(3) > 0) {
            $offset = $this->uri->segment(3);
        } else {
            $offset = 0;
        }
        $query = $this->db->query($sql_);
        $total_records = $query->num_rows();
        init_front_pagination('products/index', $total_records, $perpage);


        $sql_ .= "ORDER BY pro.product_id DESC";
        $sql_ .= " LIMIT " . $offset . ", " . $perpage . "";
        $query = $this->db->query($sql_);
        return $query;
    }

    function update_product_slug($slug, $id) {
        $this->db->where('product_id', $id);
        $data['product_slug'] = $slug;
        $this->db->update($this->db->dbprefix . $this->tbl, $data);
        return true;
    }

    /**
     * Method: saveItem
     * Params: $post
     * Return: True/False
     */
    public function saveItem($post) {
        $id = $post['product_id'];
        if (is_array($post)) {
            $data_insert = array();
            foreach ($post as $k => $v) {
                if ($k != 'product_id' && $k != 'action') {
                    if (is_array($v)) {
                        $data_insert[$k] = implode(',', array_filter($v));
                    } else {
                        $data_insert[$k] = $v;
                    }
                }
            }
            /*             * Unset unwanted fields */
            unset($data_insert['sub_parent']);
            unset($data_insert['image_ids']);
        }
        $data_insert['parent_categories'] = $data_insert['category_id'];
        $cats = end(explode(',', rtrim($data_insert['category_id'], ",")));
        $data_insert['category_id'] = $cats;
        $data_insert['user_id'] = $data_insert['user_id'];
        


        if ($post['action'] == 'add') {//Save Data Ad verts
            $data_insert['created'] = time();
            $data_insert['status'] = 1;
            $original_commission = $data_insert['commission'];
            $data_insert['commission'] = number_format(($data_insert['commission'] - ($data_insert['commission'] * (SHAREADS_COMMISION/100))), 2, '.', '');
            $db = $this->db->insert($this->db->dbprefix . $this->tbl, $data_insert);
            $product_id = $this->db->insert_id();

            /*             * COMMISSION ********* */
            $comm = array();
            $comm['product_id'] = $product_id;
            $comm['commission'] = $original_commission;
            $this->db->insert($this->db->dbprefix . $this->tbl_products_commission, $comm);
            $short_url = base_url('detail') . '?prd=' . $this->common->encode($product_id) . '&affid=' . $this->session->userdata('user_key');
            $short_url = bitly_shorten($short_url);
            $db_update_product_short_url = $this->db->where(array('product_id' => $product_id))->update($this->db->dbprefix . $this->tbl, array('short_url' => $short_url));
            $action['id'] = $product_id;
            $action['msg'] = $db;
        } else {//Update Data
            $this->db->where('product_id', $id);
            $short_url = base_url('detail') . '?prd=' . $this->common->encode($id) . '&affid=' . $this->session->userdata('user_key');
            $data_insert['short_url'] = bitly_shorten($short_url);
            $db = $this->db->update($this->db->dbprefix . $this->tbl, $data_insert);

            $action['id'] = $id;
            $action['msg'] = $db;
        }
        return $action;
    }

    function getRow($id) {
        //INNER join  c_products_commission as cpType on cpType.product_id = pro.product_id
        $sql = 'Select pro.*,cpType.commission as orignal_commision from '.$this->db->dbprefix .$this->tbl.' as pro Inner join c_products_commission as cpType ON cpType.product_id = pro.product_id WHERE pro.product_id = '.$id;
        $query = $this->db->query($sql);
        //$query = $this->db->select('pro.*,cpType.commission as orignal_commision')->join('c_products_commission as cpType','cpType.product_id = pro.product_id','INNER')->get_where($this->db->dbprefix . $this->tbl.' as pro', array('pro.product_id' => $id));
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
    }

    /**
     * Method: deleteItem
     * Params: $itemId
     * Return: True/False
     */
    public function deleteItem($product_id) {

        $this->db->where('product_id', $product_id);
        $this->db->delete($this->tbl_products_commission);

        $this->db->where('product_id', $product_id);
        $this->db->delete('c_product_images');

        $this->db->where('product_id', $product_id);
        $this->db->delete($this->tbl);
        $error = $this->db->error();
        if ($error['code'] <> 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Method: loadProductTypes
     * Params: $sel
     * Return: categories
     */
    function loadProductTypes($sel) {

        $this->db->select('*');
        $this->db->where('status', 1);
        $this->db->where('id <>', 2);
        $query = $this->db->get($this->db->dbprefix($this->tbl_product_type));
        if ($query->num_rows() > 0) {
            $html = '';
            foreach ($query->result() as $row) {
                if ($row->id == $sel) {
                    $seletd = 'selected="selected"';
                } else {
                    $seletd = '';
                }
                $html .= '<option value="' . $row->id . '" ' . $seletd . '>' . $row->product_type . '</option>';
            }
            return $html;
        }
    }

    function getSubCategories($data) {
        $query = "select category_id, category_name,category_slug from " . $this->db->dbprefix($this->tbl_categories) . " where parent_id = " . $data ['parent_id'] . " and status = 1 ORDER BY category_name ASC";
        $query = $this->db->query($query);

        return $result = $query->result_array();
    }

    /**
     * Method: getParentCats
     * Params: $parent
     * Return: categories
     */
    function getParentCats($parent_id) {
        $query = 'SELECT T2.category_id, T2.category_name,T2.parent_id
                FROM (
                    SELECT
                        @r AS _id,
                        (SELECT @r := parent_id FROM c_categories WHERE category_id = _id) AS parent_id,
                        @l := @l + 1 AS lvl
                    FROM
                        (SELECT @r := ' . $parent_id . ', @l := 0) vars,
                        c_categories m
                    WHERE @r <> 0) T1
                JOIN c_categories T2
                ON T1._id = T2.category_id
                ORDER BY T1.lvl DESC';


        $query = $this->db->query($query);

        return $result = $query->result_array();
    }

    /**
     * Method: loadCategories
     * Params: $parent,$level,$sel
     * Return: categories
     */
    function loadCategories($parent, $level, $sel) {

        $this->db->where('parent_id', $parent);
        $this->db->select('category_id,category_name');
        $this->db->where('status', 1);
        $query = $this->db->get($this->db->dbprefix($this->tbl_categories));
        if ($query->num_rows() > 0) {
            $html = '';
            foreach ($query->result() as $row) {
                if ($row->category_id == $sel) {
                    $seletd = 'selected="selected"';
                } else {
                    $seletd = '';
                }
                $html .= '<option value="' . $row->category_id . '" ' . $seletd . '>' . str_repeat('-', $level) . ' ' . $row->category_name . '</option>';
            }
            return $html;
        }
    }

    /**  For Product Images Insertion */
    function update_product_images($data, $imageid) {

        $this->db->where('image_id', $imageid);
        $this->db->update('c_product_images', $data);
    }

    /**
     * Method: update Image
     * Params: $slug
     * Return: True/False
     */
    function delete_image($image_id) {
        $this->db->where('image_id', $image_id);
        $this->db->delete('c_product_images');
    }

    /**  For Product Images Insertion */
    function add_product_images($data) {
        $insert_new = $this->db->insert('c_product_images', $data);
        return $insert_new;
    }

    /**
     * Method: remove_uploaded_file
     * Params: $images
     * Return: True/False
     */
    function remove_uploaded_file($imgId) {
        $this->db->where('image_id', $imgId);
        $this->db->delete('c_product_images');
        return true;
    }

    function getProductImages($id) {

        $query = $this->db->get_where('c_product_images', array('product_id' => $id));

        if ($query->num_rows() > 0) {

            return $query->result_array();
        }
    }
    
    function loadCommisionListings($data,$id = '') {
       
        $where .= ' where 1 ';
        $where .= ' AND pro.status = 1 AND pro.user_id = ' . $this->session->userdata('user_id');
        $where .= ' AND pro.product_id = '.$this->common->decode($id).'  ';
        $where .= ' AND orders.order_status = 2 ';
        //$where .= ' AND invoice_orders.order_ids NOT IN(orders.id) ';
        $where .= ' AND user_commissions.is_paid = 0 ';
        
        $group_by = ' group by orders.id  ';
        
        $order_by = '  ';

        $sql_ = 'SELECT
                    p_currency.currency_name,p_currency.currency_symbol,
                    pro.*,
                    (CASE  WHEN users.full_name IS NULL THEN users.first_name ELSE users.full_name END) as affiliator_name,
                    (CASE  WHEN FIND_IN_SET(orders.id,invoice_orders.order_ids) THEN "genereated" ELSE "pending" END) as invoice_status,
                    user_commissions.total_commission as affiliator_commision,
                    typ.product_type as pro_type,
                    orders.is_confirmed as is_confirmed,
                    orders.id as order_id,
                    orders.order_id as unique_order_id,
                    orders.transaction_id as unique_transaction_id,
                    (select count(lnk.id) from ' . $this->db->dbprefix . $this->tbl_share_link . ' lnk where lnk.product_id = pro.product_id AND lnk.user_id = users.user_id) as counter,
                    user_commissions.created as uc_created
                FROM
                    ' . $this->db->dbprefix . $this->tbl . ' as pro
                        INNER join  c_products_types typ on pro.product_type = typ.id
                        INNER join  c_orders as orders on orders.product_id = pro.product_id
                        LEFT join  c_user_commissions as user_commissions on user_commissions.product_id = pro.product_id
                        LEFT join  c_users as users on users.user_id = user_commissions.user_id
                        LEFT join  c_invoice_orders as invoice_orders on invoice_orders.product_id = pro.product_id
                        INNER join  c_currencies as p_currency on p_currency.currency_id = pro.currency
                        ' . $where . ' '.$group_by. ' '.$order_by.'   ';

        $perpage = 10;
        $offset = 0;
        if ($this->uri->segment(4) > 0) {
            $offset = $this->uri->segment(4);
        } else {
            $offset = 0;
        }
        $query = $this->db->query($sql_);
        $total_records = $query->num_rows();
        init_front_pagination('products/view_commisions/'.$id, $total_records, $perpage,$id);


        $sql_ .= "ORDER BY orders.id DESC";
        $sql_ .= " LIMIT " . $offset . ", " . $perpage . "";
//        echo $sql_;die();
        $query = $this->db->query($sql_);
        return $query;
    }
    
    public function changeAffiliateStatus($post)
    {
        $status = '';
        if($post['s_is_confirmed'] == 'true') { $status = 1; } else { $status = 0; } 
        $this->db->where('id', $post['s_order_id']);
        return $this->db->update('c_orders', array('is_confirmed' => (bool)$status));
    }

}

//End Class