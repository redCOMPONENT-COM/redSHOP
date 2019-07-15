<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

class RedshopControllerQuotation extends RedshopControllerAdmin
{
	public function cancel()
	{
		$this->setRedirect('index.php');
	}
}
