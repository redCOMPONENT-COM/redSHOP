<?php
/**
 * @package     Redshop.Library
 * @subpackage  Base
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

if (version_compare(JVERSION, '3.0', 'lt'))
{
	JLoader::import('joomla.application.component.model');

	/**
	 * redSHOP Controller Admin
	 *
	 * @package     Redshop
	 * @subpackage  Controller
	 * @since       2.0.0.3
	 */
	class RedshopControllerAdmin extends RedshopControllerAdminBase
	{
		/**
		 * We need to redeclare the method as JModelLegacy was not existing before 3.0.
		 *
		 * @param   JModel   $model  The data model object.
		 * @param   integer  $id     The validated data.
		 *
		 * @return  void
		 */
		protected function postDeleteHook(JModel $model, $id = null)
		{
		}
	}
}

else
{
	/**
	 * redCORE Controller Admin
	 *
	 * @package     Redcore
	 * @subpackage  Controller
	 * @since       1.0
	 */
	class RedshopControllerAdmin extends RedshopControllerAdminBase
	{
	}
}
