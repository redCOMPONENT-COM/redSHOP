<?php
/**
 * @package     RedShop
 * @subpackage  Cept
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
$scenario->group('Joomla3');

// Load the Step Object Page
$I = new AcceptanceTester\LoginSteps($scenario);

$I->wantTo('Test Text Library Manager in Administrator');
$I->doAdminLogin();
$config = $I->getConfig();
$className = 'AcceptanceTester\TextLibraryManager' . $config['env'] . 'Steps';
$I = new $className($scenario);
$name = 'Testing Text' . rand(9, 99);
$newName = 'Updated' . $name;
$description = 'Sample Descrption' . rand(9, 99);
$section = 'Product';
$I->createText($name, $description, $section);
$I->searchText($name);
$I->editText($name, $newName);
$I->searchText($newName);
$I->changeState($newName);
$I->verifyState('unpublished', $I->getState($newName));
$I->deleteText($newName);
$I->searchText($newName, 'Delete');
