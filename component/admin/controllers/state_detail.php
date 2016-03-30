<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopControllerState_detail extends RedshopController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		JRequest::setVar('view', 'state_detail');
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

		$state_name = JRequest::getVar('state_name', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$post["state_name"] = $state_name;

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');
		$post ['state_id'] = $cid [0];
		$model = $this->getModel('state_detail');
		$row = $model->store($post);

		if ($row)
		{
			$msg = JText::_('COM_REDSHOP_STATE_DETAIL_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_IN_STATE_DETAIL');
		}

		if ($apply == 1)
		{
			$this->setRedirect('index.php?option=com_redshop&view=state_detail&task=edit&cid[]=' . $row->state_id, $msg);
		}
		else
		{
			$this->setRedirect('index.php?option=com_redshop&view=state', $msg);
		}

	}

	public function cancel()
	{


		$model = $this->getModel('state_detail');
		$model->checkin();
		$msg = JText::_('COM_REDSHOP_state_DETAIL_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=com_redshop&view=state', $msg);
	}

	public function remove()
	{


		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('state_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_state_DETAIL_DELETED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=state', $msg);
	}
}
