<?php
/**
 * @package     redSHOP
 * @subpackage  Models
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class price_filterModelprice_filter extends JModelLegacy
{
    public $_id = null;

    public $_data = null;

    public $_table_prefix = null;

    public function __construct()
    {
        parent::__construct();
        $this->_table_prefix = '#__redshop_';
    }

    public function _buildQuery()
    {
        $category = JRequest::getVar('category');
        $catfld   = '';
        if ($category != 0)
        {
            $catfld .= " AND cx.category_id IN ($category) ";
        }

        $sql = "SELECT DISTINCT(p.product_id),p.* FROM " . $this->_table_prefix . "product AS p " . "LEFT JOIN " . $this->_table_prefix . "product_category_xref AS cx ON cx.product_id = p.product_id " . "WHERE p.published=1 " . $catfld . "ORDER BY p.product_price ";
        return $sql;
    }

    public function getData()
    {
        if (empty($this->_data))
        {
            $query       = $this->_buildQuery();
            $this->_data = $this->_getList($query);
        }
        return $this->_data;
    }
}

