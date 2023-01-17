<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Marketing extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        get_social_intergration();
        // check if admin login
        $this->engineinit->_is_not_logged_in_redirect('login');
        $this->load->model('marketing_model');
    }

 public function get_bitly_url(){
        
        echo bitly_shorten($_POST['url']); die();
    }
    public function index($slug)
    {

        $data = array();
        if ($slug)
            $data['category_id'] = getVal('category_id', 'categories', 'category_slug', $slug);
        if($_POST)
        {
            $results_full = $this->marketing_model->loadListings($data);
        }
        else
        {
            $results_full = $this->marketing_model->loadListings_withoutFilter($data);
        }
        

        $results = $results_full->result();
        $data['num_rows'] = $results_full->num_rows();

        ////////////////////////////////////////////////////////////////////
        $user_currency = getVal('currency', 'c_users', 'user_id', $this->session->userdata('user_id'));
        foreach ($results as $key => $value)
        {
            if ($user_currency <> $results[$key]->currency)
            {
                $results[$key]->commission = number_format((float) get_currency_rate($value->commission, $value->currency, $user_currency), 2);
            }
        }

        $array = $results;
        if (isset($_POST['type']) && $_POST['type'] <> '' && ($_POST['type'] == 'lowest_paying' || $_POST['type'] == 'highest_paying'))
        {
            // Lower to higher 
            if ($_POST['type'] == 'lowest_paying')
            {
                for ($j = 0; $j < count($array); $j ++)
                {
                    for ($i = 0; $i < count($array) - 1; $i ++)
                    {
                        $smbl  = $array[$i]->commission;
                        $smbl1 = $array[$i + 1]->commission;
                        if ($smbl > $smbl1)
                        {
                            $temp          = $array[$i + 1];
                            $array[$i + 1] = $array[$i];
                            $array[$i]     = $temp;
                        }
                    }
                }
            }
            if ($_POST['type'] == 'highest_paying')
            {
                for ($j = 0; $j < count($array); $j ++)
                {
                    for ($i = 0; $i < count($array) - 1; $i ++)
                    {
                        $smbl  = $array[$i]->commission;
                        $smbl1 = $array[$i + 1]->commission;
                        if ($smbl < $smbl1)
                        {
                            $temp          = $array[$i + 1];
                            $array[$i + 1] = $array[$i];
                            $array[$i]     = $temp;
                        }
                    }
                }
            }
        }

        $results = $array;
        foreach ($results as $key => $value)
        {
                $results[$key]->commission = getSiteCurrencySymbol('', $user_currency) . number_format((float) $value->commission, 2);
        }
        
        ////////////////////////////////////////////////////////////////////
        $data['results'] = $results;

        $data['pagination'] = $this->pagination->create_links();

        $data['user_id']    = $this->session->userdata('user_id');
        $data['categories'] = $this->marketing_model->getAllCategories();

        $data ['content'] = $this->load->view('marketing', $data, true);
        $this->load->view('includes/template_dashboard.view.php', $data);
    }

    function advance_search()
    {
        $data            = array();
        $data['user_id'] = $this->session->userdata('user_id');

        $date           = date('Y-m-d');
        $data['search'] = unserialize($this->input->cookie('searchData_' . $date, TRUE));

        $data['categories'] = $this->marketing_model->getAllCategories();
        $data ['content']   = $this->load->view('advance_search', $data, true);
        $this->load->view('includes/template_dashboard.view.php', $data);
    }

    function shareLinkCopy()
    {
        $data                  = array();
        $data['product_id']    = $this->input->post('id');
        $data['link']          = $this->input->post('link');
        $data['user_id']       = $this->session->userdata('user_id');
        $data['share_type']    = $this->input->post('type');
        $data['share_counter'] = 1;
        $data['created']       = time();
        $this->marketing_model->saveSharedLinkCopy($data);
        echo 1;
    }

    function search()
    {

        $data = array();
        if (isset($_GET))
        {
            $data = array();
            foreach ($_GET as $k => $v)
            {

                if (is_array($v))
                {

                    $data[$k] = implode(',', $v);
                }
                else
                {

                    $data[$k] = $v;
                }
            }

            $cookie_data = serialize($data);
            $created_1   = date('Y-m-d', strtotime("-1 days"));
            $this->input->set_cookie('searchData_' . $created_1, '');
            $created     = date('Y-m-d');
            $this->input->set_cookie('searchData_' . $created, $cookie_data, 86500);
        }

        $results_full    = $this->marketing_model->loadListings($data);
        
        $results = $results_full->result();
        $data['num_rows'] = $results_full->num_rows();

        ////////////////////////////////////////////////////////////////////
       
        $user_currency = getVal('currency', 'c_users', 'user_id', $this->session->userdata('user_id'));
        foreach ($results as $key => $value)
        {
            if ($user_currency <> $results[$key]->currency)
            {
                $results[$key]->commission = number_format((float) get_currency_rate($value->commission, $value->currency, $user_currency), 2);
            }
        }
//        dd($results);
        $array = $results;
        if (isset($_GET['type']) && $_GET['type'] <> '' && ($_GET['type'] == 'lowest_paying' || $_GET['type'] == 'highest_paying'))
        {
            // Lower to higher 
            if ($_GET['type'] == 'lowest_paying')
            {
                for ($j = 0; $j < count($array); $j ++)
                {
                    for ($i = 0; $i < count($array) - 1; $i ++)
                    {
                        $smbl  = $array[$i]->commission;
                        $smbl1 = $array[$i + 1]->commission;
                        if ($smbl > $smbl1)
                        {
                            $temp          = $array[$i + 1];
                            $array[$i + 1] = $array[$i];
                            $array[$i]     = $temp;
                        }
                    }
                }
            }
            if ($_GET['type'] == 'highest_paying')
            {
                for ($j = 0; $j < count($array); $j ++)
                {
                    for ($i = 0; $i < count($array) - 1; $i ++)
                    {
                        $smbl  = $array[$i]->commission;
                        $smbl1 = $array[$i + 1]->commission;
                        if ($smbl < $smbl1)
                        {
                            $temp          = $array[$i + 1];
                            $array[$i + 1] = $array[$i];
                            $array[$i]     = $temp;
                        }
                    }
                }
            }
        }
        $results = $array;
        foreach ($results as $key => $value)
        {
                $results[$key]->commission = getSiteCurrencySymbol('', $user_currency) . number_format((float) $value->commission, 2);
        }

        ////////////////////////////////////////////////////////////////////
        $data['results'] = $results;

        $data['pagination'] = $this->pagination->create_links();

        $data['user_id']    = $this->session->userdata('user_id');
        $data['categories'] = $this->marketing_model->getAllCategories();
        $data ['content']   = $this->load->view('marketing', $data, true);
        $this->load->view('includes/template_dashboard.view.php', $data);
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */