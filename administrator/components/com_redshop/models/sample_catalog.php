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

class sample_catalogModelsample_catalog extends RedshopCoreModelDetail
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

    public function getsample($sample)
    {
        $query = 'SELECT * FROM ' . $this->_table_prefix . 'catalog_colour as c left join ' . $this->_table_prefix . 'catalog_sample as s on s.sample_id=c.sample_id WHERE colour_id in (' . $sample . ')';
        $this->_db->setQuery($query);
        $sample_data = $this->_db->loadObjectlist();

        return $sample_data;
    }

    public function _loadData()
    {
        if (empty($this->_data))
        {
            $query = 'SELECT * FROM ' . $this->_table_prefix . 'sample_request WHERE request_id=' . $this->_id;
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
            $detail              = new stdClass();
            $detail->sample_id   = null;
            $detail->sample_name = null;
            $detail->published   = 1;
            $this->_data         = $detail;
            return (boolean)$this->_data;
        }
        return true;
    }
}
