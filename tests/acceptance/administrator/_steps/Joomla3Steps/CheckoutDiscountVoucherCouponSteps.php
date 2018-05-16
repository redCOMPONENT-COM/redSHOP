<?php


namespace AcceptanceTester;

/**
 * Class CheckoutDiscountVoucherCouponSteps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    2.1
 */
class CheckoutDiscountVoucherCouponSteps extends AdminManagerJoomla3Steps
{
    public function selectConditionDiscount()
    {
        $I = $this;
        $I->amOnPage(\CheckoutDiscountVoucherCouponPage::$URL);
        $I->click(\CheckoutDiscountVoucherCouponPage::$Price);
        $I->click(\CheckoutDiscountVoucherCouponPage::$enableYes);
        $I->pauseExecution();
        $I->click(\CheckoutDiscountVoucherCouponPage::$searchField);
        $I->pauseExecution();
        $I->click(\CheckoutDiscountVoucherCouponPage::$inputField);
        $I->pauseExecution();
        $I->click(\CheckoutDiscountVoucherCouponPage::$ConditionID);
    }

    public function CheckoutWithMassDiscount()
    {

    }
}