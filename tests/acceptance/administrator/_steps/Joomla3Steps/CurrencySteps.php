<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace AcceptanceTester;
/**
 * Class CurrencySteps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class CurrencySteps extends AdminManagerJoomla3Steps
{
	/**
	 * Function to add a new Currency
	 *
	 * @param   string  $currencyName  Name of the Currency
	 * @param   string  $currencyCode  Code for the Currency
	 *
	 * @return void
	 */
	public function addCurrency($currencyName = 'TestCurrency', $currencyCode = 'CUR')
	{
		$I = $this;
		$I->amOnPage(\CurrencyPage::$URL);
		$I->click(\CurrencyPage::$buttonNew);
		$I->waitForElement(\CurrencyPage::$currencyNameField);
		$I->fillField(\CurrencyPage::$currencyNameField, $currencyName);
		$I->fillField(\CurrencyPage::$currencyCodeField, $currencyCode);
		$I->click(\CurrencyPage::$buttonSaveClose);
		$I->waitForText(\CurrencyPage::$headPage,60,\CurrencyPage::$headLocators);
		$I->see(\CurrencyPage::$messageSaveSuccess, \CurrencyPage::$containerMessage);
		$I->filterListBySearching($currencyName);
		$I->seeElement(['link' => $currencyName]);
	}

	/**
	 * Function to edit an already created Currency
	 *
	 * @param   string  $currencyName     Name of the currency which is to be edited
	 * @param   string  $newCurrencyName  New Name for the Currency
	 *
	 * @return void
	 */
	public function editCurrency($currencyName = 'TestCurrency', $newCurrencyName = 'NewTestCurrency')
	{
		$I = $this;
		$I->amOnPage(\CurrencyPage::$URL);
		$I->filterListBySearching($currencyName);
		$I->click(['link' => $currencyName]);
		$I->waitForElement(\CurrencyPage::$currencyNameField);
		$I->verifyNotices(false, $this->checkForNotices(), 'Currency Edit View');
		$I->fillField(\CurrencyPage::$currencyNameField, $newCurrencyName);
		$I->click(\CurrencyPage::$buttonSaveClose);
		$I->waitForText(\CurrencyPage::$headPage,60,\CurrencyPage::$headLocators);
		$I->filterListBySearching($newCurrencyName);
		$I->seeElement(['link' => $newCurrencyName]);
	}

	/**
	 * Function to delete the Currency
	 *
	 * @param   string  $currencyName  Name of the Currency which is to be Deleted
	 *
	 * @return void
	 */
	public function deleteCurrency($currencyName = 'TestDeletingCurrency')
	{
		$this->delete(new \CurrencyPage, $currencyName, \CurrencyPage::$currencyResultRow, \CurrencyPage::$firstResult);
	}

	/**
	 * Function to Search for a Currency
	 *
	 * @param   string  $currencyName  Name of the Currency for which we are searching
	 * @param   string  $functionName  Name of the function after which Search is being called
	 *
	 * @return void
	 */
	public function searchCurrency($currencyName = 'TestCurrency', $functionName = 'Search')
	{
		$this->search(new \CurrencyPage, $currencyName, \CurrencyPage::$currencyResultRow, $functionName);
	}
}
