<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Reporting_model extends CI_Model
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



    function getTotalLinksSharedCounter($user_id, $filter)
    {
        $user_currency = getVal('currency', 'c_users', 'user_id', $user_id);
        $whr           = '';

        if (!empty($filter))
        {
            $time = $filter['time'];
            if ($time == 'today')
            {
                $whr = '(shr.created >= UNIX_TIMESTAMP(CURDATE())
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '7days')
            {
                $whr = '(shr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 7 day)
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '30days')
            {
                $whr = '(shr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 MONTH)
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '6months')
            {
                $whr = '(shr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 6 MONTH)
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '1year')
            {
                $whr = '(shr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 YEAR)
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == 'custom')
            {

                $whr = '(shr.created >= UNIX_TIMESTAMP("' . $filter['start'] . '")
   AND shr.created <  UNIX_TIMESTAMP("' . $filter['end'] . '"))';
            }
            else
            {
                $whr = '(shr.created >= UNIX_TIMESTAMP(CURDATE())
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
            }
        }
        else
        {
            $whr = '(shr.created >= UNIX_TIMESTAMP(CURDATE())
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
        }


        $query = "SELECT
				  count(share_counter) as counter
				FROM
				" . $this->db->dbprefix($this->tbl_products_links_share) . " shr

 INNER JOIN
                               " . $this->db->dbprefix($this->tbl) . " as res
                        ON
                               (res.user_id = shr.user_id)

WHERE   shr.user_id = " . $user_id . " AND " . $whr . "

          limit 1";
        $query = $this->db->query($query);
        $row   = $query->row();
        return $row->counter;
    }

    function getTotalVisitorsCounter($user_id, $filter)
    {
        $user_currency = getVal('currency', 'c_users', 'user_id', $user_id);
        $whr           = $whr1          = '';

        if (!empty($filter))
        {
            $time = $filter['time'];
            if ($time == 'today')
            {
                $whr  = '(shr.created >= UNIX_TIMESTAMP(CURDATE())
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
                $whr1 = '(track.timestamp >= UNIX_TIMESTAMP(CURDATE())
   AND track.timestamp <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '7days')
            {
                $whr  = '(shr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 7 day)
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
                $whr1 = '(track.timestamp >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 7 day)
   AND track.timestamp <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '30days')
            {
                $whr  = '(shr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 MONTH)
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
                $whr1 = '(track.timestamp >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 MONTH)
   AND track.timestamp <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '6months')
            {
                $whr  = '(shr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 6 MONTH)
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
                $whr1 = '(track.timestamp >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 6 MONTH)
   AND track.timestamp <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '1year')
            {
                $whr  = '(shr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 YEAR)
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
                $whr1 = '(track.timestamp >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 YEAR)
   AND track.timestamp <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == 'custom')
            {
                $whr = '(shr.created >= UNIX_TIMESTAMP("' . $filter['start'] . '")
   AND shr.created <  UNIX_TIMESTAMP("' . $filter['end'] . '"))';

                $whr1 = '(track.timestamp >= UNIX_TIMESTAMP("' . $filter['start'] . '")
   AND track.timestamp <  UNIX_TIMESTAMP("' . $filter['end'] . '"))';
            }
            else
            {
                $whr  = '(shr.created >= UNIX_TIMESTAMP(CURDATE())
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
                $whr1 = '(track.timestamp >= UNIX_TIMESTAMP(CURDATE())
   AND track.timestamp <  UNIX_TIMESTAMP(NOW()))';
            }
        }
        else
        {
            $whr  = '(shr.created >= UNIX_TIMESTAMP(CURDATE())
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
            $whr1 = '(track.timestamp >= UNIX_TIMESTAMP(CURDATE())
   AND track.timestamp <  UNIX_TIMESTAMP(NOW()))';
        }


        $query = "SELECT
			 DISTINCT shr.product_id
				FROM
				" . $this->db->dbprefix($this->tbl_products_links_share) . " shr
 INNER JOIN
                               " . $this->db->dbprefix($this->tbl) . " as res
                        ON
                               (res.user_id = shr.user_id)

WHERE   shr.user_id = " . $user_id . "

          ";
        $q     = $this->db->query($query);
//        dd($q->num_rows());
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

AND " . $whr1 . "
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

    function getTotalSaleCounter($user_id, $filter)
    {
        $user_currency = getVal('currency', 'c_users', 'user_id', $user_id);
        $whr           = '';

        if (!empty($filter))
        {
            $time = $filter['time'];
            if ($time == 'today')
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE())
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '7days')
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 7 day)
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '30days')
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 MONTH)
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '6months')
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 6 MONTH)
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '1year')
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 YEAR)
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == 'custom')
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP("' . $filter['start'] . '")
   AND ordr.created <  UNIX_TIMESTAMP("' . $filter['end'] . '"))';
            }
            else
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE())
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
            }
        }
        else
        {
            $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE())
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
        }


        $query = "SELECT
				group_concat(ordr.price) as counter,group_concat(pro.currency) as currency_id,(Select c_currencies.currency_symbol from c_currencies where c_currencies.currency_id = pro.currency) as currency_symbol
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
                . " AND ordr.order_status = 2    AND ordr.is_confirmed = 1 "
                . " AND " . $whr . "
                        ";
//        echo $query;die();
        $query = $this->db->query($query);
        $row   = $query->row_array();

        $currency_ids = explode(',', $row['currency_id']);
        $values       = explode(',', $row['counter']);
        $counter      = 0;
        foreach ($currency_ids as $key => $value)
        {
            $counter += get_currency_rate($values[$key], $value, $user_currency);
        }
        return $counter;
    }

    function getTotalSuccessLeadCounter($user_id, $filter)
    {
        $user_currency = getVal('currency', 'c_users', 'user_id', $user_id);
        $whr           = '';

        if (!empty($filter))
        {
            $time = $filter['time'];
            if ($time == 'today')
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE())
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '7days')
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 7 day)
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '30days')
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 MONTH)
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '6months')
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 6 MONTH)
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '1year')
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 YEAR)
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == 'custom')
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP("' . $filter['start'] . '")
   AND ordr.created <  UNIX_TIMESTAMP("' . $filter['end'] . '"))';
            }
            else
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE())
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
            }
        }
        else
        {
            $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE())
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
        }


        $query = "SELECT
				count(id) as counter
			FROM
				" . $this->db->dbprefix($this->tbl_orders) . " as ordr
                    INNER JOIN
                               " . $this->db->dbprefix($this->tbl) . " res
                        ON
                                (res.user_id = ordr.advertiser_id)
                    WHERE   ordr.advertiser_id = " . $user_id . ""
                . " AND ordr.order_status = 2    AND ordr.is_confirmed = 1 "
                . " AND " . $whr . "
                        limit 1";
