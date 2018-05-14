<?php
/**
 * Checkout With Discount Voucher Coupon
 */
use AcceptanceTester\CheckoutDiscountVoucherCouponSteps;
/**
 * Class CheckoutDiscountVoucherCouponCest
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  2.1
 */
class CheckoutDiscountVoucherCouponCest
{
    public function _before(AcceptanceTester $I)
    {
        $I->doAdministratorLogin();
    }

    public function CheckoutWithMassDiscount()
    {

    }
}