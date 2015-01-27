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
$fieldType = array("Check box", "Country selection box", "Date picker", "Documents", "Image", "Image with link", "Multiple select box", "Radio buttons",
	"Selection Based On Selected Conditions", "Single Select", "Text Tag Content", "Text area", "WYSIWYG");
$I->wantTo('Test Custom Fields Manager in Administrator');
$I->doAdminLogin();
$config = $I->getConfig();
$className = 'AcceptanceTester\CustomFieldManager' . $config['env'] . 'Steps';
$I = new $className($scenario);


foreach ($fieldType as $type)
{
	$name = 'Testing Field' . rand(100, 1000);
	$title = 'Test Title' . rand(10, 100);
	$optionValue = 'Testing Options ' . rand(100, 1000);
	$section = 'Category';
	$newTitle = 'Updated ' . $title;
	$I->addField($name, $title, $type, $section, $optionValue);
	$I->searchField($title);
	$I->editField($title, $newTitle);
	$I->searchField($newTitle);
	$I->changeState($newTitle);
	$I->verifyState('unpublished', $I->getState($newTitle));
	$I->deleteCustomField($newTitle);
	$I->searchField($newTitle, 'Delete');
}
