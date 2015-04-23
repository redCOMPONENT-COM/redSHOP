<?php
/**
 * @package     RedShop
 * @subpackage  Cept
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
$scenario->group('Joomla3');

// Load the Step Object Page
$I = new AcceptanceTester($scenario);
$config = $I->getConfig();
$className = 'AcceptanceTester\Login' . $config['env'] . 'Steps';
$I = new $className($scenario);

$I->wantTo('Test State Manager in Administrator');
$I->doAdminLogin();
$config = $I->getConfig();
$className = 'AcceptanceTester\CountryManager' . $config['env'] . 'Steps';
$I = new $className($scenario);
$randomCountryName = 'Testing Country ' . rand(99, 999);
$randomStateName = 'Testing State' . rand(99, 999);
$updatedRandomStateName = 'New ' . $randomStateName;
$randomTwoCode = rand(10, 99);
$randomThreeCode = rand(99, 999);
$randomCountry = 'Country ' . rand(99, 999);
$I->addCountry($randomCountryName, $randomThreeCode, $randomTwoCode, $randomCountry);
$I->searchCountry($randomCountryName);
$className = 'AcceptanceTester\StateManager' . $config['env'] . 'Steps';
$I = new $className($scenario);
$I->wantTo('Add a new State');
$I->addState($randomCountry, $randomStateName, $randomTwoCode, $randomThreeCode);
$I->wantTo('Update the new State');
$I->updateState($randomStateName, $updatedRandomStateName);
$I->wantTo('Delete the New State');
$I->deleteState($updatedRandomStateName);
$className = 'AcceptanceTester\CountryManager' . $config['env'] . 'Steps';
$I = new $className($scenario);
$I->deleteCountry($randomCountryName);
$I->searchCountry($randomCountryName, 'Delete');
