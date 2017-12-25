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
class CouponSteps extends AdminManagerJoomla3Steps
{
	/**
	 * Function to Add a new Coupon Code
	 *
	 * @param   string   $couponCode    Code for the new Coupon
	 * @param   integer  $couponType    Value In for the new Coupon
	 * @param   string   $couponValue   Value for the Coupon
	 * @param   integer  $couponEffect  Type of the Coupon
	 * @param   string   $couponLeft    No of Coupons Left in the System
	 *
	 * @return void
	 */
	public function addCoupon($couponCode, $couponType, $couponValue, $couponEffect, $couponLeft)
	{
		$client = $this;
		$client->amOnPage(\CouponPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->click(\CouponPage::$buttonNew);
		$client->waitForElement(\CouponPage::$fieldCode, 30);
		$client->checkForPhpNoticesOrWarnings();
		$client->fillField(\CouponPage::$fieldCode, $couponCode);
		$client->fillField(\CouponPage::$fieldValue, $couponValue);
		$client->fillField(\CouponPage::$fieldAmountLeft, $couponLeft);
		$client->selectOption(\CouponPage::$fieldType, $couponType);
		$client->selectOption(\CouponPage::$fieldEffect, $couponEffect);
		$client->click(\CouponPage::$buttonSaveClose);
		$client->waitForText(\CouponPage::$messageItemSaveSuccess, 60, \CouponPage::$selectorSuccess);
		$client->see(\CouponPage::$messageItemSaveSuccess, \CouponPage::$selectorSuccess);
		$client->searchCoupon($couponCode);
		$client->see($couponCode, \CouponPage::$resultRow);
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
		$client = $this;
		$client->amOnPage(\CouponPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->searchCoupon($couponCode);
		$client->click($couponCode);
		$client->waitForElement(\CouponPage::$fieldCode, 30);
		$client->checkForPhpNoticesOrWarnings();
		$client->fillField(\CouponPage::$fieldCode, $newCouponCode);
		$client->click(\CouponPage::$buttonSaveClose);
		$client->waitForText(\CouponPage::$messageItemSaveSuccess, 60, \CouponPage::$selectorSuccess);
		$client->see(\CouponPage::$messageItemSaveSuccess, \CouponPage::$selectorSuccess);
	}

	/**
	 * Function to delete the Coupon
	 *
	 * @param   string  $couponCode  Code of the Coupon which is to be Deleted
	 *
	 * @return  void
	 */
	public function deleteCoupon($couponCode = 'Test Coupon')
	{
		$client = $this;
		$client->amOnPage(\CouponPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->searchCoupon($couponCode);
		$client->checkAllResults();
		$client->click(\CouponPage::$buttonDelete);
		$client->acceptPopup();
		$client->waitForText(\CouponPage::$messageItemDeleteSuccess, 60, \CouponPage::$selectorSuccess);
		$client->see(\CouponPage::$messageItemDeleteSuccess, \CouponPage::$selectorSuccess);
		$client->fillField(\CouponPage::$searchField, $couponCode);
		$client->pressKey(\CouponPage::$searchField, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$client->dontSee($couponCode, \CouponPage::$resultRow);
	}

	/**
	 * Function to Search for a Coupon Code
	 *
	 * @param   string  $couponCode  Code of the Coupon for which we are searching
	 *
	 * @return  void
	 */
	public function searchCoupon($couponCode = 'Test Coupon')
	{
		$client = $this;
		$client->amOnPage(\CouponPage::$url);
		$client->waitForText(\CouponPage::$namePage, 30, \CouponPage::$headPage);
		$client->filterListBySearching($couponCode);
	}
}
