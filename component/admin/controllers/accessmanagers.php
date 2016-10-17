<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class RedshopControllerAccessmanagers extends RedshopControllerAdmin
{
	public function cancel()
	{
		$this->setRedirect('index.php');
	}

	public function getModel($name = 'Accessmanagers', $prefix = 'RedshopModel', $config = array())
	{
		return parent::getModel($name, $prefix, $config);
	}
}
