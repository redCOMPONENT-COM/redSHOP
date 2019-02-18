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
class UpdateDiscountAndSpecialDiscountSteps extends AdminManagerJoomla3Steps
{
    public function updateDiscountAndSpecialDiscount($userName, $productName)
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

        $I->waitForElement(\OrderManagerPage::$address);
        $I->waitForElementVisible(\OrderManagerPage::$address);
        $I->fillField(\OrderManagerPage::$address, 'address');
        $I->fillField(\OrderManagerPage::$zipcode, 1201010);
        $I->fillField(\OrderManagerPage::$city, "address");
        $I->fillField(\OrderManagerPage::$phone, '123100120101');

        $I->waitForElement(\OrderManagerPage::$applyUser, 30);
        $I->executeJS("jQuery('.button-apply').click()");
        $I->waitForElement(\OrderManagerPage::$productId, 30);
        $I->scrollTo(\OrderManagerPage::$productId);
        $I->waitForElement(\OrderManagerPage::$productId, 30);
        $I->click(\OrderManagerPage::$productId);
        $I->waitForElement(\OrderManagerPage::$productsSearch, 30);
        $I->fillField(\OrderManagerPage::$productsSearch, $productName);
        $I->waitForElement($userOrderPage->returnSearch($productName), 30);
        $I->click($userOrderPage->returnSearch($productName));


        $I->click(\OrderManagerPage::$buttonSave);
        $I->scrollTo();



        $I->waitForElement(\OrderManagerPage::$close, 30);
        $I->waitForText(\OrderManagerPage::$buttonClose, 10, \OrderManagerPage::$close);

    }
}