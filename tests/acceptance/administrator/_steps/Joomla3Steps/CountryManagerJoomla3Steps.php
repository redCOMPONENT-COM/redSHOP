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
class CountryManagerJoomla3Steps extends AdminManagerJoomla3Steps
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
		$I = $this;
		$I->amOnPage(\CountryManagerPage::$URL);
		$I->verifyNotices(false, $this->checkForNotices(), 'Country Manager Page');
		$I->click('New');
		$I->verifyNotices(false, $this->checkForNotices(), 'Country Manager New');
		$I->fillField(\CountryManagerPage::$countryName, $countryName);
		$I->fillField(\CountryManagerPage::$countryThreeCode, $threeCode);
		$I->fillField(\CountryManagerPage::$countryTwoCode, $twoCode);
		$I->fillField(\CountryManagerPage::$country, $country);
		$I->click('Save & Close');
		$I->waitForText('Country Management',10,'h1');
		$I->see('Country detail saved');
		$I->click(['link' => 'ID']);
		$I->see($countryName, \CountryManagerPage::$countryResultRow);
		$I->click(['link' => 'ID']);
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
		$I = $this;
		$I->amOnPage(\CountryManagerPage::$URL);
		$I->click(['link' => 'ID']);
		$I->see($countryName, \CountryManagerPage::$countryResultRow);
		$I->click(\CountryManagerPage::$countryCheck);
		$I->click('Edit');
		$I->verifyNotices(false, $this->checkForNotices(), 'Country Manager Edit View');
		$I->fillField(\CountryManagerPage::$countryName, $newCountryName);
		$I->click('Save & Close');
		$I->waitForText('Country Management',10,'h1');
		$I->see('Country detail saved');
		$I->click(['link' => 'ID']);
	}

	/**
	 * Function to Search for a Country
	 *
	 * @param   string  $countryName   Name of the Country
	 *
	 * @param   string  $functionName  Function Name after which Search is being Called
	 *
	 * @return void
	 */
	public function searchCountry($countryName, $functionName = 'Search')
	{
		$this->search(new \CountryManagerPage, $countryName, \CountryManagerPage::$countryResultRow, $functionName);
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
		$this->delete(new \CountryManagerPage, $countryName, \CountryManagerPage::$countryResultRow, \CountryManagerPage::$countryCheck);
	}
}