//        echo date('d-m-Y h:m:s');die();
        $query = $this->db->query($query);
        $row   = $query->row();
        return number_format($row->counter, 2);
    }

    function getTotalCommissionCounter($user_id, $filter)
    {

//       dd($filter);

        $user_currency = getVal('currency', 'c_users', 'user_id', $user_id);
        $whr           = '';

        if (!empty($filter))
        {
            $time = $filter['time'];
            if ($time == 'today')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE())
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '7days')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 7 day)
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '30days')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 MONTH)
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '6months')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 6 MONTH)
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '1year')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 YEAR)
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == 'custom')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP("' . $filter['start'] . '")
   AND comm.created <  UNIX_TIMESTAMP("' . $filter['end'] . '"))';
            }
            else
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE())
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
        }
        else
        {
            $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE())
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
        }


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

WHERE   comm.user_id = " . $user_id . "
                AND ordr.order_status = 2   AND ordr.is_confirmed = 1
    AND " . $whr . "
         ";
        $query = $this->db->query($query);
        $row   = $query->row_array();

        $currency_ids = explode(',', $row['currency_id']);
        $values       = explode(',', $row['counter']);
        $counter      = 0;
        foreach ($currency_ids as $key => $value)
        {
            $counter += get_currency_rate($values[$key], $value, $user_currency);
        }
        return getSiteCurrencySymbol('', $user_currency) . number_format($counter, 2);
    }

    function getTotaltotalCommissionEarnedCounter($user_id, $filter)
    {
        $user_currency = getVal('currency', 'c_users', 'user_id', $user_id);
        $whr           = '';

        if (!empty($filter))
        {
            $time = $filter['time'];
            if ($time == 'today')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE())
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '7days')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 7 day)
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '30days')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 MONTH)
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '6months')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 6 MONTH)
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '1year')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 YEAR)
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == 'custom')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP("' . $filter['start'] . '")
   AND comm.created <  UNIX_TIMESTAMP("' . $filter['end'] . '"))';
            }
            else
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE())
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
        }
        else
        {
            $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE())
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
        }


        $query = "SELECT
				  SUM(comm.advertiser_commission) as counter,pro.currency as currency_id,(Select c_currencies.currency_symbol from c_currencies where c_currencies.currency_id = pro.currency) as currency_symbol
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

WHERE   comm.user_id = " . $user_id . "
                AND ordr.order_status = 2   AND ordr.is_confirmed = 1  AND ordr.is_paid = 1
    AND " . $whr . "
        ";
//         echo $query;die();
        $query = $this->db->query($query);
        $row   = $query->result_array();
        //get_currency_rate(1,'GBP','USD')
//        dd($row);
        foreach ($row as $key => $value)
        {
            if ($user_currency <> $value['currency_id'] && $value['counter'] > 0)
            {
                $row[$key]['counter'] = get_currency_rate($value['counter'], $value['currency_id'], $user_currency);
            }
        }
        $counter = 0;
        foreach ($row as $key => $value)
        {
            $counter += $value['counter'];
        }

        return getSiteCurrencySymbol('', $user_currency) . number_format($counter, 2);
    }

    function getTotalUnsuccessfullCommisionCounter($user_id, $filter)
    {
        $user_currency = getVal('currency', 'c_users', 'user_id', $user_id);
        $whr           = '';

        if (!empty($filter))
        {
            $time = $filter['time'];
            if ($time == 'today')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE())
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '7days')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 7 day)
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '30days')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 MONTH)
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '6months')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 6 MONTH)
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '1year')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 YEAR)
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == 'custom')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP("' . $filter['start'] . '")
   AND comm.created <  UNIX_TIMESTAMP("' . $filter['end'] . '"))';
            }
            else
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE())
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
        }
        else
        {
            $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE())
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
        }


        $query = "SELECT
				  SUM(comm.advertiser_commission) as counter,pro.currency as currency_id,(Select c_currencies.currency_symbol from c_currencies where c_currencies.currency_id = pro.currency) as currency_symbol
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

WHERE   comm.user_id = " . $user_id . "
                AND ordr.order_status = 2   AND ordr.is_confirmed = 0 AND ordr.is_paid = 0 
    AND " . $whr . "
          ";
//         echo $query;die();
        $query = $this->db->query($query);
        $row   = $query->result_array();

        //get_currency_rate(1,'GBP','USD')
        foreach ($row as $key => $value)
        {
            if ($user_currency <> $value['currency_id'] && $value['counter'] > 0)
            {
                $row[$key]['counter'] = get_currency_rate($value['counter'], $value['currency_id'], $user_currency);
            }
        }
        $counter = 0;
        foreach ($row as $key => $value)
        {
            $counter += $value['counter'];
        }
//        dd($counter);
        return getSiteCurrencySymbol('', $user_currency) . number_format($counter, 2);
    }

    function getTotalLinksShared($user_id, $filter)
    {
        $user_currency = getVal('currency', 'c_users', 'user_id', $user_id);
        $whr           = '';

        if (!empty($filter))
        {
            $time = $filter['time'];
            if ($time == 'today')
            {
                $whr = '(shr.created >= UNIX_TIMESTAMP(CURDATE())
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '7days')
            {
                $whr = '(shr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 7 day)
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '30days')
            {
                $whr = '(shr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 MONTH)
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '6months')
            {
                $whr = '(shr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 6 MONTH)
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '1year')
            {
                $whr = '(shr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 YEAR)
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == 'custom')
            {
                $whr = '(shr.created >= UNIX_TIMESTAMP("' . $filter['start'] . '")
   AND shr.created <  UNIX_TIMESTAMP("' . $filter['end'] . '"))';
            }
            else
            {
                $whr = '(shr.created >= UNIX_TIMESTAMP(CURDATE())
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
            }
        }
        else
        {
            $whr = '(shr.created >= UNIX_TIMESTAMP(CURDATE())
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
        }


        $query = "SELECT
				  shr.*
				FROM
				" . $this->db->dbprefix($this->tbl_products_links_share) . " shr

 INNER JOIN
                               " . $this->db->dbprefix($this->tbl) . " as res
                        ON
                               (res.user_id = shr.user_id)

