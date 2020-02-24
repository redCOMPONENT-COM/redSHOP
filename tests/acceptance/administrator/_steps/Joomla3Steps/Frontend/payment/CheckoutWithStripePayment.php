<?php
/**
 * @package     redSHOP
 * @subpackage  CheckoutWithStripePayment
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Frontend\payment;
use FrontEndProductManagerJoomla3Page;
use StripePaymentPage;

/**
 * Class CheckoutWithStripePayment
 * @package Frontend\payment
 * @since 2.1.3
 */
class CheckoutWithStripePayment extends CheckoutWithEWAYPayment
{
	/**
	 * @param $categoryName
	 * @param $productName
	 * @param $customerInformation
	 * @param $informationVisa
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function checkoutWithStripePayment($categoryName, $productName, $customerInformation, $informationVisa)
	{
		$I = $this;
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv, 60);
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->addToCart($categoryName, $productName);
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$cartPageUrL);
		$I->waitForElementVisible(['link' => $productName], 30);
		$I->click(FrontEndProductManagerJoomla3Page:: $checkoutButton);
		$I->fillInformationPrivate($customerInformation);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$labelPayment, 30);
		$I->waitForElementVisible(StripePaymentPage::$paymentStripe, 30);

		try
		{
			$I->click(StripePaymentPage::$paymentStripe);
			$I->wait(0.5);
			$I->seeCheckboxIsChecked(StripePaymentPage::$paymentStripe);
		}catch (\Exception $e)
		{
			$I->waitForElementVisible(StripePaymentPage::$paymentStripe, 30);
			$I->wait(0.5);
			$I->click(StripePaymentPage::$paymentStripe);
		}

		$I->wait(0.5);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$acceptTerms, 30);
		$I->scrollTo(FrontEndProductManagerJoomla3Page::$acceptTerms);
		$I->executeJS($productFrontEndManagerPage->radioCheckID(FrontEndProductManagerJoomla3Page::$termAndConditionsId));

		try
		{
			$I->seeCheckboxIsChecked(FrontEndProductManagerJoomla3Page::$termAndConditions);
			$I->wait(0.5);
			$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
			$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		}catch (\Exception $e)
		{
			$I->waitForText(FrontEndProductManagerJoomla3Page::$messageAcceptTerms, 30);
			$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$acceptTerms, 30);
			$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
			$I->wait(0.5);
			$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
			$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		}

		$I->wait(3);
		$I->canSeeInPopup(StripePaymentPage::$messagePopupStripe);
		$I->acceptPopup();

		$I->switchToIFrame(StripePaymentPage::$nameIframeStripe);
		$I->waitForElementVisible(StripePaymentPage::$cardNumberIframe, 30);
		$I->wait(0.5);
		$I->fillField(StripePaymentPage::$cardNumberIframe, $informationVisa['cardNumber']);
		$I->waitForElementVisible(StripePaymentPage::$dateIframe, 30);
		$I->fillField(StripePaymentPage::$dateIframe, $informationVisa['date']);
		$I->waitForElementVisible(StripePaymentPage::$cvcIframe, 30);
		$I->fillField(StripePaymentPage::$cvcIframe, $informationVisa['cvc']);
		$I->waitForElementVisible(StripePaymentPage::$submitIframe, 30);
		$I->click(StripePaymentPage::$submitIframe);
		$I->waitForElementNotVisible(StripePaymentPage::$submitIframe, 30);
		$I->dontSeeInCurrentUrl(FrontEndProductManagerJoomla3Page::$checkoutURL);
		$I->waitForText(FrontEndProductManagerJoomla3Page::$orderReceipt, 30, FrontEndProductManagerJoomla3Page::$h1);
	}
}
