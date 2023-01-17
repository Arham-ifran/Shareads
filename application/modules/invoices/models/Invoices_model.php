<?php

//

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Invoices_model extends CI_Model
{

    var $tbl                = 'invoices';
    var $tbl_invoice_orders = 'invoice_orders';
    var $directory_name     = 'shareads_invoices';

    public function __construct()
    {
        parent::__construct();
    }

    function loadListings($data)
    {

        $where   = '  invoice.publisher_id = ' . $this->session->userdata('user_id');
        $sql_    = 'SELECT
                   * from ' . $this->db->dbprefix . $this->tbl . ' as invoice 
                        where ' . $where . '
		';
        $perpage = 10;
        $offset  = 0;
        if ($this->uri->segment(3) > 0)
        {
            $offset = $this->uri->segment(3);
        }
        else
        {
            $offset = 0;
        }
        $query         = $this->db->query($sql_);
        $total_records = $query->num_rows();
        init_front_pagination('invoices/index', $total_records, $perpage);

        $sql_ .= "ORDER BY invoice.invoice_id DESC";
        $sql_ .= " LIMIT " . $offset . ", " . $perpage . "";
        $query = $this->db->query($sql_);
        return $query;
    }

    function getPublisher($user_id = '', $payment_type = '')
    {
        $select   = $from     = $join     = $where    = $group_by = $order_by = '';

        $select = 'SELECT 
            users.user_id as publisher_id,
            users.first_name,
            users.last_name,
            users.full_name,
            users.user_name,
            users.paypal_email,
            users.currency,
            users.email,
            users.gender,
            (CASE WHEN users.payment_schedule = 1 THEN "biweekly" ELSE "month" END) as payment_schedule,
            users.status,
            users.is_active,
            users.created
             ';

        $from = 'FROM '
                . 'c_users as users ';

        $join = '  ';

        $where = 'WHERE 1 
                  AND users.account_type = 2 
                  AND users.status = 1 
                  AND users.is_active = 1 ';
        if ($payment_type <> '')
        {
            $where .= 'AND users.payment_schedule = ' . $payment_type . ' ';
        }
        if ($user_id <> '')
        {
            $where .= 'AND users.user_id = ' . $user_id . ' ';
        }

        $order_by = ' ORDER BY users.user_id ASC ';

        $sql = $select . $from . $join . $where . $group_by . $order_by;

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function getPublishers($payment_type = '')
    {
        $select   = $from     = $join     = $where    = $group_by = $order_by = '';

        $select = 'SELECT 
            users.user_id as publisher_id,
            users.first_name,
            users.last_name,
            users.full_name,
            users.user_name,
            users.paypal_email,
            users.currency,
            users.email,
            users.gender,
            (CASE WHEN users.payment_schedule = 1 THEN "biweekly" ELSE "month" END) as payment_schedule,
            users.status,
            users.is_active,
            users.created
             ';

        $from = 'FROM '
                . 'c_users as users ';

        $join = '  ';

        $where = 'WHERE 1 
                  AND users.account_type = 2 
                  AND users.status = 1 
                  AND users.is_active = 1 ';
        if ($payment_type <> '')
        {
            $where .= 'AND users.payment_schedule = ' . $payment_type . ' ';
        }

        $order_by = ' ORDER BY users.user_id ASC ';

        $sql = $select . $from . $join . $where . $group_by . $order_by;

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function getPreviousOrders($publisher_id = '', $paymentSettingType = '')
    {
        $ifNoInvoice = getValArray('invoice_id', $this->db->dbprefix($this->tbl), 'publisher_id', $publisher_id);
//        dd($ifNoInvoice);
        $select      = $from        = $join        = $where       = $group_by    = $order_by    = $limit       = '';



        $select = 'SELECT 
            currency.currency_name,currency.currency_symbol,pro.currency as invoice_currency,
            pro.product_id as p_id,
            pro.product_name,
            pro.currency as p_currency,
            products_commission.commission as prd_commission,
            SUM(user_commissions.total_commission) as total_commision_sum,
            typ.product_type as pro_type,
            orders.id as unique_order_id,
            GROUP_CONCAT(orders.id) as order_ids,
            orders.transaction_id as unique_transaction_id,
            count(orders.id) as counter,
            orders.created ';

        if ((trim(strtolower($paymentSettingType)) == 'month' || $paymentSettingType == 2) && (!empty($ifNoInvoice) && sizeof($ifNoInvoice) > 1))
        {
//            $select .= ' , UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 MONTH) as from_invoice_date,UNIX_TIMESTAMP(CURDATE()) as to_invoice_date  ';
            $select .= ' , UNIX_TIMESTAMP(concat(date_format(LAST_DAY(now() - interval 1 month),"%Y-%m-"),"01")) as from_invoice_date,UNIX_TIMESTAMP(LAST_DAY(NOW() - INTERVAL 1 MONTH)) as to_invoice_date  ';
        }
        else if ((trim(strtolower($paymentSettingType)) == 'biweekly' || $paymentSettingType == 1) && (!empty($ifNoInvoice) && sizeof($ifNoInvoice) > 1))
        {
            $select .= ' , UNIX_TIMESTAMP((case when (date_format(now(),"%d") <= 15) THEN CAST(DATE_FORMAT(NOW()- interval 1 month ,"%Y-%m-16") as DATE) ELSE concat(date_format(LAST_DAY(now()),"%Y-%m-"),"01") END))  as from_invoice_date,UNIX_TIMESTAMP((case when (date_format(now(),"%d") <= 15) THEN LAST_DAY(NOW()- interval 1 month) ELSE CAST(DATE_FORMAT(NOW() ,"%Y-%m-15") as DATE) END)) as to_invoice_date ';
        }
        else
        {
            if ((trim(strtolower($paymentSettingType)) == 'biweekly' || $paymentSettingType == 1))
            {
//                $select .= ' , UNIX_TIMESTAMP(CURDATE() - INTERVAL ' . getBiweeklyRemainingDaysByMonth() . ' DAY)  as from_invoice_date,UNIX_TIMESTAMP(CURDATE()) as to_invoice_date   ';
                $select .= ' , UNIX_TIMESTAMP((case when (date_format(now(),"%d") <= 15) THEN CAST(DATE_FORMAT(NOW()- interval 1 month ,"%Y-%m-16") as DATE) ELSE concat(date_format(LAST_DAY(now()),"%Y-%m-"),"01") END))  as from_invoice_date,UNIX_TIMESTAMP((case when (date_format(now(),"%d") <= 15) THEN LAST_DAY(NOW()- interval 1 month) ELSE CAST(DATE_FORMAT(NOW() ,"%Y-%m-15") as DATE) END)) as to_invoice_date ';
            }
            else if ((trim(strtolower($paymentSettingType)) == 'month' || $paymentSettingType == 2))
            {
//                $select .= ' , UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 MONTH) as from_invoice_date,UNIX_TIMESTAMP(CURDATE()) as to_invoice_date   ';
                $select .= ' , UNIX_TIMESTAMP(concat(date_format(LAST_DAY(now() - interval 1 month),"%Y-%m-"),"01")) as from_invoice_date,UNIX_TIMESTAMP(LAST_DAY(NOW() - INTERVAL 1 MONTH)) as to_invoice_date   ';
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
                  AND orders.is_invoice_generated = 0
                  AND user_commissions.is_paid = 0 AND orders.is_paid = 0  ';

        if ((trim(strtolower($paymentSettingType)) == 'month' || $paymentSettingType == 2) && (!empty($ifNoInvoice) && sizeof($ifNoInvoice) > 0))
        {
//            $where .= ' AND (orders.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 MONTH)) ';
            $where .= ' AND ( ( (orders.created >= UNIX_TIMESTAMP(concat(date_format(LAST_DAY(now() - interval 1 month),"%Y-%m-"),"01"))) ';
            $where .= ' AND (orders.created <= UNIX_TIMESTAMP(LAST_DAY(NOW() - INTERVAL 1 MONTH)))) ';
            $where .= ' OR ( (orders.created <= UNIX_TIMESTAMP(LAST_DAY(NOW() - INTERVAL 1 MONTH))) AND  user_commissions.is_paid = 0 ) ) ';
        }
        else if ((trim(strtolower($paymentSettingType)) == 'biweekly' || $paymentSettingType == 1) && (!empty($ifNoInvoice) && sizeof($ifNoInvoice) > 0))
        {
//            $where .= ' AND (orders.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL ' . getBiweeklyRemainingDaysByMonth() . ' DAY)) ';
            $where .= '  AND ( (  (orders.created >= UNIX_TIMESTAMP((case when (date_format(now(),"%d") <= 15) THEN CAST(DATE_FORMAT(NOW()- interval 1 month ,"%Y-%m-16") as DATE) ELSE concat(date_format(LAST_DAY(now()),"%Y-%m-"),"01") END)) ';
            $where .= ' AND (orders.created <= UNIX_TIMESTAMP((case when (date_format(now(),"%d") <= 15) THEN LAST_DAY(NOW()- interval 1 month) ELSE CAST(DATE_FORMAT(NOW() ,"%Y-%m-15") as DATE) END)))))  ';
            $where .= ' OR ( (orders.created <= UNIX_TIMESTAMP((case when (date_format(now(),"%d") <= 15) THEN LAST_DAY(NOW()- interval 1 month) ELSE CAST(DATE_FORMAT(NOW() ,"%Y-%m-15") as DATE) END))  AND user_commissions.is_paid = 0 ) )) ';
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

//        echo $sql;die();

        $query = $this->db->query($sql);
        return $query;
    }
    
    function getPreviousOrdersDetails($publisher_id = '', $invoice_id = '')
    {
        $ifNoInvoice = getValArray('invoice_id', $this->db->dbprefix($this->tbl), 'publisher_id', $publisher_id);
        $invoice_details = getValArray('*', $this->db->dbprefix($this->tbl), 'invoice_id', $invoice_id);
        $invoice_order_ids = getValArray('GROUP_CONCAT(order_ids) as order_ids','c_invoice_orders', 'invoice_id', $invoice_id)['order_ids'];
        $paymentSettingType = $invoice_details['payment_schedule'];
        $select      = $from        = $join        = $where       = $group_by    = $order_by    = $limit       = '';



        $select = 'SELECT 
            currency.currency_name,currency.currency_symbol,pro.currency as invoice_currency,
            pro.product_id as p_id,
            pro.product_name,
            pro.currency as p_currency,
            products_commission.commission as prd_commission,
            SUM(user_commissions.total_commission) as total_commision_sum,
            typ.product_type as pro_type,
            orders.id as unique_order_id,
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

        $sql = $select . $from . $join . $where . $group_by . $order_by;

//        echo $sql;die();

        $query = $this->db->query($sql);
        return $query;
    }

    public function save_invoice($user, $list, $total_commission_sum)
    {
        $list_result          = $list->result_array();
        $user_invoice_details = $list->row_array();
        $total_commission_sum = 0;

        foreach ($list_result as $key => $value)
        {
//            $total_commission_sum += (double) $value['total_commision_sum'];
            $total_commission_sum += (double) get_currency_rate($value['total_commision_sum'], $value['p_currency'], $user['currency']);
        }
//        dd($total_commission_sum);
        $invoice_insert                     = array();
        $invoice_insert['invoice_number']   = 'INV-' . time();
        $invoice_insert['publisher_id']     = $user['publisher_id'];
        $invoice_insert['payment_schedule'] = (trim($user['payment_schedule']) == 'biweekly') ? 1 : 2;
        $invoice_insert['from_datetime']    = ($user_invoice_details['from_invoice_date'] <> '') ? $user_invoice_details['from_invoice_date'] : $user['created'];
        $invoice_insert['to_datetime']      = ($user_invoice_details['to_invoice_date'] <> '') ? $user_invoice_details['to_invoice_date'] : time();
//        $invoice_insert['invoice_amount']   = (double) $total_commission_sum;
        $invoice_insert['invoice_amount']   = $total_commission_sum;
        $invoice_insert['payed_amount']     = 0;
        $invoice_insert['file']             = $this->directory_name . '/' . date('d-M-Y') . '/' . $invoice_insert['invoice_number'] . '.pdf';
        $invoice_insert['status']           = 0;
        $invoice_insert['invoice_currency'] = ((CURRENCY <> $user['currency']) ? $user['currency'] : CURRENCY );
        $invoice_insert['payment_date']     = 0;
        $invoice_insert['full_details']     = serialize($list_result);
        $invoice_insert['due_date']         = strtotime('+7 day');
        $invoice_insert['created']          = time();
        $this->db->insert($this->tbl, $invoice_insert);
        $invoice_id                         = $this->db->insert_id();

        foreach ($list_result as $key => $value)
        {
            $invoice_orders               = array();
            $invoice_orders['invoice_id'] = $invoice_id;
            $invoice_orders['product_id'] = $value['p_id'];
            $invoice_orders['order_ids']  = $value['order_ids'];
            $invoice_orders['created']    = time();
            $this->db->insert($this->tbl_invoice_orders, $invoice_orders);
        }

        return $invoice_insert['invoice_number'];
    }

    public function getExistingInvoice($publisher_id, $from, $to)
    {
        if ($from <> '' && $to <> '')
        {
            $where = " AND from_datetime = " . $from . " AND to_datetime =  " . $to . "  ";
        }
        $sql_ = "SELECT invoice_id FROM " . $this->db->dbprefix($this->tbl) . " "
                . "WHERE publisher_id = '" . $publisher_id . "' " . $where . " ";

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

    public function getInvoice($invoice_id)
    {
        $sql_  = "SELECT * FROM " . $this->db->dbprefix($this->tbl) . " "
                . "WHERE publisher_id = " . $this->session->userdata('user_id') . " AND invoice_id = " . $invoice_id . "  ";
        $query = $this->db->query($sql_);
        return $query->row_array();
    }

    public function update_invoice($invoice_number, $status)
    {
        $this->db->where('invoice_number', $invoice_number);
        $this->db->update($this->db->dbprefix($this->tbl), array('status' => $status));
    }

    public function save_payment($return)
    {
        $user_id = $this->session->userdata('user_id');

        $payment_insert['payer_id']       = $return['payer_id'];
        $payment_insert['first_name']     = $return['first_name'];
        $payment_insert['last_name']      = $return['last_name'];
        $payment_insert['txn_id']         = $return['txn_id'];
        $payment_insert['payment_fee']    = ($return['payment_fee'] <> '') ? $return['payment_fee'] : '';
        $payment_insert['payment_gross']  = $return['payment_gross'];
        $payment_insert['payment_status'] = $return['payment_status'];
        $payment_insert['payment_type']   = $return['payment_type'];
        $payment_insert['item_name']      = $return['item_name'];
        $payment_insert['invoice_number'] = $return['item_number'];
        $payment_insert['quantity']       = $return['quantity'];
        $payment_insert['txn_type']       = $return['txn_type'];
        $payment_insert['payment_date']   = $return['payment_date'];
        $payment_insert['receiver_id']    = $return['receiver_id'];
        $payment_insert['verify_sign']    = $return['verify_sign'];
        $payment_insert['payer_email']    = $return['payer_email'];
        $payment_insert['created']        = time();
        $payment_insert['user_id']        = $user_id;
        $this->db->insert('payment', $payment_insert);

        $this->update_invoice($return['item_number'], 1);
    }

    public function get_invoices_expired($after_expired_days = '')
    {
        if ($after_expired_days <> '')
        {
            $sql_ = 'select inv.*,usr.full_name,usr.email ';
            $sql_ .='from c_invoices as inv ';
            $sql_ .='inner join c_users as usr ON usr.user_id = inv.publisher_id ';
            $sql_ .='where ((UNIX_TIMESTAMP(from_unixtime(inv.due_date, "%Y-%m-%d") + INTERVAL ' . $after_expired_days . ' day)) = UNIX_TIMESTAMP(CURDATE())';
            $sql_ .='AND inv.due_date < UNIX_TIMESTAMP(NOW())) AND inv.status = 0';

            return $this->db->query($sql_)->result_array();
        }
        else
        {
            return array();
        }
    }

    //    public function save_payment($return)
//    {
//        $user_id = $this->session->userdata('user_id');
//        $payment_insert['txn_id']         = $return['tx'];
//        $payment_insert['payment_status'] = $return['st'];
//        $payment_insert['payment_type']   = $return['cc'];
//        $payment_insert['item_name']      = $return['item_name'];
//        $payment_insert['invoice_number'] = $return['item_number'];
//        $payment_insert['created']    = time();
//        $payment_insert['user_id']        = $user_id;
//        $this->db->insert('payment', $payment_insert);
//
//        $this->update_invoice($return['item_number'], 1);
//    }
}

//End Class

/*
 * 
 * SELECT 
 * concat(date_format(LAST_DAY(now() - interval 1 month),"%Y-%m-"),"01") as first_date, 
 * CAST(DATE_FORMAT(NOW()- interval 1 month ,'%Y-%m-15') as DATE) as middle_date_1, 
 * CAST(DATE_FORMAT(NOW()- interval 1 month ,'%Y-%m-16') as DATE) as middle_date_2, 
 * LAST_DAY(NOW() - INTERVAL 1 MONTH) as last_date
 * 
 * 
 * 
 */