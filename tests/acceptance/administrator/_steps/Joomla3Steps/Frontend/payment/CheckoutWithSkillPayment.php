<?php
/**
 * @package     redSHOP
 * @subpackage  Steps
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Frontend\payment;

use FrontEndProductManagerJoomla3Page;
use SkillPaymentPage;

/**
 * Class CheckoutWithSkillPayment
 * @package Frontend\payment
 * @since 2.1.3
 */
class CheckoutWithSkillPayment extends \CheckoutMissingData
{
	/**
	 * @param $checkoutAccountDetail
	 * @param $productName
	 * @param $categoryName
	 * @param $customerInformation
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function checkoutProductWithSkillPayment($checkoutAccountDetail, $productName, $categoryName, $customerInformation)
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
		$I->waitForElementVisible(SkillPaymentPage::$paymentMoneyBooker, 30);
		$I->click(SkillPaymentPage::$paymentMoneyBooker);
		$I->wait(0.5);
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
			$I->waitForElementVisible(SkillPaymentPage::$inputCart, 30);
			$I->fillField(SkillPaymentPage::$inputCart, $checkoutAccountDetail['debitCardNumber']);
			$I->waitForElementVisible(SkillPaymentPage::$inputMonth, 30);
			$I->fillField(SkillPaymentPage::$inputMonth, $checkoutAccountDetail['cardExpiryMonth']);
			$I->waitForElementVisible(SkillPaymentPage::$inputYear, 30);
			$I->fillField(SkillPaymentPage::$inputYear, $checkoutAccountDetail['cardExpiryYear']);
			$I->waitForElementVisible(SkillPaymentPage::$inputCvv, 30);
			$I->fillField(SkillPaymentPage::$inputCvv, $checkoutAccountDetail['cvv']);
			$I->waitForElementVisible(SkillPaymentPage::$inputFirstName, 30);
			$I->fillField(SkillPaymentPage::$inputFirstName, $checkoutAccountDetail['First']);
			$I->waitForElementVisible(SkillPaymentPage::$inputLastName, 30);
			$I->fillField(SkillPaymentPage::$inputLastName, $checkoutAccountDetail['Last']);
			$I->waitForElementVisible(SkillPaymentPage::$email, 30);
			$I->fillField(SkillPaymentPage::$email, $checkoutAccountDetail['email']);

			$I->waitForElementVisible(SkillPaymentPage::$buttonPay, 30);
			$I->click(SkillPaymentPage::$buttonPay);
			$I->waitForText(FrontEndProductManagerJoomla3Page::$orderReceipt, 30, FrontEndProductManagerJoomla3Page:: $h1);
		}
		catch (\Exception $e)
		{
			$I->waitForText(FrontEndProductManagerJoomla3Page::$orderReceipt, 30, FrontEndProductManagerJoomla3Page:: $h1);
		}
	}
}