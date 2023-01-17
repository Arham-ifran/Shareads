<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * Method: rights
 * Params: $right_id
 * Return: True / False
 */
if (!function_exists('rights'))
{

    function rights($right_id)
    {
        $ci = &get_instance();
        if ($ci->session->userdata('role_id') == 0)
        {
            return true;
        }
        $query = 'SELECT
		count(*) as  counter
		FROM
		c_admin_permissions as permission
		WHERE
		permission.right_id ="' . $right_id . '"
		 and role_id= "' . $ci->session->userdata('role_id') . '" limit 1';
        $query = $ci->db->query($query);
        $row   = $query->row();
        if ($row->counter > 0)
        {
            return true;
        }
        else
        {
//            $ci->session->set_flashdata('error_message', 'You don\'t have permissions for this module. Please contact your administrator.');
            return false;
        }
    }

}

/**
 * Method: getColumns
 * Params: $table
 * Return: Fields of table
 */
if (!function_exists('getColumns'))
{

    function getColumns($table)
    {
        $result = "";
        $ci     = &get_instance();
        $table  = $ci->db->dbprefix . $table;
        $sql    = "SHOW COLUMNS FROM " . $table;
        $query  = $ci->db->query($sql);
        if ($query->num_rows() > 0)
        {
            foreach ($query->result() as $row)
            {
                $result [$row->Field] = "";
            }
        }
        return $result;
    }

}

function updateVal($col, $value, $table, $where = '', $criteria = '')
{
    $ci                = &get_instance();
    $data_update[$col] = $value;
    $ci->db->where($where, $criteria);
    $ci->db->update($table, $data_update);
}

/**
 * Method: getVal
 * Params: $col, $table, $where, $criteria
 * Return: array
 */
function getVal($col, $table, $where = '', $criteria = '')
{
    $ci          = &get_instance();
    $arr_results = array();
    $ci->db->select($col);
    $ci->db->where($where, $criteria);
    $ci->db->from($table);
    $ci->db->limit(1);
    $results     = $ci->db->get();
    if ($results->num_rows() > 0)
    {
        return $results->row($col);
    }
}

/**
 * Method: getValArray
 * Params: $cols, $table, $where, $criteria
 * Return: array
 */
function getValArray($cols, $table, $where = '', $criteria = '')
{
    $ci      = &get_instance();
    $ci->db->select($cols);
    $ci->db->where($where, $criteria);
    $ci->db->from($table);
    $ci->db->limit(1);
    $results = $ci->db->get();
    if ($results->num_rows() > 0)
    {
        return $results->row_array();
    }
}

/**
 * Mehtod: init_admin_pagination
 * params: $uri, $total_records,$perpage
 * return: pagination configuration
 */
if (!function_exists('init_admin_pagination'))
{

    function init_admin_pagination($uri, $total_records, $perpage, $id = '')
    {
        $ci                           = & get_instance();
        $config ["base_url"]          = base_url() . $uri;
        $prev_link                    = '&laquo;';
        $next_link                    = '&raquo;';
        $config ["total_rows"]        = $total_records;
        $config ["per_page"]          = $perpage;
        if ($id)
            $config ['uri_segment']       = '5';
        else
            $config ['uri_segment']       = '4';
        $config ['first_link']        = 'First &laquo;';
        $config ['last_link']         = '&raquo; Last';
        $config ['num_links']         = '5';
        $config ['prev_link']         = $prev_link;
        $config ['next_link']         = $next_link;
        $config ['num_tag_open']      = '<li>';
        $config ['num_tag_close']     = '</li>';
        $config ['cur_tag_open']      = '<li class="active"><a>';
        $config ['cur_tag_close']     = '</a></li>';
        $config ['prev_tag_open']     = '<li>';
        $config ['prev_tag_close']    = '</li>';
        $config ['next_tag_open']     = '<li>';
        $config ['next_tag_close']    = '</li>';
        $config ['page_query_string'] = FALSE;
        $ci->pagination->initialize($config);
        return $config;
    }

}
/**
 * Mehtod: init_front_pagination
 * params: $uri, $total_records,$perpage
 * return: pagination configuration
 */
