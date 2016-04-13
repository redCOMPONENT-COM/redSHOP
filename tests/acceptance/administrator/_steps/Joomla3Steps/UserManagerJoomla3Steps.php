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
	 * @param   string  $userName      UserName of the User
	 * @param   string  $password      Password of the User
	 * @param   string  $email         Email of the User
	 * @param   string  $group         Group of the User
	 * @param   string  $shopperGroup  Group Shopper
	 * @param   string  $firstName     First Name of the User
	 * @param   string  $lastName      Last Name of the User
	 *
	 * @return void
	 */
	public function addUser($userName = 'TestUser', $password = 'Password123', $email = 'Testing@testing.com', $group = 'Public', $shopperGroup = 'Default Private', $firstName = 'Test', $lastName = 'Last')
	{
		$I = $this;
		$I->amOnPage(\UserManagerJoomla3Page::$URL);
		$userManagerPage = new \UserManagerJoomla3Page;
		$I->verifyNotices(false, $this->checkForNotices(), 'User Manager Page');
		$I->click('New');
		$I->click(\UserManagerJoomla3Page::$generalUserInformationTab);
		$I->waitForElement(\UserManagerJoomla3Page::$userName,30);
		$I->fillField(\UserManagerJoomla3Page::$userName, $userName);
		$I->fillField(\UserManagerJoomla3Page::$newPassword, $password);
		$I->fillField(\UserManagerJoomla3Page::$confirmNewPassword, $password);
		$I->fillField(\UserManagerJoomla3Page::$email, $email);
		$I->selectOption(\UserManagerJoomla3Page::$groupRadioButton, $group);
		$I->click(\UserManagerJoomla3Page::$shopperGroupDropDown);
		$I->waitForElement($userManagerPage->shopperGroup($shopperGroup),30);
		$I->click($userManagerPage->shopperGroup($shopperGroup));
		$I->click(\UserManagerJoomla3Page::$billingInformationTab);
		$I->waitForElement(\UserManagerJoomla3Page::$firstName,30);
		$I->fillField(\UserManagerJoomla3Page::$firstName, $firstName);
		$I->fillField(\UserManagerJoomla3Page::$lastName, $lastName);
		$I->click('Save & Close');
		$I->waitForText(\UserManagerJoomla3Page::$userSuccessMessage,60,'.alert-success');
		$I->see(\UserManagerJoomla3Page::$userSuccessMessage, '.alert-success');
		$I->executeJS('window.scrollTo(0,0)');
		$I->click(['link' => 'ID']);
		$I->see($firstName, \UserManagerJoomla3Page::$firstResultRow);
		$I->executeJS('window.scrollTo(0,0)');
		$I->click(['link' => 'ID']);
	}

	/**
	 * Function to edit an existing User
	 *
	 * @param   string  $firstName    First Name of the Current User
	 * @param   string  $updatedName  Updated Name of the User
	 *
	 * @return void
	 */
	public function editUser($firstName = 'Test', $updatedName = 'Updated Name')
	{
		$I = $this;
		$I->amOnPage(\UserManagerJoomla3Page::$URL);
		$I->executeJS('window.scrollTo(0,0)');
		$I->click(['link' => 'ID']);
		$I->see($firstName, \UserManagerJoomla3Page::$firstResultRow);
		$I->click(\UserManagerJoomla3Page::$selectFirst);
		$I->click('Edit');
		$I->click(\UserManagerJoomla3Page::$billingInformationTab);
		$I->waitForElement(\UserManagerJoomla3Page::$firstName);
		$I->fillField(\UserManagerJoomla3Page::$firstName, $updatedName);
		$I->click(\UserManagerJoomla3Page::$generalUserInformationTab);
		$I->click('Save & Close');
		$I->waitForText(\UserManagerJoomla3Page::$userSuccessMessage,60,'.alert-success');
		$I->see(\UserManagerJoomla3Page::$userSuccessMessage, '.alert-success');
		$I->see($updatedName, \UserManagerJoomla3Page::$firstResultRow);
		$I->executeJS('window.scrollTo(0,0)');
		$I->click(['link' => 'ID']);
	}

	/**
	 * Function to Search for a User
	 *
	 * @param   string  $name          Name of the User
	 * @param   string  $functionName  Name of the function After Which search is being Called
	 *
	 * @return void
	 */
	public function searchUser($name, $functionName = 'Search')
	{
		$this->search(new \UserManagerJoomla3Page, $name, \UserManagerJoomla3Page::$firstResultRow, $functionName);
	}

	/**
	 * Function to Delete User
	 *
	 * @param   String   $name              Name of the User which is to be Deleted
	 * @param   Boolean  $deleteJoomlaUser  Boolean Parameter to decide weather to delete Joomla! user as well
	 *
	 * @return void
	 */
	public function deleteUser($name, $deleteJoomlaUser = true)
	{
		$I = $this;
		$I->amOnPage(\UserManagerJoomla3Page::$URL);
		$I->executeJS('window.scrollTo(0,0)');
		$I->click(['link' => 'ID']);
		$I->see($name, \UserManagerJoomla3Page::$firstResultRow);
		$I->click(\UserManagerJoomla3Page::$selectFirst);
		$I->click('Delete');

		if ($deleteJoomlaUser)
		{
			$I->acceptPopup();
		}
		else
		{
			$I->cancelPopup();
		}

		$I->dontSee($name, \UserManagerJoomla3Page::$firstResultRow);
		$I->executeJS('window.scrollTo(0,0)');
		$I->click(['link' => 'ID']);
		$I->amOnPage('/administrator/index.php?option=com_users&view=users');
		$I->searchForItem($name);

		if ($deleteJoomlaUser)
		{
			$I->dontSee($name, ['xpath' => "//table[@id='userList']//tbody/tr[1]"]);
		}
		else
		{
			$I->see($name, ['xpath' => "//table[@id='userList']//tbody/tr[1]"]);
		}
	}
}
