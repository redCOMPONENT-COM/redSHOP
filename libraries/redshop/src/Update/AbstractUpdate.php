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
 * @since       __DEPLOY_VERSION__
 */
abstract class AbstractUpdate
{
	/**
	 * Exclude public method for not run when update
	 *
	 * @var    array
	 * @since  __DEPLOY_VERSION
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
	 * @since   __DEPLOY_VERSION__
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
	 * @since   __DEPLOY_VERSION__
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
}
