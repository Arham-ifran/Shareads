<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cron extends CI_Controller
{

    var $directory_name = 'shareads_invoices';

    public function __construct()
    {
        parent::__construct();
        // check if admin login
        $this->load->model('invoices/invoices_model');
        $this->load->library('emailutility');
    }

// End __construct
    /**
      @Method: index
      @Return: View
     */
    public function index()
    {
        $biweekly_date   = 15;
        $publishers_list = $this->invoices_model->getPublishers();
        $date            = new DateTime('now');
        foreach ($publishers_list as $key => $user)
        {
            // Payment Schedule check datetime according to settings
            if (((int) trim(date('d'), "0") == 1 || (int) trim(date('d'), "0") == (int) $biweekly_date))
            {

                if ((int) trim(date('d'), "0") == (int) $biweekly_date && $user['payment_schedule'] <> 1)
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
                    $output                 = $this->parser->parse('invoices/print_invoice', $data);
                    $this->_gen_invoice_pdf($output, $this->directory_name . '/' . date('d-M-Y'), $invoice_number);
                    unset($list_result);
                    unset($data['list_result']);

                    //mail
                    $date                  = date('Y-m-d');
                    $full_name             = ucfirst($user['first_name'] . ' ' . $user['last_name']);
                    $subject               = 'New Order was created by ' . $full_name . ' at ' . $date;
                    $admin_email_content   = $subject;
                    $data['receiver_name'] = $full_name;

                    $email_tempData            = get_email_tempData(14);
                    $email_tempData['content'] = str_replace("[INVOICE_MONTH]", date('F Y'), $email_tempData['content']);
                    $email_tempData['content'] = str_replace("[DUE_DATE]", date('d M, Y', strtotime('+7 day')), $email_tempData['content']);
                    $email_tempData['content'] = str_replace("[PUBLISHER_NAME]", $full_name, $email_tempData['content']);
                    $email_tempData['content'] = str_replace("[LOGIN]", '<a href="' . base_url('login') . '">login</a>', $email_tempData['content']);
                    if (!empty($email_tempData))
                    {
                        $data['title']           = 'Invoice of month ' . date('F');
                        $data['content']         = '';
                        $data['welcome_content'] = $email_tempData['welcome_content'];
                        $data['email_content']   = $email_tempData['content'];
                        $data['footer']          = $email_tempData['footer'];
                        $subject                 = SITE_NAME . ' Invoice of month ' . date('F Y');
                        $email_content           = $this->load->view('includes/email_templates/email_template', $data, true);
                        $data_attachment[0]      = array('tmp_name' => base_url($this->directory_name . '/' . date('d-M-Y') . '/' . $invoice_number . '.pdf'), 'file_name' => $invoice_number . '.pdf');
                        $this->emailutility->sendMail($user['email'], SITE_NAME, ADMIN_EMAIL, $subject, $email_content, '', '', $data_attachment);
                    }
                    //mail
                }
            }
            else
            {
                continue;
            }
        }
        echo 'All Invoices Generated : Successfully';
        exit;
    }

    public function specific_invoice($id)
    {
        $biweekly_date = 15;
        if ($id == '')
        {
            echo 'no user id provided';
            die();
        }
        $publishers_list = $this->invoices_model->getPublisher($id);
        $date            = new DateTime('now');
        foreach ($publishers_list as $key => $user)
        {
//        if((int) trim(date('d'), "0") == $biweekly_date && $user['payment_schedule'] <> 1)
//                {
//                    continue;
//                }
            // Payment Schedule check datetime according to settings
            if (((int) trim(date('d'), "0") == 1 || (int) trim(date('d'), "0") == $biweekly_date) || true)
            {
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
                        $order_ids = array();
                        $order_ids = explode(',', $value['order_ids']);
                        foreach ($order_ids as $order_id)
                        {
                            $this->db->where('id', $order_id);
                            $this->db->update('orders', array('is_invoice_generated' => 1));
                        }
                    }
                    $data['invoice_number'] = $invoice_number         = $this->invoices_model->save_invoice($user, $list, $total_commission_sum);
                    $this->load->library('parser');
                    $output                 = $this->parser->parse('invoices/print_invoice', $data);
                    $this->_gen_invoice_pdf($output, $this->directory_name . '/' . date('d-M-Y'), $invoice_number);
                    unset($list_result);
                    unset($data['list_result']);

                    //mail
                    $date                  = date('Y-m-d');
                    $full_name             = ucfirst($user['first_name'] . ' ' . $user['last_name']);
                    $subject               = 'New Order was created by ' . $full_name . ' at ' . $date;
                    $admin_email_content   = $subject;
                    $data['receiver_name'] = $full_name;
//                    $data['email_content'] = "<p>Hi " . $full_name . ",<br>";
//                    $data['email_content'] .= "Your Invoice is generated for " . date('F Y') . ". Please pay the invoice before due date ".date('d M, Y', strtotime('+7 day'));


                    $email_tempData            = get_email_tempData(14);
                    $email_tempData['content'] = str_replace("[INVOICE_MONTH]", date('F Y'), $email_tempData['content']);
                    $email_tempData['content'] = str_replace("[DUE_DATE]", date('d M, Y', strtotime('+7 day')), $email_tempData['content']);
                    $email_tempData['content'] = str_replace("[PUBLISHER_NAME]", $full_name, $email_tempData['content']);
                    $email_tempData['content'] = str_replace("[LOGIN]", '<a href="' . base_url('login') . '">login</a>', $email_tempData['content']);

                    if (!empty($email_tempData))
                    {
                        $data['title']           = 'Invoice of month ' . date('F');
                        $data['content']         = '';
                        $data['welcome_content'] = $email_tempData['welcome_content'];
                        $data['email_content']   = $email_tempData['content'];
                        $data['footer']          = $email_tempData['footer'];
                        $subject                 = SITE_NAME . ' Invoice of month ' . date('F Y');
                        $email_content           = $this->load->view('includes/email_templates/email_template', $data, true);
                        $data_attachment[0]      = array('tmp_name' => base_url($this->directory_name . '/' . date('d-M-Y') . '/' . $invoice_number . '.pdf'), 'file_name' => $invoice_number . '.pdf');
                        $this->emailutility->sendMail($user['email'], SITE_NAME, ADMIN_EMAIL, $subject, $email_content, '', '', $data_attachment);
                    }
                    //mail
                }
            }
            else
            {
                continue;
            }
        }
        echo 'All Invoices Generated : Successfully';
        exit;
    }

    public function tesst()
    {
        $email_tempData = get_email_tempData(14);
        if (!empty($email_tempData))
        {
            $data['title']           = 'Invoice of month ' . date('F');
            $data['content']         = '';
            $data['welcome_content'] = $email_tempData['welcome_content'];
            $data['email_content']   = $email_tempData['content'];
            $data['footer']          = $email_tempData['footer'];
            $subject                 = SITE_NAME . ' Invoice of month ' . date('F Y');
            $email_content           = $this->load->view('includes/email_templates/email_template', $data, true);
            $data_attachment[0]      = array('tmp_name' => 'shareads_invoices/15-Oct-2018/INV-1539561603.pdf', 'file_name' => 'INV-1539561603.pdf');
            $this->emailutility->sendMail('arslan.akbar@arhamsoft.com', SITE_NAME, ADMIN_EMAIL, $subject, $email_content, '', '', $data_attachment);
        }
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

    public function update_currency_rate()
    {
        $result_currencies_db = $this->db->get('c_currencies')->result_array();
        foreach ($result_currencies_db as $key => $currency)
        {
            foreach ($result_currencies_db as $keys => $currencys)
            {
                if ($currency['currency_id'] == $currencys['currency_id'])
                {
                    continue;
                }
                $cur         = 'fsyms='.$currency['currency_name'] . '&tsyms=' . $currencys['currency_name'];
                $curkey         = $currency['currency_name'] . '_' . $currencys['currency_name'];
//                $cur         = $currency['currency_name'] . '_' . $currencys['currency_name'];
//                $response    = file_get_contents('https://free.currencyconverterapi.com/api/v5/convert?q=' . $cur);
                $response    = file_get_contents('https://min-api.cryptocompare.com/data/pricemulti?' . $cur);
                $result      = json_decode($response);
                
                $array[$curkey] = $result->$currency['currency_name']->$currencys['currency_name'];
            }
        }
//        dd($array);
        $insert_currency_rate = array();
        foreach ($array as $key => $value)
        {
            $exp_currency = explode('_', $key);
            $from         = $exp_currency[0];
            $to           = $exp_currency[1];
            array_push($insert_currency_rate, array('currency_from' => $from, 'currency_to' => $to, 'currency_value' => $value, 'created' => date("Y-m-d H:i:s")));
        }
        $this->db->truncate('c_currency_rate');
        $this->db->insert_batch('c_currency_rate', $insert_currency_rate);
        echo 'rate updated';
        exit;
    }

    public function unpaid_invoices_reminders($after_days = 1)
    {
        if ($after_days <> '' || $after_days > 0)
        {
            $invoices_list = $this->invoices_model->get_invoices_expired($after_days);
            foreach ($invoices_list as $invoice_obj_key => $invoice_obj)
            {
                $email_tempData = get_email_tempData(17);
                if ($after_days == 1 || $after_days == 2)
                {
                    $email_tempData['content'] = 'You’re invoice #' . $invoice_obj['invoice_number'] . ' payment is overdue.<br/> ';
                    $email_tempData['content'] .='Kindly go to invoices section and pay the invoice of month ' . date('F', $invoice_obj['from_datetime']);
                }
                else if ($after_days == 3 || $after_days == 4 || $after_days == 5)
                {
                    $email_tempData['content'] = 'You’re invoice #' . $invoice_obj['invoice_number'] . ' payment is overdue.<br/> ';
                    $email_tempData['content'] .='Kindly pay your invoice in next 3 days otherwise shareads will block your products. ';
                }
                else if ($after_days == 6)
                {
                    $email_tempData['content'] = 'You’re invoice #' . $invoice_obj['invoice_number'] . ' payment is overdue.<br/> ';
                    $email_tempData['content'] .='Due to invoice overdue , Your products listings is stopped for advertisers and your account will be deactivated.';
                    $this->db->where('user_id', $invoice_obj['publisher_id'])->update('products', array('status' => 0));
                }
                else if ($after_days > 6)
                {
                    $email_tempData['content'] = 'You’re invoice #' . $invoice_obj['invoice_number'] . ' payment is overdue.<br/> ';
                    $email_tempData['content'] .='Your account has been deactivated. To continue with ShareAds Please contact Our customer Support';
                    $this->db->where('user_id', $invoice_obj['publisher_id'])->update('products', array('status' => 0));
                    $this->db->where('user_id', $invoice_obj['publisher_id'])->update('users', array('status' => 0, 'is_active' => 0));
                }

                if (!empty($email_tempData))
                {
                    $data['title']           = 'Invoice Overdue';
                    $data['content']         = '';
                    $data['welcome_content'] = $email_tempData['welcome_content'];
                    $data['email_content']   = $email_tempData['content'];
                    $data['footer']          = $email_tempData['footer'];
                    $subject                 = SITE_NAME . ' Invoice is overdue of month ' . date('F Y', $invoice_obj['from_datetime']);
                    $email_content           = $this->load->view('includes/email_templates/email_template', $data, true);
                    $this->emailutility->sendMail($invoice_obj['email'], SITE_NAME, ADMIN_EMAIL, $subject, $email_content);
                }
            }
        }
    }

    public function scripts_exists_checks()
    {
        $results = $this->db->where(array('script_verified_by_admin' => 0))->get('c_products')->result_array();
 
        foreach ($results as $key => $value)
        {
            $product_id = $value['product_id'];
            $script_1   = is_script_exists($value['url'], 'shareads.min.js');
            
//            $script_2   = is_script_exists($value['sale_url'], 'shareads_catcher.min.js');
            $script_2   = true;
            if ($script_1 == true && $script_2 == true)
            {
                $this->db->where('product_id', $product_id)->update('c_products', array('script_verified' => 1));
            }
            else
            {
                $this->db->where('product_id', $product_id)->update('c_products', array('script_verified' => 0));
            }
        }
        echo 'Script Scrapped completed';
    }

}
