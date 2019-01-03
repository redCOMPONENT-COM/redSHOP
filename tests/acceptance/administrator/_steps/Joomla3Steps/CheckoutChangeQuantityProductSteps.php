<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2018 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace AcceptanceTester;
use Codeception\Module\AcceptanceHelper as AcceptanceHelper;
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
	 * @param $total
	 * @throws \Exception
	 */
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
		foreach ($quantity as $char)
		{
			$I->pressKey(\CheckoutChangeQuantityProductPage::$quantityField, $char);
		}
		$I->waitForElement(\CheckoutChangeQuantityProductPage::$updateCartButton, 30);
		$I->click(\CheckoutChangeQuantityProductPage::$updateCartButton);
		$I->click(\CheckoutChangeQuantityProductPage::$checkoutButton);
		$I->waitForElement(\CheckoutChangeQuantityProductPage::$bankTransfer, 30);
		$I->executeJS("jQuery('#rs_payment_banktransfer0').click()");
		$I->waitForElement(\CheckoutChangeQuantityProductPage::$acceptTerms, 60);
		$I->waitForText($total, 30);

//		$productFrontEndManagerPage = new \CheckoutChangeQuantityProductPage();
//		$I->pauseExecution();
//		$I->executeJS($productFrontEndManagerPage->radioCheckID(\FrontEndProductManagerJoomla3Page::$termAndConditionsId));
//
//		try
//		{
//			$I->seeCheckboxIsChecked(\FrontEndProductManagerJoomla3Page::$termAndConditions);
//		}catch (\Exception $e)
//		{
//			$I->click(\FrontEndProductManagerJoomla3Page::$termAndConditions);
//		}
//
//		$I->seeCheckboxIsChecked(\CheckoutChangeQuantityProductPage::$acceptTerms);
//		$I->waitForElement(\CheckoutChangeQuantityProductPage::$checkoutFinalStep, 60);
//        $I->pauseExecution();
//		$I->click(\CheckoutChangeQuantityProductPage::$checkoutFinalStep);
//		$I->waitForElementVisible(\FrontEndProductManagerJoomla3Page::$addressAddress,60);
//		$I->fillField(\FrontEndProductManagerJoomla3Page::$addressAddress, 'address');
//		$I->fillField(\FrontEndProductManagerJoomla3Page::$addressPostalCode, 1201010);
//		$I->fillField(\FrontEndProductManagerJoomla3Page::$addressCity, "address");
//		$I->fillField(\FrontEndProductManagerJoomla3Page::$addressPhone, '123100120101');
//		$I->click(\FrontEndProductManagerJoomla3Page::$buttonSave);
//
//        $I->waitForElement(\FrontEndProductManagerJoomla3Page::$termAndConditions, 30);
//        $I->click(\FrontEndProductManagerJoomla3Page::$termAndConditions);
//        $I->waitForElement(\FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
//        $I->click(\FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
	}
}