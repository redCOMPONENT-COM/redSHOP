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
 * tax_groupController
 *
 * @package     RedSHOP
 * @subpackage  Controller
 * @since       1.0
 */
class tax_groupController extends JController
{
	/**
	 * cancel
	 */
	public function cancel()
	{
		$option = JRequest::getVar('option');

		$this->setRedirect('index.php?option=' . $option . '&view=tax_group');
	}
}
