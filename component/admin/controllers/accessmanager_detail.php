<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopControllerAccessmanager_detail extends RedshopController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		JRequest::setVar('view', 'answer_detail');
		JRequest::setVar('layout', 'default');
		JRequest::setVar('hidemainmenu', 1);
		parent::display();
	}

	public function save($apply)
	{
		$post = JRequest::get('post');

		$option = JRequest::getVar('option', '', 'request', 'string');
		$model = $this->getModel('accessmanager_detail');
		$section = JRequest::getVar('section', '', 'request', 'string');
		$row = $model->store($post);

		if ($row)
		{
			$msg = JText::_('COM_REDSHOP_ACCESS_LEVEL_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_ACCESS_LEVEL_SAVED');
		}

		if ($apply)
		{
			$this->setRedirect('index.php?option=com_redshop&view=accessmanager_detail&section=' . $section, $msg);
		}
		else
		{
			$this->setRedirect('index.php?option=com_redshop&view=accessmanager', $msg);
		}
	}

	public function apply()
	{
		$this->save(1);
	}

	public function cancel()
	{
		$option = JRequest::getVar('option');
		$msg = JText::_('COM_REDSHOP_ACCESS_LEVEL_CANCEL');
		$this->setRedirect('index.php?option=com_redshop&view=accessmanager', $msg);
	}
}
