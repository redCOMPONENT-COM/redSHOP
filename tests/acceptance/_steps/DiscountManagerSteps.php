<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace AcceptanceTester;
/**
 * Class DiscountManagerSteps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class DiscountManagerSteps extends AdminManagerSteps
{
	/**
	 * Function to add a new Discount
	 *
	 * @return void
	 */
	public function addDiscount()
	{
		$I = $this;
		$I->amOnPage(\DiscountManagerPage::$URL);
		$I->verifyNotices(false, $this->checkForNotices(), 'Discount Manager Page');
		$I->click('New');
		$I->verifyNotices(false, $this->checkForNotices(), 'Discount Manager New');
		$I->click('Cancel');
	}
}
