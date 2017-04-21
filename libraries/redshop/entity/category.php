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
 * @since       __DEPLOY_VERSION__
 */
final class RedshopEntityCategory extends RedshopEntity
{
	/**
	 * Product count
	 *
	 * @var  integer
	 */
	protected $productCount;

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
}
