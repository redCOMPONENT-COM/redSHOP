<?php
/**
 * @package     redSHOP
 * @subpackage  Steps
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Frontend\payment;
use FrontEndProductManagerJoomla3Page;
use PAYMILLPaymentPage;

/**
 * Class CheckoutWithPAYMILLPayment
 * @package Frontend\payment
 * @since 2.1.3
 */
class CheckoutWithPAYMILLPayment extends CheckoutWithAuthorizeDPMPayment
{
	/**
	 * @param $checkoutAccountDetail
	 * @param $productName
	 * @param $categoryName
	 * @param $customerInformation
	 * @param $payment
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function checkoutProductWithPAYMILLPayment($checkoutAccountDetail, $productName, $categoryName, $customerInformation, $payment)
	{
		$I = $this;
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv, 60);
		$productFrontEndManagerPage = new \FrontEndProductManagerJoomla3Page;
		$I->addToCart($categoryName, $productName);
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$cartPageUrL);
		$I->waitForElementVisible(['link' => $productName], 30);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutButton);
		$I->fillInformationPrivate($customerInformation);
		$I->wait(1);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$labelPayment, 30);
		$I->scrollTo(FrontEndProductManagerJoomla3Page::$labelPayment);
		$I->waitForText($payment, 30);
		$I->selectOption(FrontEndProductManagerJoomla3Page::$radioPayment, $payment);

		try
		{
			$I->canSeeCheckboxIsChecked(PAYMILLPaymentPage::$paymentPaymill);
		} catch (\Exception $e)
		{
			$I->selectOption(FrontEndProductManagerJoomla3Page::$radioPayment, $payment);
			$I->canSeeCheckboxIsChecked(PAYMILLPaymentPage::$paymentPaymill);
		}

		$I->waitForElement($productFrontEndManagerPage->product($productName), 60);
		$I->waitForElementVisible($productFrontEndManagerPage->product($productName), 30);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$acceptTerms, 30);
		$I->scrollTo(FrontEndProductManagerJoomla3Page::$acceptTerms);
		$I->executeJS($productFrontEndManagerPage->radioCheckID(FrontEndProductManagerJoomla3Page::$termAndConditionsId));
		$I->wait(0.5);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->dontSeeInCurrentUrl(FrontEndProductManagerJoomla3Page::$checkoutURL);

		try
		{
			$I->wantTo("checkout with card");
			$I->waitForElementVisible(PAYMILLPaymentPage::$inputCart, 30);
			$I->fillField(PAYMILLPaymentPage::$inputCart, $checkoutAccountDetail['debitCardNumber']);
			$I->waitForElementVisible(PAYMILLPaymentPage::$inputExpiry, 30);
			$I->fillField(PAYMILLPaymentPage::$inputExpiry, $checkoutAccountDetail['expires']);
			$I->waitForElementVisible(PAYMILLPaymentPage::$inputCvv, 30);
			$I->fillField(PAYMILLPaymentPage::$inputCvv, $checkoutAccountDetail['cvv']);
			$I->waitForElementVisible(PAYMILLPaymentPage::$holderName, 30);
			$I->fillField(PAYMILLPaymentPage::$holderName, $checkoutAccountDetail['cardHolder']);
			$I->waitForElementVisible(PAYMILLPaymentPage::$buttonPay, 30);
			$I->click(PAYMILLPaymentPage::$buttonPay);
			$I->waitForText(FrontEndProductManagerJoomla3Page::$orderReceipt, 30, FrontEndProductManagerJoomla3Page::$h1);
		} catch(\Exception $e)
		{
			$I->waitForText(FrontEndProductManagerJoomla3Page::$orderReceipt, 30, FrontEndProductManagerJoomla3Page::$h1);
		}
	}
}