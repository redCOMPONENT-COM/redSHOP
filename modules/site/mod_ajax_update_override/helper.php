<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_ajax_update_override
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class ModAjaxUpdateOverrideHelper
{

	/**
	 * update helper class name in redSHOP override files
	 * URL to execute: index.php?option=com_ajax&module=ajax_update_override&format=debug&method=updateOverrideTemplate
	 *
	 * @return  void
	 */
	public static function updateOverrideTemplateAjax()
	{
		$app = JFactory::getApplication();
		$dir = JPATH_SITE . "/templates/";
		$files = scandir($dir);
		$files = array_diff(scandir($dir), array('.', '..'));
		$templates = array();

		foreach ($files as $key => $value)
		{
			if (!is_file($dir . $value))
			{
				$templates[$dir . $value] = array_diff(scandir($dir . $value), array('.', '..'));
			}
		}

		$override = array();

		foreach ($templates as $key => $value)
		{
			foreach ($value as $name)
			{
				if (!is_file($key . '/' . $name))
				{
					$override[$key . '/html'] = array_diff(scandir($key . '/html'), array('.', '..'));
				}
			}
		}

		$overrideFolders = array();
		$overrideLayoutFolders = array();

		foreach ($override as $key => $value)
		{
			foreach ($value as $name)
			{
				if ($name == 'layouts')
				{
					$overrideLayoutFolders[$key . '/' . $name] = array_diff(scandir($key . '/' . $name), array('.', '..'));
				}
				elseif (!is_file($key . '/' . $name) && $name != 'layouts')
				{
					$overrideFolders[$key . '/' . $name] = array_diff(scandir($key . '/' . $name), array('.', '..'));
				}
			}
		}

		$overrideFiles = array();

		foreach ($overrideFolders as $key => $value)
		{
			foreach ($value as $name)
			{
				if (!is_file($key . '/' . $name))
				{
					$overrideFiles[$key . '/' . $name] = array_diff(scandir($key . '/' . $name), array('.', '..'));
				}
				else
				{
					$overrideFiles[$key] = array_diff(scandir($key), array('.', '..'));
				}
			}
		}

		foreach ($overrideLayoutFolders as $key => $value)
		{
			foreach ($value as $name)
			{
				if (!is_file($key . '/' . $name) && $name == 'com_redshop')
				{
					$overrideLayoutFiles[$key . '/' . $name] = array_diff(scandir($key . '/' . $name), array('.', '..'));
				}
			}
		}

		foreach ($overrideLayoutFiles as $key => $value)
		{
			foreach ($value as $name)
			{
				if (!is_file($key . '/' . $name))
				{
					$overrideFiles[$key . '/' . $name] = array_diff(scandir($key . '/' . $name), array('.', '..'));
				}
			}
		}

		$replaceString = array(
				'new producthelper' => 'RedshopSiteProduct::getInstance()',
				'new quotationHelper' => 'quotationHelper::getInstance()',
				'new order_functions' => 'order_functions::getInstance()',
				'new Redconfiguration' => 'Redconfiguration::getInstance()',
				'new Redtemplate' => 'Redtemplate::getInstance()',
				'new extra_field' => 'extra_field::getInstance()',
				'new extraField' => 'RedshopSiteExtraField::getInstance()',
				'new rsCarthelper' => 'RedshopSiteCart::getInstance()',
				'new rsUserhelper' => 'RedshopSiteUser::getInstance()',
				'new rsstockroomhelper' => 'rsstockroomhelper::getInstance()',
				'new redhelper' => 'RedshopSiteHelper::getInstance()',
				'new shipping' => 'shipping::getInstance()'
			);

		if (!empty($overrideFiles))
		{
			foreach ($overrideFiles as $path => $files)
			{
				foreach ($files as $file)
				{
					$content = file_get_contents($path . '/' . $file);

					foreach ($replaceString as $old => $new)
					{
						if (strstr($content, $old))
						{
							$content = str_replace($old, $new, $content);
							file_put_contents($path . '/' . $file, $content);
						}
					}
				}
			}

			return 'Successful';
		}
	}
}
