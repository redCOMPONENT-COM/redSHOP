<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
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
	public function addUser($userName, $password, $email, $group, $shopperGroup, $firstName, $lastName, $function = 'save')
	{
		$I = $this;
		$I->amOnPage(\UserManagerJoomla3Page::$URL);
		$userManagerPage = new \UserManagerJoomla3Page;
		$I->click(\UserManagerJoomla3Page::$newButton);
		switch ($function) {
			case 'save':
			default:
                $I->executeJS('window.scrollTo(0,0);');
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
				$I->executeJS('window.scrollTo(0,0);');
				$I->click(\UserManagerJoomla3Page::$billingInformationTab);
				$I->waitForElement(\UserManagerJoomla3Page::$firstName, 30);
				$I->fillField(\UserManagerJoomla3Page::$firstName, $firstName);
				$I->fillField(\UserManagerJoomla3Page::$lastName, $lastName);
				$I->click(\UserManagerJoomla3Page::$saveCloseButton);
				$I->waitForText(\UserManagerJoomla3Page::$userSuccessMessage, 60, \UserManagerJoomla3Page::$selectorSuccess);
				$I->see(\UserManagerJoomla3Page::$userSuccessMessage, \UserManagerJoomla3Page::$selectorSuccess);
				$I->executeJS('window.scrollTo(0,0)');
				$I->click(\UserManagerJoomla3Page::$linkUser);
				$I->waitForElement(\UserManagerJoomla3Page::$resetButton, 30);
				$I->click(\UserManagerJoomla3Page::$resetButton);
				$I->see($firstName);
				$I->executeJS('window.scrollTo(0,0)');
				$I->click(\UserManagerJoomla3Page::$linkUser);
				break;
			case 'saveclose':
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
				$I->fillField(\UserManagerJoomla3Page::$address, 'address');
				$I->fillField(\UserManagerJoomla3Page::$city, 'city');
				$I->fillField(\UserManagerJoomla3Page::$zipcode,'5000');
				$I->fillField(\UserManagerJoomla3Page::$phone, '4234324');
				$I->click(\UserManagerJoomla3Page::$saveButton);
				$I->waitForText(\UserManagerJoomla3Page::$userSuccessMessage, 60, \UserManagerJoomla3Page::$selectorSuccess);
				$I->see(\UserManagerJoomla3Page::$userSuccessMessage, \UserManagerJoomla3Page::$selectorSuccess);
				break;
		}
	}


	public function addUserMissing($userName, $password, $email, $group, $shopperGroup, $firstName, $lastName, $function)
	{
		$I = $this;
		$I->amOnPage(\UserManagerJoomla3Page::$URL);
		$userManagerPage = new \UserManagerJoomla3Page;
		$I->click(\UserManagerJoomla3Page::$newButton);

		switch ($function) {
			case 'email':
				//function missing email
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
				break;
			case 'wrongemail':
				//function wrong emai
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
				$I->waitForText(\UserManagerJoomla3Page::$emailInvalid, 60, \UserManagerJoomla3Page::$xPathError);
				$I->see(\UserManagerJoomla3Page::$emailInvalid, \UserManagerJoomla3Page::$xPathError);
				$I->waitForElement(\UserManagerJoomla3Page::$userName, 30);
				break;
			case 'userName':
				//function missing username
				$I->click(\UserManagerJoomla3Page::$generalUserInformationTab);
				$I->waitForElement(\UserManagerJoomla3Page::$userName, 30);
				$I->fillField(\UserManagerJoomla3Page::$userName, '');
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
				break;
			case 'readyUser':
				$I->waitForElement(\UserManagerJoomla3Page::$generalUserInformationTab, 30);
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
				$I->waitForText(\UserManagerJoomla3Page::$saveErrorUserAlready, 60, \UserManagerJoomla3Page::$xPathError);
				$I->see(\UserManagerJoomla3Page::$saveErrorUserAlready, \UserManagerJoomla3Page::$xPathError);
				$I->waitForElement(\UserManagerJoomla3Page::$userName, 30);
				break;
			case 'readyEmail':
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
				$I->see(\UserManagerJoomla3Page::$saveErrorEmailAlready, \UserManagerJoomla3Page::$xPathError);
				break;
			case 'passwordNotMatching':
				//function check passwork matching
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
				break;
			case 'missingShopper':
				//function check missing shopper groups
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
				$I->click(\UserManagerJoomla3Page::$generalUserInformationTab);
				$I->click(\UserManagerJoomla3Page::$saveButton);
				$I->acceptPopup();
				break;
			case 'missingJoomlaGroup':
				//function check  missing Joomla groups
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
				break;
			case 'firstName':
				//function check missing first name
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
				$I->fillField(\UserManagerJoomla3Page::$firstName,'');
				$I->fillField(\UserManagerJoomla3Page::$lastName, $lastName);
				$I->click(\UserManagerJoomla3Page::$saveButton);
				$I->acceptPopup();
				break;
			case 'lastName':
				//function missing last name
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
				$I->fillField(\UserManagerJoomla3Page::$lastName, '');
				$I->click(\UserManagerJoomla3Page::$saveButton);
				$I->acceptPopup();
				break;

		}
	}

	/**
	 * Function to do the validation for different buttons on gift card views
	 *
	 * @param $buttonName
	 *
	 */
	public function checkButtons($buttonName)
	{
		$I = $this;
		$I->amOnPage(\UserManagerJoomla3Page::$URL);
		$I->waitForText(\UserManagerJoomla3Page::$namePage, 30, \UserManagerJoomla3Page::$selectorNamePage);

		switch ($buttonName) {
			case 'cancel':
				$I->click(\UserManagerJoomla3Page::$newButton);
				$I->click(\UserManagerJoomla3Page::$generalUserInformationTab);
				$I->click(\UserManagerJoomla3Page::$cancelButton);
				break;
			case 'edit':
				$I->click(\UserManagerJoomla3Page::$editButton);
				$I->acceptPopup();
				break;
			case 'delete':
				$I->click(\UserManagerJoomla3Page::$deleteButton);
				$I->acceptPopup();
				break;
		}
		$I->see(\UserManagerJoomla3Page::$namePage, \UserManagerJoomla3Page::$selectorNamePage);
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
		$I->waitForText(\UserManagerJoomla3Page::$userSuccessMessage, 60, \UserManagerJoomla3Page::$selectorSuccess);
		$I->see(\UserManagerJoomla3Page::$userSuccessMessage, \UserManagerJoomla3Page::$selectorSuccess);
		$I->see($updatedName);
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

	/**
	 * @param string $firstName
	 * @param $lastName
	 * @param $address
	 * @param $city
	 * @param $phone
	 * @param $zipcode
	 * @throws \Exception
	 * @since 2.1.2
	 */
	public function editAddShipping($firstName, $lastName, $address, $city, $phone, $zipcode)
	{

		$I = $this;
		$I->amOnPage(\UserManagerJoomla3Page::$URL);
		$I->executeJS('window.scrollTo(0,0)');
		$I->searchUser($firstName);
		$I->see($firstName, \UserManagerJoomla3Page::$firstResultRow);

		$I->click(\UserManagerJoomla3Page::$selectFirst);
		$I->click(\UserManagerJoomla3Page::$editButton);
		$I->click(\UserManagerJoomla3Page::$shippingInformation);
		$I->see(\UserManagerJoomla3Page::$pageDetail, \UserManagerJoomla3Page::$pageDetailSelector);
		$I->click(\UserManagerJoomla3Page::$addButton);
		$I->waitForElement(\UserManagerJoomla3Page::$firstName);
		$I->fillField(\UserManagerJoomla3Page::$firstName, $firstName);
		$I->fillField(\UserManagerJoomla3Page::$lastName, $lastName);
		$I->fillField(\UserManagerJoomla3Page::$address, $address);
		$I->fillField(\UserManagerJoomla3Page::$city, $city);
		$I->fillField(\UserManagerJoomla3Page::$phone, $phone);
		$I->fillField(\UserManagerJoomla3Page::$zipcode, $zipcode);
		$I->click(\UserManagerJoomla3Page::$saveCloseButton);
		$I->waitForText(\UserManagerJoomla3Page::$userSuccessMessage, 30);
		$I->see(\UserManagerJoomla3Page::$userSuccessMessage, \UserManagerJoomla3Page::$selectorSuccess);
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
		$I->waitForText(\UserManagerJoomla3Page::$namePage, 30, \UserManagerJoomla3Page::$headPage);
		$I->filterListBySearching($name, $functionName = \UserManagerJoomla3Page::$filter);
	}

	/**
	 * Function to Delete User
	 *
	 * @param   String $name Name of the User which is to be Deleted
	 * @param   Boolean $deleteJoomlaUser Boolean Parameter to decide weather to delete Joomla! user as well
	 * @param $name
	 * @return void
	 */
	/**
	 * @param $name
	 * @param bool $deleteJoomlaUser
	 * @throws \Exception
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

	/**
	 * Function to create oder on User detail
	 *
	 *
	 * @param $nameUser
	 * @param $nameProduct
	 * @param $quantity
	 * @throws \Exception
	 */
	public function checkPlaceOder($nameUser,$nameProduct,$quantity)
	{
		$I = $this;
		$I->amOnPage(\UserManagerJoomla3Page::$URL);
		$I->executeJS('window.scrollTo(0,0)');
		$I->searchUser($nameUser);
		$I->see($nameUser, \UserManagerJoomla3Page::$firstResultRow);
		$I->click(\UserManagerJoomla3Page::$selectFirst);
		$I->click(\UserManagerJoomla3Page::$editButton);
		$I->click(\UserManagerJoomla3Page::$btnPlaceOder);

		$I->see($nameUser);
		$userOrderPage = new \OrderManagerPage();
		$I->waitForElement(\OrderManagerPage::$applyUser, 30);
		$I->executeJS("jQuery('.button-apply').click()");
		$I->waitForElement(\OrderManagerPage::$productId, 30);
		$I->scrollTo(\OrderManagerPage::$productId);
		$I->waitForElement(\OrderManagerPage::$productId, 30);
		$I->click(\OrderManagerPage::$productId);
		$I->waitForElement(\OrderManagerPage::$productsSearch, 30);
		$I->fillField(\OrderManagerPage::$productsSearch, $nameProduct);
		$I->waitForElement($userOrderPage->returnSearch($nameProduct), 30);
		$I->click($userOrderPage->returnSearch($nameProduct));
		$I->fillField(\OrderManagerPage::$quanlityFirst, $quantity);

		$I->click(\OrderManagerPage::$buttonSave);
		$I->waitForElement(\OrderManagerPage::$close, 30);
		$I->waitForText(\OrderManagerPage::$buttonClose, 10, \OrderManagerPage::$close);
	}
}