WHERE   shr.user_id = " . $user_id . " AND " . $whr . "

    GROUP BY from_unixtime(shr.created,'%Y %D %M'), product_id
    ORDER BY shr.id desc

          ";
        $query = $this->db->query($query);
        return $query->result_array();
    }

    function getShareLinkCounter($product_id, $created, $type, $user_id)
    {
        $user_currency = getVal('currency', 'c_users', 'user_id', $user_id);

        $query = "SELECT
				  count(share_counter) as counter
				FROM
				" . $this->db->dbprefix($this->tbl_products_links_share) . " shr

 INNER JOIN
                               " . $this->db->dbprefix($this->tbl) . " as res
                        ON
                               (res.user_id = shr.user_id)

WHERE   shr.user_id = " . $user_id . " AND "
                . " shr.product_id = " . $product_id . "  AND"
                . " shr.share_type = " . $type . " AND
                    from_unixtime(shr.created, '%Y-%m-%d') = '" . $created . "'

          limit 1";
//        echo $query;die();
        $query = $this->db->query($query);
        $row   = $query->row();
        if ($row->counter <> null)
        {
            return $row->counter;
        }
        else
        {
            return 0;
        }
    }
    
    function getShareLinkCounter_withURL($product_id, $created, $user_id)
    {
        $user_currency = getVal('currency', 'c_users', 'user_id', $user_id);

        $query = "SELECT
				  GROUP_CONCAT(link) as links
				FROM
				" . $this->db->dbprefix($this->tbl_products_links_share) . " shr

 INNER JOIN
                               " . $this->db->dbprefix($this->tbl) . " as res
                        ON
                               (res.user_id = shr.user_id)

WHERE   shr.user_id = " . $user_id . " AND "
                . " shr.product_id = " . $product_id . "  AND"
                 . "   from_unixtime(shr.created, '%Y-%m-%d') = '" . $created . "'

          limit 1";
//        echo $query;die();
        $query = $this->db->query($query);
        $row   = $query->row();
        if ($row->links <> null)
        {
            return $row->links;
        }
        else
        {
            return 0;
        }
    }

    function getTotalVisitors($user_id, $filter)
    {
        $user_currency = getVal('currency', 'c_users', 'user_id', $user_id);
        $whr           = $whr1          = '';

        if (!empty($filter))
        {
            $time = $filter['time'];
            if ($time == 'today')
            {
                $whr  = '(shr.created >= UNIX_TIMESTAMP(CURDATE())
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
                $whr1 = '(track.timestamp >= UNIX_TIMESTAMP(CURDATE())
   AND track.timestamp <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '7days')
            {
                $whr  = '(shr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 7 day)
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
                $whr1 = '(track.timestamp >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 7 day)
   AND track.timestamp <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '30days')
            {
                $whr  = '(shr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 MONTH)
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
                $whr1 = '(track.timestamp >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 MONTH)
   AND track.timestamp <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '6months')
            {
                $whr  = '(shr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 6 MONTH)
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
                $whr1 = '(track.timestamp >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 6 MONTH)
   AND track.timestamp <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '1year')
            {
                $whr  = '(shr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 YEAR)
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
                $whr1 = '(track.timestamp >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 YEAR)
   AND track.timestamp <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == 'custom')
            {
                $whr = '(shr.created >= UNIX_TIMESTAMP("' . $filter['start'] . '")
   AND shr.created <  UNIX_TIMESTAMP("' . $filter['end'] . '"))';

                $whr1 = '(track.timestamp >= UNIX_TIMESTAMP("' . $filter['start'] . '")
   AND track.timestamp <  UNIX_TIMESTAMP("' . $filter['end'] . '"))';
            }
            else
            {
                $whr  = '(shr.created >= UNIX_TIMESTAMP(CURDATE())
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
                $whr1 = '(track.timestamp >= UNIX_TIMESTAMP(CURDATE())
   AND track.timestamp <  UNIX_TIMESTAMP(NOW()))';
            }
        }
        else
        {
            $whr  = '(shr.created >= UNIX_TIMESTAMP(CURDATE())
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
            $whr1 = '(track.timestamp >= UNIX_TIMESTAMP(CURDATE())
   AND track.timestamp <  UNIX_TIMESTAMP(NOW()))';
        }


        $query = "SELECT
			 DISTINCT shr.product_id
				FROM
				" . $this->db->dbprefix($this->tbl_products_links_share) . " shr
 INNER JOIN
                               " . $this->db->dbprefix($this->tbl) . " as res
                        ON
                               (res.user_id = shr.user_id)

WHERE   shr.user_id = " . $user_id . "

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
				  *
				FROM
				" . $this->db->dbprefix($this->tbl_usertracking) . " track

WHERE   track.product_id IN (" . $ids . ")  AND user_identifier = '" . $user_identifier . "'

AND " . $whr1 . "
          ORDER BY track.id desc";
//             echo $query;die();
            $query           = $this->db->query($query);

//            dd($query->result_array());
            return $query->result_array();
        }
    }

    function getTotalSales($user_id, $filter)
    {
        $user_currency = getVal('currency', 'c_users', 'user_id', $user_id);
        $whr           = '';

        if (!empty($filter))
        {
            $time = $filter['time'];
            if ($time == 'today')
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE())
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '7days')
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 7 day)
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '30days')
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 MONTH)
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '6months')
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 6 MONTH)
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '1year')
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 YEAR)
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == 'custom')
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP("' . $filter['start'] . '")
   AND ordr.created <  UNIX_TIMESTAMP("' . $filter['end'] . '"))';
            }
            else
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE())
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
            }
        }
        else
        {
            $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE())
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
        }


        $query = "SELECT
				ordr.*,track.referer_page
			FROM
				" . $this->db->dbprefix($this->tbl_orders) . " as ordr
                    INNER JOIN
                               " . $this->db->dbprefix($this->tbl) . " res
                        ON
                                (res.user_id = ordr.advertiser_id)
   LEFT JOIN
                               " . $this->db->dbprefix($this->tbl_usertracking) . " track
                        ON
                                (track.id = ordr.user_tracking)
                                
INNER JOIN
                               " . $this->db->dbprefix($this->tbl_products) . " pro
                        ON
                                (pro.product_id = ordr.product_id)

                    WHERE   ordr.advertiser_id = " . $user_id . ""
                . " AND ordr.order_status = 2   AND ordr.is_confirmed = 1  AND  ordr.price <> 0 "
                . " AND " . $whr . "
                    GROUP BY ordr.id
                       ORDER BY id desc ";
        $query = $this->db->query($query);
        return $query->result_array();
    }

    function getTotalSuccessLeadSales($user_id, $filter)
    {
        $user_currency = getVal('currency', 'c_users', 'user_id', $user_id);
        $whr           = '';

        if (!empty($filter))
        {
            $time = $filter['time'];
            if ($time == 'today')
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE())
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '7days')
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 7 day)
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '30days')
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 MONTH)
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '6months')
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 6 MONTH)
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '1year')
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 YEAR)
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == 'custom')
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP("' . $filter['start'] . '")
   AND ordr.created <  UNIX_TIMESTAMP("' . $filter['end'] . '"))';
            }
            else
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE())
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
            }
        }
        else
        {
            $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE())
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
        }


        $query = "SELECT
				ordr.*,track.referer_page
			FROM
				" . $this->db->dbprefix($this->tbl_orders) . " as ordr
                    INNER JOIN
                               " . $this->db->dbprefix($this->tbl) . " res
                        ON
                                (res.user_id = ordr.advertiser_id)
                    LEFT JOIN
                               " . $this->db->dbprefix($this->tbl_usertracking) . " track
                        ON
                                (track.id = ordr.user_tracking)

                    WHERE   ordr.advertiser_id = " . $user_id . ""
                . " AND ordr.order_status = 2    AND ordr.is_confirmed = 1  "
                . " AND " . $whr . "
                    GROUP BY ordr.id
                       ORDER BY id desc ";

