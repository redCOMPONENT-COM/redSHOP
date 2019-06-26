<?php
/**
 * @package     redSHOP
 * @subpackage  Steps
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace Frontend\payment;
use AuthorizeDPMPaymentPage;
use CheckoutMissingData;

/**
 * Class CheckoutWithtAuthorizeDPMPayment
 * @package Frontend\payment
 * @since 2.1.2
 */
class CheckoutWithtAuthorizeDPMPayment extends CheckoutMissingData
{
	/**
	 * @param $checkoutAccountDetail
	 * @param $productName
	 * @param $categoryName
	 * @param $customerInformation
	 * @param $function
	 * @throws \Exception
	 */
	public function checkoutProductWithAuthorizeDPMPayment($checkoutAccountDetail, $productName, $categoryName, $customerInformation, $function)
	{
		$I = $this;
		$I->amOnPage(AuthorizeDPMPaymentPage::$URL);
		$I->waitForElement(AuthorizeDPMPaymentPage::$categoryDiv, 60);
		$productFrontEndManagerPage = new \AuthorizeDPMPaymentPage;
		$I->addToCart($categoryName, $productName);
		$I->amOnPage(AuthorizeDPMPaymentPage::$cartPageUrL);
		$I->waitForElementVisible(['link' => $productName], 30);
		$I->click(AuthorizeDPMPaymentPage:: $checkoutButton);
		switch ($function) {
			case 'login':
				$I->doFrontEndLogin($customerInformation["userName"], $customerInformation["password"]);
				$I->amOnPage(AuthorizeDPMPaymentPage:: $checkoutURL);
				break;
			case 'OneStepCheckout':
				$I->fillInformationPrivate($customerInformation);
				break;
		}
		$I->waitForElementVisible(AuthorizeDPMPaymentPage::$labelPayment, 30);
		$I->executeJS("window.scrollTo(150,150);");
		$I->waitForElementVisible(AuthorizeDPMPaymentPage::$paymentAuthorizeDPM, 30);
		$I->wait(0.5);
		$I->click(AuthorizeDPMPaymentPage::$paymentAuthorizeDPM);
		$I->waitForElementVisible(AuthorizeDPMPaymentPage::$typeCard, 30);
		$I->click(AuthorizeDPMPaymentPage::$typeCard);
		$I->waitForElementVisible(AuthorizeDPMPaymentPage:: $selectExpireMonth, 30);
		$I->selectOption(AuthorizeDPMPaymentPage:: $selectExpireMonth, $checkoutAccountDetail['cardExpiryMonth']);
		$I->waitForElementVisible(AuthorizeDPMPaymentPage:: $selectExpireYear, 30);
		$I->selectOption(AuthorizeDPMPaymentPage:: $selectExpireYear, $checkoutAccountDetail['cardExpiryYear']);
		$I->waitForElementVisible(AuthorizeDPMPaymentPage:: $cardName, 30);
		$I->fillField(AuthorizeDPMPaymentPage:: $cardName, $checkoutAccountDetail['customerName']);
		$I->waitForElementVisible(AuthorizeDPMPaymentPage:: $cardNumber, 30);
		$I->fillField(AuthorizeDPMPaymentPage:: $cardNumber, $checkoutAccountDetail['debitCardNumber']);
		$I->waitForElementVisible(AuthorizeDPMPaymentPage:: $cardCode, 30);
		$I->fillField( AuthorizeDPMPaymentPage::$cardCode, $checkoutAccountDetail['cvv']);
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
		try
		{
			$I->waitForElementNotVisible(AuthorizeDPMPaymentPage::$checkoutFinalStep, 30);
		}catch (\Exception $e)
		{
			$I->click(AuthorizeDPMPaymentPage::$termAndConditions);
			$I->waitForElementVisible(AuthorizeDPMPaymentPage::$checkoutFinalStep);
			$I->click(AuthorizeDPMPaymentPage::$checkoutFinalStep);
			$I->waitForElementNotVisible(AuthorizeDPMPaymentPage::$checkoutFinalStep, 30);
		}
		$I->dontSeeInCurrentUrl(AuthorizeDPMPaymentPage::$uriCheckout);
	}
}
