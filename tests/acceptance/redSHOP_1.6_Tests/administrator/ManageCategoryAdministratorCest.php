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
        $this->productAccessories = 'redITEM';
    }

    /*
     * Create category and save button
//     */
    public function createCategory(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Category Save creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
        $I->wantTo('Create a Category Save button');
        $I->addCategorySave($this->categoryName);

    }

    public function updateCategorySaveClose(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test if Category gets updated then Save and Close in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
        $I->wantTo('Update Existing Category');
        $I->updateCategory($this->categoryName, $this->newCategoryName);
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
        $I->updateCategorySave($this->newCategoryName, $this->newCategoryName);
    }

    /**
     * Test for State Change in Category Administrator
     *
     * @depends updateCategorySaveClose
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

    }

    /**
     * Check cancel button inside category Page
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function createCategoryCancel(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Category check cancel  in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
        $I->wantTo('Create a Category');
        $I->addCategoryCancel();
        $I->see(\CategoryManagerJ3Page::$pageManageName, \CategoryManagerJ3Page::$categoryManagement);
    }

    /**
     * Create category with child category inside
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function createCategoryChild(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Category is child category is  in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
        $I->wantTo('Create a Category');
        $I->addCategoryChild($this->categoryName, $this->noPage);
    }

    /**
     * Check Delete button without choice Category
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function deleteWithoutCategory(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Category delete without choice  in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
        $I->wantTo('Delete without a Category');
        $I->deleteWithoutChoice();
        $I->see(\CategoryManagerJ3Page::$pageManageName, \CategoryManagerJ3Page::$selectorNamePage);
    }

    /**
     * Delete all Category
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function deleteAllCategory(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Category delete all  in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
        $I->wantTo('Create a Category');
        $I->deleteAllCategory();
        $I->see(\CategoryManagerJ3Page::$pageManageName, \CategoryManagerJ3Page::$selectorNamePage);

    }

    /**
     * Check puplish button without choice any category
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function publishWithouChoice(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Category Publish without choice  in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
        $I->wantTo('Publish without a Category');
        $I->publishWithoutChoice();
        $I->see(\CategoryManagerJ3Page::$pageManageName, \CategoryManagerJ3Page::$selectorNamePage);
    }

    /** depends publishWithouChoice
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function publishAllCategory(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Category Publish all   in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
        $I->wantTo('Publish without a Category');
        $I->publishAllCategory();
        $I->see(\CategoryManagerJ3Page::$pageManageName, \CategoryManagerJ3Page::$selectorNamePage);
    }

    /**
     * Check unpublish button without choice category
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function ụnpublishWithouChoice(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Category Unpublish without choice  in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
        $I->wantTo('Unpublish without a Category');
        $I->unpublishWithoutChoice();
        $I->see(\CategoryManagerJ3Page::$pageManageName, \CategoryManagerJ3Page::$selectorNamePage);
    }

    /**
     * @param AcceptanceTester $I
     * @param $scenario
     * @depends ụnpublishWithouChoice
     *
     */

    public function unpublishAllCategory(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Category Unpublish all categories in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
        $I->wantTo('Unpublish without a Category');
        $I->unpublishAllCategories();
        $I->see(\CategoryManagerJ3Page::$pageManageName, \CategoryManagerJ3Page::$selectorNamePage);
    }

    /**
     * Check checkin button
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function checkinWithoutCategory(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Category check in without choice  in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
        $I->wantTo('Check-in  without a Category');
        $I->checkinWithoutChoice();
        $I->see(\CategoryManagerJ3Page::$pageManageName, \CategoryManagerJ3Page::$selectorNamePage);
    }

    /**
     * @param AcceptanceTester $I
     * @param $scenario
     * @depends checkinWithoutCategory
     */
    public function checkinAllCategory(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Category Unpublish all categories in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
        $I->wantTo('Check-in without a Category');
        $I->checkinAllCategories();
        $I->see(\CategoryManagerJ3Page::$pageManageName, \CategoryManagerJ3Page::$selectorNamePage);
    }

    /** Function to create category with access
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function createCategoryAccessories(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Category creation  Save and Close in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
        $I->wantTo('Create a Category');
        $I->addCategoryAccessories($this->categoryName, $this->noPage, $this->productAccessories);
    }
}
