<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopControllerShipping_rate extends RedshopController
{
	public function cancel()
	{
		$input = JFactory::getApplication()->input;
		$this->setRedirect('index.php?option=com_redshop&view=shipping_detail&task=edit&cid[]=' . $input->getInt('id', 0));
	}
}
