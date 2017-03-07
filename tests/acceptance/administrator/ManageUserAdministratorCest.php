<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class ManageUserAdministratorCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class ManageUserAdministratorCest
{
	public function __construct()
	{
		$this->faker = Faker\Factory::create();
		$this->userName = $this->faker->bothify('ManageUserAdministratorCest ?##?');
		$this->password = $this->faker->bothify('Password ?##?');
		$this->email = $this->faker->email;
		$this->shopperGroup = 'Default Private';
		$this->group = 'Public';
		$this->firstName = $this->faker->bothify('ManageUserAdministratorCest FN ?##?');
		$this->updateFirstName = 'Updating ' . $this->firstName;
		$this->lastName = 'Last';
	}

	/**
	 * Function to Test User Creation in Backend
	 *
	 */
	public function createUser(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test User creation in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\UserManagerJoomla3Steps($scenario);
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperGroup, $this->firstName, $this->lastName);
		$I->searchUser($this->firstName);
	}

	/**
	 * Function to Test User Update in the Administrator
	 *
	 * @depends createUser
	 */
	public function updateUser(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test if User gets updated in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\UserManagerJoomla3Steps($scenario);
		$I->editUser($this->firstName, $this->updateFirstName);
		$I->searchUser($this->updateFirstName);
	}

	/**
	 * Function to Test User Deletion
	 *
	 * @depends updateUser
	 */
	public function deleteUser(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Deletion of User in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->updateFirstName);
		$I->searchUser($this->updateFirstName, 'Delete');
	}
}