if (!function_exists('init_front_pagination'))
{

    function init_front_pagination($url, $total_records, $perpage, $id = '')
    {
        $ci                    = & get_instance();
        $config ["base_url"]   = base_url() . $url;
        $prev_link             = '&lsaquo;';
        $next_link             = '&rsaquo;';
        $config ["total_rows"] = $total_records;
        $config ["per_page"]   = $perpage;
        if ($id)
        {
            $config ['uri_segment'] = '4';
        }
        else
        {
            $config ['uri_segment'] = '3';
        }
        $config ['first_link']        = 'First &laquo;';
        $config ['last_link']         = '&raquo; Last';
        $config ['first_tag_open']    = '<li>';
        $config ['first_tag_close']   = '</li>';
        $config ['last_tag_open']     = '<li>';
        $config ['last_tag_close']    = '</li>';
        $config ['num_links']         = '7';
        $config ['prev_link']         = $prev_link;
        $config ['next_link']         = $next_link;
        $config ['num_tag_open']      = '<li>';
        $config ['num_tag_close']     = '</li>';
        $config ['cur_tag_open']      = '<li class="active"><a>';
        $config ['cur_tag_close']     = '</a></li>';
        $config ['prev_tag_open']     = '<li>';
        $config ['prev_tag_close']    = '</li>';
        $config ['next_tag_open']     = '<li>';
        $config ['next_tag_close']    = '</li>';
        $config ['page_query_string'] = false;
        $ci->pagination->initialize($config);
        return $config;
    }

}
if (!function_exists('init_front_pagination_support'))
{

    function init_front_pagination_support($url, $total_records, $perpage, $id = '')
    {
        $ci                    = & get_instance();
        $config ["base_url"]   = base_url() . $url;
        $prev_link             = '&lsaquo;';
        $next_link             = '&rsaquo;';
        $config ["total_rows"] = $total_records;
        $config ["per_page"]   = $perpage;
        if ($id)
        {
            $config ['uri_segment'] = '3';
        }
        else
        {
            $config ['uri_segment'] = '2';
        }
        $config ['first_link']        = 'First &laquo;';
        $config ['last_link']         = '&raquo; Last';
        $config ['first_tag_open']    = '<li>';
        $config ['first_tag_close']   = '</li>';
        $config ['last_tag_open']     = '<li>';
        $config ['last_tag_close']    = '</li>';
        $config ['num_links']         = '7';
        $config ['prev_link']         = $prev_link;
        $config ['next_link']         = $next_link;
        $config ['num_tag_open']      = '<li>';
        $config ['num_tag_close']     = '</li>';
        $config ['cur_tag_open']      = '<li class="active"><a>';
        $config ['cur_tag_close']     = '</a></li>';
        $config ['prev_tag_open']     = '<li>';
        $config ['prev_tag_close']    = '</li>';
        $config ['next_tag_open']     = '<li>';
        $config ['next_tag_close']    = '</li>';
        $config ['page_query_string'] = false;
        $ci->pagination->initialize($config);
        return $config;
    }

}

if (!function_exists('init_front_pagination_blog'))
{

    function init_front_pagination_blog($url, $total_records, $perpage, $string, $id)
    {
        $ci                    = & get_instance();
        if ($string)
            $config ["base_url"]   = $url;
        else
            $config ["base_url"]   = base_url() . $url;
        $prev_link             = '&lsaquo;';
        $next_link             = '&rsaquo;';
        $config ["total_rows"] = $total_records;
        $config ["per_page"]   = $perpage;
        if ($id)
        {
            $config ['uri_segment'] = '4';
        }
        else
        {
            $config ['uri_segment'] = '3';
        }

        $config ['first_link']      = 'First &laquo;';
        $config ['last_link']       = '&raquo; Last';
        $config ['first_tag_open']  = '<li>';
        $config ['first_tag_close'] = '</li>';
        $config ['last_tag_open']   = '<li>';
        $config ['last_tag_close']  = '</li>';
        $config ['num_links']       = '7';
        $config ['prev_link']       = $prev_link;
        $config ['next_link']       = $next_link;
        $config ['num_tag_open']    = '<li>';
        $config ['num_tag_close']   = '</li>';
        $config ['cur_tag_open']    = '<li class="active"><a>';
        $config ['cur_tag_close']   = '</a></li>';
        $config ['prev_tag_open']   = '<li>';
        $config ['prev_tag_close']  = '</li>';
        $config ['next_tag_open']   = '<li>';
        $config ['next_tag_close']  = '</li>';

        if ($string)
        {
            $config ['page_query_string'] = true;
        }
        else
        {
            $config ['page_query_string'] = false;
        }
        $ci->pagination->initialize($config);
        return $config;
    }

}

