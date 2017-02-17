<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class ManageSupplierAdministratorCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class ManageSupplierAdministratorCest
{
	/**
	 * Function to Test Supplier Creation in Backend
	 *
	 */
	public function createSupplier(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Supplier creation in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\SupplierManagerJoomla3Steps($scenario);
		$I->addSupplier();
	}
}
