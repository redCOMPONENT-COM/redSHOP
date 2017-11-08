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
 * The search controller
 *
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 * @since       1.5
 */
class RedshopControllerSearch extends RedshopController
{
	/**
	 * Method for searching
	 *
	 * @return	json
	 */
	public function search()
	{
		echo $this->getModel('search')->search();
		JFactory::getApplication()->close();
	}
}
