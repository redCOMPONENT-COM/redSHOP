<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 * stockroomController
 *
 * @package     RedSHOP
 * @subpackage  Controller
 * @since       1.0
 */
class stockroomController extends JController
{
	/**
	 * cancel
	 */
	public function cancel()
	{
		$this->setRedirect('index.php');
	}

	/**
	 * listing
	 */
	public function listing()
	{
		$this->setRedirect('index.php?option=com_redshop&view=stockroom_listing&id=0');
	}
}
