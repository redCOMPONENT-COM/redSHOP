<?php
/**
 * @package     redSHOP
 * @subpackage  Models
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.installer.installer');
jimport('joomla.installer.helper');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'model' . DS . 'detail.php';

class shipping_detailModelshipping_detail extends RedshopCoreModelDetail
{
    public $_copydata = null;

    public function &getData()
    {
        $this->_loadData();
        return $this->_data;
    }

    public function _loadData()
    {
        $query = 'SELECT * FROM #__extensions WHERE folder="redshop_shipping" and extension_id ="' . $this->_id . '" ';
        $this->_db->setQuery($query);
        $this->_data = $this->_db->loadObject();
        return (boolean)$this->_data;
    }

    public function store($data)
    {
        $query = 'UPDATE #__extensions ' . 'SET name="' . $data['name'] . '" ' . ',enabled ="' . intval($data['published']) . '" ' . 'WHERE element="' . $data['element'] . '" ';
        $this->_db->setQuery($query);
        $this->_db->query();
        if (!$this->_db->query())
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        JPluginHelper::importPlugin('redshop_shipping');
        $dispatcher = JDispatcher::getInstance();
        $dispatcher->trigger('onWriteconfig', array($data));

        return true;
    }

    /**
     * Method to get max ordering
     *
     * @access public
     * @return boolean
     */
    public function MaxOrdering()
    {
        $query = "SELECT (max(ordering)+1) FROM #__extensions where folder='redshop_shipping'";
        $this->_db->setQuery($query);
        return $this->_db->loadResult();
    }
}
