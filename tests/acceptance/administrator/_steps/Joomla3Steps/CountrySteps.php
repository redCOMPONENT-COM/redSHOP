<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;

/**
 * Class CountryManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class CountrySteps extends AdminManagerJoomla3Steps
{
	/**
	 * Function to Add a New Country
	 *
	 * @param   string  $countryName  Name of the Country
	 *
	 * @param   string  $threeCode    Three Code of Country
	 *
	 * @param   string  $twoCode      Two Code of the Country
	 *
	 * @param   string  $country      Country Value
	 *
	 * @return void
	 */
	public function addCountry($countryName, $threeCode, $twoCode, $country)
	{
		$client = $this;
		$client->amOnPage(\CountryPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->click(\CountryPage::$buttonNew);
		$client->waitForElement(\CountryPage::$fieldName, 30);
		$client->checkForPhpNoticesOrWarnings();
		$client->fillField(\CountryPage::$fieldName, $countryName);
		$client->fillField(\CountryPage::$fieldThreeCode, $threeCode);
		$client->fillField(\CountryPage::$fieldTwoCode, $twoCode);
		$client->fillField(\CountryPage::$fieldText, $country);
		$client->click(\CountryPage::$buttonSaveClose);
		$client->waitForText(\CountryPage::$messageItemSaveSuccess, 60, \CountryPage::$selectorSuccess);
		$client->see(\CountryPage::$messageItemSaveSuccess, \CountryPage::$selectorSuccess);
		$client->searchCountry($countryName);
		$client->see($countryName, \CountryPage::$resultRow);
	}

	/**
	 * Function to Update an Already Existing Country
	 *
	 * @param   string  $countryName     Name of the Country
	 *
	 * @param   string  $newCountryName  New Name of the Country
	 *
	 * @return void
	 */
	public function editCountry($countryName, $newCountryName)
	{
		$client = $this;
		$client->amOnPage(\CountryPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->searchCountry($countryName);
		$client->click($countryName);
		$client->waitForElement(\CountryPage::$fieldName, 30);
		$client->checkForPhpNoticesOrWarnings();
		$client->fillField(\CountryPage::$fieldName, $newCountryName);
		$client->click(\CountryPage::$buttonSaveClose);
		$client->waitForText(\CountryPage::$messageItemSaveSuccess, 60, \CountryPage::$selectorSuccess);
		$client->see(\SupplierPage::$messageItemSaveSuccess, \SupplierPage::$selectorSuccess);
	}

	/**
	 * Function to Search for a Country
	 *
	 * @param   string  $countryName   Name of the Country
	 *
	 * @return void
	 */
	public function searchCountry($countryName)
	{
		$client = $this;
		$client->amOnPage(\CountryPage::$url);
		$client->waitForText(\CountryPage::$namePage, 30, \CountryPage::$headPage);
		$client->filterListBySearching($countryName);
	}

	/**
	 * Function to Delete a Country
	 *
	 * @param   String  $countryName  Name of the Country
	 *
	 * @return void
	 */
	public function deleteCountry($countryName)
	{
		$client = $this;
		$client->amOnPage(\CountryPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->searchCountry($countryName);
		$client->checkAllResults();
		$client->click(\CountryPage::$buttonDelete);
		$client->acceptPopup();
		$client->waitForText(\CountryPage::$messageItemDeleteSuccess, 60, \CountryPage::$selectorSuccess);
		$client->see(\CountryPage::$messageItemDeleteSuccess, \CountryPage::$selectorSuccess);
		$client->fillField(\CountryPage::$searchField, $countryName);
		$client->pressKey(\CountryPage::$searchField, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$client->dontSee($countryName, \CountryPage::$resultRow);
	}
}
