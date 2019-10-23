<?php
/**
 * @package     redSHOP
 * @subpackage  Steps
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Frontend\payment;
use AuthorizePaymentPage;
use CheckoutMissingData;
use FrontEndProductManagerJoomla3Page;

/**
 * Class CheckoutWithAuthorizePayment
 * @package Frontend\payment
 * @since 2.1.3
 */
class CheckoutWithAuthorizePayment extends CheckoutMissingData
{
    /**
     * @param $checkoutAccountDetail
     * @throws \Exception
     * @since 2.1.3
     */
    public function fillInformationCard($checkoutAccountDetail)
	{
		$I = $this;
		$I->wantTo("Enter information of card");
		$I->waitForElementVisible(AuthorizePaymentPage::$cardName, 30);
		$I->wait(0.5);
		$I->fillField(AuthorizePaymentPage::$cardName, $checkoutAccountDetail['customerName']);
		$I->waitForElementVisible(AuthorizePaymentPage::$cardNumber, 30);
		$I->fillField(AuthorizePaymentPage::$cardNumber, $checkoutAccountDetail['debitCardNumber']);
		$I->waitForElement(AuthorizePaymentPage::$selectExpireMonth, 30);
		$I->selectOption(AuthorizePaymentPage::$selectExpireMonth, $checkoutAccountDetail['cardExpiryMonth']);
		$I->selectOption(AuthorizePaymentPage::$selectExpireYear, $checkoutAccountDetail['cardExpiryYear']);
		$I->waitForElementVisible(AuthorizePaymentPage::$cardCode, 30);
		$I->fillField( AuthorizePaymentPage::$cardCode, $checkoutAccountDetail['cvv']);
		$I->waitForElementVisible(AuthorizePaymentPage::$typeCard, 30);
		$I->click(AuthorizePaymentPage::$typeCard);
	}

	/**
	 * @param $checkoutAccountDetail
	 * @param $productName
	 * @param $categoryName
	 * @param $customerInformation
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function checkoutProductWithAuthorizePayment($checkoutAccountDetail, $productName, $categoryName, $customerInformation)
	{
		$I = $this;
		$I->amOnPage(AuthorizePaymentPage::$URL);
		$I->waitForElement(AuthorizePaymentPage::$categoryDiv, 60);
		$I->click($categoryName);
		$productFrontEndManagerPage = new AuthorizePaymentPage;
		$I->addToCart($categoryName, $productName);
		$I->amOnPage(AuthorizePaymentPage::$cartPageUrL);
		$I->waitForElementVisible(['link' => $productName], 30);
		$I->click(AuthorizePaymentPage::$checkoutButton);
		$I->fillInformationPrivate($customerInformation);
		$I->wait(1);
		$I->waitForElementVisible(AuthorizePaymentPage::$labelPayment, 30);
		$I->scrollTo(AuthorizePaymentPage::$labelPayment);
		$I->waitForElementVisible(AuthorizePaymentPage::$paymentAuthorize, 30);

		try
		{
			$I->click(AuthorizePaymentPage::$paymentAuthorize);
			$I->wait(0.5);
			$I->waitForElement(AuthorizePaymentPage::$selectExpireMonth, 30);
			$I->selectOption(AuthorizePaymentPage::$selectExpireMonth, $checkoutAccountDetail['cardExpiryMonth']);
			$I->click(AuthorizePaymentPage::$paymentAuthorize);
		}
		catch (\Exception $e)
		{
			$I->click(AuthorizePaymentPage::$paymentAuthorize);
		}

		$I->wantTo("checkout with card");
		$I->fillInformationCard($checkoutAccountDetail);
		$I->waitForElementVisible(AuthorizePaymentPage::$acceptTerms, 30);
		$I->scrollTo(AuthorizePaymentPage::$acceptTerms);
		$I->executeJS($productFrontEndManagerPage->radioCheckID(AuthorizePaymentPage::$termAndConditionsId));
		$I->wait(0.5);
		$I->waitForElementVisible(AuthorizePaymentPage::$checkoutFinalStep, 30);
		$I->click(AuthorizePaymentPage::$checkoutFinalStep);

		try
		{
			$I->waitForElementNotVisible(AuthorizePaymentPage::$checkoutFinalStep, 10);
		}
		catch (\Exception $e)
		{
			try
			{
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageAcceptTerms, 5, FrontEndProductManagerJoomla3Page::$locatorMessageAcceptTerms);
			}
			catch (\Exception $e)
			{
				$I->waitForElementVisible(AuthorizePaymentPage::$termAndConditions, 5);
				$I->click(AuthorizePaymentPage::$termAndConditions);
			}

			$I->waitForElementVisible(AuthorizePaymentPage::$checkoutFinalStep, 30);
			$I->wait(0.5);
			$I->click(AuthorizePaymentPage::$checkoutFinalStep);
		}

		$I->wait(2);
		$I->dontSeeInCurrentUrl(AuthorizePaymentPage::$checkoutURL);
	}
}