/**
 * Get get_all_countries.
 * @access	private
 * @return array
 */
function get_all_countries()
{
    $CI = & get_instance();
    $CI->db->select('id,country');
    $CI->db->from('c_countries');
    $q  = $CI->db->get();
    if ($q->num_rows() > 0)
    {
        $results = $q->result_array();
        $narray  = array();
        $arr     = array();
        $i       = 0;
        foreach ($results as $res)
        {
            if ($res['id'] == 233)
            {
                $narray[$i]['country'] = $res['country'];
            }
            else
            {
                $arr[$i]['country'] = $res['country'];
            }
            $i++;
        }
        $result = array_merge($narray, $arr);
        return $result;
    }
}

if (!function_exists('loadBlogCategories'))
{

    function loadBlogCategories()
    {

        $CI    = & get_instance();
        $qu    = "SELECT cat.id, cat.category, count(posts.post_id) as nPosts
		  				 FROM
						 	c_blog_categories as  cat
						left join c_blog_posts as posts on posts.category_id =  cat.id
                                                where cat.status = 1 AND posts.status = 1
						group by cat.id
						order by cat.category asc
							";
        $query = $CI->db->query($qu);
        if ($query->num_rows() > 0)
        {
            return $query->result_array();
        }
    }

}

if (!function_exists('loadBlogCategoriessimple'))
{

    function loadBlogCategoriessimple()
    {

        $CI    = & get_instance();
        $qu    = "SELECT cat.id, cat.category, count(posts.post_id) as nPosts
		  				 FROM
						 	c_blog_categories as  cat
						left join c_blog_posts as posts on posts.category_id =  cat.id
                                                where cat.status = 1 
						group by cat.id
						order by cat.category asc";
        $query = $CI->db->query($qu);
        if ($query->num_rows() > 0)
        {
            return $query->result_array();
        }
    }

}

/**
 * Get get_users_types.
 * @access	private
 * @return array
 */
function get_users_types()
{
    $CI = & get_instance();
    $CI->db->select('*');
    $CI->db->from('c_users_types');
    $CI->db->where('status', 1);
    $q  = $CI->db->get();
    if ($q->num_rows() > 0)
    {
        return $q->result_array();
    }
}

/**
 * Method: getChildren
 * params: $table, $ids
 * Returns: $ids
 */
if (!function_exists('getChilder'))
{

    function getChildren($table, $ids)
    {
        $ci           = &get_instance();
        $ids          = (array) $ids;
        $catid        = array_unique($ids);
        sort($ids);
        $array        = $ids;
        $implodeArray = implode(',', $array);
        $arrayNew     = array();
        for ($i = 0; $i <= count($array); $i++)
        {
            $query = "SELECT category_id FROM " . $ci->db->dbprefix($table) . " WHERE status=1 AND parent_id IN (" . $implodeArray . ") AND type_id NOT IN (" . $implodeArray . ") ";
            $query = $ci->db->query($query);
            if ($query->num_rows() > 0)
            {
                foreach ($query->result() as $row)
                {
                    $arrayNew [] = $row->category_id;
                }
            }
            $ids = array_merge($ids, $arrayNew);
        }
        $ids = array_unique($ids);
        return $ids;
    }

}


if (!function_exists('get_pages_footer'))
{

    function get_pages_footer($limit)
    {

        $ci = &get_instance();
        $ci->db->select('*');
        $ci->db->where('status', 1);
        $ci->db->where('show_footer', 1);
        $ci->db->order_by('ordering', 'ASC');
        $ci->db->limit($limit);
        $q  = $ci->db->get('c_contentmanagement');
        if ($q->num_rows() > 0)
        {
            return $q->result_array();
        }
    }

}
if (!function_exists('get_header_menu'))
{

    function get_header_menu($limit)
    {
        $ci = &get_instance();
        $ci->db->select('*');
        $ci->db->where('status', 1);
        $ci->db->where('show_header', 1);
        $ci->db->where('is_main_page', 1);
        $ci->db->order_by('ordering', 'ASC');
        $ci->db->limit($limit);
        $q  = $ci->db->get('c_contentmanagement');
        if ($q->num_rows() > 0)
        {
            return $q->result_array();
        }
    }

}

