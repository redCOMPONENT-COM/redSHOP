<?php
/**
 * @package     redSHOP
 * @subpackage  Steps ShopperGroupProduct
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Frontend\Module;
use CheckoutMissingData;
use FrontEndProductManagerJoomla3Page;

/**
 * Class ShopperGroupProductSteps
 * @package Frontend\Module
 * @since 2.1.3
 */
class ShopperGroupProductSteps extends CheckoutMissingData
{
	/**
	 * @param $userName
	 * @param $pass
	 * @param $categoryName
	 * @param $productName
	 * @param $shippingRate
	 * @param $total
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function checkShopperGroupProduct($userName, $pass, $categoryName, $productName, $shippingRate, $total)
	{
		$I = $this;
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->doFrontEndLogin($userName, $pass);

		try
		{
			$I->waitForText(FrontEndProductManagerJoomla3Page::$shopperGroupProductHeader, 30, FrontEndProductManagerJoomla3Page::$selectorPageHeader);
		} catch (\Exception $e)
		{
			$currencyUnit = $I->getCurrencyValue();
			$I->doFrontendLogout();
			$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
			$priceTotal = $currencyUnit['currencySymbol'].($total).$currencyUnit['decimalSeparator'].$currencyUnit['numberZero'];
			$I->checkoutOnePageWithLogin($userName, $pass, $productName, $categoryName, $shippingRate, $priceTotal);
			$I->executeJS($productFrontEndManagerPage->radioCheckID(FrontEndProductManagerJoomla3Page::$termAndConditionsId));

			try
			{
				$I->seeCheckboxIsChecked(FrontEndProductManagerJoomla3Page::$termAndConditions);
			} catch (\Exception $e)
			{
				$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
			}

			$I->waitForElement(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
			$I->scrollTo(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
			$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
			$I->waitForText(FrontEndProductManagerJoomla3Page::$shopperGroupProductHeader, 30, FrontEndProductManagerJoomla3Page::$selectorPageHeader);
		}
	}
}