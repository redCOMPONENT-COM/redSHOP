<?php
/**
 * Created by PhpStorm.
 * User: nhung
 * Date: 8/28/17
 * Time: 10:16 AM
 */

namespace AcceptanceTester;


class Redshop extends \AcceptanceTester
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
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->delete($db->qn('#__redshop_category'))
			->where($db->qn('parent_id') . ' != 0');

		$db->setQuery($query)->execute();
	}

	public function clearAllProducts(){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->delete($db->qn('#_redshop_product'))
			->where('1');

		$db->setQuery($query)->execute();

	}

}