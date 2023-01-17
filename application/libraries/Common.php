<?php

if (!defined('APPPATH'))
    exit('No direct script access allowed');
require_once(APPPATH . 'libraries/Class.upload.php');

class Common {

    function Common() {
        $ci = $this->CI = & get_instance();
//        $this->CI->output->enable_profiler(TRUE);
    }

    function do_upload_FBpicture($face_data) {


        $file_name = $face_data['id'] . rand(1, 99999999999) . '.jpg';
        $path = 'uploads/users/';
        $img = file_get_contents('https://graph.facebook.com/' . $face_data['id'] . '/picture?type=large');
        $file = $path . 'pic/' . $file_name;
        file_put_contents($file, $img);

        $target_path = $path . 'pic/' . $file_name;

        $height2 = '200';
        $width2 = '200';
        $newfile2 = $path . 'medium/' . $file_name;
        Common::thumbnailProfile($target_path, $width2, $height2, $newfile2, $up_ext);

        $height3 = '100';
        $width3 = '100';
        $newfile3 = $path . 'small/' . $file_name;
        Common::thumbnailProfile($target_path, $width3, $height3, $newfile3, $up_ext);


        return $file_name;
    }

    function do_upload_TWpicture($photo, $id) {

        $photo1 = str_replace('_normal', '', $photo);

        $path1 = explode('/', trim($photo1));
        $filename = $path1[count($path1) - 1];

        $name = explode(".", $filename);
        $up_ext = strtolower($name [(count($name) - 1)]);

        $rand = rand(1, 99999999999);
        $photo_name = $name[0] . $id . '_' . $rand;
        $file_name = $photo_name . '.' . $up_ext;
        $path = 'uploads/users/';
        $img = file_get_contents($photo1);
        $file = $path . 'pic/' . $file_name;
        file_put_contents($file, $img);

        $target_path = $path . 'pic/' . $file_name;

        $height2 = '200';
        $width2 = '200';
        $newfile2 = $path . 'medium/' . $file_name;
        Common::thumbnailProfile($target_path, $width2, $height2, $newfile2, $up_ext);

        $height3 = '100';
        $width3 = '100';
        $newfile3 = $path . 'small/' . $file_name;
        Common::thumbnailProfile($target_path, $width3, $height3, $newfile3, $up_ext);


        return $file_name;
    }

    function formatCreditCard($cc) {
        // REMOVE EXTRA DATA IF ANY
        $cc = str_replace(array('-', ' '), '', $cc);
        // GET THE CREDIT CARD LENGTH
        $cc_length = strlen($cc);
        $newCreditCard = substr($cc, -4);
        for ($i = $cc_length - 5; $i >= 0; $i--) {
            // ADDS HYPHEN HERE
            if ((($i + 1) - $cc_length) % 4 == 0) {
                $newCreditCard = '-' . $newCreditCard;
            }
            $newCreditCard = $cc[$i] . $newCreditCard;
        }
        // REPLACE CHARACTERS WITH X EXCEPT FIRST FOUR AND LAST FOUR
        for ($i = 4; $i < $cc_length - 2; $i++) {
            if ($newCreditCard[$i] == '-') {
                continue;
            }
            $newCreditCard[$i] = 'X';
        }
        // RETURN THE FINAL FORMATED AND MASKED CREDIT CARD NO
        return $newCreditCard;
    }

    function uniqueKey($length = 8) {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $uniqueKey = substr(str_shuffle($chars), 0, $length);
        return $uniqueKey;
    }

