<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Updates
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;
/**
 * Update class
 *
 * @package     Redshob.Update
 *
 * @since       2.1.0
 */
class RedshopUpdate211 extends RedshopInstallUpdate
{
	/**
	 * Method for fixing Calendar field not displaying properly when upgrade redshop
	 * (happen when the DEFAULT_DATEFORMAT of old version is not assigned a format)
	 *
	 * @return  void
	 *
	 * @since   2.1.1
	 * @throws  Exception
	 */
	public function fixCalendarFormField()
	{
		$app  = JFactory::getApplication();
		$currentConfig = Redshop::getConfig()->toArray();

		$temp = $app->getUserState('com_redshop.config.global.data');

		if (!empty($temp))
		{
			$currentConfig = array_merge($currentConfig, $temp);
		}

		if ($currentConfig['DEFAULT_DATEFORMAT'] === '0')
		{
			$currentConfig['DEFAULT_DATEFORMAT'] = 'Y-m-d';
		}

		$config = Redshop::getConfig();
		$app->setUserState('com_redshop.config.global.data', $currentConfig);
		$data = new Registry($currentConfig);
		$config->save($data);
	}
}
