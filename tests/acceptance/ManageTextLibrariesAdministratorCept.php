<?php
/**
 * @package     RedShop
 * @subpackage  Cept
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// Load the Step Object Page
$I = new AcceptanceTester($scenario);
$I->wantTo('Test Text Library Manager in Administrator');
$I->doAdministratorLogin();
$className = 'AcceptanceTester\TextLibraryManagerJoomla3Steps';
$I = new $className($scenario);
$name = 'Testing Text' . rand(9, 99);
$newName = 'Updated' . $name;
$description = 'Sample Description' . rand(9, 99);
$section = 'Product';
$I->createText($name, $description, $section);
$I->searchText($name);
$I->editText($name, $newName);
$I->searchText($newName);
$I->changeTextLibraryState($newName);
$I->verifyState('unpublished', $I->getTextLibraryState($newName));
$I->deleteText($newName);
$I->searchText($newName, 'Delete');
