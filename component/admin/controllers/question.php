<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class questionController extends JController
{
	function __construct($default = array())
	{
		parent::__construct($default);
	}

	function cancel()
	{
		$this->setRedirect('index.php');
	}

	function display()
	{
		parent::display();
	}

	/**
	 * logic for save an order
	 *
	 * @access public
	 * @return void
	 */
	function saveorder()
	{
		$option = JRequest::getVar('option');
		$cid    = JRequest::getVar('cid', array(), 'post', 'array');
		$order  = JRequest::getVar('order', array(), 'post', 'array');

		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);
		$model = $this->getModel('question');
		$model->saveorder($cid, $order);

		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
		$this->setRedirect('index.php?option=' . $option . '&view=question', $msg);
	}
}
