<?php
/**
 * @package     redSHOP
 * @subpackage  Steps
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\ProductCheckoutManagerJoomla3Steps;

/**
 * Class ProductCheckoutFrontEndManagerSteps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    2.1.0
 */class CheckoutOnFrontEnd extends ProductCheckoutManagerJoomla3Steps
{
	/**
	 * @param             $userName
	 * @param             $password
	 * @param             $productName
	 * @param             $categoryName
	 * @param             $subTotal
	 * @param             $vatPrice
	 * @param             $total
	 * @param array       $attributes
	 *
	 * @throws \Exception
	 */
	public function testProductAttributeWithVatCheckout($userName, $password, $productName, $categoryName, $subTotal, $vatPrice, $total, $attributes = array())
	{
		$I = $this;
		$I->doFrontEndLogin($userName, $password);
		$I->amOnPage(\FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$productFrontEndManagerPage = new \FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->click($productFrontEndManagerPage->product($productName));
		$usePage = new \FrontEndProductManagerJoomla3Page();
		$length = count($attributes);
		$I->wantToTest($length);
		for($x = 0;  $x < $length; $x ++ )
		{
			$attribute  = $attributes[$x];
			$I->waitForElementVisible($usePage->attributeDropdown(1), 30);
			$I->click($usePage->attributeDropdown(1));
			$I->waitForElement(\FrontEndProductManagerJoomla3Page::$attributeSearchFirst, 30);
			$I->fillField(\FrontEndProductManagerJoomla3Page::$attributeSearchFirst, $attribute['attributeName']);
			$I->wait(0.5);
			$I->pressKey(\FrontEndProductManagerJoomla3Page::$attributeSearchFirst, \Facebook\WebDriver\WebDriverKeys::ENTER);
			$I->wait(0.5);
			$I->waitForElement(\FrontEndProductManagerJoomla3Page::$addToCart, 30);
			$I->click(\FrontEndProductManagerJoomla3Page::$addToCart);
			try
			{
				$I->waitForText(\FrontEndProductManagerJoomla3Page::$alertSuccessMessage, 30, \FrontEndProductManagerJoomla3Page::$selectorSuccess);
			}
			catch (\Exception $e)
			{
				$I->click($usePage->attributeDropdown(1));
				$I->fillField(\FrontEndProductManagerJoomla3Page::$attributeSearchFirst, $attribute['attributeName']);
				$I->wait(0.5);
				$I->pressKey(\FrontEndProductManagerJoomla3Page::$attributeSearchFirst, \Facebook\WebDriver\WebDriverKeys::ENTER);
				$I->wait(0.5);
				$I->waitForElement(\FrontEndProductManagerJoomla3Page::$addToCart, 30);
				$I->click(\FrontEndProductManagerJoomla3Page::$addToCart);
			}
		}

		$I->amOnPage(\FrontEndProductManagerJoomla3Page::$cartPageUrL);
		$I->seeElement(['link' => $productName]);
		$I->click(\FrontEndProductManagerJoomla3Page::$checkoutButton);
		try
		{
			$I->waitForText($total, 10);
		} catch (\Exception $e)
		{
			$I->click(\FrontEndProductManagerJoomla3Page::$checkoutButton);
		}
		$I->waitForText($subTotal, 30);
		$I->waitForText($vatPrice, 30);
		$I->waitForText($total, 30);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
		$I->scrollTo(\FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$acceptTerms, 30);
		$I->click(\FrontEndProductManagerJoomla3Page::$acceptTerms);
		$I->click(\FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$orderReceiptTitle, 30);
		$I->seeElement(['link' => $productName]);
		$I->doFrontendLogout();
	}
}
