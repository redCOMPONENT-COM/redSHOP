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

$I->wantTo('Test Questions Manager in Administrator');
$I->doAdminLogin();
$config = $I->getConfig();
$className = 'AcceptanceTester\QuestionManager' . $config['env'] . 'Steps';
$I = new $className($scenario);

$product = 'redTWITTER';
$question = 'I am facing this Issue ' . rand(10, 100);
$userContactNumber = rand(10000, 1000000);
$updatedQuestion = 'Updated ' . $question;
$I->addQuestion($product, $userContactNumber, $question);
$I->searchQuestion($question);
$I->editQuestion($question, $updatedQuestion);
$I->searchQuestion($updatedQuestion);
$I->changeState($updatedQuestion);
$I->verifyState('unpublished', $I->getState($updatedQuestion));
$I->deleteQuestion($updatedQuestion);
$I->searchQuestion($updatedQuestion, 'Delete');
