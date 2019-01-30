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
 * Tool update controller
 *
 * @package     RedSHOP.backend
 * @subpackage  Controller
 * @since       2.1.0
 */
class RedshopControllerTool_Update extends RedshopControllerAdmin
{
	/**
	 * Proxy for getModel.
	 *
	 * @param   string $name   The model name. Optional.
	 * @param   string $prefix The class prefix. Optional.
	 * @param   array  $config Configuration array for model. Optional.
	 *
	 * @return  object  The model.
	 *
	 * @since   2.1.0
	 */
	public function getModel($name = 'Tool', $prefix = 'RedshopModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}

	/**
	 * Method for run ajax process.
	 *
	 * @return  void
	 * @throws  Exception
	 *
	 * @since   2.1.0
	 */
	public function ajaxProcess()
	{
		\Redshop\Helper\Ajax::validateAjaxRequest();
		$app = JFactory::getApplication();

		$remainingTasks = RedshopInstall::getRemainingTasks();

		if (empty($remainingTasks))
		{
			$app->sendHeaders();
			echo json_encode((object) array('msg' => JText::_('COM_REDSHOP_INSTALL_STEP_SUCCESS'), 'continue' => 0));
			$app->close();
		}

		$return      = array('msg' => '', 'continue' => 0);
		$currentTask = array_shift($remainingTasks);

		// Check process param
		if (empty($currentTask) || !isset($currentTask['func']))
		{
			$app->setHeader('status', 500);
			$app->sendHeaders();
			echo JText::_('COM_REDSHOP_INSTALL_ERROR_MISSING_PROCESS');
			$app->close();
		}

		$process   = $currentTask['func'];
		$isStatic  = false;
		$className = '';
		$method    = '';

		// Static call
		if (false !== strpos($process, '::'))
		{
			$process   = explode('::', $process);
			$className = $process[0];
			$method    = $process[1];
			$isStatic  = true;
		}
		elseif (false !== strpos($process, '.'))
		{
			$process   = explode('.', $process);
			$className = $process[0];
			$method    = $process[1];
		}

		// Load class if path has been provided
		if (isset($currentTask['path']))
		{
			require_once $currentTask['path'];
		}

		// Check class exist.
		if (!class_exists($className))
		{
			$app->setHeader('status', 500);
			$app->sendHeaders();
			echo JText::sprintf('COM_REDSHOP_INSTALL_ERROR_MISSING_CLASS', $className);
			$app->close();
		}

		// Check method exist in class
		if (!method_exists($className, $method))
		{
			$app->setHeader('status', 500);
			$app->sendHeaders();
			echo JText::sprintf('COM_REDSHOP_INSTALL_ERROR_MISSING_METHOD_IN_CLASS', $className, $method);
			$app->close();
		}

		try
		{
			if ($isStatic)
			{
				call_user_func(array($className, $method));
			}
			else
			{
				$class = new $className;
				call_user_func(array($class, $method));
			}
		}
		catch (Exception $error)
		{
			$app->setHeader('status', 500);
			$app->sendHeaders();
			echo $error->getMessage();
			$app->close();
		}

		if (empty($remainingTasks))
		{
			$app->setUserState(RedshopInstall::REDSHOP_INSTALL_STATE_NAME, null);
		}
		else
		{
			$app->setUserState(RedshopInstall::REDSHOP_INSTALL_STATE_NAME, $remainingTasks);
			$return['continue'] = 1;
		}

		$app->sendHeaders();
		$return['msg'] = JText::_('COM_REDSHOP_INSTALL_STEP_SUCCESS');
		echo json_encode((object) $return);
		$app->close();
	}

	/**
	 * Method for run migrate file function.
	 *
	 * @return  void
	 * @throws  Exception
	 */
	public function ajaxMigrateFiles()
	{
		\Redshop\Helper\Ajax::validateAjaxRequest();
		$app     = JFactory::getApplication();
		$version = $this->input->getString('version', '');

		if (empty($version))
		{
			$app->sendHeaders();
			echo json_encode((object) array('msg' => JText::_('COM_REDSHOP_TOOL_AJAX_ERROR_VERSION_NOT_FOUND'), 'continue' => 0));
			$app->close();
		}

		$tasks = RedshopInstall::getUpdateTasks($version);

		if (!empty($tasks))
		{
			$versionTasks = array();

			foreach ($tasks->tasks as $task)
			{
				$versionTasks[] = array('func' => $task['func'], 'path' => $tasks->path);
			}

			$app->setUserState(RedshopInstall::REDSHOP_INSTALL_STATE_NAME, $versionTasks);
		}
		else
		{
			$app->setUserState(RedshopInstall::REDSHOP_INSTALL_STATE_NAME, null);
		}

		$app->sendHeaders();
		echo json_encode($tasks);
		$app->close();
	}

	/**
	 * Method for run migrate file function.
	 *
	 * @return  void
	 * @throws  Exception
	 */
	public function ajaxRunUpdateSql()
	{
		\Redshop\Helper\Ajax::validateAjaxRequest();
		$app     = JFactory::getApplication();
		$version = $this->input->getString('version', '');
		$file    = JPath::clean(JPATH_COMPONENT_ADMINISTRATOR . '/sql/updates/mysql/' . $version . '.sql');

		if (empty($version) || !JFile::exists($file))
		{
			$app->sendHeaders();
			echo json_encode((object) array('msg' => JText::_('COM_REDSHOP_TOOL_AJAX_ERROR_VERSION_NOT_FOUND'), 'continue' => 0));
			$app->close();
		}

		$buffer = file_get_contents(JPATH_COMPONENT_ADMINISTRATOR . '/sql/updates/mysql/' . $version . '.sql');

		// Graceful exit and rollback if read not successful
		if (false === $buffer)
		{
			$app->setHeader('status', 500);
			$app->sendHeaders();
			echo JText::_('JLIB_INSTALLER_ERROR_SQL_READBUFFER');
			$app->close();
		}

		// Create an array of queries from the sql file
		$queries = JDatabaseDriver::splitSql($buffer);

		if (count($queries) === 0)
		{
			// No queries to process
			$app->sendHeaders();
			echo json_encode((object) array('msg' => JText::_('COM_REDSHOP_TOOL_UPDATE_DB_MIGRATE_SUCCESS'), 'continue' => 0));
			$app->close();
		}

		$db = JFactory::getDbo();

		// Process each query in the $queries array (split out of sql file).
		foreach ($queries as $query)
		{
			$db->setQuery($db->convertUtf8mb4QueryToUtf8($query));

			try
			{
				$db->execute();
			}
			catch (JDatabaseExceptionExecuting $e)
			{
				$app->setHeader('status', 500);
				$app->sendHeaders();
				echo JText::sprintf('JLIB_INSTALLER_ERROR_SQL_ERROR', $e->getMessage());
				$app->close();
			}
		}

		$app->sendHeaders();
		echo json_encode((object) array('msg' => JText::_('COM_REDSHOP_TOOL_UPDATE_DB_MIGRATE_SUCCESS'), 'continue' => 0));
		$app->close();
	}
}
