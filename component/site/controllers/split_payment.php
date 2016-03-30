<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


/**
 * split payment Controller.
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 * @since       1.0
 */
class RedshopControllerSplit_payment extends RedshopController
{
	/**
	 * payremaining function
	 *
	 * @access public
	 * @return void
	 */
	public function payremaining()
	{
		$model       = $this->getModel('split_payment');
		$orderresult = $model->orderplace();

		parent::display();
	}
}
