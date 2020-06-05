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
 * Coupon Entity
 *
 * @package     Redshop.Library
 * @subpackage  Entity
 * @since       2.0.6
 */
class RedshopEntityCoupon extends RedshopEntity
{
	/**
	 * @var  RedshopEntitiesCollection
	 * @since  3.0.2
	 */
	protected $users;

	/**
	 * Method for get products available with this coupon
	 *
	 * @return  RedshopEntitiesCollection
	 *
	 * @since  3.0.2
	 */
	public function getUsers()
	{
		if (null === $this->users) {
			$this->loadUsers();
		}

		return $this->users;
	}

	/**
	 * Method for load products available with this coupon
	 *
	 * @return  self
	 *
	 * @since  3.0.2
	 */
	protected function loadUsers()
	{
		$this->users = new RedshopEntitiesCollection;

		if (!$this->hasId()) {
			return $this;
		}

		$db     = JFactory::getDbo();
		$query  = $db->getQuery(true)
			->select($db->qn('user_id'))
			->from($db->qn('#__redshop_coupon_user_xref'))
			->where($db->qn('coupon_id') . ' = ' . $this->getId());
		$result = $db->setQuery($query)->loadColumn();

		if (empty($result)) {
			return $this;
		}

		foreach ($result as $userId) {
			$this->users->add(RedshopEntityUser::getInstance($userId));
		}

		return $this;
	}
}
