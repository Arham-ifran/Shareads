<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Blog extends CI_Controller {

    function __construct() {
        parent::__construct();
        $data = $this->engineinit->boot_engine();
        $this->load->model('blog_model');
    }

    function index() {
        $data = array();
        $data['all_posts'] = $this->blog_model->loadBlogListing();
        $data['pagination'] = $this->pagination->create_links();
        $data['categories'] = loadBlogCategoriessimple();
        $data ['content'] = $this->load->view('blogs', $data, true);
        $this->load->view('includes/template_fullbody.view.php', $data);
    }

    function category($category_id = 0) {
        $data = array();
        $category_id = $this->common->decode($category_id);
        $data['all_posts'] = $this->blog_model->loadBlogCategoryListing($category_id);
        $data['pagination'] = $this->pagination->create_links();
      // $data['categories'] = loadBlogCategories();
        $data['categories'] = loadBlogCategoriessimple();
        $data['cat_id'] = $category_id;
        $data ['content'] = $this->load->view('blogs', $data, true);
        $this->load->view('includes/template_fullbody.view.php', $data);
    }


    function posts($postid = 0) {
        $data = array();

        $data['post_id'] = $post_id = $this->common->decode($postid);
        $data['posts'] = $this->blog_model->loadBlogDetails($post_id);
        $data['comments'] = $this->blog_model->loadBlogCommnets($post_id);
        $data['pagination'] = $this->pagination->create_links();
        $data['categories'] = loadBlogCategories();

        if($this->session->userdata('user_id'))
        {
            $data['user_id'] = $this->session->userdata('user_id');
            $data['item_id'] = $data['post_id'];
            $data['isLike'] = $this->blog_model->isLike($data);
            $data ['userdata'] = getUserData($this->session->userdata('user_id'));
        }

        $data ['content'] = $this->load->view('posts', $data, true);
        $this->load->view('includes/template_fullbody.view.php', $data);
    }

    public function ajaxSave() {
        if($_POST['post_id'] <> '')
        {
        $result = $this->blog_model->saveCommnets($_POST);
        }
        echo $result;
    }

    function like() {
        $data = array();
        $data['user_id'] = $user_id = $this->session->userdata('user_id');
        $data ['item_id'] = trim($this->input->get_post("id"));

        $likeCounter = $this->blog_model->ckeckPostLikeOrNot($data);
        if ($likeCounter == '0') {
            $this->blog_model->incrlikeCounter($data);
        }
    }

    function dislike() {
        $data = array();
        $data['user_id'] = $this->session->userdata('user_id');
        $data ['item_id'] = trim($this->input->get_post("id"));

        $this->blog_model->decrlikeCounter($data);
    }



}
