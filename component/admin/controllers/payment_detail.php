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

define('WARNSAME', "There is already a file called '%s'.");
define('INSTALLEXT', 'Install %s %s');

class payment_detailController extends JController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function install()
	{
		$model = $this->getModel('payment_detail');
		$model->install();

		JRequest::setVar('view', 'payment_detail');
		JRequest::setVar('layout', 'default');
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}

	public function edit()
	{
		JRequest::setVar('view', 'payment_detail');
		JRequest::setVar('layout', 'default');
		JRequest::setVar('hidemainmenu', 1);
		parent::display();
	}

	public function save()
	{
		$post = JRequest::get('post');

		$accepted_credit_card = JRequest::getVar('accepted_credict_card', '', 'post', 'array');
		$accepted_credit_card = implode(",", $accepted_credit_card);
		$post["accepted_credict_card"] = $accepted_credit_card;

		$option = JRequest::getVar('option');

		$model = $this->getModel('payment_detail');

		$payment_extrainfo = JRequest::getVar('payment_extrainfo', '', 'post', 'string', JREQUEST_ALLOWRAW);

		$post["payment_extrainfo"] = $payment_extrainfo;

		if ($model->store($post))
		{
			$msg = JText::_('COM_REDSHOP_PAYMENT_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_PAYMENT');
		}

		$this->setRedirect('index.php?option=' . $option . '&view=payment', $msg);
	}

	public function remove()
	{
		$option = JRequest::getVar('option');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		$model = $this->getModel('payment_detail');
		$model->uninstall($cid);

		$this->setRedirect('index.php?option=' . $option . '&view=payment');
	}

	public function publish()
	{
		$option = JRequest::getVar('option');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
		}

		$model = $this->getModel('payment_detail');

		if (!$model->publish($cid, 1))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect('index.php?option=' . $option . '&view=payment');
	}

	public function unpublish()
	{
		$option = JRequest::getVar('option');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
		}

		$model = $this->getModel('payment_detail');

		if (!$model->publish($cid, 0))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect('index.php?option=' . $option . '&view=payment');
	}

	public function cancel()
	{
		$option = JRequest::getVar('option');

		$this->setRedirect('index.php?option=' . $option . '&view=payment');
	}

	/**
	 * logic for orderup manufacturer
	 *
	 * @access public
	 * @return void
	 */
	public function orderup()
	{
		$option = JRequest::getVar('option');

		$model = $this->getModel('payment_detail');
		$model->move(-1);

		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
		$this->setRedirect('index.php?option=' . $option . '&view=payment', $msg);
	}

	/**
	 * logic for orderdown manufacturer
	 *
	 * @access public
	 * @return void
	 */
	public function orderdown()
	{
		$option = JRequest::getVar('option');
		$model = $this->getModel('payment_detail');
		$model->move(1);

		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
		$this->setRedirect('index.php?option=' . $option . '&view=payment', $msg);
	}

	/**
	 * logic for save an order
	 *
	 * @access public
	 * @return void
	 */
	public function saveorder()
	{
		$option = JRequest::getVar('option');

		$cid = JRequest::getVar('cid', array(), 'post', 'array');
		$order = JRequest::getVar('order', array(), 'post', 'array');

		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);

		$model = $this->getModel('payment_detail');
		$model->saveorder($cid);

		$msg = JText::_('COM_REDSHOP_PAYMENT_SAVED');
		$this->setRedirect('index.php?option=' . $option . '&view=payment', $msg);
	}
}
