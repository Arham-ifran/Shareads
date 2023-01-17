<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Products extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // check if admin login
        $this->engineinit->_is_not_logged_in_redirect('login');

        $this->load->model('products_model');
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
        if ($this->session->userdata('account_type') == 1)
        {
            redirect(base_url('dashboard'));
        }
        $data['results']    = $this->products_model->loadListings($data);
        $data['pagination'] = $this->pagination->create_links();

        $data ['content'] = $this->load->view('products', $data, true);
        $this->load->view('includes/template_dashboard.view.php', $data);
    }

    /**
     * Method: add
     * Return: Load Add Form
     */
    public function add()
    {
//        error_reporting(E_ALL);
        $data = array();

        if ($this->input->post())
        {

            $insert_id = $this->products_model->saveItem($_POST);


            $_product_name = $_POST['product_name'];
            $_product_name = preg_replace('~[^\\pL\d]+~u', '-', trim($_product_name));
            $_product_name = trim($_product_name, '-');
            $_product_name = iconv('utf-8', 'us-ascii//TRANSLIT', $_product_name);
            $_product_name = strtolower($_product_name);
            $_product_name = preg_replace('~[^-\w]+~', '', $_product_name);

            $slug = $_product_name . '-' . $insert_id['id'];


            $this->products_model->update_product_slug($slug, $insert_id['id']);

            // For Images
            $this->load->library('browser');
            $browser = $this->browser->getBrowser();
            if ($browser == 'Safari' || true)
            {
                for ($i = 0; $i < count($_FILES['images']['name']); $i++)
                {

                    if ($_FILES ['images']['name'][$i] != "")
                    {
                        $extension = $this->common->getExtension($_FILES ['images'] ['name'][$i]);
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

                        $this->products_model->add_product_images($post_image);
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
                        $this->products_model->update_product_images($post_image, $imgs);
                    }
                }
            }
            if ($insert_id['msg'])
            {
                $this->session->set_flashdata('success_message', 'Product successfully saved.');
                redirect('products'); // due to flash data.
            }
            else
            {
                $this->session->set_flashdata('error_message', 'Opps! Error saving informtion. Please try again.');
            }
        }

        $data['action']   = 'add';
        $data ['content'] = $this->load->view('add', $data, true); //Return View as data
        $this->load->view('includes/template_dashboard.view.php', $data);
    }

    /**
     * Method: edit
     * Return: Load Edit Form
     */
    public function edit($id)
    {

        $itemId                  = $this->common->decode($id);
        $data['id']              = $itemId;
        $data['row']             = $this->products_model->getRow($itemId);
        $data['products_images'] = $this->products_model->getProductImages($itemId);
        $data['action']          = 'edit';
        $data ['content']        = $this->load->view('add', $data, true); //Return View as data
        $this->load->view('includes/template_dashboard.view.php', $data);
    }

    public function detials($id)
    {
        $itemId                  = $this->common->decode($id);
        $data['id']              = $itemId;
        $data['row']             = $this->products_model->getRow($itemId);
        $data['products_images'] = $this->products_model->getProductImages($itemId);
        $data ['content']        = $this->load->view('view_details', $data, true); //Return View as data
        $this->load->view('includes/template_dashboard.view.php', $data);
    }

    /**
      @Method: delete
      @Params: itemId
      @Retrun: True/False
     */
    public function delete($id)
    {

        $itemId = $this->common->decode($id);
        $result = $this->products_model->deleteItem($itemId);
        if ($result)
        {
            $this->session->set_flashdata('success_message', 'Product deleted successfully.');
            redirect('products'); // due to flash data.
        }
        else
        {
            $this->session->set_flashdata('error_message', 'Opps! Error occured while deleting record. Please try again.');
        }
    }

    function getSubCategory()
    {

        $data               = array();
        $data ['parent_id'] = $this->input->post('category_id');
        $data ['level']     = $this->input->post('level');
        $result             = $this->products_model->getSubCategories($data);

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

            $this->products_model->add_product_images($post_image);

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

        $this->products_model->delete_image($image_id);
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
        $result     = $this->products_model->remove_uploaded_file($image_id);

        if ($image_id <> '' || $image_id <> '0')
        {
            unlink('uploads/products/pic/' . $image_name);
            unlink('uploads/products/small/' . $image_name);
            unlink('uploads/products/large/' . $image_name);
            unlink('uploads/products/medium/' . $image_name);
        }
        echo $result;
    }

    public function view_commisions($id)
    {
        $decoded_id = $this->common->decode($id);
        $data       = array();
        $user_id    = $this->session->userdata('user_id');
        if ($this->session->userdata('account_type') == 1)
        {
            redirect(base_url('dashboard'));
        }
        $data['results'] = $this->products_model->loadCommisionListings($data, $id);

        $data['pagination'] = $this->pagination->create_links();

        $data ['content'] = $this->load->view('view_commisions', $data, true);
        $this->load->view('includes/template_dashboard.view.php', $data);
    }

    public function changeAffiliateStatus()
    {
        $post               = $this->input->post();
        $post['s_order_id'] = $this->common->decode($post['s_order_id']);
        $db                 = $this->products_model->changeAffiliateStatus($post);
        if ($db)
        {
            echo json_encode(array('status' => 1));
        }
        else
        {
            echo json_encode(array('status' => 1));
        }
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

    public function activate_product()
    {
        $product_id  = $this->common->decode($_POST['pid']);
        $sale_url    = $_POST['sale_url'];
        $product_obj = getValArray('url,sale_url', 'c_products', array('product_id' => $product_id));
        $script_1    = is_script_exists($product_obj['url'], 'shareads.min.js');
        $script_2    = is_script_exists($sale_url, 'shareads_catcher.min.js');

        $onSuccess = $this->load->view('messages/product_activate_success', null, true);
        $onError   = $this->load->view('messages/product_activate_error', null, true);


        if ($script_1 == true && $script_2 == true)
        {
            $this->db->where('product_id', $product_id)->update('c_products', array('script_verified' => 1, 'sale_url' => $sale_url));
            echo json_encode(array('status' => 1, 'message' => $onSuccess));
        }
        else
        {
            echo json_encode(array('status' => 0, 'message' => $onError));
        }
    }

    public function inactive($id)
    {
        $decoded_id = $this->common->decode($id);
        $this->db->where('product_id', $decoded_id)->update('c_products', array('script_verified' => 0));

        $this->session->set_flashdata('success_message', 'Product deactivated successfully.');
        redirect('products'); // due to flash data.
    }

}
