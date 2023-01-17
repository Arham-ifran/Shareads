<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class admin_users_model extends CI_Model
{

    var $tbl           = 'admin_users';
    var $tbl_roles     = 'admin_roles';
    var $column_order  = array('full_name', 'Role','email');
    var $column_search = array('full_name', 'Role','email');
    var $order         = array('user_id' => 'DESC');

    public function __construct()
    {
        parent::__construct();
    }

//End __construct
    // Common Functions
    public function loadListing()
    {
        $sql_ = 'SELECT
                    user.*,role.role
                FROM
                    ' . $this->db->dbprefix . $this->tbl . ' user
                        inner join ' . $this->db->dbprefix . $this->tbl_roles . ' as  role on role.role_id = user.role_id
                            where user.role_id <> 0
		';

        $sql_.= "ORDER BY user_id DESC";
        $query = $this->db->query($sql_);
        return $query;
    }

    function getRow($id)
    {
        $query = $this->db->get_where($this->db->dbprefix . $this->tbl, array('user_id' => $id));
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
        $this->db->where('user_id', $itemId);
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
    public function deleteItem($itemId)
    {
        $this->db->where('user_id', $itemId);
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
    public function saveItem($post, $image)
    {
        $id          = $post['user_id'];
        $data_insert = array();
        if (is_array($post))
        {
            foreach ($post as $k => $v)
            {
                if ($k != 'user_id' && $k != 'action')
                {
                    $data_insert[$k] = $v;
                }
            }
        }

        $data_insert['full_name'] = trim($data_insert['first_name'] . ' ' . $data_insert['last_name']);
        $user_name                = $data_insert['first_name'] . '-' . $data_insert['last_name'];
        $user_                    = preg_replace('~[^\\pL\d]+~u', '-', trim($user_name));
        $user_                    = trim($user_, '-');
        $user_                    = iconv('utf-8', 'us-ascii//TRANSLIT', $user_);
        $user_                    = strtolower($user_);
        $user_                    = preg_replace('~[^-\w]+~', '', $user_);


        $sqlChk = "SELECT user_name FROM " . $this->db->dbprefix($this->tbl) . " WHERE user_name = '" . $user_ . "'";
        $query  = $this->db->query($sqlChk);
        if ($query->num_rows() >= 1)
        {
            $rand                     = rand(1, 99999);
            $data_insert['user_name'] = $user_ . '_' . $rand;

            $sqlChk = "SELECT user_name FROM " . $this->db->dbprefix($this->tbl) . " WHERE user_name = '" . $data_insert['user_name'] . "'";
            $query  = $this->db->query($sqlChk);
            if ($query->num_rows() >= 1)
            {
                $rand                     = rand(1, 999999);
                $data_insert['user_name'] = $user_ . '_' . $rand;
            }
            else
            {
                $data_insert['user_name'] = $user_;
            }
        }
        else
        {
            $data_insert['user_name'] = $user_;
        }


        if ($data_insert['password'] <> '')
        {
            $data_insert['orginal_password'] = trim($data_insert['password']);
            $data_insert['password']         = md5(trim($data_insert['password']));
            unset($data_insert['con_password']);
        }
        else
        {
            unset($data_insert['password']);
        }


        if ($image <> '')
        {
            $data_insert['photo'] = $image;
            unset($data_insert['old_photo']);
        }
        else
        {
            unset($data_insert['photo']);
            unset($data_insert['old_photo']);
        }

        if ($post['action'] == 'add')
        {//Save Data
            $data_insert['created'] = time();
            $result                 = $this->db->insert($this->db->dbprefix . $this->tbl, $data_insert);
        }
        else
        {//Update Data
            $this->db->where('user_id', $id);
            $result = $this->db->update($this->db->dbprefix . $this->tbl, $data_insert);

            if ($id == $this->session->userdata('user_id'))
            {
                $this->db->select('user_id,role_id,user_name,first_name,full_name,last_name,email,photo');
                $this->db->where('status', 1);
                $this->db->where('user_id', $id);
                $this->db->limit(1);
                $qry = $this->db->get($this->tbl);
                if ($qry->num_rows() > 0)
                {
                    foreach ($qry->result() as $result)
                    {
                        $user_id    = $result->user_id;
                        $role_id    = $result->role_id;
                        $user_name  = $result->user_name;
                        $first_name = $result->first_name;
                        $last_name  = $result->last_name;
                        $full_name  = ucwords($result->full_name);
                        $photo      = $result->photo;
                        $email      = $result->email;
                    }
                    $this->session->set_userdata(array(
                        'user_id' => $user_id,
                        'role_id' => $role_id,
                        'user_name' => $user_name,
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'email' => $email,
                        'full_name' => $full_name,
                        'photo' => $photo,
                            )
                    );
                }
            }
        }
        return $result;
    }

    function get_roles()
    {
        $this->db->select("role_id,role");
        $this->db->from($this->db->dbprefix . $this->tbl_roles);
        $this->db->order_by("role", "ASC");
        $this->db->where('role_id <>', '0');
        $this->db->where('status', '1');
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Method: checkEmail
     * Return: 0/1
     */
    function checkEmail($email)
    {

        $sql_  = "SELECT email FROM " . $this->db->dbprefix($this->tbl) . " WHERE email = '" . $email . "'";
        $query = $this->db->query($sql_);
        if ($query->num_rows() >= 1)
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

    private function _get_datatables_query()
    {
        $this->db->select('user.*,role.role');
        $this->db->from($this->db->dbprefix . $this->tbl . ' as  user');
        $this->db->join($this->tbl_roles.' as role','role.role_id = user.role_id','inner');
        $this->db->where('user.role_id <> 0');

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