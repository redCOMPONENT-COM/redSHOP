<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace AcceptanceTester;
/**
 * Class StockImageManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */

class StockImageManagerJoomla3Steps extends AdminManagerJoomla3Steps
{
	/**
	 * Function to create a new Stock Image
	 *
	 * @param   string  $imageToolTip   Image Tool Tip
	 * @param   string  $stockAmount    Amount for the Stock Image
	 * @param   string  $stockQuantity  Quantity for Stock Image
	 *
	 * @return void
	 */
	public function addStockImage($imageToolTip = 'Sample Tip', $stockAmount = 'Higher than', $stockQuantity = '10')
	{
		$I = $this;
		$I->amOnPage(\StockImageManagerJoomla3Page::$URL);
		$stockImageManagerPage = new \StockImageManagerJoomla3Page;
		$I->verifyNotices(false, $this->checkForNotices(), 'Stock Image Manager Page');
		$I->click('New');
		$I->waitForElement(\StockImageManagerJoomla3Page::$stockImageToolTip,30);
		$I->fillField(\StockImageManagerJoomla3Page::$stockImageToolTip, $imageToolTip);
		$I->fillField(\StockImageManagerJoomla3Page::$stockQuantity, $stockQuantity);
		$I->click(\StockImageManagerJoomla3Page::$stockAmountDropDown);
		$I->waitForElement($stockImageManagerPage->stockAmount($stockAmount),30);
		$I->click($stockImageManagerPage->stockAmount($stockAmount));
		$I->click('Save & Close');
		$I->waitForText(\StockImageManagerJoomla3Page::$stockImageSuccessMessage,60,'.alert-success');
		$I->see(\StockImageManagerJoomla3Page::$stockImageSuccessMessage,'.alert-success');
		$I->executeJS('window.scrollTo(0,0)');
		$I->click(['link' => 'ID']);
		$I->see($imageToolTip, \StockImageManagerJoomla3Page::$firstResultRow);
		$I->executeJS('window.scrollTo(0,0)');
		$I->click(['link' => 'ID']);
	}

	/**
	 * Function to update a Stock Room
	 *
	 * @param   string  $imageToolTip   Image tip current
	 * @param   string  $updateToolTip  Updated Tool Tip
	 *
	 * @return void
	 */
	public function editStockImage($imageToolTip = 'Sample Tip', $updateToolTip = 'New Sample Tip')
	{
		$I = $this;
		$I->amOnPage(\StockImageManagerJoomla3Page::$URL);
		$I->executeJS('window.scrollTo(0,0)');
		$I->click(['link' => 'ID']);
		$I->see($imageToolTip, \StockImageManagerJoomla3Page::$firstResultRow);
		$I->click(\StockImageManagerJoomla3Page::$selectFirst);
		$I->click('Edit');
		$I->verifyNotices(false, $this->checkForNotices(), 'Stock Image Edit View Manager Page');
		$I->waitForElement(\StockImageManagerJoomla3Page::$stockImageToolTip,30);
		$I->fillField(\StockImageManagerJoomla3Page::$stockImageToolTip, $updateToolTip);
		$I->click('Save & Close');
		$I->waitForText(\StockImageManagerJoomla3Page::$stockImageSuccessMessage,60, '.alert-success');
		$I->see(\StockImageManagerJoomla3Page::$stockImageSuccessMessage, '.alert-success');
		$I->see($updateToolTip, \StockImageManagerJoomla3Page::$firstResultRow);
		$I->executeJS('window.scrollTo(0,0)');
		$I->click(['link' => 'ID']);
	}

	/**
	 * Function to Search for a Stock Image
	 *
	 * @param   string  $imageTip      Tip of the Image
	 * @param   string  $functionName  Name of the function After Which search is being Called
	 *
	 * @return void
	 */
	public function searchStockImage($imageTip, $functionName = 'Search')
	{
		$this->search(new \StockImageManagerJoomla3Page, $imageTip, \StockImageManagerJoomla3Page::$firstResultRow, $functionName);
	}

	/**
	 * Function to Delete Stock Image
	 *
	 * @param   String  $imageTip  Image Tip which is to be deleted
	 *
	 * @return void
	 */
	public function deleteStockImage($imageTip)
	{
		$this->delete(new \StockImageManagerJoomla3Page, $imageTip, \StockImageManagerJoomla3Page::$firstResultRow, \StockImageManagerJoomla3Page::$selectFirst);
	}
}