if (!function_exists('get_subMenu'))
{

    function get_subMenu($id)
    {
        $ci = &get_instance();
        $ci->db->select('*');
        $ci->db->where('status', 1);
        $ci->db->where('page_id', $id);
        $ci->db->order_by('ordering', 'ASC');
        $q  = $ci->db->get('c_contentmanagement');
        if ($q->num_rows() > 0)
        {
            return $q->result_array();
        }
    }

}

if (!function_exists('getCurrencies'))
{

    function getCurrencies()
    {
        $ci = &get_instance();
        $ci->db->select('*');
        $ci->db->where('status', 1);
        $ci->db->order_by('currency_id', 'ASC');
        $q  = $ci->db->get('c_currencies');
        if ($q->num_rows() > 0)
        {
            return $q->result_array();
        }
    }

}


if (!function_exists('getSiteCurrencySymbol'))
{

    function getSiteCurrencySymbol($column = '', $currency_id = '')
    {
        $ci = &get_instance();
        $ci->db->select('*');
        if ($currency_id <> '')
        {
            $ci->db->where('currency_id', $currency_id);
        }
        else
        {
            $ci->db->where('currency_id', CURRENCY);
        }
        $ci->db->where('status', 1);
        $q = $ci->db->get('c_currencies');
        if ($column <> '')
        {
            return $q->row_array()[$column];
        }
        else
        {
            return $q->row_array()['currency_symbol'];
        }
    }

}


if (!function_exists('get_subMenu_left'))
{

    function get_subMenu_left($id)
    {
        $ci = &get_instance();
        $ci->db->select('*');
        $ci->db->where('status', 1);
        $ci->db->where('show_left', 1);
        $ci->db->where('page_id', $id);
        $ci->db->order_by('ordering', 'ASC');
        $q  = $ci->db->get('c_contentmanagement');
        return $q->result_array();
    }

}

/**
 * Get fetchCmsPages.
 * @access	private
 * @return array
 */
function fetchCmsPages($slug)
{
    $CI    = & get_instance();
    $query = "SELECT cm.*
                  FROM
                 c_contentmanagement as cm
               WHERE cm.status = 1 AND cm.slug = '" . $slug . "'ORDER BY cm.cmId DESC  LIMIT 1 ";
    $query = $CI->db->query($query);
    if ($query->num_rows() > 0)
    {
        $result = $query->row_array();
        return $result;
    }
}

/**
 * Method: save_feedback
 * Params: $post
 * Return: True/False
 */
function save_feedback($data)
{
    $CI = & get_instance();
    $CI->db->insert('c_feedback', $data);
    $id = $CI->db->insert_id();
    return $id;
}

/**
 * Method: getUser Data
 * Params: $id
 * Return: data row
 */
function getUserData($id)
{
    $ci    = get_instance();
    $sql_  = 'SELECT usr.*,typ.type'
            . ' from c_users as usr '
            . ' INNER JOIN c_users_types typ ON usr.account_type = typ.id '
            . ' WHERE usr.user_id=' . $id;
    $query = $ci->db->query($sql_);
    if ($query->num_rows() > 0)
    {
        return $query->row_array();
    }
}

/**
 * Method: get_email_tempData
 * Params: $email_type
 * Return: array
 */
