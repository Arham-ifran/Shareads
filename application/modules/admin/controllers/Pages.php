<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pages extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
//        error_reporting(E_ALL);
        // check if admin login
        $this->engineinit->_is_not_admin_logged_in_redirect('admin/login');
        // Check rights
        if (rights(25) != true)
        {
            redirect(base_url('admin/dashboard'));
        }
        $this->load->model('pages_model');
    }

// End __construct
    /**
      @Method: index
      @Return: Listing
     */
    public function index()
    {
        $data['result']   = $this->pages_model->loadListing();
//        $data['pagination'] = $this->pagination->create_links();
        $data ['content'] = $this->load->view('pages/listing', $data, true);
        $this->load->view('templete-view', $data);
    }

    /**
     * Method: add
     * Return: Load Add Form
     */
    public function add()
    {
        $data = array();
        // Check rights
        if (rights(26) != true)
        {
            redirect(base_url('admin/dashboard'));
        }
        if ($this->input->post())
        {
            $db_query = $this->pages_model->saveItem($_POST);

            if ($db_query)
            {
                $this->session->set_flashdata('success_message', 'Information successfully saved.');
                redirect('admin/pages'); // due to flash data.
            }
            else
            {
                $this->session->set_flashdata('error_message', 'Opps! Error saving informtion. Please try again.');
            }
        }

        $data['action']   = 'add';
        $data["allpages"] = $this->pages_model->get_main_pages();
        $data ['content'] = $this->load->view('pages/form', $data, true); //Return View as data
        $this->load->view('templete-view', $data);
    }

    /**
     * Method: edit
     * Return: Load Edit Form
     */
    public function edit($id)
    {
        // Check rights
        if (rights(27) != true)
        {
            redirect(base_url('admin/dashboard'));
        }
        $itemId           = $this->common->decode($id);
        $data['id']       = $itemId;
        $data['row']      = $this->pages_model->getRow($itemId);
        $data['action']   = 'edit';
        $data["allpages"] = $this->pages_model->get_main_pages();
        $data ['content'] = $this->load->view('pages/form', $data, true); //Return View as data
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
        if (rights(28) != true)
        {
            redirect(base_url('admin/dashboard'));
        }
        $itemId = $this->common->decode($id);
        $result = $this->pages_model->deleteItem($itemId);
        if ($result)
        {
            $this->session->set_flashdata('success_message', 'Record deleted successfully.');
            redirect('admin/pages'); // due to flash data.
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
        $status = $_POST['status'];
        $result = $this->pages_model->updateItemStatus($itemId, $status);
        echo $result;
    }

    /**
     * Method: Check Page & create Slug
     *
     */
    public function checkPage()
    {
        $data = array();
        $slug = $this->input->post('slug');
        $name = $this->pages_model->checkPage($slug);
        if ($name == 0)
        {
            $page_name = preg_replace('~[^\\pL\d]+~u', '-', trim($slug));
            $page_name = trim($page_name, '-');
            $page_name = iconv('utf-8', 'us-ascii//TRANSLIT', $page_name);
            $page_name = strtolower($page_name);
            $page_name = preg_replace('~[^-\w]+~', '', $page_name);
            $pageTitle = $this->pages_model->checkPage($page_name);
            if ($pageTitle == 1)
            {
                $data ['slug'] = $page_name . strtotime(date("Y-m-d H:i:s"));
            }
            else
                $data ['slug'] = $page_name;
        } else
        {
            $data ['slug'] = 1;
        }
        echo $data ['slug'];
        exit;
    }

    /**
     * Method: ajaxUpdateOrder
     * params: post array
     * return: msg
     */
    public function ajaxUpdateOrder()
    {
        $id = $this->input->post('ordId');
        foreach ($id as $res)
        {
            $val = $this->input->post('order_' . $res . '');
            $this->pages_model->updateOrder($res, $val);
        }
        echo 'Pages order updated successfully.';
        exit();
    }

    function home()
    {
        $data             = array();
        $data ['content'] = $this->load->view('pages/form_custom_home', $data, true); //Return View as data
        $this->load->view('templete-view', $data);
    }

    function custom_update_home()
    {
        // Get the posted parameters
        $new_content  = $_POST['content'];
        $content_id   = $_POST['content_id'];
        $tpl_filename = $_POST['tpl'];

        // Get the contents of the .tpl file to edit
        $file_contents = file_get_contents(APPPATH . 'modules\home\views\\' . $tpl_filename . '.php');

        // create revision as a backup in case of emergency
        $revised_filename = str_replace('/', '.', $tpl_filename);
        $revised_filename = ltrim($revised_filename, '.');
        file_put_contents(APPPATH . 'modules\home\views\\' . $revised_filename . '-' . time() . '.php', $file_contents);


        $re = '% # Match a DIV element having id="content".
        <div\b             # Start of outer DIV start tag.
        [^>]*?             # Lazily match up to id attrib.
        \bid\s*+=\s*+      # id attribute name and =
        ([\'"]?+)          # $1: Optional quote delimiter.
        \b' . $content_id . '\b        # specific ID to be matched.
        (?(1)\1)           # If open quote, match same closing quote
        [^>]*+>            # remaining outer DIV start tag.
        (                  # $2: DIV contents. (may be called recursively!)
          (?:              # Non-capture group for DIV contents alternatives.
          # DIV contents option 1: All non-DIV, non-comment stuff...
            [^<]++         # One or more non-tag, non-comment characters.
          # DIV contents option 2: Start of a non-DIV tag...
          | <            # Match a "<", but only if it
            (?!          # is not the beginning of either
              /?div\b    # a DIV start or end tag,
            | !--        # or an HTML comment.
            )            # Ok, that < was not a DIV or comment.
          # DIV contents Option 3: an HTML comment.
          | <!--.*?-->     # A non-SGML compliant HTML comment.
          # DIV contents Option 4: a nested DIV element!
          | <div\b[^>]*+>  # Inner DIV element start tag.
            (?2)           # Recurse group 2 as a nested subroutine.
            </div\s*>      # Inner DIV element end tag.
          )*+              # Zero or more of these contents alternatives.
        )                  # End 2$: DIV contents.
        </div\s*>          # Outer DIV end tag.
        %isx';

        if (preg_match($re, $file_contents, $matches))
        {
            $content_to_replace = $matches[0];

            $replacement_content = $content_to_replace;

            // Replace the inner content of $replacement_content with $new_content
            $replacement_content = preg_replace('/(<div(?:.*?)>)(?:.*)(<\/div>)/msi', "$1" . $new_content . "$2", $replacement_content);

            // Now replace the content_to_replace with $replacement content in the HTML
            $new_file_content = str_replace($content_to_replace, $replacement_content, $file_contents);

            $new_file_contents  = str_replace('http://clients.arhamsoft.com/shareads/dev/', '<?php echo base_url()?>', $new_file_content);
            $new_file_contents  = str_replace('https://www.shareads.com/', '<?php echo base_url()?>', $new_file_contents);
            $new_file_contents  = str_replace('https://shareads.com/', '<?php echo base_url()?>', $new_file_contents);
            $new_file_contents1 = str_replace('http://localhost/shareads/', '<?php echo base_url()?>', $new_file_contents);

            // write out the new .tpl file
            file_put_contents(APPPATH . 'modules\home\views\\' . $tpl_filename . '.php', $new_file_contents1);
        }
    }

    function login_signup_content()
    {
        $data             = array();
        $data ['content'] = $this->load->view('pages/form_login_signup', $data, true); //Return View as data
        $this->load->view('templete-view', $data);
    }

    function custom_bottom_content()
    {
        // Get the posted parameters
        $new_content  = $_POST['content'];
        $content_id   = $_POST['content_id'];
        $tpl_filename = $_POST['tpl'];

        // Get the contents of the .tpl file to edit
        $file_contents = file_get_contents(APPPATH . 'modules\register\views\\' . $tpl_filename . '.php');

        // create revision as a backup in case of emergency
        $revised_filename = str_replace('/', '.', $tpl_filename);
        $revised_filename = ltrim($revised_filename, '.');
        file_put_contents(APPPATH . 'modules\register\views\\' . $revised_filename . '-' . time() . '.php', $file_contents);


        $re = '% # Match a DIV element having id="content".
        <div\b             # Start of outer DIV start tag.
        [^>]*?             # Lazily match up to id attrib.
        \bid\s*+=\s*+      # id attribute name and =
        ([\'"]?+)          # $1: Optional quote delimiter.
        \b' . $content_id . '\b        # specific ID to be matched.
        (?(1)\1)           # If open quote, match same closing quote
        [^>]*+>            # remaining outer DIV start tag.
        (                  # $2: DIV contents. (may be called recursively!)
          (?:              # Non-capture group for DIV contents alternatives.
          # DIV contents option 1: All non-DIV, non-comment stuff...
            [^<]++         # One or more non-tag, non-comment characters.
          # DIV contents option 2: Start of a non-DIV tag...
          | <            # Match a "<", but only if it
            (?!          # is not the beginning of either
              /?div\b    # a DIV start or end tag,
            | !--        # or an HTML comment.
            )            # Ok, that < was not a DIV or comment.
          # DIV contents Option 3: an HTML comment.
          | <!--.*?-->     # A non-SGML compliant HTML comment.
          # DIV contents Option 4: a nested DIV element!
          | <div\b[^>]*+>  # Inner DIV element start tag.
            (?2)           # Recurse group 2 as a nested subroutine.
            </div\s*>      # Inner DIV element end tag.
          )*+              # Zero or more of these contents alternatives.
        )                  # End 2$: DIV contents.
        </div\s*>          # Outer DIV end tag.
        %isx';

        if (preg_match($re, $file_contents, $matches))
        {
            $content_to_replace = $matches[0];

            $replacement_content = $content_to_replace;

            // Replace the inner content of $replacement_content with $new_content
            $replacement_content = preg_replace('/(<div(?:.*?)>)(?:.*)(<\/div>)/msi', "$1" . $new_content . "$2", $replacement_content);

            // Now replace the content_to_replace with $replacement content in the HTML
            $new_file_content = str_replace($content_to_replace, $replacement_content, $file_contents);

            $new_file_contents  = str_replace('http://clients.arhamsoft.com/shareads/dev/', '<?php echo base_url()?>', $new_file_content);
            $new_file_contents  = str_replace('https://www.shareads.com/', '<?php echo base_url()?>', $new_file_contents);
            $new_file_contents  = str_replace('https://shareads.com/', '<?php echo base_url()?>', $new_file_contents);
            $new_file_contents1 = str_replace('http://localhost/shareads/', '<?php echo base_url()?>', $new_file_contents);

            // write out the new .tpl file
            file_put_contents(APPPATH . 'modules\register\views\\' . $tpl_filename . '.php', $new_file_contents1);
        }
    }

    public function welcome_content($id)
    {
        $data = array();
        $data['id_page'] = $id;
        if ($_POST)
        {
            $id = $_POST['id'];
            unset($_POST['id']);
            $this->db->where('id', $id)->update('c_templates', $_POST);
            redirect('admin/pages/welcome_content/'.$data['id_page']);
        }
        $data['row']      = $this->db->from('c_templates')->where(array('user_type' => $id))->get()->row_array();
        $data ['content'] = $this->load->view('pages/form_welcome_content', $data, true); //Return View as data
        $this->load->view('templete-view', $data);
    }

}

//End Class