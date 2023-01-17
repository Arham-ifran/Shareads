<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class newsletter_model extends CI_Model
{

    var $tbl           = 'users';
    var $table         = 'users';
    var $column_order  = array('full_name', 'email');
    var $column_search = array('full_name', 'email');
    var $order         = array('user_id' => 'DESC');

    public function __construct()
    {
        parent::__construct();
    }

//End __construct
    // Common Functions
    public function loadListing()
    {
        $sql_ = 'SELECT
                    user_id as id,
            full_name,
            phone,
            email,
            newsletter_subscriber

                FROM
                    ' . $this->db->dbprefix . $this->tbl . '
                where newsletter_subscriber = 1

		';

        $sql_.= " ORDER BY id DESC ";
        $query = $this->db->query($sql_);
        return $query;
    }

    /*     * **************EXPORT **************** */

    function get_export_newsLetters()
    {

        $sql_ = 'SELECT user_id as id,
            full_name,
            phone,
            email,
            newsletter_subscriber

                FROM
                    ' . $this->db->dbprefix . $this->tbl . '
                where newsletter_subscriber = 1
		';

        $sql_.= " ORDER BY full_name DESC ";

        $query = $this->db->query($sql_);
        return $query;
    }

    /**
     * Method: updateItemStatus
     * Params: $itemId, $status
     */
    public function updateItemStatus($itemId, $status)
    {
        $data_insert = array('newsletter_subscriber' => $status);
        $this->db->where('user_id', $itemId);
        $this->db->update($this->tbl, $data_insert);
        $action      = 'Status updated successfully. Please wait...';
        $msg         = $action;
        return $msg;
    }

    /**
     * Method: deleteNewsLetter
     * Params: $itemId
     * Return: True/False
     */
    public function deleteNewsLetter($itemId)
    {
        $this->db->where('newsletter_id', $itemId);
        $this->db->delete('c_newlettter_subscribers');
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
     * Method: getNewsletter Data
     * Params: $id
     * Return: data row
     */
    function getNewsletterData($id)
    {
        $query = $this->db->get_where('c_newlettter_subscribers', array('newsletter_id' => $id));
        if ($query->num_rows() > 0)
        {
            return $query->row_array();
        }
    }

    function get_all_active_email_template($email_type)
    {
        $this->db->select("*");
        $this->db->from('c_email_templates');
        $this->db->where('email_template_type', $email_type);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function loadAllSubscribers()
    {
        $sql_  = 'SELECT  *
            FROM(
            (SELECT
                    user_id as id,
            user_name,
            phone,
            email,
            newsletter_subscriber,
            "0" as type
                FROM
                    ' . $this->db->dbprefix . $this->tbl . '
                where newsletter_subscriber = 1 and status = 1)
                UNION (
               SELECT
              newsletter_id as  Id,
              "" as user_name,
              "" as phone,
              email as email,
              "" as newsletter_subscriber,
              "1" as type
from  c_newlettter_subscribers
))reslut
        ORDER BY user_name DESC
		';
        $query = $this->db->query($sql_);
        return $query;
    }

    private function _get_datatables_query()
    {
        $this->db->select('user_id as id,full_name,phone,email,newsletter_subscriber');
        $this->db->where('newsletter_subscriber = 1');
        $this->db->where('status = 1');
        $this->db->from($this->table);

        $i = 0;
        foreach ($this->column_search as $item)
        {
            if ($_POST['search']['value'])
            {
                if ($i === 0)
                {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end();
            }
            $i++;
        }
        if (isset($_POST['order']))
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }
        else if (isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

}

//End Class