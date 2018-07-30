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
 * Product Entity
 *
 * @package     Redshop.Library
 * @subpackage  Entity
 * @since       2.0.6
 */
class RedshopEntityProduct extends RedshopEntity
{
	use \Redshop\Entity\Traits\Product\Categories;
	use \Redshop\Entity\Traits\Product\Media;

	/**
	 * @var   RedshopEntitiesCollection  Collections of related products
	 */
	protected $relatedProducts = null;

	/**
	 * @var   RedshopEntitiesCollection  Collections of child products
	 */
	protected $childProducts = null;

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
	 * @param   boolean $reload Force reload even it's cached
	 *
	 * @return  RedshopEntitiesCollection
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
	 * @param   boolean $reload Force reload even it's cached
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

		$this->relatedProducts = new RedshopEntitiesCollection;

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
	 * @param   integer $fieldId Field id
	 * @param   string  $value   Field value
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
}
