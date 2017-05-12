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
        $this->newCategoryChild = 'Test Child' . $this->faker->randomNumber();
        $this->noPage = $this->faker->randomNumber();
    }

    /*
     * Create category and save button
     */

    public function createCategorySave(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Category Save creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
        $I->wantTo('Create a Category Save button');
        $I->addCategorySave($this->categoryName);
        $I->see("item successfully saved", '.alert-success');
    }


    /**
     * Function to Test Category Updation in the Administrator
     *
     * @depends createCategory
     */
    public function updateCategorySaveClose(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test if Category gets updated then Save and Close in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
        $I->wantTo('Update Existing Category');
        $I->searchCategory($this->categoryName);
        $I->updateCategory($this->categoryName, $this->newCategoryName);
        //$I->searchCategory($this->newCategoryName);
        //$I->see($this->newCategoryName);
        $I->see("item successfully saved", '.alert-success');
    }


    /**
     * Function to Test Category Updation then Save button in the Administrator
     *
     * @depends createCategory
     */
    public function updateCategorySave(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test if Category gets updated  then Save in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
        $I->wantTo('Update Existing Category');
        $I->searchCategory($this->categoryName);
        $I->updateCategorySave($this->categoryName, $this->newCategoryName);
        $I->see("item successfully saved", '.alert-success');
        //$I->searchCategory($this->newCategoryName);
        //$I->see($this->newCategoryName);
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


    /**
     * Function to Test Category Save Close button  in Backend
     *
     */
    public function createCategorySaveClose(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Category creation  Save and Close in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
        $I->wantTo('Create a Category');
        $I->addCategorySaveClose($this->categoryName);
//        $I->see($this->categoryName);
        $I->see("item successfully saved", '.alert-success');
    }

    /**
     *  Function to create category with Save and New button
     * @param AcceptanceTester $I
     * @param $scenario
     */

    public function createCategorySaveNew(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Category creation Save and New  in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
        $I->wantTo('Create a Category');
        $I->addCategorySaveNew($this->categoryName, $this->noPage);
        $I->see("item successfully saved", '.alert-success');
    }


    public function createCategoryCancel(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Category check cancel  in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
        $I->wantTo('Create a Category');
        $I->addCategoryCancel();
        $I->see("Category Management", CategoryManagerJ3Page::$categoryManagement);
    }


// The function not run yet
//    public function createCategoryChild(AcceptanceTester $I, $scenario)
//    {
//        $I->wantTo('Test Category is child category is  in Administrator');
//        $I->doAdministratorLogin();
//        $I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
//        $I->wantTo('Create a Category');
//        $I->addCategoryChild($this->categoryName, $this->noPage);
//        $I->see("item successfully saved", '.alert-success');
//    }

    public function deleteWithoutCategory(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Category delete without choice  in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
        $I->wantTo('Delete without a Category');
        $I->deleteWithoutChoice();
        $I->see("Category Management", CategoryManagerJ3Page::$categoryManagement);
    }

    public function deleteAllCategory(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Category delete all  in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
        $I->wantTo('Create a Category');
        $I->deleteAllCategory();
        $I->see("Category Management", CategoryManagerJ3Page::$categoryManagement);

    }

    public function publishWithouChoice(AcceptanceTester $I, $scenario){
        $I->wantTo('Test Category Publish without choice  in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
        $I->wantTo('Publish without a Category');
        $I->publishWithoutChoice();
        $I->see("Category Management", CategoryManagerJ3Page::$categoryManagement);
    }

    public function publishAllCategory(AcceptanceTester $I, $scenario){
        $I->wantTo('Test Category Publish all   in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
        $I->wantTo('Publish without a Category');
        $I->publishAllCategory();
        $I->see("Category Management", CategoryManagerJ3Page::$categoryManagement);
    }



}
