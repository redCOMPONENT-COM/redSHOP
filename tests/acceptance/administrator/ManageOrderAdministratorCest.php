<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
use \AcceptanceTester;
/**
 * Class ManageOrderAdministratorCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class ManageOrderAdministratorCest
{
	/**
	 * Function to Test Order Creation in Backend
	 *
	 */
	public function createOrder(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Order creation in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\OrderManagerJoomla3Steps($scenario);
		$I->addOrder();
	}
}
