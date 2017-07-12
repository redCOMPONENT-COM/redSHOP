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
        $this->emailsave = $this->faker->email;
        $this->shopperGroup = 'Default Private';
        $this->group = 'Public';
        $this->firstName = $this->faker->bothify('ManageUserAdministratorCest FN ?##?');
        $this->updateFirstName = 'Updating ' . $this->firstName;
        $this->lastName = 'Last';
        $this->firstNameSave = "FirstName";
        $this->lastNameSave = "LastName";
        $this->emailWrong = "email";
        $this->userNameEdit = "UserNameSave" . $this->faker->randomNumber();
        $this->emailMatching = $this->faker->email;
        $this->userMissing=$this->faker->bothify('ManageUserMissingAdministratorCest ?##?');
    }

    /**
     * Function to Test User Creation in Backend
     *
     */
    public function createUser(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test User creation with save and close button in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\UserManagerJoomla3Steps($scenario);
        $I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperGroup, $this->firstName, $this->lastName);
        $I->searchUser($this->firstName);
    }

    /**
     * Function create user with save button
     *
     * @param AcceptanceTester $I
     * @param $scenario
     *
     */
    public function addUserSave(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test User creation with save button in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\UserManagerJoomla3Steps($scenario);
        $I->addUser($this->userNameEdit, $this->password, $this->emailsave, $this->group, $this->shopperGroup, $this->firstNameSave, $this->lastNameSave);
        $I->searchUser($this->firstNameSave);
    }

    /**
     *
     * add user with missing email
     *
     * @param AcceptanceTester $I
     * @param $scenario
     *
     */
    public function addUserMissingEmailSave(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test User  missing email in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\UserManagerJoomla3Steps($scenario);
        $I->addUserMissingEmailSave($this->userName, $this->password, $this->group, $this->shopperGroup, $this->firstNameSave, $this->lastNameSave);
    }

    /**
     *
     * Create User with wrong email
     *
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function addUserEmailWrongSave(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test User creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\UserManagerJoomla3Steps($scenario);
        $I->addUserEmailWrongSave($this->userMissing, $this->password, $this->emailWrong, $this->group, $this->shopperGroup, $this->firstName, $this->lastName);
    }

    public function addUserMissingUserNameSave(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test User creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\UserManagerJoomla3Steps($scenario);
        $I->addUserMissingUserNameSave($this->password, $this->email, $this->group, $this->shopperGroup, $this->firstName, $this->lastName);
    }

    public function addReadyUserSave(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test User creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\UserManagerJoomla3Steps($scenario);
        $I->addReadyUserSave($this->userNameEdit, $this->password, $this->emailsave, $this->group, $this->shopperGroup, $this->firstNameSave, $this->lastNameSave);
    }

    public function addReadyEmailSave(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test User creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\UserManagerJoomla3Steps($scenario);
        $I->addReadyEmailSave($this->userNameEdit . "editMail", $this->password, $this->emailsave, $this->group, $this->shopperGroup, $this->firstNameSave, $this->lastNameSave);
    }

    public function addUserWithPasswordNotMaching(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test User creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\UserManagerJoomla3Steps($scenario);
        $I->addUserWithPasswordNotMatching($this->userNameEdit . "editMail1", $this->password, $this->emailMatching, $this->group, $this->shopperGroup, $this->firstNameSave, $this->lastNameSave);
    }


    public function addUserMissingShopperGroup(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test User creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\UserManagerJoomla3Steps($scenario);
        $I->addUserMissingShopperGroup($this->userNameEdit . "editMail1", $this->password, $this->emailMatching, $this->group, $this->firstNameSave, $this->lastNameSave);

    }

    public function addUserMissingJoomlaGroup(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test User creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\UserManagerJoomla3Steps($scenario);
        $I->addUserMissingJoomlaGroup($this->userNameEdit . "editMail1", $this->password, $this->emailMatching, $this->shopperGroup, $this->firstNameSave, $this->lastNameSave);
    }

    public function addUserMissingFirstName(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test User creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\UserManagerJoomla3Steps($scenario);
        $I->addUserMissingFirstName($this->userNameEdit, $this->password, $this->emailsave, $this->group, $this->shopperGroup, $this->lastNameSave);
    }

    public function addUserMissingLastName(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test User creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\UserManagerJoomla3Steps($scenario);
        $I->addUserMissingLastName($this->userNameEdit, $this->password, $this->emailsave, $this->group, $this->shopperGroup, $this->firstNameSave);
    }

    public function checkCancelButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test User creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\UserManagerJoomla3Steps($scenario);
        $I->checkCancelButton();
        $I->see(\UserManagerJoomla3Page::$namePage, \UserManagerJoomla3Page::$selectorPageManagement);
    }

    public function checkEditButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test User creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\UserManagerJoomla3Steps($scenario);
        $I->checkEditButton();
        $I->see(\UserManagerJoomla3Page::$namePage, \UserManagerJoomla3Page::$selectorPageManagement);
    }

    public function checkDeleteButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test User creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\UserManagerJoomla3Steps($scenario);
        $I->checkDeleteButton();
        $I->see(\UserManagerJoomla3Page::$namePage, \UserManagerJoomla3Page::$selectorPageManagement);
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
     * Function to Test User Update in the Administrator
     *
     * @depends createUser
     */
    public function updateReadyUserName(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test if User gets updated in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\UserManagerJoomla3Steps($scenario);
        $I->editUserReady($this->updateFirstName, $this->userNameEdit);
    }

    public function checkCloseButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test if User gets updated in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\UserManagerJoomla3Steps($scenario);
        $I->checkCloseButton($this->updateFirstName);
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
        $I->deleteUser($this->updateFirstName, false);
    }


}
