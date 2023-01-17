<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Support extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('support_model');
    }

    function index($type = '')
    {

        $data                   = array();
        $data['search_support'] = '';
        if (isset($_POST) && $_POST['search_support'] <> '')
        {
            $data['search_support'] = ucfirst($_POST['search_support']);
        }
        if($type == 'm'){$data['mobile_link'] = 'm';}else{$data['mobile_link'] = '';}
        $data['all_posts']        = $this->support_model->loadSupportListing(trim($_POST['search_support']));
        $data['all_posts_titles'] = $this->support_model->loadtitles();
        $data['pagination']       = $this->pagination->create_links();

        $data ['content'] = $this->load->view('support_list', $data, true);

        if ($type == 'm')
        {
            $this->load->view('includes/template_fullbody_mobile.view.php', $data);
        }
        else
        {
            $this->load->view('includes/template_fullbody.view.php', $data);
        }
    }

    function posts($postid = 0, $type = '')
    {
        $data = array();

        $data['post_id']    = $post_id            = $this->common->decode($postid);
        $data['posts']      = $this->support_model->loadSupportDetails($post_id);
        $data['pagination'] = $this->pagination->create_links();
        if($type == 'm'){$data['mobile_link'] = 'm';}else{$data['mobile_link'] = '';}
        $data ['content'] = $this->load->view('support', $data, true);
        if ($type == 'm')
        {
            $this->load->view('includes/template_fullbody_mobile.view.php', $data);
        }
        else
        {
            $this->load->view('includes/template_fullbody.view.php', $data);
        }
    }

}
