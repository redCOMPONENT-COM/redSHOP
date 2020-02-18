<?php
/**
 * @package     redSHOP
 * @subpackage  Step CheckoutWithShippingRate
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;

use CheckoutOnFrontEnd;
use FrontEndProductManagerJoomla3Page;

/**
 * Class CheckoutWithShippingRateSteps
 * @package AcceptanceTester
 * @since 2.1.3
 */
class CheckoutWithShippingRateSteps extends CheckoutOnFrontEnd
{
	/**
	 * @param $username
	 * @param $pass
	 * @param $categoryName
	 * @param $productName
	 * @param $total
	 * @param array $shipping
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function checkoutWithShippingRate($username, $pass, $categoryName, $productName, $total, $shipping = array())
	{
		$I = $this;
		$currencyUnit = $I->getCurrencyValue();
		$I->doFrontEndLogin($username, $pass);
		$I->addToCart($categoryName, $productName);
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$cartPageUrL);
		$I->seeElement(['link' => $productName]);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutButton);

		try
		{
			$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$billingFinal, 30);
			$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$shippingMethod, 30);
			$I->selectOption(FrontEndProductManagerJoomla3Page::$radioShippingRate, $shipping['shippingName']);
			$I->waitForElement(FrontEndProductManagerJoomla3Page::$bankTransfer, 30);
			$I->executeJS($productFrontEndManagerPage->radioCheckID(FrontEndProductManagerJoomla3Page::$bankTransferId));
			$I->click(FrontEndProductManagerJoomla3Page::$checkoutButton);
		} catch (\Exception $e)
		{
			$I->waitForElement(FrontEndProductManagerJoomla3Page::$bankTransfer, 30);
			$I->executeJS($productFrontEndManagerPage->radioCheckID(FrontEndProductManagerJoomla3Page::$bankTransferId));
			$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$shippingMethod, 30);
			$I->selectOption(FrontEndProductManagerJoomla3Page::$radioShippingRate, $shipping['shippingName']);
		}

		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$termAndConditions, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
		$I->scrollTo(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
		$I->wait(0.5);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$orderReceiptTitle, 30);
		$priceRate = 'Shipping with vat: '.$currencyUnit['currencySymbol'].' '.($shipping['shippingRate']).$currencyUnit['decimalSeparator'].$currencyUnit['numberZero'];
		$priceTotal = 'Total: '.$currencyUnit['currencySymbol'].' '.($total).$currencyUnit['decimalSeparator'].$currencyUnit['numberZero'];
		$I->waitForText(FrontEndProductManagerJoomla3Page::$orderReceipt, 30, FrontEndProductManagerJoomla3Page:: $h1);
		$I->see($priceRate);
		$I->see($priceTotal);
	}
}
