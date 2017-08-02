<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Class Model Export
 *
 * @since  2.0.3
 */
class RedshopModelExport extends RedshopModel
{
	/**
	 * Method for get all available exports features.
	 *
	 * @return  array  List of available exports.
	 *
	 * @since  2.0.3
	 */
	public function getExports()
	{
		$plugins = JPluginHelper::getPlugin('redshop_export');

		if (empty($plugins))
		{
			return array();
		}

		asort($plugins);

		$language = JFactory::getLanguage();

		foreach ($plugins as $plugin)
		{
			$language->load('plg_redshop_export_' . $plugin->name, JPATH_SITE . '/plugins/redshop_export/' . $plugin->name);
		}

		return $plugins;
	}
}
