<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Model Install
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since       __DEPLOY_VERSION__
 */

class RedshopModelInstall extends RedshopModelList
{
	/**
	 * Method for get all available step of installation.
	 *
	 * @param   string  $type  Type of installation (install, install_discover, update)
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getSteps($type = 'install')
	{
		return array(
			array(
				'text' => JText::_('COM_REDSHOP_INSTALL_STEP_HANDLE_CONFIGURATION'),
				'func' => 'handleConfig'
			),
			array(
				'text' => JText::_('COM_REDSHOP_INSTALL_STEP_SYNCHRONIZE_USERS'),
				'func' => 'syncUser'
			),
		);
	}
}
