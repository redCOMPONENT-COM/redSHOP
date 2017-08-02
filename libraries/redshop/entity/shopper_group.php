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
 * Shopper Group Entity
 *
 * @package     Redshop.Library
 * @subpackage  Entity
 * @since       2.0.6
 */
class RedshopEntityShopper_Group extends RedshopEntity
{
	/**
	 * @var    RedshopEntitiesCollection
	 *
	 * @since   2.0.6
	 */
	protected $discounts;

	/**
	 * Get the associated table
	 *
	 * @param   string  $name  Main name of the Table. Example: Article for ContentTableArticle
	 *
	 * @return  RedshopTable
	 */
	public function getTable($name = null)
	{
		return JTable::getInstance('Shopper_Group_Detail', 'Table');
	}

	/**
	 * Method for get discounts of this shopper group
	 *
	 * @return   RedshopEntitiesCollection   RedshopEntitiesCollection if success. Null otherwise.
	 *
	 * @since   2.0.6
	 */
	public function getDiscounts()
	{
		if (!$this->hasId())
		{
			return null;
		}

		if (null === $this->discounts)
		{
			$this->loadDiscounts();
		}

		return $this->discounts;
	}

	/**
	 * Method for load discounts for this shopper group
	 *
	 * @return  self
	 *
	 * @since   2.0.6
	 */
	protected function loadDiscounts()
	{
		if (!$this->hasId())
		{
			return $this;
		}

		$this->discounts = new RedshopEntitiesCollection;

		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('discount_id'))
			->from($db->qn('#__redshop_discount_shoppers'))
			->where($db->qn('shopper_group_id') . ' = ' . $this->getId());
		$discounts = $db->setQuery($query)->loadColumn();

		if (empty($discounts))
		{
			return $this;
		}

		foreach ($discounts as $discountId)
		{
			$this->discounts->add(RedshopEntityDiscount::getInstance($discountId));
		}

		return $this;
	}
}
