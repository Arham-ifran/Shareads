<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Commission extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // check if admin login
        $this->engineinit->_is_not_admin_logged_in_redirect('admin/login');
        get_payment_intergration();
        $this->load->model('commission_model');
        $this->load->model('reports_model');
        $this->load->helper('string');
        $this->load->library('emailutility');
    }

    public function index()
    {
        $data = array();


        if ($this->input->post())
        {
            if ($this->input->post('date_from') != '')
            {
                $data['date_from'] = strtotime($this->input->post('date_from'));
            }

            if ($this->input->post('date_to') != '')
            {
                $data['date_to'] = strtotime($this->input->post('date_to'));
            }
        }
        $all_orders = $this->commission_model->get_all_commission_orders($data);
        foreach ($all_orders as $key => $value)
        {
            $overall_price                                 = explode(',', $value['overall_price']);
            $overall_commission                            = explode(',', $value['overall_commission']);
            $all_advertiser_commission                     = explode(',', $value['all_advertiser_commission']);
            $pro_currencies                                = explode(',', $value['pro_currencies']);
            $all_orders[$key]['overall_price']             = 0;
            $all_orders[$key]['overall_commission']        = 0;
            $all_orders[$key]['all_advertiser_commission'] = 0;

            foreach ($pro_currencies as $pkey => $value)
            {
                $all_orders[$key]['overall_price']             = $all_orders[$key]['overall_price'] + get_currency_rate($overall_price[$pkey], $value, CURRENCY);
                $all_orders[$key]['overall_commission']        = $all_orders[$key]['overall_commission'] + get_currency_rate($overall_commission[$pkey], $value, CURRENCY);
                $all_orders[$key]['all_advertiser_commission'] = $all_orders[$key]['all_advertiser_commission'] + get_currency_rate($all_advertiser_commission[$pkey], $value, CURRENCY);
            }
        }
        $data['result_for_total_orders'] = $this->reports_model->get_listing_ads_report($data);
        $data['result_ADV'] = $this->reports_model->get_advertiser_commissions_report($data);

        $data['all_orders'] = $all_orders;
        $data ['content']   = $this->load->view('commission/listing', $data, true); //Return View as data
        $this->load->view('templete-view.php', $data);
    }

    function detail($month)
    {
        $data = array();

        $data['advertiser'] = $this->commission_model->get_all_commissionAdvertiser_orders($month);

        $data['publisher'] = $this->commission_model->get_all_commissionPublisher_orders($month);
        $data['month']     = $month;
        $data ['content']  = $this->load->view('commission/listing_detail', $data, true); //Return View as data
        $this->load->view('templete-view.php', $data);
    }

    function view($month)
    {
        $data = array();

        $data['advertiser'] = $this->commission_model->get_allPaidCommissionAdvertiser_orders($month);

        $data['publisher'] = $this->commission_model->get_allPaidCommissionPublisher_orders($month);
        $data['month']     = $month;
        $data ['content']  = $this->load->view('commission/listing_detail', $data, true); //Return View as data
        $this->load->view('templete-view.php', $data);
    }

    function pay_now($month)
    {
//        exec('nohup '. CRON_JOB_URL.' payment pay/'.$month.' > /dev/null &', $output);
        header('location:' . base_url('payment/pay/' . $month));
//        echo 'DONE'.$output;
//        print_r($output);
    }

    function success()
    {

        $data['type']     = 'Success';
        $data['msg']      = 'Payment transfered successfully.';
        $data ['content'] = $this->load->view('commission/message', $data, true); //Return View as data
        $this->load->view('templete-view.php', $data);
    }

    function cancel()
    {

        $data['type']     = 'Error';
        $data['msg']      = 'Payment tranfered canceled due to some error.';
        $data ['content'] = $this->load->view('commission/message', $data, true); //Return View as data
        $this->load->view('templete-view.php', $data);
    }

    function lead_generation()
    {

        $data['all_orders'] = $this->commission_model->get_allLeadGencommission_orders($data);

        $data ['content'] = $this->load->view('commission/lead_generation', $data, true); //Return View as data
        $this->load->view('templete-view.php', $data);
    }

    public function manage_withdraw()
    {
        $data                  = array();
        $data['all_withdraws'] = $this->commission_model->get_all_withdraws();
        $data ['content']      = $this->load->view('commission/withdraw_listing', $data, true); //Return View as data
        $this->load->view('templete-view.php', $data);
    }

    public function manage_invoices()
    {
        $data = array();
        if ($this->input->post())
        {
            if ($this->input->post('date_from') != '')
                $data['date_from'] = strtotime($this->input->post('date_from'));
            if ($this->input->post('date_to') != '')
                $data['date_to']   = strtotime($this->input->post('date_to'));
        }
        $data['all_invoices'] = $this->commission_model->get_all_invoices('', $data);
        if ($this->input->post('date_from') != '')
            $data['date_from']    = date('m/d/Y', strtotime($this->input->post('date_from')));
        if ($this->input->post('date_to') != '')
            $data['date_to']      = date('m/d/Y', strtotime($this->input->post('date_to')));
        $data ['content']     = $this->load->view('commission/invoices_listing', $data, true); //Return View as data
        $this->load->view('templete-view.php', $data);
    }

