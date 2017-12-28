<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class ManageCouponAdministratorCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class CouponCest
{
	/**
	 * CouponCest constructor.
	 */
	public function __construct()
	{
		$this->faker            = Faker\Factory::create();
		$this->couponCode       = $this->faker->bothify('Coupon Code ?##?');
		$this->updateCouponCode = 'New ' . $this->couponCode;
		$this->couponValueIn    = 'Total';
		$this->couponValue      = '100';
		$this->couponType       = 'Global';
		$this->couponLeft       = '10';
	}

	/**
	 * Function to Test Coupon Creation in Backend
	 *
	 * @param   AcceptanceTester  $I         Acceptance
	 * @param   string            $scenario  Scenario
	 *
	 * @return  void
	 */
	public function createCoupon(AcceptanceTester $I, $scenario)
	{
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\CouponSteps($scenario);
		$I->addCoupon($this->couponCode, $this->couponValueIn, $this->couponValue, $this->couponType, $this->couponLeft);
		$I->searchCoupon($this->couponCode);
	}

	/**
	 * Function to Test Coupon Update in the Administrator
	 *
	 * @param   AcceptanceTester  $I         Acceptance
	 * @param   string            $scenario  Scenario
	 *
	 * @depends createCoupon
	 *
	 * @return  void
	 */
	public function updateCoupon(AcceptanceTester $I, $scenario)
	{
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\CouponSteps($scenario);
		$I->editCoupon($this->couponCode, $this->updateCouponCode);
		$I->searchCoupon($this->updateCouponCode);
	}

	/**
	 * Function to Test Coupon Deletion
	 *
	 * @depends updateCoupon
	 */
	public function deleteCoupon(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Deletion of Coupon in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\CouponSteps($scenario);
		$I->wantTo('Delete a Coupon');
		$I->deleteCoupon($this->updateCouponCode);
	}
}
