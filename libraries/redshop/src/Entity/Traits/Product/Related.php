<?php
/**
 * @package     Redshop\Entity\Traits\Product
 * @subpackage  Related
 *
 * @copyright   Copyright (C) 2012 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

namespace Redshop\Entity\Traits\Product;

/**
 * Trait Related
 * @package Redshop\Entity\Traits\Product
 *
 * @since   2.1.0
 */
trait Related
{
	/**
	 * @param   boolean $reload Force reload even it's cached
	 *
	 * @return  \RedshopEntitiesCollection
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

		$db    = \JFactory::getDbo();
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
	 * Check if we have an identifier loaded
	 *
	 * @return  boolean
	 * @since   2.1.0
	 */
	abstract public function hasId();

	/**
	 * Get the id
	 *
	 * @return  integer | null
	 * @since   2.1.0
	 */
	abstract public function getId();
}
