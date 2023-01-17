<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Pages_model extends CI_Model {
    var $tbl = 'contentmanagement';
    public function __construct() {
        parent::__construct();
    }
//End __construct
 // Common Functions
    public function loadListing() {
        $sql_ = 'SELECT
                    ' . $this->db->dbprefix . $this->tbl . '.*
                FROM
                    ' . $this->db->dbprefix . $this->tbl . '
		';
        $sql_.= "ORDER BY ordering asc";
        $query = $this->db->query($sql_);
        return $query;
    }
    function getRow($id) {
        $query = $this->db->get_where($this->db->dbprefix . $this->tbl, array('cmId' => $id));
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
        $this->db->where('cmId', $itemId);
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
        $this->db->where('cmId', $itemId);
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
    public function saveItem($post) {
        $id = $post['cmId'];
        $data_insert = array();
        if (is_array($post)) {
            foreach ($post as $k => $v) {
                if ($k != 'cmId' && $k != 'action') {
                    $data_insert[$k] = $v;
                }
            }
        }
        $data_insert['meta_keywords'] = $this->common->removeHtml($data_insert['meta_keywords']);
        $data_insert['meta_description'] = $this->common->removeHtml($data_insert['meta_description']);
        if($data_insert['is_main_page'] == 0)
            {
                $data_insert['page_id'] = $data_insert['page_id'];
            }else
            {
                $data_insert['page_id'] =0;
            }
        if ($post['action'] == 'add') {//Save Data
            $data_insert['created'] = time();
            return $this->db->insert($this->db->dbprefix . $this->tbl, $data_insert);
        } else {//Update Data
            $this->db->where('cmId', $id);
            return $this->db->update($this->db->dbprefix . $this->tbl, $data_insert);
        }

    }
    function get_main_pages ()
    {
        $this->db->select("title,cmId");
        $this->db->from($this->db->dbprefix . $this->tbl);
        $this->db->order_by("title", "ASC");
        $this->db->where('is_main_page', '1');
        $this->db->where('show_header', '1');
        $query = $this->db->get();
        return $query->result_array();
    }
    /**
	 * Method: updateOrder
	 * params: $id,$val
	 * returns: boolean
	 */
	public function updateOrder($id,$val){
		$record = array('ordering'=>$val);
		$this->db->set($record);
		$this->db->where('cmId', $id);
		$this->db->update($this->tbl);
		$error =$this->db->error();
                if ($error['code'] <> 0) {
                    return false;
                } else {
                    return true;
                }
	}

}
//End Class