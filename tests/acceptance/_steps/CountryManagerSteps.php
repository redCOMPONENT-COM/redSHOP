<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace AcceptanceTester;
/**
 * Class CountryManagerSteps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class CountryManagerSteps extends \AcceptanceTester
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
		$I->click('New');
		$I->fillField(\CountryManagerPage::$countryName, $countryName);
		$I->fillField(\CountryManagerPage::$countryThreeCode, $threeCode);
		$I->fillField(\CountryManagerPage::$countryTwoCode, $twoCode);
		$I->fillField(\CountryManagerPage::$country, $country);
		$I->click('Save & Close');
		$I->waitForElement(\CountryManagerPage::$countryTitle, 30);
		$I->seeElement(\CountryManagerPage::$countrySuccessMessage);
		$this->sortCountry($I);
		$I->see($countryName, \CountryManagerPage::$countryResultRow);
		$this->sortCountry($I);
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
		$this->sortCountry($I);
		$I->see($countryName, \CountryManagerPage::$countryResultRow);
		$I->click(\CountryManagerPage::$countryCheck);
		$I->click('Edit');
		$I->fillField(\CountryManagerPage::$countryName, $newCountryName);
		$I->click('Save & Close');
		$I->waitForElement(\CountryManagerPage::$countryTitle, 30);
		$I->seeElement(\CountryManagerPage::$countrySuccessMessage);
		$this->sortCountry($I);
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
		$I = $this;
		$I->amOnPage(\CountryManagerPage::$URL);
		$this->sortCountry($I);

		if ($functionName == 'Search')
		{
			$I->see($countryName, \CountryManagerPage::$countryResultRow);
		}
		else
		{
			$I->dontSee($countryName, \CountryManagerPage::$countryResultRow);
		}

		$this->sortCountry($I);
	}

	/**
	 * Function to Sort the Data of Country
	 *
	 * @param   Acceptance  $I  Instance of tester Class
	 *
	 * @return void
	 */
	protected function sortCountry($I)
	{
		$I->click('ID');
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
		$I = $this;
		$I->amOnPage(\CountryManagerPage::$URL);
		$this->sortCountry($I);
		$I->see($countryName, \CountryManagerPage::$countryResultRow);
		$I->click(\CountryManagerPage::$countryCheck);
		$I->click('Delete');
		$I->seeElement(\CountryManagerPage::$countryDeleteSuccessMessage);
		$I->dontSee($countryName, \CountryManagerPage::$countryResultRow);
		$this->sortCountry($I);
	}
}
