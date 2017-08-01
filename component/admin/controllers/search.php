<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopControllerSearch extends RedshopController
{
	public function cancel()
	{
		$this->setRedirect('index.php');
	}

	/**
	 * AJAX Task to get states list
	 *
	 * @return  string  JSON encoded string of states list.
	 */
	public function getStatesAjax()
	{
		// Only verify token
		RedshopHelperAjax::validateAjaxRequest('get');

		$app = JFactory::getApplication();

		ob_clean();

		echo RedshopHelperWorld::getStatesAjax($app->input->getCmd('country'));

		$app->close();
	}
}

