<?php
/**
 * @package     redSHOP
 * @subpackage  Steps
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;

use StockImagePage;

/**
 * Class StockImageSteps
 * @package AcceptanceTester
 * @since 1.4.0
 */
class StockImageSteps extends AdminManagerJoomla3Steps
{
	/**
	 * Function to Create a Stock
	 * @param $name
	 * @param $nameStockItem
	 * @param $amountStock
	 * @param $quantity
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function addStockImage($name, $nameStockItem, $amountStock , $quantity)
	{
		$I = $this;
		$I->amOnPage(StockImagePage::$URL);
		$I->click(StockImagePage::$buttonNew);
		$I->waitForText(StockImagePage::$titleCreatePage, 5);
		$I->fillField(StockImagePage::$fieldStockName, $name);
		$I->fillField(StockImagePage::$fieldQuantity, $quantity);
		$I->click(StockImagePage::$fieldDropStock);
		$I->fillField(StockImagePage::$fieldSearchStock, $nameStockItem);
		$I->click(StockImagePage::$chooseStock);
		$I->click(StockImagePage::$fieldDropAmount);
		$I->fillField(StockImagePage::$fieldSearchAmount, $amountStock);
		$I->click(StockImagePage::$chooseAmount);
		$I->click(StockImagePage::$buttonSaveClose);
		$I->waitForText(StockImagePage::$namePage, 5);
		$I->seeElement(['link' => $name]);
	}

	/**
	 * Function to Edit a Stock
	 * @param $name
	 * @param $nameUpdate
	 * @param $newQuantity
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function updateStockImage($name, $nameUpdate, $newQuantity)
	{
		$I = $this;
		$I->amOnPage(StockImagePage::$URL);
		$I->searchStockImage($name);
		$I->wait(0.2);
		$I->click($name);
		$I->waitForText(StockImagePage::$titleCreatePage, 30);
		$I->fillField(StockImagePage::$fieldStockName, $nameUpdate);
		$I->fillField(StockImagePage::$fieldQuantity, $newQuantity);
		$I->click(StockImagePage::$buttonSaveClose);
		$I->waitForText(StockImagePage::$namePage, 30);
		$I->seeElement(['link' => $nameUpdate]);
	}

	/**
	 * Function to delete all Stock
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function deleteAllStockImage()
	{
		$I = $this;
		$I->amOnPage(StockImagePage::$URL);
		$I->click(StockImagePage::$resetButton);
		$I->checkAllResults();
		$I->click(StockImagePage::$buttonDelete);
		$I->acceptPopup();
		$I->see(StockImagePage::$messageDelete, StockImagePage::$selectorSuccess);
	}

	/**
	 * Function to Search a Stock
	 *
	 * @param   string $name Name of the Stock which is to be Search
	 *
	 * @return void
	 *
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function searchStockImage($name)
	{
		$I = $this;
		$I->wantTo('Search the Stock Image');
		$I->waitForText(StockImagePage::$namePage, 30);
		$I->waitForElement(StockImagePage::$search);
		$I->fillField(StockImagePage::$search, $name);
		$I->click(StockImagePage::$iconSearch);
		$I->seeElement(['link' => $name]);
	}
}