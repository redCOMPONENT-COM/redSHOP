<?php
/**
 * @package     redSHOP
 * @subpackage  Models
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'model.php';

class customprintModelcustomprint extends RedshopCoreModel
{
    public function getData()
    {
        if (empty($this->_data))
        {
            $query       = $this->_buildQuery();
            $this->_data = $this->_getList($query);
        }
        return $this->_data;
    }

    public function _buildQuery()
    {
        $where = " where folder='redshop_custom_views' and published=1";
        $query = ' SELECT p.* FROM ' . $this->_table_prefix . 'plugins p' . $where;
        return $query;
    }
}
