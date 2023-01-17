<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Dashboard_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }
//End __construct
 // Common Functions
    public function load_listing_counter() {
        $query = "SELECT
				  count(*) as counter
				FROM
				c_products pro

           limit 1";
        $query = $this->db->query($query);
        $row = $query->row();
        return $row->counter;
    }


public function load_new_publishers_counter() {
        $query = "SELECT
				  count(*) as counter
				FROM
				c_users

WHERE   account_type = 2
           limit 1";
        $query = $this->db->query($query);
        $row = $query->row();
        return $row->counter;
    }

 public function load_new_advertiser_counter() {
        $query = "SELECT
				  count(*) as counter
				FROM
				c_users

WHERE   account_type = 1
           limit 1";
        $query = $this->db->query($query);
        $row = $query->row();
        return $row->counter;
    }
    
     public function load_new_advertiser_bankclaimfund_counter() {
        $query = "SELECT
				  count(*) as counter
				FROM
				c_users

WHERE   account_type = 1 and site_refrence = 'bank-refund.claims/check7'
           limit 1";
        $query = $this->db->query($query);
        $row = $query->row();
        return $row->counter;
    }

     public function total_ads_counter() {
        $query = "SELECT
				  count(*) as counter
				FROM
				c_announcements

           limit 1";
        $query = $this->db->query($query);
        $row = $query->row();
        return $row->counter;
    }

     public function total_categories_counter() {
        $query = "SELECT
				  count(*) as counter
				FROM
				c_categories
where parent_id = 0
           limit 1";
        $query = $this->db->query($query);
        $row = $query->row();
        return $row->counter;
    }
    
    public function total_subcategories_counter() {
        $query = "SELECT
				  count(*) as counter
				FROM
				c_categories
where parent_id > 0
           limit 1";
        $query = $this->db->query($query);
        $row = $query->row();
        return $row->counter;
    }

     public function total_cms_counter() {
        $query = "SELECT
				  count(*) as counter
				FROM
				c_contentmanagement

           limit 1";
        $query = $this->db->query($query);
        $row = $query->row();
        return $row->counter;
    }
    public function total_invitation_sent() {
        $query = "SELECT
				  count(*) as counter
				FROM
				c_publisher_invitations

          limit 1";
        $query = $this->db->query($query);
        $row = $query->row();
        return $row->counter;
    }
    public function total_accepted_invitations() {
        $query = "SELECT
				  count(*) as counter
				FROM
				c_publisher_invitations

           where status = 2 limit 1";
        $query = $this->db->query($query);
        $row = $query->row();
        return $row->counter;
    }





}
//End Class