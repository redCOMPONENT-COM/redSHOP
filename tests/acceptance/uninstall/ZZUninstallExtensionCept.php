<?php
/**
 * @package     redSHOP
 * @subpackage  Cept
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
/* Name of the File is Kept as ZZUninstallExtension instead of UninstallExtension
   So that this tests is loaded at the last during the test execution */

$I = new AcceptanceTester($scenario);

$I->wantTo('Uninstall redSHOP Extensions');
$I->doAdministratorLogin();
$I->amOnPage('/administrator/index.php?option=com_installer&view=manage');
$I->fillField('#filter_search', 'redSHOP');
$I->click(\ExtensionManagerJoomla3Page::$searchButtonJ3);
$I->waitForElement('#manageList');
$I->click(['link' => 'Location']);
$I->waitForElement('#manageList');
$I->click(['link' => 'Location']);
$I->click(\ExtensionManagerJoomla3Page::$firstCheck);
$I->click("Uninstall");
$I->see('Uninstalling the component was successful', '#system-message-container');

$I->fillField('#filter_search', 'redSHOP');
$I->click(\ExtensionManagerJoomla3Page::$searchButtonJ3);
$I->waitForText('There are no extensions installed matching your query.', 10, '.alert-no-items');
$I->see('There are no extensions installed matching your query.', '.alert-no-items');
