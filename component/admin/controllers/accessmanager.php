<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopControllerAccessmanager extends RedshopControllerForm
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		$input = JFactory::getApplication()->input;
		$input->set('view', 'accessmanager');
		$input->set('layout', 'default');
		$input->set('hidemainmenu', 1);

		parent::display();
	}

	public function save($apply)
	{
		$post = JRequest::get('post');

		$model   = $this->getModel();
		$section = JRequest::getVar('section', '', 'request', 'string');
		$row     = $model->store($post);

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
			$this->setRedirect('index.php?option=com_redshop&view=accessmanager&section=' . $section, $msg);
		}
		else
		{
			$this->setRedirect('index.php?option=com_redshop&view=accessmanagers', $msg);
		}
	}

	public function apply()
	{
		$this->save(1);
	}

	public function cancel()
	{
		$msg = JText::_('COM_REDSHOP_ACCESS_LEVEL_CANCEL');
		$this->setRedirect('index.php?option=com_redshop&view=accessmanagers', $msg);
	}

	public function getModel($name = 'Accessmanager', $prefix = 'RedshopModel', $config = array())
	{
		return parent::getModel($name, $prefix, $config);
	}
}
