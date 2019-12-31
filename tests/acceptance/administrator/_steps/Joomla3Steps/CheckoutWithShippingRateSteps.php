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

		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$termAndConditions, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
		$I->scrollTo(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$orderReceiptTitle, 30);
		$priceRate = 'Shipping with vat: '.$currencyUnit['currencySymbol'].' '.($shipping['shippingRate']).$currencyUnit['decimalSeparator'].$currencyUnit['numberZero'];
		$priceTotal = 'Total: '.$currencyUnit['currencySymbol'].' '.($total).$currencyUnit['decimalSeparator'].$currencyUnit['numberZero'];
		$I->waitForText(FrontEndProductManagerJoomla3Page::$orderReceipt, 30, FrontEndProductManagerJoomla3Page:: $h1);
		$I->see($priceRate);
		$I->see($priceTotal);
	}
}