//    public function view_invoice($invoice_id = '')
//    {
//        $data['invoice_id']      = $invoice_id              = $this->common->decode($invoice_id);
//        $data['invoice_details'] = $invoice                 = $this->commission_model->getInvoice($invoice_id);
//        $invoice_detial          = $this->commission_model->getPreviousOrders($invoice['publisher_id'], $invoice['payment_schedule']);
//        $data['list_result']     = $invoice_detial->result_array();
//        $data['user_invoice_details'] = $user_invoice_details         = $invoice_detial->row_array();
//        $publisher_id            = $invoice['publisher_id'];
//        $data['user_details']    = getValArray('*', 'c_users', array('user_id' => $publisher_id));
//
//        $data ['content'] = $this->load->view('commission/view_invoice', $data, true);
//        $this->load->view('templete-view.php', $data);
//    }

    public function view_invoice($invoice_id = '')
    {
        $data['invoice_id']           = $invoice_id                   = $this->common->decode($invoice_id);
        $data['invoice_details']      = $invoice                      = $this->commission_model->getInvoice($invoice_id);
        $invoice_detial               = $this->commission_model->getPreviousOrders($invoice['publisher_id'], $invoice['payment_schedule']);
        $data['list_result']          = $invoice_detial->result_array();
        $data['user_invoice_details'] = $user_invoice_details         = $invoice_detial->row_array();
        $publisher_id                 = $invoice['publisher_id'];
        $data['user_details']         = getValArray('*', 'c_users', array('user_id' => $publisher_id));
        $data ['content']             = $this->load->view('commission/view_invoice', $data, true);
        $this->load->view('templete-view.php', $data);
    }

    public function detail_withdraw($withdraw_id)
    {
        $data                  = array();
        $withdraw_id           = $this->common->decode($withdraw_id);
        $data['withdraw_data'] = $withdraw_data         = $this->commission_model->getWithdraw($withdraw_id);

        $data ['content'] = $this->load->view('commission/listing_detail_withdraw', $data, true);
        $this->load->view('templete-view.php', $data);
    }

    public function view_withdraw($withdraw_id)
    {
        $data        = array();
        $withdraw_id = $this->common->decode($withdraw_id);

        $data['withdraw_data'] = $withdraw_data         = $this->commission_model->getWithdraw($withdraw_id);

        $data ['content'] = $this->load->view('commission/listing_detail_withdraw', $data, true);
        $this->load->view('templete-view.php', $data);
    }

