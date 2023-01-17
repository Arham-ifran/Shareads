<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class blog_model extends CI_Model {
    var $tbl = 'blog_posts';

    var $tbl_blog_categories = 'blog_categories';
    public function __construct() {
        parent::__construct();
    }
//End __construct
 // Common Functions
    public function loadListing() {
        $sql_ = 'SELECT
                   b.*,cat.category
                FROM
                    ' . $this->db->dbprefix . $this->tbl . ' as b

                    INNER JOIN ' . $this->db->dbprefix . $this->tbl_blog_categories . ' as cat ON cat.id = b .category_id
		';
        $sql_.= "ORDER BY b.post_id asc";
        $query = $this->db->query($sql_);
        return $query;
    }
    function getRow($id) {
        $query = $this->db->get_where($this->db->dbprefix . $this->tbl, array('post_id' => $id));
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
    }
     /**
     * Method: updateItemStatus
     * Params: $itemId, $status
     */
    public function updateItemStatus($itemId, $status) {
        if ($status == 1) {
            $status = 0;
        } else {
            $status = 1;
        }
        $data_insert = array('status' => $status);
        $this->db->where('post_id', $itemId);
        $this->db->update($this->tbl, $data_insert);
        $action = 'Status updated successfully. Please wait...';
        $msg = $action;
        return $msg;
    }
     /**
     * Method: deleteItem
     * Params: $itemId
     * Return: True/False
     */
    public function deleteItem($itemId) {
        $this->db->where('post_id', $itemId);
        $this->db->delete($this->tbl);
        $error =$this->db->error();
        if ($error['code'] <> 0) {
            return false;
        } else {
            return true;
        }
    }
    /**
     * Method: checkPage
     * Params: $slug
     * Return: True/False
     */
    function checkPage($slug) {
        $sqlChk = "SELECT title FROM " . $this->db->dbprefix . $this->tbl . " WHERE title = '" . $slug . "'";
        $query = $this->db->query($sqlChk);
        if ($query->num_rows >= 1) {
            return 1;
        } else {
            return 0;
        }
    }
    /**
     * Method: saveItem
     * Params: $post
     * Return: True/False
     */
    public function saveItem($post, $image) {
        $id = $post['post_id'];
        $data_insert = array();
        if (is_array($post)) {
            foreach ($post as $k => $v) {
                if ($k != 'post_id' && $k != 'action') {
                    $data_insert[$k] = $v;
                }
            }
        }
        $data_insert['meta_keywords'] = $this->common->removeHtml($data_insert['meta_keywords']);
        $data_insert['meta_description'] = $this->common->removeHtml($data_insert['meta_description']);
         if ($image <> '') {
            $data_insert['photo'] = $image;
            unset($data_insert['old_photo']);
        } else {
            unset($data_insert['photo']);
            unset($data_insert['old_photo']);
        }
        
        if ($post['action'] == 'add') {//Save Data
            $data_insert['created'] = time();
            return $this->db->insert($this->db->dbprefix . $this->tbl, $data_insert);
        } else {//Update Data
            $this->db->where('post_id', $id);
            return $this->db->update($this->db->dbprefix . $this->tbl, $data_insert);
        }

    }
    function get_blog_categories()
    {
        $this->db->select("category,id");
        $this->db->from($this->db->dbprefix . $this->tbl_blog_categories);
        $this->db->order_by("category", "ASC");
        $this->db->where('status', 1);
        $query = $this->db->get();
        return $query->result_array();
    }


}
//End Class