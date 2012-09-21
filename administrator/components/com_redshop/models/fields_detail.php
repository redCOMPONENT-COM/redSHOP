<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license   GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 *            Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'extra_field.php');

class fields_detailModelfields_detail extends JModel
{
    var $_id = null;
    var $_data = null;
    var $_table_prefix = null;
    var $_fielddata = null;

    function __construct ()
    {
        parent::__construct();

        $this->_table_prefix = '#__redshop_';

        $array = JRequest::getVar('cid', 0, '', 'array');

        $this->setId((int)$array[0]);
    }

    function setId ($id)
    {
        $this->_id   = $id;
        $this->_data = null;
    }

    function &getData ()
    {
        if ($this->_loadData()) {
        } else  {
            $this->_initData();
        }

        return $this->_data;
    }

    function _loadData ()
    {
        if (empty($this->_data)) {
            $query = 'SELECT * FROM ' . $this->_table_prefix . 'fields  WHERE field_id = ' . $this->_id;
            $this->_db->setQuery($query);
            $this->_data = $this->_db->loadObject();
            return (boolean)$this->_data;
        }
        return true;
    }

    function _initData ()
    {
        if (empty($this->_data)) {
            $detail                      = new stdClass();
            $detail->field_id            = 0;
            $detail->field_title         = null;
            $detail->wysiwyg             = null;
            $detail->field_type          = 0;
            $detail->field_name          = null;
            $detail->field_desc          = null;
            $detail->field_class         = null;
            $detail->field_section       = 0;
            $detail->field_maxlength     = 0;
            $detail->field_cols          = 0;
            $detail->field_rows          = 0;
            $detail->field_size          = 0;
            $detail->field_show_in_front = 0;
            $detail->required            = 0;
            $detail->published           = 1;
            $detail->display_in_product  = 0;
            $this->_data                 = $detail;
            return (boolean)$this->_data;
        }
        return true;
    }

