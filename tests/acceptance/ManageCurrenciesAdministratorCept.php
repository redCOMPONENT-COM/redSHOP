<?php
/**
 * @package     RedShop
 * @subpackage  Cept
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
$scenario->group('Joomla2');
$scenario->group('Joomla3');

// Load the Step Object Page
$I = new AcceptanceTester\LoginSteps($scenario);

$I->wantTo('Test Currency Manager in Administrator');
$I->doAdminLogin();
$config = $I->getConfig();
$className = 'AcceptanceTester\CurrencyManager' . $config['env'] . 'Steps';
$I = new $className($scenario);
$randomCurrencyName = 'Testing Currency ' . rand(99, 999);
$updatedCurrencyName = 'New ' . $randomCurrencyName;
$randomCurrencyCode = 'R' . rand(1, 99);
$I->addCurrency($randomCurrencyName, $randomCurrencyCode);
$I->searchCurrency($randomCurrencyName);
$I->editCurrency($randomCurrencyName, $updatedCurrencyName);
$I->searchCurrency($updatedCurrencyName);
$I->deleteCurrency($updatedCurrencyName);
$I->searchCurrency($updatedCurrencyName, 'Delete');