//         echo $query;die();
        $query = $this->db->query($query);
//        return $query->result_array();
        $row   = $query->result_array();
//                dd($row);
        foreach ($row as $key => $value)
        {
            $pro_currecny       = getVal('currency', 'c_products', array('product_id' => $value['product_id']));
            $row[$key]['price'] = getSiteCurrencySymbol('', $user_currency) . number_format(get_currency_rate($value['price'], $pro_currecny, $user_currency), 2);
            unset($row[$key]['currency']);
        }
//        dd($row);
        ///////////////////////////////////
//        dd($query->result_array());
        return $row;
    }

    function getTotalCommission($user_id, $filter)
    {
        $user_currency = getVal('currency', 'c_users', 'user_id', $user_id);
        $whr           = '';

        if (!empty($filter))
        {
            $time = $filter['time'];
            if ($time == 'today')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE())
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '7days')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 7 day)
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '30days')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 MONTH)
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '6months')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 6 MONTH)
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '1year')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 YEAR)
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == 'custom')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP("' . $filter['start'] . '")
   AND comm.created <  UNIX_TIMESTAMP("' . $filter['end'] . '"))';
            }
            else
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE())
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
        }
        else
        {
            $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE())
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
        }


        $query  = "SELECT
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
 LEFT JOIN
                               " . $this->db->dbprefix($this->tbl_usertracking) . " track
                        ON
                                (track.id = ordr.user_tracking)

WHERE   comm.user_id = " . $user_id . "
                AND ordr.order_status = 2   AND ordr.is_confirmed = 1 
    AND " . $whr . "
        GROUP BY ordr.id
          ORDER BY id desc";
        $query  = $this->db->query($query);
        $result = $query->result_array();
        foreach ($result as $key => $value)
        {
            $pro_currency                          = getVal('currency', 'c_products', array('product_id' => $value['product_id']));
            $result[$key]['advertiser_commission'] = getSiteCurrencySymbol('', $user_currency) . number_format(get_currency_rate($result[$key]['advertiser_commission'], $pro_currency, $user_currency), 2);
        }
//        dd($result);
        return $result;
    }

    /*     * *****************PUBLISHER***************************** */
    /*     * *****************PUBLISHER***************************** */
    /*     * *****************PUBLISHER***************************** */

    function getTotalProducts($user_id, $filter)
    {
        $user_currency = getVal('currency', 'c_users', 'user_id', $user_id);
        $whr           = '';

        if (!empty($filter))
        {
            $time = $filter['time'];
            if ($time == 'today')
            {
                $whr = '(pro.created >= UNIX_TIMESTAMP(CURDATE())
   AND pro.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '7days')
            {
                $whr = '(pro.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 7 day)
   AND pro.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '30days')
            {
                $whr = '(pro.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 MONTH)
   AND pro.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '6months')
            {
                $whr = '(pro.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 6 MONTH)
   AND pro.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '1year')
            {
                $whr = '(pro.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 YEAR)
   AND pro.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == 'custom')
            {

                $whr = '(pro.created >= UNIX_TIMESTAMP("' . $filter['start'] . '")
   AND pro.created <  UNIX_TIMESTAMP("' . $filter['end'] . '"))';
            }
            else
            {
                $whr = '(pro.created >= UNIX_TIMESTAMP(CURDATE())
   AND pro.created <  UNIX_TIMESTAMP(NOW()))';
            }
        }
        else
        {
            $whr = '(pro.created >= UNIX_TIMESTAMP(CURDATE())
   AND pro.created <  UNIX_TIMESTAMP(NOW()))';
        }



        $query = "SELECT
				  count(*) as counter
				FROM
				" . $this->db->dbprefix($this->tbl_products) . " pro

 INNER JOIN
                               " . $this->db->dbprefix($this->tbl) . " as res
                        ON
                               (res.user_id = pro.user_id)

WHERE   pro.user_id = " . $user_id . " AND
    " . $whr . "
          limit 1";
        $query = $this->db->query($query);
        $row   = $query->row();
        return $row->counter;
    }

    function getTotalPublisherLinksSharedCounter($user_id, $filter)
    {
        $user_currency = getVal('currency', 'c_users', 'user_id', $user_id);
        $whr           = '';

        if (!empty($filter))
        {
            $time = $filter['time'];
            if ($time == 'today')
            {
                $whr = '(shr.created >= UNIX_TIMESTAMP(CURDATE())
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '7days')
            {
                $whr = '(shr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 7 day)
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '30days')
            {
                $whr = '(shr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 MONTH)
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '6months')
            {
                $whr = '(shr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 6 MONTH)
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '1year')
            {
                $whr = '(shr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 YEAR)
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == 'custom')
            {

                $whr = '(shr.created >= UNIX_TIMESTAMP("' . $filter['start'] . '")
   AND shr.created <  UNIX_TIMESTAMP("' . $filter['end'] . '"))';
            }
            else
            {
                $whr = '(shr.created >= UNIX_TIMESTAMP(CURDATE())
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
            }
        }
        else
        {
            $whr = '(shr.created >= UNIX_TIMESTAMP(CURDATE())
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
        }


        $query = "SELECT
				  count(share_counter) as counter
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

