<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Payment_model extends CI_Model {

    var $tbl = 'user_commissions';

    public function __construct() {
        parent::__construct();
    }

//End __construct

    public function get_all_commissionPublisher_orders($month) {

        $sql_ = 'SELECT from_unixtime(ordr.created, "%M") as monthName,ordr.is_paid,
            count(ordr.id) as total_orders,
                    SUM(ordr.price) as overall_price,
                    SUM(comm.total_commission) as overall_commission,
                    SUM(comm.advertiser_commission) as all_advertiser_commission,
                    seller.paypal_email,
                    GROUP_CONCAT(ordr.id) as order_id
                FROM
                    c_orders as ordr
                    INNER JOIN c_users seller on seller.user_id = ordr.seller_id
                    INNER JOIN c_products pro on ordr.product_id = pro.product_id
                    INNER JOIN c_user_commissions comm on ordr.id = comm.order_id

            WHERE
                    ordr.order_status > 1 AND (pro.product_type = 1 || pro.product_type = 3) AND
                    seller.hold_payment = 0 AND
                    ordr.is_paid = 0 AND
                    DATEDIFF(CURDATE(),from_unixtime(seller.created, "%Y-%m-%d")) > ' . NO_OF_DAYS . '

            AND from_unixtime(ordr.created, "%m-%Y") = "' . $month . '"
                GROUP BY ordr.seller_id
		 ORDER BY ordr.id DESC';
        $query = $this->db->query($sql_);
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
    }

    public function get_all_commissionAdvertiser_orders($month) {

         $sql_ = 'SELECT ordr.advertiser_id,
            count(ordr.id) as total_orders,comm.is_paid,
                    SUM(comm.total_commission) as overall_commission,
                    SUM(comm.advertiser_commission) as all_advertiser_commission,
                    buyer.paypal_email,
                    GROUP_CONCAT(ordr.id) as order_id
                FROM
                    c_orders as ordr
                    INNER JOIN c_user_commissions comm on ordr.id = comm.order_id
                    INNER JOIN c_users buyer on buyer.user_id = comm.user_id
                    INNER JOIN c_products pro on comm.product_id = pro.product_id


            WHERE
                ordr.order_status > 1 AND (pro.product_type = 1 || pro.product_type = 3) AND
                buyer.hold_payment = 0 AND
                comm.is_paid = 0 AND buyer.paypal_email <> "" AND
                DATEDIFF(CURDATE(),from_unixtime(buyer.created, "%Y-%m-%d")) > ' . NO_OF_DAYS . '

                AND from_unixtime(ordr.created, "%m-%Y") = "' . $month . '"

                GROUP BY ordr.advertiser_id
		ORDER BY comm.id DESC';
        $query = $this->db->query($sql_);
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
    }


    function savePaidOrdersList($orders_publisher,$orders_advertiser)
    {
        $data = array('publishers' => implode(',', $orders_publisher), 'advertisers' => implode(',', $orders_advertiser));
        $this->db->insert('paid_payments',$data);
        return $this->db->insert_id();
    }

    function update_publisher_orders($publisher)
    {

        $dataPub = array('is_paid' => 1,'transaction_id' => '');
        $this->db->where('id', $publisher);
        $this->db->update('c_orders', $dataPub);

        return true;
    }

    function update_advertiser_orders($advertiser)
    {

        $dataAdv = array('is_paid' => 1);
        $this->db->where('order_id', $advertiser);
        $this->db->update('c_user_commissions', $dataAdv);

        return true;
    }




function updateLeadGen_orders($id,$pay_key)
    {

        $dataAdv = array('status' => 1,'pay_key' => $pay_key);
        $this->db->where('id', $id);
        $this->db->update('c_lead_generation', $dataAdv);

        return true;
    }

    function getLeadGenCommissionOrder($id)
    {
            $sql_ = 'SELECT lead.*,

                    buyer.paypal_email,
                    lead.id as order_id
                FROM
                    c_lead_generation lead
                    INNER JOIN c_users buyer on buyer.user_id = lead.advertiser_id
                    INNER JOIN c_products pro on lead.product_id = pro.product_id


            WHERE
                lead.status = 0 AND pro.product_type = 2 AND
                buyer.hold_payment = 0 AND

                DATEDIFF(CURDATE(),from_unixtime(buyer.created, "%Y-%m-%d")) > ' . NO_OF_DAYS . '

                AND lead.id = "' . $id . '"

		ORDER BY lead.id DESC';
        $query = $this->db->query($sql_);
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
    }

    function getlastCronDate()
    {
            $sql_ = 'SELECT date
                FROM
                    c_cron_data

		ORDER BY id DESC limit 1';
        $query = $this->db->query($sql_);
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
    }


function updateCronDate()
    {
        $dataAdv = array('date' => strtotime('+45 day', time()));
        $this->db->insert('c_lead_generation', $dataAdv);

        return true;
    }

}

//End Class