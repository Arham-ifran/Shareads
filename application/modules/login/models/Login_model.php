<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login_model extends CI_Model {

    var $tbl = 'users';

    public function __construct() {
        parent::__construct();
    }

//End __construct
    /**
     * Method: ajaxLogin
     * params: $_POST
     * Retruns:
     */
    public function ajaxLogin($email, $passwrd) {

        $password = md5($passwrd);
        $this->db->select('*');
        $this->db->where('status', 1);
        $this->db->where('email', $email);
        $this->db->where('password', $password);
        $this->db->limit(1);
        $qry = $this->db->get($this->db->dbprefix($this->tbl));

        if ($qry->num_rows() > 0) {
            foreach ($qry->result() as $result) {
                $user_session['user_id'] = $result->user_id;
                $user_session['first_name'] = $result->first_name;
                $user_session['last_name'] = $result->last_name;
                $user_session['full_name'] = ucwords($result->full_name);
                $user_session['user_name'] = ucwords($result->user_name);
                $user_session['email'] = $result->email;
                $user_session['photo'] = $result->photo;
                $user_session['gender'] = $result->gender;
                $user_session['account_type'] = $result->account_type;
                $user_session['user_key'] = $result->user_key;
                $user_session['currency'] = $result->currency;
                $user_session['is_admin'] = 0;
                $user_session['user_is_logged_in'] = 1;
            }
            $this->session->set_userdata($user_session);

            $data = array(
                'last_activity_time' => time());
            $this->db->where('user_id', $user_session['user_id']);
            $this->db->update($this->db->dbprefix($this->tbl), $data);


            return true;
        } else {
            return false;
        }
    }

    public function ajaxLoginPopup($email, $passwrd) {

        $password = md5($passwrd);
        $this->db->select('*');
        $this->db->where('status', 1);
        $this->db->where('email', $email);
        $this->db->where('password', $password);
        $this->db->limit(1);
        $qry = $this->db->get($this->db->dbprefix($this->tbl));

        if ($qry->num_rows() > 0) {
            foreach ($qry->result() as $result) {
                $user_session['user_id'] = $result->user_id;
                $user_session['first_name'] = $result->first_name;
                $user_session['last_name'] = $result->last_name;
                $user_session['full_name'] = ucwords($result->full_name);
                $user_session['user_name'] = ucwords($result->user_name);
                $user_session['email'] = $result->email;
                $user_session['photo'] = $result->photo;
                $user_session['gender'] = $result->gender;
                $user_session['currency'] = $result->currency;
                $user_session['account_type'] = $result->account_type;
                $user_session['user_key'] = $result->user_key;
                $user_session['is_admin'] = 0;
                $user_session['user_is_logged_in'] = 1;
            }
            $this->session->set_userdata($user_session);
            $data = array(
                'last_activity_time' => time());
            $this->db->where('user_id', $user_session['user_id']);
            $this->db->update($this->db->dbprefix($this->tbl), $data);


            return true;
        } else {
            return false;
        }
    }

    /**
     * Method: updateUser
     * Return: 0/1
     */
    function updateUser($email, $password) {
        $data = array();
        $data['password'] = md5($password);
        $data['orignal_password'] = $password;
        $this->db->where('email', $email);
        $this->db->update($this->db->dbprefix($this->tbl), $data);
        return true;
    }

    /**
     * Method: checkEmail
     * Return: 0/1
     */
    function checkEmail($email) {

        $sql_ = "SELECT email FROM " . $this->db->dbprefix($this->tbl) . " WHERE email = '" . $email . "'";
        $query = $this->db->query($sql_);
        if ($query->num_rows() >= 1) {
            return 1;
        } else {
            return 0;
        }
    }
    
    /**
     * Method: checkStatusFromEmail
     * Return: 0/1
     */
    function checkStatusFromEmail($email) {

        $sql_ = "SELECT email FROM " . $this->db->dbprefix($this->tbl) . " WHERE email = '" . $email . "' AND status = 1 ";
        $query = $this->db->query($sql_);
        if ($query->num_rows() >= 1) {
            return 1;
        } else {
            return 0;
        }
    }


    public function ajaxLoginTwitterFB($data,$passw) {

        $password = md5($passw);
        $this->db->select('*');
        $this->db->where('status', 1);
        $this->db->where('connected_by_id', $data['connected_by_id']);
//        $this->db->where('password', $password);
        $this->db->limit(1);
        $qry = $this->db->get('users');

        if ($qry->num_rows() > 0) {

            foreach ($qry->result() as $result) {
                $user_session['user_id'] = $result->user_id;
                $user_session['first_name'] = $result->first_name;
                $user_session['last_name'] = $result->last_name;
                $user_session['full_name'] = ucwords($result->full_name);
                $user_session['user_name'] = ucwords($result->user_name);
                $user_session['email'] = $result->email;
                $user_session['currency'] = $result->currency;
                $user_session['photo'] = $result->photo;
                $user_session['gender'] = $result->gender;
                $user_session['account_type'] = $result->account_type;
                $user_session['user_key'] = $result->user_key;
                $user_session['is_admin'] = 0;
                $user_session['user_is_logged_in'] = 1;
            }

            $this->session->set_userdata($user_session);

            $data = array(
          'last_activity_time' => time());
        $this->db->where('user_id', $user_session['user_id']);
        $this->db->update($this->db->dbprefix('users'), $data);
//        return $update;


            return true;
        } else {
            return false;
        }
    }


    public function loginTwitterFBlinkedIn_exist($email) {


        $this->db->select('*');
        $this->db->where('status', 1);
        $this->db->where('email', $email);
        $this->db->limit(1);
        $qry = $this->db->get('users');

        if ($qry->num_rows() > 0) {

            foreach ($qry->result() as $result) {
                $user_session['user_id'] = $result->user_id;
                $user_session['first_name'] = $result->first_name;
                $user_session['last_name'] = $result->last_name;
                $user_session['full_name'] = ucwords($result->full_name);
                $user_session['user_name'] = ucwords($result->user_name);
                $user_session['email'] = $result->email;
                $user_session['photo'] = $result->photo;
                $user_session['gender'] = $result->gender;
                $user_session['currency'] = $result->currency;
                $user_session['account_type'] = $result->account_type;
                $user_session['user_key'] = $result->user_key;
                $user_session['is_admin'] = 0;
                $user_session['user_is_logged_in'] = 1;
            }

            $this->session->set_userdata($user_session);

            $data = array(
          'last_activity_time' => time());
        $this->db->where('user_id', $user_session['user_id']);
        $this->db->update($this->db->dbprefix('users'), $data);
//        return $update;

            return true;
        } else {
            return false;
        }
    }



    /**
     * Method: checkUserName
     * Return: 0/1
     */
    function checkUserName($slug)
   {
        $sqlChk = "SELECT user_name FROM " . $this->db->dbprefix($this->tbl) . " WHERE user_name = '" . $slug . "'";
            $query = $this->db->query($sqlChk);
             if ($query->num_rows() >= 1) {
                 return 1;
             }  else {
                 return 0;
             }
   }

   public function register_user($post) {

        $user_key = $this->common->uniqueKey(5);
        $sqlk = "SELECT user_key FROM " . $this->db->dbprefix($this->tbl) . " WHERE user_key = '" . $user_key . "'";
            $qu = $this->db->query($sqlk);
            if ($qu->num_rows() >= 1) {
                $post['user_key'] = $this->common->uniqueKey(5);
            }else{
                $post['user_key'] = $user_key;
            }

        $this->db->insert($this->db->dbprefix('users'), $post);
        $user_id = $this->db->insert_id();

        $action = 'Your Account created successfully. Please complete your profile.';
        $msg = $action;
        return $msg;
    }



}

//End Class