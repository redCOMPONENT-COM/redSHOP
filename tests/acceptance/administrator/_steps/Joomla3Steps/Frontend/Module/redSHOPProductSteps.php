<?php
/**
 * @package     redSHOP
 * @subpackage  Steps redSHOPProductSteps
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */


namespace Frontend\Module;
use CheckoutOnFrontEnd;
use FrontEndProductManagerJoomla3Page;
use ProductManagerPage;

/**
 * Class redSHOPProduct
 * @package Frontend\Module
 * @since 2.1.3
 */
class redSHOPProductSteps extends CheckoutOnFrontEnd
{
	/**
	 * @param $moduleName
	 * @param $productName
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function checkModuleRedSHOPProduct($moduleName, $price, $productName, $username, $password)
	{
		$I = $this;
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForText($moduleName, 30);
		$text = $I->grabTextFrom(FrontEndProductManagerJoomla3Page::$nameRedSHOPProduct);
		$I->assertEquals($text, $productName);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$imageAddToCart);
		$I->click(FrontEndProductManagerJoomla3Page::$imageAddToCart);
		$I->waitForText(FrontEndProductManagerJoomla3Page::$alertSuccessMessage);
		$I->fillField(ProductManagerPage::$username, $username);
		$I->fillField(ProductManagerPage::$password, $password);
		$I->click(ProductManagerPage::$buttonLogin);
		$I->amOnPage(ProductManagerPage::$cartPageUrL);
		$I->click(ProductManagerPage::$buttonCheckOut);
		$I->waitForElement(ProductManagerPage::$priceEnd, 60);
		$I->click(ProductManagerPage::$bankTransfer);
		$I->waitForElement(ProductManagerPage::$acceptTerms, 30);
		$I->click(ProductManagerPage::$acceptTerms);
		$I->click(ProductManagerPage::$checkoutFinalStep);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$orderReceiptTitle, 30);
	}
}