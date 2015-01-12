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

$I->wantTo('Test Templates Manager in Administrator');
$I->doAdminLogin();
$config = $I->getConfig();
$className = 'AcceptanceTester\TemplateManager' . $config['env'] . 'Steps';
$I = new $className($scenario);
$I = new AcceptanceTester\TemplateManagerJoomla3Steps($scenario);
$name = 'Template' . rand(100, 1000);
$section = 'Product';
$newName = 'Updated' . $name;
$I->addTemplate($name, $section);
$I->searchTemplate($name);
$I->editTemplate($name, $newName);
$I->searchTemplate($newName);
$I->changeState($newName);
$I->verifyState('unpublished', $I->getState($newName));
$I->deleteTemplate($newName);
$I->searchTemplate($newName, 'Delete');

