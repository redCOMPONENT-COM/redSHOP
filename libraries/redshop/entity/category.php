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
	 * @var    RedshopEntitiesCollection
	 *
	 * @since  2.1.0
	 */
	protected $media;

	/**
	 * Method for get product count of category
	 *
	 * @return  integer
	 */
	public function productCount()
	{
		if (is_null($this->productCount))
		{
			$db    = JFactory::getDbo();
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
	public function getProducts($includeProductsFromSubCat = false)
	{
		if (null === $this->products)
		{
			$this->loadProducts($includeProductsFromSubCat);
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
	protected function loadProducts($includeProductsFromSubCat = false)
	{
		if (!$this->hasId())
		{
			return $this;
		}

		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select('DISTINCT p.product_id AS id')
			->select('p.*')
			->from($db->qn('#__redshop_product_category_xref', 'pcx'))
			->leftJoin($db->qn('#__redshop_product', 'p') . ' ON ' . $db->qn('p.product_id') . ' = ' . $db->qn('pcx.product_id'))
			->leftJoin($db->qn('#__redshop_category', 'c') . ' ON ' . $db->qn('pcx.category_id') . ' = ' . $db->qn('c.id'))
			->where($db->qn('p.published') . ' = 1')
			->order($db->qn('p.product_id') . ' DESC');

		$categories = array();

		if ($includeProductsFromSubCat)
		{
			$childs = $this->getChildCategories();

			if ($childs && !$childs->isEmpty())
			{
				$categories = $childs->toFieldArray('id');
			}
		}

		$categories[] = (int) $this->getId();

		$query->where($db->qn('c.id') . ' IN (' . implode(',', $categories) . ')');

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

		$db    = JFactory::getDbo();
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
			$this->childCategories->add(self::getInstance($categoryId));
		}

		return $this;
	}

	/**
	 * Method for get medias of current category
	 *
	 * @return  RedshopEntitiesCollection
	 *
	 * @since   2.1.0
	 */
	public function getMedia()
	{
		if (null === $this->media)
		{
			$this->loadMedia();
		}

		return $this->media;
	}

	/**
	 * Method for load medias
	 *
	 * @return  self
	 *
	 * @since   2.1.0
	 */
	protected function loadMedia()
	{
		$this->media = new RedshopEntitiesCollection;

		if (!$this->hasId())
		{
			return $this;
		}

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('media_id')
			->from($db->qn('#__redshop_media'))
			->where($db->qn('media_section') . ' = ' . $db->quote('category'))
			->where($db->qn('section_id') . ' = ' . $db->quote($this->getId()));

		$results = $db->setQuery($query)->loadColumn();

		if (empty($results))
		{
			return $this;
		}

		foreach ($results as $mediaId)
		{
			$this->media->add(RedshopEntityMedia::getInstance($mediaId));
		}

		return $this;
	}

	/**
	 * Try to directly save the entity using the associated table
	 *
	 * @param   mixed  $item  Object / Array to save. Null = try to store current item
	 *
	 * @return  integer|boolean  The item id
	 *
	 * @since   1.0
	 */
	public function save($item = null)
	{
		if (!$this->processBeforeSaving($item))
		{
			return false;
		}

		if (null === $item)
		{
			$item = $this->getItem();
		}

		if (!$item)
		{
			JLog::add("Nothing to save", JLog::ERROR, 'entity');

			return false;
		}

		try
		{
			/** @var RedshopTableCategory $table */
			$table = $this->getTable();
		}
		catch (\Exception $e)
		{
			JLog::add("Table for instance " . $this->getInstanceName() . " could not be loaded", JLog::ERROR, 'entity');

			return false;
		}

		$item = Joomla\Utilities\ArrayHelper::fromObject($item);

		$table->setLocation(isset($item['parent_id']) ? $item['parent_id'] : RedshopHelperCategory::getRootId(), 'last-child');

		if (!$table->save($item))
		{
			JLog::add($table->getError(), JLog::ERROR, 'entity');

			return false;
		}

		// Force entity reload / save to cache
		static::clearInstance($this->id);
		static::getInstance($this->id)->loadFromTable($table);

		$this->processAfterSaving($table);

		return (int) $table->{$table->getKeyName()};
	}
}
