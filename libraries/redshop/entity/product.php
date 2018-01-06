<?php
/**
 * @package     Redshop.Library
 * @subpackage  Entity
 *
 * @copyright   Copyright (C) 2012 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

use Redshop\Entity\AbstractEntity;
use Redshop\Entity\EntityCollection;

defined('_JEXEC') or die;

/**
 * Product Entity
 *
 * @package     Redshop.Library
 * @subpackage  Entity
 * @since       2.0.6
 */
class RedshopEntityProduct extends AbstractEntity
{
	/**
	 * @var   EntityCollection  Collections of categories
	 */
	protected $categories = null;

	/**
	 * @var   EntityCollection  Collections of related products
	 */
	protected $relatedProducts = null;

	/**
	 * @var   RedshopEntitiesCollection  Collections of child products
	 */
	protected $childProducts = null;

	/**
	 * @var    RedshopEntitiesCollection
	 *
	 * @since  2.1.0
	 */
	protected $media;

	/**
	 * Get the associated table
	 *
	 * @param   string $name Main name of the Table. Example: Article for ContentTableArticle
	 *
	 * @return  JTable
	 */
	public function getTable($name = null)
	{
		return JTable::getInstance('Product_Detail', 'Table');
	}

	/**
	 * @param   boolean  $reload  Force reload even it's cached
	 *
	 * @return  EntityCollection
	 *
	 * @since   2.1.0
	 */
	public function getCategories($reload = false)
	{
		if (null === $this->categories || $reload === true)
		{
			$this->loadCategories();
		}

		return $this->categories;
	}

	/**
	 * @param   boolean  $reload  Force reload even it's cached
	 *
	 * @return  EntityCollection
	 *
	 * @since   2.1.0
	 */
	public function getRelatedProducts($reload = false)
	{
		if (null === $this->relatedProducts || $reload === true)
		{
			$this->loadRelated();
		}

		return $this->relatedProducts;
	}

	/**
	 * Method for get child products
	 *
	 * @param   boolean  $reload  Force reload even it's cached
	 *
	 * @return  RedshopEntitiesCollection
	 *
	 * @since   2.1.0
	 */
	public function getChildProducts($reload = false)
	{
		if (null === $this->childProducts || $reload === true)
		{
			$this->loadChild();
		}

		return $this->childProducts;
	}

	/**
	 * Method for set categories to this product
	 *
	 * @param   array    $ids             Array of categories' ids
	 * @param   boolean  $removeAssigned  Remove all assigned categories
	 *
	 * @return  mixed                     A database cursor resource on success, boolean false on failure.
	 */
	public function setCategories($ids, $removeAssigned = false)
	{
		// Merge with assigned categories
		if ($removeAssigned === false)
		{
			$categoryIds = array_merge($this->getCategories()->ids(), $ids);
		}
		else
		{
			// Or just reset it with new ids
			$categoryIds = $ids;
		}

		$categoryIds = array_unique($categoryIds);

		$db = JFactory::getDbo();

		// Delete old assigned categories
		$query = $db->getQuery(true)
			->delete($db->qn('#__redshop_product_category_xref'))
			->where($db->qn('product_id') . ' = ' . (int) $this->get('product_id'));
		$db->setQuery($query)->execute();

		// Assign new category
		$query->clear()
			->insert($db->qn('#__redshop_product_category_xref'))
			->columns($db->qn(array('category_id', 'product_id')));

		foreach ($categoryIds as $id)
		{
			$query->values((int) $id . ' , ' . (int) $this->get('product_id'));
		}

		if (!$db->setQuery($query)->execute())
		{
			return false;
		}

		// Reload new categories for this product
		$this->loadCategories();

		return true;
	}

	/**
	 * Method for check if this product exist in category.
	 *
	 * @param   integer  $id  ID of category
	 *
	 * @return  boolean
	 */
	public function inCategory($id)
	{
		return in_array($id, is_array($this->getCategories()->ids()) ? $this->getCategories()->ids() : array());
	}

	/**
	 * Method for load child categories
	 *
	 * @return  self
	 *
	 * @since   2.1.0
	 */
	protected function loadCategories()
	{
		if (!$this->hasId())
		{
			return $this;
		}

		$this->categories = new EntityCollection;

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('category_id'))
			->from($db->qn('#__redshop_product_category_xref'))
			->where($db->qn('product_id') . ' = ' . (int) $this->getId());

		$results = $db->setQuery($query)->loadColumn();

		if (empty($results))
		{
			return $this;
		}

		foreach ($results as $categoryId)
		{
			$this->categories->add(RedshopEntityCategory::getInstance($categoryId));
		}

		return $this;
	}

	/**
	 * Method to get related products
	 *
	 * @return  self
	 *
	 * @since   2.1.0
	 */
	protected function loadRelated()
	{
		if (!$this->hasId())
		{
			return $this;
		}

		$this->relatedProducts = new EntityCollection;

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select($db->quoteName('related_id'))
			->from($db->quoteName('#__redshop_product_related'))
			->where($db->quoteName('product_id') . ' = ' . (int) $this->getId());

		$productIds = $db->setQuery($query)->loadColumn();

		foreach ($productIds as $productId)
		{
			$this->relatedProducts->add(self::getInstance($productId));
		}

		return $this;
	}

	/**
	 * Method to load child product
	 *
	 * @return  self
	 *
	 * @since   2.1.0
	 */
	protected function loadChild()
	{
		if (!$this->hasId())
		{
			return $this;
		}

		$this->childProducts = new RedshopEntitiesCollection;

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select($db->quoteName('product_id'))
			->from($db->quoteName('#__redshop_product'))
			->where($db->quoteName('product_parent_id') . ' = ' . (int) $this->getId());

		$productIds = $db->setQuery($query)->loadColumn();

		foreach ($productIds as $productId)
		{
			$this->childProducts->add(self::getInstance($productId));
		}

		return $this;
	}

	/**
	 * Assign a product with a custom field
	 *
	 * @param   integer  $fieldId  Field id
	 * @param   string   $value    Field value
	 *
	 * @return boolean
	 */
	public function assignCustomField($fieldId, $value)
	{
		// Try to load this custom field data
		/** @var RedshopEntityField_Data $entity */
		$entity = RedshopEntityField_Data::getInstance()->loadItemByArray(
			array
			(
				'fieldid' => $fieldId,
				'itemid'  => $this->id,
				// Product section
				'section' => 1
			)
		);

		// This custom field data is not linked with this product than create it
		if ($entity->hasId())
		{
			return true;
		}

		return (boolean) $entity->save(
			array
			(
				'fieldid'  => $fieldId,
				'data_txt' => $value,
				'itemid'   => $this->id,
				'section'  => 1
			)
		);
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
			->where($db->qn('media_section') . ' = ' . $db->quote('product'))
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
}
