<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard_model extends CI_Model
{

    var $tbl                      = 'users';
    var $tbl_products             = 'products';
    var $tbl_usertracking         = 'usertracking';
    var $tbl_orders               = 'orders';
    var $tbl_products_links_share = 'products_links_share';
    var $tbl_user_commissions     = 'user_commissions';

    public function __construct()
    {
        parent::__construct();
    }

//End __construct
    /**
     * Method: updateProfileDetail
     * Params: $post
     * Return: True/False
     */
    public function updateProfileDetail($post, $image)
    {
        $id          = $this->session->userdata('user_id');
        $data_insert = array();
        if (is_array($post))
        {
            foreach ($post as $k => $v)
            {
                $data_insert[$k] = $v;
            }
        }

        $data_insert['full_name'] = trim($data_insert['first_name'] . ' ' . $data_insert['last_name']);
        $user_name                = $data_insert['first_name'] . '-' . $data_insert['last_name'];
        $user_                    = preg_replace('~[^\\pL\d]+~u', '-', trim($user_name));
        $user_                    = trim($user_, '-');
        $user_                    = iconv('utf-8', 'us-ascii//TRANSLIT', $user_);
        $user_                    = strtolower($user_);
        $user_                    = preg_replace('~[^-\w]+~', '', $user_);


        $sqlChk = "SELECT user_name FROM " . $this->db->dbprefix($this->tbl) . " WHERE user_name = '" . $user_ . "' and user_id <> " . $id;
        $query  = $this->db->query($sqlChk);
        if ($query->num_rows() >= 1)
        {
            $rand                     = rand(1, 99999);
            $data_insert['user_name'] = $user_ . '_' . $rand;

            $sqlChk = "SELECT user_name FROM " . $this->db->dbprefix($this->tbl) . " WHERE user_name = '" . $data_insert['user_name'] . "' and user_id <> " . $id;
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

//        if ($data_insert['password'] <> '' && $data_insert['con_password'] <> '') {
//            $data_insert['orignal_password'] = trim($data_insert['password']);
//            $data_insert['password'] = md5(trim($data_insert['password']));
//            unset($data_insert['con_password']);
//        } else {
//            unset($data_insert['con_password']);
//            unset($data_insert['password']);
//        }
        
        
        

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

        //Update Data
        $this->session->set_userdata('currency', $data_insert['currency']);
        $this->db->where('user_id', $id);
        $db = $this->db->update($this->db->dbprefix . $this->tbl, $data_insert);

        $this->db->select('*');
        $this->db->where('user_id', $id);
        $this->db->limit(1);
        $qry = $this->db->get($this->db->dbprefix($this->tbl));
        if ($qry->num_rows() > 0)
        {
            foreach ($qry->result() as $result)
            {
                $user_session['first_name'] = $result->first_name;
                $user_session['last_name']  = $result->last_name;
                $user_session['full_name']  = ucwords($result->full_name);
                $user_session['user_name']  = ucwords($result->user_name);
                $user_session['email']      = $result->email;
                $user_session['photo']      = $result->photo;
                $user_session['gender']     = $result->gender;
            }
            $this->session->set_userdata($user_session);
        }
        return $db;
    }

    function getTotalLinksShared($user_id)
    {
        $query = "SELECT
				  SUM(share_counter) as counter
				FROM
				" . $this->db->dbprefix($this->tbl_products_links_share) . " shr

 INNER JOIN
                               " . $this->db->dbprefix($this->tbl) . " as res
                        ON
                               (res.user_id = shr.user_id)

WHERE   shr.user_id = " . $user_id . " AND
    (shr.created >= UNIX_TIMESTAMP(LAST_DAY(CURDATE()) + INTERVAL 1 DAY - INTERVAL 1 MONTH)
   AND shr.created <  UNIX_TIMESTAMP(LAST_DAY(CURDATE()) + INTERVAL 1 DAY) )
          limit 1";
        $query = $this->db->query($query);
        $row   = $query->row();
        return $row->counter;
    }

    function getTotalVisitors($user_id)
    {

        $query = "SELECT
			 DISTINCT shr.product_id
				FROM
				" . $this->db->dbprefix($this->tbl_products_links_share) . " shr
 INNER JOIN
                               " . $this->db->dbprefix($this->tbl) . " as res
                        ON
                               (res.user_id = shr.user_id)

WHERE   shr.user_id = " . $user_id . "
    AND
    (shr.created >= UNIX_TIMESTAMP(LAST_DAY(CURDATE()) + INTERVAL 1 DAY - INTERVAL 1 MONTH)
   AND shr.created <  UNIX_TIMESTAMP(LAST_DAY(CURDATE()) + INTERVAL 1 DAY) )
          ";
        $q     = $this->db->query($query);
        if ($q->num_rows() > 0)
        {
            $result = $q->result_array();
            $arr    = array();
            foreach ($result as $id)
            {
                $arr[] = $id['product_id'];
            }
            $ids             = implode(',', array_filter($arr));
            $user_identifier = getVal('user_key', 'c_users', 'user_id', $user_id);
            $query           = "SELECT
				  count(*) as counter
				FROM
				" . $this->db->dbprefix($this->tbl_usertracking) . " track

WHERE   track.product_id IN (" . $ids . ") AND user_identifier = '" . $user_identifier . "'

AND
    (track.timestamp >= UNIX_TIMESTAMP(LAST_DAY(CURDATE()) + INTERVAL 1 DAY - INTERVAL 1 MONTH)
   AND track.timestamp <  UNIX_TIMESTAMP(LAST_DAY(CURDATE()) + INTERVAL 1 DAY) )
          limit 1";
//            echo $query;die();
            $query           = $this->db->query($query);
            $row             = $query->row();
            return $row->counter;
        }
        else
        {
            return 0;
        }
    }

    function getTotalSale($user_id)
    {
        $query = "SELECT
				SUM(ordr.price) as counter,pro.currency as currency_id,(Select c_currencies.currency_symbol from c_currencies where c_currencies.currency_id = pro.currency) as currency_symbol
			FROM
				" . $this->db->dbprefix($this->tbl_orders) . " as ordr
                    INNER JOIN
                               " . $this->db->dbprefix($this->tbl) . " res
                        ON
                                (res.user_id = ordr.advertiser_id)
                                INNER JOIN
                               " . $this->db->dbprefix($this->tbl_products) . " pro
                        ON
                                (pro.product_id = ordr.product_id)
                                
                    WHERE   ordr.advertiser_id = " . $user_id . " AND ordr.order_status = 2
                         AND
    (ordr.created >= UNIX_TIMESTAMP(LAST_DAY(CURDATE()) + INTERVAL 1 DAY - INTERVAL 1 MONTH)
   AND ordr.created <  UNIX_TIMESTAMP(LAST_DAY(CURDATE()) + INTERVAL 1 DAY) )
                        ";
        $query = $this->db->query($query);
       $row   = $query->result_array();
       $user_currency = $this->session->userdata('currency');
        if(trim($user_currency) == ''){
            $user_currency = getVal('currency', 'c_users', 'user_id', $user_id);
        }
        //get_currency_rate(1,'GBP','USD')
        foreach($row as $key => $value)
        {
            if($user_currency <> $value['currency_id'] && $value['counter'] > 0)
            {
                $row[$key]['counter'] = get_currency_rate($value['counter'],$value['currency_id'],$user_currency);
            }
        }
        $counter = 0;
        foreach($row as $key => $value)
        {
            $counter += $value['counter'];
        }
        return $counter;
		
    }

    function getTotalCommission($user_id)
    {
//        echo 'model -> '.$user_id;die();
        $query = "SELECT
				  group_concat(comm.advertiser_commission) as counter,group_concat(pro.currency) as currency_id,(Select c_currencies.currency_symbol from c_currencies where c_currencies.currency_id = pro.currency) as currency_symbol
				FROM
				" . $this->db->dbprefix($this->tbl_user_commissions) . " comm

 LEFT JOIN
                               " . $this->db->dbprefix($this->tbl) . " as res
                        ON
                               (res.user_id = comm.user_id || comm.user_id = 0)

INNER JOIN
                               " . $this->db->dbprefix($this->tbl_orders) . " as ordr
                        ON
                               (ordr.id = comm.order_id)
                               
INNER JOIN
                               " . $this->db->dbprefix($this->tbl_products) . " pro
                        ON
                                (pro.product_id = ordr.product_id)


WHERE   comm.user_id = " . $user_id . " AND ordr.order_status = 2
    AND
    (comm.created >= UNIX_TIMESTAMP(LAST_DAY(CURDATE()) + INTERVAL 1 DAY - INTERVAL 1 MONTH)
   AND comm.created <  UNIX_TIMESTAMP(LAST_DAY(CURDATE()) + INTERVAL 1 DAY) )
          ";
        $query = $this->db->query($query);
        $row   = $query->row_array();
        $user_currency = $this->session->userdata('currency');
        if(trim($user_currency) == ''){
            $user_currency = getVal('currency', 'c_users', 'user_id', $user_id);
        }
      
       $currency_ids = explode(',',$row['currency_id']);
       $values = explode(',',$row['counter']);
        $counter = 0;
        foreach($currency_ids as $key => $value)
        {
            $counter += get_currency_rate($values[$key],$value,$user_currency);
        }
        return $counter;
		
    }

    function getTotalSales($user_id)
    {

        $whr = '(ordr.created >= UNIX_TIMESTAMP(LAST_DAY(CURDATE()) + INTERVAL 1 DAY - INTERVAL 1 MONTH)
   AND ordr.created <  UNIX_TIMESTAMP(LAST_DAY(CURDATE()) + INTERVAL 1 DAY) )';

        $query = "SELECT
				ordr.*,track.referer_page
			FROM
				" . $this->db->dbprefix($this->tbl_orders) . " as ordr
                    INNER JOIN
                               " . $this->db->dbprefix($this->tbl) . " res
                        ON
                                (res.user_id = ordr.advertiser_id)
                     INNER JOIN
                               " . $this->db->dbprefix($this->tbl_usertracking) . " track
                        ON
                                (track.id = ordr.user_tracking)

                    WHERE   ordr.advertiser_id = " . $user_id . ""
                . " AND ordr.order_status = 2 "
                . " AND " . $whr . "
                    GROUP BY ordr.id
                       ORDER BY id desc ";
        $query = $this->db->query($query);
        return $query->result_array();
    }

    function getTotalCommissions($user_id)
    {

        $whr = '(ordr.created >= UNIX_TIMESTAMP(LAST_DAY(CURDATE()) + INTERVAL 1 DAY - INTERVAL 1 MONTH)
   AND ordr.created <  UNIX_TIMESTAMP(LAST_DAY(CURDATE()) + INTERVAL 1 DAY) )';

        $query = "SELECT
				  comm.*,ordr.url,track.referer_page
				FROM
				" . $this->db->dbprefix($this->tbl_user_commissions) . " comm

 LEFT JOIN
                               " . $this->db->dbprefix($this->tbl) . " as res
                        ON
                               (res.user_id = comm.user_id || comm.user_id = 0)
INNER JOIN
                               " . $this->db->dbprefix($this->tbl_orders) . " as ordr
                        ON
                               (ordr.id = comm.order_id)
 INNER JOIN
                               " . $this->db->dbprefix($this->tbl_usertracking) . " track
                        ON
                                (track.id = ordr.user_tracking)

WHERE   comm.user_id = " . $user_id . "
                AND ordr.order_status = 2 
    AND " . $whr . "
        GROUP BY ordr.id
          ORDER BY id desc";
//        echo $query;die();
        $query = $this->db->query($query);
        return $query->result_array();
    }

    /*     * *****************PUBLISHER***************************** */
    /*     * *****************PUBLISHER***************************** */
    /*     * *****************PUBLISHER***************************** */

    function getTotalProducts($user_id)
    {
        $query = "SELECT
				  count(*) as counter
				FROM
				" . $this->db->dbprefix($this->tbl_products) . " pro

 INNER JOIN
                               " . $this->db->dbprefix($this->tbl) . " as res
                        ON
                               (res.user_id = pro.user_id)

WHERE   pro.user_id = " . $user_id . " AND
    (pro.created >= UNIX_TIMESTAMP(LAST_DAY(CURDATE()) + INTERVAL 1 DAY - INTERVAL 1 MONTH)
   AND pro.created <  UNIX_TIMESTAMP(LAST_DAY(CURDATE()) + INTERVAL 1 DAY) )
          limit 1";
        $query = $this->db->query($query);
        $row   = $query->row();
        return $row->counter;
    }

    function getTotalPublisherLinksShared($user_id)
    {
        $query = "SELECT
				  SUM(share_counter) as counter
				FROM
				" . $this->db->dbprefix($this->tbl_products_links_share) . " shr

 INNER JOIN
                               " . $this->db->dbprefix($this->tbl) . " as res
                        ON
                               (res.user_id = shr.user_id)

INNER JOIN
                               " . $this->db->dbprefix($this->tbl_products) . " as pro
                        ON
                               (pro.product_id = shr.product_id)

WHERE   pro.user_id = " . $user_id . " AND
    (shr.created >= UNIX_TIMESTAMP(LAST_DAY(CURDATE()) + INTERVAL 1 DAY - INTERVAL 1 MONTH)
   AND shr.created <  UNIX_TIMESTAMP(LAST_DAY(CURDATE()) + INTERVAL 1 DAY) )
          ";
        $query = $this->db->query($query);
        $row   = $query->row();
        return $row->counter;
    }

    function getTotalPublisherVisitors($user_id)
    {

        $query = "SELECT
			 DISTINCT pro.product_id
				FROM
				" . $this->db->dbprefix($this->tbl_products) . " pro
 INNER JOIN
                               " . $this->db->dbprefix($this->tbl) . " as res
                        ON
                               (res.user_id = pro.user_id)

WHERE   pro.user_id = " . $user_id . "

          ";
        $q     = $this->db->query($query);
        if ($q->num_rows() > 0)
        {
            $result = $q->result_array();
            $arr    = array();
            foreach ($result as $id)
            {
                $arr[] = $id['product_id'];
            }
            $ids   = implode(',', array_filter($arr));
            $query = "SELECT
				  count(*) as counter
				FROM
				" . $this->db->dbprefix($this->tbl_usertracking) . " track
WHERE   track.product_id IN (" . $ids . ")
AND
    (track.timestamp >= UNIX_TIMESTAMP(LAST_DAY(CURDATE()) + INTERVAL 1 DAY - INTERVAL 1 MONTH)
   AND track.timestamp <  UNIX_TIMESTAMP(LAST_DAY(CURDATE()) + INTERVAL 1 DAY) )
          limit 1";
            $query = $this->db->query($query);
            $row   = $query->row();
            return $row->counter;
        }
        else
        {
            return 0;
        }
    }

    function getTotalPublisherSale($user_id)
    {
        $query = "SELECT
		 pro.currency as currency_id,SUM(ordr.price) as counter,(Select c_currencies.currency_symbol from c_currencies where c_currencies.currency_id = pro.currency) as currency_symbol
			FROM
				" . $this->db->dbprefix($this->tbl_orders) . " as ordr
                    INNER JOIN
                               " . $this->db->dbprefix($this->tbl) . " res
                        ON
                                (res.user_id = ordr.seller_id)
                                INNER JOIN
                               " . $this->db->dbprefix($this->tbl_products) . " pro
                        ON
                                (pro.product_id = ordr.product_id)
                    WHERE   ordr.seller_id = " . $user_id . " AND ordr.order_status = 2
                         AND
    (ordr.created >= UNIX_TIMESTAMP(LAST_DAY(CURDATE()) + INTERVAL 1 DAY - INTERVAL 1 MONTH)
   AND ordr.created <  UNIX_TIMESTAMP(LAST_DAY(CURDATE()) + INTERVAL 1 DAY) )   AND ordr.is_confirmed = 1 
                     group by pro.currency";
        $query = $this->db->query($query);
        $row   = $query->result_array();
        $user_currency = $this->session->userdata('currency');
        if(trim($user_currency) == ''){
            $user_currency = getVal('currency', 'c_users', 'user_id', $user_id);
        }
        foreach($row as $key => $value)
        {
            if($user_currency <> $value['currency_id'] && $value['counter'] > 0)
            {
                $row[$key]['counter'] = get_currency_rate($value['counter'],$value['currency_id'],$user_currency);
            }
        }
        $counter = 0;
        foreach($row as $key => $value)
        {
            $counter += $value['counter'];
        }
        return $counter;
    }

    function getPublisherTotalCommission($user_id)
    {
      
        $query = "SELECT
				  pro.currency as currency_id,SUM(comm.total_commission) as counter,(Select c_currencies.currency_symbol from c_currencies where c_currencies.currency_id = pro.currency) as currency_symbol
				FROM
				" . $this->db->dbprefix($this->tbl_user_commissions) . " comm
INNER JOIN
                               " . $this->db->dbprefix($this->tbl_orders) . " as ordr
                                 ON
                               (ordr.id = comm.order_id)
                               INNER JOIN
                               " . $this->db->dbprefix($this->tbl_products) . " pro
                        ON
                                (pro.product_id = ordr.product_id)


WHERE   ordr.seller_id = " . $user_id . " AND ordr.order_status = 2
    AND
    (comm.created >= UNIX_TIMESTAMP(LAST_DAY(CURDATE()) + INTERVAL 1 DAY - INTERVAL 1 MONTH)
   AND comm.created <  UNIX_TIMESTAMP(LAST_DAY(CURDATE()) + INTERVAL 1 DAY) )   AND ordr.is_confirmed = 1 
          ";
        $query = $this->db->query($query);
    
        $row   = $query->result_array();
$user_currency = $this->session->userdata('currency');
        if(trim($user_currency) == ''){
            $user_currency = getVal('currency', 'c_users', 'user_id', $user_id);
        }
        //get_currency_rate(1,'GBP','USD')
        foreach($row as $key => $value)
        {
            if($user_currency <> $value['currency_id'] && $value['counter'] > 0)
            {
                $row[$key]['counter'] = get_currency_rate($value['counter'],$value['currency_id'],$user_currency);
            }
        }
        $counter = 0;
        foreach($row as $key => $value)
        {
            $counter += $value['counter'];
        }
        return $counter;
    }

    /**
     * Method: checkEmail
     * Return: 0/1
     */
    function checkEmail($email)
    {

        $sql_  = "SELECT email FROM c_users WHERE email = '" . $email . "' AND user_id <> " . $this->session->userdata('user_id') . "";
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

    function getTotalPublisherSales($user_id)
    {

        $whr = '(ordr.created >= UNIX_TIMESTAMP(LAST_DAY(CURDATE()) + INTERVAL 1 DAY - INTERVAL 1 MONTH)
   AND ordr.created <  UNIX_TIMESTAMP(LAST_DAY(CURDATE()) + INTERVAL 1 DAY) )';


        $query = "SELECT
				ordr.*,track.referer_page
			FROM
				" . $this->db->dbprefix($this->tbl_orders) . " as ordr
                    INNER JOIN
                               " . $this->db->dbprefix($this->tbl) . " res
                        ON
                                (res.user_id = ordr.seller_id)
                    INNER JOIN
                               " . $this->db->dbprefix($this->tbl_usertracking) . " track
                        ON
                                (track.id = ordr.user_tracking)

                    WHERE   ordr.seller_id = " . $user_id . ""
                . " AND ordr.order_status = 2   AND ordr.is_confirmed = 1  "
                . " AND " . $whr . "
                    GROUP BY ordr.id
                       ORDER BY id desc, track.id desc ";
        $query = $this->db->query($query);
        return $query->result_array();
    }

    function getTotalPublisherCommission($user_id)
    {

        $whr = '(comm.created >= UNIX_TIMESTAMP(LAST_DAY(CURDATE()) + INTERVAL 1 DAY - INTERVAL 1 MONTH)
   AND comm.created <  UNIX_TIMESTAMP(LAST_DAY(CURDATE()) + INTERVAL 1 DAY) )';

        $query = "SELECT
				  comm.*,ordr.url,track.referer_page
				FROM
				" . $this->db->dbprefix($this->tbl_user_commissions) . " comm

 LEFT JOIN
                               " . $this->db->dbprefix($this->tbl) . " as res
                        ON
                               (res.user_id = comm.user_id || comm.user_id = 0)