if (!function_exists('get_email_tempData'))
{

//    function get_email_tempData($email_type)
//    {
//        $ci    = get_instance();
//        $ci->db->select("*");
//        $ci->db->from('c_email_templates');
//        $ci->db->where('email_template_type', $email_type);
//        $ci->db->where('status', 1);
//        $ci->db->order_by('id', 'desc');
//        $ci->db->limit(1);
//        $query = $ci->db->get();
//        if ($query->num_rows() >= 1)
//        {
//            $email_template = $query->row_array();
//            $email_template['footer'] = $email_template['footer'];
//             $email_template['footer'] = str_replace('[SOCIAL_LINK_FB]',FACEBOOK, $email_template['footer']);
//             $email_template['footer'] = str_replace('[SOCIAL_LINK_GP]',GOOGLE, $email_template['footer']);
//             $email_template['footer'] = str_replace('[SOCIAL_LINK_TW]',  TWITTER, $email_template['footer']);
//             $email_template['footer'] = str_replace('[SOCIAL_LINK_LN]', LINKEDIN, $email_template['footer']);
//             
//            return $email_template;
//        }
//    }
    function get_email_tempData($email_type)
    {
        $FB    = '<li style="list-style: none; padding: 0 12px; display: inline-block;"><a href="' . FACEBOOK . '"><img alt="" height="50" src="https://www.shareads.com/assets/site/images/fb.png" width="50" /></a></li>';
        $GP    = '<li style="list-style: none; padding: 0 12px; display: inline-block;"><a href="' . GOOGLE . '"><img alt="" height="50" src="https://www.shareads.com/assets/site/images/G+.png" width="50" /></a></li>';
        $TW    = '<li style="list-style: none; padding: 0 12px; display: inline-block;"><a href="' . TWITTER . '"><img alt="" height="50" src="https://www.shareads.com/assets/site/images/twit.png" width="50" /></a></li>';
        $LN    = '<li style="list-style: none; padding: 0 12px; display: inline-block;"><a href="' . LINKEDIN . '"><img alt="" height="50" src="https://www.shareads.com/assets/site/images/l-in.png" width="50" /></a></li>';
        $ci    = get_instance();
        $ci->db->select("*");
        $ci->db->from('c_email_templates');
        $ci->db->where('email_template_type', $email_type);
        $ci->db->where('status', 1);
        $ci->db->order_by('id', 'desc');
        $ci->db->limit(1);
        $query = $ci->db->get();
        if ($query->num_rows() >= 1)
        {
            $email_template           = $query->row_array();
            $email_template['footer'] = $email_template['footer'];

            $html = '';
            if (trim(FACEBOOK) <> '')
            {
                $html .= $FB;
            }
            if (trim(GOOGLE) <> '')
            {
                $html .= $GP;
            }
            if (trim(TWITTER) <> '')
            {
                $html .= $TW;
            }
            if (trim(LINKEDIN) <> '')
            {
                $html .= $LN;
            }


            $email_template['footer'] = str_replace('[SOCIAL_LINKS]', $html, $email_template['footer']);

            return $email_template;
        }
    }

}
/**
 * ******For single column value
 * */
if (!function_exists('get_user_col_value'))
{

    function get_user_col_value($cols, $where = '', $criteria = '')
    {
        $ci          = &get_instance();
        $arr_results = array();
        $ci->db->select($cols);
        $ci->db->where($where, $criteria);
        $ci->db->from('users');
        $ci->db->limit(1);
        $results     = $ci->db->get();
        if ($results->num_rows() > 0)
        {
            return $results->row_array();
        }
    }

}
/**
 * ******For complete user data
 * */
if (!function_exists('get_user_data'))
{

    function get_user_data($col, $where = '', $criteria = '')
    {
        $ci      = &get_instance();
        $ci->db->select($col);
        $ci->db->where($where, $criteria);
        $ci->db->from('users');
        $ci->db->limit(1);
        $results = $ci->db->get();
        if ($results->num_rows() > 0)
        {
            return $results->row_array();
        }
    }

}

/**
 * ******For getProductIdFromUrl
 * */
if (!function_exists('getProductIdFromUrl'))
{

    function getProductIdFromUrl($purl)
    {
        $url   = explode('?', $purl);
        $ci    = &get_instance();
        $query = "SELECT
				  product_id
				FROM
				c_products
                    WHERE   url = '" . $url[0] . "'
          limit 1";
        $query = $ci->db->query($query);
        if ($query->num_rows() > 0)
        {
            $row = $query->row();
            return $row->product_id;
        }
        else
        {
            return 0;
        }
    }

}

/* Get Banner Code* */

function get_ad_code($direction, $ads_id, $parent_categories)
{
    $CI     = & get_instance();
    $cat_id = '';

    $urlType  = $CI->uri->segment(1);
    $urlType1 = $CI->uri->segment(2);
    if ($urlType == '' || $urlType == 'home')
    {
        $type = 1;
    }
    else
    {
        $type = 3;
    }

    if ($type <> 0)
    {
        $whr = '';
        if ($ads_id <> '' && $ads_id <> 0)
        {
            $whr .= ' AND ads_id <> ' . $ads_id;
        }

        if ($type <> '' && $type <> 0)
        {
            $whr .= ' AND is_home  = ' . $type;
        }

        //$end_date >= UNIX_TIMESTAMP() AND
        $query = "SELECT *
                  FROM
                 	c_announcements
					 WHERE   announcements_destination_id = '" . $direction . "' and status = 1 " . $whr . "
					 ORDER BY  RAND(), `ads_id` desc limit 1
                ";

        $query = $CI->db->query($query);
        if ($query->num_rows() > 0)
        {
            $result = $query->row_array();
            return $result;
        }
    }
}

