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
	 * @param   string $name Main name of the Table. Example: Article for ContentTableArticle
	 *
	 * @return  TableProduct_Detail|boolean
	 */
	public function getTable($name = null)
	{
		return JTable::getInstance('Product_Detail', 'Table');
	}

	/**
	 * Default loading is trying to use the associated table
	 *
	 * @param   string $key      Field name used as key
	 * @param   string $keyValue Value used if it's not the $this->id property of the instance
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
	 *
	 * @return  array<object>
	 *
	 * @since   2.0.7
	 */
	public function getMediaDetail()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from($db->quoteName('#__redshop_media'))
			->where($db->quoteName('section_id') . ' = ' . (int) $this->getId())
			->where($db->quoteName('media_section') . ' = ' . $db->quote('product'));

		return $db->setQuery($query)->loadObjectlist();
	}

	/**
	 *
	 * @return  array<string>
	 *
	 * @since   2.0.7
	 */
	public function getCategories()
	{
		$db    = $this->getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('name'))
			->from($db->qn('#__redshop_product_category_xref', 'pcx'))
			->leftjoin($db->qn('#__redshop_category', 'c') . ' ON ' . $db->qn('c.id') . ' = ' . $db->qn('pcx.category_id'))
			->where($db->qn('pcx.product_id') . ' = ' . $db->q((int) $this->getId()))
			->order($db->qn('c.name'));

		return $db->setQuery($query)->loadObjectlist();
	}

	/**
	 * Get array object of custom fields' data
	 *
	 * @return  array<object>|null
	 *
	 * @TODO    Use section const instead fixed value.
	 * @since   2.0.7
	 */
	public function getCustomfieldsData()
	{
		$db    = $this->getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from($db->quoteName('#__redshop_fields_data', 'fd'))
			->innerJoin($db->quoteName('#__redshop_fields', 'f') . ' ON ' . $db->quoteName('fd.fieldid') . ' = ' . $db->quoteName('f.field_id'))
			->where($db->quoteName('fd.section') . ' = 1')
			->where($db->quoteName('fd.itemid') . ' = ' . (int) $this->getId());

		return $db->setQuery($query)->loadObjectlist();
	}
}
