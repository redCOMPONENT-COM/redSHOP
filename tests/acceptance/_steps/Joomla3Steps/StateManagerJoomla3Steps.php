<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace AcceptanceTester;
use Codeception\Module\WebDriver;


/**
 * Class StateManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class StateManagerJoomla3Steps extends AdminManagerJoomla3Steps
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
		$I = $this;
		$stateManagerPage = new \StateManagerJ3Page;
		$I->amOnPage(\StateManagerJ3Page::$URL);
		$I->see('States');
		$I->verifyNotices(false, $this->checkForNotices(), 'States Manager Page');
		$I->click('New');
		$I->verifyNotices(false, $this->checkForNotices(), 'States Manager New');
		$I->click(\StateManagerJ3Page::$countryIdDropDown);
		$I->click($stateManagerPage->countryID($countryName));
		$I->fillField(\StateManagerJ3Page::$stateName, $stateName);
		$I->fillField(\StateManagerJ3Page::$stateTwoCode, $twoCode);
		$I->fillField(\StateManagerJ3Page::$stateThreeCode, $threeCode);
		$I->click("Save & Close");
		$I->see('State detail saved');
		$I->fillField(\StateManagerJ3Page::$searchField, $stateName);
		$I->click(\StateManagerJ3Page::$searchButton);
		$I->see($stateName, \StateManagerJ3Page::$stateResultRow);
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
		$I = $this;
		$I->amOnPage(\StateManagerJ3Page::$URL);
		$I->executeInSelenium(
			function(\WebDriver $webdriver)
			{
				$config = $this->getConfig();
				$webdriver->get($config['host'] . \StateManagerJ3Page::$URL);
				$element = $webdriver->findElement(\WebDriverBy::xpath(\StateManagerJ3Page::$searchField));
				$element->clear();
			}
		);
		$I->fillField(\StateManagerJ3Page::$searchField, $stateName);
		$I->click(\StateManagerJ3Page::$searchButton);
		$I->click(\StateManagerJ3Page::$checkAll);
		$I->click('Edit');
		$I->verifyNotices(false, $this->checkForNotices(), 'States Manager Edit');
		$I->fillField(\StateManagerJ3Page::$stateName, $stateNewName);
		$I->click("Save & Close");
		$I->see('State detail saved');
		$I->amOnPage(\StateManagerJ3Page::$URL);
		$I->executeInSelenium(
			function(\WebDriver $webdriver)
			{
				$config = $this->getConfig();
				$webdriver->get($config['host'] . \StateManagerJ3Page::$URL);
				$element = $webdriver->findElement(\WebDriverBy::xpath(\StateManagerJ3Page::$searchField));
				$element->clear();
			}
		);
		$I->fillField(\StateManagerJ3Page::$searchField, $stateNewName);
		$I->click(\StateManagerJ3Page::$searchButton);
		$I->see($stateNewName, \StateManagerJ3Page::$stateResultRow);
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
		$I = $this;
		$I->amOnPage(\StateManagerJ3Page::$URL);
		$I->executeInSelenium(
			function(\WebDriver $webdriver)
			{
				$config = $this->getConfig();
				$webdriver->get($config['host'] . \StateManagerJ3Page::$URL);
				$element = $webdriver->findElement(\WebDriverBy::xpath(\StateManagerJ3Page::$searchField));
				$element->clear();
			}
		);
		$I->fillField(\StateManagerJ3Page::$searchField, $stateName);
		$I->click(\StateManagerJ3Page::$searchButton);
		$I->see($stateName, \StateManagerJ3Page::$stateResultRow);
		$I->click(\StateManagerJ3Page::$checkAll);
		$I->click('Delete');
		$I->see('State Detail Successfully Deleted');
		$I->amOnPage(\StateManagerJ3Page::$URL);
		$I->click(\StateManagerJ3Page::$searchButton);
		$I->dontSee($stateName, \StateManagerJ3Page::$stateResultRow);
	}
}
