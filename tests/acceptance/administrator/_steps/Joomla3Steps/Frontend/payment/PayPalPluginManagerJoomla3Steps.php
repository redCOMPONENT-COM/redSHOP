<?php
/**
 * @package     redSHOP
 * @subpackage  Steps
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Frontend\payment;

use FrontEndProductManagerJoomla3Page;
use ImageOptimizer\Exception\Exception;
use PayPalPluginManagerJoomla3Page;

/**
 * Class PayPalPluginManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @since    2.1.2
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 */
class PayPalPluginManagerJoomla3Steps extends \CheckoutMissingData
{
	/**
	 * @param $addressDetail
	 * @param $payPalAccountDetail
	 * @param string $productName
	 * @param string $categoryName
	 * @throws \Exception
	 * @since 2.1.2
	 */
	public function checkoutProductWithPayPalPayment($addressDetail, $payPalAccountDetail, $productName,$categoryName)
	{
		$I = $this;
		$I->addToCart($categoryName, $productName);
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->amOnPage(FrontEndProductManagerJoomla3Page:: $cartPageUrL);
		$I->checkForPhpNoticesOrWarnings();
		$I->waitForElementVisible(['link' => $productName], 30);
		$I->click(FrontEndProductManagerJoomla3Page:: $checkoutButton);
		$I->fillInformationPrivate($addressDetail);
		$I->wait(0.5);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page:: $labelPayment, 30);
		$I->wait(0.5);
		$I->scrollTo(FrontEndProductManagerJoomla3Page::$labelPayment);
		$I->waitForElementVisible(PayPalPluginManagerJoomla3Page::$payPalPaymentOptionSelectOnCheckout, 30);
		$I->wait(0.5);
		$I->click(PayPalPluginManagerJoomla3Page::$payPalPaymentOptionSelectOnCheckout);
		$I->waitForElementVisible($productFrontEndManagerPage->product($productName), 30);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$acceptTerms, 30);
		$I->scrollTo(FrontEndProductManagerJoomla3Page::$acceptTerms);
		$I->executeJS($productFrontEndManagerPage->radioCheckID(FrontEndProductManagerJoomla3Page::$termAndConditionsId));
		$I->wait(0.5);

		try
		{
			$I->seeCheckboxIsChecked(FrontEndProductManagerJoomla3Page::$termAndConditions);
		} catch (\Exception $e)
		{
			$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
		}

		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);

		try
		{
			$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
			$I->waitForElementNotVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 10);
		} catch (\Exception $e)
		{
			$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
			$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
			$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		}

		$I->waitForElementVisible(PayPalPluginManagerJoomla3Page::$payWithPayPalAccountOption, 60);
		$I->seeElement(\PayPalPluginManagerJoomla3Page::$payWithPayPalAccountOption);
		$I->wait(1);
		$I->waitForElementVisible(PayPalPluginManagerJoomla3Page::$payWithPayPalAccountOption, 60);
		$I->click(PayPalPluginManagerJoomla3Page::$payWithPayPalAccountOption);

		$I->waitForElement(\PayPalPluginManagerJoomla3Page::$payPalPasswordField, 30);
		$I->fillField(\PayPalPluginManagerJoomla3Page::$payPalLoginEmailField, $payPalAccountDetail["username"]);
		$I->fillField(\PayPalPluginManagerJoomla3Page::$payPalPasswordField, $payPalAccountDetail["password"]);
		$I->click(\PayPalPluginManagerJoomla3Page::$submitLoginField);
		$I->waitForElementVisible(\PayPalPluginManagerJoomla3Page::$payNowField, 30);
		$I->seeElement(\PayPalPluginManagerJoomla3Page::$payNowField);
		$I->wait(1);
		$I->waitForElementVisible(\PayPalPluginManagerJoomla3Page::$payNowField, 60);
		$I->click(\PayPalPluginManagerJoomla3Page::$payNowField);
		$I->waitForElement(\PayPalPluginManagerJoomla3Page::$paymentCompletionSuccessMessage, 30);
		$I->seeElement(\PayPalPluginManagerJoomla3Page::$paymentCompletionSuccessMessage);
		$I->waitForText(PayPalPluginManagerJoomla3Page::$successMessage, 30);

		$I->waitForElement(PayPalPluginManagerJoomla3Page::$merchantReturnBtn, 30);
		$I->click(PayPalPluginManagerJoomla3Page::$merchantReturnBtn);
		$I->waitForText(PayPalPluginManagerJoomla3Page::$orderPlaced, 30);
	}
}
