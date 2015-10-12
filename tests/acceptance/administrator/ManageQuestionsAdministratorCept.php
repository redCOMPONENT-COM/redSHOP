<?php
/**
 * @package     RedShop
 * @subpackage  Cept
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// Load the Step Object Page
$I = new AcceptanceTester($scenario);
$I->wantTo('Test Questions Manager in Administrator');
$I->doAdministratorLogin();
$className = 'AcceptanceTester\QuestionManagerJoomla3Steps';
$I = new $className($scenario);

$product = 'redSHOEMANIAC';
$question = 'I am facing this Issue ' . rand(10, 100);
$userContactNumber = rand(10000, 1000000);
$updatedQuestion = 'Updated ' . $question;
$I->addQuestion($product, $userContactNumber, $question);
$I->searchQuestion($question);
$I->editQuestion($question, $updatedQuestion);
$I->searchQuestion($updatedQuestion);
$I->changeQuestionState($updatedQuestion);
$I->verifyState('unpublished', $I->getQuestionState($updatedQuestion));
$I->deleteQuestion($updatedQuestion);
$I->searchQuestion($updatedQuestion, 'Delete');
