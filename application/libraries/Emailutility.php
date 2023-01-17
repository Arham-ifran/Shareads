<?php

if (!defined('APPPATH'))
    exit('No direct script access allowed');

/*
 * @Author: Saad Khan
 *
 */

final class Emailutility {

    function Emailutility() {
        $this->CI = & get_Instance();
        $this->_obj = & get_instance();
        $this->CI->load->library('phpmailer');
    }

    /*
      Send Mail
      Pra @ To Email address
      Pra @ sendor name
      Pra @ sendor email address
      Pra @ subject
      Pra @ html body
      Pra @ html text
     */

    function accountVarification($email_content, $email, $subject) {
        $body_html = $email_content;

        Emailutility::sendMail($email, SITE_NAME,ADMIN_EMAIL, $subject, $body_html);
    }

    function send_email_user($email_content, $email, $subject) {
        $body_html = $email_content;

        Emailutility::sendMail($email, SITE_NAME, ADMIN_EMAIL, $subject, $body_html);
    }

    function send_email_admin($email_content,$subject) {
        $body_html = $email_content;

        Emailutility::sendMail(ADMIN_EMAIL, SITE_NAME, ADMIN_EMAIL, $subject, $body_html);
    }

    function send_contact_inquiry($email_content, $subject) {
        $body_html = $email_content;

        Emailutility::sendMail(ADMIN_EMAIL,SITE_NAME, ADMIN_EMAIL, $subject, $body_html);
    }



    function leave_feedback_message($data, $email_content,$subject) {

        $body_html = $email_content;


        Emailutility::sendMail($data['to_email'], SITE_NAME, ADMIN_EMAIL, $subject, $body_html);
    }

    function sendMail($to_email, $sendor_name, $sendor_email, $subject, $body_html, $cc, $bcc,$attachments = '') {

        define("DOMAIN", 'shareads.co.uk');
        define("MAILGUN_API", 'key-21c064eb161d94011e18ba0a0447d1f0');
     $ch = curl_init();
//        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data',));

        if (count($attachments) > 0 && is_array($attachments)) {
            curl_setopt($ch, CURLOPT_VERBOSE, 0);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: multipart/form-data"));
        }

//        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, 'api:' . MAILGUN_API);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        $plain = strip_tags(nl2br($body_html));

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_URL, 'https://api.mailgun.net/v3/' . DOMAIN . '/messages');

        $data = array(
            'to' => $to_email,
            'from' => $sendor_name . '<' . $sendor_email . '>',
            'subject' => $subject,
            'html' => $body_html
                //'text' => $plain
        );
        if ($cc) {
            $data["cc"] = $cc;
        }
        if ($bcc) {
            $data["bcc"] = $bcc;
        }
        
        if (count($attachments) > 0 && is_array($attachments)) {
            $i = 1;
            foreach ($attachments as $attch) {
                $data["attachment[" . ($i) . "]"] = curl_file_create($attch['tmp_name'], 'application/pdf', $attch["file_name"]);
                $i++;
            }
        }
   
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $j = json_decode(curl_exec($ch));

        $info = curl_getinfo($ch);
//        echo '<pre>';
//        print_r($j);

        if ($info['http_code'] != 200) {
            $this->sendMail1($to_email, $sendor_name, $sendor_email, $subject, $body_html, $cc, $bcc, $attachments);
        }
//         error ("Fel 313: Vänligen meddela detta via E-post till support@".DOMAIN);

        curl_close($ch);

        return true;

        
//    require_once 'phpmailer/PHPMailerAutoload.php';
//    $mail = new PHPMailer;
//    $mail->isSMTP();
//    $mail->Host = 'smtp.gmail.com';
//    $mail->SMTPAuth = true;
//    $mail->Username = 'buzzfli.project@gmail.com';
//    $mail->Password = 'buzzfliproject';
//    $mail->SMTPSecure = 'tls';
//    $mail->Port = 587;
//    $mail->From = $sendor_email;
//    $mail->FromName = $sendor_name;
//    $mail->addAddress($to_email);
//    $mail->isHTML(true);
//    $mail->Subject = $subject;
//    $mail->Body = $body_html;
//    $mail->AltBody = $body_html;
//   
//
//    
//    if (!$mail->send()) {
//        echo 'Message could not be sent.';
//        echo 'Mailer Error: ' . $mail->ErrorInfo . '';
//        exit;
//    }
        
        
        
        
        
        
        
        
//        $headers .= 'MIME-Version: 1.0' . "\r\n";
//        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
//
//        if($cc <> '')
//        $headers .= "cc:  ". $cc ."\r\n";
//         if($bcc <> '')
//        $headers .= "bcc:  ". $bcc ."\r\n";
//
//        $headers .= "From: $sendor_name \r\n" .
//        "Reply-To: $sendor_email \r\n" .
//        "X-Mailer: PHP/" . phpversion();
//
//        mail($to_email, $subject, $body_html, $headers);
    }
function sendMail1($to_email, $sendor_name, $sendor_email, $subject, $body_html, $cc = '', $bcc = '', $attachments = '') {

        $separator = "==Multipart_Boundary_x".md5(time())."x";//md5(time());
        $eol = PHP_EOL;

        // main header (multipart mandatory)
        $headers = $body = "";
        $headers .= "From: " . $sendor_name . "<" . $sendor_email . ">\r\n";

        if ($cc <> '')
            $headers .= "Cc:  " . $cc . "\r\n";
        if ($bcc <> '')
            $headers .= "Bcc:  " . $bcc . "\r\n";

        $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

        $headers .= "MIME-Version: 1.0" . $eol;
        $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol . $eol;


        $body .= "Content-Transfer-Encoding: 7bit" . $eol;
        $body .= "This is a MIME encoded message." . $eol . $eol;

        // message
        $body .= "--" . $separator . $eol;
        $body .= "Content-Type: text/html; charset=\"iso-8859-1\"" . $eol;
        $body .= "Content-Transfer-Encoding: 8bit" . $eol . $eol;
        $body .= $body_html . $eol . $eol;

        // attachment
        if (count($attachments) > 0  && $attachments != '') {
            $u = 1;
            foreach ($attachments as $attch) {

                $body .= "--" . $separator . $eol;
                $body .= "Content-Type: application/pdf; name=\"" . $attch["file_name"] . "\"" . $eol;
                $body .= "Content-Transfer-Encoding: base64" . $eol;
                $body .= "Content-Disposition: attachment[".$u."]" . $eol . $eol;
                $body .= chunk_split(base64_encode(file_get_contents($attch["tmp_name"]))) . $eol . $eol;
                $body .= "--" . $separator . "". $eol . $eol;
                $u++;
            }
        }

        mail($to_email, $subject, $body, $headers);
    }

}

?>