<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class payment_integration_model extends CI_Model {
    var $tbl = 'payment_integration';
    public function __construct() {
        parent::__construct();
    }
//End __construct
 // Common Functions
    public function loadListing() {
         $sql_ = 'SELECT
                    pkg.*
                FROM
                    ' . $this->db->dbprefix . $this->tbl . ' as pkg
		';

        $sql_.= "ORDER BY id DESC";
        $query = $this->db->query($sql_);
        return $query;
    }
    function getRow($id) {
        $query = $this->db->get_where($this->db->dbprefix . $this->tbl, array('id' => $id));
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
        $this->db->where('id', $itemId);
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

        $this->db->where('id', $itemId);
        $this->db->delete($this->tbl);
        $error =$this->db->error();
        if ($error['code'] <> 0) {
            return false;
        } else {
            return true;
        }
    }
    /**
     * Method: saveItem
     * Params: $post
     * Return: True/False
     */
    public function saveItem($post) {
        $id = $post['id'];
        $data_insert = array();
        if (is_array($post)) {
            foreach ($post as $k => $v) {
                if ($k != 'id' && $k != 'action') {
                    $data_insert[$k] = $v;
                }
            }
        }
        if ($post['action'] == 'add') {//Save Data
            $this->db->insert($this->db->dbprefix . $this->tbl, $data_insert);
            $action = 'Record added successfully. Please wait...';
        } else {//Update Data
            $this->db->where('id', $id);
            $this->db->update($this->db->dbprefix . $this->tbl, $data_insert);
            $action = 'Record updated successfully. Please wait...';
        }
                $msg = $action;
        return $msg;
    }
}
//End Class