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
 * The state controller
 *
 * @package     RedSHOP.Backend
 * @subpackage  Controller.State
 * @since       2.0.0.4
 */
class RedshopControllerState extends RedshopControllerForm
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
	public function getModel($name = 'State', $prefix = 'RedshopModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}

	/**
	 * Method for get list of state base on specific country value.
	 *
	 * @return  void
	 * @throws  Exception
	 *
	 * @since   2.0.3
	 */
	public function ajaxGetState()
	{
		\Redshop\Helper\Ajax::validateAjaxRequest();

		$app = JFactory::getApplication();

		$country = $app->input->getString('country', '');
		$html    = RedshopHelperWorld::getStateList(array('country_code' => $country), 'jform[tax_state]', 'BT', 'form-control', 'state_3_code');

		if (!empty($html))
		{
			echo $html['state_dropdown'];
		}
		else
		{
			echo '';
		}

		$app->close();
	}
}
