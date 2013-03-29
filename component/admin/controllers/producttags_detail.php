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

class producttags_detailController extends JController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		JRequest::setVar('view', 'producttags_detail');
		JRequest::setVar('layout', 'default');
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}

	public function save()
	{
		$post = JRequest::get('post');

		$option = JRequest::getVar('option');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		$post ['tags_id'] = $cid[0];

		$model = $this->getModel('producttags_detail');

		if ($model->store($post))
		{
			$msg = JText::_('COM_REDSHOP_TAGS_DETAIL_SAVED');

		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_TAGS_DETAIL');
		}

		$this->setRedirect('index.php?option=' . $option . '&view=producttags', $msg);
	}

	public function remove()
	{
		$option = JRequest::getVar('option');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('producttags_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_TAGS_DETAIL_DELETED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=' . $option . '&view=producttags', $msg);
	}

	public function publish()
	{
		$option = JRequest::getVar('option');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
		}

		$model = $this->getModel('producttags_detail');

		if (!$model->publish($cid, 1))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_TAGS_DETAIL_PUBLISHED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=' . $option . '&view=producttags', $msg);
	}

	public function unpublish()
	{
		$option = JRequest::getVar('option');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
		}

		$model = $this->getModel('producttags_detail');

		if (!$model->publish($cid, 0))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_TAGS_DETAIL_UNPUBLISHED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=' . $option . '&view=producttags', $msg);
	}

	public function cancel()
	{
		$option = JRequest::getVar('option');
		$msg = JText::_('COM_REDSHOP_TAGS_DETAIL_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=' . $option . '&view=producttags', $msg);
	}
}