/**
 * Get get_payment_intergration.
 * @access	private
 * @return array
 */
function get_payment_intergration()
{
    $ci     = & get_instance();
    $table1 = $ci->db->dbprefix . 'payment_integration';
    $ci->db->select('*');
    $ci->db->where('status',1);
    $ci->db->order_by('id', 'desc');
    $ci->db->limit(1);
    $q1     = $ci->db->get($table1);

    if ($q1->num_rows() > 0)
    {
        foreach ($q1->result() as $row1)
        {
            foreach ($row1 as $k => $v)
            {
                define(strtoupper($k), $v);
            }
        }
    }
    return $q1->row();
}

/**
 * Get get_social_intergration.
 * @access	private
 * @return array
 */
function get_social_intergration()
{
    $ci = & get_instance();

    $table2 = $ci->db->dbprefix . 'social_integrations';
    $ci->db->select('*');
    $ci->db->order_by('id', 'desc');
    $ci->db->limit(1);
    $q2     = $ci->db->get($table2);

    if ($q2->num_rows() > 0)
    {
        foreach ($q2->result() as $row2)
        {
            foreach ($row2 as $k => $v)
            {
                define(strtoupper($k), $v);
            }
        }
    }
}

function clearSessionIfAdminLoggedIn()
{
    $CI       = & get_instance();
    $is_admin = $CI->session->userdata('is_admin');

    if ($is_admin == 1)
    {
        $CI->session->unset_userdata(
                array(
                    'user_id',
                    'first_name',
                    'last_name',
                    'full_name',
                    'email',
                    'photo',
                    'user_name',
                    'photo',
                    'user_is_logged_in',
                    'is_admin'));
    }
}

function dd($arr = '')
{
    if (!empty($arr))
    {
        echo "<pre>";
        print_r($arr);
        die();
    }
    else
    {
        echo "<pre>";
        print_r($_POST);
        print_r($_FILES);
        die();
    }
}

if (!function_exists('getTotalUnpaidInvoices'))
{

    function getTotalUnpaidInvoices($user_id = '')
    {
        $ci = &get_instance();
        if (trim($user_id) == '')
        {
            $user_id = $ci->session->userdata('user_id');
        }
        $query = 'SELECT
		count(*) as  counter
		FROM
		c_invoices as invoices
		WHERE
		invoices.publisher_id ="' . $user_id . '" AND invoices.status = 0  limit 1';
        $query = $ci->db->query($query);
        $row   = $query->row();
        return $row->counter;
    }

}

function getBiweeklyRemainingDaysByMonth($month = '')
{
    $now_date = (int) trim(date('d'), "0");
    if ($month == '')
    {
        $month = (string) trim(date('d'), "0");
    }
    else
    {
        $month = (string) trim($month, "0");
    }

    if ($now_date == 1)
    {
        $start = (string) date("d", strtotime("first day of last month"));
        $end   = (string) date("d", strtotime("last day of last month"));
    }
    else
    {
        $start = (string) date("d", strtotime("first day of this month"));
        $end   = (string) date("d", strtotime("last day of this month"));
    }

    if ($month == "1")
    {
        $end = "31";
    }
    else if ($month == "2")
    {
        if ((int) date('Y') % 4 == 0)
        {
            $end = "29";
        }
        else
        {
            $end = "28";
        }
    }
    else if ($month == "3")
    {
        $end = "31";
    }
    else if ($month == "4")
    {
        $end = "30";
    }
    else if ($month == "5")
    {
        $end = "31";
    }
    else if ($month == "6")
    {
        $end = "30";
    }
    else if ($month == "7")
    {
        $end = "31";
    }
    else if ($month == "8")
    {
        $end = "31";
    }
    else if ($month == "9")
    {
        $end = "30";
    }
    else if ($month == "10")
    {
        $end = "31";
    }
    else if ($month == "11")
    {
        $end = "30";
    }
    else if ($month == "12")
    {
        $end = "31";
    }

    $dates_interval = array('start_date' => $start, 'end_date' => $end);

    if ($now_date == 1)
    {
        $days = (int) $dates_interval['end_date'] - 15;
        $days = (int) ($days + 1);
        return (int) $days;
    }
    else
    {
        $days = 15;
        return (int) $days;
    }
}

