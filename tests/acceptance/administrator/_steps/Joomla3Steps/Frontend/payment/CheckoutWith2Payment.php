<?php
/**
 * @package     redSHOP
 * @subpackage  Steps
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Frontend\payment;

use CheckoutOnFrontEnd;
use FrontEndProductManagerJoomla3Page;
use Frontend2PaymentPage;

/**
 * Class CheckoutWith2Payment
 * @since 2.1.2
 */
class CheckoutWith2Payment extends CheckoutOnFrontEnd
{
	/**
	 * @param $userName
	 * @param $password
	 * @param $checkoutAccountInformation
	 * @param $productName
	 * @param $categoryName
	 * @throws \Exception
	 */
	public function checkoutProductWith2Checkout( $userName , $password, $checkoutAccountInformation, $productName, $categoryName)
	{
		$I = $this;
		$I->doFrontEndLogin($userName, $password);
		$I->addToCart($categoryName, $productName);
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->amOnPage(FrontEndProductManagerJoomla3Page:: $cartPageUrL);
		$I->checkForPhpNoticesOrWarnings();
		$I->waitForElementVisible(['link' => $productName], 30);
		$I->click(FrontEndProductManagerJoomla3Page:: $checkoutButton);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page:: $labelPayment, 30);
		$I->scrollTo(FrontEndProductManagerJoomla3Page::$labelPayment);
		$I->waitForElementVisible(Frontend2PaymentPage::$payment2checkout, 30);
		$I->wait(0.5);
		$I->click(Frontend2PaymentPage::$payment2checkout);
		$I->waitForElementVisible($productFrontEndManagerPage->product($productName), 30);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$acceptTerms, 30);
		$I->scrollTo(FrontEndProductManagerJoomla3Page::$acceptTerms);
		$I->executeJS($productFrontEndManagerPage->radioCheckID(FrontEndProductManagerJoomla3Page::$termAndConditionsId));
		$I->wait(0.5);
		try
		{
			$I->seeCheckboxIsChecked(FrontEndProductManagerJoomla3Page::$termAndConditions);
		}catch (\Exception $e)
		{
			$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
		}
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
		try
		{
			$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
			$I->waitForElementNotVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 10);
		}catch (\Exception $e)
		{
			$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
			$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
			$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		}
		$I->waitForText(Frontend2PaymentPage::$secureCheckout, 30, Frontend2PaymentPage:: $h1);
		$I->click(Frontend2PaymentPage::$reviewCart);
		$I->waitForElementVisible(Frontend2PaymentPage::$shippingAddress1, 30);
		$I->fillField(Frontend2PaymentPage::$shippingAddress1, $checkoutAccountInformation['shippingAddress']);
		$I->waitForElementVisible(Frontend2PaymentPage::$shippingInformation, 30);
		$I->click(Frontend2PaymentPage::$shippingInformation);
		$I->waitForElementVisible(Frontend2PaymentPage::$checkboxSamAsShipping, 30);
		$I->wait(0.5);
		$I->click(Frontend2PaymentPage::$checkboxSamAsShipping);
		$I->waitForElementVisible(Frontend2PaymentPage::$billingInformation, 30);
		$I->wait(0.5);
		$I->click(Frontend2PaymentPage::$billingInformation);
		$I->waitForElement(Frontend2PaymentPage::$inputCartNumber, 30);
		$I->waitForElementVisible(Frontend2PaymentPage::$inputCartNumber, 30);
		$I->fillField(Frontend2PaymentPage::$inputCartNumber, $checkoutAccountInformation['debitCardNumber']);
		$I->click(Frontend2PaymentPage::$buttonPayment2Checkout);
		$I->waitForText(Frontend2PaymentPage:: $message2CheckoutSuccess, 30, Frontend2PaymentPage:: $h1);
	}

	/**
	 * @param $userName
	 * @param $password
	 * @param $productName
	 * @param $categoryName
	 * @throws \Exception
	 * since 2.1.2
	 */
	public function CheckoutWithEPAYPayment ($userName , $password, $productName, $categoryName)
	{
		$I = $this;
		$I->doFrontEndLogin($userName, $password);
		$I->addToCart($categoryName, $productName);
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->amOnPage(FrontEndProductManagerJoomla3Page:: $cartPageUrL);
		$I->checkForPhpNoticesOrWarnings();
		$I->waitForElementVisible(['link' => $productName], 30);
		$I->click(FrontEndProductManagerJoomla3Page:: $checkoutButton);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page:: $labelPayment, 30);
		$I->scrollTo(FrontEndProductManagerJoomla3Page::$labelPayment);
		$I->waitForElementVisible(Frontend2PaymentPage::$buttonEPay, 30);
		$I->wait(0.5);
		$I->click(Frontend2PaymentPage::$buttonEPay);
		$I->waitForElementVisible($productFrontEndManagerPage->product($productName), 30);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$acceptTerms, 30);
		$I->scrollTo(FrontEndProductManagerJoomla3Page::$acceptTerms);
		$I->executeJS($productFrontEndManagerPage->radioCheckID(FrontEndProductManagerJoomla3Page::$termAndConditionsId));
		$I->wait(0.5);
		try
		{
			$I->seeCheckboxIsChecked(FrontEndProductManagerJoomla3Page::$termAndConditions);
		}catch (\Exception $e)
		{
			$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
		}
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
		try
		{
			$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
			$I->waitForElementNotVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 10);
		}catch (\Exception $e)
		{
			$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
			$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
			$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		}
		$I->dontSee(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
	}
}