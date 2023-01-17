<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Email_templates_model extends CI_Model {
    var $tbl = 'c_email_templates';
    public function __construct() {
        parent::__construct();
    }
//End __construct
 // Common Functions
    function get_all_active_email_template ($email_type)
    {
	    $this->db->select("*");
        $this->db->from('c_email_templates');
	$this->db->where('email_template_type', $email_type);
        $query = $this->db->get();
        return $query->row_array();
    }
    function add_email_template ($data)
    {
        $insert_new = $this->db->insert('c_email_templates', $data);
        return $insert_new;
    }
    function update_email_template ($data)
    {
	$this->db->where('id', $data['id']);
        return $this->db->update('c_email_templates', $data);
    }
}
//End Class