INNER JOIN
                               " . $this->db->dbprefix($this->tbl_orders) . " as ordr
                        ON
                               (ordr.id = comm.order_id)
 INNER JOIN
                               " . $this->db->dbprefix($this->tbl_usertracking) . " track
                        ON
                                (track.id = ordr.user_tracking)

WHERE   ordr.seller_id = " . $user_id . " AND ordr.order_status = 2   AND ordr.is_confirmed = 1  
    AND " . $whr . "
        GROUP BY ordr.id
          ORDER BY id desc";
//        echo $query;die();
        $query = $this->db->query($query);
        return $query->result_array();
    }

    function getTotalPublisherSuccessLeadCounter($user_id, $filter)
    {

        $whr = '';

        $whr = '(ordr.created >= UNIX_TIMESTAMP(LAST_DAY(CURDATE()) + INTERVAL 1 DAY - INTERVAL 1 MONTH)
   AND ordr.created <  UNIX_TIMESTAMP(LAST_DAY(CURDATE()) + INTERVAL 1 DAY) )';



        $query = "SELECT
				pro.currency as currency_id,count(ordr.id) as counter,(Select c_currencies.currency_symbol from c_currencies where c_currencies.currency_id = pro.currency) as currency_symbol
			FROM
				" . $this->db->dbprefix($this->tbl_orders) . " as ordr
                    INNER JOIN
                               " . $this->db->dbprefix($this->tbl) . " res
                        ON
                                (res.user_id = ordr.seller_id)
                                INNER JOIN
                               " . $this->db->dbprefix($this->tbl_products) . " pro
                        ON
                                (pro.product_id = ordr.product_id)

                    WHERE   ordr.seller_id = " . $user_id . " AND ordr.order_status = 2   AND ordr.is_confirmed = 1  "
                . " AND " . $whr . "
                       ";
        $query = $this->db->query($query);
         $row   = $query->result_array();
