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
 * Class Redshop Helper for Manufacturer
 *
 * @since  2.0.0.3
 */
class RedshopHelperManufacturer
{
	/**
	 * Get media id
	 *
	 * @param   int  $mid  Media section ID
	 *
	 * @return  object
	 */
	public static function getMedia($mid)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from($db->quoteName('#__redshop_media'))
			->where($db->quoteName('media_section') . '=' . $db->quote('manufacturer'))
			->where($db->quoteName('section_id') . '=' . (int) $mid);

		$db->setQuery($query);

		return $db->loadObject();
	}

	/**
	 * Get array of template by section
	 *
	 * @return bool|array
	 *
	 * @since  2.0.0.3
	 */
	public static function getTemplates()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query
			->select($db->quoteName('template_id', 'value'))
			->select($db->quoteName('template_name', 'text'))
			->from($db->quoteName('#__redshop_template'))
			->where($db->quoteName('template_section') . '=' . $db->quote('manufacturer_products'))
			->where($db->quoteName('published') . '=' . (int) 1);

		$db->setQuery($query);

		return $db->loadObjectList();
	}

	/**
	 * Get extra fields
	 *
	 * @param   int  $manufacturerId  Manufacturer ID
	 * @param   int  $section         Section
	 *
	 * @return  mixed|string
	 *
	 * @since   2.0.0.3
	 */
	public static function getExtraFields($manufacturerId, $section = 10)
	{
		$field = extra_field::getInstance();

		return $field->list_all_field($section, $manufacturerId);
	}
}
