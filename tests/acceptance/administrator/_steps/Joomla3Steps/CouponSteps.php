<?php
/**
 * @package     redSHOP
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Step\AbstractStep;

/**
 * Class CouponManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class CouponSteps extends AbstractStep
{
	use Step\Traits\CheckIn, Step\Traits\Publish, Step\Traits\Delete;

	/**
	 * @param array $data
	 * @param $type
	 * @param $startDate
	 * @param $endDate
	 * @throws Exception
	 * @since 2.1.3
	 */
	public function checkStartDateLargerThanEndDate($data = array(), $type, $startDate, $endDate)
	{
		$I = $this;
		$I->amOnPage(CouponPage::$url);
		$I->checkForPhpNoticesOrWarnings();
		$I->click(CouponPage::$buttonNew);
		$I->checkForPhpNoticesOrWarnings();
		$I->fillFormData($this->getFormFields(), $data);
		$I->click(CouponPage::$startDateField);
		$I->addValueForField(CouponPage::$startDateField, $startDate, 10);
		$I->selectOption(CouponPage::$couponType, $type);
		$I->addValueForField(CouponPage::$endDateField, $endDate, 10);
		$I->click(CouponPage::$buttonSave);
		$I->assertSystemMessageContains(CouponPage::$messageFail);
	}

	/**
	 * @param $userName
	 * @param $password
	 * @param $productName
	 * @param $categoryName
	 * @param $couponCode
	 * @param $total
	 * @param $discountPrice
	 * @param $subTotal
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function checkoutProductWithCouponCode($userName, $password, $productName, $categoryName, $couponCode, $total, $discountPrice, $subTotal)
	{
		$I = $this;
		$I->doFrontEndLogin($userName, $password);
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->waitForElementVisible($productFrontEndManagerPage->product($productName));
		$I->click($productFrontEndManagerPage->product($productName));
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$addToCart);
		$I->click(FrontEndProductManagerJoomla3Page::$addToCart);
		$I->waitForText(GiftCardCheckoutPage::$alertSuccessMessage, 60, GiftCardCheckoutPage::$selectorSuccess);
		$I->see(GiftCardCheckoutPage::$alertSuccessMessage, GiftCardCheckoutPage::$selectorSuccess);
		$I->amOnPage(GiftCardCheckoutPage::$cartPageUrL);
		$I->seeElement(['link' => $productName]);
		$I->fillField(GiftCardCheckoutPage::$couponInput, $couponCode);
		$I->click(GiftCardCheckoutPage::$couponButton);
		$I->waitForText(GiftCardCheckoutPage::$messageValid, 10, GiftCardCheckoutPage::$selectorSuccess);
		$I->see(GiftCardCheckoutPage::$messageValid, GiftCardCheckoutPage::$selectorSuccess);

		$I->see($total, GiftCardCheckoutPage::$priceTotal);
		$I->see($discountPrice, GiftCardCheckoutPage::$priceDiscount);
		$I->see($subTotal, GiftCardCheckoutPage::$priceEnd);
	}
}
