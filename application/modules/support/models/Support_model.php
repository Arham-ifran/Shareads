<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Support_Model extends CI_Model
{

    var $tbl = 'helptopics';

    public function __construct()
    {

        parent::__construct();
    }

    public function loadtitles()
    {

        $query  = 'SELECT
                    helptopic.title as support_title
                FROM
                    c_helptopics helptopic';
        $result = $this->db->query($query);
        $row    = $result->result_array();
        return $row;
    }

    public function loadSupportDetails($post_id)
    {

        $sql_ = 'SELECT
                    helptopic.*, count(helptopic.id) as nCounts

                FROM
                    ' . $this->db->dbprefix . $this->tbl . ' as  helptopic ';
        $sql_ .= " where helptopic.id  = '" . $post_id . "' AND helptopic.status = 1";
        $sql_.=" LIMIT 1";

        $query = $this->db->query($sql_);
        return $query->row_array();
    }

    public function loadSupportListing($search_support = '')
    {

        $where = '';

        if ($search_support <> '')
        {
            $where = ' AND helptopic.title like "%' . $search_support . '%" ';
        }

        $sql_    = 'SELECT
                    helptopic.*, count(helptopic.id) as nCounts
                FROM
                    ' . $this->db->dbprefix . $this->tbl . ' as helptopic
                        
		';
        $sql_ .= 'where helptopic.status = 1 ' . $where;
        $sql_ .= " group by helptopic.id	";
        $perpage = 10; //global_setting('perpage');
        $offset  = 0;
        if ($this->uri->segment(2) > 0)
        {
            $offset = $this->uri->segment(2);
        }
        else
        {

            $offset = 0;
        }
        $query         = $this->db->query($sql_);
        $total_records = $query->num_rows();
        init_front_pagination_support('support', $total_records, $perpage);
        $sql_.= " ORDER BY id DESC";

        $sql_.=" LIMIT " . $offset . ", " . $perpage . "";


        $query = $this->db->query($sql_);
        return $query->result_array();
    }

}

//End Class