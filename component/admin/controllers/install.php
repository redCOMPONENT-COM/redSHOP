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
 * Install controller
 *
 * @package     RedSHOP.backend
 * @subpackage  Controller
 * @since       __DEPLOY_VERSION__
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
	 * Method for handle configuration file.
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function handleConfig()
	{
		RedshopHelperAjax::validateAjaxRequest();
		$app = JFactory::getApplication();

		try
		{
			// Only loading from legacy when version is older than 1.6
			if (version_compare(RedshopHelperJoomla::getManifestValue('version'), '1.6', '<'))
			{
				// Load configuration file from legacy file.
				Redshop::getConfig()->loadLegacy();
			}

			// Try to load distinct if no config found.
			Redshop::getConfig()->loadDist();
		}
		catch (Exception $e)
		{
			$app->setHeader('status', 500);
			$app->sendHeaders();
			echo $e->getMessage();
			$app->close();
		}

		$app->sendHeaders();
		echo JText::_('COM_REDSHOP_INSTALL_STEP_SUCCESS');
		$app->close();
	}

	/**
	 * Method for synchronize user.
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function syncUser()
	{
		RedshopHelperAjax::validateAjaxRequest();
		$app = JFactory::getApplication();

		try
		{
			rsUserHelper::getInstance()->userSynchronization();
		}
		catch (Exception $e)
		{
			$app->setHeader('status', 500);
			$app->sendHeaders();
			echo $e->getMessage();
			$app->close();
		}

		$app->sendHeaders();
		echo JText::_('COM_REDSHOP_INSTALL_STEP_SUCCESS');
		$app->close();
	}

	/**
	 * Method for template data.
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function templateData()
	{
		RedshopHelperAjax::validateAjaxRequest();
		$app = JFactory::getApplication();

		/** @var RedshopModelInstall $model */
		$model = $this->getModel();

		if (!$model->processTemplateDemo())
		{
			$app->setHeader('status', 500);
			$app->sendHeaders();
			echo JText::_('COM_REDSHOP_INSTALL_STEP_FAIL');
			$app->close();
		}

		$app->sendHeaders();
		echo JText::_('COM_REDSHOP_INSTALL_STEP_SUCCESS');
		$app->close();
	}

	/**
	 * Method for process template files.
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function templateFiles()
	{
		RedshopHelperAjax::validateAjaxRequest();
		$app = JFactory::getApplication();

		/** @var RedshopModelInstall $model */
		$model = $this->getModel();

		if (!$model->processTemplateFiles())
		{
			$app->setHeader('status', 500);
			$app->sendHeaders();
			echo JText::_('COM_REDSHOP_INSTALL_STEP_FAIL');
			$app->close();
		}

		$app->sendHeaders();
		echo JText::_('COM_REDSHOP_INSTALL_STEP_SUCCESS');
		$app->close();
	}

	/**
	 * Method for update menu item id if necessary.
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function updateMenu()
	{
		RedshopHelperAjax::validateAjaxRequest();
		$app = JFactory::getApplication();

		/** @var RedshopModelInstall $model */
		$model = $this->getModel();

		if (!$model->processUpdateMenuItem())
		{
			$app->setHeader('status', 500);
			$app->sendHeaders();
			echo JText::_('COM_REDSHOP_INSTALL_STEP_FAIL');
			$app->close();
		}

		$app->sendHeaders();
		echo JText::_('COM_REDSHOP_INSTALL_STEP_SUCCESS');
		$app->close();
	}

	/**
	 * Method for integrate with com_sh404sef extension if necessary.
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function integrateSh404sef()
	{
		RedshopHelperAjax::validateAjaxRequest();
		$app = JFactory::getApplication();

		/** @var RedshopModelInstall $model */
		$model = $this->getModel();

		if (!$model->processIntegrateSh404sef())
		{
			$app->setHeader('status', 500);
			$app->sendHeaders();
			echo JText::_('COM_REDSHOP_FAILED_TO_COPY_SH404SEF_PLUGIN_LANGUAGE_FILE');
			$app->close();
		}

		$app->sendHeaders();
		echo JText::_('COM_REDSHOP_INSTALL_STEP_SUCCESS');
		$app->close();
	}

	/**
	 * Method for check database structure when update.
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function updateCheckDatabase()
	{
		RedshopHelperAjax::validateAjaxRequest();
		$app = JFactory::getApplication();

		/** @var RedshopModelInstall $model */
		$model = $this->getModel();

		if (!$model->processUpdateCheckDatabase())
		{
			$app->setHeader('status', 500);
			$app->sendHeaders();
			echo JText::_('COM_REDSHOP_INSTALL_STEP_FAIL');
			$app->close();
		}

		$app->sendHeaders();
		echo JText::_('COM_REDSHOP_INSTALL_STEP_SUCCESS');
		$app->close();
	}

	/**
	 * Method for update override template when update.
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function updateOverrideTemplate()
	{
		RedshopHelperAjax::validateAjaxRequest();
		$app = JFactory::getApplication();

		/** @var RedshopModelInstall $model */
		$model = $this->getModel();

		if (!$model->processUpdateOverrideTemplate())
		{
			$app->setHeader('status', 500);
			$app->sendHeaders();
			echo JText::_('COM_REDSHOP_INSTALL_STEP_FAIL');
			$app->close();
		}

		$app->sendHeaders();
		echo JText::_('COM_REDSHOP_INSTALL_STEP_SUCCESS');
		$app->close();
	}

	/**
	 * Method for clean old unused files and folders.
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function updateCleanOldFiles()
	{
		RedshopHelperAjax::validateAjaxRequest();
		$app = JFactory::getApplication();

		/** @var RedshopModelInstall $model */
		$model = $this->getModel();

		if (!$model->processUpdateCleanOldFiles())
		{
			$app->setHeader('status', 500);
			$app->sendHeaders();
			echo JText::_('COM_REDSHOP_INSTALL_STEP_FAIL');
			$app->close();
		}

		$app->sendHeaders();
		echo JText::_('COM_REDSHOP_INSTALL_STEP_SUCCESS');
		$app->close();
	}

	/**
	 * Method to update schema table if necessary. From redshop 1.3.3.1
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function updateDatabaseSchema()
	{
		RedshopHelperAjax::validateAjaxRequest();
		$app = JFactory::getApplication();

		/** @var RedshopModelInstall $model */
		$model = $this->getModel();

		$version = $model->processUpdateDatabaseSchema();

		if ($version === false)
		{
			$app->setHeader('status', 500);
			$app->sendHeaders();
			echo JText::_('COM_REDSHOP_INSTALL_STEP_FAIL');
			$app->close();
		}

		$app->sendHeaders();
		echo $version;
		$app->close();
	}

	/**
	 * Update > Method for rename image files name to correct format.
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function updateImageFileNames()
	{
		RedshopHelperAjax::validateAjaxRequest();
		$app = JFactory::getApplication();

		/** @var RedshopModelInstall $model */
		$model = $this->getModel();

		if (!$model->processUpdateImageFileNames())
		{
			$app->setHeader('status', 500);
			$app->sendHeaders();
			echo JText::_('COM_REDSHOP_INSTALL_STEP_FAIL');
			$app->close();
		}

		$app->sendHeaders();
		echo JText::_('COM_REDSHOP_INSTALL_STEP_SUCCESS');
		$app->close();
	}
}
