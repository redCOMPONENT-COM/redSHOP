<?php
/**
 * @package     redSHOP
 * @subpackage  Steps
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Frontend\payment;

use FrontEndProductManagerJoomla3Page;
use Frontend2PaymentPage;
use CheckoutMissingData;
/**
 * Class CheckoutWithAmazonPayment
 * @package Frontend\payment
 * sice 2.1.2
 */
class CheckoutWithAmazonPayment extends CheckoutMissingData
{
	/**
	 * @param $userName
	 * @param $password
	 * @param $productName
	 * @param $categoryName
	 * @throws \Exception
	 * since 2.1.2
	 */
	public function CheckoutWithAmazonPayment ($productName, $categoryName, $customerInformation)
	{
		$I = $this;
		$I->addToCart($categoryName, $productName);
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->amOnPage(FrontEndProductManagerJoomla3Page:: $cartPageUrL);
		$I->checkForPhpNoticesOrWarnings();
		$I->waitForElementVisible(['link' => $productName], 30);
		$I->click(FrontEndProductManagerJoomla3Page:: $checkoutButton);
		$I->wait(0.5);
		$I->fillInformationPrivate($customerInformation);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page:: $labelPayment, 30);
		$I->scrollTo(FrontEndProductManagerJoomla3Page::$labelPayment);
		$I->waitForElementVisible(Frontend2PaymentPage::$buttonAmazon, 30);
		$I->wait(0.5);
		$I->click(Frontend2PaymentPage::$buttonAmazon);
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