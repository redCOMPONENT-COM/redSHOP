<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;

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
	 * Method for get list of state base on specific country value.
	 *
	 * @return  void
	 *
	 * @since   2.0.3
	 */
	public function ajaxGetState()
	{
		RedshopHelperAjax::validateAjaxRequest();

		$app = JFactory::getApplication();

		$country = $app->input->getString('country', '');
		$html = RedshopHelperWorld::getStateList(array('country_code' => $country), 'jform[tax_state]', 'BT', 'form-control', 'state_3_code');

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
