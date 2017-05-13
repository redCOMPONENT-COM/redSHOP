<?php
/**
 * @package     Redshop.Library
 * @subpackage  Entity
 *
 * @copyright   Copyright (C) 2012 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

/**
 * Order Entity
 *
 * @package     Redshop.Library
 * @subpackage  Entity
 * @since       __DEPLOY_VERSION__
 */
class RedshopEntityOrder extends RedshopEntity
{
	/**
	 * @var   RedshopEntitiesCollection
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected $orderItems;

	/**
	 * Get the associated table
	 *
	 * @param   string  $name  Main name of the Table. Example: Article for ContentTableArticle
	 *
	 * @return  RedshopTable
	 */
	public function getTable($name = null)
	{
		return JTable::getInstance('Order_Detail', 'Table');
	}

	/**
	 * Default loading is trying to use the associated table
	 *
	 * @param   string  $key       Field name used as key
	 * @param   string  $keyValue  Value used if it's not the $this->id property of the instance
	 *
	 * @return  self
	 */
	public function loadItem($key = 'order_id', $keyValue = null)
	{
		if ($key == 'order_id' && !$this->hasId())
		{
			return $this;
		}

		if (($table = $this->getTable()) && $table->load(array($key => ($key == 'order_id' ? $this->id : $keyValue))))
		{
			$this->loadFromTable($table);
		}

		return $this;
	}

	/**
	 * Method for get order items for this order
	 *
	 * @return   mixed   RedshopEntitiesCollection if success. Null otherwise.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getOrderItems()
	{
		if (!$this->hasId())
		{
			return null;
		}

		if (null === $this->orderItems)
		{
			$this->loadOrderItems();
		}

		return $this->orderItems;
	}

	/**
	 * Method for load order items for this order
	 *
	 * @return  self
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function loadOrderItems()
	{
		if (!$this->hasId())
		{
			return $this;
		}

		$this->orderItems = new RedshopEntitiesCollection;

		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_order_item'))
			->where($db->qn('order_id') . ' = ' . $this->getId());
		$orderItems = $db->setQuery($query)->loadObjectList();

		if (empty($orderItems))
		{
			return $this;
		}

		foreach ($orderItems as $orderItem)
		{
			$entity = RedshopEntityOrder_Item::getInstance($orderItem->order_item_id);
			$entity->bind($orderItem);

			$this->orderItems->add($entity);
		}

		return $this;
	}
}
