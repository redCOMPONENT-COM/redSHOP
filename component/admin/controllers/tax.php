<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopControllerTax extends RedshopController
{
	public function cancel()
	{


		$this->setRedirect('index.php?option=com_redshop&view=tax');
	}
}
