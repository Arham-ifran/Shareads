<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Feedback_model extends CI_Model
{

    var $tbl           = 'feedback';
    var $table         = 'feedback';
    var $column_order  = array('name', 'email', 'phone');
    var $column_search = array('name', 'email', 'phone');
    var $order         = array('feedId' => 'DESC');

    public function __construct()
    {
        parent::__construct();
    }

//End __construct
    // Common Functions
    public function loadListing()
    {
        $sql_ = 'SELECT
                    ' . $this->db->dbprefix . $this->tbl . '.*
                FROM
                    ' . $this->db->dbprefix . $this->tbl . '
		';

        $sql_.= "ORDER BY feedId DESC";
        $query = $this->db->query($sql_);
        return $query;
    }

    function getRow($id)
    {
        $query = $this->db->get_where($this->db->dbprefix . $this->tbl, array('feedId' => $id));
        if ($query->num_rows() > 0)
        {
            return $query->row_array();
        }
    }

    /**
     * Method: updateFeedbackStatus
     * Params: $itemId
     */
    public function updateFeedbackStatus($itemId)
    {
        $status      = 1;
        $data_insert = array('status' => $status);
        $this->db->where('feedId', $itemId);
        $this->db->update($this->tbl, $data_insert);
        return true;
    }

    /**
     * Method: deleteItem
     * Params: $itemId
     * Return: True/False
     */
    public function deleteItem($itemId)
    {
        $this->db->where('feedId', $itemId);
        $this->db->delete($this->tbl);
        if ($this->db->_error_number())
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    private function _get_datatables_query()
    {
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