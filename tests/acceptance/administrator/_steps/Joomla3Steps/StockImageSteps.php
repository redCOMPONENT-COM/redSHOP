<?php
/**
 * Class StockImageSteps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    2.4
 */

namespace AcceptanceTester;

class StockImageSteps extends AdminManagerJoomla3Steps
{
	/**
	 * Function to Create a Stock
	 *
	 * @param   string $name Name of the Stock which is to be Create
	 *
	 * @return void
	 */
	public function addStockImage($name = 'Name Stock Image ?##?', $nameStockItem = 'default', $amountStock = 'Higher than', $quantity = '50')
	{
		$I = $this;
		$I->amOnPage(\StockImagePage::$URL);
		$I->click(\StockImagePage::$buttonNew);
		$I->waitForText(\StockImagePage::$titleCreatePage, 5);
		$I->fillField(\StockImagePage::$fieldStockName, $name);
		$I->fillField(\StockImagePage::$fieldQuantity, $quantity);
		$I->click(\StockImagePage::$fieldDropStock);
		$I->fillField(\StockImagePage::$fieldSearchStock, $nameStockItem);
		$I->click(\StockImagePage::$chooseStock);
		$I->click(\StockImagePage::$fieldDropAmount);
		$I->fillField(\StockImagePage::$fieldSearchAmount, $amountStock);
		$I->click(\StockImagePage::$chooseAmount);
		$I->click(\StockImagePage::$buttonSaveClose);
		$I->waitForText(\StockImagePage::$namePage, 5);
		$I->seeElement(['link' => $name]);
	}

	/**
	 * Function to Edit a Stock
	 *
	 * @param   string $name Name of the Stock which is to be Edit
	 *
	 * @return void
	 */
	public function updateStockImage($name = 'Name Stock Image ?##?', $newQuantity = '40')
	{
		$I = $this;
		$I->amOnPage(\StockImagePage::$URL);
		$I->searchStockImage($name);
		$I->click($name);
		$I->waitForText(\StockImagePage::$titleCreatePage, 30);
		$I->checkForPhpNoticesOrWarnings();
		$I->waitForElement(\StockImagePage::$fieldQuantity, 30);
		$I->fillField(\StockImagePage::$fieldQuantity, $newQuantity);
		$I->click(\StockImagePage::$buttonSaveClose);
		$I->waitForText(\StockImagePage::$namePage, 30);
		$I->seeElement(['link' => $name]);
	}

	/**
	 * Function to Delete a Stock
	 *
	 * @param   string $name Name of the Stock which is to be Delete
	 *
	 * @return void
	 */
	public function deleteStockImage($name = 'Name Stock Image ?##?')
	{
		$I = $this;
		$I->amOnPage(\StockImagePage::$URL);
		$I->checkForPhpNoticesOrWarnings();
		$I->waitForElement(\StockImagePage::$search, 30);
		$I->searchStockImage($name);
		$I->checkAllResults();
		$I->waitForText(\StockImagePage::$buttonDelete, 30);
		$I->click(\StockImagePage::$buttonDelete);
		$I->dontSeeElement(['link' => $name]);
	}

	/**
	 * Function to Search a Stock
	 *
	 * @param   string $name Name of the Stock which is to be Search
	 *
	 * @return void
	 */
	public function searchStockImage($name)
	{
		$I = $this;
		$I->wantTo('Search the Stock Image');
		$I->waitForText(\StockImagePage::$namePage, 30);
		$I->waitForElement(\StockImagePage::$search);
		$I->filterListBySearching($name, \StockImagePage::$search);
	}
}