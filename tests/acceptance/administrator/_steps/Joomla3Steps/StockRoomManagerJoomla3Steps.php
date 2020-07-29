<?php
/**
 * @package     redSHOP
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;
use StockRoomManagerJoomla3Page;

/**
 * Class StockRoomManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4.0
 */
class StockRoomManagerJoomla3Steps extends AdminManagerJoomla3Steps
{
	/**
	 * Function to add a new Stockroom Detail
	 * @param string $name
	 * @param string $minAmount
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function addStockRoom($name = 'Sample', $minAmount = '100')
	{
		$I = $this;
		$I->amOnPage(StockRoomManagerJoomla3Page::$URL);
		$I->click(StockRoomManagerJoomla3Page::$newButton);
		$I->waitForElement(StockRoomManagerJoomla3Page::$stockRoomName, 30);
		$I->fillField(StockRoomManagerJoomla3Page::$stockRoomName, $name);
		$I->fillField(StockRoomManagerJoomla3Page::$minimumStockAmount, $minAmount);
		$I->waitForElement(StockRoomManagerJoomla3Page::$saveButton, 30);
		$I->click(StockRoomManagerJoomla3Page::$saveButton);
		$I->waitForText(StockRoomManagerJoomla3Page::$stockRoomSuccessMessage, 60, StockRoomManagerJoomla3Page::$selectorSuccess);
		$I->click(StockRoomManagerJoomla3Page::$buttonClose);
	}

	/**
	 * Function to update a Stockroom Information
	 * @param string $name
	 * @param string $newName
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function editStockRoom($name = 'Sample', $newName = 'Updated Name')
	{
		$I = $this;
		$I->amOnPage(StockRoomManagerJoomla3Page::$URL);
		$I->filterListBySearching($name, StockRoomManagerJoomla3Page::$searchField);
		$I->wait(0.1);
		$I->click(['link' => $name]);
		$I->waitForElement(StockRoomManagerJoomla3Page::$stockRoomName, 30);
		$I->fillField(StockRoomManagerJoomla3Page::$stockRoomName, $newName);
		$I->click(StockRoomManagerJoomla3Page::$buttonSaveClose);
		$I->waitForText(StockRoomManagerJoomla3Page::$stockRoomSuccessMessage, 60, StockRoomManagerJoomla3Page::$selectorSuccess);
		$I->see(StockRoomManagerJoomla3Page::$stockRoomSuccessMessage, StockRoomManagerJoomla3Page::$selectorSuccess);
	}

	/**
	 * Function to change State of a Stockroom
	 * @param string $state
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function changeStockRoomState($state = 'unpublish')
	{
		$this->changeState(new StockRoomManagerJoomla3Page, $state);
	}

	/**
	 * Function to Search for a Stockroom
	 * @param $name
	 * @param string $functionName
	 * @since 1.4.0
	 */
	public function searchStockRoom($name, $functionName = 'Search')
	{
		$this->search(new StockRoomManagerJoomla3Page, $name, StockRoomManagerJoomla3Page::$firstResultRow, $functionName);
	}

	/**
	 * Function to get State of the Stockroom
	 * @param $name
	 * @return string
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function getStockRoomState($name)
	{
		$result = $this->getState(new StockRoomManagerJoomla3Page, $name, StockRoomManagerJoomla3Page::$firstResultRow, StockRoomManagerJoomla3Page::$stockRoomStatePath);

		return $result;
	}

	/**
	 * Function to Delete Stockroom
	 *
	 * @param   String $name Name of the Stockroom which is to be Deleted
	 *
	 * @return void
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function deleteStockRoom($name)
	{
		$this->delete(new StockRoomManagerJoomla3Page, $name, StockRoomManagerJoomla3Page::$firstResultRow, StockRoomManagerJoomla3Page::$selectFirst, StockRoomManagerJoomla3Page::$searchField);
	}

	/**
	 * Function to Delete all Stockroom
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function deleteAllStockRoom()
	{
		$I = $this;
		$I->amOnPage(StockRoomManagerJoomla3Page::$URL);
		$I->click(StockRoomManagerJoomla3Page::$resetButton);
		$I->checkAllResults();
		$I->click(StockRoomManagerJoomla3Page::$buttonDelete);
		$I->acceptPopup();
		$I->see(StockRoomManagerJoomla3Page::$deleteMessage, StockRoomManagerJoomla3Page::$selectorSuccess);
	}
}
