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
class ManageCouponAdministratorCest
{
    public function __construct()
    {
        $this->faker = Faker\Factory::create();
        $this->couponCode = $this->faker->bothify('Coupon Code ?##?');
        $this->updateCouponCode = 'New ' . $this->couponCode;
        $this->couponValueIn = 'Total';
        $this->couponValue = '100';
        $this->couponType = 'Globally';
        $this->couponLeft = '10';
    }

    /**
     * Function to Test Coupon Creation in Backend
     *
     */
    public function createCoupon(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Coupon creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\CouponManagerJoomla3Steps($scenario);
        $I->wantTo('Create a Coupon');
        $I->addCoupon($this->couponCode, $this->couponValueIn, $this->couponValue, $this->couponType, $this->couponLeft);
        $I->searchCoupon($this->couponCode);
    }

    /**
     * Function to Test Coupon Updation in the Administrator
     *
     * @depends createCoupon
     */
    public function updateCoupon(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test if Coupon gets updated in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\CouponManagerJoomla3Steps($scenario);
        $I->wantTo('Update Existing Coupon');
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
        $I = new AcceptanceTester\CouponManagerJoomla3Steps($scenario);
        $I->wantTo('Delete a Coupon');
        $I->deleteCoupon($this->updateCouponCode);
        $I->searchCoupon($this->updateCouponCode, 'Delete');
    }
}
