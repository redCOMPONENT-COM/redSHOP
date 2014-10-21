<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace AcceptanceTester;
/**
 * Class CouponManagerSteps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class CouponManagerSteps extends AdminManagerSteps
{
	/**
	 * Function to Add a new Coupon
	 *
	 * @return  void
	 */
	public function addCoupon()
	{
		$I = $this;
		$I->amOnPage(\CountryManagerPage::$URL);
		$I->verifyNotices(false, $this->checkForNotices(), 'Coupon Manager Page');
		$I->click('New');
		$I->verifyNotices(false, $this->checkForNotices(), 'Coupon Manager New');
		$I->click('Cancel');
	}
}
