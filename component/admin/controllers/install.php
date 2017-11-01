<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Install controller
 *
 * @package     RedSHOP.backend
 * @subpackage  Controller
 * @since       2.0.4
 */
class RedshopControllerInstall extends RedshopControllerAdmin
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
	 * @since   1.6
	 */
	public function getModel($name = 'Install', $prefix = 'RedshopModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}

	/**
	 * Method for run ajax process.
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 */
	public function ajaxProcess()
	{
		RedshopHelperAjax::validateAjaxRequest();
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
			$return['msg'] = JText::_('COM_REDSHOP_INSTALL_ERROR_MISSING_PROCESS');
			echo json_encode((object) $return);
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
			$return['msg'] = JText::sprintf('COM_REDSHOP_INSTALL_ERROR_MISSING_CLASS', $className);
			echo json_encode((object) $return);
			$app->close();
		}

		// Check method exist in class
		if (!method_exists($className, $method))
		{
			$app->setHeader('status', 500);
			$app->sendHeaders();
			$return['msg'] = JText::sprintf('COM_REDSHOP_INSTALL_ERROR_MISSING_METHOD_IN_CLASS', $className, $method);
			echo json_encode((object) $return);
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
			$return['msg'] = $error->getMessage();
			echo json_encode((object) $return);
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
}
