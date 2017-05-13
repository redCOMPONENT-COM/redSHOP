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
	 * @var   RedshopEntityOrder_Payment
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected $payment;

	/**
	 * @var    RedshopEntitiesCollection
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected $users;

	/**
	 * @var   RedshopEntityOrder_User
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected $billing;

	/**
	 * @var   RedshopEntityOrder_User
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected $shipping;

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
	 * @return   RedshopEntitiesCollection   RedshopEntitiesCollection if success. Null otherwise.
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
	 * Method for get payment for this order
	 *
	 * @return   RedshopEntityOrder_Payment   Payment data if success. Null otherwise.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getPayment()
	{
		if (!$this->hasId())
		{
			return null;
		}

		if (null === $this->payment)
		{
			$this->loadPayment();
		}

		return $this->payment;
	}

	/**
	 * Method for get users of this order
	 *
	 * @return   RedshopEntitiesCollection   Collection of users if success. Null otherwise.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getUsers()
	{
		if (!$this->hasId())
		{
			return null;
		}

		if (null === $this->users)
		{
			$this->loadUsers();
		}

		return $this->users;
	}

	/**
	 * Method for get billing information of this order
	 *
	 * @return   RedshopEntityOrder_User   User infor if success. Null otherwise.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getBilling()
	{
		if (!$this->hasId())
		{
			return null;
		}

		if (null === $this->billing)
		{
			$this->loadBilling();
		}

		return $this->billing;
	}

	/**
	 * Method for get shipping information of this order
	 *
	 * @return   RedshopEntityOrder_User   User infor if success. Null otherwise.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getShipping()
	{
		if (!$this->hasId())
		{
			return null;
		}

		if (null === $this->shipping)
		{
			$this->loadShipping();
		}

		return $this->shipping;
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

	/**
	 * Method for load payment of this order
	 *
	 * @return  self
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function loadPayment()
	{
		if (!$this->hasId())
		{
			return $this;
		}

		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_order_payment'))
			->where($db->qn('order_id') . ' = ' . (int) $this->getId());
		$result = $db->setQuery($query)->loadObject();

		if (empty($result))
		{
			return $this;
		}

		$this->payment = RedshopEntityOrder_Payment::getInstance($result->payment_order_id)->bind($result);
		$this->payment->loadPlugin();

		return $this;
	}

	/**
	 * Method for load users of this order
	 *
	 * @return  self
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function loadUsers()
	{
		if (!$this->hasId())
		{
			return $this;
		}

		$this->users = new RedshopEntitiesCollection;

		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_order_users_info'))
			->where($db->qn('order_id') . ' = ' . (int) $this->getId());
		$results = $db->setQuery($query)->loadObjectList();

		if (empty($results))
		{
			return $this;
		}

		foreach ($results as $result)
		{
			$entity = RedshopEntityOrder_User::getInstance($result->order_info_id)->bind($result)->loadExtraFields();

			$this->users->add($entity);
		}

		return $this;
	}

	/**
	 * Method for load billing user information of this order
	 *
	 * @return  self
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function loadBilling()
	{
		if (!$this->hasId())
		{
			return $this;
		}

		$users = $this->getUsers();

		if ($users->isEmpty())
		{
			return $this;
		}

		foreach ($users as $user)
		{
			if ($user->get('address_type') == 'BT')
			{
				$this->billing = $user;

				return $this;
			}
		}

		return $this;
	}

	/**
	 * Method for load shipping user information of this order
	 *
	 * @return  self
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function loadShipping()
	{
		if (!$this->hasId())
		{
			return $this;
		}

		$users = $this->getUsers();

		if ($users->isEmpty())
		{
			return $this;
		}

		foreach ($users as $user)
		{
			if ($user->get('address_type') == 'ST')
			{
				$this->shipping = $user;

				return $this;
			}
		}

		return $this;
	}
}
