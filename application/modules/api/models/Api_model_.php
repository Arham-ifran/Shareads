<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Api_model extends CI_Model {

    var $tbl = 'users';
    var $tbl_products = 'products';
    var $tbl_share_link = 'products_links_share';
    var $tbl_product_type = 'products_types';
    var $tbl_categories = 'categories';
    var $tbl_products_commission = 'products_commission';

    public function __construct() {
        parent::__construct();
    }

//End __construct
    /**
     * Method: register
     * Params: $data
     * Return: insert_id
     */
    function register($data_insert) {
        $user_name = $data_insert['first_name'] . ' ' . $data_insert['last_name'];
        $user_ = preg_replace('~[^\\pL\d]+~u', '', trim($user_name));
        $user_ = trim($user_, '');
        $user_ = iconv('utf-8', 'us-ascii//TRANSLIT', $user_);
        $user_ = strtolower($user_);
        $user_ = preg_replace('~[^-\w]+~', '', $user_);
        $sqlChk = "SELECT user_name FROM " . $this->db->dbprefix($this->tbl) . " WHERE user_name = '" . $user_ . "'";
        $query = $this->db->query($sqlChk);
        if ($query->num_rows() >= 1) {
            $rand = rand(1, 99999);
            $data_insert['user_name'] = $user_ = $user_ . '_' . $rand;
            $sqlChk = "SELECT user_name FROM " . $this->db->dbprefix($this->tbl) . " WHERE user_name = '" . $data_insert['user_name'] . "'";
            $query = $this->db->query($sqlChk);
            if ($query->num_rows() >= 1) {
                $rand = rand(1, 999999);
                $data_insert['user_name'] = $user_ . '_' . $rand;
            }
        } else {
            $data_insert['user_name'] = $user_;
        }
        $user_key = $this->common->uniqueKey(5);
        $sqlk = "SELECT user_key FROM " . $this->db->dbprefix($this->tbl) . " WHERE user_key = '" . $user_key . "'";
        $qu = $this->db->query($sqlk);
        if ($qu->num_rows() >= 1) {
            $data_insert['user_key'] = $this->common->uniqueKey(5);
        } else {
            $data_insert['user_key'] = $user_key;
        }
        $data_insert['newsletter_subscriber'] = 1;
        $data_insert['full_name'] = $data_insert['first_name'] . ' ' . $data_insert['last_name'];
        //Save USER Data
        $data_insert['created'] = time();
        return $this->db->insert($this->db->dbprefix . $this->tbl, $data_insert);
    }

    /**
     * Method: ajaxLogin
     * params: $_POST
     * Retruns:
     */
    public function ajaxLogin($email, $passwrd) {
        $password = md5($passwrd);
        $this->db->select('*');
        $this->db->where('status', 1);
        $this->db->where('email', $email);
        $this->db->where('password', $password);
        $this->db->limit(1);
        $qry = $this->db->get($this->db->dbprefix($this->tbl));
        if ($qry->num_rows() > 0) {
            foreach ($qry->result() as $result) {
                $user_session['user_id'] = $result->user_id;
            }
            $data = array(
                'last_activity_time' => time());
            $this->db->where('user_id', $user_session['user_id']);
            $this->db->update($this->db->dbprefix($this->tbl), $data);
            return $user_session['user_id'];
        } else {
            return 0;
        }
    }

    /**
     * Method: verify_email
     * Params: $fb_identifier_id
     * Return: True
     */
    function verify_email($email) {
        $query = " SELECT  u.user_id
                     FROM " . $this->db->dbprefix($this->tbl) . " as u
                     WHERE u.email = '" . $email . "' ";

        $result = $this->db->query($query);
        if ($result->num_rows() > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * Method: verify_user
     * Params: $fb_identifier_id
     * Return: True
     */
    function verify_user($connected_by_id) {
        $query = " SELECT  u.user_id
                     FROM " . $this->db->dbprefix($this->tbl) . " as u
                     WHERE u.connected_by_id  = " . $connected_by_id . " ";
        $result = $this->db->query($query);
        if ($result->num_rows() > 0) {
            return 0;
        } else {
            return 1;
        }
    }

    /**
     * Method: verify_user_id
     * Params: $fb_identifier_id
     * Return: True
     */
    function verify_user_id($user_id) {
        $query = " SELECT  u.user_id
                     FROM " . $this->db->dbprefix($this->tbl) . " as u
                     WHERE u.user_id  = " . $user_id . " ";
        $result = $this->db->query($query);
        if ($result->num_rows() > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * Method: get_user_data
     * Params: $user_id
     * Return: array
     */
    function get_user_data($user_id) {
        $query = " SELECT
                u.user_id,
                u.first_name,
                u.last_name,
                u.full_name,
                u.account_type,
                u.user_key,
                u.paypal_email,
                u.email,
                u.gender,
                u.phone,
                u.fax,
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
        if ($result->num_rows() > 0) {
            $results = $result->row_array();
            $results['photo'] = $this->common->is_person_image_exist(base_url("uploads/users/medium/" . $results['photo']), $results['gender']);
            return $results;
        }
    }

    /**
     * Method: update_profile
     * Params: $data, $user_id
     * Return: True
     */
    function update_profile($data, $user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->update($this->db->dbprefix($this->tbl), $data);
        return true;
    }

    /**
      @Method: subscribe_now
      @Retrun: insert_id
     * */
    function subscribe_now($data) {
        $this->db->select('email');
        $this->db->where(array('email' => $data ['email']));
        $query = $this->db->get('c_newlettter_subscribers');
        if ($query->num_rows() > 0) {
            return 0;
        } else {
            $this->db->insert('c_newlettter_subscribers', $data);
            return $insert_id = $this->db->insert_id();
        }
    }

    function checkSubscribeEmail($chimp_email) {
        $this->db->select('email');
        $this->db->where(array('email' => $chimp_email));
        $query = $this->db->get('c_newlettter_subscribers');
        if ($query->num_rows() > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * Method: updateUser
     * Return: 0/1
     */
    function updateUser($email, $password) {
        $data = array();
        $data['password'] = md5($password);
        $data['orignal_password'] = $password;
        $this->db->where('email', $email);
        $this->db->update($this->db->dbprefix($this->tbl), $data);
        return true;
    }

    function getAllCategories($id) {
        $sql_ = 'SELECT c.category_id 	, c.category_name, c.category_slug , '
                . ' c.parent_id'
                . ' FROM ' . $this->db->dbprefix($this->tbl_categories) . ' AS c'
                . ''
                . ' WHERE c.status=1 AND c.parent_id = ' . $id . ' ORDER BY c.category_name';

        $query = $this->db->query($sql_);
        return $query->result_array();
    }

    function loadListings($data) {


        if ($data['limit'] == 0 || $data['limit'] == "") {
            $data['limit'] = 10;
        }
        if ($data['offset'] == 0 || $data['offset'] == "") {
            $data['offset'] = 0;
        }

        $where = ' pro.status = 1';

        if ($data['category_id'] <> '' && $data['category_id'] <> 'all') {
            $where .= ' AND FIND_IN_SET(' . $data['category_id'] . ',parent_categories)';
        }

        if ($data['product_type'] <> '') {
            $where .= ' AND product_type IN (' . $data['product_type'] . ')';
        }


        if ($data['avg_sale'] <> '') {
            $where .= ' AND ordr.price > ' . $data['avg_sale'] . '';
        }

        if ($data['avg_percentage'] <> '') {
            $where .= ' AND comm.advertiser_commission > ' . $data['avg_percentage'] . '';
        }


        if ($data['query'] <> '') {
            $where .= ' AND ( pro.product_name  LIKE "%' . $data['query'] . '%" ||  pro.short_description LIKE "%' . $data['query'] . '%" ||  pro.long_description LIKE "%' . $data['query'] . '%" )';
        }


        $sql_ = 'SELECT
                    pro.*,(select count(lnk.id) from
                        ' . $this->db->dbprefix . $this->tbl_share_link . ' lnk where lnk.product_id = pro.product_id
                        ) as counter
                FROM
                    ' . $this->db->dbprefix . $this->tbl_products . ' as pro

                    LEFT JOIN c_orders ordr ON ordr.product_id = pro.product_id
                    LEFT JOIN c_user_commissions comm ON comm.order_id = ordr.id

                        where ' . $where . '
                            GROUP BY pro.product_id
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
        if ($data['type'] == '1') {
            $sql_.= "ORDER BY pro.product_id DESC";
        } elseif ($data['type'] == '2') {
            $sql_.= "ORDER BY counter DESC";
        } elseif ($data['type'] == '3') {
            $sql_.= "ORDER BY pro.commission DESC";
        } elseif ($data['type'] == '4') {
            $sql_.= "ORDER BY pro.commission ASC";
        } else {
            $sql_.= "ORDER BY pro.product_id DESC";
        }


        $sql_.=" LIMIT " . $data['offset'] . ", " . $data['limit'] . "";

       // echo $sql_; exit;
        $query = $this->db->query($sql_);
        return $query->result_array();
    }

    function loadProducts($user_id, $offset, $limit) {

        $where = ' pro.status = 1 AND user_id = ' . $user_id;
        if ($limit == 0 || $limit == "") {
            $limit = 10;
        }
        if ($offset == 0 || $offset == "") {
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

        $sql_.= "ORDER BY pro.product_id DESC";
        $sql_.=" LIMIT " . $offset . ", " . $limit . "";
        $query = $this->db->query($sql_);
        return $query->result_array();
    }

    /**
     * Method: getProductsTypes
     * Params: $sel
     * Return: categories
     */
    function getProductsTypes() {

        $this->db->select('*');
        $this->db->where('status', 1);
        $this->db->where('id <>', 2);
        $query = $this->db->get($this->db->dbprefix($this->tbl_product_type));
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
    }

    /**
     * Method: save_feedback
     * Params: $post
     * Return: True/False
     */
    function save_feedback($data) {
        $this->db->insert('c_feedback', $data);
        $id = $this->db->insert_id();
        return $id;
    }

    /**
     * Method: get_cmspage
     * Params: $data
     * Return: array
     */
    function get_allcmspage() {

        $sql_ = 'SELECT *'
                . ''
                . ' FROM c_contentmanagement as con'
                . ' WHERE  con.status = 1 AND is_main_page = 1 '
        ;

        $query = $this->db->query($sql_);
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
    }

    /**
     * Method: getSocialNetworks
     * Params: $data
     * Return: array
     */
    function getSocialNetworks() {

        $sql_ = 'SELECT  facebook,twitter,linkedin,google '
                . ''
                . ' FROM c_site_settings';

        $query = $this->db->query($sql_);
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
    }

    /**
     * Method: deleteItem
     * Params: $itemId
     * Return: True/False
     */
    public function delete_products($product_id, $user_id) {

        $this->db->where('product_id', $product_id);
        $this->db->delete($this->tbl_products_commission);

        $this->db->where('product_id', $product_id);
        $this->db->delete('c_product_images');

        $this->db->where('product_id', $product_id);
        $this->db->where('user_id', $user_id);
        $this->db->delete($this->tbl_products);
        $error = $this->db->error();
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
        $id = $post['product_id'];
        if (is_array($post)) {
            $data_insert = array();
            foreach ($post as $k => $v) {
//                if ($k != 'product_id') {
                if (is_array($v)) {
                    $data_insert[$k] = implode(',', array_filter($v));
                } else {
                    $data_insert[$k] = $v;
                }
//                }
            }
            /*             * Unset unwanted fields */
            unset($data_insert['sub_parent']);
            unset($data_insert['image_ids']);
        }
        $data_insert['parent_categories'] = $data_insert['category_id'];
        $cats = end(explode(',', rtrim($data_insert['category_id'], ",")));
        $data_insert['category_id'] = $cats;
        $data_insert['user_id'] = $data_insert['user_id'];



        if ($id == '') {//Save Data
            $data_insert['created'] = time();
            $data_insert['status'] = 1;
            $db = $this->db->insert($this->db->dbprefix . $this->tbl_products, $data_insert);
            $product_id = $this->db->insert_id();

            /*             * COMMISSION ********* */
            $comm = array();
            $comm['product_id'] = $product_id;
            $comm['commission'] = $data_insert['commission'];
            $this->db->insert($this->db->dbprefix . $this->tbl_products_commission, $comm);

            $action['id'] = $product_id;
            $action['msg'] = $db;
        } else {//Update Data
            $this->db->where('product_id', $id);
            $db = $this->db->update($this->db->dbprefix . $this->tbl_products, $data_insert);

            $action['id'] = $id;
            $action['msg'] = $db;
        }
        return $action;
    }

    function update_product_slug($slug, $id) {
        $this->db->where('product_id', $id);
        $data['product_slug'] = $slug;
        $this->db->update($this->db->dbprefix . $this->tbl_products, $data);
        return true;
    }

    function getRow($id) {
        $query = $this->db->get_where($this->db->dbprefix . $this->tbl_products, array('product_id' => $id));
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
    }

    function saveSharedLinkCopy($data) {
        return $this->db->insert($this->db->dbprefix . $this->tbl_share_link, $data);
    }

}

//End Class