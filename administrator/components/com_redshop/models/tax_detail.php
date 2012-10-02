<?php
/**
 * @package     redSHOP
 * @subpackage  Models
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'thumbnail.php');
jimport('joomla.client.helper');
JClientHelper::setCredentialsFromRequest('ftp');
require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'model' . DS . 'detail.php';

class tax_detailModeltax_detail extends RedshopCoreModelDetail
{
    public $_tax_group_id = null;

    public function __construct()
    {
        parent::__construct();

        $_tax_group_id       = JRequest::getVar('tax_group_id', 0, '');
        $this->_tax_group_id = $_tax_group_id;
    }

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

            $query = ' SELECT tr.*,tg.tax_group_name  ' . ' FROM ' . $this->_table_prefix . 'tax_rate as tr' . ' LEFT JOIN ' . $this->_table_prefix . 'tax_group as tg ON tr.tax_group_id = tg.tax_group_id ' . ' WHERE tr.tax_rate_id = ' . $this->_id;
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
            $detail                = new stdClass();
            $detail->tax_rate_id   = 0;
            $detail->tax_state     = null;
            $detail->tax_country   = null;
            $detail->mdate         = 0;
            $detail->tax_rate      = null;
            $detail->tax_group_id  = $this->_tax_group_id;
            $detail->is_eu_country = 0;

            $this->_data = $detail;

            return (boolean)$this->_data;
        }

        return true;
    }

    public function store($data)
    {
        $row = $this->getTable();

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
}
