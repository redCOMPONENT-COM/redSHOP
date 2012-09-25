<?php
/**
 * @package     redSHOP
 * @subpackage  Models
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'mail.php');

class catalogModelcatalog extends JModelLegacy
{
    public $_table_prefix = null;

    function __construct()
    {
        parent::__construct();
        $this->_table_prefix = '#__redshop_';
    }

    function catalogStore($data)
    {
        $row =& $this->getTable('catalog_request');
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
        return true;
    }

    function catalogSampleStore($data)
    {
        $row =& $this->getTable('sample_request');
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
        return true;
    }

    function getCatalogList()
    {
        $query   = "SELECT c.*,c.catalog_id AS value,c.catalog_name AS text FROM " . $this->_table_prefix . "catalog AS c " . "WHERE c.published = 1 ";
        $catalog = $this->_getList($query);
        return $catalog;
    }

    function getCatalogSampleList()
    {
        $query   = "SELECT c.* FROM " . $this->_table_prefix . "catalog_sample AS c " . "WHERE c.published = 1 ";
        $catalog = $this->_getList($query);
        return $catalog;
    }

    function getCatalogSampleColorList($sample_id = 0)
    {
        $and = "";
        if ($sample_id != 0)
        {
            $and = "AND c.sample_id='" . $sample_id . "' ";
        }
        $query   = "SELECT c.* FROM " . $this->_table_prefix . "catalog_colour AS c " . "WHERE 1=1 " . $and;
        $catalog = $this->_getList($query);
        return $catalog;
    }
}

