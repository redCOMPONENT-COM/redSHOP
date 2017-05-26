<?php
/**
 * @package     Redshop.Library
 * @subpackage  Entity
 *
 * @copyright   Copyright (C) 2012 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

/**
 * Category Entity
 *
 * @package     Redshop.Library
 * @subpackage  Entity
 * @since       2.0.6
 */
class RedshopEntityCategory extends RedshopEntity
{
	/**
	 * Product count
	 *
	 * @var  integer
	 */
	protected $productCount;

	/**
	 * Products
	 *
	 * @var  array
	 */
	protected $products;

	/**
	 * @var    RedshopEntitiesCollection
	 *
	 * @since  2.0.6
	 */
	protected $childCategories;

	/**
	 * Method for get product count of category
	 *
	 * @return  integer
	 */
	public function productCount()
	{
		if (is_null($this->productCount))
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select('COUNT(category_id)')
				->from($db->qn('#__redshop_product_category_xref'))
				->where($db->qn('category_id') . ' = ' . $db->quote((int) $this->getId()));

			$this->productCount = $db->setQuery($query)->loadResult();
		}

		return $this->productCount;
	}

	/**
	 * Method for get products of current category
	 *
	 * @return  array
	 *
	 * @since   2.0.6
	 */
	public function getProducts()
	{
		if (null === $this->products)
		{
			$this->loadProducts();
		}

		return $this->products;
	}

	/**
	 * Method for load products
	 *
	 * @return  $this
	 *
	 * @since   2.0.6
	 */
	protected function loadProducts()
	{
		if (!$this->hasId())
		{
			return $this;
		}

		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select($db->qn('p.product_id', 'id'))
			->select('p.*')
			->from($db->qn('#__redshop_product_category_xref', 'pcx'))
			->leftJoin($db->qn('#__redshop_product', 'p') . ' ON ' . $db->qn('p.product_id') . ' = ' . $db->qn('pcx.product_id'))
			->leftJoin($db->qn('#__redshop_category', 'c') . ' ON ' . $db->qn('pcx.category_id') . ' = ' . $db->qn('c.id'))
			->where($db->qn('c.id') . ' = ' . (int) $this->getId())
			->where($db->qn('p.published') . ' = 1');

		$this->products = $db->setQuery($query)->loadObjectList();

		return $this;
	}

	/**
	 * Method for get child categories of current category
	 *
	 * @return  RedshopEntitiesCollection
	 *
	 * @since   2.0.6
	 */
	public function getChildCategories()
	{
		if (null === $this->childCategories)
		{
			$this->loadChildCategories();
		}

		return $this->childCategories;
	}

	/**
	 * Method for load child categories
	 *
	 * @return  self
	 *
	 * @since   2.0.6
	 */
	protected function loadChildCategories()
	{
		if (!$this->hasId())
		{
			return $this;
		}

		$this->childCategories = new RedshopEntitiesCollection;

		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select('id')
			->from($db->qn('#__redshop_category'))
			->where($db->qn('lft') . ' > ' . $this->get('lft'))
			->where($db->qn('rgt') . ' < ' . $this->get('rgt'));
		$results = $db->setQuery($query)->loadColumn();

		if (empty($results))
		{
			return $this;
		}

		foreach ($results as $categoryId)
		{
			$this->childCategories->add(RedshopEntityCategory::getInstance($categoryId));
		}

		return $this;
	}
}
