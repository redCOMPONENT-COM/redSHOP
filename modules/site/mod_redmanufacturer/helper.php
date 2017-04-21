<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_redmanufacturer
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

/**
 * Helper for mod_redmanufacturer
 *
 * @since 1.5
 */
abstract class ModRedManufacturerHelper
{

	/**
	 * Retrieve a list of article
	 *
	 * @param   \Joomla\Registry\Registry  &$params  Module parameters
	 *
	 * @return  mixed
	 */
	public static function getList(&$params)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('m.name, ma.manufacturer_name, ma.manufacturer_id')
			->from($db->qn('#__redshop_manufacturer', 'ma'))
			->leftJoin($db->qn('#__redshop_media', 'm') . ' ON m.section_id = ma.manufacturer_id')
			->where('m.section = ' . $db->q('manufacturer'))
			->where('m.published = 1')
			->where('ma.published = 1');
		
		// Ordering
		switch ($params->get('order_by', 0))
		{
			case '1':
				$query->order($db->quoteName('ma') . '.' . $db->quoteName('ordering') . ' ASC');
				break;
			case '2':
				$query->order($db->quoteName('ma') . '.' . $db->quoteName('ordering') . ' DESC');
				break;
			default:
				$query->order($db->quoteName('ma') . '.' . $db->quoteName('manufacturer_id') . ' ASC');
				break;
		}

		return $db->setQuery($query, 0, (int) $params->get('NumberOfProducts', 5))->loadObjectList();
	}
}
