<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;

/**
 * Class StateSteps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class StateSteps extends AdminManagerJoomla3Steps
{
	/**
	 * Function to Add a State
	 *
	 * @param   string  $countryName  Name of the Country
	 * @param   string  $stateName    State Name
	 * @param   string  $twoCode      Two Code of State
	 * @param   string  $threeCode    Three Code of State
	 *
	 * @return void
	 */
	public function addState($countryName = 'Test Country', $stateName = 'Test State', $twoCode = '12', $threeCode = '123')
	{
		$client = $this;
		$stateManagerPage = new \StatePage;

		$client->amOnPage(\StatePage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->click(\StatePage::$buttonNew);
		$client->waitForElement(\StatePage::$fieldCountryDropdown, 30);
		$client->click(\StatePage::$fieldCountryDropdown);
		$client->fillField(\StatePage::$fieldCountrySearch, $countryName);
		$client->click($stateManagerPage->countryID($countryName));
		$client->fillField(\StatePage::$fieldName, $stateName);
		$client->fillField(\StatePage::$fieldTwoCode, $twoCode);
		$client->fillField(\StatePage::$fieldThreeCode, $threeCode);
		$client->click(\StatePage::$buttonSaveClose);
		$client->waitForText(\StatePage::$messageItemSaveSuccess, 60, \StatePage::$selectorSuccess);
		$client->see(\StatePage::$messageItemSaveSuccess, \StatePage::$selectorSuccess);
		$client->fillField(\StatePage::$searchField, $stateName);
		$client->click(\StatePage::$searchButton);
		$client->see($stateName, \StatePage::$resultRow);
	}

	/**
	 * Function to Edit a State
	 *
	 * @param   string  $stateName     State which we are supposed to Edit
	 * @param   string  $stateNewName  New Name of the State
	 *
	 * @return void
	 */
	public function updateState($stateName = 'Test State', $stateNewName = 'New State Name')
	{
		$client = $this;
		$client->amOnPage(\StatePage::$url);
		$client->searchState($stateName);
		$client->click($stateName);
		$client->waitForElement(\StatePage::$fieldName, 30);
		$client->checkForPhpNoticesOrWarnings();
		$client->fillField(\StatePage::$fieldName, $stateNewName);
		$client->click(\StatePage::$buttonSaveClose);
		$client->waitForText(\StatePage::$messageItemSaveSuccess, 60, \StatePage::$selectorSuccess);
		$client->see(\StatePage::$messageItemSaveSuccess, \StatePage::$selectorSuccess);
	}

	/**
	 * Function to Delete a State
	 *
	 * @param   string  $stateName  Name of the State which is to be Deleted
	 *
	 * @return void
	 */
	public function deleteState($stateName = 'Test State')
	{
		$client = $this;
		$client->amOnPage(\StatePage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->searchState($stateName);
		$client->checkAllResults();
		$client->click(\StatePage::$buttonDelete);
		$client->acceptPopup();
		$client->waitForText(\StatePage::$messageItemDeleteSuccess, 60, \StatePage::$selectorSuccess);
		$client->see(\StatePage::$messageItemDeleteSuccess, \StatePage::$selectorSuccess);
		$client->fillField(\StatePage::$searchField, $stateName);
		$client->pressKey(\StatePage::$searchField, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$client->dontSee($stateName, \StatePage::$resultRow);
	}

	/**
	 * Function to Search a State
	 *
	 * @param   string  $stateName  Name of the State which is to be Deleted
	 *
	 * @return void
	 */
	public function searchState($stateName)
	{
		$client = $this;
		$client->amOnPage(\StatePage::$url);
		$client->waitForText(\StatePage::$namePage, 30, \StatePage::$headPage);
		$client->filterListBySearching($stateName);
	}
}
