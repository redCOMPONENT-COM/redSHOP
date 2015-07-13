<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace AcceptanceTester;
/**
 * Class CurrencyManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class CurrencyManagerJoomla3Steps extends AdminManagerJoomla3Steps
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
		$I->amOnPage(\CurrencyManagerPage::$URL);
		$I->click('New');
		$I->waitForElement(\CurrencyManagerPage::$currencyNameField);
		$I->fillField(\CurrencyManagerPage::$currencyNameField, $currencyName);
		$I->fillField(\CurrencyManagerPage::$currencyCodeField, $currencyCode);
		$I->click('Save & Close');
		$I->see('Currency Management', 'h1');
		$I->see('Currency detail saved', '.alert-success');
		$I->click(['link' => 'ID']);
		$I->see($currencyName, \CurrencyManagerPage::$currencyResultRow);
		$I->click(['link' => 'ID']);
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
		$I->amOnPage(\CurrencyManagerPage::$URL);
		$I->click(['link' => 'ID']);
		$I->see($currencyName, \CurrencyManagerPage::$currencyResultRow);
		$I->click(\CurrencyManagerPage::$firstResult);
		$I->click('Edit');
		$I->waitForElement(\CurrencyManagerPage::$currencyNameField);
		$I->verifyNotices(false, $this->checkForNotices(), 'Currency Edit View');
		$I->fillField(\CurrencyManagerPage::$currencyNameField, $newCurrencyName);
		$I->click('Save & Close');
		$I->see('Currency Management', 'h1');
		$I->see($newCurrencyName, \CurrencyManagerPage::$currencyResultRow);
		$I->click(['link' => 'ID']);

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
		$this->delete(new \CurrencyManagerPage, $currencyName, \CurrencyManagerPage::$currencyResultRow, \CurrencyManagerPage::$firstResult);
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
		$this->search(new \CurrencyManagerPage, $currencyName, \CurrencyManagerPage::$currencyResultRow, $functionName);
	}
}
