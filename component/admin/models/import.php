<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Import model
 *
 * @since  2.0.3
 */
class RedshopModelImport extends RedshopModel
{
	/**
	 * Method for get all available imports plugin.
	 *
	 * @return  array  List of available imports.
	 *
	 * @since  2.0.3
	 */
	public function getImports()
	{
		$plugins = JPluginHelper::getPlugin('redshop_import');

		if (empty($plugins))
		{
			return array();
		}

		asort($plugins);

		$language = JFactory::getLanguage();

		foreach ($plugins as $plugin)
		{
			$language->load('plg_redshop_import_' . $plugin->name, JPATH_SITE . '/plugins/redshop_import/' . $plugin->name);
		}

		return $plugins;
	}
}
