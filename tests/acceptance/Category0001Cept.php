<?php
/**
 * @package     RedShop
 * @subpackage  Cept
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
$I = new AcceptanceTester\LoginSteps($scenario);
$I->wantTo('Want to Test Category Manager');
$I->doAdminLogin();
$I = new AcceptanceTester\CategoryManagerSteps($scenario);
$name = 'Testing Category ' . rand(99, 999);
$newName = 'New ' . $name;
$I->addCategory($name);
$I->searchCategory($name);
$I->updateCategory($name, $newName);
$I->searchCategory($newName);
$I->changeState($newName, 'unpublish');
$currentState = $I->getState($newName);
$I->verifyState('unpublished', $currentState);
$I->deleteCategory($newName);
$I->searchCategory($newName, 'Delete');

