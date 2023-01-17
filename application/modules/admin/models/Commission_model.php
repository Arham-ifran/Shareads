<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class commission_model extends CI_Model
{

    var $tbl                = 'user_commissions';
    var $tbl_withdraw       = 'withdraw';
    var $tbl_orders         = 'orders';
    var $tbl_invoices       = 'invoices';
    var $tbl_invoice_orders = 'invoice_orders';
    var $directory_name     = 'shareads_invoices';

    public function __construct()
    {
        parent::__construct();
    }

//End __construct

    public function get_all_commission_orders()
    {
        $data   = $_POST;
        $filter = '';
        if (trim($data['date_from']) != '' && trim($data['date_to']) == '')
        {
            $filter .= ' AND ordr.created  >="' . strtotime($data['date_from']) . '"';
        }
        if (trim($data['date_from']) == '' && trim($data['date_to']) != '')
        {
            $filter .= ' AND ordr.created  <="' . strtotime($data['date_to']) . '"';
        }
        if (trim($data['date_from']) != '' && trim($data['date_to']) != '')
        {
            $filter .= ' AND ordr.created  >="' . strtotime($data['date_from']) . '"';
            $filter .= ' AND ordr.created  <="' . strtotime($data['date_to']) . '"';
        }

        $sql_ = 'SELECT from_unixtime(ordr.created, "%M %Y") as monthName,
           from_unixtime(ordr.created, "%m-%Y") as monthDate,
           ordr.created as timestampp ,
           Group_concat(DISTINCT(pro.product_id))                           AS product_ids, 
            count(ordr.id) as total_orders,ordr.is_paid,
                    GROUP_CONCAT(ordr.price) as overall_price,
                    GROUP_CONCAT(comm.total_commission) as overall_commission,
                    GROUP_CONCAT(comm.advertiser_commission) as all_advertiser_commission,
		GROUP_CONCAT(pro.currency) as pro_currencies
                FROM
                    c_orders as ordr
                    LEFT JOIN c_users seller on seller.user_id = ordr.seller_id AND seller.hold_payment = 0
                    LEFT JOIN c_users buyer on ((buyer.user_id = ordr.advertiser_id AND buyer.hold_payment = 0) ||buyer.user_id = 0)
                    INNER JOIN c_products pro on ordr.product_id = pro.product_id
                    LEFT JOIN c_user_commissions comm on ordr.id = comm.order_id

            WHERE
                    ordr.order_status > 1 AND (pro.product_type = 1 OR pro.product_type = 3) 
                    ' . $filter . '
                 GROUP BY from_unixtime(ordr.created, "%M")
		 ORDER BY ordr.id DESC';
//echo $sql_;die();
        $query = $this->db->query($sql_);
        if ($query->num_rows() > 0)
        {
            return $query->result_array();
        }
    }

    public function get_all_commissionAdvertiser_orders($month)
    {

        $sql_  = 'SELECT
            count(ordr.id) as total_orders,comm.is_paid,
                    SUM(comm.total_commission) as overall_commission,
                    SUM(comm.advertiser_commission) as all_advertiser_commission
                FROM
                    c_orders as ordr
                    INNER JOIN c_user_commissions comm on ordr.id = comm.order_id
                    LEFT JOIN c_users buyer on buyer.user_id = comm.user_id
                    INNER JOIN c_products pro on comm.product_id = pro.product_id


            WHERE
                ordr.order_status > 1 AND (pro.product_type = 1 OR pro.product_type = 3) AND
                buyer.hold_payment = 0 AND ordr.is_paid = 0 AND

                DATEDIFF(CURDATE(),from_unixtime(buyer.created, "%Y-%m-%d")) > ' . NO_OF_DAYS . '

                AND from_unixtime(ordr.created, "%m-%Y") = "' . $month . '"
                    GROUP BY ordr.is_paid
		ORDER BY comm.id DESC';
        $query = $this->db->query($sql_);
        if ($query->num_rows() > 0)
        {
            return $query->row_array();
        }
    }

    public function get_all_commissionPublisher_orders($month)
    {

        $sql_  = 'SELECT from_unixtime(ordr.created, "%M") as monthName,ordr.is_paid,
            count(ordr.id) as total_orders,
                    SUM(ordr.price) as overall_price,
                    SUM(comm.total_commission) as overall_commission,
                    SUM(comm.advertiser_commission) as all_advertiser_commission
                FROM
                    c_orders as ordr
                    INNER JOIN c_users seller on seller.user_id = ordr.seller_id
                    INNER JOIN c_products pro on ordr.product_id = pro.product_id
                    INNER JOIN c_user_commissions comm on ordr.id = comm.order_id

            WHERE
                    ordr.order_status > 1 AND (pro.product_type = 1 OR pro.product_type = 3) AND
                    seller.hold_payment = 0 AND  ordr.is_paid = 0 AND

                    DATEDIFF(CURDATE(),from_unixtime(seller.created, "%Y-%m-%d")) > ' . NO_OF_DAYS . '

            AND from_unixtime(ordr.created, "%m-%Y") = "' . $month . '"
        GROUP BY ordr.is_paid
		 ORDER BY ordr.id DESC';
        $query = $this->db->query($sql_);
        if ($query->num_rows() > 0)
        {
            return $query->row_array();
        }
    }

    public function get_allPaidCommissionAdvertiser_orders($month)
    {

        $sql_  = 'SELECT
            count(ordr.id) as total_orders,comm.is_paid,
                    SUM(comm.total_commission) as overall_commission,
                    SUM(comm.advertiser_commission) as all_advertiser_commission
                FROM
                    c_orders as ordr
                    INNER JOIN c_user_commissions comm on ordr.id = comm.order_id
                    LEFT JOIN c_users buyer on buyer.user_id = comm.user_id
                    INNER JOIN c_products pro on comm.product_id = pro.product_id


            WHERE
                ordr.order_status > 1 AND (pro.product_type = 1 OR pro.product_type = 3) AND
                buyer.hold_payment = 0 AND ordr.is_paid = 1 AND

                DATEDIFF(CURDATE(),from_unixtime(buyer.created, "%Y-%m-%d")) > ' . NO_OF_DAYS . '

                AND from_unixtime(ordr.created, "%m-%Y") = "' . $month . '"
                    GROUP BY ordr.is_paid
		ORDER BY comm.id DESC';
        $query = $this->db->query($sql_);
        if ($query->num_rows() > 0)
        {
            return $query->row_array();
        }
    }

    public function get_allPaidCommissionPublisher_orders($month)
    {

        $sql_  = 'SELECT from_unixtime(ordr.created, "%M") as monthName,ordr.is_paid,
            count(ordr.id) as total_orders,
                    SUM(ordr.price) as overall_price,
                    SUM(comm.total_commission) as overall_commission,
                    SUM(comm.advertiser_commission) as all_advertiser_commission
                FROM
                    c_orders as ordr
                    INNER JOIN c_users seller on seller.user_id = ordr.seller_id
                    INNER JOIN c_products pro on ordr.product_id = pro.product_id
                    INNER JOIN c_user_commissions comm on ordr.id = comm.order_id

            WHERE
                    ordr.order_status > 1 AND (pro.product_type = 1 OR pro.product_type = 3) AND
                    seller.hold_payment = 0 AND  ordr.is_paid = 1 AND

                    DATEDIFF(CURDATE(),from_unixtime(seller.created, "%Y-%m-%d")) > ' . NO_OF_DAYS . '

            AND from_unixtime(ordr.created, "%m-%Y") = "' . $month . '"
        GROUP BY ordr.is_paid
		 ORDER BY ordr.id DESC';
        $query = $this->db->query($sql_);
        if ($query->num_rows() > 0)
        {
            return $query->row_array();
        }
    }

    public function get_allLeadGencommission_orders()
    {

        $sql_  = 'SELECT from_unixtime(ordr.created, "%M %Y") as monthName,
           from_unixtime(ordr.created, "%m-%Y") as monthDate,ordr.status,

                    SUM(ordr.commission) as all_advertiser_commission
                FROM
                    c_lead_generation as ordr

                    INNER JOIN c_products pro on ordr.product_id = pro.product_id
                    LEFT JOIN c_users seller on seller.user_id = ordr.seller_id AND seller.hold_payment = 0
                    LEFT JOIN c_users buyer on ((buyer.user_id = ordr.advertiser_id AND buyer.hold_payment = 0) ||buyer.user_id = 0)
            WHERE
            ordr.status = 1 AND

                    DATEDIFF(CURDATE(),from_unixtime(seller.created, "%Y-%m-%d")) > ' . NO_OF_DAYS . '


                 GROUP BY from_unixtime(ordr.created, "%M"),ordr.status
		 ORDER BY ordr.status ASC, ordr.id DESC';
        $query = $this->db->query($sql_);
        if ($query->num_rows() > 0)
        {
            return $query->result_array();
        }
    }

    public function get_all_withdraws($withdraw_id = '')
    {
        $select   = $from     = $join     = $where    = $group_by = $order_by = $limit    = '';

        $case_1 = '(CASE WHEN users.payment_type = 2 THEN "Wire Transfer" ELSE "Paypal" END) as payment_type_text';

        $select = 'SELECT withdraw.*,'
                . 'CONCAT(users.first_name," ",users.last_name) as affiliater_name,'
                . 'users.payment_type as payment_type, '
                . 'users.account_holder_name as account_holder_name, '
                . 'users.account_number as account_number, '
                . 'users.iban_code as iban_code, '
                . 'users.swift_code as swift_code, '
                . 'users.bank_name as bank_name, '
                . 'users.bank_address as bank_address, '
                . 'users.paypal_email as paypal_email, '
                . $case_1 . '  ';

        $from = 'FROM ' . $this->db->dbprefix($this->tbl_withdraw) . ' as withdraw ';

        $join = 'INNER join c_orders as orders on orders.advertiser_id = withdraw.affiliate_id 
                 LEFT join c_users as users on users.user_id = withdraw.affiliate_id ';

        $where = 'WHERE 1  ';
        if ($withdraw_id <> '')
        {
            $where .= ' AND withdraw.id = ' . $withdraw_id . '  ';
        }

        $group_by = ' group by withdraw.id ';
        $order_by = ' order by withdraw.id desc ';
        $limit    = ' ';

        $sql = $select . $from . $join . $where . $group_by . $order_by;

//         echo $sql;die();
        $query = $this->db->query($sql);
        $list  = $query->result_array();
        return $list;
    }

    public function get_all_invoices($invoice_id = '', $data)
    {

        if (trim($data['date_from']) != '' && trim($data['date_to']) == '')
        {
            $this->db->where('inv.created  >="' . $data['date_from'] . '"');
        }
        if (trim($data['date_from']) == '' && trim($data['date_to']) != '')
        {
            $this->db->where('inv.created  <="' . $data['date_to'] . '"');
        }
        if (trim($data['date_from']) != '' && trim($data['date_to']) != '')
        {
            $this->db->where('inv.created  >="' . $data['date_from'] . '"');
            $this->db->where('inv.created  <="' . $data['date_to'] . '"');
        }
        if ($this->common->decode($_GET['pid']) <> '')
        {
            $this->db->where('user.user_id', $this->common->decode($_GET['pid']));
        }

        $this->db->select('inv.*,user.email,user.full_name');
        $this->db->from($this->tbl_invoices . ' as inv');
        $this->db->join('c_users as user', 'user.user_id = inv.publisher_id', 'inner');
        if ($invoice_id <> '')
        {
            $this->db->where('invoice_id', $invoice_id);
        }
        $this->db->where('publisher_id <> 1');
        $this->db->order_by('inv.created', 'desc');

        $query = $this->db->get();
        $list  = $query->result_array();
        return $list;
    }

    public function getWithdraw($withdraw_id = '')
    {
        $sql_select_withdraw = ' Select withdraw.*,users.payment_type as u_payment_type,users.first_name,users.first_name,users.last_name,users.paypal_email,users.account_holder_name,users.account_number,users.iban_code,users.swift_code,users.bank_name,users.bank_address from ' . $this->db->dbprefix($this->tbl_withdraw) . ' as withdraw 
            INNER Join c_users as users on users.user_id = withdraw.affiliate_id
                          WHERE withdraw.id = ' . $withdraw_id;

        $query = $this->db->query($sql_select_withdraw);
        return $query->result_array();
    }

    public function getOrders($withdraw_id = '')
    {
        $sql_select_withdraw = ' Select withdraw.orders_ids as orders_ids from ' . $this->db->dbprefix($this->tbl_withdraw) . ' as withdraw 
                          WHERE withdraw.id = ' . $withdraw_id;

        $query     = $this->db->query($sql_select_withdraw);
        $order_ids = $query->row_array()['orders_ids'];


        $select = 'SELECT 
            pro.product_id as p_id,
            pro.product_name,
            products_commission.commission as prd_commission,
            typ.product_type as pro_type,
            orders.order_id as unique_order_id,
            orders.transaction_id as unique_transaction_id,
            orders.created ';


        $from = 'FROM '
                . 'c_orders as orders ';

        $join = 'INNER join c_products pro on pro.product_type = orders.product_id
            INNER join c_products_types typ on pro.product_type = typ.id
                 INNER join c_products_commission as products_commission on products_commission.product_id = pro.product_id
                 LEFT join c_user_commissions as user_commissions on user_commissions.order_id = orders.id 
                 LEFT join c_users as users on users.user_id = user_commissions.user_id ';

        $where = 'WHERE  orders.id IN(' . $order_ids . ')';


        $group_by = '   ';
        $order_by = ' ORDER BY orders.id DESC ';
        $limit    = ' ';

        $sql = $select . $from . $join . $where . $group_by . $order_by;

        $query = $this->db->query($sql);

        return $query->result_array();
    }

    public function UpdateWithdraw($withdraw_id, $withdraw_status)
    {
        $sql_select_withdraw = ' Select orders_ids from ' . $this->db->dbprefix($this->tbl_withdraw) . ' as withdraw 
                          WHERE withdraw.id = ' . $withdraw_id;
        $query               = $this->db->query($sql_select_withdraw);
        $orders_ids          = $query->row_array()['orders_ids'];

        $affiliate_id          = getVal('affiliate_id', 'c_withdraw', 'id', $withdraw_id);
        $affiliate_paymentType = getVal('payment_type', 'c_users', 'user_id', $affiliate_id);

        $sql_update_withdraw = ' UPDATE ' . $this->db->dbprefix($this->tbl_withdraw) . ' as withdraw 
                SET withdraw.status = ' . $withdraw_status . ' ,  withdraw.payment_type = ' . $affiliate_paymentType . '  
                          WHERE withdraw.id = ' . $withdraw_id;
        $this->db->query($sql_update_withdraw);

        $sql_update_orders = ' UPDATE ' . $this->db->dbprefix($this->tbl_orders) . ' as orders 
                SET orders.is_paid = ' . $withdraw_status . ' 
                          WHERE orders.id IN(' . $orders_ids . ')  ';
        return $this->db->query($sql_update_orders);
    }

    function savePaidOrdersList($orders_advertiser = '')
    {
        $data = array('advertisers' => $orders_advertiser);
        $this->db->insert('paid_payments', $data);
        return $this->db->insert_id();
    }

    public function getInvoice($invoice_id)
    {



        $sql_  = "SELECT * FROM " . $this->db->dbprefix($this->tbl_invoices) . " "
                . "WHERE invoice_id = " . $invoice_id . "  ";
        $query = $this->db->query($sql_);
        return $query->row_array();
    }

    function getPreviousOrders($publisher_id = '', $paymentSettingType = '')
    {
        $ifNoInvoice = getValArray('invoice_id', $this->db->dbprefix($this->tbl_invoices), 'publisher_id', $publisher_id);
        $select      = $from        = $join        = $where       = $group_by    = $order_by    = $limit       = '';



        $select = 'SELECT 
            currency.currency_name,currency.currency_symbol,pro.currency as invoice_currency,
            pro.product_id as p_id,
            pro.product_name,
            pro.currency as p_currency,
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
            $select .= ' , UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 MONTH) as from_invoice_date,UNIX_TIMESTAMP(CURDATE()) as to_invoice_date  ';
        }
        else if ((trim(strtolower($paymentSettingType)) == 'biweekly' || $paymentSettingType == 1) && (!empty($ifNoInvoice) && sizeof($ifNoInvoice) > 1))
        {
            $select .= ' , UNIX_TIMESTAMP(CURDATE() - INTERVAL ' . getBiweeklyRemainingDaysByMonth() . ' DAY)  as from_invoice_date,UNIX_TIMESTAMP(CURDATE()) as to_invoice_date ';
        }
        else
        {
            if ((trim(strtolower($paymentSettingType)) == 'biweekly' || $paymentSettingType == 1))
            {
                $select .= ' , UNIX_TIMESTAMP(CURDATE() - INTERVAL ' . getBiweeklyRemainingDaysByMonth() . ' DAY)  as from_invoice_date,UNIX_TIMESTAMP(CURDATE()) as to_invoice_date   ';
            }
            else if ((trim(strtolower($paymentSettingType)) == 'month' || $paymentSettingType == 2))
            {
                $select .= ' , UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 MONTH) as from_invoice_date,UNIX_TIMESTAMP(CURDATE()) as to_invoice_date   ';
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
                  AND pro.status = 1 
                  AND orders.order_status = 2 
                  AND orders.is_confirmed = 1 
                  AND user_commissions.is_paid = 0 AND orders.is_paid = 0  ';
        if ((trim(strtolower($paymentSettingType)) == 'month' || $paymentSettingType == 2) && (!empty($ifNoInvoice) && sizeof($ifNoInvoice) > 1))
        {
            $where .= ' AND (orders.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 MONTH)) ';
        }
        else if ((trim(strtolower($paymentSettingType)) == 'biweekly' || $paymentSettingType == 1) && (!empty($ifNoInvoice) && sizeof($ifNoInvoice) > 1))
        {
            $where .= ' AND (orders.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL ' . getBiweeklyRemainingDaysByMonth() . ' DAY)) ';
        }
        else
        {
            $where .= ' ';
        }

        if ($publisher_id <> '')
        {
            $where .= ' AND pro.user_id = ' . $publisher_id . ' ';
        }

        $group_by = ' GROUP BY pro.product_id  ';
        $order_by = ' ORDER BY orders.id DESC ';
        $limit    = ' ';

        $sql = $select . $from . $join . $where . $group_by . $order_by;

        $query = $this->db->query($sql);
        return $query;
    }

}

//End Class