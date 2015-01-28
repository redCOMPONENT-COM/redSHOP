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

$I->wantTo('Test Wrapper Manager in Administrator');
$I->doAdminLogin();
$config = $I->getConfig();
$className = 'AcceptanceTester\WrapperManager' . $config['env'] . 'Steps';
$I = new $className($scenario);
$name = 'Sample Wrapper ' . rand(10, 100);
$price = rand(100, 1000);
$category = 'Events and Forms';
$newName = 'Updated ' . $name;
$I->addWrapper($name, $price, $category);
$I->searchWrapper($name);
$I->editWrapper($name, $newName);
$I->searchWrapper($newName);
$I->changeState($newName);
$I->verifyState('unpublished', $I->getState($newName));
$I->deleteWrapper($newName);
$I->searchWrapper($newName, 'Delete');