    function randomPassword($length = 8) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $password = substr(str_shuffle($chars), 0, $length);
        return $password;
    }

    function do_upload_image($path, $allow_types = 'gif|jpg|png|jpeg', $max_height = '8000', $max_width = '8000', $tag_name, $file_name = "") {


        $config ['max_width'] = $max_width; //'8000';
        $config ['max_height'] = $max_height; //'8000';
        $name = explode(".", $file_name);
        $up_ext = strtolower($name [(count($name) - 1)]);
        $rand = rand(1, 99999999999);

        $string = str_replace(' ', '-', $name[0]); // Replaces all spaces with hyphens.
        $names = preg_replace('/[^A-Za-z0-9\-]/', '', $string);

        $file_name = $names . '_' . $rand . '.' . $up_ext;
        $target_path = $path . 'pic/' . $file_name;

        if (move_uploaded_file($tag_name, $target_path)) {
            $objUpload = new upload($target_path);
            $height1 = '90';
            $width1 = '235';
            $newfile1 = $path . 'small/';
            $resize = 'image_ratio';
            Common::doProcess($objUpload, $width1, $height1, $resize, $newfile1);
            return $file_name;
        } else {
            return false;
        }
    }

    function do_upload_favicon($path, $allow_types = 'gif|jpg|png|jpeg', $max_height = '8000', $max_width = '8000', $tag_name, $file_name = "") {


        $config ['max_width'] = $max_width; //'8000';
        $config ['max_height'] = $max_height; //'8000';
        $name = explode(".", $file_name);
        $up_ext = strtolower($name [(count($name) - 1)]);
        $rand = rand(1, 99999999999);

        $string = str_replace(' ', '-', $name[0]); // Replaces all spaces with hyphens.
        $names = preg_replace('/[^A-Za-z0-9\-]/', '', $string);

        $file_name = $names . '_' . $rand . '.' . $up_ext;
        $target_path = $path . 'pic/' . $file_name;

        if (move_uploaded_file($tag_name, $target_path)) {
            $objUpload = new upload($target_path);
            $height1 = '50';
            $width1 = '50';
            $newfile1 = $path . 'small/';
            $resize = 'image_ratio';
            Common::doProcess($objUpload, $width1, $height1, $resize, $newfile1);
            return $file_name;
        } else {
            return false;
        }
    }

    function do_upload_profile($path, $allow_types = 'gif|jpg|png|jpeg', $max_height = '8000', $max_width = '8000', $tag_name, $file_name = "") {


        $config ['max_width'] = $max_width; //'8000';
        $config ['max_height'] = $max_height; //'8000';
        $name = explode(".", $file_name);
        $up_ext = strtolower($name [(count($name) - 1)]);
        $rand = rand(1, 99999999999);

        $string = str_replace(' ', '-', $name[0]); // Replaces all spaces with hyphens.
        $names = preg_replace('/[^A-Za-z0-9\-]/', '', $string);

        $file_name = $names . '_' . $rand . '.' . $up_ext;
        $target_path = $path . 'pic/' . $file_name;

        if (move_uploaded_file($tag_name, $target_path)) {

            $height1 = '200';
            $width1 = '200';
            $newfile1 = $path . 'medium/' . $file_name;
            Common::thumbnailProfile($target_path, $width1, $height1, $newfile1, $up_ext);

            $height3 = '75';
            $width3 = '75';
            $newfile3 = $path . 'small/' . $file_name;
            Common::thumbnailProfile($target_path, $width3, $height3, $newfile3, $up_ext);


            return $file_name;
        } else {
            return false;
        }
    }

    function do_upload_ajax_profile($path, $allow_types = 'gif|jpg|png|jpeg', $max_height = '8000', $max_width = '8000', $tag_name, $file_name = "") {

        $source_path = $tag_name;
        $config ['max_width'] = $max_width; //'8000';
        $config ['max_height'] = $max_height; //'8000';
        $name = explode(".", $file_name);
        $up_ext = strtolower($name [(count($name) - 1)]);
        $rand = rand(1, 99999999999);

        $string = str_replace(' ', '-', $name[0]); // Replaces all spaces with hyphens.
        $names = preg_replace('/[^A-Za-z0-9\-]/', '', $string);

        $file_name = $names . '_' . $rand . '.' . $up_ext;
        $target_path = $path . 'pic/' . $file_name;

        //if (move_uploaded_file($tag_name, $target_path)) {
        if (copy($source_path, $target_path)){
            $height1 = '200';
        $width1 = '200';
        $newfile1 = $path . 'medium/' . $file_name;
        Common::thumbnailProfile($target_path, $width1, $height1, $newfile1, $up_ext);

        $height3 = '75';
        $width3 = '75';
        $newfile3 = $path . 'small/' . $file_name;
        Common::thumbnailProfile($target_path, $width3, $height3, $newfile3, $up_ext);


        return $file_name;
        } else {
            return false;
        }
    }

    function do_upload_logo($path, $allow_types = 'gif|jpg|png|jpeg', $max_height = '8000', $max_width = '8000', $tag_name, $file_name = "") {


        $config ['max_width'] = $max_width; //'8000';
        $config ['max_height'] = $max_height; //'8000';
        $name = explode(".", $file_name);
        $up_ext = strtolower($name [(count($name) - 1)]);
        $rand = rand(1, 99999999999);

        $string = str_replace(' ', '-', $name[0]); // Replaces all spaces with hyphens.
        $names = preg_replace('/[^A-Za-z0-9\-]/', '', $string);

        $file_name = $names . '_' . $rand . '.' . $up_ext;
        $target_path = $path . 'pic/' . $file_name;

        if (move_uploaded_file($tag_name, $target_path)) {
            $height3 = '150';
            $width3 = '90';
            $newfile3 = $path . 'small/' . $file_name;
            Common::thumbnailProfile($target_path, $width3, $height3, $newfile3, $up_ext);


            return $file_name;
        } else {
            return false;
        }
    }

    function do_upload_category($path, $allow_types = 'gif|jpg|png|jpeg', $max_height = '8000', $max_width = '8000', $tag_name, $file_name = "") {


        $config ['max_width'] = $max_width; //'8000';
        $config ['max_height'] = $max_height; //'8000';
        $name = explode(".", $file_name);
        $up_ext = strtolower($name [(count($name) - 1)]);
        $rand = rand(1, 99999999999);

        $string = str_replace(' ', '-', $name[0]); // Replaces all spaces with hyphens.
        $names = preg_replace('/[^A-Za-z0-9\-]/', '', $string);

        $file_name = $names . '_' . $rand . '.' . $up_ext;
        $target_path = $path . 'pic/' . $file_name;

        if (move_uploaded_file($tag_name, $target_path)) {
            $height1 = '100';
            $width1 = '100';
            $newfile1 = $path . 'small/' . $file_name;
            Common::thumbnail($target_path, $width1, $height1, $newfile1, $up_ext);
            return $file_name;
        } else {
            return false;
        }
    }

    function do_upload_banner($path, $allow_types = 'gif|jpg|png|jpeg', $max_height = '8000', $max_width = '8000', $tag_name, $file_name = "", $type) {
        ;
        $config ['max_width'] = $max_width; //'8000';
        $config ['max_height'] = $max_height; //'8000';
        $name = explode(".", $file_name);
        $up_ext = strtolower($name [(count($name) - 1)]);
        $rand = rand(1, 99999999999);

        $string = str_replace(' ', '-', $name[0]); // Replaces all spaces with hyphens.
        $names = preg_replace('/[^A-Za-z0-9\-]/', '', $string);

        $file_name = $names . '_' . $rand . '.' . $up_ext;
        $target_path = $path . 'pic/' . $file_name;
        if (move_uploaded_file($tag_name, $target_path)) {
            return $file_name;
        } else {
            return false;
        }
    }

    /* File UPLOAD* */

    function do_upload_image_product($path, $allow_types = 'gif|jpg|png|jpeg', $max_height = '8000', $max_width = '8000', $tag_name, $file_name = "") {

        $config ['max_width'] = $max_width; //'8000';
        $config ['max_height'] = $max_height; //'8000';
        $name = explode(".", $file_name);
        $up_ext = strtolower($name [(count($name) - 1)]);
        $rand = rand(1, 99999999999);

        $string = str_replace(' ', '-', $name[0]); // Replaces all spaces with hyphens.
        $names = preg_replace('/[^A-Za-z0-9\-]/', '', $string);

        $file_name = $names . '_' . $rand . '.' . $up_ext;
        $target_path = $path . 'pic/' . $file_name;

        //Create Object of upload class
        if (move_uploaded_file($tag_name, $target_path)) {
            $objUpload = new upload($target_path);
            $height1 = '600';
            $width1 = '800';
            $newfile1 = $path . 'large/';
            $resize = 'image_ratio';
            Common::doProcess($objUpload, $width1, $height1, $resize, $newfile1);

            $height2 = '300';
            $width2 = '400';
            $newfile2 = $path . 'medium/';
            $resize1 = 'image_ratio';
            Common::doProcess($objUpload, $width2, $height2, $resize1, $newfile2);

            $height3 = '150';
            $width3 = '150';
            $newfile3 = $path . 'small/';
            $resize2 = 'image_ratio';
            Common::doProcess($objUpload, $width3, $height3, $resize2, $newfile3);
            return $file_name;
        } else {
            return false;
        }
    }

    public static function doProcess($objUpload, $width, $height, $resize, $uploadDir) {
        $objUpload->file_overwrite = true;
        $objUpload->image_resize = true;
        $objUpload->image_x = $width;
        $objUpload->image_y = $height;
        switch ($resize) {
            case 'image_ratio_crop':
                $objUpload->image_ratio_crop = true;
                break;
            case 'image_ratio_fill':
                $objUpload->image_ratio_fill = true;
                break;
            case 'image_ratio_no_zoom_in':
                $objUpload->image_ratio_no_zoom_in = true;
                break;
            case 'image_ratio_no_zoom_out ':
                $objUpload->image_ratio_no_zoom_out = true;
                break;
            case 'image_ratio_x ':
                $objUpload->image_ratio_x = true;
                break;
            case 'image_ratio_y  ':
                $objUpload->image_ratio_y = true;
                break;
            default :
                $objUpload->image_ratio = true;
        }


        if (exif_imagetype($objUpload->file_src_pathname) == 2) {
            $exif = exif_read_data($objUpload->file_src_pathname);

            if (!empty($exif['Orientation'])) {
                switch ($exif['Orientation']) {
                    case 8:
                        $objUpload->image_rotate = 90;
                        break;
                    case 3:
                        $objUpload->image_rotate = 180;
                        break;
                    case 6:
                        $objUpload->image_rotate = 90;
                        $objUpload->image_x = $height;
                        $objUpload->image_y = $width;
                        break;
                }
            }
        }

        //die($uploadDir);
        $objUpload->process($uploadDir);
    }

    function getExtension($str) {

        $i = strrpos($str, ".");
        if (!$i) {
            return "";
        }
        $l = strlen($str) - $i;
        $ext = substr($str, $i + 1, $l);
        return $ext;
    }

    //You do not need to alter these functions
    function getHeight($image) {
        $sizes = getimagesize($image);
        $height = $sizes [1];
        return $height;
    }

    //You do not need to alter these functions
    function getWidth($image) {

        $sizes = getimagesize($image);
        $width = $sizes [0];
        return $width;
    }

    /** height width fix* */
    function thumbnail($target_path, $width, $height, $newfile, $up_ext) {

        switch ($up_ext) {
            case 'jpg' : case 'jpeg' :
                $src = imagecreatefromjpeg($target_path);
                break;
            case 'png' :
                $src = imagecreatefrompng($target_path);
                break;
            case 'gif' :
                $src = imagecreatefromgif($target_path);
                break;
        }
        $oldW = imagesx($src);
        $oldH = imagesy($src);
        if ($oldW > 400 || $oldH > 300) {
//        /* Calculate the New Image Dimensions */
//        if ($oldH > $oldW) {
//            /* Portrait */
//            $newW = $width;
//            $newH = floor($oldH * ( $width / $oldW ));
//        } else {
//            /* Landscape */
//            $newH = $width;
//            $newW = floor($oldW * ( $width / $oldH ));
//        }
            $newW = $width;
            $newH = $height;
            /* Create the New Image */
            $new = imagecreatetruecolor($newW, $newH);
            /* Transcribe the Source Image into the New (Square) Image */
            //imagecopyresized
            imagecopyresampled($new, $src, 0, 0, 0, 0, $newW, $newH, $oldW, $oldH);
            switch ($up_ext) {
                case 'jpg' : case 'jpeg' :
                    $src = imagejpeg($new, $newfile);
                    break;
                case 'png' :
                    $src = imagepng($new, $newfile);
                    break;
                case 'gif' :
                    $src = imagegif($new, $newfile);
                    break;
            }
        } else {
            copy($target_path, $newfile);
        }
    }

    function thumbnailProfile($target_path, $width, $height, $newfile, $up_ext) {

        switch ($up_ext) {
            case 'jpg' : case 'jpeg' :
                $src = imagecreatefromjpeg($target_path);
                break;
            case 'png' :
                $src = imagecreatefrompng($target_path);
                break;
            case 'gif' :
                $src = imagecreatefromgif($target_path);
                break;
        }
        $oldW = imagesx($src);

        $oldH = imagesy($src);

        if ($oldW > 200 && $oldH > 200) {
            /* Calculate the New Image Dimensions */
            if ($oldH > $oldW) {
                /* Portrait */
                $newW = $width;
                $newH = floor($oldH * ( $width / $oldW ));
            } else {
                /* Landscape */
                $newH = $width;
                $newW = floor($oldW * ( $width / $oldH ));
            }
            /* Create the New Image */
            $new = imagecreatetruecolor($newW, $newH);
            /* Transcribe the Source Image into the New (Square) Image */
            //imagecopyresized
            imagecopyresampled($new, $src, 0, 0, 0, 0, $newW, $newH, $oldW, $oldH);
            switch ($up_ext) {
                case 'jpg' : case 'jpeg' :
                    $src = imagejpeg($new, $newfile);
                    break;
                case 'png' :
                    $src = imagepng($new, $newfile);
                    break;
                case 'gif' :
                    $src = imagegif($new, $newfile);
                    break;
            }
        } else {

            copy($target_path, $newfile);
        }
    }

    function check_image($image_url, $default_img = 'no_image.jpg') {

 $ch = curl_init($image_url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($code == 200) {
         return $image_url;
    } else {
        return base_url() . "assets/admin/img/" . $default_img;
    }
    curl_close($ch);
    return $status;



//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $image_url);
//        curl_setopt($ch, CURLOPT_NOBODY, 1);
//        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
////        echo curl_exec($ch);
//        if (curl_exec($ch) !== FALSE) {
// if (File::exists($image_url))
//         {
//             return $image_url;
//         }
//         else
//         {
//             return base_url() . "assets/admin/img/" . $default_img;
//         }
        // if (@getimagesize($image_url)) {

        //     return $image_url;
        // } else {

        //     return base_url() . "assets/admin/img/" . $default_img;
        // }
//        } else {
//
//            return base_url() . "assets/admin/img/" . $default_img;
//        }
    }

    function is_person_image_exist($image_url, $gender) {
        if ($gender == '') {
            $gender = 'male';
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $image_url);
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if (curl_exec($ch) !== FALSE) {
            return $image_url;
        } else {
            if ($gender == 'male') {
                return base_url() . "assets/site/img/unknown_male.jpg";
            } else {
                return base_url() . "assets/site/img/unknown_female.jpg";
            }
        }
    }

    /**
     * Method: Date Difference between 2 dates or one date with current date
     * Params:
     */
    function dateDiff($time1, $time2, $precision = 1) {

        // If not numeric then convert texts to unix timestamps
        if (!is_int($time1)) {
            $time1 = strtotime($time1);
        }
        if (!is_int($time2)) {
            $time2 = strtotime($time2);
        }
        // If time1 is bigger than time2
        // Then swap time1 and time2
        if ($time1 > $time2) {
            $ttime = $time1;
            $time1 = $time2;
            $time2 = $ttime;
        }
        // Set up intervals and diffs arrays
        $intervals = array('Year', 'Month', 'Day', 'Hour', 'Minute', 'Second');
        $diffs = array();
        // Loop thru all intervals
        foreach ($intervals as $interval) {
            // Set default diff to 0
            $diffs [$interval] = 0;
            // Create temp time from time1 and interval
            $ttime = strtotime("+1 " . $interval, $time1);
            // Loop until temp time is smaller than time2
            while ($time2 >= $ttime) {
                $time1 = $ttime;
                $diffs [$interval] ++;
                // Create new temp time from time1 and interval
                $ttime = strtotime("+1 " . $interval, $time1);
            }
        }
        $count = 0;
        $times = array();
        // Loop thru all diffs
        foreach ($diffs as $interval => $value) {
            // Break if we have needed precission
            if ($count >= $precision) {
                break;
            }
            // Add value and interval
            // if value is bigger than 0
            if ($value > 0) {
                // Add s if value is not 1
                if ($value != 1) {
                    $interval .= "s";
                }
                // Add value and interval to times array
                $times [] = $value . " " . $interval;
                $count ++;
            }
        }
        // Return string with times
        return implode(", ", $times);
    }

    function mime_types($file) {
        // our list of mime types
        $mime_types = array(
            "pdf" => "application/pdf"
            , "exe" => "application/octet-stream"
            , "zip" => "application/zip"
            , "docx" => "application/msword"
            , "doc" => "application/msword"
            , "xls" => "application/vnd.ms-excel"
            , "ppt" => "application/vnd.ms-powerpoint"
            , "gif" => "image/gif"
            , "png" => "image/png"
            , "jpeg" => "image/jpg"
            , "jpg" => "image/jpg"
            , "mp3" => "audio/mpeg"
            , "wav" => "audio/x-wav"
            , "mpeg" => "video/mpeg"
            , "mpg" => "video/mpeg"
            , "mpe" => "video/mpeg"
            , "mov" => "video/quicktime"
            , "avi" => "video/x-msvideo"
            , "3gp" => "video/3gpp"
            , "css" => "text/css"
            , "jsc" => "application/javascript"
            , "js" => "application/javascript"
            , "php" => "text/html"
            , "htm" => "text/html"
            , "html" => "text/html"
        );

        $extension = strtolower(end(explode('.', $file)));

        return $mime_types[$extension];
    }

    public function encode($value) {
        if (!$value) {
            return false;
        }
        return hashids_encrypt($value);
    }

    public function decode($value) {
        if (!$value) {
            return false;
        }
        return hashids_decrypt($value);
    }

//    public function encode($value) {
//        $skey = $this->CI->config->item('encryption_key'); // change this
//        if (!$value) {
//            return false;
//        }
//        $text = $value;
//        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
//        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
//        $crypttext = trim(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, str_pad($this->skey, 16, "\0"), $text, MCRYPT_MODE_ECB, $iv), "\0");
//        return trim($this->safe_b64encode($crypttext));
//    }
//
//    public function decode($value) {
//        $skey = $this->CI->config->item('encryption_key'); // change this
//        if (!$value) {
//            return false;
//        }
//        $crypttext = $this->safe_b64decode($value);
//        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
//        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
//        $decrypttext = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, str_pad($this->skey, 16, "\0"), $crypttext, MCRYPT_MODE_ECB, $iv), "\0");
//        return trim($decrypttext);
//    }
//
//    public function safe_b64encode($string) {
//        $skey = $this->CI->config->item('encryption_key'); // change this
//        $data = base64_encode($string);
//        $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
//        return $data;
//    }
//
//    public function safe_b64decode($string) {
//        $skey = $this->CI->config->item('encryption_key'); // change this
//        $data = str_replace(array('-', '_'), array('+', '/'), $string);
//        $mod4 = strlen($data) % 4;
//        if ($mod4) {
//            $data .= substr('====', $mod4);
//        }
//        return base64_decode($data);
//    }

    /*     * End generate mobile numbers */

    /*     * *****funcitons to resize**** */

    function max_width($width) {
        $this->max_width = $width;
    }

    function max_height($height) {
        $this->max_height = $height;
    }

    function image_path($path) {
        $this->path = $path;
    }

    function get_mime() {
        $img_data = getimagesize($this->path);
        $this->mime = $img_data['mime'];
    }

    function create_image() {
        switch ($this->mime) {
            case 'image/jpeg':
                $this->image = imagecreatefromjpeg($this->path);
                break;
            case 'image/gif':
                $this->image = imagecreatefromgif($this->path);
                break;
            case 'image/png':
                $this->image = imagecreatefrompng($this->path);
                break;
        }
    }

    function image_resize($new_path) {
        set_time_limit(600);
        $this->get_mime();
        $this->create_image();
        $this->width = imagesx($this->image);
        $this->height = imagesy($this->image);
        $this->set_dimension();
        $image_resized = imagecreatetruecolor($this->new_width, $this->new_height);
        imagecopyresampled($image_resized, $this->image, 0, 0, 0, 0, $this->new_width, $this->new_height, $this->width, $this->height);
        $this->path = $new_path;
        imagejpeg($image_resized, $this->path);
    }

    //######### FUNCTION FOR RESETTING DEMENSIONS OF IMAGE ###########
    function set_dimension() {
        if ($this->width == $this->height) {
            $case = 'first';
        } elseif ($this->width > $this->height) {
            $case = 'second';
        } else {
            $case = 'third';
        }
        if ($this->width > $this->max_width && $this->height > $this->max_height) {
            $cond = 'first';
        } elseif ($this->width > $this->max_width && $this->height <= $this->max_height) {
            $cond = 'first';
        } else {
            $cond = 'third';
        }
        switch ($case) {
            case 'first':
                $this->new_width = $this->max_width;
                $this->new_height = $this->max_height;
                break;
            case 'second':
                $ratio = $this->width / $this->height;
                $amount = $this->width - $this->max_width;
                $this->new_width = $this->width - $amount;
                $this->new_height = $this->height - ($amount / $ratio);
                break;
            case 'third':
                $ratio = $this->height / $this->width;
                $amount = $this->height - $this->max_height;
                $this->new_height = $this->height - $amount;
                $this->new_width = $this->width - ($amount / $ratio);
                break;
        }
    }

    /*     * ******End resize** */

    /**
     *
     * Enter description here ...
     * @param S $string
     */
    function removeHtml($string) {

        return preg_replace("/<.*?>/", "", $string);
    }

    function aasort(&$array, $key, $order = 0) {

        $sorter = array();
        $ret = array();
        reset($array);
        foreach ($array as $ii => $va) {
            $sorter [$ii] = $va [$key];
        }
        if ($order == 0) {
            asort($sorter);
        } else {
            arsort($sorter);
        }
        foreach ($sorter as $ii => $va) {
            $ret [$ii] = $array [$ii];
        }
        $array = $ret;
    }

}

?>
