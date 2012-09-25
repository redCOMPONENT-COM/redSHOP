<?php
/**
 * @package     redSHOP
 * @subpackage  Models
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class customprintModelcustomprint extends JModelLegacy
{
    public $_data = null;

    public $_table_prefix = null;

    function __construct()
    {
        parent::__construct();

        $this->_table_prefix = '#__';
    }

    function getData()
    {
        if (empty($this->_data))
        {
            $query       = $this->_buildQuery();
            $this->_data = $this->_getList($query);
        }
        return $this->_data;
    }

    function _buildQuery()
    {
        $where = " where folder='redshop_custom_views' and published=1";
        $query = ' SELECT p.* FROM ' . $this->_table_prefix . 'plugins p' . $where;
        return $query;
    }
}
