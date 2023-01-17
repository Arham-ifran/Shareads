<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Api_model extends CI_Model
{

    var $tbl                     = 'users';
    var $tbl_products            = 'products';
    var $tbl_share_link          = 'products_links_share';
    var $tbl_product_type        = 'products_types';
    var $tbl_categories          = 'categories';
    var $tbl_products_commission = 'products_commission';
    var $tbl_withdraw            = 'withdraw';
    var $tbl_invoices            = 'invoices';
    var $tbl_invoice_orders      = 'invoice_orders';
    var $tbl_payment             = 'payment';
    var $tbl_orders              = 'orders';
    var $tbl_user_commissions    = 'user_commissions';
    var $tbl_helptopics          = 'helptopics';

    public function __construct()
    {
        parent::__construct();
    }

//End __construct
    /**
     * Method: register
     * Params: $data
     * Return: insert_id
     */
    function register($data_insert)
    {
        $user_name = $data_insert['first_name'] . ' ' . $data_insert['last_name'];
        $user_     = preg_replace('~[^\\pL\d]+~u', '', trim($user_name));
        $user_     = trim($user_, '');
        $user_     = iconv('utf-8', 'us-ascii//TRANSLIT', $user_);
        $user_     = strtolower($user_);
        $user_     = preg_replace('~[^-\w]+~', '', $user_);
        $sqlChk    = "SELECT user_name FROM " . $this->db->dbprefix($this->tbl) . " WHERE user_name = '" . $user_ . "'";
        $query     = $this->db->query($sqlChk);
        if ($query->num_rows() >= 1)
        {
            $rand                     = rand(1, 99999);
            $data_insert['user_name'] = $user_                    = $user_ . '_' . $rand;
            $sqlChk                   = "SELECT user_name FROM " . $this->db->dbprefix($this->tbl) . " WHERE user_name = '" . $data_insert['user_name'] . "'";
            $query                    = $this->db->query($sqlChk);
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
        $data_insert['full_name']             = $data_insert['first_name'] . ' ' . $data_insert['last_name'];
        //Save USER Data
        $data_insert['created']               = time();
        return $this->db->insert($this->db->dbprefix . $this->tbl, $data_insert);
    }

    /**
     * Method: ajaxLogin
     * params: $_POST
     * Retruns:
     */
    public function ajaxLogin($email, $passwrd)
    {
        $password = md5($passwrd);
        $this->db->select('*');
        $this->db->where('status', 1);
        $this->db->where('email', $email);
        $this->db->where('password', $password);
        $this->db->limit(1);
        $qry      = $this->db->get($this->db->dbprefix($this->tbl));
        if ($qry->num_rows() > 0)
        {
            foreach ($qry->result() as $result)
            {
                $user_session['user_id'] = $result->user_id;
            }
            $data = array(
                'last_activity_time' => time());
            $this->db->where('user_id', $user_session['user_id']);
            $this->db->update($this->db->dbprefix($this->tbl), $data);
            return $user_session['user_id'];
        }
        else
        {
            return 0;
        }
    }

    /**
     * Method: verify_email
     * Params: $fb_identifier_id
     * Return: True
     */
    function verify_email($email)
    {
        $query = " SELECT  u.user_id
                     FROM " . $this->db->dbprefix($this->tbl) . " as u
                     WHERE u.email = '" . $email . "' ";

        $result = $this->db->query($query);
        if ($result->num_rows() > 0)
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

    /**
     * Method: verify_user
     * Params: $fb_identifier_id
     * Return: True
     */
    function verify_user($connected_by_id)
    {
        $query  = " SELECT  u.user_id
                     FROM " . $this->db->dbprefix($this->tbl) . " as u
                     WHERE u.connected_by_id  = " . $connected_by_id . " ";
        $result = $this->db->query($query);
        if ($result->num_rows() > 0)
        {
            return 0;
        }
        else
        {
            return 1;
        }
    }

    /**
     * Method: verify_user_id
     * Params: $fb_identifier_id
     * Return: True
     */
    function verify_user_id($user_id)
    {
        $query  = " SELECT  u.user_id
                     FROM " . $this->db->dbprefix($this->tbl) . " as u
                     WHERE u.user_id  = " . $user_id . " ";
        $result = $this->db->query($query);
        if ($result->num_rows() > 0)
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

    /**
     * Method: get_user_data
     * Params: $user_id
     * Return: array
     */
    function get_user_data($user_id)
    {
        $query  = " SELECT
                u.user_id,
                u.first_name,
                u.last_name,
                u.full_name,
                u.account_type,
                u.user_key,
                (CASE  WHEN u.paypal_email IS NOT NULL THEN u.paypal_email  ELSE '' END) as paypal_email,
                u.email,
                u.gender,
                u.payment_type,
                u.phone,
                u.fax,
                u.currency,
                (CASE  WHEN u.account_holder_name IS NOT NULL THEN u.account_holder_name  ELSE '' END) as account_holder_name,
                (CASE  WHEN u.account_number IS NOT NULL THEN u.account_number  ELSE '' END) as account_number,
                (CASE  WHEN u.iban_code IS NOT NULL THEN u.iban_code  ELSE '' END) as iban_code,
                (CASE  WHEN u.swift_code IS NOT NULL THEN u.swift_code  ELSE '' END) as swift_code,
                (CASE  WHEN u.sort_code IS NOT NULL THEN u.sort_code  ELSE '' END) as sort_code,
                (CASE  WHEN u.bank_name IS NOT NULL THEN u.bank_name  ELSE '' END) as bank_name,
                (CASE  WHEN u.bank_address IS NOT NULL THEN u.bank_address  ELSE '' END) as bank_address,
                u.payment_schedule,
                u.city,
                u.state,
                u.address,
                u.additional_address,
                u.zip_code,
                u.country,
                u.photo,
                u.about_me,
                u.status
            FROM " . $this->db->dbprefix($this->tbl) . " as u
            WHERE u.status = 1 AND  u.user_id = " . $user_id . " limit 1 ";
        $result = $this->db->query($query);
        if ($result->num_rows() > 0)
        {
            $results                      = $result->row_array();
            $results['photo']             = $this->common->is_person_image_exist(base_url("uploads/users/medium/" . $results['photo']), $results['gender']);
            $results['payment_type']      = (int) $results['payment_type'];
            $results['payment_schedule']  = (int) $results['payment_schedule'];
            $results['currency']          = (int) $results['currency'];
            $results['can_edit_schedual'] = (int) (date('d') <= 10) ? 1 : 0;
//            $results['can_edit_schedual'] = 1;
            return $results;
        }
    }

    /**
     * Method: update_profile
     * Params: $data, $user_id
     * Return: True
     */
    function update_profile($data, $user_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->update($this->db->dbprefix($this->tbl), $data);
        return true;
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
     * Method: updateUser
     * Return: 0/1
     */
    function updateUser($email, $password)
    {
        $data                     = array();
        $data['password']         = md5($password);
        $data['orignal_password'] = $password;
        $this->db->where('email', $email);
        $this->db->update($this->db->dbprefix($this->tbl), $data);
        return true;
    }

    function getAllCategories($id)
    {
        $sql_ = 'SELECT c.category_id 	, c.category_name, c.category_slug , '
                . ' c.parent_id'
                . ' FROM ' . $this->db->dbprefix($this->tbl_categories) . ' AS c'
                . ''
                . ' WHERE c.status=1 AND c.parent_id = ' . $id . ' ORDER BY c.category_name';

        $query = $this->db->query($sql_);
        return $query->result_array();
    }

    function loadListings($data)
    {


        if ($data['limit'] == 0 || $data['limit'] == "")
        {
            $data['limit'] = 10;
        }
        if ($data['offset'] == 0 || $data['offset'] == "")
        {
            $data['offset'] = 0;
        }

        $where = ' pro.status = 1';

        if ($data['category_id'] <> '' && $data['category_id'] <> 'all')
        {
            $where .= ' AND FIND_IN_SET(' . $data['category_id'] . ',parent_categories)';
        }

        if ($data['product_type'] <> '')
        {
            $where .= ' AND product_type IN (' . $data['product_type'] . ')';
        }


        if ($data['avg_sale'] <> '')
        {
            $where .= ' AND ordr.price > ' . $data['avg_sale'] . '';
        }

        if ($data['avg_percentage'] <> '')
        {
            $where .= ' AND comm.advertiser_commission > ' . $data['avg_percentage'] . '';
        }


        if ($data['query'] <> '')
        {
            $where .= ' AND ( pro.product_name  LIKE "%' . $data['query'] . '%" ||  pro.short_description LIKE "%' . $data['query'] . '%" ||  pro.long_description LIKE "%' . $data['query'] . '%" )';
        }


        $sql_ = 'SELECT
                    pro.*,(select count(lnk.id) from
                        ' . $this->db->dbprefix . $this->tbl_share_link . ' lnk where lnk.product_id = pro.product_id
                        ) as counter, (select url from c_usertracking where REPLACE(SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING_INDEX(pro.url, "/", 3), "://", -1), "/", 1), "?", 1),"www.","") = REPLACE(SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING_INDEX(c_usertracking.url, "/", 3), "://", -1), "/", 1), "?", 1),"www.","") limit 1) as ut_url 
                FROM
                    ' . $this->db->dbprefix . $this->tbl_products . ' as pro

                    LEFT JOIN c_orders ordr ON ordr.product_id = pro.product_id
                    LEFT JOIN c_user_commissions comm ON comm.order_id = ordr.id

                        where ' . $where . '
                            GROUP BY pro.product_id
                            HAVING ut_url IS NOT NULL
		';

