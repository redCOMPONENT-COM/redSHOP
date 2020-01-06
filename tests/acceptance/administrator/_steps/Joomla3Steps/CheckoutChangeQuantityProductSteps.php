<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace AcceptanceTester;
use Codeception\Module\AcceptanceHelper as AcceptanceHelper;
use FrontEndProductManagerJoomla3Page;

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
	/**
	 * @param $category
	 * @param $productName
	 * @param $total
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function checkoutChangeQuantity($category, $productName, $total)
	{
		$I = $this;
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->amOnPage(\CheckoutChangeQuantityProductPage::$url);
		$I->click($category);
		$I->waitForElementVisible(["link" => $productName], 30);
		$I->click(["link" => $productName]);
		$I->waitForElementVisible(\CheckoutChangeQuantityProductPage::$addToCart, 30);
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
		$I->waitForElement(\CheckoutChangeQuantityProductPage::$bankTransfer, 30);
		$I->executeJS("jQuery('#rs_payment_banktransfer0').click()");
		$I->wait(2);
		try
		{
			$I->seeCheckboxIsChecked(FrontEndProductManagerJoomla3Page::$bankTransfer);
		}catch (\Exception $e)
		{
			$I->click(FrontEndProductManagerJoomla3Page::$bankTransfer);
		}
		$I->waitForElement(\CheckoutChangeQuantityProductPage::$acceptTerms, 30);
		$I->wait(0.5);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$acceptTerms, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
		try
		{
			$I->seeCheckboxIsChecked(FrontEndProductManagerJoomla3Page::$termAndConditions);
		}catch (\Exception $e)
		{
			$I->executeJS($productFrontEndManagerPage->radioCheckID(FrontEndProductManagerJoomla3Page::$termAndConditionsId));
		}
		$I->waitForElement(\CheckoutChangeQuantityProductPage::$checkoutFinalStep, 60);
		$I->click(\CheckoutChangeQuantityProductPage::$checkoutFinalStep);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$addressAddress, 30);
		$I->waitForElementVisible(\FrontEndProductManagerJoomla3Page::$addressAddress, 30);
		$I->fillField(\FrontEndProductManagerJoomla3Page::$addressAddress, 'address');
		$I->fillField(\FrontEndProductManagerJoomla3Page::$addressPostalCode, 1201010);
		$I->fillField(\FrontEndProductManagerJoomla3Page::$addressCity, "address");
		$I->fillField(\FrontEndProductManagerJoomla3Page::$addressPhone, '123100120101');
		$I->click(\FrontEndProductManagerJoomla3Page::$buttonSave);
		$I->waitForText(\FrontEndProductManagerJoomla3Page::$headBilling, 30, null);
		$I->amOnPage(\FrontEndProductManagerJoomla3Page::$cartPageUrL);
		$I->see($total, \FrontEndProductManagerJoomla3Page::$priceTotal);
		$I->click(\FrontEndProductManagerJoomla3Page::$checkoutButton);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$bankTransfer, 60);
		$I->click(FrontEndProductManagerJoomla3Page::$bankTransfer);

		$I->waitForElement(\CheckoutChangeQuantityProductPage::$acceptTerms, 30);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$acceptTerms, 30);
		$I->executeJS($productFrontEndManagerPage->radioCheckID(FrontEndProductManagerJoomla3Page::$termAndConditionsId));
		$I->wait(0.5);

		try
		{
			$I->seeCheckboxIsChecked(FrontEndProductManagerJoomla3Page::$termAndConditions);
			$I->waitForElementVisible(\CheckoutChangeQuantityProductPage::$checkoutFinalStep, 60);
			$I->click(\CheckoutChangeQuantityProductPage::$checkoutFinalStep);
			$I->waitForText(\FrontEndProductManagerJoomla3Page::$orderReceipt, 30);
		}catch (\Exception $e)
		{
			$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$termAndConditions, 30);
			$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
			$I->waitForElement(\CheckoutChangeQuantityProductPage::$checkoutFinalStep, 60);
			$I->click(\CheckoutChangeQuantityProductPage::$checkoutFinalStep);
			$I->waitForText(\FrontEndProductManagerJoomla3Page::$orderReceipt, 30);
		}
	}
}
