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
 * Controller Supplier Detail
 *
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 * @since       2.0.4
 */
class RedshopControllerSupplier extends RedshopControllerForm
{
	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  object  The model.
	 *
	 * @since   2.1.0
	 */
	public function getModel($name = 'Supplier', $prefix = 'RedshopModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}

	/**
	 * Validate new supplier
	 *
	 * @return void
	 * @since   __DEPLOY_VERSION__
	 */
	public function ajaxValidateNewSupplier()
	{
		$app   = JFactory::getApplication();
		$model = $this->getModel();
		$email = $app->input->getString('email');
		$flag  = 1;

		if (!$model->getSupplierByEmail($email))
		{
			$flag = 0;
		}

		echo $flag;

		$app->close();
	}
}
