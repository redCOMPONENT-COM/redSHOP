<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');


jimport('joomla.application.component.controller');
/**
 * Product Mini Controller
 *
 * @static
 * @package        redSHOP
 * @since          1.0
 */
class product_miniController extends JController
{


	/**
	 * cancel function
	 *
	 * @access public
	 * @return void
	 */
	public function cancel()
	{
		$this->setRedirect('index.php');
	}

	/**
	 * logic for display
	 *
	 * @access public
	 * @return void
	 */
	public function display()
	{
		parent::display();
	}
}