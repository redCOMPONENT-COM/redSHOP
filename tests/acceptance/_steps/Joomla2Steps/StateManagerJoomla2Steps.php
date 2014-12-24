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
 * Class StateManagerJoomla2Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class StateManagerJoomla2Steps extends AdminManagerJoomla2Steps
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
		$I->amOnPage(\StateManagerPage::$URL);
		$I->see('States');
		$I->verifyNotices(false, $this->checkForNotices(), 'States Manager Page');
		$I->click('New');
		$I->verifyNotices(false, $this->checkForNotices(), 'States Manager New');
		$I->selectOption(\StateManagerPage::$countryId, $countryName);
		$I->fillField(\StateManagerPage::$stateName, $stateName);
		$I->fillField(\StateManagerPage::$stateTwoCode, $twoCode);
		$I->fillField(\StateManagerPage::$stateThreeCode, $threeCode);
		$I->click("Save & Close");
		$I->see('State detail saved');
		$I->fillField(\StateManagerPage::$searchField, $stateName);
		$I->click(\StateManagerPage::$searchButton);
		$I->see($stateName, \StateManagerPage::$stateResultRow);
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
		$I->amOnPage(\StateManagerPage::$URL);
		$config = $I->getConfig();
		$I->executeInSelenium(
			function(\WebDriver $webdriver)
			{
				$config = $this->getConfig();
				$webdriver->get($config['host'] . \StateManagerPage::$URL);
				$element = $webdriver->findElement(\WebDriverBy::xpath(\StateManagerPage::$searchField));
				$element->clear();
			}
		);
		$I->fillField(\StateManagerPage::$searchField, $stateName);
		$I->click(\StateManagerPage::$searchButton);
		$I->click(\StateManagerPage::$checkAll);
		$I->click('Edit');
		$I->verifyNotices(false, $this->checkForNotices(), 'States Manager Edit');
		$I->fillField(\StateManagerPage::$stateName, $stateNewName);
		$I->click("Save & Close");
		$I->see('State detail saved');
		$I->amOnPage(\StateManagerPage::$URL);
		$I->executeInSelenium(
			function(\WebDriver $webdriver)
			{
				$config = $this->getConfig();
				$webdriver->get($config['host'] . \StateManagerPage::$URL);
				$element = $webdriver->findElement(\WebDriverBy::xpath(\StateManagerPage::$searchField));
				$element->clear();
			}
		);
		$I->fillField(\StateManagerPage::$searchField, $stateNewName);
		$I->click(\StateManagerPage::$searchButton);
		$I->see($stateNewName, \StateManagerPage::$stateResultRow);
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
		$I->amOnPage(\StateManagerPage::$URL);
		$I->executeInSelenium(
			function(\WebDriver $webdriver)
			{
				$config = $this->getConfig();
				$webdriver->get($config['host'] . \StateManagerPage::$URL);
				$element = $webdriver->findElement(\WebDriverBy::xpath(\StateManagerPage::$searchField));
				$element->clear();
			}
		);
		$I->fillField(\StateManagerPage::$searchField, $stateName);
		$I->click(\StateManagerPage::$searchButton);
		$I->see($stateName, \StateManagerPage::$stateResultRow);
		$I->click(\StateManagerPage::$checkAll);
		$I->click('Delete');
		$I->see('State Detail Successfully Deleted');
		$I->amOnPage(\StateManagerPage::$URL);
		$I->click(\StateManagerPage::$searchButton);
		$I->dontSee($stateName, \StateManagerPage::$stateResultRow);
	}
}
