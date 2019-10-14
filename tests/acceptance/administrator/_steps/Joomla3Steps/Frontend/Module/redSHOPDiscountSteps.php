<?php
/**
 * @package     redSHOP
 * @subpackage  Steps redSHOPDiscountSteps
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Frontend\Module;
use ProductManagerPage;
use CheckoutOnFrontEnd;
use FrontEndProductManagerJoomla3Page;

/**
 * Class redSHOPDiscountSteps
 * @package Frontend\Module
 * @since 2.1.3
 */
class redSHOPDiscountSteps extends CheckoutOnFrontEnd
{
	/**
	 * @param $moduleName
	 * @param $username
	 * @param $password
	 * @param $usernameDC
	 * @param $passwordDC
	 * @param $valueDiscount
	 * @param $categoryName
	 * @param $productName
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function checkModuleRedSHOPDiscount($moduleName, $username, $password, $usernameDC, $passwordDC, $valueDiscount, $categoryName, $productName)
	{
		$I = $this;
		$currencyUnit = $I->getCurrencyValue();

		$I->comment("I checkout with User In Group Don't Have Discount");
		$I->doFrontEndLogin($username, $password);
		$I->dontSee($moduleName);
		$I->doFrontendLogout();

		$I->comment("I checkout with User In Group Have Discount");
		$I->doFrontEndLogin($usernameDC, $passwordDC);
		$I->waitForText($moduleName, 30);
		$text = $I->grabTextFrom(FrontEndProductManagerJoomla3Page::$valueDiscount);
		$priceTotal = $currencyUnit['currencySymbol'].($valueDiscount).$currencyUnit['decimalSeparator'].$currencyUnit['numberZero'];
		$I->assertEquals($text, $priceTotal);
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForText($categoryName, 30);
		$I->click($categoryName);
		$I->waitForText($productName, 30);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$addToCart, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$addToCart);
		$I->waitForText(FrontEndProductManagerJoomla3Page::$alertSuccessMessage, 30, FrontEndProductManagerJoomla3Page::$selectorSuccess);
		$I->amOnPage(ProductManagerPage::$cartPageUrL);
		$I->waitForElementVisible(ProductManagerPage::$buttonCheckOut, 30);
		$I->click(ProductManagerPage::$buttonCheckOut);
		$I->waitForElementVisible(ProductManagerPage::$priceEnd, 60);
		$I->waitForElementVisible(ProductManagerPage::$bankTransfer, 30);
		$I->click(ProductManagerPage::$bankTransfer);
		$I->waitForElementVisible(ProductManagerPage::$acceptTerms, 30);
		$I->click(ProductManagerPage::$acceptTerms);
		$I->waitForElementVisible(ProductManagerPage::$checkoutFinalStep, 30);
		$I->click(ProductManagerPage::$checkoutFinalStep);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$orderReceiptTitle, 30);
	}
}