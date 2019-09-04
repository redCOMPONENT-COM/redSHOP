<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Frontend\shipping;

use CheckoutMissingData;
use ConfigurationPage;
use FrontEndProductManagerJoomla3Page;

/**
 * Class CheckoutWithShippingGLSSteps
 * @package Frontend\shipping
 * @since 2.1.3
 */
class CheckoutWithShippingGLSSteps extends CheckoutMissingData
{
	/**
	 * @param $categoryname
	 * @param $productname
	 * @param $userName
	 * @param $pass
	 * @param $shippingPrice
	 * @param $total
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function checkoutWithShippingGLS($categoryname , $productname, $userName, $pass, $shippingName, $shippingPrice, $total)
	{
		$I = $this;
		$I->amOnPage(ConfigurationPage::$URL);
		$currencyPrice = $I->getCurrencyValue();
		$I->addToCart($categoryname, $productname);
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->doFrontEndLogin($userName, $pass);
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$cartPageUrL);
		$I->checkForPhpNoticesOrWarnings();
		$I->waitForElementVisible(['link' => $productname], 30);
		$I->click(FrontEndProductManagerJoomla3Page:: $checkoutButton);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$labelShippingGLS, 30);
		$I->scrollTo(FrontEndProductManagerJoomla3Page::$labelShippingGLS);
		$shippingRateGLS = new FrontEndProductManagerJoomla3Page();
		$I->waitForElementVisible($shippingRateGLS->xpathShippingRateGLS($shippingName), 30);
		$I->click($shippingRateGLS->xpathShippingRateGLS($shippingName));

		try
		{
			$I->seeCheckboxIsChecked($shippingRateGLS->xpathShippingRateGLS($shippingName));
		}catch (\Exception $e)
		{
			$I->click($shippingRateGLS->xpathShippingRateGLS($shippingName));
		}

		$I->waitForElementVisible($productFrontEndManagerPage->product($productname), 30);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$acceptTerms, 30);
		$I->scrollTo(FrontEndProductManagerJoomla3Page::$acceptTerms);
		$I->executeJS($productFrontEndManagerPage->radioCheckID(FrontEndProductManagerJoomla3Page::$termAndConditionsId));

		try
		{
			$I->seeCheckboxIsChecked(FrontEndProductManagerJoomla3Page::$termAndConditions);
		}catch (\Exception $e)
		{
			$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
		}

		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
		$priceShippingMethod = 'Shipping with vat: '.$currencyPrice['currencySymbol'].' '.($shippingPrice).$currencyPrice['decimalSeparator'].$currencyPrice['numberZero'];
		$priceTotalOnCart = 'Total: '.$currencyPrice['currencySymbol'].' '.($total).$currencyPrice['decimalSeparator'].$currencyPrice['numberZero'];
		$I->see($priceShippingMethod);
		$I->see($priceTotalOnCart);

		try
		{
			$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
			$I->waitForElementNotVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
		}catch (\Exception $e)
		{
			$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
			$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
			$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		}

		$I->waitForText(FrontEndProductManagerJoomla3Page::$orderReceipt, 30, FrontEndProductManagerJoomla3Page:: $h1);
	}
}