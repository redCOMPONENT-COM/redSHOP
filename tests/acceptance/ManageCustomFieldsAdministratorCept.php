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

$I->wantTo('Test Custom Fields Manager in Administrator');
$I->doAdminLogin();
$config = $I->getConfig();
$className = 'AcceptanceTester\CustomFieldManager' . $config['env'] . 'Steps';
$I = new $className($scenario);


$name = 'Testing Field' . rand(100, 1000);
$title = 'Test Title' . rand(10, 100);
$type = 'Text area';
$section = 'Category';
$newTitle = 'Updated ' . $title;
$I->addField($name, $title, $type, $section);
$I->searchField($title);
$I->editField($title, $newTitle);
$I->searchField($newTitle);
$I->changeState($newTitle);
$I->verifyState('unpublished', $I->getState($newTitle));
$I->deleteCustomField($newTitle);
$I->searchField($newTitle, 'Delete');
