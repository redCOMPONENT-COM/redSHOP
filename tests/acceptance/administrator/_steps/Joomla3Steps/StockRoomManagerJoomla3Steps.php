<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
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
class StockRoomManagerJoomla3Steps extends AdminManagerJoomla3Steps
{
    /**
     * Function to add a new Stock Room Detail
     *
     * @param   string $name Name of the Stock Room
     * @param   string $minAmount Min Amount
     *
     * @return void
     */
    public function addStockRoom($name = 'Sample', $minAmount = '100')
    {
        $I = $this;
        $I->amOnPage(\StockRoomManagerJoomla3Page::$URL);
        $I->click(\StockRoomManagerJoomla3Page::$newButton);
        $I->waitForElement(\StockRoomManagerJoomla3Page::$stockRoomName, 30);
        $I->fillField(\StockRoomManagerJoomla3Page::$stockRoomName, $name);
        $I->fillField(\StockRoomManagerJoomla3Page::$minimumStockAmount, $minAmount);
        $I->click(\StockRoomManagerJoomla3Page::$saveCloseButton);
        $I->waitForText(\StockRoomManagerJoomla3Page::$stockRoomSuccessMessage, 60, \StockRoomManagerJoomla3Page::$selectorSuccess);
    }

    /**
     * Function to update a Stock Room Information
     *
     * @param   string $name Current Name
     * @param   string $newName Updated Name
     *
     * @return void
     */
    public function editStockRoom($name = 'Sample', $newName = 'Updated Name')
    {
        $I = $this;
        $I->amOnPage(\StockRoomManagerJoomla3Page::$URL);
        $I->filterListBySearching($name, $searchField = ['id' => 'filter']);
        $I->click(['link' => $name]);
        $I->waitForElement(\StockRoomManagerJoomla3Page::$stockRoomName, 30);
        $I->fillField(\StockRoomManagerJoomla3Page::$stockRoomName, $newName);
        $I->click(\StockRoomManagerJoomla3Page::$saveCloseButton);
        $I->waitForText(\StockRoomManagerJoomla3Page::$stockRoomSuccessMessage, 60, \StockRoomManagerJoomla3Page::$selectorSuccess);
        $I->see(\StockRoomManagerJoomla3Page::$stockRoomSuccessMessage, \StockRoomManagerJoomla3Page::$selectorSuccess);
    }

    /**
     * Function to change State of a Stock Room
     *
     * @param   string $name Name of the Stock Room
     * @param   string $state State of the Stock Room
     *
     * @return void
     */
    public function changeStockRoomState($name, $state = 'unpublish')
    {
        $this->changeState(new \StockRoomManagerJoomla3Page, $name, $state, \StockRoomManagerJoomla3Page::$firstResultRow, \StockRoomManagerJoomla3Page::$selectFirst);
    }

    /**
     * Function to Search for a Stock Room
     *
     * @param   string $name Name of the Stock Room
     * @param   string $functionName Name of the function After Which search is being Called
     *
     * @return void
     */
    public function searchStockRoom($name, $functionName = 'Search')
    {
        $this->search(new \StockRoomManagerJoomla3Page, $name, \StockRoomManagerJoomla3Page::$firstResultRow, $functionName);
    }

    /**
     * Function to get State of the Stock Room
     *
     * @param   String $name Name of the Stock Room
     *
     * @return string
     */
    public function getStockRoomState($name)
    {
        $result = $this->getState(new \StockRoomManagerJoomla3Page, $name, \StockRoomManagerJoomla3Page::$firstResultRow, \StockRoomManagerJoomla3Page::$stockRoomStatePath);

        return $result;
    }

    /**
     * Function to Delete Stock Room
     *
     * @param   String $name Name of the Stock Room which is to be Deleted
     *
     * @return void
     */
    public function deleteStockRoom($name)
    {
        $this->delete(new \StockRoomManagerJoomla3Page, $name, \StockRoomManagerJoomla3Page::$firstResultRow, \StockRoomManagerJoomla3Page::$selectFirst, $filterId = ['id' => 'filter']);
    }
}
