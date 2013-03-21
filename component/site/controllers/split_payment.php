<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 * split payment Controller.
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 * @since       1.0
 */
class Split_paymentController extends JController
{
	public function __construct($default = array())
	{
		parent::__construct($default);

		$user =& JFactory::getUser();
		$model = $this->getModel('split_payment');
	}

	/**
	 * payremaining function
	 *
	 * @access public
	 * @return void
	 */
	public function payremaining()
	{
		global $mainframe;
		$post = JRequest::get('post');
		$option = JRequest::getVar('option');
		$Itemid = JRequest::getVar('Itemid');
		$task = JRequest::getVar('task');
		$model = $this->getModel('split_payment');

		$orderresult = $model->orderplace();

		$view = & $this->getView('split_payment', 'result');
		parent::display();
	}
}
