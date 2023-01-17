<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

// --------------------------------------------------------------------------
// Engine Init Class - V1.0
// --------------------------------------------------------------------------

/**
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * THIS SOFTWARE AND DOCUMENTATION IS PROVIDED "AS IS," AND COPYRIGHT
 * HOLDERS MAKE NO REPRESENTATIONS OR WARRANTIES, EXPRESS OR IMPLIED,
 * INCLUDING BUT NOT LIMITED TO, WARRANTIES OF MERCHANTABILITY OR
 * FITNESS FOR ANY PARTICULAR PURPOSE OR THAT THE USE OF THE SOFTWARE
 * OR DOCUMENTATION WILL NOT INFRINGE ANY THIRD PARTY PATENTS,
 * COPYRIGHTS, TRADEMARKS OR OTHER RIGHTS.COPYRIGHT HOLDERS WILL NOT
 * BE LIABLE FOR ANY DIRECT, INDIRECT, SPECIAL OR CONSEQUENTIAL
 * DAMAGES ARISING OUT OF ANY USE OF THE SOFTWARE OR DOCUMENTATION.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://gnu.org/licenses/>.
 */
class Engineinit {

    function Engineinit() {
        $CI = & get_instance();
    }

    /**
     * General site data retrival
     * @access public
     * @return mixed
     */
    function boot_engine() {
        $CI = &get_instance();
        $data['site_name'] = $CI->config->item('site_name');
        $data['base_url'] = $CI->config->item('base_url');
        $data['site_email'] = $CI->config->item('site_email');
        $data['site_version'] = $CI->config->item('site_version');
        log_message('info', $data['site_name'] . ' ' . $data['site_version'] . ' Booted.');
        return $data;
    }

    /**
     * Redirect user if user is not logged in
     * @access	private
     */
    function _is_not_admin_logged_in_redirect($redirect_url) {
        $CI = & get_instance();
        $is_logged_in = $CI->session->userdata('user_is_logged_in');
        $is_admin = $CI->session->userdata('is_admin');
        
           
        if ($is_logged_in == '' || ($is_admin == '' || $is_admin == 0)) {
            $last_url = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
            $last_url .= "://" . $_SERVER['HTTP_HOST'] . $_SERVER ['REQUEST_URI'];
            redirect($redirect_url . '?last_url=' . urlencode($last_url));
        }
    }

    /**
     * Redirect user if user is not Super Admin
     * @access	private
     */
    function _is_not_super_admin_redirect($redirect_url) {
        $CI = & get_instance();
        $is_logged_in = $CI->session->userdata('role_id');
        $is_admin = $CI->session->userdata('is_admin');
        if ($is_logged_in <> '0' && $is_admin <> '') {
            // @todo CI is initialized twice due the redirect, need to verify and fix it.
            redirect($redirect_url);
        }
    }
    
    function forcefullyLogedOut($error) {
        $CI = & get_instance();

//        $CI->session->sess_destroy();
        $CI->session->unset_userdata(
                array(
                    'user_id','fb_account_id',
                    'first_name',
                    'last_name',
                    'full_name',
                    'email',
                    'photo',
                    'gender',
                    'user_name',
                    'role_id',
                    'user_is_logged_in',
                    'is_admin'));

//        session_destroy();
        $CI->session->unset_userdata('fb_access_token');

        $CI->session->set_flashdata('error_message', $error);
        $CI->session->keep_flashdata('error_message');

        redirect(base_url('login'), 'refresh');


    }

    /**
     * Redirect user if already logged in
     * @access	private
     */
    function _is_logged_in_redirect($redirect_url) {
        $CI = & get_instance();
        $is_logged_in = $CI->session->userdata('user_is_logged_in');
        if ($is_logged_in == '1') {
            // @todo CI is initialized twice due the redirect, need to verify and fix it.
            redirect($redirect_url);
        }
    }

    /**
     * Redirect user if user is not logged in
     * @access	private
     */
    function _is_not_logged_in_redirect($redirect_url) {
        $CI = & get_instance();

        $user_id = $CI->session->userdata('user_id');
        $DB_STATUS = getVal('status', 'c_users',array('user_id' => $user_id));
        $is_logged_in = $CI->session->userdata('user_is_logged_in');
        $is_admin = $CI->session->userdata('is_admin');
        if (($is_logged_in == '0' || $is_logged_in == '') || $is_admin == 1 || ($DB_STATUS == 0 || $DB_STATUS == '')) {
            $last_url = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
            $last_url .= "://" . $_SERVER['HTTP_HOST'] . $_SERVER ['REQUEST_URI'];
            
            
            // @todo CI is initialized twice due the redirect, need to verify and fix it.
            redirect($redirect_url . '/logout?last_url=' . urlencode($last_url));
        }
    }



    /**
     * Get admin from the logged in session.
     * @access	private
     * @return string
     */
    function get_session_super_admin() {
        $CI = & get_instance();
        $role_id = $CI->session->userdata('role_id');
        return $role_id;
    }

    /**
     * Redirect user if user is not logged in
     * @access	private
     */
    function _is_not_access_admin_redirect($redirect_url) {
        $CI = & get_instance();
        $account_type = $CI->session->userdata('account_type');
        if ($account_type == 1) {
            // @todo CI is initialized twice due the redirect, need to verify and fix it.
            redirect($redirect_url);
        }
    }

}

?>