function get_currency_rate($value = 1, $currency_from = 1, $currency_to = 1)
{
    $ci               = &get_instance();
    $currency_slug_to = $ci->db->select('currency_name')->where('currency_id', $currency_to)->get('c_currencies')->row()->currency_name;

    if ($currency_from <> '')
    {
        $currency_slug_from   = $ci->db->select('currency_name')->where('currency_id', $currency_from)->get('c_currencies')->row()->currency_name;
        $result_currencies_db = $ci->db->where(array('currency_from' => $currency_slug_from, 'currency_to' => $currency_slug_to))->get('c_currency_rate')->row_array();
    }
    else
    {
        $result_currencies_db = $ci->db->where(array('currency_to' => $currency_slug_to))->get('c_currency_rate')->row_array();
    }
    if ($currency_from == $currency_to)
    {
        return $value;
    }
    else
    {
        $rate = $result_currencies_db['currency_value'] * $value;
        return $rate;
    }
}

function bitly_shorten($url)
{
    $query = array(
        "version" => "2.0.1",
        "longUrl" => $url,
        "login" => API_LOGIN, // replace with your login
        "apiKey" => API_KEY // replace with your api key
    );

    $query = http_build_query($query);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://api.bitly.com/v3/shorten?" . $query);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $response = curl_exec($ch);
    curl_close($ch);

    $response = json_decode($response);
    if ($response->status_txt == "OK")
    {
        return $response->data->url;
    }
    else
    {
        return $url;
    }
}

function generateRandomString($length = 10)
{
    $characters       = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString     = '';
    for ($i = 0; $i < $length; $i++)
    {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

if (!function_exists('is_script_exists'))
{

    function is_script_exists($url = '', $script_to_verify = '')
    {
        $is_script_exit = 0;
        if ($url <> '' && $script_to_verify <> '')
        {
            $html  = file_get_contents($url);
            $dom   = new DOMDocument;
            @$dom->loadHTML($html);
            $links = $dom->getElementsByTagName('script');
            foreach ($links as $link)
            {
                if (strpos($link->getAttribute('src'), $script_to_verify) !== false)
                {
                    $is_script_exit = 1;
                }
            }
        }
        return (bool) $is_script_exit;
    }

}

if (!function_exists('get_unique_device'))
{

    function get_unique_device()
    {
//        return md5($_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
        return md5($_SERVER['REMOTE_ADDR']);
    }

}

if (!function_exists('get_client_ip'))
{

    function get_client_ip()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    function make_excel_export($headers = '', $values = '', $filename = 'excel_export')
    {
        $file_ending = "xls";
        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=$filename.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        $sep         = "\t";
        for ($i = 0; $i < mysql_num_fields($headers); $i++)
        {
            echo mysql_field_name($headers, $i) . "\t";
        }
        print("\n");
        while ($row = mysql_fetch_row($values))
        {
            $schema_insert = "";
            for ($j = 0; $j < mysql_num_fields($values); $j++)
            {
                if (!isset($row[$j]))
                    $schema_insert .= "NULL" . $sep;
                elseif ($row[$j] != "")
                    $schema_insert .= "$row[$j]" . $sep;
                else
                    $schema_insert .= "" . $sep;
            }
            $schema_insert = str_replace($sep . "$", "", $schema_insert);
            $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
            $schema_insert .= "\t";
            print(trim($schema_insert));
            print "\n";
        }
    }

}

function get_users_by_type($type)
{
    if ($type <> '')
    {
        $CI = & get_instance();
        $CI->db->select('*');
        $CI->db->from('c_users');
        $CI->db->where('status', 1);
        $CI->db->where('user_id != 1');
        $CI->db->where('account_type', $type);
        $q  = $CI->db->get();
        if ($q->num_rows() > 0)
        {
            return $q->result_array();
        }
    }
    else
    {
        return null;
    }
}