//    
    public function make_withdraw_wiretransfer($withdraw_id)
    {
        $withdraw_id             = $this->common->decode($withdraw_id);
        $withdraw_current_status = getVal('status', 'c_withdraw', 'id', $withdraw_id);
        $withdraw_array          = getValArray('*', 'c_withdraw', 'id', $withdraw_id);
        $affiliate_details       = getValArray('*', 'c_users', 'user_id', $withdraw_array['affiliate_id']);
        if ($withdraw_current_status > 0)
        {
            $this->session->set_flashdata('success_message', 'WireTransfer withdraw already done.');
            redirect('admin/commission/manage_withdraw');
        }
        $withdraw_status = 1;

        $db = $this->commission_model->UpdateWithdraw($withdraw_id, $withdraw_status);


        $email_tempData        = get_email_tempData(16);
        $data['receiver_name'] = $affiliate_details['full_name'];
        $data['name']          = $affiliate_details['full_name'];
        $data['from_email']    = ADMIN_EMAIL;
        $data['subject']       = SITE_NAME . ' Withdraw';
        $data['to_email']      = $affiliate_details['email'];
        $email_content         = "You withdraw request is in process, you will soon recieve withdrawal from  <strong>" . SITE_NAME . "</strong> of " . getSiteCurrencySymbol('', $withdraw_array['currency']) . ' ' . number_format($withdraw_array['amount_requested'], 2) . " on your provided wire transfer details .<br/>";
        $email_content .= '<strong>Wire Transfer Details</strong><br/>';
        $email_content .= '<strong>Bank name</strong> : ' . $affiliate_details['bank_name'] . ' <br/>';
        $email_content .= '<strong>Account holder name</strong> : ' . $affiliate_details['account_holder_name'] . ' <br/>';
        $email_content .= '<strong>Account number</strong> : ' . $affiliate_details['account_number'] . ' <br/>';
        $email_content .= '<strong>Iban code</strong> : ' . $affiliate_details['iban_code'] . ' <br/>';
        $email_content .= '<strong>Swift code</strong> : ' . $affiliate_details['swift_code'] . ' <br/>';
        $email_content .= '<strong>SORT code</strong> : ' . $affiliate_details['sort_code'] . ' <br/>';
        $email_content .= '<strong>Bank address</strong> : ' . $affiliate_details['bank_address'] . ' <br/><br/>';
        
        $email_tempData['content'] = str_replace("[DETAILS_WITHDRAW]", $email_content, $email_tempData['content']);
        
        if (!empty($email_tempData))
        {
            $data['title']   = $email_tempData['title'];
            $data['content'] = $email_tempData['content'];
            $data['footer']  = $email_tempData['footer'];
            $subject         = $data['subject'];
            $email_content   = $this->load->view('includes/email_templates/email_template', $data, true);
            $this->emailutility->send_email_user($email_content, $data['to_email'], $subject);
        }


        if ($db)
        {
            $this->session->set_flashdata('success_message', 'WireTransfer withdraw made successfully');
            redirect('admin/commission/manage_withdraw');
        }
        else
        {
            $this->session->set_flashdata('success_message', 'Unable to make WireTransfer withdraw');
            redirect('admin/commission/manage_withdraw');
        }
    }

    function withdraw_paypal($withdraw_id)
    {
        $withdraw_id             = $this->common->decode($withdraw_id);
        $withdraw_current_status = getVal('status', 'c_withdraw', 'id', $withdraw_id);
        if ($withdraw_current_status == 0)
        {
            $withdraw_data            = getValArray('*', 'c_withdraw', 'id', $withdraw_id);
            $withdraw_currency_symbol = getVal('currency_name', 'c_currencies', 'currency_id', $withdraw_data['currency']);
            $withdraw_currency_symbol = strtoupper($withdraw_currency_symbol);
            $affilator_data           = getValArray('*', 'c_users', 'user_id', $withdraw_data['affiliate_id']);

            $payment_credentials = get_payment_intergration();
            
            $config              = array(
                'Sandbox' => INTEGRATION_TYPE == 1 ? 'true' : 'false', // Sandbox / testing mode option.
                'APIUsername' => API_USERNAME, // PayPal API username of the API caller
                'APIPassword' => API_PASSWORD, // PayPal API password of the API caller
                'APISignature' => API_SIGNATURE, // PayPal API signature of the API caller
                'APISubject' => '', // PayPal API subject (email address of 3rd party user that has granted API permission for your app)
                'APIVersion' => '123.0', // API version you'd like to use for your call.  You can set a default version in the class and leave this blank if you want.
                'DeviceID' => '',
                'ApplicationID' => APPLICATION_ID,
                'DeveloperEmailAccount' => PAYPAL_ID
            );
           
            $this->load->library('paypal/Paypal_adaptive', $config);
            $ClientDetailsFields = array(
                'CustomerID' => '', // Your ID for the sender  127 char max.
                'CustomerType' => '', // Your ID of the type of customer.  127 char max.
                'GeoLocation' => '', // Sender's geographic location
                'Model' => '', // A sub-identification of the application.  127 char max.
                'PartnerName' => ''         // Your organization's name or ID
            );
            $FundingTypes        = array('ECHECK', 'BALANCE', 'CREDITCARD');
            $Receivers           = array();


            ///Amount rounded
            $amount                            = $withdraw_data['amount_requested'];
            $exp_amount                        = explode('.', $amount);
            $withdraw_data['amount_requested'] = $exp_amount[0] . '.' . substr($exp_amount[1], 0, 2);
            $withdraw_data['amount_requested'] = (float) $withdraw_data['amount_requested'];
            ///
            ////////////////////////////////////////////////////////////
            $withdrawId                        = base64_encode($withdraw_data['id']);
            $Receiver                          = array(
                'Amount' => $withdraw_data['amount_requested'], // Required.  Amount to be paid to the receiver.
                'Email' => $affilator_data['paypal_email'], // Receiver's email address. 127 char max.
                'InvoiceID' => random_string('alnum', 8) . '-#' . $withdrawId, // The invoice number for the payment.  127 char max.
                'PaymentType' => 'GOODS', // Transaction type.  Values are:  GOODS, SERVICE, PERSONAL, CASHADVANCE, DIGITALGOODS
                'PaymentSubType' => '', // The transaction subtype for the payment.
                'Phone' => array('CountryCode' => '', 'PhoneNumber' => '', 'Extension' => ''), // Receiver's phone number.   Numbers only.
                'Primary' => '', // Whether this receiver is the primary receiver.  Values are:  TRUE, FALSE
            );
            array_push($Receivers, $Receiver);
            $insertId                          = $this->commission_model->savePaidOrdersList($withdraw_data['orders_ids']);
            ////////////////////////////////////////////////////////////
            $PayRequestFields                  = array(
                'ActionType' => 'PAY', // Required.  Whether the request pays the receiver or whether the request is set up to create a payment request, but not fulfill the payment until the ExecutePayment is called.  Values are:  PAY, CREATE, PAY_PRIMARY
                'CancelURL' => base_url('admin/commission/cancel_paypal'), // Required.  The URL to which the sender's browser is redirected if the sender cancels the approval for the payment after logging in to paypal.com.  1024 char max.
                'CurrencyCode' => $withdraw_currency_symbol, // Required.  3 character currency code.
                'FeesPayer' => 'EACHRECEIVER', // The payer of the fees.  Values are:  SENDER, PRIMARYRECEIVER, EACHRECEIVER, SECONDARYONLY
                'IPNNotificationURL' => base_url('payment/notify/' . $insertId), // The URL to which you want all IPN messages for this payment to be sent.  1024 char max.
                'Memo' => 'Transfer Advertiser Commission.', // A note associated with the payment (text, not HTML).  1000 char max
                'Pin' => '', // The sener's personal id number, which was specified when the sender signed up for the preapproval
                'PreapprovalKey' => '', // The key associated with a preapproval for this payment.  The preapproval is required if this is a preapproved payment.
                'ReturnURL' => base_url('admin/commission/success_paypal/' . $this->common->encode($withdraw_data['id'])), // Required.  The URL to which the sener's browser is redirected after approvaing a payment on paypal.com.  1024 char max.
                'ReverseAllParallelPaymentsOnError' => '', // Whether to reverse paralel payments if an error occurs with a payment.  Values are:  TRUE, FALSE
                'SenderEmail' => PAYPAL_ID, // Sender's email address.  127 char max.
                'TrackingID' => random_string('alnum', 16)       // Unique ID that you specify to track the payment.  127 char max.
            );
            //dd($PayRequestFields);
            $SenderIdentifierFields            = array(
                'UseCredentials' => ''      // If TRUE, use credentials to identify the sender.  Default is false.
            );
            $AccountIdentifierFields           = array(
                'Email' => '', // Sender's email address.  127 char max.
                'Phone' => array('CountryCode' => '', 'PhoneNumber' => '', 'Extension' => '')        // Sender's phone number.  Numbers only.
            );
            $PayPalRequestData                 = array(
                'PayRequestFields' => $PayRequestFields,
                'ClientDetailsFields' => $ClientDetailsFields,
                'FundingTypes' => $FundingTypes,
                'Receivers' => $Receivers,
                'SenderIdentifierFields' => $SenderIdentifierFields,
                'AccountIdentifierFields' => $AccountIdentifierFields
            );
            $PayPalResult                      = $this->paypal_adaptive->Pay($PayPalRequestData);
            if (!$this->paypal_adaptive->APICallSuccessful($PayPalResult['Ack']))
            {
                $data             = array('Errors' => $PayPalResult['Errors']);
                $data['type']     = 'error';
                $data['msg']      = $PayPalResult['Errors'][0]['Message'];
                $data ['content'] = $this->load->view('payment/payment', $data, true); //Return View as data
                 $this->load->view('templete-view.php', $data);
            }
            else
            {
                header('location:' . base_url('admin/commission/success_paypal/' . $this->common->encode($withdraw_data['id'])));
                exit;
            }
        }
        else
        {
            $this->session->set_flashdata('success_message', 'Paypal withdraw already done.');
            redirect('admin/commission/manage_withdraw');
        }
    }

    function success_paypal($withdraw_id)
    {
        $withdraw_id             = $this->common->decode($withdraw_id);
        $withdraw_current_status = getVal('status', 'c_withdraw', 'id', $withdraw_id);
        $withdraw_array          = getValArray('*', 'c_withdraw', 'id', $withdraw_id);
        $withdraw_affiliate_id   = getVal('affiliate_id', 'c_withdraw', 'id', $withdraw_id);
        $user_data               = getValArray('*', 'c_users', 'user_id', $withdraw_affiliate_id);
        if ($withdraw_current_status > 0)
        {
            $this->session->set_flashdata('success_message', 'WireTransfer withdraw already done.');
            redirect('admin/commission/manage_withdraw');
        }
        $withdraw_status = 1;
        $db              = $this->commission_model->UpdateWithdraw($withdraw_id, $withdraw_status);

        $email_tempData        = get_email_tempData(16);
        $data['receiver_name'] = $user_data['full_name'];
        $data['name']          = $user_data['full_name'];
        $data['from_email']    = ADMIN_EMAIL;
        $data['subject']       = SITE_NAME . ' Withdraw';
        $data['to_email']      = $user_data['email'];
        $email_content         = "You have successfully recieve a withdraw from <strong>" . SITE_NAME . "</strong> of " . getSiteCurrencySymbol('', $withdraw_array['currency']) . ' ' . number_format($withdraw_array['amount_requested'], 2) . " on your paypal <strong>" . $user_data['paypal_email'] . "</strong> .<br/><br/>";
        
        $email_tempData['content'] = str_replace("[DETAILS_WITHDRAW]", $email_content, $email_tempData['content']);
        
        if (!empty($email_tempData))
        {
            $data['title']   = $email_tempData['title'];
            $data['content'] = $email_tempData['content'];
            $data['footer']  = $email_tempData['footer'];
            $subject         = $data['subject'];
            $email_content   = $this->load->view('includes/email_templates/email_template', $data, true);
            $this->emailutility->send_email_user($email_content, $data['to_email'], $subject);
        }


        if ($db)
        {
            $this->session->set_flashdata('success_message', 'WireTransfer withdraw made successfully');
            redirect('admin/commission/manage_withdraw');
        }
        else
        {
            $this->session->set_flashdata('success_message', 'Unable to make WireTransfer withdraw');
            redirect('admin/commission/manage_withdraw');
        }
    }

    function cancel_paypal()
    {
        $this->session->set_flashdata('success_message', 'Unable to make Paypal withdraw');
        redirect('admin/commission/manage_withdraw');
    }

}

//End Class