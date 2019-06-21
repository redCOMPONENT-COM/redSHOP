<?php
/**
 * @package     redSHOP
 * @subpackage  Steps
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Frontend\payment;
use FrontEndEWAYPaymentPage;

/**
 * Class CheckoutWithEWAYPayment
 * @package Frontend\payment
 * @since 2.1.2
 */
class CheckoutWithEWAYPayment extends \CheckoutOnFrontEnd
{
	/**
	 * @param $user
	 * @param $password
	 * @param $checkoutAccountDetail
	 * @param $productName
	 * @param $categoryName
	 * @throws \Exception
	 * @since 2.1.2
	 */
	public function checkoutProductWithEWAYPayment( $user, $password, $checkoutAccountDetail, $productName, $categoryName)
	{
		$I = $this;
		$I->doFrontEndLogin($user,$password);
		$I->amOnPage(FrontEndEWAYPaymentPage::$URL);
		$I->waitForElement(FrontEndEWAYPaymentPage::$categoryDiv, 60);
		$productFrontEndManagerPage = new \FrontEndEWAYPaymentPage;
		$I->addToCart($categoryName, $productName);
		$I->amOnPage(FrontEndEWAYPaymentPage::$cartPageUrL);
		$I->seeElement(['link' => $productName]);
		$I->click(FrontEndEWAYPaymentPage:: $checkoutButton);
		$I->waitForElementVisible(FrontEndEWAYPaymentPage::$labelPayment);
		$I->click(FrontEndEWAYPaymentPage::$paymentEWAY);
		$I->waitForElement(FrontEndEWAYPaymentPage:: $cardName, 60);
		$I->fillField(FrontEndEWAYPaymentPage:: $cardName, $checkoutAccountDetail['customerName']);
		$I->fillField(FrontEndEWAYPaymentPage:: $cardNumber, $checkoutAccountDetail['debitCardNumber']);
		$I->fillField( FrontEndEWAYPaymentPage::$cardCode, $checkoutAccountDetail['cvv']);
		$I->selectOption(FrontEndEWAYPaymentPage:: $selectExpireMonth, $checkoutAccountDetail['cardExpiryMonth']);
		$I->selectOption(FrontEndEWAYPaymentPage:: $selectExpireYear, $checkoutAccountDetail['cardExpiryMonth']);
		$I->click(FrontEndEWAYPaymentPage::$typeCard);
		$I->waitForElement($productFrontEndManagerPage->product($productName), 60);
		$I->waitForElementVisible($productFrontEndManagerPage->product($productName), 30);
		$I->waitForElementVisible(FrontEndEWAYPaymentPage::$acceptTerms, 30);
		$I->scrollTo(FrontEndEWAYPaymentPage::$acceptTerms);
		$I->executeJS($productFrontEndManagerPage->radioCheckID(FrontEndEWAYPaymentPage::$termAndConditionsId));
		$I->wait(0.5);
		try
		{
			$I->seeCheckboxIsChecked(FrontEndEWAYPaymentPage::$termAndConditions);
		}catch (\Exception $e)
		{
			$I->click(FrontEndEWAYPaymentPage::$termAndConditions);
		}
		$I->waitForElementVisible(FrontEndEWAYPaymentPage::$checkoutFinalStep);
		$I->click(FrontEndEWAYPaymentPage::$checkoutFinalStep);
		$I->waitForElementNotVisible(FrontEndEWAYPaymentPage::$checkoutFinalStep, 30);
	}
}