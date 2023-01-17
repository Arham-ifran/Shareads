<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Wallet_model extends CI_Model
{

    var $tbl                      = 'users';
    var $tbl_products             = 'products';
    var $tbl_usertracking         = 'usertracking';
    var $tbl_orders               = 'orders';
    var $tbl_products_links_share = 'products_links_share';
    var $tbl_user_commissions     = 'user_commissions';
    var $tbl_withdraw             = 'withdraw';

    public function __construct()
    {
        parent::__construct();
    }

    function getTotalCommission($_user_id)
    {
        $user_id = $this->session->userdata('user_id');
        if ($_user_id <> '')
        {
            $user_id = $_user_id;
        }
        $user_currency  = getVal('currency', 'c_users', 'user_id', $user_id);
        $query          = "SELECT SUM(comm.advertiser_commission) as counter, 
                                  Group_concat(comm.advertiser_commission) as counter_group,
           Group_concat(pro.product_id)    AS prr ,
Group_concat(pro.currency)    AS prr_currency  FROM
				" . $this->db->dbprefix($this->tbl_user_commissions) . " comm
 INNER JOIN
                               " . $this->db->dbprefix($this->tbl) . " as res
                        ON
                               (res.user_id = comm.user_id || comm.user_id = 0)
LEFT JOIN
                               " . $this->db->dbprefix($this->tbl_orders) . " as ordr
                        ON
                               (ordr.id = comm.order_id)
                               
INNER JOIN
                               " . $this->db->dbprefix($this->tbl_products) . " as pro
                        ON
                               (pro.product_id = comm.product_id)
LEFT JOIN c_invoice_orders as cio ON  FIND_IN_SET(ordr.id, cio.order_ids) > 0
INNER JOIN c_invoices as inv ON inv.invoice_id = cio.invoice_id
LEFT JOIN c_payment as payment_inv ON payment_inv.invoice_number = inv.invoice_number
WHERE   comm.user_id = " . $user_id . " AND ordr.order_status = 2  AND ordr.is_confirmed = 1 AND pro.of_admin = 0  AND      ordr.is_paid = 0 AND inv.status = 1  AND DATEDIFF(CURDATE(),from_unixtime(ordr.created,'%Y-%m-%d')) >= " . NO_OF_DAYS . "
    ";
//echo $query;die();
        $query          = $this->db->query($query);
        $row            = $query->row_array();
        $prr            = explode(',', $row['prr']);
        $prr_currency   = explode(',', $row['prr_currency']);
        $counter_group  = explode(',', $row['counter_group']);
        $row['counter'] = 0;
        foreach ($prr as $pkey => $value)
        {
            $row['counter'] = $row['counter'] + get_currency_rate($counter_group[$pkey], $prr_currency[$pkey], $user_currency);
        }

        return $row['counter'];
    }

    function getTotalCommissionAdmin($_user_id)
    {
        $user_id = $this->session->userdata('user_id');
        if ($_user_id <> '')
        {
            $user_id = $_user_id;
        }
        $user_currency = getVal('currency', 'c_users', 'user_id', $user_id);
        $query         = "SELECT
				  SUM(comm.advertiser_commission) as counter, 
                                  Group_concat(comm.advertiser_commission) as counter_group,
           Group_concat(pro.product_id)    AS prr ,
Group_concat(pro.currency)    AS prr_currency 
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
                               INNER JOIN
                               " . $this->db->dbprefix($this->tbl_products) . " as pro
                        ON
                               (pro.product_id = comm.product_id)
                               
WHERE   comm.user_id = " . $user_id . "  AND ordr.order_status = 2   AND ordr.is_confirmed = 1 AND ordr.is_paid = 0 AND pro.of_admin = 1  ";
 $query .= " AND DATEDIFF(CURDATE(),from_unixtime(ordr.created,'%Y-%m-%d')) >= " . NO_OF_DAYS . "   ";
//echo $query;die();
        $query         = $this->db->query($query);

        $row            = $query->row_array();
        $prr            = explode(',', $row['prr']);
        $prr_currency   = explode(',', $row['prr_currency']);
        $counter_group  = explode(',', $row['counter_group']);
        $row['counter'] = 0;
        foreach ($prr as $pkey => $value)
        {
            $row['counter'] = $row['counter'] + get_currency_rate($counter_group[$pkey], $prr_currency[$pkey], $user_currency);
        }

        return $row['counter'];
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
                               



WHERE   comm.user_id = " . $user_id . "  AND ordr.order_status = 2   AND ordr.is_confirmed = 1 AND ordr.is_paid = 0
    ";
//echo $query;die();
        $query   = $this->db->query($query);
        $row     = $query->row();
        return $row->counter;
    }

    // STATUS::0=pending,1=completed
    public function getWithdrawRequestsSUMByStatus($status = '', $_user_id = '')
    {
        $user_id       = $this->session->userdata('user_id');
        if ($_user_id <> '')
        {
            $user_id = $_user_id;
        }
        $user_currency = getVal('currency', 'c_users', 'user_id', $user_id);
        
        $query         = "SELECT GROUP_CONCAT(withdraw.amount_requested) as total_amount,GROUP_CONCAT(withdraw.currency) as currency "
                . "FROM " . $this->db->dbprefix($this->tbl_withdraw) . " as withdraw "
                . "WHERE   withdraw.affiliate_id = " . $user_id . "  ";

        $query .= " AND withdraw.status = " . $status . "  ";
        $query .= ' HAVING SUM(withdraw.amount_requested) IS NOT NULL ';
//echo $query;die();
        $query = $this->db->query($query);
        // $row   = $query->row_array();
;$row            = $query->row_array();

        $prr            = explode(',', $row['currency']);
        $counter_group  = explode(',', $row['total_amount']);
        $row['counter'] = 0;
        foreach ($prr as $pkey => $value)
        {
            $row['counter'] = $row['counter'] + get_currency_rate($counter_group[$pkey], $prr[$pkey], $user_currency);
        }

        return array('total_amount' => $row['counter']);
        // return $row;
    }

    public function getWithdrawRequestsByStatus($status = '', $_user_id = '')
    {
        $user_id = $this->session->userdata('user_id');
        if ($_user_id <> '')
        {
            $user_id = $_user_id;
        }
        $this->db->select('*');
        $this->db->from($this->db->dbprefix($this->tbl_withdraw));
        $this->db->where("affiliate_id", $user_id);
        if ($status <> '')
        {
            $this->db->where("status", $status);
        }
        $this->db->order_by("id", "desc");
        $query = $this->db->get();
//        echo $this->db->last_query();die();     
        $list  = $query->result_array();
        return $list;
    }

    public function getLastSuccessWidthdraw($_user_id = '')
    {
        $user_id = $this->session->userdata('user_id');
        if ($_user_id <> '')
        {
            $user_id = $_user_id;
        }
        $query = "SELECT withdraw.amount_requested as lastWithdrawAmount,withdraw.currency as wd_currency "
                . "FROM " . $this->db->dbprefix($this->tbl_withdraw) . " withdraw "
                . "WHERE   withdraw.affiliate_id = " . $user_id . " AND withdraw.status = 1   ";
        $query .= " ORDER BY withdraw.id  DESC LIMIT 1 ";
        // dd($query);
        $query = $this->db->query($query);
        $row   = $query->row_array();
        return $row;
    }
    
     public function getLastsWidthdraw($_user_id = '')
    {
        $user_id = $this->session->userdata('user_id');
        if ($_user_id <> '')
        {
            $user_id = $_user_id;
        }
        $query = "SELECT withdraw.amount_requested as lastWithdrawAmount,withdraw.currency as wd_currency "
                . "FROM " . $this->db->dbprefix($this->tbl_withdraw) . " withdraw "
                . "WHERE   withdraw.affiliate_id = " . $user_id . "  ";
        $query .= " ORDER BY withdraw.id  DESC LIMIT 1 ";
        // dd($query);
        $query = $this->db->query($query);
        $row   = $query->row_array();
        return $row;
    }
    

    public function saveWithdrawRequest($amount_withdraw, $order_ids)
    {
        $insert                     = array();
        $insert['affiliate_id']     = $this->session->userdata('user_id');
        $payment_type               = getVal('payment_type', 'users', array('user_id' => $insert['affiliate_id']));
        $insert['amount_requested'] = $amount_withdraw;
        $insert['orders_ids']       = $order_ids;
        $insert['status']           = 0;
        $insert['payment_type']     = $payment_type;
        $insert['created']          = time();
        $insert['currency']         = $this->session->userdata('currency');
        $insert['updated']          = time();
        $this->db->insert($this->tbl_withdraw, $insert);
        $withdraw_id                = $this->db->insert_id();
        return $withdraw_id;
    }

    function getTotalCommissionOrders($user_id)
    {
        $query  = "SELECT GROUP_CONCAT(ordr.id) as order_ids FROM
				" . $this->db->dbprefix($this->tbl_user_commissions) . " comm
 LEFT JOIN " . $this->db->dbprefix($this->tbl) . " as res
                        ON (res.user_id = comm.user_id || comm.user_id = 0) INNER JOIN
                               " . $this->db->dbprefix($this->tbl_orders) . " as ordr
                        ON (ordr.id = comm.order_id)
WHERE   comm.user_id = " . $user_id . " AND ordr.order_status = 2 AND ordr.is_paid = 0 
    Limit 1 ";
//echo $query;die();
        $query  = $this->db->query($query);
        $result = $query->row_array();
        return $result['order_ids'];
    }

}

//End Class