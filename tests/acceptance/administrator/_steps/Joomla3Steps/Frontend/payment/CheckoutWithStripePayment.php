<?php
/**
 * @package     redSHOP
 * @subpackage  CheckoutWithStripePayment
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace Frontend\payment;
use FrontEndProductManagerJoomla3Page;

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
		$I->wait(1);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$labelPayment, 30);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$paymentStripe, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$paymentStripe);
		$I->wait(0.5);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$acceptTerms, 30);
		$I->scrollTo(FrontEndProductManagerJoomla3Page::$acceptTerms);
		$I->executeJS($productFrontEndManagerPage->radioCheckID(FrontEndProductManagerJoomla3Page::$termAndConditionsId));
		$I->wait(0.5);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->wait(1);
		$I->acceptPopup();
		$I->switchToIFrame(FrontEndProductManagerJoomla3Page::$nameIframeStripe);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$cardNumberIframe, 30);
		$I->fillField(FrontEndProductManagerJoomla3Page::$cardNumberIframe, $informationVisa['cardNumber']);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$dateIframe, 30);
		$I->fillField(FrontEndProductManagerJoomla3Page::$dateIframe, $informationVisa['date']);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$cvcIframe, 30);
		$I->fillField(FrontEndProductManagerJoomla3Page::$cvcIframe, $informationVisa['cvc']);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$submitIframe, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$submitIframe);
		$I->wait(1);
		$I->dontSeeInCurrentUrl(FrontEndProductManagerJoomla3Page::$checkoutURL);
		$I->waitForText(FrontEndProductManagerJoomla3Page::$orderReceipt, 30, FrontEndProductManagerJoomla3Page:: $h1);
	}
}