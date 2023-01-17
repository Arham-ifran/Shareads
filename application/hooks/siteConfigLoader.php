<?php
class siteConfigLoader
{
    function initialize() {
        $ci =& get_instance();
        $ci->db->save_queries = FALSE;
        $ci->load->library('web_config');
	$ci->web_config->setSiteConfig();
    }
}