<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace AcceptanceTester;
/**
 * Class ManufacturerManagerSteps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class ManufacturerManagerSteps extends AdminManagerSteps
{
	/**
	 * Function to Add a new Manufacturer
	 *
	 * @return void
	 */
	public function addManufacturer()
	{
		$I = $this;
		$I->amOnPage(\ManufacturerManagerPage::$URL);
		$I->verifyNotices(false, $this->checkForNotices(), 'Manufacturer Manager Page');
		$I->click('New');
		$I->verifyNotices(false, $this->checkForNotices(), 'Manufacturer Manager New');
		$I->click('Cancel');
	}
}
