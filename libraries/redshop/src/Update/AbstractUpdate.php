<?php
/**
 * @package     Redshop
 * @subpackage  Libraries
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

namespace Redshop\Update;

defined('_JEXEC') or die;

/**
 * Abstract update class
 *
 * @package     Redshob.Libraries
 * @subpackage  Update
 * @since       2.0.6
 */
abstract class AbstractUpdate
{
	/**
	 * Exclude public method for not run when update
	 *
	 * @var    array
	 * @since  2.0.6
	 */
	protected $exclude = array('getTasksList');

	/**
	 * Method for return an correct task
	 *
	 * @param   string $name     Name of task
	 * @param   string $function Function for execute.
	 *
	 * @return  \stdClass
	 *
	 * @since   2.0.6
	 */
	protected function task($name, $function)
	{
		$task       = new \stdClass;
		$task->name = \JText::_($name);
		$task->func = $function;

		return $task;
	}

	/**
	 * Method for get all tasks (public method of current class)
	 *
	 * @return  mixed
	 *
	 * @since   2.0.6
	 */
	public function getTasksList()
	{
		$tasks = array();

		// Iterate through each method in the class
		foreach (get_class_methods($this) as $method)
		{
			// Get a reflection object for the class method
			$reflect = new \ReflectionMethod($this, $method);

			/*
			 * For private, use isPrivate().
			 * For protected, use isProtected()
			 * See the Reflection API documentation for more definitions
			 */
			if ($reflect->isPublic() && !in_array($method, $this->exclude))
			{
				// The method is one we're looking for, push it onto the return array
				array_push($tasks, $method);
			}
		}

		if (empty($tasks))
		{
			return false;
		}

		foreach ($tasks as $i => $task)
		{
			$tasks[$i] = $this->task(strtoupper('COM_REDSHOP_UPDATE_' . get_class($this) . '_' . strtoupper($task)), $task);
		}

		return $tasks;
	}

	/**
	 * Delete folders recursively.
	 *
	 * @param   array $folders Folders
	 *
	 * @return  boolean
	 *
	 * @since   2.0.6
	 */
	protected function deleteFolders(array $folders)
	{
		foreach ($folders as $folder)
		{
			if (!$this->deleteFolder($folder))
			{
				return false;
			}
		}

		return true;
	}

	/**
	 * Delete files recursively.
	 *
	 * @param   array  $files  Files
	 *
	 * @return  boolean
	 *
	 * @since   2.0.6
	 */
	protected function deleteFiles(array $files)
	{
		foreach ($files as $file)
		{
			if (file_exists($file))
			{
				JFile::delete($file);
			}
		}

		return true;
	}

	/**
	 * Delete folder recursively
	 *
	 * @param   string $folder Folder to delete
	 *
	 * @return  boolean
	 *
	 * @since   2.0.6
	 */
	protected function deleteFolder($folder)
	{
		if (!is_dir($folder))
		{
			return true;
		}

		$files = glob($folder . '/*');

		foreach ($files as $file)
		{
			if (is_dir($file))
			{
				if (!$this->deleteFolder($file))
				{
					return false;
				}

				continue;
			}

			if (!JFile::delete($file))
			{
				return false;
			}
		}

		return rmdir($folder);
	}
}
