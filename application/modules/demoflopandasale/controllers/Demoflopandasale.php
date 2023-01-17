<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Demoflopandasale extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        get_payment_intergration();
        $this->load->model('demoflopandasale_model');
    }

    public function index()
    {
        $data = array();
        $this->load->view('index.php', $data);
    }

    function success()
    {
        $data = array();
        $this->load->view('success.php', $data);
    }

}
