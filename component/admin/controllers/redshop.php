<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class RedshopControllerRedshop extends RedshopController
{
	public function demoContentInsert()
	{
		/** @var RedshopModelRedshop $model */
		$model = $this->getModel('redshop');

		$model->demoContentInsert();
		$msg = JText::_('COM_REDSHOP_SAMPLE_DATA_INSTALLED');

		$this->setRedirect('index.php?option=com_redshop', $msg);
	}

	/**
	 * Get default configuration file back
	 *
	 * @since   1.7
	 *
	 * @return  void
	 */
	public function getDefaultConfig()
	{
		$app = JFactory::getApplication();

		if (!Redshop::getConfig()->loadDist())
		{
			$app->enqueueMessage(JText::_('LIB_REDSHOP_ERROR_WRITE_FAILED'), 'error');
		}

		$app->redirect('index.php?option=com_redshop');
	}
}
