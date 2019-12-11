<?php
/**
 * @package     redSHOP
 * @subpackage  Steps
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Frontend\payment;
use EANTransferPaymentPage;
use CheckoutMissingData;
use FrontEndProductManagerJoomla3Page;

/**
 * Class CheckoutWithEANTransferPayment
 * @package Frontend\payment
 * @since 2.1.4
 */
class CheckoutWithEANTransferPayment extends CheckoutMissingData
{
	public function checkoutProductWithEANTransferPayment($productName, $categoryName, $customerInformation)
	{
		$I = $this;
		$I->amOnPage(EANTransferPaymentPage::$URL);
		$I->waitForElement(EANTransferPaymentPage::$categoryDiv, 60);
		$I->click($categoryName);
		$productFrontEndManagerPage = new EANTransferPaymentPage;
		$I->addToCart($categoryName, $productName);
		$I->amOnPage(EANTransferPaymentPage::$cartPageUrL);
		$I->waitForElementVisible(['link' => $productName], 30);
		$I->click(EANTransferPaymentPage::$checkoutButton);
		$I->fillInformationPrivate($customerInformation);
		$I->wait(1);
		$I->waitForElementVisible(EANTransferPaymentPage::$labelPayment, 30);
		$I->scrollTo(EANTransferPaymentPage::$labelPayment);
		$I->waitForElementVisible(EANTransferPaymentPage::$eanPayment, 30);
		$I->click(EANTransferPaymentPage::$eanPayment);

		$I->wantTo("checkout with card");
		$I->waitForElementVisible(EANTransferPaymentPage::$acceptTerms, 30);
		$I->scrollTo(EANTransferPaymentPage::$acceptTerms);
		$I->executeJS($productFrontEndManagerPage->radioCheckID(EANTransferPaymentPage::$termAndConditionsId));
		$I->wait(0.5);
		$I->waitForElementVisible(EANTransferPaymentPage::$checkoutFinalStep, 30);
		$I->click(EANTransferPaymentPage::$checkoutFinalStep);

		try
		{
			$I->waitForElementNotVisible(EANTransferPaymentPage::$checkoutFinalStep, 10);
		}
		catch (\Exception $e)
		{
			try
			{
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageAcceptTerms, 5, FrontEndProductManagerJoomla3Page::$locatorMessageAcceptTerms);
			}
			catch (\Exception $e)
			{
				$I->waitForElementVisible(EANTransferPaymentPage::$termAndConditions, 5);
				$I->click(EANTransferPaymentPage::$termAndConditions);
			}

			$I->waitForElementVisible(EANTransferPaymentPage::$checkoutFinalStep, 30);
			$I->wait(0.5);
			$I->click(EANTransferPaymentPage::$checkoutFinalStep);
		}

		$I->wait(2);
		$I->dontSeeInCurrentUrl(EANTransferPaymentPage::$checkoutURL);
	}
}
