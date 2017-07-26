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
        $this->emailMissingUser=$this->faker->email;
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
        $this->userMissing = $this->faker->bothify('ManageUserMissingAdministratorCest ?##?');
    }
    public function _before(AcceptanceTester $I)
    {
        $I->doAdministratorLogin();
    }

    /**
     *
     * Function add user with save and save &c lose button
     *
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function addUser(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test User creation with save button in Administrator');
        $I = new AcceptanceTester\UserManagerJoomla3Steps($scenario);
        $I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperGroup, $this->firstName, $this->lastName, 'save');
        $I->addUser($this->userNameEdit, $this->password, $this->emailsave, $this->group, $this->shopperGroup, $this->firstNameSave, $this->lastNameSave, 'saveclose');

    }

    /**
     *
     * Function create user with missing field
     *
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function addUserMissing(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test User creation with save button in Administrator');
        $I = new AcceptanceTester\UserManagerJoomla3Steps($scenario);
        $I->addUserMissing($this->userName, $this->password, $this->email, $this->group, $this->shopperGroup, $this->firstNameSave, $this->lastNameSave, 'email');
        $I->addUserMissing($this->userMissing, $this->password, $this->emailWrong, $this->group, $this->shopperGroup, $this->firstName, $this->lastName, 'wrongemail');
        $I->addUserMissing($this->userMissing, $this->password, $this->emailMissingUser, $this->group, $this->shopperGroup, $this->firstName, $this->lastName, 'userName');
        $I->addUserMissing($this->userNameEdit, $this->password, $this->emailsave, $this->group, $this->shopperGroup, $this->firstNameSave, $this->lastNameSave, 'readyUser');
        $I->addUserMissing($this->userNameEdit . "editMail1", $this->password, $this->emailsave, $this->group, $this->shopperGroup, $this->firstNameSave, $this->lastNameSave, 'readyEmail');
        $I->addUserMissing($this->userNameEdit . "editMail1Test", $this->password, $this->emailMatching, $this->group, $this->shopperGroup, $this->firstNameSave, $this->lastNameSave, 'passwordNotMatching');
        $I->addUserMissing($this->userNameEdit . "editMail1", $this->password, $this->emailMatching, $this->group, $this->shopperGroup, $this->firstNameSave, $this->lastNameSave, 'missingShopper');
        $I->addUserMissing($this->userNameEdit . "editMail1", $this->password, $this->emailMatching, $this->group, $this->shopperGroup, $this->firstNameSave, $this->lastNameSave, 'missingJoomlaGroup');
        $I->addUserMissing($this->userNameEdit . "editMail1", $this->password, $this->emailsave, $this->group, $this->shopperGroup, $this->firstNameSave, $this->lastNameSave, 'firstName');
        $I->addUserMissing($this->userNameEdit . "editMail1", $this->password, $this->emailsave, $this->group, $this->shopperGroup, $this->firstNameSave, $this->lastNameSave, 'lastName');
    }

    /**
     * Function to Test User Update in the Administrator
     *
     * @depends addUser
     */
    public function updateUser(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test if User gets updated in Administrator');
        $I = new AcceptanceTester\UserManagerJoomla3Steps($scenario);
        $I->editUser($this->firstName, $this->updateFirstName);
        $I->searchUser($this->updateFirstName);
    }

    /**
     * Function to Test User Update in the Administrator
     *
     * @depends addUser
     */
    public function updateReadyUserName(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test if User gets updated in Administrator');
        $I = new AcceptanceTester\UserManagerJoomla3Steps($scenario);
        $I->editUserReady($this->updateFirstName, $this->userNameEdit);
    }

    public function checkCloseButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test if User gets updated in Administrator');
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
        $I = new AcceptanceTester\UserManagerJoomla3Steps($scenario);
        $I->deleteUser($this->updateFirstName, false);
    }

    public function checkButtons(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test to validate different buttons on Gift Card Views');
        $I = new AcceptanceTester\UserManagerJoomla3Steps($scenario);
        $I->checkButtons('edit');
        $I->checkButtons('cancel');
        $I->checkButtons('delete');
    }
}
