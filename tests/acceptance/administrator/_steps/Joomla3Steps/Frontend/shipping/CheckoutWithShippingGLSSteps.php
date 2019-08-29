<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Frontend\shipping;

use CheckoutMissingData;
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
	 * @param $price
	 * @param $total
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function checkoutWithShippingGLS($categoryname , $productname, $userName, $pass, $price, $total)
	{
		$I = $this;
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->doFrontEndLogin($userName, $pass);
		$I->addToCart($categoryname, $productname);
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$cartPageUrL);
		$I->checkForPhpNoticesOrWarnings();
		$I->waitForElementVisible(['link' => $productname], 30);
		$I->click(FrontEndProductManagerJoomla3Page:: $checkoutButton);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$labelShippingGLS, 30);
		$I->scrollTo(FrontEndProductManagerJoomla3Page::$labelShippingGLS);
		$I->click(FrontEndProductManagerJoomla3Page::$shippingRateGLS);
		try
		{
			$I->seeCheckboxIsChecked(FrontEndProductManagerJoomla3Page::$shippingRateGLS);
		}catch (\Exception $e)
		{
			$I->click(FrontEndProductManagerJoomla3Page::$shippingRateGLS);
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

		try
		{
			$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
			$I->waitForElementNotVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
		}catch (\Exception $e)
		{
			$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
			$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
			$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		}

		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$orderReceiptTitle, 30);
		$I->see($price, FrontEndProductManagerJoomla3Page::$priceShippingRate);
		$I->waitForText($price, 30 , FrontEndProductManagerJoomla3Page::$priceShippingRate);
		$I->see($total, FrontEndProductManagerJoomla3Page::$totalFinalCheckout);
		$I->waitForText($total, 30 ,FrontEndProductManagerJoomla3Page::$totalFinalCheckout);
	}
}