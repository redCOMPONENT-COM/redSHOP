<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_redmanufacturer
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/redshop.cfg.php';
JLoader::import('redshop.library');
JLoader::load('RedshopHelperAdminConfiguration');
$Redconfiguration = new Redconfiguration;
$Redconfiguration->defineDynamicVars();
JLoader::load('RedshopHelperAdminCategory');
JLoader::load('RedshopHelperProduct');
JLoader::load('RedshopHelperAdminImages');

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
	 * @param   \Joomla\Registry\Registry  &$params  module parameters
	 *
	 * @return  mixed
	 */
	public static function getList(&$params)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('m.media_name, ma.manufacturer_name, ma.manufacturer_id')
			->from($db->qn('#__redshop_manufacturer', 'ma'))
			->leftJoin($db->qn('#__redshop_media', 'm') . ' ON m.section_id = ma.manufacturer_id')
			->where('m.media_section = ' . $db->q('manufacturer'))
			->where('m.published = 1')
			->where('ma.published = 1');

		return $db->setQuery($query, 0, (int) $params->get('NumberOfProducts', 5))->loadObjectList();
	}
}
