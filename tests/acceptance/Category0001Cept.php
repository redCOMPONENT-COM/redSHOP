<?php
/**
 * @package     RedShop
 * @subpackage  Cept
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
$I = new AcceptanceTester\LoginSteps($scenario);
$I->wantTo('Want to Test Category Manager');
$I->doAdminLogin("Function to Login to Admin Panel");
$I = new AcceptanceTester\CategoryManagerSteps($scenario);
$randomCategoryName = 'Testing Category ' . rand(99, 999);
$updatedCategoryName = 'New ' . $randomCategoryName;
$I->addCategory($randomCategoryName);
$I->verifySearch('true', $I->searchCategory($randomCategoryName));
$I->updateCategory($randomCategoryName, $updatedCategoryName);
$I->verifySearch('true', $I->searchCategory($updatedCategoryName));
$I->changeState($updatedCategoryName, 'unpublish');
$currentState = $I->getState($updatedCategoryName);
$I->verifyState('unpublished', $currentState);
$I->deleteCategory($updatedCategoryName);
$I->verifySearch('false', $I->searchCategory($updatedCategoryName, 'Delete'));

