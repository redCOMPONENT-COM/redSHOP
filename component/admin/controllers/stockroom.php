<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopControllerStockroom extends RedshopController
{
	public function cancel()
	{
		$this->setRedirect('index.php');
	}

	public function listing()
	{
		$this->setRedirect('index.php?option=com_redshop&view=stockroom_listing&id=0');
	}
}
