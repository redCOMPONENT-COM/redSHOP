<?php
/**
 * @package     RedShop
 * @subpackage  Cept
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// Load the Step Object Page
$I = new AcceptanceTester($scenario);
$I->wantTo('Test Wrapper Manager in Administrator');
$I->doAdministratorLogin();
$className = 'AcceptanceTester\WrapperManagerJoomla3Steps';
$I = new $className($scenario);
$name = 'Sample Wrapper ' . rand(10, 100);
$price = rand(100, 1000);
$category = 'Events and Forms';
$newName = 'Updated ' . $name;
$I->addWrapper($name, $price, $category);
$I->searchWrapper($name);
$I->editWrapper($name, $newName);
$I->searchWrapper($newName);
$I->changeWrapperState($newName);
$I->verifyState('unpublished', $I->getWrapperState($newName));
$I->deleteWrapper($newName);
$I->searchWrapper($newName, 'Delete');
