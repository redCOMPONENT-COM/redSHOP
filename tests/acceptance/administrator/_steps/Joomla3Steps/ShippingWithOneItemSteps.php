<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Frontend\payment\CheckoutWithEWAYPayment;

/**
 * Class ShippingWithOneItemSteps
 * @since 2.1.5
 */
class ShippingWithOneItemSteps extends CheckoutWithEWAYPayment
{
	/**
	 * @param $categoryName
	 * @param $product
	 * @param $customerInformation
	 * @param $shipping
	 * @param $shippingMethod
	 * @throws Exception
	 * @since 2.1.5
	 */
	public function ChekoutWithOneItemShippingSteps($categoryName, $product, $customerInformation, $shipping, $shippingMethod)
	{
		$I = $this;
		$currencyUnit = $I->getCurrencyValue();
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv, 60);
		$I->addToCart($categoryName, $product['name']);

		$I->amOnPage(FrontEndProductManagerJoomla3Page::$cartPageUrL);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutButton, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutButton);
		$I->fillInformationPrivate($customerInformation);

		$I->waitForElement(FrontEndProductManagerJoomla3Page::$bankTransfer, 30);
		$I->executeJS($productFrontEndManagerPage->radioCheckID(FrontEndProductManagerJoomla3Page::$bankTransferId));
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$termAndConditions, 30);
		$I->scrollTo(FrontEndProductManagerJoomla3Page::$termAndConditions);
		$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);

		try
		{
			$I->canSeeCheckboxIsChecked(FrontEndProductManagerJoomla3Page::$termAndConditions);
		}

		catch (\Exception $e)
		{
			$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
			$I->canSeeCheckboxIsChecked(FrontEndProductManagerJoomla3Page::$termAndConditions);
		}

		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$orderReceiptTitle, 30);
		$I->waitForText(FrontEndProductManagerJoomla3Page::$orderReceipt, 30, FrontEndProductManagerJoomla3Page::$h1);

		$total = $product['price'] + $shipping['shippingRate'];
		$priceTotal = $currencyUnit['currencySymbol'].' '.($total).$currencyUnit['decimalSeparator'].$currencyUnit['numberZero'];
		$priceRate = $currencyUnit['currencySymbol'].' '.($shipping['shippingRate']).$currencyUnit['decimalSeparator'].$currencyUnit['numberZero'];

		$I->waitForText($shippingMethod, 30);
		$I->see($shippingMethod);
		$I->see($priceRate);
		$I->see($priceTotal);
	}

	/**
	 * @param $customerInformation
	 * @param $shippingMethod
	 * @param $shipping
	 * @throws Exception
	 * @since 2.1.5
	 */
	public function checkOrderShipping($customerInformation, $shippingMethod, $shipping)
	{
		$I = $this;
		$I->amOnPage(OrderManagerPage::$URL);
		$I->filterListBySearchOrder($customerInformation['firstName']);
		$I->click(OrderManagerPage::$orderID);
		$I->scrollTo(OrderManagerPage::$shippingInfor);
		$I->see($shippingMethod);
		$I->see($shipping['shippingRate']);
		$I->waitForElement(OrderManagerPage::$close, 30);
		$I->waitForText(OrderManagerPage::$buttonClose, 10, OrderManagerPage::$close);
	}
}
