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

$I->wantTo('Test Stock Rooms Manager in Administrator');
$I->doAdminLogin();
$config = $I->getConfig();
$className = 'AcceptanceTester\StockRoomManager' . $config['env'] . 'Steps';
$I = new $className($scenario);
$name = 'Testing Stock Rooms' . rand(100, 1000);
$newName = 'Updated ' . $name;
$amount = '100';
$I->addStockRoom($name, $amount);
$I->searchStockRoom($name);
$I->editStockRoom($name, $newName);
$I->searchStockRoom($newName);
$I->changeStockRoomState($newName);
$I->verifyState('unpublished', $I->getStockRoomState($newName));
$I->deleteStockRoom($newName);
$I->searchStockRoom($newName, 'Delete');
