<?php
/**
 * Created by PhpStorm.
 */

namespace AcceptanceTester;
/**
 * Class StockRoomManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */

class StockImageManagement extends AdminManagerJoomla3Steps
{
    public function addStockImage($name = 'Sample', $minAmount = '100'){
        $I = $this;
        $I->amOnPage(\StockImageManagement::$URL);
        $I->click(\StockImageManagement::$newButton);
        $I->waitForElement(\StockImageManagement::$stockRoomName, 30);
        $I->fillField(\StockImageManagement::$stockRoomName, $name);
        $I->fillField(\StockImageManagement::$minimumStockAmount, $minAmount);
        $I->click(\StockImageManagement::$saveCloseButton);
        $I->waitForText(\StockImageManagement::$stockRoomSuccessMessage, 60, \StockImageManagement::$selectorSuccess);
    }
}