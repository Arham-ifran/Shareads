<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Helptopics_model extends CI_Model
{

    var $tbl = 'helptopics';

    public function __construct()
    {
        parent::__construct();
    }

    public function loadListing()
    {
        $sql_  = 'SELECT
                   helptopic.*
                FROM
                    ' . $this->db->dbprefix . $this->tbl . ' as helptopic ';
        $sql_.= " ORDER BY helptopic.id desc";
        $query = $this->db->query($sql_);
        return $query;
    }

    function getRow($id)
    {
        $query = $this->db->get_where($this->db->dbprefix . $this->tbl, array('id' => $id));
        if ($query->num_rows() > 0)
        {
            return $query->row_array();
        }
    }

    /**
     * Method: updateItemStatus
     * Params: $itemId, $status
     */
    public function updateItemStatus($itemId, $status)
    {
        if ($status == 1)
        {
            $status = 0;
        }
        else
        {
            $status = 1;
        }
        $data_insert = array('status' => $status);
        $this->db->where('id', $itemId);
        $this->db->update($this->tbl, $data_insert);
        $action      = 'Status updated successfully. Please wait...';
        $msg         = $action;
        return $msg;
    }

    /**
     * Method: deleteItem
     * Params: $itemId
     * Return: True/False
     */
    public function deleteItem($itemId)
    {
        $this->db->where('id', $itemId);
        $this->db->delete($this->tbl);
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
     * Method: checkPage
     * Params: $slug
     * Return: True/False
     */
    function checkPage($slug)
    {
        $sqlChk = "SELECT title FROM " . $this->db->dbprefix . $this->tbl . " WHERE title = '" . $slug . "'";
        $query  = $this->db->query($sqlChk);
        if ($query->num_rows >= 1)
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

    /**
     * Method: saveItem
     * Params: $post
     * Return: True/False
     */
    public function saveItem($post)
    {
        $id          = $post['id'];
        $data_insert = array();
        if (is_array($post))
        {
            foreach ($post as $k => $v)
            {
                if ($k != 'id' && $k != 'action')
                {
                    $data_insert[$k] = $v;
                }
            }
        }

        if ($post['action'] == 'add')
        {//Save Data
            $data_insert['slug'] = str_replace(" ","_",strtolower($post['title']));
            $data_insert['created'] = time();
            $data_insert['updated'] = time();
            return $this->db->insert($this->db->dbprefix . $this->tbl, $data_insert);
        }
        else
        {//Update Data
            $data_insert['updated'] = time();
            $this->db->where('id', $id);
            return $this->db->update($this->db->dbprefix . $this->tbl, $data_insert);
        }
    }

}

//End Class