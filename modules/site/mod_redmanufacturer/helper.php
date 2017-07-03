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
 * @since  1.5
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
		$query = $db->getQuery(true);

		$query->select($db->qn(['m.media_name', 'ma.manufacturer_name', 'ma.manufacturer_id']))
			->from($db->qn('#__redshop_manufacturer', 'ma'))
			->leftJoin($db->qn('#__redshop_media', 'm') . ' ON ' . $db->qn('m.section_id') . ' = ' . $db->qn('ma.manufacturer_id'))
			->where($db->qn('m.media_section') . ' = ' . $db->q('manufacturer'))
			->where($db->qn('m.published') . ' = 1')
			->where($db->qn('ma.published') . ' = 1');

		// Ordering
		switch ($params->get('order_by', 0))
		{
			case '1':
				$query->order($db->qn('ma.ordering') . ' ASC');
				break;
			case '2':
				$query->order($db->qn('ma.ordering') . ' DESC');
				break;
			default:
				$query->order($db->qn('ma.manufacturer_id') . ' ASC');
				break;
		}

		$limit = (int) $params->get('NumberOfProducts', 5);

		return $db->setQuery($query, 0, $limit)->loadObjectList();
	}
}
