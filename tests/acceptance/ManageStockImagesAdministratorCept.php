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

$I->wantTo('Test Stock Images Manager in Administrator');
$I->doAdminLogin();
$config = $I->getConfig();
$className = 'AcceptanceTester\StockImageManager' . $config['env'] . 'Steps';
$I = new $className($scenario);
$tip = 'Testing Stock Images' . rand(100, 1000);
$newTip = 'Updated ' . $tip;
$quantity = '100';
$amount = 'Higher than';
$I->addStockImage($tip, $amount, $quantity);
$I->searchStockImage($tip);
$I->editStockImage($tip, $newTip);
$I->searchStockImage($newTip);
$I->deleteStockImage($newTip);
$I->searchStockImage($newTip, 'Delete');
