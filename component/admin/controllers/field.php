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
 * Field controller
 *
 * @package     RedSHOP.Backend
 * @subpackage  Controller.Field
 * @since       2.0.6
 */
class RedshopControllerField extends RedshopControllerForm
{
	/**
	 * Method for get all exist field name
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 */
	public function ajaxGetAllFieldName()
	{
		RedshopHelperAjax::validateAjaxRequest();

		$app = JFactory::getApplication();
		$model = $this->getModel('Field');

		echo implode(',', $model->getExistFieldNames($app->input->getInt('field_id', 0)));

		$app->close();
	}
}
