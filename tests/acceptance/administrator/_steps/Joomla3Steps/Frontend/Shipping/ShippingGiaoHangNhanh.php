<?php
/**
 * @package     redSHOP
 * @subpackage  ShippingGiaoHangNhanh
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Frontend\Shipping;
use Frontend\payment\CheckoutWithEWAYPayment;
use FrontEndProductManagerJoomla3Page;

/**
 * Class ShippingGiaoHangNhanh
 * @package Frontend\Shipping
 * @since 2.1.3
 */
class ShippingGiaoHangNhanh extends CheckoutWithEWAYPayment
{
	/**
	 * @param $categoryName
	 * @param $productName
	 * @param $customerInformation
	 * @param $price
	 * @param $shipping
	 * @param $pluginName
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function checkoutWithShippingGiaoHangNhanh($categoryName, $productName, $customerInformation, $price, $shipping, $pluginName)
	{
		$I = $this;
		$currencyUnit = $I->getCurrencyValue();

		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv, 60);
		$I->addToCart($categoryName, $productName);

		$I->amOnPage(FrontEndProductManagerJoomla3Page::$cartPageUrL);
		$I->waitForElementVisible(['link' => $productName], 30);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$quantityFieldCart, 30);
		$quantity = $I->grabValueFrom(FrontEndProductManagerJoomla3Page::$quantityFieldCart);

		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutButton);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutButton);
		$I->waitForJS("return window.jQuery && jQuery.active == 0;", 30);
		$I->fillInformationPrivate($customerInformation);
		$I->waitForElementVisible($productFrontEndManagerPage->xpathShippingName($shipping['shippingName']), 30);
		$I->wait(0.5);
		$I->click($productFrontEndManagerPage->xpathShippingName($shipping['shippingName']));

		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$bankTransfer, 30);
		$I->executeJS($productFrontEndManagerPage->radioCheckID(FrontEndProductManagerJoomla3Page::$bankTransferId));
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$termAndConditions, 30);
		$I->scrollTo(FrontEndProductManagerJoomla3Page::$termAndConditions);
		$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);

		try
		{
			$I->canSeeCheckboxIsChecked(FrontEndProductManagerJoomla3Page::$termAndConditions);
		}catch (\Exception $e)
		{
			$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
			$I->canSeeCheckboxIsChecked(FrontEndProductManagerJoomla3Page::$termAndConditions);
		}

		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$orderReceiptTitle, 30);
		$I->waitForText(FrontEndProductManagerJoomla3Page::$orderReceipt, 30, FrontEndProductManagerJoomla3Page::$h1);

		$total = $price*$quantity + $shipping['shippingRate'];
		$priceTotal = $currencyUnit['currencySymbol'].' '.($total).$currencyUnit['decimalSeparator'].$currencyUnit['numberZero'];
		$priceRate = $currencyUnit['currencySymbol'].' '.($shipping['shippingRate']).$currencyUnit['decimalSeparator'].$currencyUnit['numberZero'];

		$I->waitForText($pluginName, 30);
		$I->see($pluginName);
		$I->see($priceRate);
		$I->see($priceTotal);
	}
}
