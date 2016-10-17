<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * The state controller
 *
 * @package     RedITEM.Backend
 * @subpackage  Controller.State
 * @since       2.0.0.2.2
 */
class RedshopControllerState extends RedshopController
{
	/**
	 * constructor (registers additional tasks to methods)
	 *
	 * @param   array  $default  default value
	 */
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	/**
	 * display the add and the edit form
	 *
	 * @return void
	 */
	public function edit()
	{
		JRequest::setVar('view', 'state_detail');
		JRequest::setVar('layout', 'default');
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}

	/**
	 * display the apply and the edit form
	 *
	 * @return boolean
	 */
	public function apply()
	{
		$this->save(1);
	}

	/**
	 * display the save form
	 *
	 * @param   int  $apply  flag to know save or aplly
	 *
	 * @return void
	 */
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

	/**
	 * display the cancel form
	 *
	 * @return void
	 */
	public function cancel()
	{
		$model = $this->getModel('state_detail');
		$model->checkin();
		$msg = JText::_('COM_REDSHOP_state_DETAIL_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=com_redshop&view=state', $msg);
	}

	/**
	 * display the remove form
	 *
	 * @return void
	 */
	public function remove()
	{
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
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
