<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Announcements_model extends CI_Model {
    var $tbl = 'announcements';
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

        $sql_.= "ORDER BY ads_id DESC";
        $query = $this->db->query($sql_);
        return $query;
    }
    function getRow($id) {
        $query = $this->db->get_where($this->db->dbprefix . $this->tbl, array('ads_id' => $id));
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
    }
    function allannouncementsLocations() {
        $query = $this->db->get_where('c_announcements_destinations', array('status' => 1));
        if ($query->num_rows() > 0) {
            return $query->result_array();
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
        $this->db->where('ads_id', $itemId);
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
        $this->db->where('ads_id', $itemId);
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
    public function saveItem($post,$image) {
        $id = $post['ads_id'];
        $data_insert = array();
        if (is_array($post)) {
            foreach ($post as $k => $v) {
                if ($k != 'ads_id' && $k != 'action') {
                    $data_insert[$k] = $v;
                }
            }
        }
        if($data_insert['user_id'] == '' || $data_insert['user_id'] == 0)
        {
                $data_insert['user_id'] = $this->session->userdata('user_id');
        }
        $img = $data_insert['old_image'];
        if($image <> '')
        {
          $data_insert['images'] = $image;
          unlink('uploads/announcements/pic/' . $img);
          unset($data_insert['old_image']);
        }else{
            unset($data_insert['images']);
            unset($data_insert['old_image']);
        }
        $end_date = $data_insert['end_date'];
        unset($data_insert['end_date']);
        $data_insert['start_date'] = time();
        $data_insert['end_date'] = strtotime($end_date);

//        $data_insert['bannerCode'] = $this->common->removeHtml($data_insert['bannerCode']);

        if ($post['action'] == 'add') {//Save Data
            $data_insert['created'] = time();
            $this->db->insert($this->db->dbprefix . $this->tbl, $data_insert);
            $action = 'Record added successfully. Please wait...';
        } else {//Update Data
           if($data_insert['is_banner'] == 2)
        {
            $data_insert['url'] = '';
            $data_insert['images'] = '';
            unlink('uploads/announcements/pic/' . $img);
        }
        if($data_insert['is_banner'] == 1)
        {
            $data_insert['bannerCode'] = '';
        }
            $this->db->where('ads_id', $id);
            $this->db->update($this->db->dbprefix . $this->tbl, $data_insert);
            $action = 'Record updated successfully. Please wait...';
        }
                $msg = $action;
        return $msg;
    }
}
//End Class