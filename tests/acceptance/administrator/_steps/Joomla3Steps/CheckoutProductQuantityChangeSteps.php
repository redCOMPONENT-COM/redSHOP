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
 * @since    2.1
 */
class CheckoutProductQuantityChangeSteps extends AdminManagerJoomla3Steps
{

	public function configChangeQuantityProduct()
	{
		$I = $this;
		$I->amOnPage(\CheckoutProductChangeQuantityPage::$URL);
		$I->click(\CheckoutProductChangeQuantityPage::$Cart);
		$I->waitForElement(\CheckoutProductChangeQuantityPage::$enableQuantity, 30);
		$I->click(\CheckoutProductChangeQuantityPage::$enableQuantity);
		$I->click(\CheckoutProductChangeQuantityPage::$buttonSave);
	}

	public function goOnFrontEnd($category)
	{
		$I = $this;
		$I->amOnPage(\CheckoutProductChangeQuantityPage::$url);
		$I->waitForElement(\CheckoutProductChangeQuantityPage::$categoryTitle, 30);
		$I->click($category);
		$I->click(\CheckoutProductChangeQuantityPage::$addToCart);
		$I->amOnPage(\CheckoutProductChangeQuantityPage::$cartPageUrL);
		$I->click(\CheckoutProductChangeQuantityPage::$quantityField);
		$I->pressKey(\CheckoutProductChangeQuantityPage::$quantityField, \Facebook\WebDriver\WebDriverKeys::BACKSPACE);
		$quantities = 10;
		$quantity = str_split($quantities);
		foreach ($quantity as $char) {
			$I->pressKey(\CheckoutProductChangeQuantityPage::$quantityField, $char);
		}
		$I->waitForElement(\CheckoutProductChangeQuantityPage::$updateCartButton, 30);
		$I->click(\CheckoutProductChangeQuantityPage::$updateCartButton);
		$I->click(\CheckoutProductChangeQuantityPage::$checkoutButton);
		$I->click(\CheckoutProductChangeQuantityPage::$bankTransfer);
		$I->click(\CheckoutProductChangeQuantityPage::$checkoutButton);
		$I->waitForElement(\CheckoutProductChangeQuantityPage::$termAndConditions, 30);
		$I->click(\CheckoutProductChangeQuantityPage::$termAndConditions);
		$I->click(\CheckoutProductChangeQuantityPage::$checkoutFinalStep);
	}

	public function returnConfig()
	{
		$I = $this;
		$I->amOnPage(\CheckoutProductChangeQuantityPage::$URL);
		$I->click(\CheckoutProductChangeQuantityPage::$Cart);
		$I->waitForElement(\CheckoutProductChangeQuantityPage::$disableQuantity, 30);
		$I->click(\CheckoutProductChangeQuantityPage::$disableQuantity);
		$I->click(\CheckoutProductChangeQuantityPage::$buttonSave);
	}
}
