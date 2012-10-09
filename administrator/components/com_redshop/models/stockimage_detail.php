<?php
/**
 * @package     redSHOP
 * @subpackage  Models
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'model' . DS . 'detail.php';

class RedshopModelStockimage_detail extends RedshopCoreModelDetail
{
    public function &getData()
    {
        if ($this->_loadData())
        {
        }
        else
        {
            $this->_initData();
        }
        return $this->_data;
    }

    public function _loadData()
    {
        if (empty($this->_data))
        {
            $query = 'SELECT * FROM ' . $this->_table_prefix . 'stockroom_amount_image AS si ' . 'WHERE stock_amount_id="' . $this->_id . '" ';
            $this->_db->setQuery($query);
            $this->_data = $this->_db->loadObject();
            return (boolean)$this->_data;
        }
        return true;
    }

    public function _initData()
    {
        if (empty($this->_data))
        {
            $detail                             = new stdClass();
            $detail->stock_amount_id            = 0;
            $detail->stockroom_id               = 0;
            $detail->stock_option               = null;
            $detail->stock_quantity             = 0;
            $detail->stock_amount_image         = null;
            $detail->stock_amount_image_tooltip = null;
            $this->_data                        = $detail;
            return (boolean)$this->_data;
        }
        return true;
    }

    public function store($data)
    {
        $row  = $this->getTable('stockroom_amount_image');
        $file = JRequest::getVar('stock_amount_image', '', 'files', 'array');
        if ($_FILES['stock_amount_image']['name'] != "")
        {
            $ext         = explode(".", $file['name']);
            $filetmpname = substr($file['name'], 0, strlen($file['name']) - strlen($ext[count($ext) - 1]));

            $filename                = JPath::clean(time() . '_' . $filetmpname . "jpg"); //Make the filename unique
            $row->stock_amount_image = $filename;

            $src  = $file['tmp_name'];
            $dest = REDSHOP_FRONT_IMAGES_RELPATH . 'stockroom' . DS . $filename;
            JFile::upload($src, $dest);

            if (isset($data['stock_image']) != "" && is_file(REDSHOP_FRONT_IMAGES_RELPATH . 'stockroom' . DS . $data['stock_image']))
            {
                unlink(REDSHOP_FRONT_IMAGES_RELPATH . 'stockroom' . DS . $data['stock_image']);
            }
        }
        if (!$row->bind($data))
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        if (!$row->store())
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        return $row;
    }

    public function getStockAmountOption($select = 0)
    {
        $option   = array();
        $option[] = JHTML::_('select.option', 0, JText::_('COM_REDSHOP_SELECT'));
        $option[] = JHTML::_('select.option', 1, JText::_('COM_REDSHOP_HIGHER_THAN'));
        $option[] = JHTML::_('select.option', 2, JText::_('COM_REDSHOP_EQUAL'));
        $option[] = JHTML::_('select.option', 3, JText::_('COM_REDSHOP_LOWER_THAN'));
        if ($select != 0)
        {
            $option = $option[$select]->text;
        }
        return $option;
    }

    public function getStockRoomList()
    {
        $query = 'SELECT s.stockroom_id AS value, s.stockroom_name AS text,s.* FROM ' . $this->_table_prefix . 'stockroom AS s ';
        $this->_db->setQuery($query);
        $list = $this->_db->loadObjectlist();
        return $list;
    }
}
