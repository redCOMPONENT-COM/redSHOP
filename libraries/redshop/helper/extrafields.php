<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Class Redshop Helper ExtraFields
 *
 * @since  1.6.1
 */
class RedshopHelperExtraFields
{
	/**
	 * List of fields data
	 *
	 * @var  array
	 */
	protected static $fieldsData = array();

	/**
	 * All fields information
	 *
	 * @var  array
	 */
	protected static $fieldsName = array();

	/**
	 * Get list of fields.
	 *
	 * @param   integer  $published   Published Status which needs to be get. Default -1 will ignore any status.
	 * @param   integer  $limitStart  Set limit start
	 * @param   integer  $limit       Set limit
	 *
	 * @return  array    Array of all the available fields based on arguments.
	 */
	public static function getList($published = -1, $limitStart = 0, $limit = 0)
	{
		$db = JFactory::getDbo();

		if (!empty(self::$fieldsName))
		{
			return self::$fieldsName;
		}

		$query = $db->getQuery(true)
					->select('*')
					->from($db->qn('#__redshop_fields'));

		if ($published >= 0)
		{
			$query->where($db->qn('published') . ' = ' . (int) $published);
		}

		self::$fieldsName = $db->setQuery($query, $limitStart, $limit)->loadObjectList('field_name');

		return self::$fieldsName;
	}

	/**
	 * Get field information from field name.
	 *
	 * @param   string  $name  Field name prefixed with `rs_`
	 *
	 * @return  object|null    Field information object otherwise null.
	 */
	public static function getField($name)
	{
		$fields = self::getList();

		if (array_key_exists($name, $fields))
		{
			return $fields[$name];
		}

		return null;
	}

	/**
	 * Get Section Field Data List
	 *
	 * @param   int  $name         Name of the field - Typically contains `rs_` prefix.
	 * @param   int  $section      Section id of the field.
	 * @param   int  $sectionItem  Section item id
	 *
	 * @return mixed|null
	 */
	public static function getDataByName($name, $section, $sectionItem)
	{
		// Get Field id
		$fieldId = self::getField($name)->field_id;

		return self::getData($fieldId, $section, $sectionItem);
	}

	/**
	 * Get Section Field Data List
	 *
	 * @param   int  $fieldId      Field id
	 * @param   int  $section      Section id of the field.
	 * @param   int  $sectionItem  Section item id
	 *
	 * @return  mixed|null
	 */
	public static function getData($fieldId, $section, $sectionItem)
	{
		$key = $fieldId . '.' . $section . '.' . $sectionItem;

		if (array_key_exists($key, self::$fieldsData))
		{
			return self::$fieldsData[$key];
		}

		// Init null.
		self::$fieldsData[$key] = null;

		if ($section == 1)
		{
			$product = Redshop::product((int) $sectionItem);

			if ($product && isset($product->extraFields[$fieldId]))
			{
				self::$fieldsData[$key] = $product->extraFields[$fieldId];
			}
		}
		else
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select(array('fd.*', 'f.field_title'))
				->from($db->qn('#__redshop_fields_data', 'fd'))
				->leftJoin($db->qn('#__redshop_fields', 'f') . ' ON fd.fieldid = f.field_id')
				->where('fd.itemid = ' . (int) $sectionItem)
				->where('fd.fieldid = ' . (int) $fieldId)
				->where('fd.section = ' . $db->quote($section));
			self::$fieldsData[$key] = $db->setQuery($query)->loadObject();
		}

		return self::$fieldsData[$key];
	}
}
