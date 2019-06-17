<?php
/**
 * @package     redSHOP
 * @subpackage  Steps
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace Frontend\payment;

use AuthorizeDPMPaymentPage;
use CheckoutOnFrontEnd;

/**
 * Class CheckoutWithtAuthorizeDPMPayment
 * @package Frontend\payment
 * @since 2.1.2
 */
class CheckoutWithtAuthorizeDPMPayment extends CheckoutOnFrontEnd
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
	public function checkoutProductWithAuthorizeDPMPayment( $user, $password, $checkoutAccountDetail, $productName, $categoryName)
	{
		$I = $this;
		$I->doFrontEndLogin($user,$password);
		$I->amOnPage(AuthorizeDPMPaymentPage::$URL);
		$I->waitForElement(AuthorizeDPMPaymentPage::$categoryDiv, 60);
		$productFrontEndManagerPage = new \AuthorizeDPMPaymentPage;
		$I->addToCart($categoryName, $productName);
		$I->amOnPage(AuthorizeDPMPaymentPage::$cartPageUrL);
		$I->seeElement(['link' => $productName]);
		$I->click(AuthorizeDPMPaymentPage:: $checkoutButton);
		$I->waitForElementVisible(AuthorizeDPMPaymentPage::$labelPayment);
		$I->click(AuthorizeDPMPaymentPage::$paymentAuthorizeDPM);
		$I->waitForElement(AuthorizeDPMPaymentPage:: $cardName, 60);
		$I->fillField(AuthorizeDPMPaymentPage:: $cardName, $checkoutAccountDetail['customerName']);
		$I->fillField(AuthorizeDPMPaymentPage:: $cardNumber, $checkoutAccountDetail['debitCardNumber']);
		$I->fillField( AuthorizeDPMPaymentPage::$cardCode, $checkoutAccountDetail['cvv']);
		$I->selectOption(AuthorizeDPMPaymentPage:: $selectExpireMonth,$checkoutAccountDetail['cardExpiryMonth']);
		$I->selectOption(AuthorizeDPMPaymentPage:: $selectExpireYear, $checkoutAccountDetail['cardExpiryMonth']);
		$I->click(AuthorizeDPMPaymentPage::$typeCard);
		$I->waitForElement($productFrontEndManagerPage->product($productName), 60);
		$I->waitForElementVisible($productFrontEndManagerPage->product($productName), 30);

		$I->waitForElementVisible(AuthorizeDPMPaymentPage::$acceptTerms, 30);
		$I->scrollTo(AuthorizeDPMPaymentPage::$acceptTerms);
		$I->executeJS($productFrontEndManagerPage->radioCheckID(AuthorizeDPMPaymentPage::$termAndConditionsId));
		$I->wait(0.5);
		try
		{
			$I->seeCheckboxIsChecked(AuthorizeDPMPaymentPage::$termAndConditions);
		}catch (\Exception $e)
		{
			$I->click(AuthorizeDPMPaymentPage::$termAndConditions);
		}
		$I->waitForElementVisible(AuthorizeDPMPaymentPage::$checkoutFinalStep);
		$I->click(AuthorizeDPMPaymentPage::$checkoutFinalStep);
		$I->waitForElementNotVisible(AuthorizeDPMPaymentPage::$checkoutFinalStep, 30);
		$I->dontSeeInCurrentUrl('checkout');
	}
}