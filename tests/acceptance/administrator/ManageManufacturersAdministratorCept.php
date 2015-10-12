<?php
/**
 * @package     RedShop
 * @subpackage  Cept
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// Load the Step Object Page
$I = new AcceptanceTester($scenario);
$I->wantTo('Test Manufacturer Manager in Administrator');
$I->doAdministratorLogin();
$className = 'AcceptanceTester\ManufacturerManagerJoomla3Steps';
$I = new $className($scenario);
$manufacturerName = 'Testing Manufacturer ' . rand(99, 999);
$updatedName = 'Updated ' . $manufacturerName;
$I->addManufacturer($manufacturerName);
$I->searchManufacturer($manufacturerName);
$I->editManufacturer($manufacturerName, $updatedName);
$I->searchManufacturer($updatedName);
$I->changeManufacturerState($updatedName);
$I->verifyState('unpublished', $I->getManufacturerState($updatedName));
$I->deleteManufacturer($updatedName);
$I->searchManufacturer($updatedName, 'Delete');
