<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Payment extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
//        if (!$this->input->is_cli_request())
//            show_error('Direct access is not allowed');
        get_payment_intergration();
        $this->load->model('payment_model');
        $this->load->helper('string');
        $this->load->library('emailutility');
    }

    function index()
    {
        if (!$this->input->is_cli_request())
            show_error('Direct access is not allowed');
        $month = date('m-Y');

        $date = $this->payment_model->getlastCronDate();

        if ($month == date('m-Y', $date['date']))
        {

            $advertisers = $this->payment_model->get_all_commissionAdvertiser_orders($month);
            $publishers  = $this->payment_model->get_all_commissionPublisher_orders($month);

            if (count($publishers) > 0 || count($advertisers) > 0)
            {

                $config = array(
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

                $FundingTypes = array('ECHECK', 'BALANCE', 'CREDITCARD');

                $Receivers = array();
                $array     = $array1    = array();
                foreach ($publishers as $pub)
                {
                    $amount   = $pub['overall_price'] - $pub['overall_commission'];
                    if ($pub['paypal_email'] == '')
                        continue;
                    $order_id = base64_encode($pub['order_id']);


                    ///Amount rounded
                    $amount                            = $amount;
                    $exp_amount                        = explode('.', $amount);
                    $amount = $exp_amount[0] . '.' . substr($exp_amount[1], 0, 2);
                    $amount = (float) $amount;


                    $Receiver = array(
                        'Amount' => $amount, // Required.  Amount to be paid to the receiver.
                        'Email' => $pub['paypal_email'], // Receiver's email address. 127 char max.
                        'InvoiceID' => random_string('alnum', 8) . '--' . $order_id, // The invoice number for the payment.  127 char max.
                        'PaymentType' => 'GOODS', // Transaction type.  Values are:  GOODS, SERVICE, PERSONAL, CASHADVANCE, DIGITALGOODS
                        'PaymentSubType' => '', // The transaction subtype for the payment.
                        'Phone' => array('CountryCode' => '', 'PhoneNumber' => '', 'Extension' => ''), // Receiver's phone number.   Numbers only.
                        'Primary' => ''            // Whether this receiver is the primary receiver.  Values are:  TRUE, FALSE
                    );
                    $array[]  = explode(',', $pub['order_id']);
                    array_push($Receivers, $Receiver);
                }
                // Advertisers
                foreach ($advertisers as $adv)
                {
                    $amount   = $adv['all_advertiser_commission'];
                    if ($adv['paypal_email'] == '')
                        continue;
                    $orderId  = base64_encode($adv['order_id']);
                    $Receiver = array(
                        'Amount' => $amount, // Required.  Amount to be paid to the receiver.
                        'Email' => $adv['paypal_email'], // Receiver's email address. 127 char max.
                        'InvoiceID' => random_string('alnum', 8) . '-#' . $orderId, // The invoice number for the payment.  127 char max.
                        'PaymentType' => 'GOODS', // Transaction type.  Values are:  GOODS, SERVICE, PERSONAL, CASHADVANCE, DIGITALGOODS
                        'PaymentSubType' => '', // The transaction subtype for the payment.
                        'Phone' => array('CountryCode' => '', 'PhoneNumber' => '', 'Extension' => ''), // Receiver's phone number.   Numbers only.
                        'Primary' => '', // Whether this receiver is the primary receiver.  Values are:  TRUE, FALSE
                    );
                    $array1[] = explode(',', $adv['order_id']);
                    array_push($Receivers, $Receiver);
                }

                $orders_publisher  = array_unique(call_user_func_array('array_merge', $array));
                $orders_advertiser = array_unique(call_user_func_array('array_merge', $array1));

                $insertId = $this->payment_model->savePaidOrdersList($orders_publisher, $orders_advertiser);

                // Prepare request arrays
                $PayRequestFields = array(
                    'ActionType' => 'PAY', // Required.  Whether the request pays the receiver or whether the request is set up to create a payment request, but not fulfill the payment until the ExecutePayment is called.  Values are:  PAY, CREATE, PAY_PRIMARY
                    'CancelURL' => base_url('admin/commission/success'), // Required.  The URL to which the sender's browser is redirected if the sender cancels the approval for the payment after logging in to paypal.com.  1024 char max.
                    'CurrencyCode' => strtoupper(getSiteCurrencySymbol('currency_name')), // Required.  3 character currency code.
                    'FeesPayer' => 'EACHRECEIVER', // The payer of the fees.  Values are:  SENDER, PRIMARYRECEIVER, EACHRECEIVER, SECONDARYONLY
                    'IPNNotificationURL' => base_url('payment/notify/' . $insertId), // The URL to which you want all IPN messages for this payment to be sent.  1024 char max.
                    'Memo' => 'Transfer Advertiser Commission and Publisher Payments.', // A note associated with the payment (text, not HTML).  1000 char max
                    'Pin' => '', // The sener's personal id number, which was specified when the sender signed up for the preapproval
                    'PreapprovalKey' => '', // The key associated with a preapproval for this payment.  The preapproval is required if this is a preapproved payment.
                    'ReturnURL' => base_url('admin/commission/success'), // Required.  The URL to which the sener's browser is redirected after approvaing a payment on paypal.com.  1024 char max.
                    'ReverseAllParallelPaymentsOnError' => '', // Whether to reverse paralel payments if an error occurs with a payment.  Values are:  TRUE, FALSE
                    'SenderEmail' => PAYPAL_ID, // Sender's email address.  127 char max.
                    'TrackingID' => random_string('alnum', 16)       // Unique ID that you specify to track the payment.  127 char max.
                );


                $SenderIdentifierFields = array(
                    'UseCredentials' => ''      // If TRUE, use credentials to identify the sender.  Default is false.
                );

                $AccountIdentifierFields = array(
                    'Email' => '', // Sender's email address.  127 char max.
                    'Phone' => array('CountryCode' => '', 'PhoneNumber' => '', 'Extension' => '')        // Sender's phone number.  Numbers only.
                );

                $PayPalRequestData = array(
                    'PayRequestFields' => $PayRequestFields,
                    'ClientDetailsFields' => $ClientDetailsFields,
                    'FundingTypes' => $FundingTypes,
                    'Receivers' => $Receivers,
                    'SenderIdentifierFields' => $SenderIdentifierFields,
                    'AccountIdentifierFields' => $AccountIdentifierFields
                );

                $PayPalResult = $this->paypal_adaptive->Pay($PayPalRequestData);

                if (!$this->paypal_adaptive->APICallSuccessful($PayPalResult['Ack']))
                {
                    $data         = array('Errors' => $PayPalResult['Errors']);
                    $data['type'] = 'error';
                    $data['msg']  = $data['Errors'][0]['Message'];
                }
                else
                {
                    // Successful call.  Load view or whatever you need to do here.
                    $this->payment_model->updateCronDate();
                }
            }
        }
    }

    function pay($month)
    {

        $advertisers = $this->payment_model->get_all_commissionAdvertiser_orders($month);
//        $publishers = $this->payment_model->get_all_commissionPublisher_orders($month);
        //count($publishers) > 0 || 
        if (count($advertisers) > 0)
        {

            $config = array(
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

            $FundingTypes = array('ECHECK', 'BALANCE', 'CREDITCARD');

            $Receivers = array();
            $array     = $array1    = array();
//            foreach ($publishers as $pub) {
//                $amount = $pub['overall_price'] - $pub['overall_commission'];
//                if ($pub['paypal_email'] == '')
//                    continue;
//                $order_id = base64_encode($pub['order_id']);
//                $Receiver = array(
//                    'Amount' => $amount, // Required.  Amount to be paid to the receiver.
//                    'Email' => $pub['paypal_email'], // Receiver's email address. 127 char max.
//                    'InvoiceID' => random_string('alnum', 8) . '--' . $order_id, // The invoice number for the payment.  127 char max.
//                    'PaymentType' => 'GOODS', // Transaction type.  Values are:  GOODS, SERVICE, PERSONAL, CASHADVANCE, DIGITALGOODS
//                    'PaymentSubType' => '', // The transaction subtype for the payment.
//                    'Phone' => array('CountryCode' => '', 'PhoneNumber' => '', 'Extension' => ''), // Receiver's phone number.   Numbers only.
//                    'Primary' => ''            // Whether this receiver is the primary receiver.  Values are:  TRUE, FALSE
//                );
//                $array[] = explode(',', $pub['order_id']);
//                array_push($Receivers, $Receiver);
//            }
            // Advertisers
            foreach ($advertisers as $adv)
            {
                $amount   = $adv['all_advertiser_commission'];
                if ($adv['paypal_email'] == '')
                    continue;
                $orderId  = base64_encode($adv['order_id']);
                $Receiver = array(
                    'Amount' => $amount, // Required.  Amount to be paid to the receiver.
                    'Email' => $adv['paypal_email'], // Receiver's email address. 127 char max.
                    'InvoiceID' => random_string('alnum', 8) . '-#' . $orderId, // The invoice number for the payment.  127 char max.
                    'PaymentType' => 'GOODS', // Transaction type.  Values are:  GOODS, SERVICE, PERSONAL, CASHADVANCE, DIGITALGOODS
                    'PaymentSubType' => '', // The transaction subtype for the payment.
                    'Phone' => array('CountryCode' => '', 'PhoneNumber' => '', 'Extension' => ''), // Receiver's phone number.   Numbers only.
                    'Primary' => '', // Whether this receiver is the primary receiver.  Values are:  TRUE, FALSE
                );
                $array1[] = explode(',', $adv['order_id']);
                array_push($Receivers, $Receiver);
            }

//            $orders_publisher = array_unique(call_user_func_array('array_merge', $array));
            $orders_advertiser = array_unique(call_user_func_array('array_merge', $array1));

            $insertId = $this->payment_model->savePaidOrdersList($orders_publisher, $orders_advertiser);

            // Prepare request arrays
            $PayRequestFields = array(
                'ActionType' => 'PAY', // Required.  Whether the request pays the receiver or whether the request is set up to create a payment request, but not fulfill the payment until the ExecutePayment is called.  Values are:  PAY, CREATE, PAY_PRIMARY
                'CancelURL' => base_url('admin/commission/success'), // Required.  The URL to which the sender's browser is redirected if the sender cancels the approval for the payment after logging in to paypal.com.  1024 char max.
                'CurrencyCode' => strtoupper(getSiteCurrencySymbol('currency_name')), // Required.  3 character currency code.
                'FeesPayer' => 'EACHRECEIVER', // The payer of the fees.  Values are:  SENDER, PRIMARYRECEIVER, EACHRECEIVER, SECONDARYONLY
                'IPNNotificationURL' => base_url('payment/notify/' . $insertId), // The URL to which you want all IPN messages for this payment to be sent.  1024 char max.
                'Memo' => 'Transfer Advertiser Commission and Publisher Payments.', // A note associated with the payment (text, not HTML).  1000 char max
                'Pin' => '', // The sener's personal id number, which was specified when the sender signed up for the preapproval
                'PreapprovalKey' => '', // The key associated with a preapproval for this payment.  The preapproval is required if this is a preapproved payment.
                'ReturnURL' => base_url('admin/commission/success'), // Required.  The URL to which the sener's browser is redirected after approvaing a payment on paypal.com.  1024 char max.
                'ReverseAllParallelPaymentsOnError' => '', // Whether to reverse paralel payments if an error occurs with a payment.  Values are:  TRUE, FALSE
                'SenderEmail' => PAYPAL_ID, // Sender's email address.  127 char max.
                'TrackingID' => random_string('alnum', 16)       // Unique ID that you specify to track the payment.  127 char max.
            );


            $SenderIdentifierFields = array(
                'UseCredentials' => ''      // If TRUE, use credentials to identify the sender.  Default is false.
            );

            $AccountIdentifierFields = array(
                'Email' => '', // Sender's email address.  127 char max.
                'Phone' => array('CountryCode' => '', 'PhoneNumber' => '', 'Extension' => '')        // Sender's phone number.  Numbers only.
            );

            $PayPalRequestData = array(
                'PayRequestFields' => $PayRequestFields,
                'ClientDetailsFields' => $ClientDetailsFields,
                'FundingTypes' => $FundingTypes,
                'Receivers' => $Receivers,
                'SenderIdentifierFields' => $SenderIdentifierFields,
                'AccountIdentifierFields' => $AccountIdentifierFields
            );

            $PayPalResult = $this->paypal_adaptive->Pay($PayPalRequestData);
//            echo '<pre>';
//            print_r($PayPalResult);exit;
            if (!$this->paypal_adaptive->APICallSuccessful($PayPalResult['Ack']))
            {
                $data             = array('Errors' => $PayPalResult['Errors']);
                $data['type']     = 'error';
                $data['msg']      = $PayPalResult['Errors'][0]['Message'];
                $data ['content'] = $this->load->view('payment', $data, true); //Return View as data
                $this->load->view('includes/template_dashboard.view.php', $data);
            }
            else
            {

                header('location:' . base_url('admin/commission/success'));
                exit;

                // Successful call.  Load view or whatever you need to do here.
//                header('location:' . $PayPalResult['RedirectURL']);
            }
        }
        else
        {
            $data['type']     = 'success';
            $data['msg']      = 'No new Order found. Already payment tranfered.';
            $data ['content'] = $this->load->view('payment', $data, true); //Return View as data
            $this->load->view('includes/template_dashboard.view.php', $data);
        }
    }

    function success()
    {

        $data['type']     = 'Success';
        $data['msg']      = 'Payment transfered successfully.';
        $data ['content'] = $this->load->view('payment', $data, true); //Return View as data
        $this->load->view('includes/template_dashboard.view.php', $data);
    }

    function cancel()
    {

        $data['type']     = 'Error';
        $data['msg']      = 'Payment Canceled.';
        $data ['content'] = $this->load->view('payment', $data, true); //Return View as data
        $this->load->view('includes/template_dashboard.view.php', $data);
    }

    function notify($itemId)
    {

        //@mail("saeed@arhamsoft.com", "PAYPAL notify shareads", "Done<br />data = <pre>" . print_r($_POST, true) . "</pre>");
        if ($itemId)
        {
//            $publishers = array_filter(explode(',', getVal('publishers', 'c_paid_payments', 'id', $itemId)));
            $advertisers = array_filter(explode(',', getVal('advertisers', 'c_paid_payments', 'id', $itemId)));
            if ($_POST['status'] == 'COMPLETED')
            {
                // Publishers
//                if (is_array($publishers) && !empty($publishers)) {
//                    foreach ($publishers as $ordr) {
//
//                        $this->payment_model->update_publisher_orders($ordr);
//                        $order_data = getValArray('*', 'c_orders', 'id', $ordr);
//                        $commission = getVal('commission', 'c_products_commission', 'product_id', $order_data['product_id']);
//                        $product_name = getVal('product_name', 'c_products', 'product_id', $order_data['product_id']);
//                        $user_data2 = get_user_data('full_name,email,paypal_email', 'user_id', $order_data['seller_id']);
//
//
//                        ///////Send Email to Order creater///////////////
//                        /*                         * ** Send Order Email Start ***** */
//
//                        $data['receiver_name'] = $user_data2['full_name'];
//                        $data['email_content'] = 'Your Product payment has successfully tranfered to your given paypal account. Please see the details below.<br /><br />
//                                        <strong>paypal Email :</strong>&nbsp; ' . $user_data2['paypal_email'] . '<br /><br />
//					<strong>Order NO :</strong>&nbsp; ' . $order_data['id'] . '<br /><br />
//                                        <strong>Total Amount :</strong>&nbsp;<br /><br />
//                                        <strong>Commission Amount :</strong>&nbsp;<br /><br />
//					<strong>Payment Status :</strong>&nbsp; Paid<br /><br />
//                                        <strong>Product Link :</strong>&nbsp;<a href="' . $order_data['url'] . '">' . $product_name . '</a>
//					';
//
//                        $email_tempData = get_email_tempData(7);
//
//                        if (!empty($email_tempData)) {
//                            $data['title'] = $email_tempData['title'];
//                            $data['content'] = $email_tempData['content'];
//
//                            $data['welcome_content'] = $email_tempData['welcome_content'];
//                            $data['footer'] = $email_tempData['footer'];
//                        } else {
//                            $data['title'] = 'Your products payment received from ' . SITE_NAME;
//                            $data['welcome_content'] = '';
//                            $data['content'] = $this->load->view('includes/email_templates/email_content.php', $data, true);
//                            $data['footer'] = $this->load->view('includes/email_templates/email_footer.php', $data, true);
//                        }
//                        $subject = $data['title'];
//                        $email_content = $this->load->view('includes/email_templates/email_template', $data, true);
//                        $this->emailutility->send_email_user($email_content, $user_data2['email'], $subject);
//                        unset($data);
//                        /*                         * ** Send Order success Email End ***** */
//                    }
//                }
                // Advertisers
                if (is_array($advertisers) && !empty($advertisers))
                {
                    foreach ($advertisers as $ordr)
                    {

                        $this->payment_model->update_advertiser_orders($ordr);
                        $order_data   = getValArray('*', 'c_orders', 'id', $ordr);
                        $commission   = getVal('advertiser_commission', 'c_user_commissions', 'order_id', $ordr);
                        $product_name = getVal('product_name', 'c_products', 'product_id', $order_data['product_id']);
                        $user_data2   = get_user_data('full_name,email,paypal_email', 'user_id', $order_data['advertiser_id']);

                        ///////Send Email to Order creater///////////////
                        /**** Send Order Email Start ******/

                        $data['receiver_name'] = $user_data2['full_name'];
                        $data['email_content'] = 'Your Product commission has successfully tranfered to your given paypal account. Please see the details below.<br /><br />
                                        <strong>paypal Email :</strong>&nbsp; ' . $user_data2['paypal_email'] . '<br /><br />
					<strong>Order NO :</strong>&nbsp; ' . $order_data['id'] . '<br /><br />
                                        <strong>Total Amount :</strong>&nbsp; <?php echo getSiteCurrencySymbol(); ?>' . number_format($order_data['price']) . '<br /><br />
                                        <strong>Commission Amount :</strong>&nbsp; <?php echo getSiteCurrencySymbol(); ?>' . number_format($commission) . '<br /><br />
					<strong>Payment Status :</strong>&nbsp; Paid<br /><br />
                                        <strong>Product Link :</strong>&nbsp;<a href="' . $order_data['url'] . '">' . $product_name . '</a>
					';

                        $email_tempData = get_email_tempData(7);

                        if (!empty($email_tempData))
                        {
                            $data['title']           = $email_tempData['title'];
                            $data['content']         = $email_tempData['content'];
                            $data['welcome_content'] = $email_tempData['welcome_content'];
                            $data['footer']          = $email_tempData['footer'];
                        }
                        else
                        {
                            $data['title']           = 'Your products commission received from ' . SITE_NAME;
                            $data['welcome_content'] = '';
                            $data['content']         = $this->load->view('includes/email_templates/email_content.php', $data, true);
                            $data['footer']          = $this->load->view('includes/email_templates/email_footer.php', $data, true);
                        }
                        $subject       = $data['title'];
                        $email_content = $this->load->view('includes/email_templates/email_template', $data, true);
                        $this->emailutility->send_email_user($email_content, $user_data2['email'], $subject);
                        unset($data);
                        /*                         * ** Send Order success Email End ***** */
                    }
                }
            }
        }
    }

// LEAD GENERATION
    function lead_generation($id)
    {

        $itemId = $this->common->decode($id);

        $advertisers = $this->payment_model->getLeadGenCommissionOrder($itemId);

        if (is_array($advertisers) && !empty($advertisers))
        {

            $config = array(
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

            $FundingTypes = array('ECHECK', 'BALANCE', 'CREDITCARD');

            $Receivers = array();

            // Advertisers
            $amount = $advertisers['commission'];
            if ($advertisers['paypal_email'] <> '')
            {
                $orderId  = base64_encode($advertisers['id']);
                $Receiver = array(
                    'Amount' => $amount, // Required.  Amount to be paid to the receiver.
                    'Email' => $advertisers['paypal_email'], // Receiver's email address. 127 char max.
                    'InvoiceID' => random_string('alnum', 8) . '-#' . $orderId, // The invoice number for the payment.  127 char max.
                    'PaymentType' => 'GOODS', // Transaction type.  Values are:  GOODS, SERVICE, PERSONAL, CASHADVANCE, DIGITALGOODS
                    'PaymentSubType' => '', // The transaction subtype for the payment.
                    'Phone' => array('CountryCode' => '', 'PhoneNumber' => '', 'Extension' => ''), // Receiver's phone number.   Numbers only.
                    'Primary' => '', // Whether this receiver is the primary receiver.  Values are:  TRUE, FALSE
                );
            }
            array_push($Receivers, $Receiver);

            // Prepare request arrays
            $PayRequestFields = array(
                'ActionType' => 'PAY', // Required.  Whether the request pays the receiver or whether the request is set up to create a payment request, but not fulfill the payment until the ExecutePayment is called.  Values are:  PAY, CREATE, PAY_PRIMARY
                'CancelURL' => base_url('admin/lead_generation/success'), // Required.  The URL to which the sender's browser is redirected if the sender cancels the approval for the payment after logging in to paypal.com.  1024 char max.
                'CurrencyCode' => strtoupper(getSiteCurrencySymbol('currency_name')), // Required.  3 character currency code.
                'FeesPayer' => 'EACHRECEIVER', // The payer of the fees.  Values are:  SENDER, PRIMARYRECEIVER, EACHRECEIVER, SECONDARYONLY
                'IPNNotificationURL' => base_url('payment/notify_lead/' . $advertisers['id']), // The URL to which you want all IPN messages for this payment to be sent.  1024 char max.
                'Memo' => 'Transfer Advertiser Lead Generation Commission.', // A note associated with the payment (text, not HTML).  1000 char max
                'Pin' => '', // The sener's personal id number, which was specified when the sender signed up for the preapproval
                'PreapprovalKey' => '', // The key associated with a preapproval for this payment.  The preapproval is required if this is a preapproved payment.
                'ReturnURL' => base_url('admin/lead_generation/success'), // Required.  The URL to which the sener's browser is redirected after approvaing a payment on paypal.com.  1024 char max.
                'ReverseAllParallelPaymentsOnError' => '', // Whether to reverse paralel payments if an error occurs with a payment.  Values are:  TRUE, FALSE
                'SenderEmail' => PAYPAL_ID, // Sender's email address.  127 char max.
                'TrackingID' => random_string('alnum', 16)       // Unique ID that you specify to track the payment.  127 char max.
            );


            $SenderIdentifierFields = array(
                'UseCredentials' => ''      // If TRUE, use credentials to identify the sender.  Default is false.
            );

            $AccountIdentifierFields = array(
                'Email' => '', // Sender's email address.  127 char max.
                'Phone' => array('CountryCode' => '', 'PhoneNumber' => '', 'Extension' => '')        // Sender's phone number.  Numbers only.
            );

            $PayPalRequestData = array(
                'PayRequestFields' => $PayRequestFields,
                'ClientDetailsFields' => $ClientDetailsFields,
                'FundingTypes' => $FundingTypes,
                'Receivers' => $Receivers,
                'SenderIdentifierFields' => $SenderIdentifierFields,
                'AccountIdentifierFields' => $AccountIdentifierFields
            );

            $PayPalResult = $this->paypal_adaptive->Pay($PayPalRequestData);
//            echo '<pre>';
//            print_r($PayPalResult);exit;
            if (!$this->paypal_adaptive->APICallSuccessful($PayPalResult['Ack']))
            {
                $data             = array('Errors' => $PayPalResult['Errors']);
                $data['type']     = 'error';
                $data['msg']      = $PayPalResult['Errors'][0]['Message'];
                $data ['content'] = $this->load->view('payment', $data, true); //Return View as data
                $this->load->view('includes/template_dashboard.view.php', $data);
            }
            else
            {

                header('location:' . base_url('admin/lead_generation/success'));
                exit;

                // Successful call.  Load view or whatever you need to do here.
//                header('location:' . $PayPalResult['RedirectURL']);
            }
        }
        else
        {
            $data['type']     = 'success';
            $data['msg']      = 'No new Order found. Already payment tranfered.';
            $data ['content'] = $this->load->view('payment', $data, true); //Return View as data
            $this->load->view('includes/template_dashboard.view.php', $data);
        }
    }

    function notify_lead($itemId)
    {
//    @mail ( "saeed@arhamsoft.com", "PAYPAL notify", "Done----".$itemId."<br />data = <pre>" . print_r ( $_POST, true ) . "</pre>" );
        if ($itemId)
        {

            if ($_POST['status'] == 'COMPLETED')
            {
                // Advertisers

                $this->payment_model->updateLeadGen_orders($itemId, $_POST['pay_key']);
                $order_data   = getValArray('*', 'c_lead_generation', 'id', $itemId);
                $product_name = getVal('product_name', 'c_products', 'product_id', $order_data['product_id']);
                $user_data2   = get_user_data('full_name,email,paypal_email', 'user_id', $order_data['advertiser_id']);


                ///////Send Email to Order creater///////////////
                /*                 * ** Send Order Email Start ***** */

                $data['receiver_name'] = $user_data2['full_name'];
                $data['email_content'] = 'Your Product commission has successfully tranfered to your given paypal account. Please see the details below.<br /><br />
                                        <strong>Paypal Email :</strong>&nbsp; ' . $user_data2['paypal_email'] . '<br /><br />
					<strong>Order NO :</strong>&nbsp; ' . $order_data['id'] . '<br /><br />

                                        <strong>Commission Amount :</strong>&nbsp; <?php echo getSiteCurrencySymbol(); ?>' . number_format($order_data['commission']) . '<br /><br />
					<strong>Payment Status :</strong>&nbsp; Paid<br /><br />
                                        <strong>Product Link :</strong>&nbsp;<a href="' . $order_data['url'] . '">' . $product_name . '</a>
					';

                $email_tempData = get_email_tempData(7);

                if (!empty($email_tempData))
                {
                    $data['title']   = $email_tempData['title'];
                    $data['content'] = $email_tempData['content'];

                    $data['welcome_content'] = $email_tempData['welcome_content'];
                    $data['footer']          = $email_tempData['footer'];
                }
                else
                {
                    $data['title']           = 'Your products commission received from ' . SITE_NAME;
                    $data['welcome_content'] = '';
                    $data['content']         = $this->load->view('includes/email_templates/email_content.php', $data, true);
                    $data['footer']          = $this->load->view('includes/email_templates/email_footer.php', $data, true);
                }
                $subject       = $data['title'];
                $email_content = $this->load->view('includes/email_templates/email_template', $data, true);
                $this->emailutility->send_email_user($email_content, $user_data2['email'], $subject);
                unset($data);
                /*                 * ** Send Order success Email End ***** */
            }
        }
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */