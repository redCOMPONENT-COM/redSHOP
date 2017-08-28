<?php
/**
 * @package     RedSHOP.Codeception
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */


class Redshop
{
	/**
	 * Clear all tables.
	 *
	 * @return  void
	 */
	public function clearAllTables()
	{
		$this->clearAllCategories();
		$this->clearAllProducts();
	}

	public function clearAllCategories()
	{
		$db = \JFactory::getDbo();
		$query = $db->getQuery(true)
			->delete($db->qn('#__redshop_category'))
			->where($db->qn('parent_id') . ' != 0');

		$db->setQuery($query)->execute();
	}

	public function clearAllProducts(){
		$db = \JFactory::getDbo();
		$query = $db->getQuery(true)
			->delete($db->qn('#_redshop_product'))
			->where('1');

		$db->setQuery($query)->execute();

	}

}