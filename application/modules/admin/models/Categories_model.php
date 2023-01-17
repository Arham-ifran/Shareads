<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Categories_Model extends CI_Model {

    var $tbl = 'categories';
    var $tbl_prods = 'products';
    var $limit;
    var $limitstart;
    var $filter_state;
    var $filter_category;
    var $filter_search;
    var $_total;
    var $items = array();

    public function __construct() {
        parent::__construct();
        /*         * Search filters */
        if ($this->input->post()) {
            $this->filter_search = strtolower($this->input->post('category_name'));
            $this->filter_category = $this->input->post('category_id');
            $this->filter_state = $this->input->post('status');
        }
        $this->limit = 10;
        $this->limitstart = 0;
        /* End search filters */
    }

//End __construct

    public function getItems() {
        $this->buildRecursiveData($this->getparent_id(), $spcr = '');
        $this->_total = count($this->items);
        return $this->items;
    }

    function getparent_id() {
        if ($this->filter_category > 0) {
            $query = "SELECT parent_id FROM " . $this->db->dbprefix($this->tbl) . " WHERE category_id =" . $this->filter_category;
            $query = $this->db->query($query);
            if ($query->num_rows() > 0) {
                return $query->row('parent_id');
            }
        }
        return 0;
    }

    function buildRecursiveData($parent, $spcr = '') {
        $query = "SELECT * FROM " . $this->db->dbprefix($this->tbl) . "";
        $where = array();
        $setparent = 1;
        if ($this->filter_state != '') {
            $where[] = "status=" . $this->filter_state;
            $setparent = 0;
        }
        if ($this->filter_category > 0) {
            $tree = getChildren($this->tbl, $this->filter_category);
            $where[] = 'category_id IN (' . implode(',', $tree) . ')';
        }
        if ($this->filter_search) {
            $where[] = 'LOWER(category_name) LIKE ' . '"%' . mysql_escape_string($this->filter_search) . '%"';
            $setparent = 0;
        }
        if ($setparent) {
            $where[] = "parent_id=" . $parent;
        }
        $where = ( count($where) ? ' WHERE ' . implode(' AND ', $where) : '' );
        $query .= $where;
        $query .= " ORDER BY category_id ASC";
        $query = $this->db->query($query);
        $cats = $query->result();
        $c = 0;
        $count = count($cats);
        if ($count) {
            foreach ($cats as $cat) {
                $cat->up = ($c == 0) ? 0 : 1;
                $cat->down = ($c + 1 == $count) ? 0 : 1;
                $cat->spcr = $spcr;
                if ($cat->parent_id > 0) {
                    $cat->spcr .= "<sup>L</sup>&nbsp;&nbsp;";
                }
                $this->items[] = $cat;
                $c++;
                if ($setparent) {
                    $this->buildRecursiveData($cat->category_id, $spcr . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
                }
            }
        }
    }



    /**
     * Method: getRow
     * Params: $id
     * Return: data row
     */
    function getRow($id) {
        $query = $this->db->get_where($this->tbl, array('category_id' => $id));
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
    }

//End get_row
    /**
     * Method: getCategories
     * Params: $parent,$level,$sel
     * Return: categories
     */

    function getCategories($parent, $level, $sel) {
        $this->db->where('parent_id', $parent);
        $this->db->select('category_id,category_name');
        $this->db->where('status', 1);
        $query = $this->db->get($this->tbl);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                if ($row->category_id == $sel) {
                    $seletd = 'selected="selected"';
                } else {
                    $seletd = '';
                }
                echo '<option value="' . $row->category_id . '" ' . $seletd . '>' . str_repeat('-', $level) . ' ' . $row->category_name . '</option>';
                $this->getCategories($row->category_id, $level + 1, $sel);
            }
        }
    }

    /**
     * Method: saveItem
     * Params: $post
     * Return: True/False
     */
    public function saveItem($post) {
        $id = $post['category_id'];
        $data_insert = array();
        if (is_array($post)) {
            foreach ($post as $k => $v) {
                if ($k != 'category_id' && $k != 'action') {
                    $data_insert[$k] = $v;
                }
            }
        }
        $data_insert['meta_keywords'] = $this->common->removeHtml($data_insert['meta_keywords']);
        $data_insert['meta_description'] = $this->common->removeHtml($data_insert['meta_description']);

        if ($post['action'] == 'add') {//Save Data
           return $this->db->insert($this->db->dbprefix . $this->tbl, $data_insert);
        } else {//Update Data
            $this->db->where('category_id', $id);
           return $this->db->update($this->db->dbprefix . $this->tbl, $data_insert);
        }

    }

    /**
     * Method: deleteItem
     * Params: $itemId
     * Return: True/False
     */
    public function deleteItem($itemId) {
        $this->db->where('category_id', $itemId);
        $this->db->or_where('parent_id', $itemId);
        $this->db->delete($this->tbl);
        $error = $this->db->error();
        if ($error['code'] <> 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Method: updateItemStatus
     * Params: $itemId, $status
     */
    public function updateItemStatus($itemId, $status) {
        if ($status == 1) {
            $status = 0;
        } else {
            $status = 1;
        }
        $data_insert = array('status' => $status);
        $this->db->where('category_id', $itemId);
        $this->db->update($this->tbl, $data_insert);
        $action = 'updated';
        $msg = 'Status ' . $action . ' successfully. Please wait...';
        return $msg;
    }

    /**
     * Method: isUniqueTitle
     * Params: $id,$title
     */
    public function isUniqueTitle($id, $title) {
        $this->db->select('category_id');
        $this->db->where('category_id <>', $id);
        $this->db->where('category_name', $title);
        $q = $this->db->get($this->tbl);
        if ($q->num_rows() > 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Start front end functions
     * */

    /**
     * Method: fetchCategories
     * params: $oderBy
     * Returns: categories
     */
    public function fetchCategories($oderBy) {
        $sql_ = 'SELECT c.category_id, c.category_name, c.category_slug, c.parent_id, IFNULL(a.prodsscount, 0) as prodsscount FROM ' . $this->db->dbprefix($this->tbl) . ' AS c';
        $sql_ .= ' LEFT JOIN (SELECT a.category_id, COUNT(a.product_id) as prodsscount FROM ' . $this->db->dbprefix($this->tbl_prods) . ' AS a WHERE a.status=1 GROUP BY a.category_id) AS a ON a.category_id=c.category_id';
        $sql_ .= ' WHERE c.status=1 ORDER BY c.' . $oderBy . '';
        $query = $this->db->query($sql_);
        return $query->result();
    }

    /**
     * Method: checkPage
     * Params: $slug
     * Return: True/False
     */
    function checkCatSlug($slug) {
        $sqlChk = "SELECT category_name FROM " . $this->db->dbprefix . $this->tbl . " WHERE category_name = '" . $slug . "'";
        $query = $this->db->query($sqlChk);
        if ($query->num_rows >= 1) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
	 * Method: updateOrder
	 * params: $id,$val
	 * returns: boolean
	 */
	public function updateOrder($id,$val){
		$record = array('service_category'=>$val);
		$this->db->set($record);
		$this->db->where('category_id', $id);
		$this->db->update($this->tbl);
		$error =$this->db->error();
                if ($error['code'] <> 0) {
                    return false;
                } else {
                    return true;
                }
	}

}

//End Class