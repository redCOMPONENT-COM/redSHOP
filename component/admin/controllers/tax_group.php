<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopControllerTax_group extends RedshopController
{
	public function cancel()
	{
		$option = JRequest::getVar('option');

		$this->setRedirect('index.php?option=com_redshop&view=tax_group');
	}
}
