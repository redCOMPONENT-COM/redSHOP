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
 * Tax Rate Entity
 *
 * @package     Redshop.Library
 * @subpackage  Entity
 * @since       3.0.2
 */
class RedshopEntityTax_Rate extends RedshopEntity
{
	/**
	 * Method for get shopper groups associate with this tax rate
	 *
	 * @return  RedshopEntitiesCollection
	 *
	 * @since   3.0.2
	 */
	public function getShopperGroups()
	{
		if (null === $this->shopperGroups) {
			$this->loadShopperGroups();
		}

		return $this->shopperGroups;
	}

	/**
	 * Method for load shopper groups associate with this tax rate
	 *
	 * @return  self
	 *
	 * @since   3.0.2
	 */
	protected function loadShopperGroups()
	{
		$this->shopperGroups = new RedshopEntitiesCollection;

		if (!$this->hasId()) {
			return $this;
		}

		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select($db->qn('shopper_group_id'))
			->from($db->qn('#__redshop_tax_shoppergroup_xref'))
			->where($db->qn('tax_rate_id') . ' = ' . $this->getId());

		$result = $db->setQuery($query)->loadColumn();

		if (empty($result)) {
			return $this;
		}

		foreach ($result as $shopperGroupId) {
			$this->shopperGroups->add(RedshopEntityShopper_Group::getInstance($shopperGroupId));
		}

		return $this;
	}
}
