<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace AcceptanceTester;


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
		$I->click('New');
		//$I->verifyNotices(false, $this->checkForNotices(), 'States Manager New');
		$I->click(\StateManagerJ3Page::$countryIdDropDown);
		$I->click('//*[@id="select2-results-1"]/li[2]');
		$I->fillField(\StateManagerJ3Page::$stateName, $stateName);
		$I->fillField(\StateManagerJ3Page::$stateTwoCode, $twoCode);
		$I->fillField(\StateManagerJ3Page::$stateThreeCode, $threeCode);
		$I->click("Save & Close");
		$I->see('Item successfully saved.', ['id' => 'system-message-container']);

		
	}

	/**
	 * Function to Edit a State
	 *
	 * @param   string  $stateName     State which we are supposed to Edit
	 * @param   string  $stateNewName  New Name of the State

	 S
	public function updateState($stateName = 'Test State', $stateNewName = 'New State Name')
	{
		$I = $this;
		$I->amOnPage(\StateManagerJ3Page::$URL);
		$I->filterListBySearching2($stateName);
		$I->click(\StateManagerJ3Page::$checkAll);
		$I->click('Edit');
		//$I->verifyNotices(false, $this->checkForNotices(), 'States Manager Edit');
		$I->fillField(\StateManagerJ3Page::$stateName, $stateNewName);
		$I->click("Save & Close");
		$I->see('Item successfully saved.', ['id' => 'system-message-container']);
		$I->amOnPage(\StateManagerJ3Page::$URL);
		
	}

	/**
	 * Function to Delete a State
	 *
	 * @param   string  $stateName  Name of the State which is to be Deleted
	 *
	 * @return void
	 */
	public function deleteState($stateNewName = 'Test State')
	{
		$I = $this;
		$I->amOnPage(\StateManagerJ3Page::$URL);
		$I->filterListBySearching2($stateNewName);
		$I->see($stateNewName, \StateManagerJ3Page::$stateResultRow);
		$I->click(\StateManagerJ3Page::$checkAll);
		$I->click('Delete');
		$I->see('1 item successfully deleted', '.alert-success');
		
	}
}
