<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2018 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace AcceptanceTester;
/**
 * Class ProductManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    2.4
 */
class CheckoutChangeQuantityProductSteps extends AdminManagerJoomla3Steps
{
	public function checkoutChangeQuantity($category, $total)
	{
		$I = $this;
		$I->amOnPage(\CheckoutChangeQuantityProductPage::$url);
		$I->click($category);
		$I->click(\CheckoutChangeQuantityProductPage::$addToCart);
		$I->amOnPage(\CheckoutChangeQuantityProductPage::$cartPageUrL);
		$I->click(\CheckoutChangeQuantityProductPage::$quantityField);
		$I->pressKey(\CheckoutChangeQuantityProductPage::$quantityField, \Facebook\WebDriver\WebDriverKeys::BACKSPACE);
		$quantities = 10;
		$quantity = str_split($quantities);
		foreach ($quantity as $char) {
			$I->pressKey(\CheckoutChangeQuantityProductPage::$quantityField, $char);
		}
		$I->waitForElement(\CheckoutChangeQuantityProductPage::$updateCartButton, 30);
		$I->click(\CheckoutChangeQuantityProductPage::$updateCartButton);
		$I->click(\CheckoutChangeQuantityProductPage::$checkoutButton);
		$I->scrollTo(\CheckoutChangeQuantityProductPage::$bankTransfer);
		$I->click(\CheckoutChangeQuantityProductPage::$bankTransfer);
		$I->waitForElement(\CheckoutChangeQuantityProductPage::$termAndConditions,30);
		$I->click(\CheckoutChangeQuantityProductPage::$termAndConditions);
		$I->scrollTo(\CheckoutChangeQuantityProductPage::$checkoutFinalStep);
		$I->click(\CheckoutChangeQuantityProductPage::$checkoutFinalStep);
		$I->waitForElementVisible(\FrontEndProductManagerJoomla3Page::$addressAddress);
		$I->fillField(\FrontEndProductManagerJoomla3Page::$addressAddress, 'address');
		$I->fillField(\FrontEndProductManagerJoomla3Page::$addressPostalCode, 1201010);
		$I->fillField(\FrontEndProductManagerJoomla3Page::$addressCity, "address");
		$I->fillField(\FrontEndProductManagerJoomla3Page::$addressPhone, '123100120101');
		$I->click(\FrontEndProductManagerJoomla3Page::$buttonSave);
		$I->waitForText(\FrontEndProductManagerJoomla3Page::$headBilling, 30, null);
		$I->amOnPage(\FrontEndProductManagerJoomla3Page::$cartPageUrL);
		$I->see($total, \FrontEndProductManagerJoomla3Page::$priceTotal);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$checkoutButton, 10);
		$I->click(\FrontEndProductManagerJoomla3Page::$checkoutButton);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 10);
	}
}