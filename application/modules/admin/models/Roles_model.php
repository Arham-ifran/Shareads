<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Roles_model extends CI_Model
{

    var $tbl            = 'admin_users';
    var $tbl_roles      = 'admin_roles';
    var $tbl_rights     = 'admin_rights';
    var $tbl_permission = 'admin_permissions';
    
    var $tbl_permissions = 'admin_permissions';
    var $table = 'admin_roles';
    var $column_order = array('Role', 'Status');
    var $column_search = array('Role', 'Status');
    var $order = array('role_id' => 'DESC');


    public function __construct()
    {
        parent::__construct();
    }

//End __construct
    // Common Functions
    public function loadListing()
    {
        $sql_ = 'SELECT
                    role.*
                FROM
                    ' . $this->db->dbprefix . $this->tbl_roles . ' role
                        where role_id <> 0
		';

        $sql_.= "ORDER BY role_id DESC";
        $query = $this->db->query($sql_);
        return $query;
    }

    function getRow($id)
    {

        $query = "SELECT roles.`role_id`, GROUP_CONCAT(right_id) AS right_ids, roles.`role`,roles.status "
                . " FROM " . $this->db->dbprefix . $this->tbl_roles . " roles "
                . " JOIN `c_admin_permissions` ON `c_admin_permissions`.`role_id` = `roles`.`role_id` "
                . " WHERE roles.`role_id` =  " . $id;
        $query = $this->db->query($query);

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
        $this->db->where('role_id', $itemId);
        $this->db->update($this->tbl_roles, $data_insert);
        $action      = 'Status updated successfully. Please wait...';
        $msg         = $action;
        return $msg;
    }

    /**
     * Method: deleteItem
     * Params: $itemId
     * Return: True/False
     */
    public function deleteItem($itemId)
    {
        $this->db->where('role_id', $itemId);
        $this->db->delete($this->tbl_permission);

        $this->db->where('role_id', $itemId);
        $this->db->delete($this->tbl_roles);
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
        $id                  = $post['role_id'];
        $data_insert         = array();
        $data_insert['role'] = $post['role'];

        if ($post['action'] == 'add')
        {//Save Data
            $db              = $this->db->insert($this->db->dbprefix . $this->tbl_roles, $data_insert);
            $insert_id       = $this->db->insert_id();
            $data_permission = array();
            unset($post['role']);
            unset($post['role_id']);
            unset($post['action']);
            unset($post['status']);
            foreach ($post as $key => $value)
            {
                $data_permission['role_id']   = $insert_id;
                $data_permission ['right_id'] = $key;
                $this->db->insert($this->db->dbprefix . $this->tbl_permission, $data_permission);
            }
            return $db;
        }
        else
        {//Update Data
            $this->db->where('role_id', $id);
            $db = $this->db->update($this->db->dbprefix . $this->tbl_roles, $data_insert);

            $this->db->where('role_id', $id);
            $this->db->delete($this->tbl_permission);

            $data_permission = array();
            unset($post['role']);
            unset($post['role_id']);
            unset($post['action']);
            unset($post['status']);
            foreach ($post as $key => $value)
            {
                $data_permission['role_id']   = $id;
                $data_permission ['right_id'] = $key;
                $this->db->insert($this->db->dbprefix . $this->tbl_permission, $data_permission);
            }
            return $db;
        }
    }

    function rights()
    {
        $this->db->select('c_admin_rights.*');
        $this->db->select('c_admin_modules.module');
        $this->db->join('c_admin_modules', 'c_admin_modules.module_id = c_admin_rights.module_id', 'left');
        $this->db->where('c_admin_rights.status', 1);
        $this->db->where('c_admin_modules.status', 1);
        $this->db->order_by("c_admin_modules.module_id", "asc");
        $this->db->order_by("c_admin_rights.id", "asc");
        $query = $this->db->get('c_admin_rights');
        return $query->result_array();
    }

    private function _get_datatables_query()
    {
        $this->db->from($this->table)->where('role_id <> 0');
        $i = 0;
        foreach ($this->column_search as $item)
        {
            if ($_POST['search']['value'])
            {
                if ($i === 0)
                {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end();
            }
            $i++;
        }
        if (isset($_POST['order']))
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }
        else if (isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

}

//End Class