WHERE   pro.user_id = " . $user_id . " AND " . $whr . "

          limit 1";
        $query = $this->db->query($query);
        $row   = $query->row();
        return $row->counter;
    }

    function getTotalPublisherVisitorsCounter($user_id, $filter)
    {
        $user_currency = getVal('currency', 'c_users', 'user_id', $user_id);
        $whr           = $whr1          = '';

        if (!empty($filter))
        {
            $time = $filter['time'];
            if ($time == 'today')
            {
                $whr  = '(shr.created >= UNIX_TIMESTAMP(CURDATE())
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
                $whr1 = '(track.timestamp >= UNIX_TIMESTAMP(CURDATE())
   AND track.timestamp <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '7days')
            {
                $whr  = '(shr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 7 day)
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
                $whr1 = '(track.timestamp >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 7 day)
   AND track.timestamp <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '30days')
            {
                $whr  = '(shr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 MONTH)
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
                $whr1 = '(track.timestamp >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 MONTH)
   AND track.timestamp <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '6months')
            {
                $whr  = '(shr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 6 MONTH)
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
                $whr1 = '(track.timestamp >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 6 MONTH)
   AND track.timestamp <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '1year')
            {
                $whr  = '(shr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 YEAR)
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
                $whr1 = '(track.timestamp >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 YEAR)
   AND track.timestamp <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == 'custom')
            {
                $whr = '(shr.created >= UNIX_TIMESTAMP("' . $filter['start'] . '")
   AND shr.created <  UNIX_TIMESTAMP("' . $filter['end'] . '"))';

                $whr1 = '(track.timestamp >= UNIX_TIMESTAMP("' . $filter['start'] . '")
   AND track.timestamp <  UNIX_TIMESTAMP("' . $filter['end'] . '"))';
            }
            else
            {
                $whr  = '(shr.created >= UNIX_TIMESTAMP(CURDATE())
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
                $whr1 = '(track.timestamp >= UNIX_TIMESTAMP(CURDATE())
   AND track.timestamp <  UNIX_TIMESTAMP(NOW()))';
            }
        }
        else
        {
            $whr  = '(shr.created >= UNIX_TIMESTAMP(CURDATE())
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
            $whr1 = '(track.timestamp >= UNIX_TIMESTAMP(CURDATE())
   AND track.timestamp <  UNIX_TIMESTAMP(NOW()))';
        }


        $query = "SELECT
			 DISTINCT shr.product_id
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

            $ids = implode(',', array_filter($arr));

            $query = "SELECT
				  count(*) as counter
				FROM
				" . $this->db->dbprefix($this->tbl_usertracking) . " track

WHERE   track.product_id IN (" . $ids . ")

AND " . $whr1 . "
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

    function getTotalPublisherSaleCounter($user_id, $filter)
    {
        $user_currency = getVal('currency', 'c_users', 'user_id', $user_id);
        $user_currency = getVal('currency', 'c_users', 'user_id', $user_id);

        $whr = '';

        if (!empty($filter))
        {
            $time = $filter['time'];
            if ($time == 'today')
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE())
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '7days')
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 7 day)
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '30days')
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 MONTH)
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '6months')
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 6 MONTH)
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '1year')
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 YEAR)
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == 'custom')
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP("' . $filter['start'] . '")
   AND ordr.created <  UNIX_TIMESTAMP("' . $filter['end'] . '"))';
            }
            else
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE())
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
            }
        }
        else
        {
            $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE())
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
        }


        $query = "SELECT
				SUM(ordr.price) as counter, pro.currency as currency_id,(Select c_currencies.currency_symbol from c_currencies where c_currencies.currency_id = pro.currency) as currency_symbol
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

                    WHERE   ordr.seller_id = " . $user_id . " AND ordr.order_status = 2   AND ordr.is_confirmed = 1 "
                . " AND " . $whr . "
                       ";
//        echo $query;die('ok');
        $query = $this->db->query($query);
        $row   = $query->result_array();
        //get_currency_rate(1,'GBP','USD')
//        dd();
        foreach ($row as $key => $value)
        {
            if ($user_currency <> $value['currency_id'] && $value['counter'] > 0)
            {
                $row[$key]['counter'] = get_currency_rate($value['counter'], $value['currency_id'], $user_currency);
            }
        }
        $counter = 0;
        foreach ($row as $key => $value)
        {
            $counter += $value['counter'];
        }

        return number_format($counter, 2);
    }

    function getTotalPublisherSuccessLeadCounter($user_id, $filter)
    {
        $user_currency = getVal('currency', 'c_users', 'user_id', $user_id);
        $whr           = '';

        if (!empty($filter))
        {
            $time = $filter['time'];
            if ($time == 'today')
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE())
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '7days')
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 7 day)
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '30days')
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 MONTH)
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '6months')
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 6 MONTH)
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '1year')
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 YEAR)
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == 'custom')
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP("' . $filter['start'] . '")
   AND ordr.created <  UNIX_TIMESTAMP("' . $filter['end'] . '"))';
            }
            else
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE())
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
            }
        }
        else
        {
            $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE())
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
        }


        $query = "SELECT
				count(ordr.id) as counter
			FROM
				" . $this->db->dbprefix($this->tbl_orders) . " as ordr
                    INNER JOIN
                               " . $this->db->dbprefix($this->tbl) . " res
                        ON
                                (res.user_id = ordr.seller_id)

                    WHERE   ordr.seller_id = " . $user_id . " AND ordr.order_status = 2   AND ordr.is_confirmed = 1 "
                . " AND " . $whr . "
                        limit 1";
        $query = $this->db->query($query);
        $row   = $query->row();
        return $row->counter;
    }

    function getTotalPublisherCommissionCounter($user_id, $filter)
    {
        $user_currency = getVal('currency', 'c_users', 'user_id', $user_id);
        $whr           = '';

        if (!empty($filter))
        {
            $time = $filter['time'];
            if ($time == 'today')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE())
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '7days')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 7 day)
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '30days')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 MONTH)
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '6months')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 6 MONTH)
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '1year')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 YEAR)
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == 'custom')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP("' . $filter['start'] . '")
   AND comm.created <  UNIX_TIMESTAMP("' . $filter['end'] . '"))';
            }
            else
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE())
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
        }
        else
        {
            $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE())
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
        }


        $query = "SELECT SUM(comm.total_commission) as counter,pro.currency as currency_id,(Select c_currencies.currency_symbol from c_currencies where c_currencies.currency_id = pro.currency) as currency_symbol
                    FROM " . $this->db->dbprefix($this->tbl_user_commissions) . " comm
                    INNER JOIN " . $this->db->dbprefix($this->tbl_orders) . " as ordr ON (ordr.id = comm.order_id)
                    LEFT JOIN c_invoice_orders as cio ON  FIND_IN_SET(ordr.id, cio.order_ids) > 0
                    INNER JOIN c_invoices as inv ON inv.invoice_id = cio.invoice_id
                    LEFT JOIN c_payment as payment_inv ON payment_inv.invoice_number = inv.invoice_number
                    INNER JOIN " . $this->db->dbprefix($this->tbl_products) . " pro ON (pro.product_id = ordr.product_id)"
                . " WHERE   ordr.seller_id = " . $user_id . " AND inv.status = 1 AND ordr.order_status = 2 

    AND " . $whr . "
         ";
        $query = $this->db->query($query);
        $row   = $query->result_array();

        foreach ($row as $key => $value)
        {
            if ($user_currency <> $value['currency_id'] && $value['counter'] > 0)
            {
                $row[$key]['counter'] = get_currency_rate($value['counter'], $value['currency_id'], $user_currency);
            }
        }
        $counter = 0;
        foreach ($row as $key => $value)
        {
            $counter += $value['counter'];
        }
