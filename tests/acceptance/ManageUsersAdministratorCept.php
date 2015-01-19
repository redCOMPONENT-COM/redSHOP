<?php
/**
 * @package     RedShop
 * @subpackage  Cept
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
$scenario->group('Joomla3');

// Load the Step Object Page
$I = new AcceptanceTester\LoginSteps($scenario);

$I->wantTo('Test Users Manager in Administrator');
$I->doAdminLogin();
$config = $I->getConfig();
$className = 'AcceptanceTester\UserManager' . $config['env'] . 'Steps';
$I = new $className($scenario);
$I = new AcceptanceTester\UserManagerJoomla3Steps($scenario);
$userName = 'Testing' . rand(9, 999);
$password = 'password' . rand(9, 999);
$email = 'email' . rand(9, 99) . '@email.com';
$shopperGroup = 'Default Private';
$group = 'Public';
$firstName = 'Test' . rand(99, 999);
$updateFirstName = 'Updating ' . $firstName;
$lastName = 'Last';
$I->addUser($userName, $password, $email, $group, $shopperGroup, $firstName, $lastName);
$I->searchUser($firstName);
$I->editUser($firstName, $updateFirstName);
$I->searchUser($updateFirstName);
$I->deleteUser($updateFirstName);
$I->searchUser($updateFirstName, 'Delete');
