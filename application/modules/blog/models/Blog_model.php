<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Blog_Model extends CI_Model {

    var $tbl = 'blog_posts';
    var $tbl_likes = 'blog_likes';

    public function __construct() {

        parent::__construct();
    }

//End __construct
    // Common Functions

    public function loadBlogCommnets($post_id) {

        $sql_ = 'SELECT
                    coment.*, users.full_name as user_name,users.photo,users.gender
                FROM
                    c_blog_comments as coment
                  inner join c_users as users on   users.user_id  = coment.user_id
		';
        if ($post_id > 0)
            $sql_ .= " where coment.post_id   = '" . $post_id . "'";

        $perpage = 10; //global_setting('perpage');
        $offset = 0;
        if ($this->uri->segment(4) > 0) {

            $offset = $this->uri->segment(4);
        } else {

            $offset = 0;
        }
        $query = $this->db->query($sql_);
        $total_records = $query->num_rows();
        init_front_pagination_blog('blog/posts/' . $this->common->encode($post_id), $total_records, $perpage, '', $post_id);
        $sql_.= $where . "ORDER BY coment.comment_id DESC";

        $sql_.=" LIMIT " . $offset . ", " . $perpage . "";

        $query = $this->db->query($sql_);
        return $query->result_array();
    }

    public function totalComentsCounter($post_id) {

        $query = 'SELECT
                    count(comments.comment_id) as counter
                FROM
                    c_blog_comments comments

		where comments.post_id  = "' . $post_id . '"';
        $result = $this->db->query($query);
        $row = $result->row();
        return $row->counter;
    }

    public function loadBlogDetails($post_id) {

        $sql_ = 'SELECT
                    blog_posts.*, count(comments.comment_id) as nCounts

                FROM
                    ' . $this->db->dbprefix . $this->tbl . ' as  blog_posts
                    left join c_blog_comments as comments on comments.post_id  = blog_posts.post_id
		';
        $sql_ .= " where blog_posts.post_id  = '" . $post_id . "' AND blog_posts.status = 1";

        // $query = $this->db->query($sql_);
        $sql_.=" LIMIT 1";

        $query = $this->db->query($sql_);
        return $query->row_array();
    }

    public function loadBlogListing() {
        $sql_ = 'SELECT
                    blog_posts.*, count(comments.comment_id) as nCounts
                FROM
                    ' . $this->db->dbprefix . $this->tbl . ' as blog_posts
                    left join c_blog_comments as comments on comments.post_id  = blog_posts.post_id

		';
        $sql_ .= 'where blog_posts.status = 1 ';


        $sql_ .= " group by blog_posts.post_id	";
        $perpage = 10; //global_setting('perpage');
        $offset = 0;
        if ($this->uri->segment(2) > 0) {
            $offset = $this->uri->segment(2);
        } else {

            $offset = 0;
        }
        $query = $this->db->query($sql_);
        $total_records = $query->num_rows();
        init_front_pagination('blog', $total_records, $perpage);
        $sql_.= " ORDER BY post_id DESC";

        $sql_.=" LIMIT " . $offset . ", " . $perpage . "";


        $query = $this->db->query($sql_);
        return $query->result_array();
    }

    public function loadBlogCategoryListing($category_id) {
        $sql_ = 'SELECT
                    blog_posts.*, count(comments.comment_id) as nCounts
                FROM
                    ' . $this->db->dbprefix . $this->tbl . ' as blog_posts
                    left join c_blog_comments as comments on comments.post_id  = blog_posts.post_id

		';
        $sql_ .= "where blog_posts.status = 1 AND blog_posts.category_id  = '" . $category_id . "'";

        $sql_ .= " group by blog_posts.post_id	";
        $perpage = 10; //global_setting('perpage');
        $offset = 0;
        if ($this->uri->segment(4) > 0) {
            $offset = $this->uri->segment(4);
        } else {

            $offset = 0;
        }

        $query = $this->db->query($sql_);
        $total_records = $query->num_rows();
        $cat = $this->common->encode($category_id);
        init_front_pagination_blog('blog/category/' . $cat, $total_records, $perpage, '', $category_id);
        $sql_.= " ORDER BY post_id DESC";

        $sql_.=" LIMIT " . $offset . ", " . $perpage . "";

        $query = $this->db->query($sql_);
        return $query->result_array();
    }

    /**

     * Method: saveItem

     * Params: $post

     * Return: True/False

     */
    public function saveCommnets($post) {

        $data_insert = array();

        if (is_array($post)) {

            foreach ($post as $k => $v) {
                $data_insert[$k] = $v;
            }
        }
        $data_insert['user_id'] = $this->session->userdata('user_id'); {//Save Data
            $data_insert['created'] = time();
            $this->db->insert('c_blog_comments', $data_insert);
        }
        $msg = "Your comments has been saved.";

        return $msg;
    }

    /**
     *
     *
     *
     * To check that member like this page or not.
     *
     * @param $data Array
     *       	 containing page id and member id.
     */
    function isLike($data) {
        $user_id = $this->session->userdata('user_id');
        $query = " SELECT count(*) as counter
		FROM
		c_blog_likes

		where user_id = " . $user_id . " AND item_id = " . $data ['item_id'] . " LIMIT 1";
        $result = $this->db->query($query);
        $row = $result->row();
        return $row->counter;
    }

    function incrlikeCounter($data) {

        $query = "update " . $this->db->dbprefix . $this->tbl . " set like_counter = (like_counter + 1) where post_id = " . $data ['item_id'] . " ";
        $query = $this->db->query($query);


        $data = array('user_id' => $data ['user_id'], 'created' => time(), 'item_id' => $data ['item_id']);
        $this->db->insert($this->db->dbprefix . $this->tbl_likes, $data);

        return true;
    }

    function decrlikeCounter($data) {

        $query = "update " . $this->db->dbprefix . $this->tbl . " set like_counter = (like_counter - 1) where post_id = " . $data ['item_id'] . " ";
        $query = $this->db->query($query);

        $query = "delete from " . $this->db->dbprefix . $this->tbl_likes . " where item_id = " . $data ['item_id'] . " AND user_id = " . $data ['user_id'] . "    ";
        $query = $this->db->query($query);

        return true;
    }

    function ckeckPostLikeOrNot($data) {
        $user_id = $this->session->userdata('user_id');
        $query = ' SELECT count(*) as counter
FROM
  ' . $this->db->dbprefix . $this->tbl_likes . '
  where   item_id="' . $data ['item_id'] . '"  and user_id = "' . $user_id . '" LIMIT 1';
        $query = $this->db->query($query);
        $row = $query->row();
        return $row->counter;
    }

}

//End Class