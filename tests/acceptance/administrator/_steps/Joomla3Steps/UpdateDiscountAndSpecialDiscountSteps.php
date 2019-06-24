<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
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
class UpdateDiscountAndSpecialDiscountSteps extends OrderManagerJoomla3Steps
{
	/**
	 * @param $userName
	 * @param $productName
	 * @param $firstName
	 * @param $discountUpdate
	 * @param $specialUpdate
	 * @param $randomProductPrice
	 */
	public function updateDiscountAndSpecialDiscount($userName, $productName, $firstName, $discountUpdate, $specialUpdate, $randomProductPrice)
	{
		$I = $this;
		$I->amOnPage(\OrderManagerPage::$URL);
		$I->click(\OrderManagerPage::$buttonNew);
		$I->click(\OrderManagerPage::$userId);
		$I->waitForElement(\OrderManagerPage::$userSearch, 30);
		$userOrderPage = new \OrderManagerPage();

		$I->fillField(\OrderManagerPage::$userSearch, $userName);
		$I->waitForElement($userOrderPage->returnSearch($userName), 30);
		$I->pressKey(\OrderManagerPage::$userSearch, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->waitForElement(\OrderManagerPage::$fistName, 30);
		$I->see($userName);
		$I->wait(2);

		$I->waitForElement(\OrderManagerPage::$address, 30);
		$I->waitForElementVisible(\OrderManagerPage::$address, 30);
		$I->fillField(\OrderManagerPage::$address, 'address');
		$I->fillField(\OrderManagerPage::$zipcode, 1201010);
		$I->fillField(\OrderManagerPage::$city, "address");
		$I->fillField(\OrderManagerPage::$phone, '123100120101');

		$I->waitForElement(\OrderManagerPage::$applyUser, 30);
		$I->executeJS("jQuery('.button-apply').click()");
		$I->waitForElement(\OrderManagerPage::$productId, 30);
		$I->executeJS('window.scrollTo(65,80);');
		$I->waitForElementVisible(\OrderManagerPage::$productId, 30);
		$I->click(\OrderManagerPage::$productId);
		$I->waitForElement(\OrderManagerPage::$productsSearch, 30);
		$I->fillField(\OrderManagerPage::$productsSearch, $productName);
		$I->waitForElement($userOrderPage->returnSearch($productName), 30);
		$I->click($userOrderPage->returnSearch($productName));
		$I->wait(0.2);
		$I->click(\OrderManagerPage::$buttonSave);

		$I->click(\OrderManagerPage::$buttonClose);
		$I->searchOrder($firstName);
		$id = $I->grabTextFrom("//div[@class='table-responsive']//td[3]//a[1]");
		$I->fillField(\OrderManagerPage::$filter, $firstName);
		$I->click("//div[@class='table-responsive']//td[3]//a[1]");

		$I->scrollTo(\OrderManagerPage::$discountUpdate);
		$I->waitForElementVisible(\OrderManagerPage::$discountUpdate, 30);
		$I->fillField(\OrderManagerPage::$discountUpdate, $discountUpdate);
		$I->waitForElement($userOrderPage->returnButtonUpdateDiscount($id), 30);
		$I->executeJS('window.scrollTo(65,80);');
		$I->waitForElementVisible(\OrderManagerPage::$discountUpdate, 30);
		$I->click($userOrderPage->returnButtonUpdateDiscount($id));

		$I->scrollTo(\OrderManagerPage::$specialUpdate);
		$I->waitForElementVisible(\OrderManagerPage::$specialUpdate, 30);
		$I->fillField(\OrderManagerPage::$specialUpdate, $specialUpdate);
		$I->waitForElement($userOrderPage->returnButtonSpecialDiscount($id), 30);
		$I->executeJS('window.scrollTo(65,80);');
		$I->waitForElementVisible(\OrderManagerPage::$specialUpdate, 30);
		$I->click($userOrderPage->returnButtonSpecialDiscount($id));
		$I->scrollTo(\OrderManagerPage::$specialUpdate);
		$adminFinalPriceEnd = $randomProductPrice-($discountUpdate+$specialUpdate);
		$I->see($adminFinalPriceEnd);

		$I->waitForElement(\OrderManagerPage::$close, 30);
		$I->waitForText(\OrderManagerPage::$buttonClose, 10, \OrderManagerPage::$close);

	}
}