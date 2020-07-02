<?php
/**
 * @package     redSHOP
 * @subpackage  Steps
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Frontend\payment;
use CheckoutMissingData;
use FrontEndProductManagerJoomla3Page;

/**
 * Class CheckoutWithEANTransferPayment
 * @package Frontend\payment
 * @since 2.1.4
 */
class CheckoutWithEANTransferPayment extends CheckoutMissingData
{
	/**
	 * @param $productName
	 * @param $categoryName
	 * @param $customerInformation
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function checkoutProductWithEANTransferPayment($productName, $categoryName, $customerInformation)
	{
		$I = $this;
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv, 60);
		$I->click($categoryName);
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->addToCart($categoryName, $productName);
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$cartPageUrL);
		$I->waitForElementVisible(['link' => $productName], 30);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutButton);
		$I->fillInformationPrivate($customerInformation);
		$I->wait(1);

		try
		{
			$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$eanPayment, 30);
			$I->wait(0.5);
			$I->click(FrontEndProductManagerJoomla3Page::$eanPayment);
			$I->seeCheckboxIsChecked(FrontEndProductManagerJoomla3Page::$eanPayment);
		}
		catch (\Exception $e)
		{
			$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$eanPayment, 30);
			$I->wait(1);
			$I->click(FrontEndProductManagerJoomla3Page::$eanPayment);
			$I->seeCheckboxIsChecked(FrontEndProductManagerJoomla3Page::$eanPayment);
		}

		$I->wantTo("checkout with card");
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$acceptTerms, 30);
		$I->scrollTo(FrontEndProductManagerJoomla3Page::$acceptTerms);
		$I->executeJS($productFrontEndManagerPage->radioCheckID(FrontEndProductManagerJoomla3Page::$termAndConditionsId));
		$I->wait(0.5);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);

		try
		{
			$I->waitForElementNotVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 10);
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

		$I->waitForText(FrontEndProductManagerJoomla3Page::$orderReceipt, 30, FrontEndProductManagerJoomla3Page::$h1);
		$I->see(FrontEndProductManagerJoomla3Page::$orderReceipt);
	}
}