//dd($row);
        //get_currency_rate(1,'GBP','USD')
//        foreach($row as $key => $value)
//        {
//            if($user_currency <> $value['currency_id'] && $value['counter'] > 0)
//            {
//                $row[$key]['counter'] = get_currency_rate($value['counter'],$value['currency_id'],$this->session->userdata('currency'));
//            }
//        }
        
        $counter = 0;
        foreach($row as $key => $value)
        {
            $counter += $value['counter'];
        }
        
        return $counter;
    }

    function getTotalSuccessLeadCounter($user_id, $filter)
    {



        $whr = '(ordr.created >= UNIX_TIMESTAMP(LAST_DAY(CURDATE()) + INTERVAL 1 DAY - INTERVAL 1 MONTH)
   AND ordr.created <  UNIX_TIMESTAMP(LAST_DAY(CURDATE()) + INTERVAL 1 DAY) )';



        $query = "SELECT
				pro.currency as currency_id,count(id) as counter,(Select c_currencies.currency_symbol from c_currencies where c_currencies.currency_id = pro.currency) as currency_symbol
			FROM
				" . $this->db->dbprefix($this->tbl_orders) . " as ordr
                    INNER JOIN
                               " . $this->db->dbprefix($this->tbl) . " res
                                  
                        ON
                                (res.user_id = ordr.advertiser_id)
                                INNER JOIN
                               " . $this->db->dbprefix($this->tbl_products) . " pro
                        ON
                                (pro.product_id = ordr.product_id)
                    WHERE   ordr.advertiser_id = " . $user_id . ""
                . " AND ordr.order_status = 2 "
                . " AND " . $whr . "
                      ";
        $query = $this->db->query($query);
         $row   = $query->result_array();
//dd($row);
        //get_currency_rate(1,'GBP','USD')
//        foreach($row as $key => $value)
//        {
//            if($user_currency <> $value['currency_id'] && $value['counter'] > 0)
//            {
//                $row[$key]['counter'] = get_currency_rate($value['counter'],$value['currency_id'],$this->session->userdata('currency'));
//            }
//        }
        
        $counter = 0;
        foreach($row as $key => $value)
        {
            $counter += $value['counter'];
        }
        
        return $counter;
    }

    function savePaymentSettings($post)
    {
        $id          = $this->session->userdata('user_id');
        $data_insert = array();
        if (is_array($post))
        {
            foreach ($post as $k => $v)
            {
                $data_insert[$k] = $v;
            }
        }
        //Update Data
        $this->db->where('user_id', $id);
        $db = $this->db->update($this->db->dbprefix . $this->tbl, $data_insert);
        return $db;
    }

}

//End Class