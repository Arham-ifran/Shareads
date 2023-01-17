<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Wallet extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // check if admin login
        $this->engineinit->_is_not_logged_in_redirect('login');
        $this->load->model('wallet_model');
        $this->load->library('emailutility');
        if ($this->session->userdata('account_type') == 2)
        {
            redirect(base_url('dashboard'));
        }
//          ini_set('display_errors', 1);
//          ini_set('display_startup_errors', 1);
//          error_reporting(E_ALL);
    }

    public function index()
    {
        $data                 = array();
        $user_id              = $this->session->userdata('user_id');
        $data['userdata']     = getUserData($user_id);
        $totalCommission      = $this->wallet_model->getTotalCommission($user_id);
        $totalCommissionAdmin = $this->wallet_model->getTotalCommissionAdmin($user_id);

        $totalCommission    = $totalCommission_bk = $totalCommissionAdmin + $totalCommission;

        $totalUnconfirmedCommission = $this->wallet_model->getTotalUnconfirmedCommission($user_id);

        $data['withdrawRequestsList']        = $this->wallet_model->getWithdrawRequestsByStatus();
        $data['pendingwithdrawRequestsList'] = $this->wallet_model->getWithdrawRequestsByStatus(0);
        $data['successwithdrawRequestsList'] = $this->wallet_model->getWithdrawRequestsByStatus(1);
        $data['lastWithdrawAmount']          = $this->wallet_model->getLastSuccessWidthdraw();
        $lastWithdrawAmount                  = $this->wallet_model->getLastsWidthdraw();

        $pendingwithdrawRequestsSUM = $this->wallet_model->getWithdrawRequestsSUMByStatus(0);
        $successwithdrawRequestsSUM = $this->wallet_model->getWithdrawRequestsSUMByStatus(1);
        $data['successWithdrawn']   = number_format($successwithdrawRequestsSUM['total_amount'], 2);

        $totalPendingPayment = ((empty($pendingwithdrawRequestsSUM['total_amount']) || $pendingwithdrawRequestsSUM['total_amount'] > 0) ? $pendingwithdrawRequestsSUM['total_amount'] : 0);
        $totalsuccessPayment = ((empty($successwithdrawRequestsSUM['total_amount']) || $successwithdrawRequestsSUM['total_amount'] > 0) ? $successwithdrawRequestsSUM['total_amount'] : 0);



        $totalCommission = $totalCommission - $totalPendingPayment;

        $totalCommission    = number_format(($totalCommission), 2);
        $totalCommission_bk = number_format(($totalCommission_bk), 2);
        if ($totalCommission >= 0)
        {
            $data['totalCommission'] = $totalCommission;
        }
        else
        {
            $data['totalCommission'] = 0;
        }

        if ($data['totalCommission'] < 0)
        {
            $data['totalCommission'] = number_format(0, 2);
        }

        $data ['content'] = $this->load->view('wallet', $data, true);
        $this->load->view('includes/template_dashboard.view.php', $data);
    }

    public function request_refund()
    {
        $data     = array();
        $user_id  = $this->session->userdata('user_id');
        $userdata = getUserData($user_id);

        $totalCommissionOrders = $this->wallet_model->getTotalCommissionOrders($user_id);
        $totalCommission       = $this->wallet_model->getTotalCommission($user_id);
        $totalCommissionAdmin  = $this->wallet_model->getTotalCommissionAdmin($user_id);
        $totalCommission       = $totalCommission_bk    = $totalCommissionAdmin + $totalCommission;

        $totalUnconfirmedCommission = $this->wallet_model->getTotalUnconfirmedCommission($user_id);

        $data['withdrawRequestsList']        = $this->wallet_model->getWithdrawRequestsByStatus();
        $data['pendingwithdrawRequestsList'] = $this->wallet_model->getWithdrawRequestsByStatus(0);
        $data['successwithdrawRequestsList'] = $this->wallet_model->getWithdrawRequestsByStatus(1);
        $data['lastWithdrawAmount']          = $this->wallet_model->getLastSuccessWidthdraw();

        $pendingwithdrawRequestsSUM = $this->wallet_model->getWithdrawRequestsSUMByStatus(0);
        $successwithdrawRequestsSUM = $this->wallet_model->getWithdrawRequestsSUMByStatus(1);

        $totalPendingPayment = ((empty($pendingwithdrawRequestsSUM['total_amount']) || $pendingwithdrawRequestsSUM['total_amount'] > 0) ? $pendingwithdrawRequestsSUM['total_amount'] : 0);
        $totalsuccessPayment = ((empty($successwithdrawRequestsSUM['total_amount']) || $successwithdrawRequestsSUM['total_amount'] > 0) ? $successwithdrawRequestsSUM['total_amount'] : 0);

        $totalCommission = $totalCommission - $totalPendingPayment;
        $totalCommission = $totalCommission - $totalsuccessPayment;
        if ($totalCommission < 0)
        {
            $totalCommission = $totalsuccessPayment + $totalCommission;
        }

        $totalCommission    = number_format(($totalCommission), 2);
        $totalCommission_bk = number_format(($totalCommission_bk), 2);


        if ($totalCommission > 0)
        {
            if ($totalCommission == $totalCommission_bk && $totalPendingPayment <> $totalCommission_bk && $totalsuccessPayment >= $totalCommission)
            {
                $totalCommission = $totalCommission;
            }
            else if ($totalPendingPayment <> $totalCommission_bk && $totalCommission_bk <> $lastWithdrawAmount['lastWithdrawAmount'])
            {
                $totalCommission = $totalCommission_bk;
            }
            else
            {
                $totalCommission = number_format(0, 2);
            }
        }
        else
        {
            $totalCommission = number_format(0, 2);
        }
        if ($totalCommission < 0)
        {
            $totalCommission = number_format(0, 2);
        }
        $amount_withdraw = $totalCommission;
        
        $limit_withdraw = (double)unserialize(LIMIT_WITHDRAW)[$this->session->userdata('currency')];

        if ($amount_withdraw < (double) $limit_withdraw)
        {
            $this->session->set_flashdata('success_message', 'You commission withdraw request must be greater or equal to '.getSiteCurrencySymbol('',$this->session->userdata('currency')).' '. number_format($limit_withdraw,2));
            redirect('wallet'); // due to flash data.
        }
        $db                          = $this->wallet_model->saveWithdrawRequest($amount_withdraw, $totalCommissionOrders);
        /// TO ADVERTISER
        $email_tempData_2            = get_email_tempData(15);
        $email_tempData_2['content'] = str_replace("[ADVERTISER]", ucfirst($userdata['first_name']), $email_tempData_2['content']);
        $email_tempData_2['content'] = str_replace("[AMOUNT]", '<strong>' . getSiteCurrencySymbol('',$userdata['currency']).$amount_withdraw . '</strong>', $email_tempData_2['content']);
        if (!empty($email_tempData_2))
        {
            $data_2['receiver_name']   = 'no_hi';
            $data_2['title']           = $email_tempData_2['title'];
            $data_2['email_content']   = $email_tempData_2['content'];
            $data_2['welcome_content'] = $email_tempData_2['welcome_content'];
            $data_2['footer']          = $email_tempData_2['footer'];
            $subject                   = $data_2['title'];
            $email_content             = $this->load->view('includes/email_templates/email_template', $data_2, true);
            $this->emailutility->accountVarification($email_content, $userdata['email'], $subject);
            // $this->emailutility->accountVarification($email_content.'empty ord :'.$order_id.' --> user_id -> ('.$userid.'-'.$affid.') product -> '.$product_id.' commission -> '.$pro_commission,'mohsinlaeeque786@gmail.com', $subject);
        }
        //////////////
        if ($db)
        {
            $this->session->set_flashdata('success_message', 'You have successfully made your withdraw request.');
            redirect('wallet'); // due to flash data.
        }
        else
        {
            $this->session->set_flashdata('error_message', 'Opps! Error saving informtion. Please try again.');
            redirect('wallet'); // due to flash data.
        }
    }

}
