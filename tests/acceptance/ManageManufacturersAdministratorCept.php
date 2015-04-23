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

$I->wantTo('Test Manufacturer Manager in Administrator');
$I->doAdminLogin();
$config = $I->getConfig();
$className = 'AcceptanceTester\ManufacturerManager' . $config['env'] . 'Steps';
$I = new $className($scenario);

if ($config['env'] == 'Joomla2')
{
	$I->addManufacturer();
}
else
{
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
}