    function store ($data)
    {
        $row       = $this->getTable();
        $field_cid = $data['cid'][0];

        if (!$field_cid) {
            $data['ordering'] = $this->MaxOrdering();
        }

        if (!$row->bind($data)) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        if (!$row->store()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        return $row;
    }

    function field_save ($id, $post)
    {
        $extra_field = new extra_field();
        $value_id    = array();
        $extra_name  = array();
        $extra_value = array();
        if (array_key_exists("value_id", $post)) {
            //$extra_value = $post["extra_value"];
            $extra_value = JRequest::getVar('extra_value', '', 'post', 'string', JREQUEST_ALLOWRAW);
            $value_id    = $post["value_id"];
            if ($post["field_type"] == 11 || $post["field_type"] == 13) {
                $extra_name = JRequest::getVar('extra_name_file', '', 'files', 'array');
                $total      = count($extra_name['name']);
            } else {
                //$extra_name = $post["extra_name"];
                $extra_name = JRequest::getVar('extra_name', '', 'post', 'string', JREQUEST_ALLOWRAW);
                $total      = count($extra_name);
            }
        }

        $filed_data_id = $extra_field->getFieldValue($id);

        if (count($filed_data_id) > 0) {
            $fid = array();
            foreach ($filed_data_id as $f) {
                $fid[] = $f->value_id;
            }
            $del_fid = array_diff($fid, $value_id);
            if (count($del_fid) > 0) {
                $this->field_delete($del_fid, 'value_id');
            }
        }
        for ($j = 0; $j < $total; $j++) {
            $set = "";
            if ($post["field_type"] == 11 || $post["field_type"] == 13) {
                if ($extra_value[$j] != "" && $extra_name['name'][$j] != "") {
                    $filename = time() . "_" . $extra_name['name'][$j];

                    $src  = $extra_name['tmp_name'][$j];
                    $dest = REDSHOP_FRONT_IMAGES_RELPATH . 'extrafield' . DS . $filename;

                    JFile::upload($src, $dest);

                    $set = " field_name='" . $filename . "', ";
                }
            } else {
                $filename = $extra_name[$j];
                $set      = " field_name='" . $filename . "', ";
            }
            if ($value_id[$j] == "") {
                $query = "INSERT INTO " . $this->_table_prefix . "fields_value "
                    . "(field_id,field_name,field_value) "
                    . "VALUE ( '" . $id . "','" . $filename . "','" . $extra_value[$j] . "' ) ";
            } else {
                $query = "UPDATE " . $this->_table_prefix . "fields_value "
                    . "SET " . $set . " field_value='" . $extra_value[$j] . "' "
                    . "WHERE value_id='" . $value_id[$j] . "' ";
            }
            $this->_db->setQuery($query);
            if (!$this->_db->query()) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }
    }

    function field_delete ($id, $field)
    {
        $id    = implode(',', $id);
        $query = 'DELETE FROM ' . $this->_table_prefix . 'fields_value WHERE ' . $field . ' IN ( ' . $id . ' )';

        $this->_db->setQuery($query);
        if (!$this->_db->query()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
    }

    function delete ($cid = array())
    {
        if (count($cid)) {
            $cids = implode(',', $cid);

            $query = 'DELETE FROM ' . $this->_table_prefix . 'fields WHERE field_id IN ( ' . $cids . ' )';
            $this->_db->setQuery($query);
            if (!$this->_db->query()) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }

            // 	remove fields_data
            $query_field_data = 'DELETE FROM ' . $this->_table_prefix . 'fields_data  WHERE fieldid IN ( ' . $cids . ' ) ';
            $this->_db->setQuery($query_field_data);
            if (!$this->_db->query()) {
                $this->setError($this->_db->getErrorMsg());
                //return false;
            }
        }

        return true;
    }

    function publish ($cid = array(), $publish = 1)
    {
        if (count($cid)) {
            $cids = implode(',', $cid);

            $query = 'UPDATE ' . $this->_table_prefix . 'fields'
                . ' SET published = ' . intval($publish)
                . ' WHERE field_id IN ( ' . $cids . ' )';
            $this->_db->setQuery($query);
            if (!$this->_db->query()) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }

        return true;
    }


    function saveorder ($cid = array(), $order)
    {
        $row        = $this->getTable();
        $groupings  = array();
        $conditions = array();

        // update ordering values
        for ($i = 0; $i < count($cid); $i++) {
            $row->load((int)$cid[$i]);
            // track categories
            $groupings[] = $row->field_id;

            if ($row->ordering != $order[$i]) {
                $row->ordering = $order[$i];
                if (!$row->store()) {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }
                // remember to updateOrder this group
                $condition = 'field_section = ' . (int)$row->field_section;
                $found     = false;
                foreach ($conditions as $cond) {
                    if ($cond[1] == $condition) {
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $conditions[] = array($row->field_id, $condition);
                }
            }
        }
        // execute updateOrder for each group
        foreach ($conditions as $cond) {
            $row->load($cond[0]);
            $row->reorder($cond[1]);
        }
//		// execute updateOrder for each parent group
//		$groupings = array_unique( $groupings );
//		foreach ($groupings as $group){
//			$row->reorder((int) $group);
//		}
        return true;
    }

    /*

     /**
      * Method to get max ordering
      *
      * @access public
      * @return boolean
      */
    function MaxOrdering ()
    {
        $query = "SELECT (count(*)+1) FROM " . $this->_table_prefix . "fields";
        $this->_db->setQuery($query);
        return $this->_db->loadResult();
    }

    /**
     * Method to move
     *
     * @access  public
     * @return  boolean True on success
     * @since   0.9
     */
    function move ($direction)
    {
        $row = JTable::getInstance('fields_detail', 'Table');

        if (!$row->load($this->_id)) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        if (!$row->move($direction)) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        return true;
    }

    /**
     * Method to get databse field name
     *
     * @access public
     * @return boolean
     */
    function checkFieldname ($field_name, $field_id)
    {
        $query = "SELECT COUNT(*) AS cnt FROM " . $this->_table_prefix . "fields "
            . "WHERE field_name='" . $field_name . "' "
            . "AND field_id!='" . $field_id . "' ";
        $this->_db->setQuery($query);
        $result = $this->_db->loadResult();
        return (boolean)$result;
    }
}

?>
