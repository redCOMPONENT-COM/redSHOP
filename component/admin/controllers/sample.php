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
 * sampleController
 *
 * @package     RedSHOP
 * @subpackage  Controller
 * @since       1.0
 */
class sampleController extends JController
{
	/**
	 * cancel
	 */
	public function cancel()
	{
		$this->setRedirect('index.php');
	}
}
