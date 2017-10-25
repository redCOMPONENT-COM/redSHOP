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
	 * Method for get all available version of installation.
	 *
	 * @return  array
	 *
	 * @since   2.0.7
	 */
	public function getAvailableUpdate()
	{
		$updatePath = JPATH_COMPONENT_ADMINISTRATOR . '/updates';

		$files = JFolder::files($updatePath, '.php', false, true);
		$versions = array();

		foreach ($files as $file)
		{
			$version = new stdClass;

			$version->version = JFile::stripExt(basename($file));

			require_once $file;

			$version->class = 'RedshopUpdate' . str_replace(array('.', '-'), '', $version->version);

			/** @var RedshopInstallUpdate $updateClass */
			$updateClass = new $version->class;
			$classTasks  = $updateClass->getTasksList();
			$version->tasks = array();

			if (empty($classTasks))
			{
				continue;
			}

			foreach ($classTasks as $classTask)
			{
				$version->tasks[] = JText::_($classTask->name);
			}

			$versions[$version->version] = $version;
		}

		arsort($versions);

		return $versions;
	}

	/**
	 * Method for get all available step of update.
	 *
	 * @return  array
	 *
	 * @since   2.0.6
	 */
	public function getUpdateSteps()
	{
		$updatePath = JPATH_COMPONENT_ADMINISTRATOR . '/updates';

		// Get available updates class.
		if (!is_dir($updatePath))
		{
			return array();
		}

		$app     = JFactory::getApplication();
		$version = $app->getUserState('redshop.old_version', null);
		$specificVersion = $app->input->get('version', null);

		$tasks = array(
			array(
				'text' => JText::_('COM_REDSHOP_INSTALL_STEP_HANDLE_CONFIG'),
				'func' => 'RedshopInstall::handleConfig'
			)
		);

		if (is_null($version) && is_null($specificVersion))
		{
			$app->setUserState(RedshopInstall::REDSHOP_INSTALL_STATE_NAME, $tasks);
			$app->setUserState('redshop.old_version', null);

			return $tasks;
		}

		$files   = JFolder::files($updatePath, '.php', false, true);
		$classes = array();

		foreach ($files as $file)
		{
			$updateVersion = JFile::stripExt(basename($file));

			if (!is_null($version) && version_compare($version, $updateVersion, '<'))
			{
				$classes[$updateVersion] = array('class' => 'RedshopUpdate' . str_replace(array('.', '-'), '', $updateVersion), 'path' => $file);
			}
			elseif (!is_null($specificVersion) && version_compare($specificVersion, $updateVersion, '='))
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

		$app->setUserState(RedshopInstall::REDSHOP_INSTALL_STATE_NAME, $tasks);
		$app->setUserState('redshop.old_version', null);

		return $tasks;
	}
}
