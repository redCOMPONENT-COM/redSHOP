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

    public function CheckoutWithMassDiscount(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('I want to select condition discount');
        $I = new CheckoutDiscountVoucherCouponSteps($scenario);
        $I->selectConditionDiscount();

//        $I->wantTo('I want to checkout product with price discount 10% of mass discount');
//        $I = new CheckoutDiscountVoucherCouponSteps($scenario);
//        $I->CheckoutWithMassDiscount();
    }
}