<?php
/**
 * @package     RedShop
 * @subpackage  Cept
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// Load the Step Object Page
$I = new AcceptanceTester($scenario);
$I->wantTo('Test Country Manager in Administrator');
$I->doAdministratorLogin();
$className = 'AcceptanceTester\CountryManagerJoomla3Steps';
$I = new $className($scenario);
$randomCountryName = 'Testing Country ' . rand(99, 999);
$updatedRandomCountryName = 'New ' . $randomCountryName;
$randomTwoCode = rand(10, 99);
$randomThreeCode = rand(99, 999);
$randomCountry = 'Country ' . rand(99, 999);
$I->addCountry($randomCountryName, $randomThreeCode, $randomTwoCode, $randomCountry);
$I->searchCountry($randomCountry);
$I->editCountry($randomCountryName, $updatedRandomCountryName);
$I->searchCountry($updatedRandomCountryName);
$I->deleteCountry($updatedRandomCountryName);
$I->searchCountry($updatedRandomCountryName, 'Delete');

