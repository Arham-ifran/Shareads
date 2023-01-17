<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Site_settings_Model extends CI_Model {
	var $tbl = 'site_settings';
	public function __construct()
	{
		parent::__construct();
	}//End __construct
	/**
	 * Method: getRow
	 * Params: $id
	 * Return: data row
	 */
	function getRow($id)
	{
		$query = $this->db->get_where($this->tbl, array('id' => $id));
		if ($query->num_rows()>0)
		{
			return $query->row_array();
		}
	}//End get_row
	/**
	 * Method: saveItem
	 * Params: $post
	 * Return: True/False
	 */
	public function saveItem($post,$image,$image_favicon){

		
		$data_insert = array();
		if (is_array($post)){
			foreach ($post as $k=>$v){
					$data_insert[$k] = $v;
			}
		}
//
        $data_insert['site_keywords'] = $this->common->removeHtml($data_insert['site_keywords']);
        $data_insert['site_description'] = $this->common->removeHtml($data_insert['site_description']);
        $data_insert['site_name'] = $this->common->removeHtml($data_insert['site_name']);
        $data_insert['site_title'] = $this->common->removeHtml($data_insert['site_title']);


	if ($post['id'] == 0) {//Save Data
           return $this->db->insert($this->db->dbprefix . $this->tbl, $data_insert);

        } else {//Update Data
            $this->db->where('id', $post['id']);
           return $this->db->update($this->db->dbprefix . $this->tbl, $data_insert);

        }

	}

}//End Class