//        if ($data['type'] == 'all') {
//            $sql_.= "ORDER BY pro.product_id DESC";
//        } elseif ($data['type'] == 'popular') {
//            $sql_.= "ORDER BY counter DESC";
//        } elseif ($data['type'] == 'highest_paying') {
//            $sql_.= "ORDER BY pro.commission DESC";
//        } elseif ($data['type'] == 'lowest_paying') {
//           $sql_.= "ORDER BY pro.commission ASC";
//        }else{
//            $sql_.= "ORDER BY pro.product_id DESC";
//        }
        if ($data['type'] == '1')
        {
            $sql_ .= "ORDER BY pro.product_id DESC";
        }
        elseif ($data['type'] == '2')
        {
            $sql_ .= "ORDER BY counter DESC";
        }
        elseif ($data['type'] == '3')
        {
            $sql_ .= "ORDER BY pro.commission DESC";
        }
        elseif ($data['type'] == '4')
        {
            $sql_ .= "ORDER BY pro.commission ASC";
        }
        else
        {
            $sql_.= "ORDER BY pro.sequence DESC,pro.product_id DESC";
        }


        $sql_ .= " LIMIT " . $data['offset'] . ", " . $data['limit'] . "";

        // echo $sql_; exit;
        $query = $this->db->query($sql_);
        return $query->result_array();
    }

    function loadProducts($user_id, $offset, $limit)
    {

        $where = ' pro.status = 1 AND user_id = ' . $user_id;
        if ($limit == 0 || $limit == "")
        {
            $limit = 10;
        }
        if ($offset == 0 || $offset == "")
        {
            $offset = 0;
        }

        $sql_ = 'SELECT
                    pro.*,typ.product_type as pro_type,(select count(lnk.id) from
                        ' . $this->db->dbprefix . $this->tbl_share_link . ' lnk where lnk.product_id = pro.product_id
                        ) as counter,cat.category_name
                FROM
                    ' . $this->db->dbprefix . $this->tbl_products . ' as pro
                        INNER join  c_categories cat on pro.category_id = cat.category_id
                        INNER join  c_products_types typ on pro.product_type = typ.id
                        where ' . $where . '
		';

        $sql_ .= "ORDER BY pro.product_id DESC";
        $sql_ .= " LIMIT " . $offset . ", " . $limit . "";
        $query = $this->db->query($sql_);
        return $query->result_array();
    }

    /**
     * Method: getProductsTypes
     * Params: $sel
     * Return: categories
     */
    function getProductsTypes()
    {

        $this->db->select('*');
        $this->db->where('status', 1);
        $this->db->where('id <>', 2);
        $query = $this->db->get($this->db->dbprefix($this->tbl_product_type));
        if ($query->num_rows() > 0)
        {
            return $query->result_array();
        }
    }

    /**
     * Method: save_feedback
     * Params: $post
     * Return: True/False
     */
    function save_feedback($data)
    {
        $this->db->insert('c_feedback', $data);
        $id = $this->db->insert_id();
        return $id;
    }

    /**
     * Method: get_cmspage
     * Params: $data
     * Return: array
     */
    function get_allcmspage()
    {

        $sql_ = 'SELECT *'
                . ''
                . ' FROM c_contentmanagement as con'
                . ' WHERE  con.status = 1 AND is_main_page = 1 '
        ;

        $query = $this->db->query($sql_);
        if ($query->num_rows() > 0)
        {
            return $query->result_array();
        }
    }

    function get_allcmspageLinks()
    {

        $sql_ = 'SELECT con.title,CONCAT("' . base_url() . '",con.slug,"/m") as url'
                . ''
                . ' FROM c_contentmanagement as con'
                . ' WHERE  con.status = 1 AND is_main_page = 1 '
        ;

        $query = $this->db->query($sql_);
        if ($query->num_rows() > 0)
        {
            return $query->result_array();
        }
    }

    /**
     * Method: getSocialNetworks
     * Params: $data
     * Return: array
     */
    function getSocialNetworks()
    {

        $sql_ = 'SELECT  facebook,twitter,linkedin,google '
                . ''
                . ' FROM c_site_settings';

        $query = $this->db->query($sql_);
        if ($query->num_rows() > 0)
        {
            return $query->row_array();
        }
    }

    /**
     * Method: deleteItem
     * Params: $itemId
     * Return: True/False
     */
    public function delete_products($product_id, $user_id)
    {

        $this->db->where('product_id', $product_id);
        $this->db->delete($this->tbl_products_commission);

        $this->db->where('product_id', $product_id);
        $this->db->delete('c_product_images');

        $this->db->where('product_id', $product_id);
        $this->db->where('user_id', $user_id);
        $this->db->delete($this->tbl_products);
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
        $id = $post['product_id'];
        if (is_array($post))
        {
            $data_insert = array();
            foreach ($post as $k => $v)
            {
//                if ($k != 'product_id') {
                if (is_array($v))
                {
                    $data_insert[$k] = implode(',', array_filter($v));
                }
                else
                {
                    $data_insert[$k] = $v;
                }
//                }
            }
            /*             * Unset unwanted fields */
            unset($data_insert['sub_parent']);
            unset($data_insert['image_ids']);
        }
        $data_insert['parent_categories'] = $data_insert['category_id'];
        $cats                             = end(explode(',', rtrim($data_insert['category_id'], ",")));
        $data_insert['category_id']       = $cats;
        $data_insert['user_id']           = $data_insert['user_id'];
        $total_comission                  = $data_insert['commission'];
//        $data_insert['commission']        = number_format(($data_insert['commission'] * 95 / 100), 2, '.', '');
        $data_insert['commission'] = number_format(($data_insert['commission'] - ($data_insert['commission'] * (SHAREADS_COMMISION/100))), 2, '.', '');


        if ($id == '')
        {//Save Data
            $data_insert['created'] = time();
            $data_insert['status']  = 1;
            $db                     = $this->db->insert($this->db->dbprefix . $this->tbl_products, $data_insert);
            $product_id             = $this->db->insert_id();

            /*             * COMMISSION ********* */
            $comm               = array();
            $comm['product_id'] = $product_id;
            //  $comm['commission'] = $data_insert['commission'];
            $comm['commission'] = $total_comission;
            $this->db->insert($this->db->dbprefix . $this->tbl_products_commission, $comm);

            $action['id']  = $product_id;
            $action['msg'] = $db;
        }
        else
        {//Update Data
            $this->db->where('product_id', $id);
            $db = $this->db->update($this->db->dbprefix . $this->tbl_products, $data_insert);

            $action['id']  = $id;
            $action['msg'] = $db;
        }
        return $action;
    }

    
    function update_product_slug($slug, $id)
    {
        $this->db->where('product_id', $id);
        $data['product_slug'] = $slug;
        $this->db->update($this->db->dbprefix . $this->tbl_products, $data);
        return true;
    }

    function getRow($id)
    {
        $query = $this->db->get_where($this->db->dbprefix . $this->tbl_products, array('product_id' => $id));
        if ($query->num_rows() > 0)
        {
            return $query->row_array();
        }
    }

    function saveSharedLinkCopy($data)
    {
        return $this->db->insert($this->db->dbprefix . $this->tbl_share_link, $data);
    }

    /**  For Product Images Insertion */
    function add_product_images($data)
    {
        $insert_new = $this->db->insert('c_product_images', $data);
        return $insert_new;
    }

    function delete_product_images($product_id)
    {

        $this->db->select('*');
        $this->db->where('product_id', $product_id);
        $query = $this->db->get('c_product_images');
        if ($query->num_rows() > 0)
        {
            $result = $query->result_array();

            foreach ($result as $res)
            {
                unlink('uploads/products/pic/' . $res['image']);
                unlink('uploads/products/large/' . $res['image']);
                unlink('uploads/products/small/' . $res['image']);
                unlink('uploads/products/medium/' . $res['image']);
            }
        }

        $this->db->where('product_id', $product_id);
        $this->db->delete('c_product_images');
    }

    // NEW APIS :: MOHSIN :: START

    public function getWithdrawRequests($_user_id, $status = '')
    {
        $user_id = $_user_id;
        $this->db->select('amount_requested,status,created,currency');
        $this->db->from($this->db->dbprefix($this->tbl_withdraw));
        $this->db->where("affiliate_id", $user_id);
        if ($status <> '')
        {
            $this->db->where("status", $status);
        }
        $query = $this->db->get();
        $list  = $query->result_array();
        foreach ($list as $key => $value)
        {
            $currency                = getSiteCurrencySymbol('', $list[$key]['currency']);
            $list[$key]['amount']    = $currency . number_format($list[$key]['amount_requested'], 2);
            $list[$key]['status']    = (($list[$key]['status'] == 1) ? 'Completed' : 'Pending');
            $list[$key]['date_time'] = date('Y-m-d', $list[$key]['created']);

            unset($list[$key]['currency']);
            unset($list[$key]['amount_requested']);
            unset($list[$key]['created']);
        }
        return $list;
    }

    function getTotalWallet($_user_id)
    {
        $where .= " AND ordr.order_status = 2  ";
        $where .= " AND ordr.is_paid = 1  ";
        $where .= " AND ordr.is_confirmed = 1  ";
        $where .= " AND inv.status = 1 ";
        $where .= " AND DATEDIFF(CURDATE(),from_unixtime(ordr.created,'%Y-%m-%d')) > " . NO_OF_DAYS . " ";
        $user_id = $_user_id;
        $query   = "SELECT SUM(comm.advertiser_commission) as counter "
                . "FROM " . $this->db->dbprefix($this->tbl_user_commissions) . " comm
                INNER JOIN " . $this->db->dbprefix($this->tbl) . " as res ON (res.user_id = comm.user_id || comm.user_id = 0)
                LEFT JOIN " . $this->db->dbprefix($this->tbl_orders) . " as ordr ON (ordr.id = comm.order_id)                            
                LEFT JOIN c_invoice_orders as cio ON  FIND_IN_SET(ordr.id, cio.order_ids) > 0
                INNER JOIN c_invoices as inv ON inv.invoice_id = cio.invoice_id
                LEFT JOIN c_payment as payment_inv ON payment_inv.invoice_number = inv.invoice_number
                WHERE   comm.user_id = " . $user_id . "  " . $where . " ";


//        echo $query;
//        die();
        $query = $this->db->query($query);
        $row   = $query->row();
        return $row->counter;
    }

    function getTotalUnconfirmedCommission($_user_id)
    {
        $user_id = $_user_id;
        $query   = "SELECT
				  SUM(comm.advertiser_commission) as counter
				FROM
				" . $this->db->dbprefix($this->tbl_user_commissions) . " comm
 INNER JOIN
                               " . $this->db->dbprefix($this->tbl) . " as res
                        ON
                               (res.user_id = comm.user_id || comm.user_id = 0)
LEFT JOIN
                               " . $this->db->dbprefix($this->tbl_orders) . " as ordr
                        ON
                               (ordr.id = comm.order_id)

WHERE   comm.user_id = " . $user_id . "   AND comm.user_id = " . $user_id . "
                AND ordr.order_status = 2   AND ordr.is_confirmed = 1 AND ordr.is_paid = 0
    ";
//        echo $query;die();
        $query   = $this->db->query($query);
        $row     = $query->row();
        return $row->counter;
    }

    public function getWithdrawRequestsSUM($_user_id, $status = 1)
    {
        $user_id = $_user_id;
        $query   = "SELECT SUM(withdraw.amount_requested) as total_amount "
                . "FROM " . $this->db->dbprefix($this->tbl_withdraw) . " as withdraw "
                . "WHERE   withdraw.affiliate_id = " . $user_id . "  ";
        $query .= " AND withdraw.status = " . $status . "  ";
        $query .= ' HAVING SUM(withdraw.amount_requested) IS NOT NULL ';

        $query = $this->db->query($query);
        $row   = $query->row_array();
        return $row['total_amount'];
    }

    public function saveWithdrawRequest($amount_withdraw, $order_ids, $user)
    {
        $insert                     = array();
        $insert['affiliate_id']     = $user['user_id'];
        $insert['amount_requested'] = $amount_withdraw;
        $insert['orders_ids']       = $order_ids;
        $insert['payment_type']     = $user['payment_type'];
        $insert['status']           = 0;
        $insert['created']          = time();
        $insert['updated']          = time();
        $this->db->insert($this->tbl_withdraw, $insert);
        $withdraw_id                = $this->db->insert_id();
        return $withdraw_id;
    }

    function getTotalCommissionOrders($_user_id)
    {
        $user_id = $_user_id;
        $query   = "SELECT GROUP_CONCAT(ordr.id) as order_ids FROM
                    " . $this->db->dbprefix($this->tbl_user_commissions) . " comm
                    LEFT JOIN " . $this->db->dbprefix($this->tbl) . " as res
                    ON (res.user_id = comm.user_id || comm.user_id = 0) INNER JOIN
                    " . $this->db->dbprefix($this->tbl_orders) . " as ordr
                    ON (ordr.id = comm.order_id)
                    WHERE   comm.user_id = " . $user_id . " AND ordr.order_status = 2 AND ordr.is_paid = 0 
                    AND DATEDIFF(CURDATE(),from_unixtime(ordr.created,'%Y-%m-%d')) > " . NO_OF_DAYS . " Limit 1 ";

        $query  = $this->db->query($query);
        $result = $query->row_array();
        return $result['order_ids'];
    }

    function getInvoices($user_id, $perpage = 10, $offset = 0)
    {
        if ($perpage == '')
        {
            $perpage = 10;
        }
        if ($offset == '')
        {
            $offset = 0;
        }
        $where = '  invoice.publisher_id = ' . $user_id;
        $sql_  = 'SELECT
                   invoice.invoice_id,invoice.invoice_number,invoice.invoice_amount,invoice.invoice_currency,invoice.created,invoice.status from ' . $this->db->dbprefix . $this->tbl_invoices . ' as invoice 
                        where ' . $where . '  ';

        $query         = $this->db->query($sql_);
        $total_records = $query->num_rows();

        $sql_ .= " ORDER BY invoice.invoice_id DESC";
        $sql_ .= " LIMIT " . $offset . ", " . $perpage . "";
//        echo $sql_;die();
        $query = $this->db->query($sql_);
        $list  = $query->result_array();
        foreach ($list as $key => $value)
        {
//            dd($list[$key]);
            $currency                     = getSiteCurrencySymbol('', $list[$key]['invoice_currency']);
            $list[$key]['invoice_id']     = (int) $list[$key]['invoice_id'];
            $list[$key]['invoice_number'] = $list[$key]['invoice_number'];
            $list[$key]['amount']         = $currency . number_format(get_currency_rate($list[$key]['invoice_amount'], CURRENCY, $list[$key]['invoice_currency']), 2);
//            $list[$key]['amount']         = $currency . number_format($list[$key]['invoice_amount'], 2);
            $list[$key]['due_date']       = date('Y-m-d', strtotime('+7 day', $list[$key]['created']));
            $list[$key]['status']         = (($list[$key]['status'] == 1) ? 'Paid' : 'Pending');
            unset($list[$key]['invoice_currency']);
            unset($list[$key]['invoice_amount']);
            unset($list[$key]['created']);
        }
        return $list;
    }

    function getPreviousOrders($publisher_id = '', $paymentSettingType = '', $user = '', $invoice_id = '')
    {
        $ifNoInvoice      = getValArray('invoice_id', $this->db->dbprefix($this->tbl_invoices), 'publisher_id', $publisher_id);
        $invoice_details = getValArray('*', $this->db->dbprefix($this->tbl_invoices), 'invoice_id', $invoice_id);

        $invoice_order_ids = getValArray('GROUP_CONCAT(order_ids) as order_ids','c_invoice_orders', 'invoice_id', $invoice_id)['order_ids'];
        $paymentSettingType = $invoice_details['payment_schedule'];
        $invoice_currency = CURRENCY;
        if ($invoice_id <> '')
        {
            $invoice_currency = getValArray('invoice_currency', $this->db->dbprefix($this->tbl_invoices), 'invoice_id', $invoice_id)['invoice_currency'];
        }
//        dd($invoice_id);
//       dd($invoice_currency);
        $select   = $from     = $join     = $where    = $group_by = $order_by = $limit    = '';

//dd($ifNoInvoice);

        $select = 'SELECT 
            currency.currency_name,currency.currency_symbol,pro.currency as invoice_currency,
            pro.product_id as p_id,
            pro.product_name,
            products_commission.commission as prd_commission,
            SUM(user_commissions.total_commission) as total_commision_sum,
            typ.product_type as pro_type,
            orders.order_id as unique_order_id,
            GROUP_CONCAT(orders.id) as order_ids,
            orders.transaction_id as unique_transaction_id,
            count(orders.id) as counter,
            orders.created ';

        if ((trim(strtolower($paymentSettingType)) == 'month' || $paymentSettingType == 2) && (!empty($ifNoInvoice) && sizeof($ifNoInvoice) > 1))
        {
            $select .= ' , '.$invoice_details['from_datetime'].' as from_invoice_date,'.$invoice_details['to_datetime'].' as to_invoice_date  ';
        }
        else if ((trim(strtolower($paymentSettingType)) == 'biweekly' || $paymentSettingType == 1) && (!empty($ifNoInvoice) && sizeof($ifNoInvoice) > 1))
        {
            $select .= ' , '.$invoice_details['from_datetime'].' as from_invoice_date,'.$invoice_details['to_datetime'].' as to_invoice_date ';
        }
        else
        {
            if ((trim(strtolower($paymentSettingType)) == 'biweekly' || $paymentSettingType == 1))
            {
                $select .= ' , '.$invoice_details['from_datetime'].'  as from_invoice_date,'.$invoice_details['to_datetime'].' as to_invoice_date ';
            }
            else if ((trim(strtolower($paymentSettingType)) == 'month' || $paymentSettingType == 2))
            {
                $select .= ' , '.$invoice_details['from_datetime'].' as from_invoice_date,'.$invoice_details['to_datetime'].' as to_invoice_date   ';
            }
            else
            {
                $select .= '   ';
            }
        }

        $from = 'FROM '
                . 'c_products as pro ';

        $join = 'INNER join c_products_types typ on pro.product_type = typ.id
                 INNER join c_products_commission as products_commission on products_commission.product_id = pro.product_id
                 INNER join c_orders as orders on orders.product_id = pro.product_id 
                 INNER join c_currencies as currency on currency.currency_id = pro.currency 
                 LEFT join c_user_commissions as user_commissions on user_commissions.order_id = orders.id 
                 LEFT join c_users as users on users.user_id = user_commissions.user_id ';

        $where = 'WHERE 1 
                  AND orders.id IN( '.$invoice_order_ids.' )  ';

        $group_by = ' GROUP BY pro.product_id  ';
        $order_by = ' ORDER BY orders.id DESC ';
        $limit    = ' ';

        $group_by = ' GROUP BY pro.product_id  ';
        $order_by = ' ORDER BY orders.id DESC ';
        $limit    = ' ';

        $sql = $select . $from . $join . $where . $group_by . $order_by;

//        echo $sql;die();

        $query                = $this->db->query($sql);
        $query                = $query->result_array();
        $total_commission_sum = $total_share_counter  = $prd_commission       = 0;
//        dd($query);
        $product_names        = '';
        foreach ($query as $key => $value)
        {
            if ($key <> 0)
            {
                $product_names .= ',';
            }
            $prd_commission += (double) get_currency_rate($value['prd_commission'], CURRENCY, $invoice_currency);
            $total_commission_sum += (double) get_currency_rate($value['total_commision_sum'], CURRENCY, $invoice_currency);
            $total_share_counter += (int) $value['counter'];
            $product_names .= $value['product_name'];
        }

        $invoices_item = array();
//        dd($query);
        foreach ($query as $key => $value)
        {
            $invoices_item[$key]['product_type']       = $value['pro_type'];
            $invoices_item[$key]['product_name']       = $value['product_name'];
            $invoices_item[$key]['product_commission'] = getSiteCurrencySymbol('', $invoice_currency) . number_format(get_currency_rate($value['prd_commission'], $value['invoice_currency'], $invoice_currency), 2);
            $invoices_item[$key]['sales_counter']      = $value['counter'];
            $invoices_item[$key]['user_commission']    = getSiteCurrencySymbol('', $invoice_currency) . number_format(get_currency_rate($value['total_commision_sum'], $value['invoice_currency'], $invoice_currency), 2);
        }
        return array('list' => $invoices_item,
            'prd_commission' => getSiteCurrencySymbol('', $invoice_currency) . number_format($prd_commission, 2),
            'total_commission_sum' => getSiteCurrencySymbol('', $invoice_currency) . number_format($total_commission_sum, 2),
            'total_share_counter' => number_format($total_share_counter, 2),
            'item_names' => $product_names, 'invoice_currency' => $invoice_currency);
    }

    public function getInvoice($user_id, $invoice_id)
    {
        $sql_  = "SELECT * FROM " . $this->db->dbprefix($this->tbl_invoices) . " "
                . "WHERE publisher_id = " . $user_id . " AND invoice_id = " . $invoice_id . "  ";
        $query = $this->db->query($sql_);
        return $query->row_array();
    }

    function loadCommisionListings($user_id, $user, $p_id = '', $count = 10, $offset = 0)
    {

        $where .= ' where 1 ';
        $where .= ' AND pro.status = 1 AND pro.user_id = ' . $user_id;
        $where .= ' AND pro.product_id = ' . $p_id . '  ';
        $where .= ' AND orders.order_status = 2 ';
        $where .= ' AND user_commissions.is_paid = 0 ';
        $group_by = ' group by orders.id  ';
        $order_by = '  ';
        $sql_     = 'SELECT
                    pro.currency,orders.order_id as order_from_orders,orders.transaction_id,
                    (CASE  WHEN FIND_IN_SET(orders.id,invoice_orders.order_ids) THEN 1 ELSE 0 END) as invoice_generated,
                    user_commissions.total_commission as ad_commission,
                    orders.is_confirmed as ad_status,
                    orders.id as unique_id,
                    (select count(lnk.id) from ' . $this->db->dbprefix . $this->tbl_share_link . ' lnk where lnk.product_id = pro.product_id AND lnk.user_id = users.user_id) as share_counter
                    
                FROM
                    ' . $this->db->dbprefix . $this->tbl_products . ' as pro
                        INNER join  c_products_types typ on pro.product_type = typ.id
                        INNER join  c_orders as orders on orders.product_id = pro.product_id
                        LEFT join  c_user_commissions as user_commissions on user_commissions.product_id = pro.product_id
                        LEFT join  c_users as users on users.user_id = user_commissions.user_id
                        LEFT join  c_invoice_orders as invoice_orders on invoice_orders.product_id = pro.product_id
                        INNER join  c_currencies as p_currency on p_currency.currency_id = pro.currency
                        ' . $where . ' ' . $group_by . ' ' . $order_by . '   ';

        if ($count == '')
        {
            $count = 10;
        }
        if ($offset == '')
        {
            $offset = 0;
        }


        $query         = $this->db->query($sql_);
        $total_records = $query->num_rows();


        $sql_ .= "ORDER BY orders.id DESC";
        $sql_ .= " LIMIT " . $offset . ", " . $count . "";
//        echo $sql_;die();
        $query = $this->db->query($sql_);
        $query = $query->result_array();

        foreach ($query as $key => $value)
        {
            $query[$key]['order_id']          = ($query[$key]['order_from_orders'] <> '') ? $query[$key]['order_from_orders'] : 'N/A';
            $query[$key]['transaction_id']    = ($query[$key]['transaction_id'] <> '') ? $query[$key]['transaction_id'] : 'N/A';
            $query[$key]['ad_commission']     = getSiteCurrencySymbol('', $query[$key]['currency']) . number_format($value['ad_commission'], 2);
            unset($query[$key]['currency']);
            unset($query[$key]['order_from_orders']);
            $query[$key]['ad_status']         = (int) $query[$key]['ad_status'];
            $query[$key]['share_counter']     = (int) $query[$key]['share_counter'];
            $query[$key]['invoice_generated'] = (int) $query[$key]['invoice_generated'];
        }
        return $query;
    }

    public function changeAffiliateStatus($user_id, $order_id, $status)
    {
        if ($status == 1)
        {
            $status = 1;
        }
        else
        {
            $status = 0;
        }
        $this->db->where('id', $order_id);
        return $this->db->update('c_orders', array('is_confirmed' => (bool) $status));
    }

    public function update_invoice($invoice_number, $status)
    {
        $this->db->where('invoice_number', $invoice_number);
        $this->db->update($this->db->dbprefix('invoices'), array('status' => $status));
    }

    public function save_payment($return)
    {
        $user_id                          = $return['user_id'];
        $payment_insert['txn_id']         = $return['tx'];
        $payment_insert['payment_status'] = $return['st'];
        $payment_insert['payment_type']   = $return['cc'];
        $payment_insert['item_name']      = $return['item_name'];
        $payment_insert['invoice_number'] = $return['item_number'];
        $payment_insert['mobile_response'] = $return['mobile_response'];
        $payment_insert['created']        = time();
        $payment_insert['user_id']        = $user_id;
        $this->db->insert('payment', $payment_insert);

        $this->update_invoice($return['item_number'], 1);

        return 1;
    }

    public function loadSupportListing($search_support = '')
    {

        $where = '';

        if ($search_support <> '')
        {
            $where = ' AND helptopic.title like "%' . $search_support . '%" ';
        }

        $sql_    = 'SELECT
                    helptopic.id as support_id,helptopic.title
                FROM
                    ' . $this->db->dbprefix . $this->tbl_helptopics . ' as helptopic
		';
        $sql_ .= 'where helptopic.status = 1 ' . $where;
        $sql_ .= " group by helptopic.id	";
        $perpage = 10; //global_setting('perpage');
        $offset  = 0;
        if (isset($_GET['offset']))
        {
            $offset = $_GET['offset'];
        }
        else
        {
            $offset = 0;
        }
        if (isset($_GET['limit']))
        {
            $perpage = $_GET['limit'];
        }
        else
        {
            $perpage = 10;
        }

        $query         = $this->db->query($sql_);
        $total_records = $query->num_rows();
        init_front_pagination_support('support', $total_records, $perpage);
        $sql_.= " ORDER BY id DESC";

        $sql_.=" LIMIT " . $offset . ", " . $perpage . "";


        $query  = $this->db->query($sql_);
        $result = $query->result_array();
        return $result;
    }

    public function loadSupportDetails($post_id)
    {
        $sql_ = 'SELECT
                    helptopic.id as support_id,helptopic.title,helptopic.description

                FROM
                    ' . $this->db->dbprefix . $this->tbl_helptopics . ' as  helptopic ';
        $sql_ .= " where helptopic.id  = '" . $post_id . "' AND helptopic.status = 1";
        $sql_.=" LIMIT 1";

        $query = $this->db->query($sql_);
        return $query->row_array();
    }

    // NEW APIS :: MOHSIN :: END
}

//End Class