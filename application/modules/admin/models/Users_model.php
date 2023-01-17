<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Users_model extends CI_Model
{

    var $tbl                       = 'users';
    var $tbl_publisher_invitations = 'publisher_invitations';
    var $tbl_site_settings         = 'site_settings';
    var $tbl_users_types           = 'users_types';
    var $tbl_orders                = 'c_orders';
    var $tbl_user_commissions      = 'c_user_commissions';
    var $column_order              = array('full_name', 'email','site_refrence');
    var $column_search             = array('full_name', 'email','site_refrence');
    var $order                     = array('user_id' => 'DESC');

    public function __construct()
    {
        parent::__construct();
    }

//End __construct
    // Common Functions
    public function loadListing_publisher()
    {
//        $sql_ = 'SELECT
//                    user.*,SUM(ordr.price) as total_price,SUM(comm.advertiser_commission) as advertiser_commission
//                FROM
//                    ' . $this->db->dbprefix . $this->tbl . ' user
//
//                    LEFT JOIN c_orders as ordr ON  ordr.seller_id = user.user_id AND ordr.order_status > 1
//                    LEFT JOIN c_user_commissions as comm ON ordr.id = comm.order_id
//
//                WHERE account_type = 2
//		';
        $sql_ = 'SELECT
                    user.*, GROUP_CONCAT(ordr.product_id) as product_ids,SUM(comm.total_commission) as advertiser_commission
                FROM
                    ' . $this->db->dbprefix . $this->tbl . ' user


                LEFT JOIN c_orders as ordr ON user.user_id = ordr.seller_id  AND ordr.order_status > 1
                LEFT JOIN c_user_commissions as comm ON ordr.id = comm.order_id

                WHERE user.account_type = 2
		';

        $sql_.= " GROUP BY user.user_id ORDER BY user.user_id DESC";
//echo $sql_;die();
        $query = $this->db->query($sql_);
        return $query;
    }

    public function loadListing_publisher_invitations()
    {
        $sql_  = 'SELECT
                    *
                FROM
                    ' . $this->db->dbprefix . $this->tbl_publisher_invitations . ' publisher_invitations
		 group by email order by id desc ';
        $query = $this->db->query($sql_);
        return $query;
    }

    public function saveItemInvitePublishers($post)
    {
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

        if ($post['action'] == 'add')
        {//Save Data
            $user_key                = $this->common->uniqueKey(5);
            $data_insert['user_key'] = $user_key;
            $data_insert['created']  = time();
            unset($data_insert['id']);
            $res                     = $this->db->insert($this->db->dbprefix . $this->tbl_publisher_invitations, $data_insert);
            $insert_id               = $this->db->insert_id();
        }
        else
        {//Update Data
            $this->db->where('id', $id);
            $this->db->update($this->db->dbprefix . $this->tbl_publisher_invitations, $data_insert);
            $insert_id = $id;
        }
        return $insert_id;
    }
    
    public function saveItemInvitePublishers_new($post)
    {
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

//        if ($post['action'] == 'add')
//        {//Save Data
            $data_insert['user_key'] = $post['user_key'];
            $data_insert['created']  = time();
            unset($data_insert['id']);
            $res                     = $this->db->insert($this->db->dbprefix . $this->tbl_publisher_invitations, $data_insert);
            $insert_id               = $this->db->insert_id();
//        }
//        else
//        {//Update Data
//            $this->db->where('id', $id);
//            $this->db->update($this->db->dbprefix . $this->tbl_publisher_invitations, $data_insert);
//            $insert_id = $id;
//        }
        return $insert_id;
    }

    public function loadListing_advertiser()
    {
//        $sql_ = 'SELECT
//                    user.*,SUM(comm.advertiser_commission) as advertiser_commission
//                FROM
//                    ' . $this->db->dbprefix . $this->tbl . ' user
//
//
//                LEFT JOIN c_orders as ordr ON user.user_id = ordr.advertiser_id AND ordr.order_status > 1
//                LEFT JOIN c_user_commissions as comm ON ordr.id = comm.order_id
//
//                WHERE account_type = 1
//		';
        $sql_ = 'SELECT
                    user.*, GROUP_CONCAT(ordr.product_id) as product_ids,SUM(comm.advertiser_commission) as advertiser_commission
                FROM
                    ' . $this->db->dbprefix . $this->tbl . ' user


                LEFT JOIN c_orders as ordr ON user.user_id = ordr.advertiser_id AND ordr.order_status > 1
                LEFT JOIN c_user_commissions as comm ON ordr.id = comm.order_id

                WHERE user.account_type = 1
		';

        $sql_.= " GROUP BY user.user_id ORDER BY user.user_id DESC";
        $query = $this->db->query($sql_);
        return $query;
    }

    public function loadOrders_advertiser($itemId)
    {
        $sql_ = 'SELECT
                    ordr.*,user.full_name,
                    comm.*,product_name,typ.product_type
                FROM
                    c_orders ordr


                INNER JOIN c_users as user ON user.user_id = ordr.advertiser_id
                INNER JOIN c_user_commissions as comm ON ordr.id = comm.order_id
                INNER JOIN c_products as pro ON ordr.product_id = pro.product_id
                INNER join  c_products_types typ on pro.product_type = typ.id

                WHERE ordr.advertiser_id = ' . $itemId . ' AND ordr.order_status > 1
		';

        $sql_.= " ORDER BY ordr.id DESC";
        $query = $this->db->query($sql_);
        return $query;
    }

    public function loadOrders_publisher($itemId)
    {
        $sql_ = 'SELECT
                    ordr.*,user.full_name,typ.product_type,
                    comm.*,product_name
                FROM
                    c_orders ordr


                INNER JOIN c_users as user ON user.user_id = ordr.advertiser_id
                INNER JOIN c_user_commissions as comm ON ordr.id = comm.order_id
                INNER JOIN c_products as pro ON ordr.product_id = pro.product_id
                INNER join  c_products_types typ on pro.product_type = typ.id

                WHERE ordr.seller_id = ' . $itemId . ' AND ordr.order_status > 1
		';

        $sql_.= " ORDER BY ordr.id DESC";
        $query = $this->db->query($sql_);
        return $query;
    }

    function getRow($id)
    {
        $sql_  = 'SELECT
                    user.*
                FROM
                    ' . $this->db->dbprefix . $this->tbl . ' user

                            where user.user_id = ' . $id . '
		';
        $query = $this->db->query($sql_);
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
//        $data_insert = array('is_active' => 1);
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
     * Method: saveItemAdvertiser
     * Params: $post
     * Return: True/False
     */
    public function saveItemAdvertiser($post, $image)
    {
        $id = $post['user_id'];

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
            $data_insert['orignal_password'] = trim($data_insert['password']);
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

        $data_insert['account_type'] = 1;
        if ($post['action'] == 'add')
        {//Save Data
            $user_key = $this->common->uniqueKey(5);
            $sqlk     = "SELECT user_key FROM " . $this->db->dbprefix($this->tbl) . " WHERE user_key = '" . $user_key . "'";
            $qu       = $this->db->query($sqlk);
            if ($qu->num_rows() >= 1)
            {
                $data_insert['user_key'] = $this->common->uniqueKey(5);
            }
            else
            {
                $data_insert['user_key'] = $user_key;
            }
            $data_insert['created'] = time();
            $res                    = $this->db->insert($this->db->dbprefix . $this->tbl, $data_insert);
            $insert_id              = $this->db->insert_id();
        }
        else
        {//Update Data
            $this->db->where('user_id', $id);
            $this->db->update($this->db->dbprefix . $this->tbl, $data_insert);
            $insert_id = $id;
        }
        return $insert_id;
    }

    /**
     * Method: saveItemPublisher
     * Params: $post
     * Return: True/False
     */
    public function saveItemPublisher($post, $image)
    {
        $id = $post['user_id'];

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
            $data_insert['orignal_password'] = trim($data_insert['password']);
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

        $data_insert['account_type'] = 2;
        if ($post['action'] == 'add')
        {//Save Data
            $user_key = $this->common->uniqueKey(5);
            $sqlk     = "SELECT user_key FROM " . $this->db->dbprefix($this->tbl) . " WHERE user_key = '" . $user_key . "'";
            $qu       = $this->db->query($sqlk);
            if ($qu->num_rows() >= 1)
            {
                $data_insert['user_key'] = $this->common->uniqueKey(5);
            }
            else
            {
                $data_insert['user_key'] = $user_key;
            }
            $data_insert['created'] = time();
            $res                    = $this->db->insert($this->db->dbprefix . $this->tbl, $data_insert);
            $insert_id              = $this->db->insert_id();
        }
        else
        {//Update Data
            $this->db->where('user_id', $id);
            $this->db->update($this->db->dbprefix . $this->tbl, $data_insert);
            $insert_id = $id;
        }
        return $insert_id;
    }

    /**
     * Method: checkEmail
     * Return: 0/1
     */
    function checkEmail($email, $id)
    {

        $wh = '';
        if ($id <> '')
        {
            $wh = "AND user_id <> " . $id;
        }
        $sql_  = "SELECT email FROM " . $this->db->dbprefix($this->tbl) . " WHERE email = '" . $email . "' " . $wh;
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

    function checkInvitationEmail($email, $id)
    {

        $wh = '';
        if ($id <> '')
        {
            $wh = "AND id <> " . $id;
        }
        $sql_   = "SELECT email FROM " . $this->db->dbprefix($this->tbl_publisher_invitations) . " WHERE email = '" . $email . "' " . $wh;
        $sql_2  = "SELECT email FROM " . $this->db->dbprefix($this->tbl) . " WHERE email = '" . $email . "' " . $wh;
        $query  = $this->db->query($sql_);
        $query2 = $this->db->query($sql_2);
        if ($query->num_rows() >= 1 || $query2->num_rows() >= 1)
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

    /**
     * Method: updateItemStatus
     * Params: $itemId, $status
     */
    public function updateUser($itemId, $password)
    {

        $data_insert = array('orignal_password' => $password, 'password' => md5($password), 'is_active' => 1);

        $this->db->where('user_id', $itemId);
        return $this->db->update($this->tbl, $data_insert);
    }

    public function deleteInvitationPublisherItem($itemId)
    {

        $this->db->where('id', $itemId);
        $this->db->delete($this->tbl_publisher_invitations);
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

    public function updateItemInvitationPublisherStatus($itemId, $status)
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
//        $data_insert = array('is_active' => 1);
        $this->db->where('id', $itemId);
        $this->db->update($this->tbl_publisher_invitations, $data_insert);
        $action      = 'Status updated successfully. Please wait...';
        $msg         = $action;
        return $msg;
    }

    function getRowIP($id)
    {
        $sql_  = 'SELECT
                    *
                FROM
                    ' . $this->db->dbprefix . $this->tbl_publisher_invitations . ' publisher_invitations

                            where publisher_invitations.id = ' . $id . '
		';
        $query = $this->db->query($sql_);
        if ($query->num_rows() > 0)
        {
            return $query->row_array();
        }
    }

    public function saveInvitation_settings($post)
    {
        $data_update = array();

        $data_update['invitation_default_title']   = $post['invitation_default_title'];
        $data_update['invitation_default_content'] = $post['invitation_default_content'];


        $this->db->where('id', 1);
        return $this->db->update($this->db->dbprefix . $this->tbl_site_settings, $data_update);
    }

    public function getInvitationList()
    {
        $query = $this->db->select('id')->where('status != 2')->get($this->tbl_publisher_invitations);
        if ($query->num_rows() > 0)
        {
            return $query->result_array();
        }
    }

    private function _get_datatables_query_adv()
    {
        $this->db->select('user.*, GROUP_CONCAT(ordr.product_id) as product_ids,SUM(comm.advertiser_commission) as advertiser_commission');
        $this->db->from($this->db->dbprefix . $this->tbl . ' as  user');
        $this->db->join($this->tbl_orders . ' as ordr', 'user.user_id = ordr.advertiser_id AND ordr.order_status > 1', 'left');
        $this->db->join($this->tbl_user_commissions . ' as comm', 'ordr.id = comm.order_id', 'left');
        $this->db->where('user.account_type = 1 ');
        $this->db->group_by('user.user_id');

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

    function get_datatables_adv()
    {
        $this->_get_datatables_query_adv();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered_adv()
    {
        $this->_get_datatables_query_adv();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all_adv()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    private function _get_datatables_query_pub()
    {
        $this->db->select('user.*, GROUP_CONCAT(ordr.product_id) as product_ids,SUM(comm.total_commission) as advertiser_commission');
        $this->db->from($this->db->dbprefix . $this->tbl . ' as  user');
        $this->db->join($this->tbl_orders . ' as ordr', 'user.user_id = ordr.seller_id  AND ordr.order_status > 1', 'left');
        $this->db->join($this->tbl_user_commissions . ' as comm', 'ordr.id = comm.order_id', 'left');
        $this->db->where('user.account_type = 2 ');
        $this->db->group_by('user.user_id');

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

    function get_datatables_pub()
    {
        $this->_get_datatables_query_pub();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered_pub()
    {
        $this->_get_datatables_query_pub();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all_pub()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

}

//End Class