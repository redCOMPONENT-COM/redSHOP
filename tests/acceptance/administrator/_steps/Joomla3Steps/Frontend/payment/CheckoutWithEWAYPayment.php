<?php
/**
 * @package     redSHOP
 * @subpackage  Steps
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Frontend\payment;
use AuthorizeDPMPaymentPage;
use FrontEndProductManagerJoomla3Page;

/**
 * Class CheckoutWithEWAYPayment
 * @package Frontend\payment
 * @since 2.1.3
 */
class CheckoutWithEWAYPayment extends CheckoutWithAuthorizeDPMPayment
{
	/**
	 * @param $checkoutAccountDetail
	 * @param $productName
	 * @param $categoryName
	 * @param $customerInformation
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function checkoutProductWithEWAYPayment($checkoutAccountDetail, $productName, $categoryName, $customerInformation)
	{
		$I = $this;
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv, 60);
		$productFrontEndManagerPage = new \FrontEndProductManagerJoomla3Page;
		$I->addToCart($categoryName, $productName);
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$cartPageUrL);
		$I->waitForElementVisible(['link' => $productName], 30);
		$I->click(FrontEndProductManagerJoomla3Page:: $checkoutButton);
		$I->fillInformationPrivate($customerInformation);
		$I->wait(1);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$labelPayment,30);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$paymentEWAY, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$paymentEWAY);
		$I->wait(0.5);

		try
		{
			$I->waitForElement(AuthorizeDPMPaymentPage::$selectExpireMonth, 30);
			$I->selectOption(AuthorizeDPMPaymentPage::$selectExpireMonth, $checkoutAccountDetail['cardExpiryMonth']);
			$I->click(FrontEndProductManagerJoomla3Page::$paymentEWAY);
		}
		catch (\Exception $e)
		{
			$I->click(FrontEndProductManagerJoomla3Page::$paymentEWAY);
		}

		$I->wantTo("checkout with card");
		$I->fillInformationCard($checkoutAccountDetail);

		$I->waitForElement($productFrontEndManagerPage->product($productName), 60);
		$I->waitForElementVisible($productFrontEndManagerPage->product($productName), 30);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$acceptTerms, 30);
		$I->scrollTo(FrontEndProductManagerJoomla3Page::$acceptTerms);
		$I->executeJS($productFrontEndManagerPage->radioCheckID(FrontEndProductManagerJoomla3Page::$termAndConditionsId));
		$I->wait(0.5);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);

		try
		{
			$I->waitForText(FrontEndProductManagerJoomla3Page::$orderReceipt, 10, FrontEndProductManagerJoomla3Page:: $h1);
		}
		catch (\Exception $e)
		{
			try
			{
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageAcceptTerms, 5, FrontEndProductManagerJoomla3Page::$locatorMessageAcceptTerms);
			}
			catch (\Exception $e)
			{
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$termAndConditions, 5);
				$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
			}

			$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
			$I->wait(0.5);
			$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		}

		$I->wait(2);
		$I->dontSeeInCurrentUrl(FrontEndProductManagerJoomla3Page::$checkoutURL);
		$I->waitForText(FrontEndProductManagerJoomla3Page::$orderReceipt, 30, FrontEndProductManagerJoomla3Page:: $h1);
	}
}