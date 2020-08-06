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
 * Class shippingDefaultGLS
 * @package Frontend\Shipping
 * @since 2.1.3
 */
class shippingDefaultGLS extends CheckoutWithEWAYPayment
{
	/**
	 * @param $categoryName
	 * @param $product
	 * @param $customerInformation
	 * @param $shipping
	 * @param $shippingName
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function checkoutWithShippingDefaultGLS($categoryName, $product, $customerInformation, $shipping, $shippingName)
	{
		$I = $this;
		$currencyUnit = $I->getCurrencyValue();

		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv, 60);
		$I->addToCart($categoryName, $product['name']);

		$I->amOnPage(FrontEndProductManagerJoomla3Page::$cartPageUrL);
		$I->waitForElementVisible(['link' => $product['name']], 30);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$quantityFieldCart, 30);
		$quantity = $I->grabValueFrom(FrontEndProductManagerJoomla3Page::$quantityFieldCart);
		$I->comment($quantity);
		$total = $product['price']*$quantity + $shipping['shippingRate'];
		$priceTotal = $currencyUnit['currencySymbol'].' '.($total).$currencyUnit['decimalSeparator'].$currencyUnit['numberZero'];
		$priceRate = $currencyUnit['currencySymbol'].' '.($shipping['shippingRate']).$currencyUnit['decimalSeparator'].$currencyUnit['numberZero'];

		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutButton, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutButton);
		$I->fillInformationPrivate($customerInformation);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$shippingMethod, 30);
		$I->scrollTo(FrontEndProductManagerJoomla3Page::$shippingMethod);
		$I->selectOption(FrontEndProductManagerJoomla3Page::$radioShippingRate, $shipping['shippingName']);

		try
		{
			$I->canSeeCheckboxIsChecked(FrontEndProductManagerJoomla3Page::$iconShippingGLS);
		} catch (\Exception $e)
		{
			$I->selectOption(FrontEndProductManagerJoomla3Page::$radioShippingRate, $shipping['shippingName']);
			$I->canSeeCheckboxIsChecked(FrontEndProductManagerJoomla3Page::$iconShippingGLS);
		}

		$I->waitForElement(FrontEndProductManagerJoomla3Page::$bankTransfer, 30);
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

		$I->waitForText($shippingName, 30);
		$I->see($shippingName);
		$I->see($priceRate);
		$I->see($priceTotal);
	}

	/**
	 * @param $categoryName
	 * @param $product
	 * @param $customerInformation
	 * @param $shipping
	 * @param $shippingName
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function checkoutWithShippingGLSBusiness($categoryName, $product, $customerInformation, $shipping, $shippingName)
	{
		$I = $this;
		$currencyUnit = $I->getCurrencyValue();

		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv, 60);
		$I->addToCart($categoryName, $product['name']);

		$I->amOnPage(FrontEndProductManagerJoomla3Page::$cartPageUrL);
		$I->waitForElementVisible(['link' => $product['name']], 30);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$quantityFieldCart, 30);
		$quantity = $I->grabValueFrom(FrontEndProductManagerJoomla3Page::$quantityFieldCart);

		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutButton, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutButton);

		$I->comment('Business');
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$radioCompany, 60);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$radioCompany, 60);
		$I->wait(1);
		$I->click(FrontEndProductManagerJoomla3Page::$radioCompany);

		$I->fillInformationBusiness($customerInformation);
		$I->waitForElementVisible($productFrontEndManagerPage->xpathShippingName($shipping['shippingName']), 30);
		$I->click($productFrontEndManagerPage->xpathShippingName($shipping['shippingName']));

		$I->scrollTo(FrontEndProductManagerJoomla3Page::$labelPayment);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$bankTransfer, 30);
		$I->executeJS($productFrontEndManagerPage->radioCheckID(FrontEndProductManagerJoomla3Page::$bankTransferId));
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$termAndConditions, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);

		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$orderReceiptTitle, 30);
		$I->waitForText(FrontEndProductManagerJoomla3Page::$orderReceipt, 30, FrontEndProductManagerJoomla3Page::$h1);

		$total = $product['price']*$quantity + $shipping['shippingRate'];
		$priceTotal = $currencyUnit['currencySymbol'].($total).$currencyUnit['decimalSeparator'].$currencyUnit['numberZero'];
		$priceShippingRate = $currencyUnit['currencySymbol'].($shipping['shippingRate']).$currencyUnit['decimalSeparator'].$currencyUnit['numberZero'];

		$I->waitForText($shippingName, 30);
		$I->see($shippingName);
		$I->waitForText($priceShippingRate, 30);
		$I->see($priceShippingRate);
		$I->waitForText($priceTotal, 30);
		$I->see($priceTotal);
	}
}
