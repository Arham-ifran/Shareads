<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Register_model extends CI_Model
{

    var $tbl                       = 'users';
    var $tbl_publisher_invitations = 'publisher_invitations';

    public function __construct()
    {
        parent::__construct();
    }

//End __construct

    /**
     * Method: saveItem
     * Params: $post
     * Return: True/False
     */
    public function saveItem($post)
    {

        $data_insert = array();
        if (is_array($post))
        {
            foreach ($post as $k => $v)
            {
                if ($k != 'action')
                {
                    $data_insert[$k] = $v;
                }
            }
        }

        $user_name = $data_insert['first_name'] . ' ' . $data_insert['last_name'];
        $user_     = preg_replace('~[^\\pL\d]+~u', '', trim($user_name));
        $user_     = trim($user_, '');
        $user_     = iconv('utf-8', 'us-ascii//TRANSLIT', $user_);
        $user_     = strtolower($user_);
        $user_     = preg_replace('~[^-\w]+~', '', $user_);


        $sqlChk = "SELECT user_name FROM " . $this->db->dbprefix($this->tbl) . " WHERE user_name = '" . $user_ . "'";
        $query  = $this->db->query($sqlChk);

        if ($query->num_rows() >= 1)
        {
            $rand                     = rand(1, 99999);
            $data_insert['user_name'] = $user_                    = $user_ . '_' . $rand;

            $sqlChk = "SELECT user_name FROM " . $this->db->dbprefix($this->tbl) . " WHERE user_name = '" . $data_insert['user_name'] . "'";
            $query  = $this->db->query($sqlChk);
            if ($query->num_rows() >= 1)
            {
                $rand                     = rand(1, 999999);
                $data_insert['user_name'] = $user_ . '_' . $rand;
            }
        }
        else
        {
            $data_insert['user_name'] = $user_;
        }


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

        $data_insert['newsletter_subscriber'] = 1;
        $data_insert['user_ip']               = get_client_ip();
        $data_insert['unique_device']         = get_unique_device();

        $data_insert['full_name'] = $data_insert['first_name'] . ' ' . $data_insert['last_name'];

        if ($this->uri->segment(2) == 'publisher')
        {
            $data_insert['account_type'] = 2;
        }
        else
        {
            $data_insert['account_type'] = 1;
        }
        //Save USER Data
        $data_insert['created'] = time();
        $data_insert['status']  = 0;
        $result                 = $this->db->insert($this->db->dbprefix . $this->tbl, $data_insert);


        return $result;
    }

    public function saveItemInvitors($post)
    {

        $data_insert = array();
        if (is_array($post))
        {
            foreach ($post as $k => $v)
            {
                if ($k != 'action')
                {
                    $data_insert[$k] = $v;
                }
            }
        }

        $data_insert['orignal_password'] = $post['password'];
        $data_insert['password']         = md5($data_insert['password']);

        $user_name = $data_insert['first_name'] . ' ' . $data_insert['last_name'];
        $user_     = preg_replace('~[^\\pL\d]+~u', '', trim($user_name));
        $user_     = trim($user_, '');
        $user_     = iconv('utf-8', 'us-ascii//TRANSLIT', $user_);
        $user_     = strtolower($user_);
        $user_     = preg_replace('~[^-\w]+~', '', $user_);


        $sqlChk = "SELECT user_name FROM " . $this->db->dbprefix($this->tbl) . " WHERE user_name = '" . $user_ . "'";
        $query  = $this->db->query($sqlChk);

        if ($query->num_rows() >= 1)
        {
            $rand                     = rand(1, 99999);
            $data_insert['user_name'] = $user_                    = $user_ . '_' . $rand;

            $sqlChk = "SELECT user_name FROM " . $this->db->dbprefix($this->tbl) . " WHERE user_name = '" . $data_insert['user_name'] . "'";
            $query  = $this->db->query($sqlChk);
            if ($query->num_rows() >= 1)
            {
                $rand                     = rand(1, 999999);
                $data_insert['user_name'] = $user_ . '_' . $rand;
            }
        }
        else
        {
            $data_insert['user_name'] = $user_;
        }


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

        $data_insert['newsletter_subscriber'] = 1;

        $data_insert['full_name'] = $data_insert['first_name'] . ' ' . $data_insert['last_name'];

        if ($this->uri->segment(2) == 'publisher' || $this->uri->segment(2) == 'ipublisher')
        {
            $data_insert['account_type'] = 2;
        }
        else
        {
            $data_insert['account_type'] = 1;
        }
        //Save USER Data
        $data_insert['is_active']     = 1;
        $data_insert['user_ip']       = get_client_ip();
        $data_insert['unique_device'] = get_unique_device();
        $data_insert['created']       = time();
        $data_insert['status']        = 1;

        $result = $this->db->insert($this->db->dbprefix . $this->tbl, $data_insert);
        unset($_COOKIE['link_signup_userkey']);
        setcookie('link_signup_userkey', null, -1, '/');

        return $result;
    }

    /**
     * Method: checkEmail
     * Return: 0/1
     */
    function checkEmail($email)
    {

        $sql_  = "SELECT email FROM " . $this->db->dbprefix($this->tbl) . " WHERE email = '" . $email . "' ";
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

    function checkDeviceUnique($email)
    {
        $sql_  = "SELECT email FROM " . $this->db->dbprefix($this->tbl) . " WHERE email = '" . $email . "' OR unique_device = '" . get_unique_device() . "'";
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

    function getInvitationUser($user_key)
    {

        $sql_  = "SELECT first_name,last_name,full_name,email FROM " . $this->db->dbprefix($this->tbl_publisher_invitations) . " WHERE user_key = '" . $user_key . "' and (status = 0 || status = 1) ";
        $query = $this->db->query($sql_);
        if ($query->num_rows() >= 1)
        {
            return $query->row_array();
        }
        else
        {
            return [];
        }
    }

    function checkSubscribeEmail($chimp_email)
    {

        $this->db->select('email');
        $this->db->where(array('email' => $chimp_email));
        $query = $this->db->get('c_newlettter_subscribers');
        if ($query->num_rows() > 0)
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

    /**
      @Method: subscribe_now
      @Retrun: insert_id
     * */
    function subscribe_now($data)
    {
        $this->db->select('email');
        $this->db->where(array('email' => $data ['email']));
        $query = $this->db->get('c_newlettter_subscribers');
        if ($query->num_rows() > 0)
        {
            return 0;
        }
        else
        {
            $this->db->insert('c_newlettter_subscribers', $data);
            return $insert_id = $this->db->insert_id();
        }
    }

    function getOrderStatus($user_id, $product_id)
    {

        $query = "select * from c_orders where advertiser_id = " . $user_id . " and product_id = " . $product_id . " and is_paid = 0 and order_status = 1 ORDER BY id desc limit 1";
        $query = $this->db->query($query);
        if ($query->num_rows() > 0)
        {
            return $query->row_array();
        }
        else
        {
            return 0;
        }
    }

    function updateOrderStatus($status)
    {
        $this->db->where('id', $status['id']);
        return $this->db->update('c_orders', array('order_status' => '2', 'order_id' => generateRandomString(), 'transaction_id' => generateRandomString(), 'sale_ip' => get_client_ip()));
    }

}

//End Class