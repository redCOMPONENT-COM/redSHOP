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
	/**
	 * Get the associated table
	 *
	 * @param   string  $name  Main name of the Table. Example: Article for ContentTableArticle
	 *
	 * @return  RedshopTable
	 */
	public function getTable($name = null)
	{
		return JTable::getInstance('Product_Detail', 'Table');
	}

	/**
	 * Default loading is trying to use the associated table
	 *
	 * @param   string  $key       Field name used as key
	 * @param   string  $keyValue  Value used if it's not the $this->id property of the instance
	 *
	 * @return  self
	 */
	public function loadItem($key = 'product_id', $keyValue = null)
	{
		if ($key == 'product_id' && !$this->hasId())
		{
			return $this;
		}

		if (($table = $this->getTable()) && $table->load(array($key => ($key == 'product_id' ? $this->id : $keyValue))))
		{
			$this->loadFromTable($table);
		}

		return $this;
	}

	/**
	 * Get all categories linked with this product
	 *
	 * @return  array<object>
	 *
	 * @since   2.0.7
	 */
	public function getCategories()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('p.product_id'))
			->select($db->qn('cx.category_id'))
			->from($db->qn('#__redshop_product', 'p'))
			->leftJoin(
				$db->qn('#__redshop_product_category_xref', 'cx')
				. ' ON ' . $db->qn('p.product_id') . ' = ' . $db->qn('cx.product_id')
			)
			->where($db->qn('p.product_id') . ' = ' . (int) $this->getId());

		return $db->setQuery($query)->loadObjectList();
	}
}
