<?php
/**
 * @package     RedShop
 * @subpackage  Cept
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// Load the Step Object Page
$I = new AcceptanceTester\LoginSteps($scenario);

$I->wantTo('Want to Test State Manager');
$I->doAdminLogin();
$I = new AcceptanceTester\CountryManagerSteps($scenario);
$randomCountryName = 'Testing Country ' . rand(99, 999);
$randomStateName = 'Testing State' . rand(99, 999);
$updatedRandomStateName = 'New ' . $randomStateName;
$randomTwoCode = rand(10, 99);
$randomThreeCode = rand(99, 999);
$randomCountry = 'Country ' . rand(99, 999);
$I->addCountry($randomCountryName, $randomThreeCode, $randomTwoCode, $randomCountry);
$I->searchCountry($randomCountryName);
$I = new AcceptanceTester\StateManagerSteps($scenario);
$I->wantTo('Add a new State');
$I->addState($randomCountry, $randomStateName, $randomTwoCode, $randomThreeCode);
$I->wantTo('Update the new State');
$I->updateState($randomStateName, $updatedRandomStateName);
$I->wantTo('Delete the New State');
$I->deleteState($updatedRandomStateName);
$I = new AcceptanceTester\CountryManagerSteps($scenario);
$I->deleteCountry($randomCountryName);
$I->searchCountry($randomCountryName, 'Delete');
