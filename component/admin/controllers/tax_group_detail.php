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

class tax_group_detailController extends JController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		JRequest::setVar('view', 'tax_group_detail');
		JRequest::setVar('layout', 'default');
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}

	public function save()
	{
		$post = JRequest::get('post');
		$option = JRequest::getVar('option');

		$model = $this->getModel('tax_group_detail');

		if ($model->store($post))
		{
			$msg = JText::_('COM_REDSHOP_TAX_GROUP_DETAIL_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_TAX_GROUP_DETAIL');
		}

		$this->setRedirect('index.php?option=' . $option . '&view=tax_group', $msg);
	}

	public function remove()
	{
		$option = JRequest::getVar('option');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		if (!is_array($cid) && $cid == 1)
		{
			$msg = JText::_('COM_REDSHOP_DEFAULT_VAT_GROUP_CAN_NOT_BE_DELETED');
		}

		elseif (in_array(1, $cid))
		{
			$msg = JText::_('COM_REDSHOP_DEFAULT_VAT_GROUP_CAN_NOT_BE_DELETED');
		}

		else
		{
			$model = $this->getModel('tax_group_detail');

			if (!$model->delete($cid))
			{
				echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
			}

			$msg = JText::_('COM_REDSHOP_TAX_GROUP_DETAIL_DELETED_SUCCESSFULLY');
		}

		$this->setRedirect('index.php?option=' . $option . '&view=tax_group', $msg);
	}

	public function publish()
	{
		$option = JRequest::getVar('option');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
		}

		$model = $this->getModel('tax_group_detail');

		if (!$model->publish($cid, 1))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_TAX_GROUP_DETAIL_PUBLISHED_SUCCESFULLY');

		$this->setRedirect('index.php?option=' . $option . '&view=tax_group', $msg);
	}

	public function unpublish()
	{
		$option = JRequest::getVar('option');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
		}

		$model = $this->getModel('tax_group_detail');

		if (!$model->publish($cid, 0))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_TAX_GROUP_DETAIL_UNPUBLISHED_SUCCESFULLY');

		$this->setRedirect('index.php?option=' . $option . '&view=tax_group', $msg);
	}

	public function cancel()
	{
		$option = JRequest::getVar('option');
		$msg = JText::_('COM_REDSHOP_TAX_GROUP_DETAIL_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=' . $option . '&view=tax_group', $msg);
	}
}
