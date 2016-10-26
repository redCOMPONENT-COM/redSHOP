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
 * Controller Zipcode Detail
 *
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 * @since       2.0.0.8
 */
class RedshopControllerZipcode extends RedshopControllerForm
{
	/**
	 * [ajaxGetState2Code function]
	 * 
	 * @return void;
	 */
	function ajaxGetState2Code()
	{
		$model = $this->getModel();
		$form = $model->getForm();
		echo $form->renderField('state_code');

		exit;
	}
}
