<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Listings extends CI_Controller
{

    public function __construct()
    {

        parent::__construct();
        // check if admin login
        $this->engineinit->_is_not_admin_logged_in_redirect('admin/login');
        // Check rights
        if (rights(21) != true)
        {
            redirect(base_url('admin/dashboard'));
        }
        $this->load->model('listings_model');
        $this->load->library('emailutility');
    }

// End __construct
    /**
      @Method: index
      @Return: Listing
     */
    public function index()
    {
        $data['result']   = $this->listings_model->loadListing();
        $data ['content'] = $this->load->view('listings/listing', $data, true);
        $this->load->view('templete-view', $data);
    }

    public function detail($product_id)
    {
        $data['product_id']      = $product_id              = $this->common->decode($product_id);
        $data['row']             = $this->listings_model->getRow($product_id);
        $data['products_images'] = $this->listings_model->getProductImages($product_id);
        $data['product_chart']   = $this->listings_model->get_product_chart($product_id);
        $data ['content']        = $this->load->view('listings/detail', $data, true);
        $this->load->view('templete-view', $data);
    }

    /**
     * Method: add
     * Return: Load Add Form
     */
    public function add()
    {
//   echo '<pre>';print_r($_FILES);die();
        $data = array();
        // Check rights
        if (rights(22) != true)
        {
            redirect(base_url('admin/dashboard'));
        }
        if ($this->input->post())
        {
            $redirect = false;
            if (isset($_POST['redirect']))
            {
                $redirect = true;
                unset($_POST['redirect']);
            }
            $insert_id = $this->listings_model->saveItem($_POST);

            $_product_name = $_POST['product_name'];
            $_product_name = preg_replace('~[^\\pL\d]+~u', '-', trim($_product_name));
            $_product_name = trim($_product_name, '-');
            $_product_name = iconv('utf-8', 'us-ascii//TRANSLIT', $_product_name);
            $_product_name = strtolower($_product_name);
            $_product_name = preg_replace('~[^-\w]+~', '', $_product_name);

            $slug    = $_product_name . '-' . $insert_id['id'];
            $this->listings_model->update_product_slug($slug, $insert_id['id']);
            // For Images
            $this->load->library('browser');
            $browser = $this->browser->getBrowser();
//            echo $browser;die();
            if ($browser == 'Safari' || true)
            {
//                echo '<pre>';print_r($_FILES);die();
                for ($i = 0; $i < count($_FILES['images']['name']); $i++)
                {
                    if ($_FILES ['images']['name'][$i] != "")
                    {
                        $extension = $this->common->getExtension($_FILES ['images']['name'][$i]);
                        $extension = strtolower($extension);
                        if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif"))
                        {
                            return false;
                        }
                        $path        = 'uploads/products/';
                        $allow_types = 'gif|jpg|jpeg|png';
                        $max_height  = '8000';
                        $max_width   = '8000';

                        $post_image['image'] = $this->common->do_upload_image_product($path, $allow_types, $max_height, $max_width, $_FILES ['images']['tmp_name'][$i], $_FILES ['images']['name'][$i]);

                        $post_image['product_id'] = $insert_id['id'];
                        $post_image['status']     = 1;

                        $this->listings_model->add_product_images($post_image);
                    }
                }
            }
            else
            {
                $image_ids = explode(',', $_POST['image_ids']);
                if (!empty($image_ids))
                {
                    foreach ($image_ids as $imgs)
                    {
                        $post_image               = array();
                        $post_image['product_id'] = $insert_id['id'];
                        $post_image['status']     = 1;
                        $this->listings_model->update_product_images($post_image, $imgs);
                    }
                }
            }
            //SENDING EMAIL :: START
            if ($_POST['action'] == 'add')
            {
                $email_tempData            = get_email_tempData(11);
                $email_tempData['content'] = str_replace("[PRODUCT_NAME]", ucfirst($_POST['product_name']), $email_tempData['content']);
                $email_tempData['content'] = str_replace("[PRODUCT_COMMISSION]", getSiteCurrencySymbol('', $_POST['currency']) . number_format($_POST['commission'], 2), $email_tempData['content']);
                $email_tempData['content'] = str_replace("[CLICK_NOW_TO_SHARE]", "<a class='blue_btn' target='blank' href='" . base_url('login') . "'>Click now to share</a>", $email_tempData['content']);
                if (!empty($email_tempData))
                {
                    $data['title']           = $email_tempData['title'];
                    $data['email_content']   = $email_tempData['content'];
                    $data['welcome_content'] = $email_tempData['welcome_content'];
                    $data['footer']          = $email_tempData['footer'];
                    $subject                 = $data['title'];
                    $email_content           = $this->load->view('includes/email_templates/email_template', $data, true);
                    $all_active_advertisers  = $this->db->select('email')->where(array('account_type' => 1, 'status' => 1, 'is_active' => 1))->get('users')->result();
                    foreach ($all_active_advertisers as $key => $advertiser_email_obj)
                    {
                        $this->emailutility->accountVarification($email_content, $advertiser_email_obj->email, $subject);
                    }
                }
            }
            //SENDING EMAIL :: END

            if ($insert_id['msg'])
            {
                if ($redirect == false)
                {
                    $this->session->set_flashdata('success_message', 'Listing successfully saved.');
                    redirect('admin/listings'); // due to flash data.
                }
                else
                {
                    $this->session->set_flashdata('success_message', 'Listing successfully saved.');
                    redirect('admin/users/publisher'); // due to flash data.
                }
            }
            else
            {
                $this->session->set_flashdata('error_message', 'Opps! Error saving informtion. Please try again.');
            }
        }

        $data['action']   = 'add';
        $data ['content'] = $this->load->view('listings/form', $data, true); //Return View as data
        $this->load->view('templete-view', $data);
    }

    /**
     * Method: edit
     * Return: Load Edit Form
     */
    public function edit($id)
    {
        // Check rights
        if (rights(23) != true)
        {
            redirect(base_url('admin/dashboard'));
        }
        $itemId                  = $this->common->decode($id);
        $data['id']              = $itemId;
        $data['row']             = $this->listings_model->getRow($itemId);
        $data['products_images'] = $this->listings_model->getProductImages($itemId);
        $data['action']          = 'edit';
        $data ['content']        = $this->load->view('listings/form', $data, true); //Return View as data
        $this->load->view('templete-view', $data);
    }

    /**
      @Method: delete
      @Params: itemId
      @Retrun: True/False
     */
    public function delete($id)
    {
        // Check rights
        if (rights(24) != true)
        {
            redirect(base_url('admin/dashboard'));
        }
        $itemId = $this->common->decode($id);
        $result = $this->listings_model->deleteItem($itemId);
        if ($result)
        {
            $this->session->set_flashdata('success_message', 'Record deleted successfully.');
            redirect('admin/listings'); // due to flash data.
        }
        else
        {
            $this->session->set_flashdata('error_message', 'Opps! Error occured while deleting record. Please try again.');
        }
    }

    /**
     * Method: ajaxChangeStatus
     *
     */
    public function ajaxChangeStatus()
    {
        $itemId = $_POST['itemId'];
        $product_obj = getValArray('*','c_products','product_id',$itemId);
        $user_obj = getValArray('*','c_users','user_id',$product_obj['user_id']);
        $status = $_POST['status'];

        if ($status == 0)
        {
            $email_tempData            = get_email_tempData(20);
            $email_tempData['content'] = str_replace("[PRODUCT_NAME]", '<strong>'.ucfirst($product_obj['product_name']).'</strong>', $email_tempData['content']);
            if (!empty($email_tempData))
            {
                $data['title']           = $email_tempData['title'];
                $data['email_content']   = $email_tempData['content'];
                $data['welcome_content'] = $email_tempData['welcome_content'];
                $data['footer']          = $email_tempData['footer'];
                $subject                 = $data['title'];
                $email_content           = $this->load->view('includes/email_templates/email_template', $data, true);
                $this->emailutility->accountVarification($email_content,$user_obj['email'], $subject);
            }
        }
        else
        {
            updateVal('script_verified_by_admin', 0, 'c_products', 'product_id', $itemId);
            updateVal('script_verified', 0, 'c_products', 'product_id', $itemId);
            $email_tempData            = get_email_tempData(21);
            $email_tempData['content'] = str_replace("[PRODUCT_NAME]", '<strong>'.ucfirst($product_obj['product_name']).'</strong>', $email_tempData['content']);
            if (!empty($email_tempData))
            {
                $data['title']           = $email_tempData['title'];
                $data['email_content']   = $email_tempData['content'];
                $data['welcome_content'] = $email_tempData['welcome_content'];
                $data['footer']          = $email_tempData['footer'];
                $subject                 = $data['title'];
                $email_content           = $this->load->view('includes/email_templates/email_template', $data, true);
                $this->emailutility->accountVarification($email_content,$user_obj['email'], $subject);
            }
        }
        $result = $this->listings_model->updateItemStatus($itemId, $status);
        echo $result;
    }

    function getSubCategory()
    {

        $data               = array();
        $data ['parent_id'] = $this->input->post('category_id');
        $data ['level']     = $this->input->post('level');
        $result             = $this->listings_model->getSubCategories($data);

        $data1                 = array();
        $data1 ['result']      = $result;
        $data1 ['category_id'] = $data ['parent_d'];
        $data1 ['label']       = 'Sub Category';

        $data1 ['result_counter'] = count($result);
        echo json_encode($data1);
    }

    /**
      @Method: image_uplaod
      @Retrun: echo json data
     * */
    function image_uplaod()
    {

        $post_image = array();
        $snkDrop    = $this->session->userdata('snkDrop');
        $data       = array();
        if ($_FILES ['images']['name'] != "")
        {
            $extension = $this->common->getExtension($_FILES ['images'] ['name']);
            $extension = strtolower($extension);
            if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif"))
            {
                return false;
            }
            $path        = 'uploads/products/';
            $allow_types = 'gif|jpg|jpeg|png';
            $max_height  = '8000';
            $max_width   = '8000';

            $images = $this->common->do_upload_image_product($path, $allow_types, $max_height, $max_width, $_FILES ['images']['tmp_name'], $_FILES ['images']['name']);

            $post_image['status'] = 1;
            $post_image['image']  = $images;

            $this->listings_model->add_product_images($post_image);

            $data['images'] = $images;
            $data['id']     = $this->db->insert_id();
        }
        echo json_encode($data);
        exit;
    }

    /**
     * Method: ajax delete single image ad vert
     *
     */
    function delete_image()
    {
        $image_name = $this->input->get_post("image");
        $image_id   = $this->input->get_post("image_id");

        unlink('uploads/products/pic/' . $image_name);
        unlink('uploads/products/small/' . $image_name);
        unlink('uploads/products/large/' . $image_name);
        unlink('uploads/products/medium/' . $image_name);

        $this->listings_model->delete_image($image_id);
        return true;
    }

    /**
      @Method: remove_uploaded_file
      @Retrun: echo data
     * */
    function remove_uploaded_file()
    {

        $image_name = $this->input->post('image_name');
        $image_id   = $this->input->post('id');
        $result     = $this->listings_model->remove_uploaded_file($image_id);

        if ($image_id <> '' || $image_id <> '0')
        {
            unlink('uploads/products/pic/' . $image_name);
            unlink('uploads/products/small/' . $image_name);
            unlink('uploads/products/large/' . $image_name);
            unlink('uploads/products/medium/' . $image_name);
        }
        echo $result;
    }

    public function verifyReferalURL()
    {
        $referal_url = $_POST['referal_url'];
        $query       = "SELECT product_id FROM c_products WHERE url LIKE '%" . $referal_url . "%'
            or REPLACE(Substring_index(Substring_index(Substring_index(  Substring_index(c_products.url,  '/', 3)  , '://', -1), '/', 1), '?', 1),  'www.'  , '' ) =
REPLACE(Substring_index(Substring_index(Substring_index(Substring_index('" . $referal_url . "',  '/', 3),'://',-1), '/', 1), '?', 1),  'www.','') 
          order by product_id DESC limit 1";
        $query       = $this->db->query($query);
        if ($query->num_rows() > 0)
        {
            echo 0;
        }
        else
        {
            echo 1;
        }
    }

    public function activate_product_manually($_product_id)
    {
        $product_id = $this->common->decode($_product_id);
        $result     = $this->db->where('product_id', $product_id)->update('c_products', array('script_verified' => 1, 'script_verified_by_admin' => 1));
//        $product_url = getVal('url', 'c_products', array('product_id' => $product_id));
//
//        $insert                      = array();
//        $insert['product_id']        = $product_id;
//        $insert['user_identifier']   = 1;
//        $insert['url']               = $product_url;
//        $insert['request_uri']       = 'ADDED_MANUALLY';
//        $insert['timestamp']         = 'ADDED_MANUALLY';
//        $insert['client_ip']         = 'ADDED_MANUALLY';
//        $insert['client_user_agent'] = 'ADDED_MANUALLY';
//        $insert['referer_page']      = 'ADDED_MANUALLY';
//        $result                      = $this->db->insert('usertracking', $insert);
        if ($result)
        {
            $this->session->set_flashdata('success_message', 'Product activated successfully.');
            redirect('admin/listings'); // due to flash data.
        }
        else
        {
            $this->session->set_flashdata('error_message', 'Opps! Error occured while deleting record. Please try again.');
        }
        redirect('admin/listings');
    }

}

//End Class