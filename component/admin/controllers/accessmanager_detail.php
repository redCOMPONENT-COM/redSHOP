<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopControllerAccessmanager_detail extends RedshopController
{
	protected $jinput;

	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
		$this->jinput = JFactory::getApplication()->input;
	}

	public function edit()
	{
		$this->jinput->set('view', 'accessmanager_detail');
		$this->jinput->set('layout', 'default');
		$this->jinput->set('hidemainmenu', 1);
		parent::display();
	}

	public function save($apply)
	{
		$post = $this->jinput->getArray($_POST);

		$model = $this->getModel('accessmanager_detail');
		$section = $this->jinput->getString('section');
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
		$msg = JText::_('COM_REDSHOP_ACCESS_LEVEL_CANCEL');
		$this->setRedirect('index.php?option=com_redshop&view=accessmanager', $msg);
	}
}
