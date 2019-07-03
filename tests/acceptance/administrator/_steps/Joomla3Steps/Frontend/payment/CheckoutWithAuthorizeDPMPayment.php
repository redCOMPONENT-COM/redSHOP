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
 * Class CheckoutWithAuthorizeDPMPayment
 * @package Frontend\payment
 * @since 2.1.2
 */
class CheckoutWithAuthorizeDPMPayment extends CheckoutMissingData
{
	/**
	 * @param $checkoutAccountDetail
	 * @param $productName
	 * @param $categoryName
	 * @param $customerInformation
	 * @throws \Exception
	 */
	public function checkoutProductWithAuthorizeDPMPayment($checkoutAccountDetail, $productName, $categoryName, $customerInformation)
	{
		$I = $this;
		$I->amOnPage(AuthorizeDPMPaymentPage::$URL);
		$I->waitForElement(AuthorizeDPMPaymentPage::$categoryDiv, 60);
		$productFrontEndManagerPage = new AuthorizeDPMPaymentPage;
		$I->addToCart($categoryName, $productName);
		$I->amOnPage(AuthorizeDPMPaymentPage::$cartPageUrL);
		$I->waitForElementVisible(['link' => $productName], 30);
		$I->click(AuthorizeDPMPaymentPage::$checkoutButton);
		$I->fillInformationPrivate($customerInformation);
		$I->waitForElementVisible(AuthorizeDPMPaymentPage::$labelPayment, 30);
		$I->scrollTo(AuthorizeDPMPaymentPage::$labelPayment);
		$I->waitForElementVisible(AuthorizeDPMPaymentPage::$paymentAuthorizeDPM, 30);
		$I->wait(0.5);
		$I->click(AuthorizeDPMPaymentPage::$paymentAuthorizeDPM);
		try
		{
			$I->seeCheckboxIsChecked(AuthorizeDPMPaymentPage::$paymentAuthorizeDPM);
			$I->waitForElementVisible(AuthorizeDPMPaymentPage::$cardCode, 30);
			$I->fillField( AuthorizeDPMPaymentPage::$cardCode, $checkoutAccountDetail['cvv']);
		}catch (\Exception $e)
		{
			$I->click(AuthorizeDPMPaymentPage::$paymentAuthorizeDPM);
		}

		$I->waitForElementVisible(AuthorizeDPMPaymentPage::$cardCode, 30);
		$I->fillField( AuthorizeDPMPaymentPage::$cardCode, $checkoutAccountDetail['cvv']);
		$I->waitForElementVisible(AuthorizeDPMPaymentPage:: $cardName, 30);
		$I->fillField(AuthorizeDPMPaymentPage::$cardName, $checkoutAccountDetail['customerName']);
		$I->fillField(AuthorizeDPMPaymentPage::$cardNumber, $checkoutAccountDetail['debitCardNumber']);
		$I->selectOption(AuthorizeDPMPaymentPage::$selectExpireMonth, $checkoutAccountDetail['cardExpiryMonth']);
		$I->selectOption(AuthorizeDPMPaymentPage::$selectExpireYear, $checkoutAccountDetail['cardExpiryYear']);
		$I->waitForElementVisible(AuthorizeDPMPaymentPage::$typeCard, 30);
		$I->click(AuthorizeDPMPaymentPage::$typeCard);
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

		$I->waitForElementVisible(AuthorizeDPMPaymentPage::$checkoutFinalStep, 30);
		$I->click(AuthorizeDPMPaymentPage::$checkoutFinalStep);

		try
		{
			$I->waitForElementNotVisible(AuthorizeDPMPaymentPage::$checkoutFinalStep, 30);
		}catch (\Exception $e)
		{
			$I->waitForElementVisible(AuthorizeDPMPaymentPage::$checkoutFinalStep, 30);
			$I->wait(0.5);
			$I->click(AuthorizeDPMPaymentPage::$checkoutFinalStep);
		}

		$I->dontSeeInCurrentUrl(AuthorizeDPMPaymentPage::$checkoutURL);
	}
}
