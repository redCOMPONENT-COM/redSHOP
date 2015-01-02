<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopControllerAddressfields_listing extends RedshopController
{
	public function cancel()
	{
		$this->setRedirect('index.php');
	}

	public function saveorder()
	{
		$option = JRequest::getVar('option');
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');
		$order = JRequest::getVar('order', array(), 'post', 'array');
		$model = $this->getModel('addressfields_listing');

		if ($model->saveorder($cid, $order))
		{
			$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_NEW_ORDERING_ERROR');
		}
		$this->setRedirect('index.php?option=com_redshop&view=addressfields_listing', $msg);
	}

	/**
	 * logic for orderup manufacturer
	 *
	 * @access public
	 * @return void
	 */
	public function orderup()
	{
		global $context;

		$app = JFactory::getApplication();

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');
		$option = JRequest::getVar('option');
		$filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');
		$up = 1;

		if (strtolower($filter_order_Dir) == "asc")
		{
			$up = -1;
		}

		$model = $this->getModel('addressfields_listing');
		$model->move($up, $cid[0]);

		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
		$this->setRedirect('index.php?option=com_redshop&view=addressfields_listing', $msg);
	}

	/**
	 * logic for orderdown manufacturer
	 *
	 * @access public
	 * @return void
	 */
	public function orderdown()
	{
		global $context;

		$app = JFactory::getApplication();

		$option = JRequest::getVar('option');
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');
		$filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');
		$down = -1;

		if (strtolower($filter_order_Dir) == "asc")
		{
			$down = 1;
		}

		$model = $this->getModel('addressfields_listing');
		$model->move($down, $cid[0]);

		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
		$this->setRedirect('index.php?option=com_redshop&view=addressfields_listing', $msg);
	}
}

