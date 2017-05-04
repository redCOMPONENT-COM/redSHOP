<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Model Install
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since       2.0.4
 */
class RedshopModelInstall extends RedshopModelList
{
	/**
	 * @var  string
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected $updatePath = JPATH_COMPONENT_ADMINISTRATOR . '/updates';

	/**
	 * Method for get all available step of installation.
	 *
	 * @param   string $type Type of installation (install, install_discover, update)
	 *
	 * @return  array
	 *
	 * @since   2.0.4
	 */
	public function getSteps($type = 'install')
	{
		if ($type != 'install')
		{
			return $this->getUpdateSteps();
		}

		return RedshopInstall::getInstallTasks();
	}

	/**
	 * Method for get all available step of update.
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getUpdateSteps()
	{
		// Get available updates class.
		if (!is_dir($this->updatePath))
		{
			return array();
		}

		$files   = glob($this->updatePath . '/*.php');
		$version = RedshopHelperJoomla::getManifestValue('version');
		$classes = array();

		$tasks = array(
			array(
				'text' => 'COM_REDSHOP_INSTALL_STEP_HANDLE_CONFIG',
				'func' => 'RedshopInstall::handleConfig'
			)
		);

		foreach ($files as $file)
		{
			$updateVersion = JFile::stripExt(basename($file));

			if (version_compare($version, $updateVersion, '<'))
			{
				$classes[$updateVersion] = array('class' => 'RedshopUpdate' . str_replace(array('.', '-'), '', $updateVersion), 'path' => $file);
			}
		}

		asort($classes);

		foreach ($classes as $class)
		{
			require_once $class['path'];

			/** @var RedshopInstallUpdate $updateClass */
			$updateClass = new $class['class'];
			$classTasks  = $updateClass->getTasksList();

			if (empty($classTasks))
			{
				continue;
			}

			foreach ($classTasks as $classTask)
			{
				$tasks[] = array(
					'text' => $classTask->name,
					'func' => $class['class'] . '.' . $classTask->func,
					'path' => $class['path']
				);
			}
		}

		JFactory::getApplication()->setUserState(RedshopInstall::REDSHOP_INSTALL_STATE_NAME, $tasks);

		return $tasks;
	}
}
