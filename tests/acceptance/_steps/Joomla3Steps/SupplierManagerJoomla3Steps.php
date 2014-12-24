<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace AcceptanceTester;
/**
 * Class SupplierManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class SupplierManagerJoomla3Steps extends AdminManagerJoomla3Steps
{
	/**
	 * Function to Add a New Supplier
	 *
	 * @return void
	 */
	public function addSupplier()
	{
		$I = $this;
		$I->amOnPage(\SupplierManagerPage::$URL);
		$I->verifyNotices(false, $this->checkForNotices(), 'Supplier Manager Page');
		$I->click('New');
		$I->verifyNotices(false, $this->checkForNotices(), 'Supplier Manager New');
		$I->click('Cancel');
	}
}
