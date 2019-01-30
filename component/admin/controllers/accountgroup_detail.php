<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Account Group Detail controller
 *
 * @since  1.5
 */
class RedshopControllerAccountgroup_Detail extends RedshopController
{
	/**
	 * RedshopControllerAccountgroup_Detail constructor.
	 *
	 * @param   array  $default  Config
	 */
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	/**
	 * Edit
	 *
	 * @return  void
	 */
	public function edit()
	{
		$this->input->set('view', 'accountgroup_detail');
		$this->input->set('layout', 'default');
		$this->input->set('hidemainmenu', 1);
		parent::display();
	}

	/**
	 * Apply
	 *
	 * @return  void
	 */
	public function apply()
	{
		$this->save(1);
	}

	/**
	 * Save
	 *
	 * @param   integer  $apply  Apply or not.
	 *
	 * @return  void
	 */
	public function save($apply = 0)
	{
		$post = $this->input->post->getArray();
		$cid  = $this->input->post->get('cid', array(0), 'array');

		$post['accountgroup_id'] = $cid[0];

		/** @var RedshopModelAccountgroup_detail $model */
		$model = $this->getModel('accountgroup_detail');
		$row   = $model->store($post);

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
			$this->setRedirect('index.php?option=com_redshop&view=accountgroup_detail&task=edit&cid[]=' . $row->accountgroup_id, $msg);
		}
		else
		{
			$this->setRedirect('index.php?option=com_redshop&view=accountgroup', $msg);
		}
	}

	/**
	 * Cancel
	 *
	 * @return  void
	 */
	public function cancel()
	{
		$this->setRedirect('index.php?option=com_redshop&view=accountgroup', JText::_('COM_REDSHOP_ACCOUNTGROUP_DETAIL_EDITING_CANCELLED'));
	}

	/**
	 * Remove
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 */
	public function remove()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		/** @var RedshopModelAccountgroup_detail $model */
		$model = $this->getModel('accountgroup_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_ACCOUNTGROUP_DETAIL_DELETED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=accountgroup', $msg);
	}
}