//        echo getSiteCurrencySymbol('', $user_currency) . number_format($counter, 2);die();
        return getSiteCurrencySymbol('', $user_currency) . number_format($counter, 2);
        ;
    }

    function getTotalSuccessSalesCommissionCounter($user_id, $filter)
    {
        $user_currency = getVal('currency', 'c_users', 'user_id', $user_id);
        $whr           = '';

        if (!empty($filter))
        {
            $time = $filter['time'];
            if ($time == 'today')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE())
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '7days')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 7 day)
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '30days')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 MONTH)
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '6months')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 6 MONTH)
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '1year')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 YEAR)
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == 'custom')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP("' . $filter['start'] . '")
   AND comm.created <  UNIX_TIMESTAMP("' . $filter['end'] . '"))';
            }
            else
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE())
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
        }
        else
        {
            $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE())
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
        }


        $query = "SELECT
				  SUM(comm.total_commission) as counter,pro.currency as currency_id,(Select c_currencies.currency_symbol from c_currencies where c_currencies.currency_id = pro.currency) as currency_symbol
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

WHERE   ordr.seller_id = " . $user_id . " AND ordr.order_status = 2   AND ordr.is_confirmed = 1 AND ordr.is_paid = 0
    AND " . $whr . "
          ";
        $query = $this->db->query($query);
        $row   = $query->result_array();
        //get_currency_rate(1,'GBP','USD')
        foreach ($row as $key => $value)
        {
            if ($user_currency <> $value['currency_id'] && $value['counter'] > 0)
            {
                $row[$key]['counter'] = get_currency_rate($value['counter'], $value['currency_id'], $user_currency);
            }
        }
        $counter = 0;
        foreach ($row as $key => $value)
        {
            $counter += $value['counter'];
        }
        return getSiteCurrencySymbol('', $user_currency) . number_format($counter, 2);
        ;
    }

    function getTotalPendingSalesCommissionCounter($user_id, $filter)
    {
        $user_currency = getVal('currency', 'c_users', 'user_id', $user_id);
        $whr           = '';

        if (!empty($filter))
        {
            $time = $filter['time'];
            if ($time == 'today')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE())
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '7days')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 7 day)
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '30days')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 MONTH)
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '6months')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 6 MONTH)
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '1year')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 YEAR)
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == 'custom')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP("' . $filter['start'] . '")
   AND comm.created <  UNIX_TIMESTAMP("' . $filter['end'] . '"))';
            }
            else
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE())
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
        }
        else
        {
            $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE())
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
        }


        $query = "SELECT
				  SUM(comm.total_commission) as counter,pro.currency as currency_id,(Select c_currencies.currency_symbol from c_currencies where c_currencies.currency_id = pro.currency) as currency_symbol
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

WHERE   ordr.seller_id = " . $user_id . " AND ordr.order_status = 2   AND ordr.is_confirmed = 0 AND ordr.is_paid = 0

    AND " . $whr . "
          ";
        $query = $this->db->query($query);
        $row   = $query->result_array();
        //get_currency_rate(1,'GBP','USD')
        foreach ($row as $key => $value)
        {
            if ($user_currency <> $value['currency_id'] && $value['counter'] > 0)
            {
                $row[$key]['counter'] = get_currency_rate($value['counter'], $value['currency_id'], $user_currency);
            }
        }
        $counter = 0;
        foreach ($row as $key => $value)
        {
            $counter += $value['counter'];
        }
        return getSiteCurrencySymbol('', $user_currency) . number_format($counter, 2);
        ;
    }

    function getTotalPublisherLinksShared($user_id, $filter)
    {
        $user_currency = getVal('currency', 'c_users', 'user_id', $user_id);
        $whr           = '';

        if (!empty($filter))
        {
            $time = $filter['time'];
            if ($time == 'today')
            {
                $whr = '(shr.created >= UNIX_TIMESTAMP(CURDATE())
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '7days')
            {
                $whr = '(shr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 7 day)
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '30days')
            {
                $whr = '(shr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 MONTH)
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '6months')
            {
                $whr = '(shr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 6 MONTH)
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '1year')
            {
                $whr = '(shr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 YEAR)
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == 'custom')
            {
                $whr = '(shr.created >= UNIX_TIMESTAMP("' . $filter['start'] . '")
   AND shr.created <  UNIX_TIMESTAMP("' . $filter['end'] . '"))';
            }
            else
            {
                $whr = '(shr.created >= UNIX_TIMESTAMP(CURDATE())
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
            }
        }
        else
        {
            $whr = '(shr.created >= UNIX_TIMESTAMP(CURDATE())
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
        }


        $query = "SELECT
				  shr.*
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

WHERE   pro.user_id = " . $user_id . " AND " . $whr . "

    GROUP BY from_unixtime(shr.created,'%Y %D %M'), product_id
    ORDER BY shr.id desc

          ";
        $query = $this->db->query($query);
        return $query->result_array();
    }

    function getPublisherShareLinkCounter($product_id, $created, $type, $user_id)
    {
        $user_currency = getVal('currency', 'c_users', 'user_id', $user_id);


        $query = "SELECT
				  count(share_counter) as counter
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

WHERE   pro.user_id = " . $user_id . " AND "
                . " shr.product_id = " . $product_id . "  AND"
                . " shr.share_type = " . $type . " AND
                    from_unixtime(shr.created, '%Y-%m-%d') = '" . $created . "'

          limit 1";
        $query = $this->db->query($query);
        $row   = $query->row();
        if ($row->counter <> null)
        {
            return $row->counter;
        }
        else
        {
            return 0;
        }
    }
    function getPublisherShareLinkCounter_withURL($product_id, $created, $user_id)
    {
        $user_currency = getVal('currency', 'c_users', 'user_id', $user_id);


        $query = "SELECT
				  GROUP_CONCAT(link) as links
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

WHERE   pro.user_id = " . $user_id . " AND "
                . " shr.product_id = " . $product_id . "  AND"
                    . "   from_unixtime(shr.created, '%Y-%m-%d') = '" . $created . "'

          limit 1";
        $query = $this->db->query($query);
        $row   = $query->row();
        if ($row->links <> null)
        {
            return $row->links;
        }
        else
        {
            return 0;
        }
    }

    function getTotalPublisherVisitors($user_id, $filter)
    {
        $user_currency = getVal('currency', 'c_users', 'user_id', $user_id);
        $whr           = $whr1          = '';

        if (!empty($filter))
        {
            $time = $filter['time'];
            if ($time == 'today')
            {
                $whr  = '(shr.created >= UNIX_TIMESTAMP(CURDATE())
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
                $whr1 = '(track.timestamp >= UNIX_TIMESTAMP(CURDATE())
   AND track.timestamp <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '7days')
            {
                $whr  = '(shr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 7 day)
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
                $whr1 = '(track.timestamp >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 7 day)
   AND track.timestamp <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '30days')
            {
                $whr  = '(shr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 MONTH)
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
                $whr1 = '(track.timestamp >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 MONTH)
   AND track.timestamp <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '6months')
            {
                $whr  = '(shr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 6 MONTH)
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
                $whr1 = '(track.timestamp >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 6 MONTH)
   AND track.timestamp <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '1year')
            {
                $whr  = '(shr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 YEAR)
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
                $whr1 = '(track.timestamp >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 YEAR)
   AND track.timestamp <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == 'custom')
            {
                $whr = '(shr.created >= UNIX_TIMESTAMP("' . $filter['start'] . '")
   AND shr.created <  UNIX_TIMESTAMP("' . $filter['end'] . '"))';

                $whr1 = '(track.timestamp >= UNIX_TIMESTAMP("' . $filter['start'] . '")
   AND track.timestamp <  UNIX_TIMESTAMP("' . $filter['end'] . '"))';
            }
            else
            {
                $whr  = '(shr.created >= UNIX_TIMESTAMP(CURDATE())
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
                $whr1 = '(track.timestamp >= UNIX_TIMESTAMP(CURDATE())
   AND track.timestamp <  UNIX_TIMESTAMP(NOW()))';
            }
        }
        else
        {
            $whr  = '(shr.created >= UNIX_TIMESTAMP(CURDATE())
   AND shr.created <  UNIX_TIMESTAMP(NOW()))';
            $whr1 = '(track.timestamp >= UNIX_TIMESTAMP(CURDATE())
   AND track.timestamp <  UNIX_TIMESTAMP(NOW()))';
        }


        $query = "SELECT
			 DISTINCT shr.product_id
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

            $ids = implode(',', array_filter($arr));

            $query = "SELECT
				  *
				FROM
				" . $this->db->dbprefix($this->tbl_usertracking) . " track

