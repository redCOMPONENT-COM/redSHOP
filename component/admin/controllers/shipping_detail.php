<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die ('Restricted access');

jimport('joomla.application.component.controller');

class shipping_detailController extends JController
{
	function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	function edit()
	{
		JRequest::setVar('view', 'shipping_detail');
		JRequest::setVar('layout', 'default');
		JRequest::setVar('hidemainmenu', 1);
		parent::display();

	}

	function apply()
	{
		$this->save(1);
	}

	function save($apply = 0)
	{
		$post = JRequest::get('post');
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');
		$option = JRequest::getVar('option');
		$model = $this->getModel('shipping_detail');
		$row = $model->store($post);

		if ($row)
		{
			$msg = JText::_('COM_REDSHOP_SHIPPING_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_shipping');
		}
		if ($apply == 1)
		{
			$this->setRedirect('index.php?option=' . $option . '&view=shipping_detail&task=edit&cid[]=' . $post['extension_id'], $msg);
		}
		else
		{
			$this->setRedirect('index.php?option=' . $option . '&view=shipping', $msg);
		}
	}

	function publish()
	{
		$option = JRequest::getVar('option');
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
		}

		$model = $this->getModel('shipping_detail');
		if (!$model->publish($cid, 1))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}
		$this->setRedirect('index.php?option=' . $option . '&view=shipping');
	}

	function unpublish()
	{
		$option = JRequest::getVar('option');
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
		}
		$model = $this->getModel('shipping_detail');
		if (!$model->publish($cid, 0))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}
		$this->setRedirect('index.php?option=' . $option . '&view=shipping');
	}

	function cancel()
	{
		$option = JRequest::getVar('option');
		$this->setRedirect('index.php?option=' . $option . '&view=shipping');
	}

	/**
	 * logic for orderup manufacturer
	 *
	 * @access public
	 * @return void
	 */
	function orderup()
	{
		$option = JRequest::getVar('option');
		$model = $this->getModel('shipping_detail');
		$model->move(-1);
		//$model->orderup();
		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
		$this->setRedirect('index.php?option=' . $option . '&view=shipping', $msg);
	}

	/**
	 * logic for orderdown manufacturer
	 *
	 * @access public
	 * @return void
	 */
	function orderdown()
	{
		$option = JRequest::getVar('option');
		$model = $this->getModel('shipping_detail');
		$model->move(1);
		//$model->orderdown();
		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
		$this->setRedirect('index.php?option=' . $option . '&view=shipping', $msg);
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
		$cid = JRequest::getVar('cid', array(), 'post', 'array');
		$order = JRequest::getVar('order', array(), 'post', 'array');

		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);

		$model = $this->getModel('shipping_detail');
		$model->saveorder($cid);

		$msg = JText::_('COM_REDSHOP_SHIPPING_SAVED');
		$this->setRedirect('index.php?option=' . $option . '&view=shipping', $msg);
	}
}


