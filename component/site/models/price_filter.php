<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


/**
 * Class price_filterModelprice_filter
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 * @since       1.0
 */
class RedshopModelPrice_filter extends RedshopModel
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
		$catfld   = '';

		if ($category = JRequest::getInt('category', 0))
		{
			$catfld .= " AND cx.category_id = " . (int) $category . ' ';
		}

		$sql = "SELECT DISTINCT(p.product_id),p.* FROM " . $this->_table_prefix . "product AS p "
			. "LEFT JOIN " . $this->_table_prefix . "product_category_xref AS cx ON cx.product_id = p.product_id "
			. "WHERE p.published=1 "
			. $catfld
			. "ORDER BY p.product_price ";

		return $sql;
	}

	public function getData()
	{
		if (empty($this->_data))
		{
			$query       = $this->_buildQuery();
			$limit = JFactory::getApplication()->input->getInt('count');
			$this->_data = $this->_getList($query, 0, $limit);
		}

		return $this->_data;
	}
}
