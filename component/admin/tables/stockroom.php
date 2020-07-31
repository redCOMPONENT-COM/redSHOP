<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * The Stockroom table
 *
 * @package     RedSHOP.Backend
 * @subpackage  Table.Catalog
 * @since       __DEPLOY_VERSION__
 */
class RedshopTableStockroom extends RedshopTable
{
	/**
	 * The table name without prefix.
	 *
	 * @var string
	 */
	protected $_tableName = 'redshop_stockroom';

	/**
	 * Called before store(). Overriden to send isNew to plugins.
	 *
	 * @param   boolean  $updateNulls  True to update null values as well.
	 * @param   boolean  $isNew        True if we are adding a new item.
	 * @param   mixed    $oldItem      null for new items | JTable otherwise
	 *
	 * @return  boolean  True on success.
	 * @throws  Exception
	 */
	protected function beforeStore($updateNulls = false, $isNew = false, $oldItem = null)
	{
		if (!parent::beforeStore($updateNulls, $isNew, $oldItem)) {
			return false;
		}

		if ($this->delivery_time == 'Weeks') {
			$this->min_del_time *= 7;
			$this->max_del_time *= 7;
		}

		return true;
	}

	/**
	 * Delete one or more registers
	 *
	 * @param   string|array  $pk  Array of ids or ids comma separated
	 *
	 * @return  boolean  Deleted successfuly?
	 */
	public function doDelete($pk = null)
	{
		$db = JFactory::getDbo();
		$stockIds = $pk;

		if (!is_array($stockIds)) {
			$stockIds = array($stockIds);
		}

		foreach ($stockIds as $stockId)
		{
			//Delete stock of product stock
			$queryProduct = $db->getQuery(true)
				->delete($db->qn('#__redshop_product_stockroom_xref'))
				->where($db->qn('stockroom_id') . ' = ' . $db->q($stockId));

			if (!$db->setQuery($queryProduct)->execute()) {
				$this->setError($db->getErrorMsg());
				return false;
			}

			//Delete stock of product stock
			$queryProductAttribute = $db->getQuery(true)
				->delete($db->qn('#__redshop_product_attribute_stockroom_xref'))
				->where($db->qn('stockroom_id') . ' = ' . $db->q($stockId));

			if (!$db->setQuery($queryProductAttribute)->execute()) {
				$this->setError($db->getErrorMsg());
				return false;
			}
		}

		return parent::doDelete($pk);
	}
}
