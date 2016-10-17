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
 * Access manager detail controller
 *
 * @package     RedSHOP.Backend
 * @subpackage  Controller.Accessmanager
 * @since       2.0
 */
class RedshopControllerAccessmanager extends RedshopControllerForm
{
	/**
	 * RedshopControllerAccessmanager constructor.
	 *
	 * @param   array  $default  An optional associative array of configuration settings.
	 */
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	/**
	 * Edit task
	 *
	 * @return  JControllerLegacy
	 */
	public function edit()
	{
		$input = JFactory::getApplication()->input;
		$input->set('view', 'accessmanager');
		$input->set('layout', 'default');
		$input->set('hidemainmenu', 1);

		parent::display();
	}

	/**
	 * Save task
	 *
	 * @param   int  $apply  Save / Apply data
	 *
	 * @return  void
	 */
	public function save($apply)
	{
		$input = JFactory::getApplication()->input;
		$post = $input->post->getArray();

		$model   = $this->getModel();
		$section = $input->request->getString('section', '');

		if ($model->store($post))
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

	/**
	 * Alias method of save without redirect back to main view
	 *
	 * @return  void
	 */
	public function apply()
	{
		$this->save(1);
	}

	/**
	 * Cancel task
	 *
	 * @return  void
	 */
	public function cancel()
	{
		$this->setRedirect('index.php?option=com_redshop&view=accessmanagers', JText::_('COM_REDSHOP_ACCESS_LEVEL_CANCEL'));
	}

	/**
	 * Proxy to get RedshopModelAccessmanager
	 *
	 * @param   string  $name    Model name
	 * @param   string  $prefix  Model prefix
	 * @param   array   $config  Configuration
	 *
	 * @return  object
	 */
	public function getModel($name = 'Accessmanager', $prefix = 'RedshopModel', $config = array())
	{
		return parent::getModel($name, $prefix, $config);
	}
}