WHERE   track.product_id IN (" . $ids . ")

AND " . $whr1 . "
          ORDER BY track.id desc";
            $query = $this->db->query($query);
            return $query->result_array();
        }
    }

    function getTotalPublisherSales($user_id, $filter)
    {
        $user_currency = getVal('currency', 'c_users', 'user_id', $user_id);
        $whr           = '';

        if (!empty($filter))
        {
            $time = $filter['time'];
            if ($time == 'today')
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE())
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '7days')
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 7 day)
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '30days')
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 MONTH)
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '6months')
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 6 MONTH)
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '1year')
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 YEAR)
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == 'custom')
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP("' . $filter['start'] . '")
   AND ordr.created <  UNIX_TIMESTAMP("' . $filter['end'] . '"))';
            }
            else
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE())
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
            }
        }
        else
        {
            $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE())
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
        }


        $query = "SELECT
				ordr.*,track.referer_page,pro.currency
			FROM
				" . $this->db->dbprefix($this->tbl_orders) . " as ordr
                    INNER JOIN
                               " . $this->db->dbprefix($this->tbl) . " res
                        ON
                                (res.user_id = ordr.seller_id)
                     LEFT JOIN
                               " . $this->db->dbprefix($this->tbl_usertracking) . " track
                        ON
                                (track.id = ordr.user_tracking)
                                INNER JOIN
                               " . $this->db->dbprefix($this->tbl_products) . " pro

                    WHERE   ordr.seller_id = " . $user_id . ""
                . " AND ordr.order_status = 2 AND ordr.price <> 0 AND ordr.is_confirmed = 1 "
                . " AND " . $whr . "
                    GROUP BY ordr.id
                       ORDER BY id desc, track.id desc ";
        $query = $this->db->query($query);
        //////////////////////////////////

        $row = $query->result_array();
        foreach ($row as $key => $value)
        {
            $pro_currecny       = getVal('currency', 'c_products', array('product_id' => $value['product_id']));
            $row[$key]['price'] = getSiteCurrencySymbol('', $user_currency) . number_format(get_currency_rate($value['price'], $pro_currecny, $user_currency), 2);
            unset($row[$key]['currency']);
        }
//        dd($row);
        ///////////////////////////////////
//        dd($query->result_array());
        return $row;
    }

    function getTotalPublisherLeadSales($user_id, $filter)
    {
        $user_currency = getVal('currency', 'c_users', 'user_id', $user_id);
        $whr           = '';

        if (!empty($filter))
        {
            $time = $filter['time'];
            if ($time == 'today')
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE())
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '7days')
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 7 day)
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '30days')
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 MONTH)
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '6months')
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 6 MONTH)
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '1year')
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 YEAR)
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == 'custom')
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP("' . $filter['start'] . '")
   AND ordr.created <  UNIX_TIMESTAMP("' . $filter['end'] . '"))';
            }
            else
            {
                $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE())
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
            }
        }
        else
        {
            $whr = '(ordr.created >= UNIX_TIMESTAMP(CURDATE())
   AND ordr.created <  UNIX_TIMESTAMP(NOW()))';
        }


        $query = "SELECT
				ordr.*,track.referer_page,pro.currency
			FROM
				" . $this->db->dbprefix($this->tbl_orders) . " as ordr
                    INNER JOIN
                               " . $this->db->dbprefix($this->tbl) . " res
                        ON
                                (res.user_id = ordr.seller_id)
                     LEFT JOIN
                               " . $this->db->dbprefix($this->tbl_usertracking) . " track
                        ON
                                (track.id = ordr.user_tracking)
                                    INNER JOIN
                               " . $this->db->dbprefix($this->tbl_products) . " pro
                        ON
                                (pro.product_id = ordr.product_id)

                    WHERE   ordr.seller_id = " . $user_id . ""
                . " AND ordr.order_status = 2  AND ordr.is_confirmed = 1 "
                . " AND " . $whr . "
                    GROUP BY ordr.id
                       ORDER BY id desc, track.id desc ";
        $query = $this->db->query($query);
//        dd($query->result_array());
        $row   = $query->result_array();
//                dd($row);
        foreach ($row as $key => $value)
        {
            $pro_currecny       = getVal('currency', 'c_products', array('product_id' => $value['product_id']));
            $row[$key]['price'] = getSiteCurrencySymbol('', $user_currency) . number_format(get_currency_rate($value['price'], $pro_currecny, $user_currency), 2);
            unset($row[$key]['currency']);
        }
//        dd($row);
        ///////////////////////////////////
