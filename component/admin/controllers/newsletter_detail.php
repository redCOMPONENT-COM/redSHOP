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

class newsletter_detailController extends JController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		JRequest::setVar('view', 'newsletter_detail');
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
		$body = JRequest::getVar('body', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$post["body"] = $body;

		$option = JRequest::getVar('option');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		$post ['newsletter_id'] = $cid [0];

		$model = $this->getModel('newsletter_detail');

		if ($row = $model->store($post))
		{
			$msg = JText::_('COM_REDSHOP_NEWSLETTER_DETAIL_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_NEWSLETTER_DETAIL');
		}

		if ($apply == 1)
		{
			$this->setRedirect('index.php?option=' . $option . '&view=newsletter_detail&task=edit&cid[]=' . $row->newsletter_id, $msg);
		}
		else
		{
			$this->setRedirect('index.php?option=' . $option . '&view=newsletter', $msg);
		}
	}

	public function remove()
	{
		$option = JRequest::getVar('option');
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		$val = 0;

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('newsletter_detail');

		foreach ($cid as $key => $value)
		{
			if ($value == 1)
			{
				unset($cid[$key]);
				$val = 1;
			}
		}

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		if ($val == 1)
		{
			$msg = JText::_('COM_REDSHOP_DEFAULT_NEWSLETTER_CAN_NOT_BE_DELETED');
		}

		else
		{
			$msg = JText::_('COM_REDSHOP_NEWSLETTER_DETAIL_DELETED_SUCCESSFULLY');
		}

		$this->setRedirect('index.php?option=' . $option . '&view=newsletter', $msg);
	}

	public function publish()
	{
		$option = JRequest::getVar('option');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
		}

		$model = $this->getModel('newsletter_detail');

		if (!$model->publish($cid, 1))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_NEWSLETTER_DETAIL_PUBLISHED_SUCCESFULLY');
		$this->setRedirect('index.php?option=' . $option . '&view=newsletter', $msg);
	}

	public function unpublish()
	{
		$option = JRequest::getVar('option');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
		}

		$model = $this->getModel('newsletter_detail');

		if (!$model->publish($cid, 0))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_NEWSLETTER_DETAIL_UNPUBLISHED_SUCCESFULLY');
		$this->setRedirect('index.php?option=' . $option . '&view=newsletter', $msg);
	}

	public function cancel()
	{
		$option = JRequest::getVar('option');
		$msg = JText::_('COM_REDSHOP_NEWSLETTER_DETAIL_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=' . $option . '&view=newsletter', $msg);
	}

	public function copy()
	{
		$option = JRequest::getVar('option');
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');
		$model = $this->getModel('newsletter_detail');

		if ($model->copy($cid))
		{
			$msg = JText::_('COM_REDSHOP_NEWSLETTER_COPIED_WITH_SUBSCRIBER');
		}

		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_COPYING_NEWSLETTER');
		}

		$this->setRedirect('index.php?option=' . $option . '&view=newsletter', $msg);
	}
}
