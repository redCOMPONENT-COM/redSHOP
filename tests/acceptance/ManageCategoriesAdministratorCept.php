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

$I->wantTo('Test Category Manager in Administrator');
$I->doAdminLogin();
$I = new AcceptanceTester\CategoryManagerSteps($scenario);
$randomCategoryName = 'Testing Category ' . rand(99, 999);

$I->wantTo('Create a Category');
$I->addCategory($randomCategoryName);
$I->see($randomCategoryName);

$I->wantTo('Rename an existing Category');
$updatedCategoryName = 'New ' . $randomCategoryName;
$I->searchCategory($randomCategoryName);
$I->updateCategory($randomCategoryName, $updatedCategoryName);
$I->searchCategory($updatedCategoryName);
$I->see($updatedCategoryName);

$I->wantTo('Change the status of an existing Category');
$I->changeState($updatedCategoryName, 'unpublish');
$currentState = $I->getState($updatedCategoryName);
$I->verifyState('unpublished', $currentState);

$I->wantTo('Delete an existing Category');
$I->deleteCategory($updatedCategoryName);
$I->searchCategory($updatedCategoryName, 'Delete');
$I->dontSee($updatedCategoryName);

