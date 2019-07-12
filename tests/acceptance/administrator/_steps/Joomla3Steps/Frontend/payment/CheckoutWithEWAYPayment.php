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
class CheckoutWithEWAYPayment extends CheckoutWithAuthorizeDPMPayment
{
	/**
	 * @param $checkoutAccountDetail
	 * @param $productName
	 * @param $categoryName
	 * @param $customerInformation
	 * @throws \Exception
	 * @since 2.1.2
	 */
	public function checkoutProductWithEWAYPayment($checkoutAccountDetail, $productName, $categoryName, $customerInformation)
	{
		$I = $this;
		$I->amOnPage(FrontEndEWAYPaymentPage::$URL);
		$I->waitForElement(FrontEndEWAYPaymentPage::$categoryDiv, 60);
		$productFrontEndManagerPage = new \FrontEndEWAYPaymentPage;
		$I->addToCart($categoryName, $productName);
		$I->amOnPage(FrontEndEWAYPaymentPage::$cartPageUrL);
		$I->waitForElementVisible(['link' => $productName], 30);
		$I->click(FrontEndEWAYPaymentPage:: $checkoutButton);
		$I->fillInformationPrivate($customerInformation);
		$I->wait(1);
		$I->waitForElementVisible(FrontEndEWAYPaymentPage::$labelPayment,30);
		$I->waitForElementVisible(FrontEndEWAYPaymentPage::$paymentEWAY, 30);
		$I->click(FrontEndEWAYPaymentPage::$paymentEWAY);
		$I->wait(0.5);

		try
		{
			$I->waitForElement(FrontEndEWAYPaymentPage::$selectExpireMonth, 30);
			$I->selectOption(FrontEndEWAYPaymentPage::$selectExpireMonth, $checkoutAccountDetail['cardExpiryMonth']);
			$I->click(FrontEndEWAYPaymentPage::$paymentEWAY);
		}
		catch (\Exception $e)
		{
			$I->click(FrontEndEWAYPaymentPage::$paymentEWAY);
		}

		$I->wantTo("checkout with card");
		$I->fillInformationCard($checkoutAccountDetail);

		$I->waitForElement($productFrontEndManagerPage->product($productName), 60);
		$I->waitForElementVisible($productFrontEndManagerPage->product($productName), 30);
		$I->waitForElementVisible(FrontEndEWAYPaymentPage::$acceptTerms, 30);
		$I->scrollTo(FrontEndEWAYPaymentPage::$acceptTerms);
		$I->executeJS($productFrontEndManagerPage->radioCheckID(FrontEndEWAYPaymentPage::$termAndConditionsId));
		$I->wait(0.5);
		$I->waitForElementVisible(FrontEndEWAYPaymentPage::$checkoutFinalStep, 30);
		$I->click(FrontEndEWAYPaymentPage::$checkoutFinalStep);

		try
		{
			$I->waitForElementNotVisible(FrontEndEWAYPaymentPage::$checkoutFinalStep, 10);
		}
		catch (\Exception $e)
		{
			try
			{
				$I->waitForText(FrontEndEWAYPaymentPage::$messageAcceptTerms, 5, FrontEndEWAYPaymentPage::$locatorMessageAcceptTerms);
			}
			catch (\Exception $e)
			{
				$I->waitForElementVisible(FrontEndEWAYPaymentPage::$termAndConditions, 5);
				$I->click(FrontEndEWAYPaymentPage::$termAndConditions);
			}

			$I->waitForElementVisible(FrontEndEWAYPaymentPage::$checkoutFinalStep, 30);
			$I->wait(0.5);
			$I->click(FrontEndEWAYPaymentPage::$checkoutFinalStep);
		}

		$I->wait(2);
		$I->dontSeeInCurrentUrl(FrontEndEWAYPaymentPage::$checkoutURL);
	}
}