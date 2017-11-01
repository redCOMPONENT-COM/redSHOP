<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace AcceptanceTester;
/**
 * Class CouponManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class CouponManagerJoomla3Steps extends AdminManagerJoomla3Steps
{
	/**
	 * Function to Add a new Coupon Code
	 *
	 * @param   string  $couponCode     Code for the new Coupon
	 * @param   string  $couponValueIn  Value In for the new Coupon
	 * @param   string  $couponValue    Value for the Coupon
	 * @param   string  $couponType     Type of the Coupon
	 * @param   string  $couponLeft     No of Coupons Left in the System
	 *
	 * @return void
	 */
	public function addCoupon($couponCode = 'TestCoupon', $couponValueIn = 'Total', $couponValue = '100', $couponType = 'Globally', $couponLeft = '10')
	{
		$I = $this;
		$I->amOnPage(\CouponManagerJ3Page::$URL);
		$couponManagerPage = new \CouponManagerJ3Page;
		$I->verifyNotices(false, $this->checkForNotices(), 'Coupon Manager Page');
		$I->click('New');
		$I->verifyNotices(false, $this->checkForNotices(), 'Coupon Manager New');
		$I->fillField(\CouponManagerJ3Page::$couponCode, $couponCode);
		$I->fillField(\CouponManagerJ3Page::$couponValue, $couponValue);
		$I->fillField(\CouponManagerJ3Page::$couponLeft, $couponLeft);
		$I->click(\CouponManagerJ3Page::$couponValueInDropDown);
		$I->click($couponManagerPage->couponValueIn($couponValueIn));
		$I->click('Save & Close');
		$I->waitForElement(['id' => 'system-message-container'], 60);
		$I->see('Coupon detail saved', '.alert-success');
		$I->seeElement(['link' => $couponCode]);
	}

	/**
	 * Function to Edit Coupon Code
	 *
	 * @param   string  $couponCode     Coupon Code which is to be Edited
	 * @param   string  $newCouponCode  New Coupon Code for the current one
	 *
	 * @return void
	 */
	public function editCoupon($couponCode = 'Current Code', $newCouponCode = 'Testing New')
	{
		$I = $this;
		$I->amOnPage(\CouponManagerJ3Page::$URL);
		$I->executeJS('window.scrollTo(0,0)');
		$I->click(['link' => 'ID']);
		$I->see($couponCode, \CouponManagerJ3Page::$firstResultRow);
		$I->click(\CouponManagerJ3Page::$selectFirst);
		$I->click('Edit');
		$I->verifyNotices(false, $this->checkForNotices(), 'Coupon Edit View');
		$I->waitForElement(\CouponManagerJ3Page::$couponCode, 20);
		$I->fillField(\CouponManagerJ3Page::$couponCode, $newCouponCode);
		$I->click('Save & Close');
		$I->waitForElement(['id' => 'system-message-container'], 60);
		$I->see('Coupon detail saved', '.alert-success');
		$I->seeElement(['link' => $newCouponCode]);
	}

	/**
	 * Function to delete the Coupon
	 *
	 * @param   string  $couponCode  Code of the Coupon which is to be Deleted
	 *
	 * @return void
	 */
	public function deleteCoupon($couponCode = 'Test Coupon')
	{
		$this->delete(new \CouponManagerJ3Page, $couponCode, \CouponManagerJ3Page::$firstResultRow, \CouponManagerJ3Page::$selectFirst);
	}

	/**
	 * Function to Search for a Coupon Code
	 *
	 * @param   string  $couponCode    Code of the Coupon for which we are searching
	 * @param   string  $functionName  Name of the function after which Search is being called
	 *
	 * @return void
	 */
	public function searchCoupon($couponCode = 'Test Coupon', $functionName = 'Search')
	{
		$this->search(new \CouponManagerJ3Page, $couponCode, \CouponManagerJ3Page::$firstResultRow, $functionName);
	}
}
