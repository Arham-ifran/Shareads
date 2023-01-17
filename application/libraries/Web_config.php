<?php

class Web_Config
{

    public function __construct()
    {
        
    }

    public function setSiteConfig()
    {
        $ci    = &get_instance();
        $table = $ci->db->dbprefix . 'site_settings';
        $ci->db->select('*');
        $ci->db->order_by('id', 'desc');
        $ci->db->limit(1);
        $q     = $ci->db->get($table);
        if ($q->num_rows() > 0)
        {
            foreach ($q->result() as $row)
            {
                foreach ($row as $k => $v)
                {
                    define(strtoupper($k), $v);
                }
            }
        }

        ///// TIME ZONE PICKER //////
        if (isset($_SESSION['timezone']) || trim($_SESSION['timezone']) == '' || $_SESSION['timezone'] == NULL)
        {
            $ip     = $_SERVER['REMOTE_ADDR'];
            $json   = file_get_contents('http://ip-api.com/json/' . $ip);
            
            $ipData = json_decode($json, true);
            if ($ipData['timezone'])
            {
                $_SESSION['timezone'] = $ipData['timezone'];
            }
            else
            {
                $_SESSION['timezone'] = date_default_timezone_get();
            }
            date_default_timezone_set($_SESSION['timezone']);
            
        }else{
            date_default_timezone_set($_SESSION['timezone']);
        }
        ///// TIME ZONE PICKER //////
    }

}

?>