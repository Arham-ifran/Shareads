<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Social_integration_model extends CI_Model {
    var $tbl = 'social_integrations';
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
        $perpage = 10; //global_setting('perpage');
        $offset = 0;
        if ($this->uri->segment(4) > 0) {
            $offset = $this->uri->segment(4);
        } else {
            $offset = 0;
        }
        $query = $this->db->query($sql_);
        $total_records = $query->num_rows();
        init_admin_pagination('admin/social_integration/index', $total_records, $perpage);
        $sql_.= "ORDER BY id DESC";
        $sql_.=" LIMIT " . $offset . ", " . $perpage . "";
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