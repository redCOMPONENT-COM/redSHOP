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

class manufacturer_detailModelmanufacturer_detail extends RedshopCoreModelDetail
{
    public $_copydata = null;

    public $_templatedata = null;

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
            $query = 'SELECT * FROM ' . $this->_table_prefix . 'manufacturer WHERE manufacturer_id = ' . $this->_id;
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
            $detail                          = new stdClass();
            $detail->manufacturer_id         = 0;
            $detail->manufacturer_name       = null;
            $detail->manufacturer_desc       = null;
            $detail->manufacturer_email      = null;
            $detail->manufacturer_url        = null;
            $detail->product_per_page        = 0;
            $detail->template_id             = 0;
            $detail->metakey                 = null;
            $detail->metadesc                = null;
            $detail->metalanguage_setting    = null;
            $detail->metarobot_info          = null;
            $detail->pagetitle               = null;
            $detail->pageheading             = null;
            $detail->sef_url                 = null;
            $detail->excluding_category_list = null;
            $detail->published               = 1;
            $this->_data                     = $detail;
            return (boolean)$this->_data;
        }
        return true;
    }

    public function store($data)
    {
        $order_functions  = new order_functions();
        $plg_manufacturer = $order_functions->getparameters('plg_manucaturer_excluding_category');
        if (count($plg_manufacturer) > 0 && $plg_manufacturer[0]->enabled)
        {
            $data['excluding_category_list'] = @ implode(',', $data['excluding_category_list']);
        }

        $row              = $this->getTable();
        $data['ordering'] = $this->MaxOrdering();

        if (!$row->bind($data))
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        if (count($plg_manufacturer) > 0 && $plg_manufacturer[0]->enabled)
        {
            if (!$row->excluding_category_list)
            {
                $row->excluding_category_list = '';
            }
        }
        if (!$row->store())
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        return $row;
    }

    public function TemplateData()
    {
        $query = "SELECT template_id as value,template_name as text FROM " . $this->_table_prefix . "template WHERE template_section ='manufacturer_products' and published=1";
        $this->_db->setQuery($query);
        $this->_templatedata = $this->_db->loadObjectList();
        return $this->_templatedata;
    }

    public function getMediaId($mid)
    {
        $query = 'SELECT media_id,media_name FROM ' . $this->_table_prefix . 'media ' . 'WHERE media_section="manufacturer" AND section_id = ' . $mid;
        $this->_db->setQuery($query);
        return $this->_db->loadObject();
    }

    /**
     * Method to get max ordering
     *
     * @access public
     * @return boolean
     */
    public function MaxOrdering()
    {
        $query = "SELECT (max(ordering)+1) FROM " . $this->_table_prefix . "manufacturer";
        $this->_db->setQuery($query);
        return $this->_db->loadResult();
    }
}
