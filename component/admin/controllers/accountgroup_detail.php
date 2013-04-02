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

class accountgroup_detailController extends JController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		JRequest::setVar('view', 'accountgroup_detail');
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
		$option = JRequest::getVar('option');
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');
		$post ['accountgroup_id'] = $cid [0];
		$model = $this->getModel('accountgroup_detail');
		$row = $model->store($post);

		if ($row)
		{
			$msg = JText::_('COM_REDSHOP_ACCOUNTGROUP_DETAIL_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_ACCOUNTGROUP_DETAIL');
		}

		if ($apply == 1)
		{
			$this->setRedirect('index.php?option=' . $option . '&view=accountgroup_detail&task=edit&cid[]=' . $row->accountgroup_id, $msg);
		}
		else
		{
			$this->setRedirect('index.php?option=' . $option . '&view=accountgroup', $msg);
		}
	}

	public function cancel()
	{
		$option = JRequest::getVar('option');
		$msg = JText::_('COM_REDSHOP_ACCOUNTGROUP_DETAIL_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=' . $option . '&view=accountgroup', $msg);
	}

	public function remove()
	{
		$option = JRequest::getVar('option');
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('accountgroup_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_ACCOUNTGROUP_DETAIL_DELETED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=' . $option . '&view=accountgroup', $msg);
	}

	public function publish()
	{
		$option = JRequest::getVar('option');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
		}

		$model = $this->getModel('accountgroup_detail');

		if (!$model->publish($cid, 1))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_ACCOUNTGROUP_DETAIL_PUBLISHED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=' . $option . '&view=accountgroup', $msg);
	}

	public function unpublish()
	{
		$option = JRequest::getVar('option');
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
		}

		$model = $this->getModel('accountgroup_detail');

		if (!$model->publish($cid, 0))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_ACCOUNTGROUP_DETAIL_UNPUBLISHED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=' . $option . '&view=accountgroup', $msg);
	}
}
