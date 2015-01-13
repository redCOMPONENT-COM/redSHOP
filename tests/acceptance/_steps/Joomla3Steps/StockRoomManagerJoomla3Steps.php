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
	 * @param   string  $name       Name of the Stock Room
	 * @param   string  $minAmount  Min Amount
	 *
	 * @return void
	 */
	public function addStockRoom($name = 'Sample', $minAmount = '100')
	{
		$I = $this;
		$I->amOnPage(\StockRoomManagerJoomla3Page::$URL);
		$I->verifyNotices(false, $this->checkForNotices(), 'Stock Room Manager Page');
		$I->click('New');
		$I->waitForElement(\StockRoomManagerJoomla3Page::$stockRoomName, 30);
		$I->fillField(\StockRoomManagerJoomla3Page::$stockRoomName, $name);
		$I->fillField(\StockRoomManagerJoomla3Page::$minimumStockAmount, $minAmount);
		$I->click('Save & Close');
		$I->waitForText(\StockRoomManagerJoomla3Page::$stockRoomSuccessMessage, 60);
		$I->see(\StockRoomManagerJoomla3Page::$stockRoomSuccessMessage);
		$I->click('ID');
		$I->see($name, \StockRoomManagerJoomla3Page::$firstResultRow);
		$I->click('ID');
	}

	/**
	 * Function to update a Stock Room Information
	 *
	 * @param   string  $name     Current Name
	 * @param   string  $newName  Updated Name
	 *
	 * @return void
	 */
	public function editStockRoom($name = 'Sample', $newName = 'Updated Name')
	{
		$I = $this;
		$I->amOnPage(\StockRoomManagerJoomla3Page::$URL);
		$I->click('ID');
		$I->see($name, \StockRoomManagerJoomla3Page::$firstResultRow);
		$I->click(\StockRoomManagerJoomla3Page::$selectFirst);
		$I->click('Edit');
		$I->verifyNotices(false, $this->checkForNotices(), 'Stock Room Edit View Manager Page');
		$I->waitForElement(\StockRoomManagerJoomla3Page::$stockRoomName, 30);
		$I->fillField(\StockRoomManagerJoomla3Page::$stockRoomName, $newName);
		$I->click('Save & Close');
		$I->waitForText(\StockRoomManagerJoomla3Page::$stockRoomSuccessMessage);
		$I->see(\StockRoomManagerJoomla3Page::$stockRoomSuccessMessage);
		$I->see($newName, \StockRoomManagerJoomla3Page::$firstResultRow);
		$I->click('ID');
	}

	/**
	 * Function to change State of a Stock Room
	 *
	 * @param   string  $name   Name of the Stock Room
	 * @param   string  $state  State of the Stock Room
	 *
	 * @return void
	 */
	public function changeState($name, $state = 'unpublish')
	{
		$I = $this;
		$I->amOnPage(\StockRoomManagerJoomla3Page::$URL);
		$I->click('ID');
		$I->see($name, \StockRoomManagerJoomla3Page::$firstResultRow);
		$I->click(\StockRoomManagerJoomla3Page::$selectFirst);

		if ($state == 'unpublish')
		{
			$I->click("Unpublish");
		}
		else
		{
			$I->click("Publish");
		}

		$I->click('ID');

	}

	/**
	 * Function to Search for a Stock Room
	 *
	 * @param   string  $name          Name of the Stock Room
	 * @param   string  $functionName  Name of the function After Which search is being Called
	 *
	 * @return void
	 */
	public function searchStockRoom($name, $functionName = 'Search')
	{
		$I = $this;
		$I->amOnPage(\StockRoomManagerJoomla3Page::$URL);
		$I->click('ID');

		if ($functionName == 'Search')
		{
			$I->see($name, \StockRoomManagerJoomla3Page::$firstResultRow);
		}
		else
		{
			$I->dontSee($name, \StockRoomManagerJoomla3Page::$firstResultRow);
		}

		$I->click('ID');
	}

	/**
	 * Function to get State of the Stock Room
	 *
	 * @param   String  $name  Name of the Stock Room
	 *
	 * @return string
	 */
	public function getState($name)
	{
		$I = $this;
		$I->amOnPage(\StockRoomManagerJoomla3Page::$URL);
		$I->click('ID');
		$I->see($name, \StockRoomManagerJoomla3Page::$firstResultRow);
		$text = $I->grabAttributeFrom(\StockRoomManagerJoomla3Page::$stockRoomStatePath, 'onclick');

		if (strpos($text, 'unpublish') > 0)
		{
			$result = 'published';
		}

		if (strpos($text, 'publish') > 0)
		{
			$result = 'unpublished';
		}

		$I->click('ID');

		return $result;
	}

	/**
	 * Function to Delete Stock Room
	 *
	 * @param   String  $name  Name of the Stock Room which is to be Deleted
	 *
	 * @return void
	 */
	public function deleteStockRoom($name)
	{
		$I = $this;
		$I->amOnPage(\StockRoomManagerJoomla3Page::$URL);
		$I->click('ID');
		$I->see($name, \StockRoomManagerJoomla3Page::$firstResultRow);
		$I->click(\StockRoomManagerJoomla3Page::$selectFirst);
		$I->click('Delete');
		$I->dontSee($name, \StockRoomManagerJoomla3Page::$firstResultRow);
		$I->click('ID');
	}
}
