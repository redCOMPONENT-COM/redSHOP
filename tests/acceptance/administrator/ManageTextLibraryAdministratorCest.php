<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class ManageTextLibraryAdministratorCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class ManageTextLibraryAdministratorCest
{
	public function __construct()
	{
		$this->faker = Faker\Factory::create();
		$this->name = $this->faker->bothify('ManageTextLibraryAdministratorCest ?##?');
		$this->newName = 'Updated' . $this->name;
		$this->description = $this->faker->bothify('ManageTextLibraryAdministratorCest Description ?##?');
		$this->section = 'Product';
	}

	/**
	 * Function to Test Text Library Creation in Backend
	 *
	 */
	public function createTextLibrary(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Text Library creation in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\TextLibraryManagerJoomla3Steps($scenario);
		$I->createText($this->name, $this->description, $this->section);
		$I->searchText($this->name);
	}

	/**
	 * Function to Test Text Library Update in the Administrator
	 *
	 * @depends createTextLibrary
	 */
	public function updateTextLibrary(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test if Text Library gets updated in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\TextLibraryManagerJoomla3Steps($scenario);
		$I->editText($this->name, $this->newName);
		$I->searchText($this->newName);
	}

	/**
	 * Test for State Change in Text Library Administrator
	 *
	 * @depends updateTextLibrary
	 */
	public function changeTextLibraryState(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test if State of a Text Library gets Updated in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\TextLibraryManagerJoomla3Steps($scenario);
		$I->changeTextLibraryState($this->newName);
	}

	/**
	 * Function to Test Text Library Deletion
	 *
	 * @depends changeTextLibraryState
	 */
	public function deleteTextLibrary(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Deletion of Text Library in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\TextLibraryManagerJoomla3Steps($scenario);
		$I->deleteText($this->newName);
	}
}
