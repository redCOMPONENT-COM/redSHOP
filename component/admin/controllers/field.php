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
 * Field controller
 *
 * @package     RedSHOP.Backend
 * @subpackage  Controller.Field
 * @since       2.0.6
 */
class RedshopControllerField extends RedshopControllerForm
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
	public function getModel($name = 'Field', $prefix = 'RedshopModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}

	/**
	 * Method for get all exist field name
	 *
	 * @return  void
	 * @throws  Exception
	 *
	 * @since   2.0.6
	 */
	public function ajaxGetAllFieldName()
	{
		\Redshop\Helper\Ajax::validateAjaxRequest();

		$app   = JFactory::getApplication();
		$model = $this->getModel('Field');

		echo implode(',', $model->getExistFieldNames($app->input->getInt('field_id', 0)));

		$app->close();
	}
}
