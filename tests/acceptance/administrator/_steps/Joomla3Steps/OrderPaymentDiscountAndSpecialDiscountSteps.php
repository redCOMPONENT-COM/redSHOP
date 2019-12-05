<?php
/**
 * @package     redShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;

use OrderManagerPage;

/**
 * Class OrderPaymentDiscountAndSpecialDiscountSteps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    2.1.4
 */
class OrderPaymentDiscountAndSpecialDiscountSteps extends OrderManagerJoomla3Steps
{
	/**
	 * @param $userName
	 * @param $productName
	 * @param $firstName
	 * @param $address
	 * @param $zipcode
	 * @param $city
	 * @param $phone
	 * @param $priceDiscount
	 * @param $specialUpdate
	 * @param $randomProductPrice
	 * @throws \Exception
	 * @since 2.1.4
	 */
	public function updatePaymentDiscountAndSpecialDiscount($userName, $productName, $firstName, $address, $zipcode, $city, $phone, $priceDiscount, $specialUpdate, $randomProductPrice)
	{
		$I = $this;
		$I->amOnPage(OrderManagerPage::$URL);
		$I->click(OrderManagerPage::$buttonNew);
		$I->waitForText(OrderManagerPage::$titlePage, 30);
		$I->click(OrderManagerPage::$userId);
		$I->waitForElementVisible(OrderManagerPage::$userSearch, 30);
		$userOrderPage = new OrderManagerPage();
		$I->fillField(OrderManagerPage::$userSearch, $userName);
		$I->waitForElement($userOrderPage->returnSearch($userName), 30);
		$I->pressKey(OrderManagerPage::$userSearch, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->waitForElement(OrderManagerPage::$fistName, 30);
		$I->see($userName);
		$I->wait(1.5);
		$I->waitForElement(OrderManagerPage::$address, 30);
		$I->waitForElementVisible(OrderManagerPage::$address, 30);
		$I->fillField(OrderManagerPage::$address, $address);
		$I->fillField(OrderManagerPage::$zipcode, $zipcode);
		$I->fillField(OrderManagerPage::$city, $city);
		$I->fillField(OrderManagerPage::$phone, $phone);
		$I->waitForElement(OrderManagerPage::$applyUser, 30);
		$I->executeJS("jQuery('.button-apply').click()");
		$I->waitForElement(OrderManagerPage::$productId, 30);
		$I->scrollTo(OrderManagerPage::$productId);
		$I->executeJS('window.scrollTo(80,80);');
		$I->waitForElementVisible(OrderManagerPage::$productId, 30);
		$I->click(OrderManagerPage::$productId);
		$I->waitForElement(OrderManagerPage::$productsSearch, 60);
		$I->fillField(OrderManagerPage::$productsSearch, $productName);
		$I->waitForElementVisible($userOrderPage->returnSearch($productName), 30);
		$I->click($userOrderPage->returnSearch($productName));
		$I->wait(1);
		$I->scrollto(OrderManagerPage::$bankTranferPayment);
		$I->executeJS('window.scrollTo(0,100);');
		$I->click(OrderManagerPage::$bankTranferPayment);
		$I->click(OrderManagerPage::$buttonSavePay);
		$I->click(OrderManagerPage::$buttonClose);
		$I->searchOrder($firstName);
		$id = $I->grabTextFrom(OrderManagerPage::$orderID);
		$I->fillField(OrderManagerPage::$filter, $firstName);
		$I->click(OrderManagerPage::$orderID);
		$I->scrollTo(OrderManagerPage::$specialUpdate);
		$I->waitForElementVisible(OrderManagerPage::$specialUpdate, 30);
		$I->fillField(OrderManagerPage::$specialUpdate, $specialUpdate);
		$I->waitForElementVisible($userOrderPage->returnButtonSpecialDiscount($id), 30);
		$I->executeJS('window.scrollTo(65,80);');
		$I->waitForElementVisible(OrderManagerPage::$specialUpdate, 30);
		$I->click($userOrderPage->returnButtonSpecialDiscount($id));
		$I->scrollTo(OrderManagerPage::$specialUpdate);
		$adminFinalPriceEnd = ($randomProductPrice - ($specialUpdate/100)*$randomProductPrice) - (($priceDiscount/100)*($randomProductPrice-($specialUpdate/100)*$randomProductPrice));
		$I->wait(1);
		$I->see($adminFinalPriceEnd);
		$I->executeJS('window.scrollTo(0,0);');
		$I->waitForElement(OrderManagerPage::$close, 30);
		$I->waitForText(OrderManagerPage::$buttonClose, 10, OrderManagerPage::$close);
	}
}