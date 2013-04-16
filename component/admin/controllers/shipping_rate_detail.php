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

class shipping_rate_detailController extends JController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		JRequest::setVar('view', 'shipping_rate_detail');
		JRequest::setVar('layout', 'default');
		JRequest::setVar('hidemainmenu', 1);
		parent::display();
	}

	public function apply()
	{
		$this->save(1);
	}

	public function save($apply = 0)
	{
		$post = JRequest::get('post');

		// Include extra field class
		require_once JPATH_COMPONENT . '/helpers/extra_field.php';

		$option = JRequest::getVar('option');
		$post['shipping_rate_on_product'] = $post['container_product'];
		$post["shipping_location_info"] = JRequest::getVar('shipping_location_info', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$model = $this->getModel('shipping_rate_detail');

		if ($row = $model->store($post))
		{
			$field = new extra_field;

			// Field_section 11 :Shipping
			$field->extra_field_save($post, "11", $row->shipping_rate_id);
			$msg = JText::_('COM_REDSHOP_SHIPPING_LOCATION_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_SHIPPING');
		}

		if ($apply)
		{
			$this->setRedirect('index.php?option=' . $option . '&view=shipping_rate_detail&cid=' . $row->shipping_rate_id
				. '&id=' . $post['id'], $msg
			);
		}
		else
		{
			$this->setRedirect('index.php?option=' . $option . '&view=shipping_rate&id=' . $post['id'], $msg);
		}
	}

	public function remove()
	{
		$post = JRequest::get('post');
		$option = JRequest::getVar('option');
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');
		$model = $this->getModel('shipping_rate_detail');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect('index.php?option=' . $option . '&view=shipping_rate&id=' . $post['id']);
	}

	public function publish()
	{
		$option = JRequest::getVar('option');
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
		}

		$model = $this->getModel('shipping_rate_detail');

		if (!$model->publish($cid, 1))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect('index.php?option=' . $option . '&view=shipping_rate');
	}

	public function unpublish()
	{
		$option = JRequest::getVar('option');
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
		}

		$model = $this->getModel('shipping_rate_detail');

		if (!$model->publish($cid, 0))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect('index.php?option=' . $option . '&view=shipping_rate');
	}

	public function cancel()
	{
		$post = JRequest::get('post');
		$option = JRequest::getVar('option');
		$this->setRedirect('index.php?option=' . $option . '&view=shipping_rate&id=' . $post['id']);
	}

	public function copy()
	{
		$post = JRequest::get('post');
		$option = JRequest::getVar('option');
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');
		$model = $this->getModel('shipping_rate_detail');

		if ($model->copy($cid))
		{
			$msg = JText::_('COM_REDSHOP_SHIPPING_RATE_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_SHIPPING');
		}

		$this->setRedirect('index.php?option=' . $option . '&view=shipping_rate&id=' . $post['id'], $msg);
	}

	public function GetStateDropdown()
	{
		$get = JRequest::get('get');
		$model = $this->getModel('shipping_rate_detail');
		$model->GetStateDropdown($get);
		exit;
	}
}
