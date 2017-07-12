<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;
/**
 * Class UserManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class UserManagerJoomla3Steps extends AdminManagerJoomla3Steps
{
    /**
     * Function To add a new User
     *
     * @param   string $userName UserName of the User
     * @param   string $password Password of the User
     * @param   string $email Email of the User
     * @param   string $group Group of the User
     * @param   string $shopperGroup Group Shopper
     * @param   string $firstName First Name of the User
     * @param   string $lastName Last Name of the User
     *
     * @return void
     */
    public function addUser($userName, $password, $email, $group, $shopperGroup, $firstName, $lastName)
    {
        $I = $this;
        $I->amOnPage(\UserManagerJoomla3Page::$URL);
        $userManagerPage = new \UserManagerJoomla3Page;
        $I->verifyNotices(false, $this->checkForNotices(), \UserManagerJoomla3Page::$pageNotice);
        $I->click(\UserManagerJoomla3Page::$newButton);
        $I->click(\UserManagerJoomla3Page::$generalUserInformationTab);
        $I->waitForElement(\UserManagerJoomla3Page::$userName, 30);
        $I->fillField(\UserManagerJoomla3Page::$userName, $userName);
        $I->fillField(\UserManagerJoomla3Page::$newPassword, $password);
        $I->fillField(\UserManagerJoomla3Page::$confirmNewPassword, $password);
        $I->fillField(\UserManagerJoomla3Page::$email, $email);
        $I->selectOption(\UserManagerJoomla3Page::$groupRadioButton, $group);
        $I->click(\UserManagerJoomla3Page::$shopperGroupDropDown);
        $I->waitForElement($userManagerPage->shopperGroup($shopperGroup), 30);
        $I->click($userManagerPage->shopperGroup($shopperGroup));
        $I->click(\UserManagerJoomla3Page::$billingInformationTab);
        $I->waitForElement(\UserManagerJoomla3Page::$firstName, 30);
        $I->fillField(\UserManagerJoomla3Page::$firstName, $firstName);
        $I->fillField(\UserManagerJoomla3Page::$lastName, $lastName);
        $I->click(\UserManagerJoomla3Page::$saveCloseButton);
        $I->waitForText(\UserManagerJoomla3Page::$userSuccessMessage, 60, \UserManagerJoomla3Page::$selectorSuccess);
        $I->see(\UserManagerJoomla3Page::$userSuccessMessage,\UserManagerJoomla3Page::$selectorSuccess);
        $I->executeJS('window.scrollTo(0,0)');
        $I->click(\UserManagerJoomla3Page::$linkUser);
        $I->see($firstName, \UserManagerJoomla3Page::$firstResultRow);
        $I->executeJS('window.scrollTo(0,0)');
        $I->click(\UserManagerJoomla3Page::$linkUser);
    }

    public function addUserSave($userName, $password, $email, $group, $shopperGroup, $firstName, $lastName)
    {
        $I = $this;
        $I->amOnPage(\UserManagerJoomla3Page::$URL);
        $userManagerPage = new \UserManagerJoomla3Page;
        $I->verifyNotices(false, $this->checkForNotices(), \UserManagerJoomla3Page::$pageNotice);
        $I->click(\UserManagerJoomla3Page::$newButton);
        $I->click(\UserManagerJoomla3Page::$generalUserInformationTab);
        $I->waitForElement(\UserManagerJoomla3Page::$userName, 30);
        $I->fillField(\UserManagerJoomla3Page::$userName, $userName);
        $I->fillField(\UserManagerJoomla3Page::$newPassword, $password);
        $I->fillField(\UserManagerJoomla3Page::$confirmNewPassword, $password);
        $I->fillField(\UserManagerJoomla3Page::$email, $email);
        $I->selectOption(\UserManagerJoomla3Page::$groupRadioButton, $group);
        $I->click(\UserManagerJoomla3Page::$shopperGroupDropDown);
        $I->waitForElement($userManagerPage->shopperGroup($shopperGroup), 30);
        $I->click($userManagerPage->shopperGroup($shopperGroup));
        $I->click(\UserManagerJoomla3Page::$billingInformationTab);
        $I->waitForElement(\UserManagerJoomla3Page::$firstName, 30);
        $I->fillField(\UserManagerJoomla3Page::$firstName, $firstName);
        $I->fillField(\UserManagerJoomla3Page::$lastName, $lastName);
        $I->click(\UserManagerJoomla3Page::$saveButton);
        $I->waitForText(\UserManagerJoomla3Page::$userSuccessMessage, 60,\UserManagerJoomla3Page::$selectorSuccess);
        $I->see(\UserManagerJoomla3Page::$userSuccessMessage,\UserManagerJoomla3Page::$selectorSuccess);
    }

    public function addUserMissingEmailSave($userName, $password, $group, $shopperGroup, $firstName, $lastName)
    {
        $I = $this;
        $I->amOnPage(\UserManagerJoomla3Page::$URL);
        $userManagerPage = new \UserManagerJoomla3Page;
        $I->verifyNotices(false, $this->checkForNotices(),\UserManagerJoomla3Page::$pageNotice);
        $I->click(\UserManagerJoomla3Page::$newButton);
        $I->click(\UserManagerJoomla3Page::$generalUserInformationTab);
        $I->waitForElement(\UserManagerJoomla3Page::$userName, 30);
        $I->fillField(\UserManagerJoomla3Page::$userName, $userName);
        $I->fillField(\UserManagerJoomla3Page::$newPassword, $password);
        $I->fillField(\UserManagerJoomla3Page::$confirmNewPassword, $password);
        $I->selectOption(\UserManagerJoomla3Page::$groupRadioButton, $group);
        $I->click(\UserManagerJoomla3Page::$shopperGroupDropDown);
        $I->waitForElement($userManagerPage->shopperGroup($shopperGroup), 30);
        $I->click($userManagerPage->shopperGroup($shopperGroup));
        $I->click(\UserManagerJoomla3Page::$billingInformationTab);
        $I->waitForElement(\UserManagerJoomla3Page::$firstName, 30);
        $I->fillField(\UserManagerJoomla3Page::$firstName, $firstName);
        $I->fillField(\UserManagerJoomla3Page::$lastName, $lastName);
        $I->click(\UserManagerJoomla3Page::$saveButton);
        $I->acceptPopup();
        $I->waitForElement(\UserManagerJoomla3Page::$userName, 30);
    }

    public function addUserEmailWrongSave($userName, $password, $email, $group, $shopperGroup, $firstName, $lastName)
    {
        $I = $this;
        $I->amOnPage(\UserManagerJoomla3Page::$URL);
        $userManagerPage = new \UserManagerJoomla3Page;
        $I->verifyNotices(false, $this->checkForNotices(),\UserManagerJoomla3Page::$pageNotice);
        $I->click(\UserManagerJoomla3Page::$newButton);
        $I->click(\UserManagerJoomla3Page::$generalUserInformationTab);
        $I->waitForElement(\UserManagerJoomla3Page::$userName, 30);
        $I->fillField(\UserManagerJoomla3Page::$userName, $userName);
        $I->fillField(\UserManagerJoomla3Page::$newPassword, $password);
        $I->fillField(\UserManagerJoomla3Page::$confirmNewPassword, $password);
        $I->fillField(\UserManagerJoomla3Page::$email, $email);
        $I->selectOption(\UserManagerJoomla3Page::$groupRadioButton, $group);
        $I->click(\UserManagerJoomla3Page::$shopperGroupDropDown);
        $I->waitForElement($userManagerPage->shopperGroup($shopperGroup), 30);
        $I->click($userManagerPage->shopperGroup($shopperGroup));
        $I->click(\UserManagerJoomla3Page::$billingInformationTab);
        $I->waitForElement(\UserManagerJoomla3Page::$firstName, 30);
        $I->fillField(\UserManagerJoomla3Page::$firstName, $firstName);
        $I->fillField(\UserManagerJoomla3Page::$lastName, $lastName);
        $I->click(\UserManagerJoomla3Page::$saveButton);
        $I->waitForText(\UserManagerJoomla3Page::$emailInvalid, 60, \UserManagerJoomla3Page::$selectorError);
        $I->see(\UserManagerJoomla3Page::$emailInvalid, \UserManagerJoomla3Page::$selectorError);
        $I->waitForElement(\UserManagerJoomla3Page::$userName, 30);
    }

    public function addUserMissingUserNameSave($password, $email, $group, $shopperGroup, $firstName, $lastName)
    {
        $I = $this;
        $I->amOnPage(\UserManagerJoomla3Page::$URL);
        $userManagerPage = new \UserManagerJoomla3Page;
        $I->verifyNotices(false, $this->checkForNotices(),\UserManagerJoomla3Page::$pageNotice);
        $I->click(\UserManagerJoomla3Page::$newButton);
        $I->click(\UserManagerJoomla3Page::$generalUserInformationTab);
        $I->waitForElement(\UserManagerJoomla3Page::$userName, 30);
        $I->fillField(\UserManagerJoomla3Page::$newPassword, $password);
        $I->fillField(\UserManagerJoomla3Page::$confirmNewPassword, $password);
        $I->fillField(\UserManagerJoomla3Page::$email, $email);
        $I->selectOption(\UserManagerJoomla3Page::$groupRadioButton, $group);
        $I->click(\UserManagerJoomla3Page::$shopperGroupDropDown);
        $I->waitForElement($userManagerPage->shopperGroup($shopperGroup), 30);
        $I->click($userManagerPage->shopperGroup($shopperGroup));
        $I->click(\UserManagerJoomla3Page::$billingInformationTab);
        $I->waitForElement(\UserManagerJoomla3Page::$firstName, 30);
        $I->fillField(\UserManagerJoomla3Page::$firstName, $firstName);
        $I->fillField(\UserManagerJoomla3Page::$lastName, $lastName);
        $I->click(\UserManagerJoomla3Page::$saveButton);
        $I->acceptPopup();
    }

    public function addReadyUserSave($userName, $password, $email, $group, $shopperGroup, $firstName, $lastName)
    {
        $I = $this;
        $I->amOnPage(\UserManagerJoomla3Page::$URL);
        $userManagerPage = new \UserManagerJoomla3Page;
        $I->verifyNotices(false, $this->checkForNotices(),\UserManagerJoomla3Page::$pageNotice);
        $I->click(\UserManagerJoomla3Page::$newButton);
        $I->click(\UserManagerJoomla3Page::$generalUserInformationTab);
        $I->waitForElement(\UserManagerJoomla3Page::$userName, 30);
        $I->fillField(\UserManagerJoomla3Page::$userName, $userName);
        $I->fillField(\UserManagerJoomla3Page::$newPassword, $password);
        $I->fillField(\UserManagerJoomla3Page::$confirmNewPassword, $password);
        $I->fillField(\UserManagerJoomla3Page::$email, $email);
        $I->selectOption(\UserManagerJoomla3Page::$groupRadioButton, $group);
        $I->click(\UserManagerJoomla3Page::$shopperGroupDropDown);
        $I->waitForElement($userManagerPage->shopperGroup($shopperGroup), 30);
        $I->click($userManagerPage->shopperGroup($shopperGroup));
        $I->click(\UserManagerJoomla3Page::$billingInformationTab);
        $I->waitForElement(\UserManagerJoomla3Page::$firstName, 30);
        $I->fillField(\UserManagerJoomla3Page::$firstName, $firstName);
        $I->fillField(\UserManagerJoomla3Page::$lastName, $lastName);
        $I->click(\UserManagerJoomla3Page::$saveButton);
        $I->waitForText(\UserManagerJoomla3Page::$saveErrorUserAlready, 60, \UserManagerJoomla3Page::$selectorError);
        $I->see(\UserManagerJoomla3Page::$saveErrorUserAlready, \UserManagerJoomla3Page::$selectorError);
        $I->waitForElement(\UserManagerJoomla3Page::$userName, 30);
    }

    public function addReadyEmailSave($userName, $password, $email, $group, $shopperGroup, $firstName, $lastName)
    {
        $I = $this;
        $I->amOnPage(\UserManagerJoomla3Page::$URL);
        $userManagerPage = new \UserManagerJoomla3Page;
        $I->verifyNotices(false, $this->checkForNotices(),\UserManagerJoomla3Page::$pageNotice);
        $I->click(\UserManagerJoomla3Page::$newButton);
        $I->click(\UserManagerJoomla3Page::$generalUserInformationTab);
        $I->waitForElement(\UserManagerJoomla3Page::$userName, 30);
        $I->fillField(\UserManagerJoomla3Page::$userName, $userName);
        $I->fillField(\UserManagerJoomla3Page::$newPassword, $password);
        $I->fillField(\UserManagerJoomla3Page::$confirmNewPassword, $password);
        $I->fillField(\UserManagerJoomla3Page::$email, $email);
        $I->selectOption(\UserManagerJoomla3Page::$groupRadioButton, $group);
        $I->click(\UserManagerJoomla3Page::$shopperGroupDropDown);
        $I->waitForElement($userManagerPage->shopperGroup($shopperGroup), 30);
        $I->click($userManagerPage->shopperGroup($shopperGroup));
        $I->click(\UserManagerJoomla3Page::$billingInformationTab);
        $I->waitForElement(\UserManagerJoomla3Page::$firstName, 30);
        $I->fillField(\UserManagerJoomla3Page::$firstName, $firstName);
        $I->fillField(\UserManagerJoomla3Page::$lastName, $lastName);
        $I->click(\UserManagerJoomla3Page::$saveButton);
//        $I->waitForText(\UserManagerJoomla3Page::$saveErrorEmailAlready, 60, \UserManagerJoomla3Page::$selectorError);
        $I->see(\UserManagerJoomla3Page::$saveErrorEmailAlready,\UserManagerJoomla3Page::$selectorError);
    }

    public function addUserWithPasswordNotMatching($userName, $password, $email, $group, $shopperGroup, $firstName, $lastName)
    {
        $I = $this;
        $I->amOnPage(\UserManagerJoomla3Page::$URL);
        $userManagerPage = new \UserManagerJoomla3Page;
        $I->verifyNotices(false, $this->checkForNotices(),\UserManagerJoomla3Page::$pageNotice);
        $I->click(\UserManagerJoomla3Page::$newButton);
        $I->click(\UserManagerJoomla3Page::$generalUserInformationTab);
        $I->waitForElement(\UserManagerJoomla3Page::$userName, 30);
        $I->fillField(\UserManagerJoomla3Page::$userName, $userName);
        $I->fillField(\UserManagerJoomla3Page::$newPassword, $password);
        $I->fillField(\UserManagerJoomla3Page::$confirmNewPassword, "Edit");
        $I->fillField(\UserManagerJoomla3Page::$email, $email);
        $I->selectOption(\UserManagerJoomla3Page::$groupRadioButton, $group);
        $I->click(\UserManagerJoomla3Page::$shopperGroupDropDown);
        $I->waitForElement($userManagerPage->shopperGroup($shopperGroup), 30);
        $I->click($userManagerPage->shopperGroup($shopperGroup));
        $I->click(\UserManagerJoomla3Page::$billingInformationTab);
        $I->waitForElement(\UserManagerJoomla3Page::$firstName, 30);
        $I->fillField(\UserManagerJoomla3Page::$firstName, $firstName);
        $I->fillField(\UserManagerJoomla3Page::$lastName, $lastName);
        $I->click(\UserManagerJoomla3Page::$saveButton);
        $I->acceptPopup();
    }

    public function addUserMissingShopperGroup($userName, $password, $email, $group, $firstName, $lastName)
    {
        $I = $this;
        $I->amOnPage(\UserManagerJoomla3Page::$URL);
        $userManagerPage = new \UserManagerJoomla3Page;
        $I->verifyNotices(false, $this->checkForNotices(),\UserManagerJoomla3Page::$pageNotice);
        $I->click(\UserManagerJoomla3Page::$newButton);
        $I->click(\UserManagerJoomla3Page::$generalUserInformationTab);
        $I->waitForElement(\UserManagerJoomla3Page::$userName, 30);
        $I->fillField(\UserManagerJoomla3Page::$userName, $userName);
        $I->fillField(\UserManagerJoomla3Page::$newPassword, $password);
        $I->fillField(\UserManagerJoomla3Page::$confirmNewPassword, $password);
        $I->fillField(\UserManagerJoomla3Page::$email, $email);
        $I->selectOption(\UserManagerJoomla3Page::$groupRadioButton, $group);
        $I->click(\UserManagerJoomla3Page::$billingInformationTab);
        $I->waitForElement(\UserManagerJoomla3Page::$firstName, 30);
        $I->fillField(\UserManagerJoomla3Page::$firstName, $firstName);
        $I->fillField(\UserManagerJoomla3Page::$lastName, $lastName);
        $I->click(\UserManagerJoomla3Page::$saveButton);
        $I->acceptPopup();
    }

    public function addUserMissingJoomlaGroup($userName, $password, $email, $shopperGroup, $firstName, $lastName)
    {
        $I = $this;
        $I->amOnPage(\UserManagerJoomla3Page::$URL);
        $userManagerPage = new \UserManagerJoomla3Page;
        $I->verifyNotices(false, $this->checkForNotices(),\UserManagerJoomla3Page::$pageNotice);
        $I->click(\UserManagerJoomla3Page::$newButton);
        $I->click(\UserManagerJoomla3Page::$generalUserInformationTab);
        $I->waitForElement(\UserManagerJoomla3Page::$userName, 30);
        $I->fillField(\UserManagerJoomla3Page::$userName, $userName);
        $I->fillField(\UserManagerJoomla3Page::$newPassword, $password);
        $I->fillField(\UserManagerJoomla3Page::$confirmNewPassword, $password . "Edit");
        $I->fillField(\UserManagerJoomla3Page::$email, $email);
        $I->click(\UserManagerJoomla3Page::$shopperGroupDropDown);
        $I->waitForElement($userManagerPage->shopperGroup($shopperGroup), 30);
        $I->click($userManagerPage->shopperGroup($shopperGroup));
        $I->click(\UserManagerJoomla3Page::$billingInformationTab);
        $I->waitForElement(\UserManagerJoomla3Page::$firstName, 30);
        $I->fillField(\UserManagerJoomla3Page::$firstName, $firstName);
        $I->fillField(\UserManagerJoomla3Page::$lastName, $lastName);
        $I->click(\UserManagerJoomla3Page::$saveButton);
        $I->acceptPopup();
    }

    public function addUserMissingFirstName($userName, $password, $email, $group, $shopperGroup, $lastName)
    {
        $I = $this;
        $I->amOnPage(\UserManagerJoomla3Page::$URL);
        $userManagerPage = new \UserManagerJoomla3Page;
        $I->verifyNotices(false, $this->checkForNotices(),\UserManagerJoomla3Page::$pageNotice);
        $I->click(\UserManagerJoomla3Page::$newButton);
        $I->click(\UserManagerJoomla3Page::$generalUserInformationTab);
        $I->waitForElement(\UserManagerJoomla3Page::$userName, 30);
        $I->fillField(\UserManagerJoomla3Page::$userName, $userName);
        $I->fillField(\UserManagerJoomla3Page::$newPassword, $password);
        $I->fillField(\UserManagerJoomla3Page::$confirmNewPassword, $password);
        $I->fillField(\UserManagerJoomla3Page::$email, $email);
        $I->selectOption(\UserManagerJoomla3Page::$groupRadioButton, $group);
        $I->click(\UserManagerJoomla3Page::$shopperGroupDropDown);
        $I->waitForElement($userManagerPage->shopperGroup($shopperGroup), 30);
        $I->click($userManagerPage->shopperGroup($shopperGroup));
        $I->click(\UserManagerJoomla3Page::$billingInformationTab);
        $I->waitForElement(\UserManagerJoomla3Page::$firstName, 30);
        $I->fillField(\UserManagerJoomla3Page::$lastName, $lastName);
        $I->click(\UserManagerJoomla3Page::$saveButton);
        $I->acceptPopup();
    }


    public function addUserMissingLastName($userName, $password, $email, $group, $shopperGroup, $firstName)
    {
        $I = $this;
        $I->amOnPage(\UserManagerJoomla3Page::$URL);
        $userManagerPage = new \UserManagerJoomla3Page;
        $I->verifyNotices(false, $this->checkForNotices(),\UserManagerJoomla3Page::$pageNotice);
        $I->click(\UserManagerJoomla3Page::$newButton);
        $I->click(\UserManagerJoomla3Page::$generalUserInformationTab);
        $I->waitForElement(\UserManagerJoomla3Page::$userName, 30);
        $I->fillField(\UserManagerJoomla3Page::$userName, $userName);
        $I->fillField(\UserManagerJoomla3Page::$newPassword, $password);
        $I->fillField(\UserManagerJoomla3Page::$confirmNewPassword, $password);
        $I->fillField(\UserManagerJoomla3Page::$email, $email);
        $I->selectOption(\UserManagerJoomla3Page::$groupRadioButton, $group);
        $I->click(\UserManagerJoomla3Page::$shopperGroupDropDown);
        $I->waitForElement($userManagerPage->shopperGroup($shopperGroup), 30);
        $I->click($userManagerPage->shopperGroup($shopperGroup));
        $I->click(\UserManagerJoomla3Page::$billingInformationTab);
        $I->waitForElement(\UserManagerJoomla3Page::$firstName, 30);
        $I->fillField(\UserManagerJoomla3Page::$firstName, $firstName);
        $I->click(\UserManagerJoomla3Page::$saveButton);
        $I->acceptPopup();
    }

    public function checkCancelButton()
    {
        $I = $this;
        $I->amOnPage(\UserManagerJoomla3Page::$URL);
        $userManagerPage = new \UserManagerJoomla3Page;
        $I->verifyNotices(false, $this->checkForNotices(),\UserManagerJoomla3Page::$pageNotice);
        $I->click(\UserManagerJoomla3Page::$newButton);
        $I->click(\UserManagerJoomla3Page::$generalUserInformationTab);
        $I->click(\UserManagerJoomla3Page::$cancelButton);
    }

    public function checkEditButton()
    {
        $I = $this;
        $I->amOnPage(\UserManagerJoomla3Page::$URL);
        $userManagerPage = new \UserManagerJoomla3Page;
        $I->verifyNotices(false, $this->checkForNotices(),\UserManagerJoomla3Page::$pageNotice);
        $I->click(\UserManagerJoomla3Page::$editButton);
        $I->acceptPopup();
    }

    public function checkDeleteButton()
    {
        $I = $this;
        $I->amOnPage(\UserManagerJoomla3Page::$URL);
        $userManagerPage = new \UserManagerJoomla3Page;
        $I->verifyNotices(false, $this->checkForNotices(), \UserManagerJoomla3Page::$pageNotice);
        $I->click(\UserManagerJoomla3Page::$deleteButton);
        $I->acceptPopup();
    }

    /**
     * Function to edit an existing User
     *
     * @param   string $firstName First Name of the Current User
     * @param   string $updatedName Updated Name of the User
     *
     * @return void
     */
    public function editUser($firstName = 'Test', $updatedName = 'Updated Name')
    {
        $I = $this;
        $I->amOnPage(\UserManagerJoomla3Page::$URL);
        $I->executeJS('window.scrollTo(0,0)');
        $I->searchUser($firstName);
        $I->see($firstName, \UserManagerJoomla3Page::$firstResultRow);
        $I->click(\UserManagerJoomla3Page::$selectFirst);
        $I->click(\UserManagerJoomla3Page::$editButton);
        $I->click(\UserManagerJoomla3Page::$billingInformationTab);
        $I->waitForElement(\UserManagerJoomla3Page::$firstName);
        $I->fillField(\UserManagerJoomla3Page::$firstName, $updatedName);
        $I->click(\UserManagerJoomla3Page::$generalUserInformationTab);
        $I->click(\UserManagerJoomla3Page::$saveCloseButton);
        $I->waitForText(\UserManagerJoomla3Page::$userSuccessMessage, 60,\UserManagerJoomla3Page::$selectorSuccess);
        $I->see(\UserManagerJoomla3Page::$userSuccessMessage, \UserManagerJoomla3Page::$selectorSuccess);
        $I->see($updatedName, \UserManagerJoomla3Page::$firstResultRow);
        $I->executeJS('window.scrollTo(0,0)');
        $I->click(\UserManagerJoomla3Page::$linkUser);
    }

    public function editUserReady($firstName = 'Test', $updatedName = 'Updated Name')
    {
        $I = $this;
        $I->amOnPage(\UserManagerJoomla3Page::$URL);
        $I->executeJS('window.scrollTo(0,0)');
        $I->searchUser($firstName);
        $I->see($firstName, \UserManagerJoomla3Page::$firstResultRow);
        $I->click(\UserManagerJoomla3Page::$selectFirst);
        $I->click(\UserManagerJoomla3Page::$editButton);
        $I->click(\UserManagerJoomla3Page::$generalUserInformationTab);
        $I->waitForElement(\UserManagerJoomla3Page::$userName);
        $I->fillField(\UserManagerJoomla3Page::$userName, $updatedName);
        $I->click(\UserManagerJoomla3Page::$saveCloseButton);
        $I->waitForText(\UserManagerJoomla3Page::$saveError, 60, \UserManagerJoomla3Page::$errorUserReady);
        $I->see(\UserManagerJoomla3Page::$saveError, \UserManagerJoomla3Page::$errorUserReady);
    }

    public function editAddShipping($firstName = 'Test', $lastName, $address, $city, $phone, $zipcode)
    {

        $I = $this;
        $I->amOnPage(\UserManagerJoomla3Page::$URL);
        $I->executeJS('window.scrollTo(0,0)');
        $I->searchUser($firstName);
        $I->see($firstName, \UserManagerJoomla3Page::$firstResultRow);

        $I->click(\UserManagerJoomla3Page::$selectFirst);
        $I->click(\UserManagerJoomla3Page::$editButton);
        $I->click(\UserManagerJoomla3Page::$shippingInformation);
        $I->see(\UserManagerJoomla3Page::$pageDetail,\UserManagerJoomla3Page::$pageDetailSelector);
        $I->click(\UserManagerJoomla3Page::$addButton);

        $I->amOnPage(\UserManagerJoomla3Page::$URLShipping);
        $I->waitForElement(\UserManagerJoomla3Page::$firstName);
        $I->fillField(\UserManagerJoomla3Page::$firstName, $firstName);
        $I->fillField(\UserManagerJoomla3Page::$lastName, $lastName);
        $I->fillField(\UserManagerJoomla3Page::$address, $address);
        $I->fillField(\UserManagerJoomla3Page::$city, $city);
        $I->fillField(\UserManagerJoomla3Page::$phone, $phone);
        $I->fillField(\UserManagerJoomla3Page::$zipcode, $zipcode);

    }


    public function checkCloseButton($firstName)
    {
        $I = $this;
        $I->amOnPage(\UserManagerJoomla3Page::$URL);
        $I->executeJS('window.scrollTo(0,0)');
        $I->searchUser($firstName);
        $I->see($firstName, \UserManagerJoomla3Page::$firstResultRow);
        $I->click(\UserManagerJoomla3Page::$selectFirst);
        $I->click(\UserManagerJoomla3Page::$editButton);
        $I->click(\UserManagerJoomla3Page::$generalUserInformationTab);
        $I->waitForElement(\UserManagerJoomla3Page::$userName);
        $I->click(\UserManagerJoomla3Page::$generalUserInformationTab);
        $I->click(\UserManagerJoomla3Page::$closeButton);
    }

    /**
     * Function to Search for a User
     *
     * @param   string $name Name of the User
     * @param   string $functionName Name of the function After Which search is being Called
     *
     * @return void
     */
    public function searchUser($name, $functionName = 'filter')
    {
        $I = $this;
        $I->wantTo('Search the User ');
        $I->amOnPage(\UserManagerJoomla3Page::$URL);
        $I->waitForText(\UserManagerJoomla3Page::$namePage, 30,\UserManagerJoomla3Page::$headPage);
        $I->filterListBySearching($name, $functionName = \UserManagerJoomla3Page::$filter);
    }

    /**
     * Function to Delete User
     *
     * @param   String $name Name of the User which is to be Deleted
     * @param   Boolean $deleteJoomlaUser Boolean Parameter to decide weather to delete Joomla! user as well
     *
     * @return void
     */
    public function deleteUser($name, $deleteJoomlaUser = true)
    {
        $I = $this;
        $I->amOnPage(\UserManagerJoomla3Page::$URL);
        $I->executeJS('window.scrollTo(0,0)');
        $I->searchUser($name);
        $I->see($name, \UserManagerJoomla3Page::$firstResultRow);
        $I->click(\UserManagerJoomla3Page::$selectFirst);
        $I->click(\UserManagerJoomla3Page::$deleteButton);

        if ($deleteJoomlaUser) {
            $I->acceptPopup();
        } else {
            $I->cancelPopup();
        }

        $I->dontSee($name, \UserManagerJoomla3Page::$firstResultRow);
        $I->executeJS('window.scrollTo(0,0)');
        $I->click(['link' => 'ID']);
        $I->amOnPage(\UserManagerJoomla3Page::$URLJoomla);
        $I->searchForItem($name);

        if ($deleteJoomlaUser) {
            $I->dontSee($name, \UserManagerJoomla3Page::$userJoomla);
        } else {
            $I->see($name, \UserManagerJoomla3Page::$userJoomla);
        }
    }
}
