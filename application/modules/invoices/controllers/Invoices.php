<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Invoices extends CI_Controller
{

    var $directory_name = 'shareads_invoices';

    public function __construct()
    {
        parent::__construct();
        // check if admin login
        $this->engineinit->_is_not_logged_in_redirect('login');
        $this->load->model('invoices_model');
        if ($this->session->userdata('account_type') == 1 || $this->session->userdata('is_admin') <> 0 || $this->session->userdata('user_id') == 1)
        {
            redirect(base_url('dashboard'));
        }
    }

// End __construct
    /**
      @Method: index
      @Return: View
     */
    public function index()
    {

        $data    = array();
        $user_id = $this->session->userdata('user_id');

        $data['results']    = $this->invoices_model->loadListings($data);
        $data['pagination'] = $this->pagination->create_links();
//dd($data['results']->result());
        $data ['content'] = $this->load->view('invoices', $data, true);
        $this->load->view('includes/template_dashboard.view.php', $data);
    }

    public function view($invoice_id)
    {

        $data['invoice_id']      = $invoice_id              = $this->common->decode($invoice_id);
        $data['invoice_details'] = $invoice                 = $this->invoices_model->getInvoice($invoice_id);
//        dd($data['invoice_details']);
        $invoice_detial          = $this->invoices_model->getPreviousOrdersDetails($invoice['publisher_id'], $invoice_id);

        $data['list_result']     = $invoice_detial->result_array();
        
        
        $data ['content'] = $this->load->view('invoice_view', $data, true);
        $this->load->view('includes/template_dashboard.view.php', $data);
    }

    public function cron()
    {
        $publishers_list = $this->invoices_model->getPublishers();
        $date            = new DateTime('now');
        foreach ($publishers_list as $key => $user)
        {
            // Payment Schedule check datetime according to settings
            if ($date->modify('first day of this month')->format('d') <> date('d'))
            {
                continue;
            }
            else if ($user['payment_schedule'] == 2 && floor($date->modify('last day of this month')->format('d') / 2) <> date('d'))
            {
                continue;
            }
            // Month Invoice Directory Create with the date
            if (!file_exists($this->directory_name . '/' . date('d-M-Y')))
            {
                mkdir($this->directory_name . '/' . date('d-M-Y'), 0777, true);
            }
            $list                         = $this->invoices_model->getPreviousOrders($user['publisher_id'], $user['payment_schedule']);
            $data['list_result']          = $list_result                  = $list->result_array();
            $data['user_invoice_details'] = $user_invoice_details         = $list->row_array();

            $data['user_details'] = $user;
            // If no record found against user record commision
            if (empty($list_result) || $this->invoices_model->getExistingInvoice($user['publisher_id'], $user_invoice_details['from_invoice_date'], $user_invoice_details['to_invoice_date']))
            {
                continue;
            }
            else
            {
                $total_commission_sum = 0;
                foreach ($list_result as $key => $value)
                {
                    $total_commission_sum += (double) $value['total_commision_sum'];
                }
                $data['invoice_number'] = $invoice_number         = $this->invoices_model->save_invoice($user, $list, $total_commission_sum);
                $this->load->library('parser');
                $output                 = $this->parser->parse('print_invoice', $data);
                $this->_gen_invoice_pdf($output, $this->directory_name . '/' . date('d-M-Y'), $invoice_number);
                unset($list_result);
                unset($data['list_result']);
            }
        }
        echo 'All Invoices Generated : Successfully';
        exit;
    }

    private function _gen_invoice_pdf($html, $path = '', $file_name = '')
    {
        $this->load->library('MPDF54/mpdf');
        $mpdf                = new mPDF('c');
        $mpdf->SetDisplayMode('fullpage', 'two');
        $mpdf->mirrorMargins = 1;
        $mpdf->setHeader();
        $mpdf->setHeader('');
        $mpdf->setFooter('');
        $mpdf->WriteHTML($html);
        if ($path <> '' && $file_name <> '')
        {
            $mpdf->Output($path . '/' . $file_name . '.pdf', 'F');
        }
        else
        {
            $mpdf->Output();
        }
    }

    public function proceed_payment($invoice_id)
    {
        $total_commission_sum = 0;
        $product_names        = '';
        $data['invoice_id']   = $invoice_id           = $this->common->decode($invoice_id);
        $data['user_details'] = $invoice              = $this->invoices_model->getInvoice($invoice_id);
        
        $user_currency = getVal('currency', 'c_users', 'user_id', $invoice['publisher_id']);
        
        if ($invoice['status'] == 1)
        {
            redirect('invoices');
        }
        $invoice_detial      = $this->invoices_model->getPreviousOrdersDetails($invoice['publisher_id'], $invoice['payment_schedule']);
        $data['list_result'] = $invoice_detial->result_array();
//        dd($data);
        foreach ($data['list_result'] as $key => $value)
        {
            if ($key <> 0)
            {
                $product_names .= ',';
            }
//            $total_commission_sum += (double) $value['total_commision_sum'];
            $total_commission_sum += (double) get_currency_rate($value['total_commision_sum'],$value['p_currency'],$invoice['invoice_currency']);
            $product_names .= $value['product_name'];
        }
//        dd($invoice);
        $returnURL           = base_url() . 'invoices/success'; //payment success url
        $cancelURL           = base_url() . 'invoices/view/' . $this->common->encode($invoice_id); //payment cancel url
        $notifyURL           = base_url() . 'invoices/success'; //ipn url
        $payment_credentials = get_payment_intergration();
        $paypal_data['osCsid']        = 'abc';
        $paypal_data['cmd']           = '_xclick';
        $paypal_data['business']      = $payment_credentials->paypal_id;
        $paypal_data['item_name']     = $product_names;
        $paypal_data['item_number']   = $data['user_details']['invoice_number'];
        $paypal_data['amount']        = $total_commission_sum;
        $paypal_data['no_shipping']   = 1;
        $paypal_data['return']        = $returnURL;
        $paypal_data['rm']            = '2';
        $paypal_data['cbt']           = 'Your order is NOT complete until you click here!';
        $paypal_data['currency_code'] = strtoupper(getSiteCurrencySymbol('currency_name',$invoice['invoice_currency']));
        $paypal_data['cancel_return'] = $cancelURL;
        $paypal_data['notify_url ']   = $notifyURL;
//dd($paypal_data);
        if ($payment_credentials->integration_type == 0)
        {
            header("Location: " . $payment_credentials->sandbox_url . "?" . http_build_query($paypal_data));
        }
        else
        {
            header("Location: " . $payment_credentials->live_url . "?" . http_build_query($paypal_data));
        }
        //////////////////////////////////////////
//        $payment_credentials = get_payment_intergration();
//     
//        $config['business']         = $payment_credentials->paypal_id;
//        $config['cpp_header_image'] = ''; //Image header url [750 pixels wide by 90 pixels high]
//        $config['return']           = base_url('invoices/success');
//        $config['cancel_return']    = base_url('invoices/view/' . $this->common->encode($invoice_id));
//        $config['notify_url']       = base_url('invoices/success'); //IPN Post
//        $config['production']       = INTEGRATION_TYPE; //Its false by default and will use sandbox
//        $config["invoice"]          = $data['user_details']['invoice_number'].time(); //The invoice id
//        $config["currency_code"]    = strtoupper(getSiteCurrencySymbol('currency_name'));
//
//        $config["custom"] = base64_encode(serialize(array('item_number' => $data['user_details']['invoice_number']))); //The custom array
//        
//        $this->load->library('paypal', $config);
//        $this->paypal->add($product_names,$total_commission_sum, 1);
//        $this->paypal->pay();
        //////////////////////////////////////////
    }

//    public function success()
//    {
//        $get = $_GET;
////        dd($get);
//        if (strtolower(trim($get['st'])) == 'completed')
//        {
//            $this->invoices_model->save_payment($get);
//            $this->session->set_flashdata('success_message', 'Invoice Payment Successfully');
//            redirect('invoices');
//        }
//        else
//        {
//            $this->session->set_flashdata('error_message', 'Invoice Payment Failed');
//            redirect('invoices');
//        }
//    }
    public function success()
    {
        $post = $_POST;
        if (strtolower($post['payment_status']) == 'completed' || strtolower($post['payment_status']) == 'pending')
        {
            $this->invoices_model->save_payment($post);
            $this->session->set_flashdata('success_message', 'Invoice Payment Successfully');
            redirect('invoices');
        }
        else
        {
            $this->session->set_flashdata('error_message', 'Invoice Payment Failed');
            redirect('invoices');
        }
    }

    public function cancel()
    {
        $this->session->set_flashdata('error_message', 'Invoice Payment Cancelled');
        redirect('invoices');
    }

}
