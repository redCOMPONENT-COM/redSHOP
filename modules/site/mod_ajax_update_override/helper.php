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
		$dir       = JPATH_SITE . "/templates/";
		$codeDir       = JPATH_SITE . "/code/";
		$files     = scandir($dir);
		$files     = array_diff(scandir($dir), array('.', '..'));
		$codeFiles     = scandir($codeDir);
		$codeFiles     = array_diff(scandir($codeDir), array('.', '..'));
		$templates = array();

		foreach ($codeFiles as $key => $value)
		{
			if (is_dir($codeDir . 'administrator/components'))
			{
				$templates[$codeDir . 'administrator/components'] = array_diff(scandir($codeDir . 'administrator/components'), array('.', '..'));
			}

			if (is_dir($codeDir . 'administrator'))
			{
				$templates[$codeDir . 'administrator'] = array_diff(scandir($codeDir . 'administrator'), array('.', '..'));
			}

			if (is_dir($codeDir . 'components'))
			{
				$templates[$codeDir . 'components'] = array_diff(scandir($codeDir . 'components'), array('.', '..'));
			}

			if (is_dir($codeDir))
			{
				$templates[$codeDir] = array_diff(scandir($codeDir), array('.', '..'));
			}
		}

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
					if (is_dir($key . '/com_redshop'))
					{
						$override[$key . '/com_redshop'] = array_diff(scandir($key . '/com_redshop'), array('.', '..'));
					}

					if (is_dir($key . '/html'))
					{
						$override[$key . '/html'] = array_diff(scandir($key . '/html'), array('.', '..'));
					}

					if (is_dir($key . '/code/com_redshop'))
					{
						$override[$key . '/code/com_redshop'] = array_diff(scandir($key . '/code/com_redshop'), array('.', '..'));
					}

					if (is_dir($key . '/code/components/com_redshop'))
					{
						$override[$key . '/code/components/com_redshop'] = array_diff(scandir($key . '/code/components/com_redshop'), array('.', '..'));
					}
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
				'new shipping' => 'shipping::getInstance()',
				'new CurrencyHelper' => 'CurrencyHelper::getInstance()',
				'new statistic' => 'RedshopSiteStatistic::getInstance()',
				'new economic' => 'economic::getInstance()',
				'class producthelper extends producthelperDefault' => 'class RedshopSiteProduct extends RedshopSiteProductDefault',
				'class rsCarthelper extends rsCarthelperDefault' => 'class RedshopSiteCart extends RedshopSiteCartDefault',
				'class extraField extends extraFieldDefault' => 'class RedshopSiteExtraField extends RedshopSiteExtraFieldDefault',
				'class redhelper extends redhelperDefault' => 'class RedshopSiteHelper extends RedshopSiteHelperDefault',
				'class rsUserhelper extends rsUserhelperDefault' => 'class RedshopSiteUser extends RedshopSiteUserDefault'


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
		}
	}
}
