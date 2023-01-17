<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Listings_model extends CI_Model
{

    var $tbl                     = 'products';
    var $tbl_categories          = 'categories';
    var $tbl_products_commission = 'products_commission';
    var $tbl_product_type        = 'products_types';

    public function __construct()
    {
        parent::__construct();
    }

//End __construct
    // Common Functions
    public function loadListing()
    {
           $filter = 'where 1 ';
        if (isset($_GET) && !empty($_GET))
        {
            if($_GET['f_product_name'] <> '')
            {
                $filter .= ' and pro.product_name like "%'.$_GET['f_product_name'].'%"  ';
            }
            if($_GET['f_category_id'] <> '')
            {
                $filter .= ' and FIND_IN_SET("'.$_GET['f_category_id'].'",pro.parent_categories) ';
            }
            if($_GET['f_sub_category_id'] <> '')
            {
                $filter .= ' and pro.category_id = '.$_GET['f_sub_category_id'].' ';
            }
            
            if($_GET['status'] <> '')
            {
               
                if($_GET['status'] == 1)
                {
                    $filter .= ' and pro.status = '.$_GET['status'].' ';
                }
                else if($_GET['status'] == 0)
                {
                    $filter .= ' and pro.status = '.$_GET['status'].' ';
                }
                else if($_GET['status'] == 2)
                {
                    $filter .= ' and pro.status = 1 and (pro.script_verified = 1 or pro.script_verified_by_admin = 1) ';
                }
                else
                {
                    $filter .= '  ';
                }
            }
        }
        
        $sql_  = 'SELECT
                    pro.*,cat.category_name,full_name,typ.product_type as pro_type
                FROM
                    ' . $this->db->dbprefix . $this->tbl . ' as pro
                        Left join  c_users on c_users.user_id = pro.user_id
                        INNER join  c_products_types typ on pro.product_type = typ.id
                        INNER JOIN c_categories as cat ON pro.category_id = cat.category_id
                        
		';
        $sql_ .= $filter;
        $sql_ .= "ORDER BY product_id DESC";
//        echo $sql_;die();
        $query = $this->db->query($sql_);
        return $query;
    }

    function getRow($id)
    {
        $query = $this->db->get_where($this->db->dbprefix . $this->tbl, array('product_id' => $id));
        if ($query->num_rows() > 0)
        {
            return $query->row_array();
        }
    }

    /**
     * Method: updateItemStatus
     * Params: $itemId, $status
     */
    public function updateItemStatus($itemId, $status)
    {
        if ($status == 1)
        {
            $status = 0;
        }
        else
        {
            $status = 1;
        }
        $data_insert = array('status' => $status);
        $this->db->where('product_id', $itemId);
        $this->db->update($this->tbl, $data_insert);
        $action      = 'Status updated successfully. Please wait...';
        $msg         = $action;
        return $msg;
    }

    /**
     * Method: deleteItem
     * Params: $itemId
     * Return: True/False
     */
    public function deleteItem($product_id)
    {

        $this->db->where('product_id', $product_id);
        $this->db->delete('c_product_images');

        $this->db->where('product_id', $product_id);
        $this->db->delete($this->tbl_products_commission);

        $this->db->where('product_id', $product_id);
        $this->db->delete($this->tbl);
        $error = $this->db->error();
        if ($error['code'] <> 0)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    /**
     * Method: saveItem
     * Params: $post
     * Return: True/False
     */
    public function saveItem($post)
    {
//        ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

        $user_id_publisher = $_POST['user_id_publisher'];
        unset($_POST['user_id_publisher']);

        $id = $post['product_id'];
        if (is_array($post))
        {
            $data_insert = array();
            foreach ($post as $k => $v)
            {
                if ($k != 'product_id' && $k != 'action')
                {
                    if (is_array($v))
                    {
                        $data_insert[$k] = implode(',', array_filter($v));
                    }
                    else
                    {
                        $data_insert[$k] = $v;
                    }
                }
            }
            /*             * Unset unwanted fields */
            unset($data_insert['sub_parent']);
            unset($data_insert['image_ids']);
        }


        $data_insert['parent_categories'] = $data_insert['category_id'];
        $cats                             = end(explode(',', rtrim($data_insert['category_id'], ",")));
        $data_insert['category_id']       = $cats;
        $data_insert['user_id']           = $data_insert['user_id'];


        if ($post['action'] == 'add')
        {//Save Data Ad verts
            $data_insert['created'] = time();
            $data_insert['status']  = $_POST['status'];
            if ($user_id_publisher > 1)
            {
                $data_insert['user_id']  = $user_id_publisher;    // For admin id in users table
                $data_insert['of_admin'] = 0;
            }
            else
            {
                $data_insert['user_id']  = 1;    // For admin id in users table
                $data_insert['of_admin'] = 1;
            }
            $original_commission       = $data_insert['commission'];
            if ($data_insert['of_admin'] == 0 || $user_id_publisher > 1)
            {
                $data_insert['commission'] = $original_commission;
                $data_insert['commission'] = number_format(($data_insert['commission'] - ($data_insert['commission'] * (SHAREADS_COMMISION / 100))), 2, '.', '');
            }
            else
            {
                $data_insert['commission'] = $original_commission;
            }
            
            unset($data_insert['user_id_publisher']);
            
            $db                        = $this->db->insert($this->db->dbprefix . $this->tbl, $data_insert);
            $product_id                = $this->db->insert_id();

            /*             * COMMISSION ********* */
            $comm               = array();
            $comm['product_id'] = $product_id;
            if ($data_insert['of_admin'] == 0 || $user_id_publisher > 1)
            {
                $comm['commission'] = $original_commission;
//                $comm['commission'] = number_format(($comm['commission'] - ($comm['commission'] * (SHAREADS_COMMISION / 100))), 2, '.', '');
            }
            else
            {
                $comm['commission'] = $original_commission;
            }
            $this->db->insert($this->db->dbprefix . $this->tbl_products_commission, $comm);
            $short_url                   = base_url('detail') . '?prd=' . $this->common->encode($product_id) . '&affid=' . $this->session->userdata('user_key');
            $short_url                   = bitly_shorten($short_url);
            $db_update_product_short_url = $this->db->where(array('product_id' => $product_id))->update($this->db->dbprefix . $this->tbl, array('short_url' => $short_url));
            $action['id']                = $product_id;
            $action['msg']               = $db;
        }
        else
        {//Update Data
            $total_comission = $data_insert['commission'];
            if ($data_insert['of_admin'] == 0 || $user_id_publisher > 1)
            {
                $data_insert['user_id']  = $user_id_publisher;
                $data_insert['commission'] = number_format(($data_insert['commission'] - ($data_insert['commission'] * (SHAREADS_COMMISION / 100))), 2, '.', '');
            }
            else
            {
                $data_insert['user_id']  = $user_id_publisher;
                $data_insert['commission'] = number_format(($data_insert['commission']), 2, '.', '');
            }
            $this->db->where('product_id', $id);
            $short_url                = base_url('detail') . '?prd=' . $this->common->encode($id) . '&affid=' . $this->session->userdata('user_key');
            $data_insert['short_url'] = bitly_shorten($short_url);
            unset($data_insert['user_id_publisher']);
            $db                       = $this->db->update($this->db->dbprefix . $this->tbl, $data_insert);
            $dataInsert['commission'] = $total_comission;

            $action['id']  = $id;
            $action['msg'] = $db;
            $this->db->where('product_id', $id);
            $this->db->update($this->db->dbprefix . $this->tbl_products_commission, $dataInsert);
        }
        return $action;
    }

    function getSubCategories($data)
    {
        $query = "select category_id, category_name,category_slug from " . $this->db->dbprefix($this->tbl_categories) . " where parent_id = " . $data ['parent_id'] . " and status = 1 ORDER BY category_name ASC";
        $query = $this->db->query($query);

        return $result = $query->result_array();
    }

    /**
     * Method: getParentCats
     * Params: $parent
     * Return: categories
     */
    function getParentCats($parent_id)
    {
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
    function loadCategories($parent, $level, $sel)
    {

        $this->db->where('parent_id', $parent);
        $this->db->select('category_id,category_name');
        $this->db->where('status', 1);
        $query = $this->db->get($this->db->dbprefix($this->tbl_categories));
        if ($query->num_rows() > 0)
        {
            $html = '';
            foreach ($query->result() as $row)
            {
                if ($row->category_id == $sel)
                {
                    $seletd = 'selected="selected"';
                }
                else
                {
                    $seletd = '';
                }
                $html .= '<option value="' . $row->category_id . '" ' . $seletd . '>' . str_repeat('-', $level) . ' ' . $row->category_name . '</option>';
            }
            return $html;
        }
    }

    /**
     * Method: loadProductTypes
     * Params: $sel
     * Return: categories
     */
    function loadProductTypes($sel)
    {

        $this->db->select('*');
        $this->db->where('status', 1);
        $query = $this->db->get($this->db->dbprefix($this->tbl_product_type));
        if ($query->num_rows() > 0)
        {
            $html = '';
            foreach ($query->result() as $row)
            {
                if ($row->id == $sel)
                {
                    $seletd = 'selected="selected"';
                }
                else
                {
                    $seletd = '';
                }
                $html .= '<option value="' . $row->id . '" ' . $seletd . '>' . $row->product_type . '</option>';
            }
            return $html;
        }
    }

    function update_product_slug($slug, $id)
    {
        $this->db->where('product_id', $id);
        $data['product_slug'] = $slug;
        $this->db->update($this->db->dbprefix . $this->tbl, $data);
        return true;
    }

    /**  For Product Images Insertion */
    function update_product_images($data, $imageid)
    {

        $this->db->where('image_id', $imageid);
        $this->db->update('c_product_images', $data);
    }

    /**
     * Method: update Image
     * Params: $slug
     * Return: True/False
     */
    function delete_image($image_id)
    {
        $this->db->where('image_id', $image_id);
        $this->db->delete('c_product_images');
    }

    /**  For Product Images Insertion */
    function add_product_images($data)
    {
        $insert_new = $this->db->insert('c_product_images', $data);
        return $insert_new;
    }

    /**
     * Method: remove_uploaded_file
     * Params: $images
     * Return: True/False
     */
    function remove_uploaded_file($imgId)
    {
        $this->db->where('image_id', $imgId);
        $this->db->delete('c_product_images');
        return true;
    }

    function getProductImages($id)
    {

        $query = $this->db->get_where('c_product_images', array('product_id' => $id));

        if ($query->num_rows() > 0)
        {

            return $query->result_array();
        }
    }

    function get_product_chart($product_id)
    {
        $_sql = " Select ut.* from c_usertracking as ut LEFT join c_orders as ordr ON ordr.user_tracking = ut.id where ut.product_id = " . $product_id . " AND ordr.order_status = 2  ORDER BY `id` DESC ";
        return $this->db->query($_sql)->result_array();
    }
    
    
//        private function _get_datatables_query()
//    {
//        
////         $this->db->select('user.*, GROUP_CONCAT(ordr.product_id) as product_ids,SUM(comm.total_commission) as advertiser_commission');
////        $this->db->from($this->db->dbprefix . $this->tbl . ' as  user');
////        $this->db->join($this->tbl_orders . ' as ordr', 'user.user_id = ordr.seller_id  AND ordr.order_status > 1', 'left');
////        $this->db->join($this->tbl_user_commissions . ' as comm', 'ordr.id = comm.order_id', 'left');
////        $this->db->where('user.account_type = 2 ');
////        $this->db->group_by('user.user_id');
//
//        $i = 0;
//        foreach ($this->column_search as $item)
//        {
//            if ($_POST['search']['value'])
//            {
//                if ($i === 0)
//                {
//                    $this->db->group_start();
//                    $this->db->like($item, $_POST['search']['value']);
//                }
//                else
//                {
//                    $this->db->or_like($item, $_POST['search']['value']);
//                }
//                if (count($this->column_search) - 1 == $i) //last loop
//                    $this->db->group_end();
//            }
//            $i++;
//        }
//        if (isset($_POST['order']))
//        {
//            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
//        }
//        else if (isset($this->order))
//        {
//            $order = $this->order;
//            $this->db->order_by(key($order), $order[key($order)]);
//        }
//    }
//
//    function get_datatables()
//    {
//        $this->_get_datatables_query();
//        if ($_POST['length'] != -1)
//            $this->db->limit($_POST['length'], $_POST['start']);
//        $query = $this->db->get();
//        return $query->result();
//    }
//
//    function count_filtered()
//    {
//        $this->_get_datatables_query();
//        $query = $this->db->get();
//        return $query->num_rows();
//    }
//
//    public function count_all()
//    {
//        $this->db->from($this->table);
//        return $this->db->count_all_results();
//    }

}

//End Class