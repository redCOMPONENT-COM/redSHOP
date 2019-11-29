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
 * Discount Product Entity
 *
 * @package     Redshop.Library
 * @subpackage  Entity
 * @since       2.1.0
 */
class RedshopEntityDiscount_Product extends RedshopEntity
{
	/**
	 * @var RedshopEntitiesCollection
	 */
	protected $shopperGroups;

	/**
	 * @var RedshopEntitiesCollection
	 */
	protected $categories;

	/**
	 * Method for get shopper groups associate with this discount
	 *
	 * @return  RedshopEntitiesCollection
	 *
	 * @since   2.1.0
	 */
	public function getShopperGroups()
	{
		if (null === $this->shopperGroups)
		{
			$this->loadShopperGroups();
		}

		return $this->shopperGroups;
	}

	/**
	 * Method for get categories associate with this discount
	 *
	 * @return  RedshopEntitiesCollection
	 *
	 * @since   2.1.0
	 */
	public function getCategories()
	{
		if (null === $this->categories)
		{
			$this->loadCategories();
		}

		return $this->categories;
	}

	/**
	 * Method for load categories associate with this discount
	 *
	 * @return  self
	 *
	 * @since   2.1.0
	 */
	protected function loadCategories()
	{
		$this->categories = new RedshopEntitiesCollection;

		if (!$this->hasId() || empty($this->get('category_ids')))
		{
			return $this;
		}

		$categoryIds = explode(',', $this->get('category_ids'));

		foreach ($categoryIds as $categoryId)
		{
			$this->categories->add(RedshopEntityCategory::getInstance($categoryId));
		}

		return $this;
	}

	/**
	 * Method for load shopper groups associate with this discount
	 *
	 * @return  self
	 *
	 * @since   2.1.0
	 */
	protected function loadShopperGroups()
	{
		$this->shopperGroups = new RedshopEntitiesCollection;

		if (!$this->hasId())
		{
			return $this;
		}

		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select($db->qn('shopper_group_id'))
			->from($db->qn('#__redshop_discount_product_shoppers'))
			->where($db->qn('discount_product_id') . ' = ' . $this->getId());

		$result = $db->setQuery($query)->loadColumn();

		if (empty($result))
		{
			return $this;
		}

		foreach ($result as $shopperGroupId)
		{
			$this->shopperGroups->add(RedshopEntityShopper_Group::getInstance($shopperGroupId));
		}

		return $this;
	}
}
