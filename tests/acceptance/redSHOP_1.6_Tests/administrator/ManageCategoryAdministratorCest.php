<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class ManageCategoryAdministratorCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class ManageCategoryAdministratorCest
{
	public function __construct()
	{
		$this->faker = Faker\Factory::create();
		$this->categoryName = 'Testing Category ' . $this->faker->randomNumber();
		$this->newCategoryName = 'New -' . $this->categoryName;
	}

	/**
	 * Function to Test Category Creation in Backend
	 *
	 */
	public function createCategory(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Category creation in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
		$I->wantTo('Create a Category');
		$I->addCategory($this->categoryName);
		$I->see($this->categoryName);
	}

	/**
	 * Function to Test Category Updation in the Administrator
	 *
	 * @depends createCategory
	 */
	public function updateCategory(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test if Category gets updated in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
		$I->wantTo('Update Existing Category');
		$I->searchCategory($this->categoryName);
		$I->updateCategory($this->categoryName, $this->newCategoryName);
		$I->searchCategory($this->newCategoryName);
		$I->see($this->newCategoryName);
	}

	/**
	 * Test for State Change in Category Administrator
	 *
	 * @depends updateCategory
	 */
	public function changeStateCategory(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test if State of a Category gets Updated in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
		$I->wantTo('Update Existing Category');
		$I->changeCategoryState($this->newCategoryName, 'unpublish');
		$currentState = $I->getCategoryState($this->newCategoryName);
		$I->verifyState('unpublished', $currentState);
		$I->see("1 item successfully unpublished", '.alert-success');
	}

	/**
	 * Function to Test Category Deletion
	 *
	 * @depends changeStateCategory
	 */
	public function deleteCategory(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Deletion of Category in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
		$I->wantTo('Delete a Category');
		$I->deleteCategory($this->newCategoryName);
	}
}
