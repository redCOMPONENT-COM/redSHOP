<?php
/**
 * @package     RedShop
 * @subpackage  Cept
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// Load the Step Object Page
$I = new AcceptanceTester($scenario);
$I->wantTo('Test Templates Manager in Administrator');
$I->doAdministratorLogin();
$className = 'AcceptanceTester\TemplateManagerJoomla3Steps';
$I = new $className($scenario);
$I = new AcceptanceTester\TemplateManagerJoomla3Steps($scenario);
$name = 'Template' . rand(100, 1000);
$section = 'Product';
$newName = 'Updated' . $name;
$I->addTemplate($name, $section);
$I->searchTemplate($name);
$I->editTemplate($name, $newName);
$I->searchTemplate($newName);
$I->changeTemplateState($newName);
$I->verifyState('unpublished', $I->getTemplateState($newName));
$I->deleteTemplate($newName);
$I->searchTemplate($newName, 'Delete');

