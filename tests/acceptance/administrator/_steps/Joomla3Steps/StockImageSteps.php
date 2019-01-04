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
	 *
	 * @throws \Exception
	 */
	public function addStockImage($name, $nameStockItem, $amountStock , $quantity)
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
	 *
	 * @throws \Exception
	 */
	public function updateStockImage($name, $nameUpdate, $newQuantity)
	{
		$I = $this;
		$I->amOnPage(\StockImagePage::$URL);
		$I->searchStockImage($name);
		$I->wait(0.2);
		$I->click($name);
		$I->waitForText(\StockImagePage::$titleCreatePage, 30);
		$I->fillField(\StockImagePage::$fieldStockName, $nameUpdate);
		$I->fillField(\StockImagePage::$fieldQuantity, $newQuantity);
		$I->click(\StockImagePage::$buttonSaveClose);
		$I->waitForText(\StockImagePage::$namePage, 30);
		$I->seeElement(['link' => $nameUpdate]);
	}

	/**
	 * @throws \Exception
	 */
	public function deleteAllStockImage()
	{
		$I = $this;
		$I->amOnPage(\StockImagePage::$URL);
		$I->click(\StockImagePage::$resetButton);
		$I->checkAllResults();
		$I->click(\StockImagePage::$buttonDelete);
		$I->see(\StockImagePage::$messageDelete, \StockImagePage::$selectorSuccess);
	}

	/**
	 * Function to Search a Stock
	 *
	 * @param   string $name Name of the Stock which is to be Search
	 *
	 * @return void
	 *
	 * @throws \Exception
	 */
	public function searchStockImage($name)
	{
		$I = $this;
		$I->wantTo('Search the Stock Image');
		$I->waitForText(\StockImagePage::$namePage, 30);
		$I->waitForElement(\StockImagePage::$search);
		$I->fillField(\StockImagePage::$search, $name);
		$I->click(\StockImagePage::$iconSearch);
		$I->seeElement(['link' => $name]);
	}
}