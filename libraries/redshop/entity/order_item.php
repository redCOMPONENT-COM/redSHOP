<?php
/**
 * @package     Redshop.Library
 * @subpackage  Entity
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

/**
 * Order Item Entity
 *
 * @package     Redshop.Library
 * @subpackage  Entity
 * @since       2.0.6
 */
class RedshopEntityOrder_Item extends RedshopEntity
{
	/**
	 * @var   RedshopEntitiesCollection
	 *
	 * @since   2.0.6
	 */
	protected $accessoryItems;

	/**
	 * Get the associated table
	 *
	 * @param   string  $name  Main name of the Table. Example: Article for ContentTableArticle
	 *
	 * @return  RedshopTable
	 */
	public function getTable($name = null)
	{
		return JTable::getInstance('Order_Item_Detail', 'Table');
	}

	/**
	 * Method for get accessory items for this order item
	 *
	 * @return   RedshopEntitiesCollection   RedshopEntitiesCollection if success. Null otherwise.
	 *
	 * @since   2.0.6
	 */
	public function getAccessoryItems()
	{
		if (!$this->hasId())
		{
			return null;
		}

		if (null === $this->accessoryItems)
		{
			$this->loadAccessoryItems();
		}

		return $this->accessoryItems;
	}

	/**
	 * Method for load accessory items for this order item
	 *
	 * @return  self
	 *
	 * @since   2.0.6
	 */
	protected function loadAccessoryItems()
	{
		if (!$this->hasId())
		{
			return $this;
		}

		$this->accessoryItems = new RedshopEntitiesCollection;

		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_order_acc_item'))
			->where($db->qn('order_item_id') . ' = ' . $this->getId());
		$items = $db->setQuery($query)->loadObjectList();

		if (empty($items))
		{
			return $this;
		}

		foreach ($items as $item)
		{
			$entity = RedshopEntityOrder_Item_Accessory::getInstance($item->order_item_acc_id);

			$entity->bind($item);

			$this->accessoryItems->add($entity);
		}

		return $this;
	}
}
