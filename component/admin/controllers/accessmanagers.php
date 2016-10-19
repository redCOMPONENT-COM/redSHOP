<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Access managers controller
 *
 * @package     RedSHOP.Backend
 * @subpackage  Controller.Accessmanagers
 * @since       2.0
 */
class RedshopControllerAccessmanagers extends RedshopControllerAdmin
{
	/**
	 * Cancel task
	 *
	 * @return  void
	 */
	public function cancel()
	{
		$this->setRedirect('index.php');
	}

	/**
	 * Proxy to get RedshopModelAccessmanagers
	 *
	 * @param   string  $name    Model name
	 * @param   string  $prefix  Model prefix
	 * @param   array   $config  Configuration
	 *
	 * @return  object
	 */
	public function getModel($name = 'Accessmanagers', $prefix = 'RedshopModel', $config = array())
	{
		return parent::getModel($name, $prefix, $config);
	}
}
