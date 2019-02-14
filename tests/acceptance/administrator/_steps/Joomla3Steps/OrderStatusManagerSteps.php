<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;
/**
 * Class OrderStatusManagerSteps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    2.4
 */
class OrderStatusManagerSteps extends AdminManagerJoomla3Steps
{
    public function createOrderStatus($orderStatusName,$orderStatusCode)
    {
        $I = $this;
        $I->amOnPage(\OrderStatusManagerPage::$URL);
        $I->click(\OrderStatusManagerPage::$buttonNew);
        $I->fillField(\OrderStatusManagerPage::$orderstatusName, $orderStatusName);
        $I->fillField(\OrderStatusManagerPage::$orderstatusCode, $orderStatusCode);
        $I->click(\OrderStatusManagerPage::$buttonSave);
        $I->waitForText(\OrderStatusManagerPage::$messageSaveSuccess, 30, \OrderStatusManagerPage::$selectorSuccess);
    }

    public function createOrderStatusMissingName($orderStatusCode)
    {
        $I = $this;
        $I->amOnPage(\OrderStatusManagerPage::$URL);
        $I->click(\OrderStatusManagerPage::$buttonNew);
        $I->fillField(\OrderStatusManagerPage::$orderstatusCode, $orderStatusCode);
        $I->click(\OrderStatusManagerPage::$buttonSave);
        $I->waitForText(\OrderStatusManagerPage::$messageNameFieldRequired, 30, \OrderStatusManagerPage::$selectorError);
    }

    public function createOrderStatusMissingCode($orderStatusName)
    {
        $I = $this;
        $I->amOnPage(\OrderStatusManagerPage::$URL);
        $I->click(\OrderStatusManagerPage::$buttonNew);
        $I->fillField(\OrderStatusManagerPage::$orderstatusName, $orderStatusName);
        $I->click(\OrderStatusManagerPage::$buttonSave);
        $I->waitForText(\OrderStatusManagerPage::$messageCodeFieldRequired, 30, \OrderStatusManagerPage::$selectorError);
    }

    public function editOrderStatus($orderStatusName, $changeName)
    {
        $I = $this;
        $I->amOnPage(\OrderStatusManagerPage::$URL);
        $I->fillField(\OrderStatusManagerPage::$filterSearch, $orderStatusName);
        $I->presskey(\OrderStatusManagerPage::$filterSearch, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
        $I->click(\OrderStatusManagerPage::$orderstatusName);
        $I->waitForElement(\OrderStatusManagerPage::$orderstatusName, 30);
        $I->fillField(\OrderStatusManagerPage::$orderstatusName, $changeName);
        $I->click(\OrderStatusManagerPage::$buttonSave);
        $I->waitForText(\OrderStatusManagerPage::$messageSaveSuccess, 30, \OrderStatusManagerPage::$selectorSuccess);
        $I->see($orderStatusName);
    }


    public function searchOrderStatus($orderStatusName)
    {
        $I = $this;
        $I->wantTo('Search the Order Status');
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->click(\OrderStatusManagerPage::$resetButton);
        $I->searchOrderStatus($orderStatusName);
        $I->waitForText(\OrderStatusManagerPage::$messageDelete, \OrderStatusManagerPage::$selectorSuccess);
    }

    public function deleteOrderstatus($orderStatusName)
    {
        $I = $this;
        $I->amOnPage(\OrderStatusManagerPage::$URL);
        $I->searchOrderStatus($orderStatusName);
        $I->checkAllResults();
        $I->click(ProductManagerPage::$buttonDelete);
    }
}