//        dd($query->result_array());
        return $row;
    }

    function getTotalPublisherCommission($user_id, $filter)
    {
        $user_currency     = getVal('currency', 'c_users', 'user_id', $user_id);
        $user_account_type = getVal('account_type', 'c_users', 'user_id', $user_id);
        $whr               = '';

        if (!empty($filter))
        {
            $time = $filter['time'];
            if ($time == 'today')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE())
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '7days')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 7 day)
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '30days')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 MONTH)
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '6months')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 6 MONTH)
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '1year')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 YEAR)
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == 'custom')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP("' . $filter['start'] . '")
   AND comm.created <  UNIX_TIMESTAMP("' . $filter['end'] . '"))';
            }
            else
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE())
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
        }
        else
        {
            $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE())
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
        }


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
 LEFT JOIN
                               " . $this->db->dbprefix($this->tbl_usertracking) . " track
                        ON
                                (track.id = ordr.user_tracking)
                                 LEFT JOIN c_invoice_orders as cio ON  FIND_IN_SET(ordr.id, cio.order_ids) > 0
                    INNER JOIN c_invoices as inv ON inv.invoice_id = cio.invoice_id
                    LEFT JOIN c_payment as payment_inv ON payment_inv.invoice_number = inv.invoice_number

WHERE   ordr.seller_id = " . $user_id . " AND ordr.order_status = 2 AND inv.status = 1  AND ordr.is_confirmed = 1 
    AND " . $whr . "
        GROUP BY ordr.id
          ORDER BY id desc";
        $query = $this->db->query($query);
        $row   = $query->result_array();
//                dd($row);
        foreach ($row as $key => $value)
        {
            if ($user_account_type == 2)
            {
                $pro_currecny                  = getVal('currency', 'c_products', array('product_id' => $value['product_id']));
                $row[$key]['total_commission'] = getSiteCurrencySymbol('', $user_currency) . number_format(get_currency_rate($value['total_commission'], $pro_currecny, $user_currency), 2);
            }
            else
            {
                $pro_currecny                       = getVal('currency', 'c_products', array('product_id' => $value['product_id']));
                $row[$key]['advertiser_commission'] = getSiteCurrencySymbol('', $user_currency) . number_format(get_currency_rate($value['advertiser_commission'], $pro_currecny, $user_currency), 2);
            }
        }
//        dd($row);
        ///////////////////////////////////
//        dd($query->result_array());
        return $row;
    }

    function getTotalSuccessSalesCommission($user_id, $filter)
    {
        $user_currency = getVal('currency', 'c_users', 'user_id', $user_id);
        $whr           = '';

        if (!empty($filter))
        {
            $time = $filter['time'];
            if ($time == 'today')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE())
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '7days')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 7 day)
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '30days')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 MONTH)
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '6months')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 6 MONTH)
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '1year')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 YEAR)
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == 'custom')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP("' . $filter['start'] . '")
   AND comm.created <  UNIX_TIMESTAMP("' . $filter['end'] . '"))';
            }
            else
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE())
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
        }
        else
        {
            $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE())
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
        }


        $query = "SELECT
				  comm.*,ordr.url,track.referer_page,pro.currency
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
 LEFT JOIN
                               " . $this->db->dbprefix($this->tbl_usertracking) . " track
                        ON
                                (track.id = ordr.user_tracking)
                                INNER JOIN
                               " . $this->db->dbprefix($this->tbl_products) . " pro
                        ON
                                (pro.product_id = ordr.product_id)

WHERE   ordr.seller_id = " . $user_id . " AND ordr.order_status = 2 AND ordr.is_paid = 0   AND ordr.is_confirmed = 1 
    AND " . $whr . "
        GROUP BY ordr.id
          ORDER BY id desc";
        $query = $this->db->query($query);
        $row   = $query->result_array();

        //get_currency_rate(1,'GBP','USD')
        foreach ($row as $key => $value)
        {
            if ($user_currency <> $value['currency'] && $value['total_commission'] > 0)
            {
                $row[$key]['total_commission']      = getSiteCurrencySymbol('', $user_currency) . number_format(get_currency_rate($value['total_commission'], $value['currency'], $user_currency), 2);
                $row[$key]['advertiser_commission'] = getSiteCurrencySymbol('', $user_currency) . number_format(get_currency_rate($value['advertiser_commission'], $value['currency'], $user_currency), 2);
            }
            else
            {
                $row[$key]['total_commission']      = getSiteCurrencySymbol('', $user_currency) . number_format($value['total_commission'], 2);
                $row[$key]['advertiser_commission'] = getSiteCurrencySymbol('', $user_currency) . number_format($value['advertiser_commission'], 2);
            }
            unset($row[$key]['currency']);
        }
//          dd($row);
        return $row;
    }

    function getTotalPendingSalesCommission($user_id, $filter)
    {
        $user_currency = getVal('currency', 'c_users', 'user_id', $user_id);
        $whr           = '';

        if (!empty($filter))
        {
            $time = $filter['time'];
            if ($time == 'today')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE())
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '7days')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 7 day)
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '30days')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 MONTH)
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '6months')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 6 MONTH)
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == '1year')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 YEAR)
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
            elseif ($time == 'custom')
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP("' . $filter['start'] . '")
   AND comm.created <  UNIX_TIMESTAMP("' . $filter['end'] . '"))';
            }
            else
            {
                $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE())
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
            }
        }
        else
        {
            $whr = '(comm.created >= UNIX_TIMESTAMP(CURDATE())
   AND comm.created <  UNIX_TIMESTAMP(NOW()))';
        }


        $query = "SELECT
				  comm.*,ordr.url,track.referer_page,pro.currency
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
 LEFT JOIN
                               " . $this->db->dbprefix($this->tbl_usertracking) . " track
                        ON
                                (track.id = ordr.user_tracking)
                                   INNER JOIN
                               " . $this->db->dbprefix($this->tbl_products) . " pro
                        ON
                                (pro.product_id = ordr.product_id)

WHERE   ordr.seller_id = " . $user_id . " AND ordr.order_status = 2  AND ordr.is_paid = 0  AND ordr.is_confirmed = 0
    AND " . $whr . "
        GROUP BY ordr.id
          ORDER BY id desc";
        $query = $this->db->query($query);
        $row   = $query->result_array();

        //get_currency_rate(1,'GBP','USD')
        foreach ($row as $key => $value)
        {
            if ($user_currency <> $value['currency'] && $value['total_commission'] > 0)
            {
                $row[$key]['total_commission']      = getSiteCurrencySymbol('', $user_currency) . number_format(get_currency_rate($value['total_commission'], $value['currency'], $user_currency), 2);
                $row[$key]['advertiser_commission'] = getSiteCurrencySymbol('', $user_currency) . number_format(get_currency_rate($value['advertiser_commission'], $value['currency'], $user_currency), 2);
            }
            else
            {
                $row[$key]['total_commission']      = getSiteCurrencySymbol('', $user_currency) . number_format($value['total_commission'], 2);
                $row[$key]['advertiser_commission'] = getSiteCurrencySymbol('', $user_currency) . number_format($value['advertiser_commission'], 2);
            }
            unset($row[$key]['currency']);
        }
        return $row;
    }

}

//End Class