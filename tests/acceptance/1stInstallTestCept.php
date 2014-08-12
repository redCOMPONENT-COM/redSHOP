<?php
/**
 * @package     RedShop
 * @subpackage  Cept
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
$scenario->group('installation');
$I = new AcceptanceTester\InstallJoomla2Steps($scenario);
$I->wantTo('Execute Joomla Installation');
$I->installJoomla2();
$I = new AcceptanceTester\LoginSteps($scenario);
$I->wantTo('Execute Admin Login');
$I->doAdminLogin();
$I = new AcceptanceTester\InstallExtensionSteps($scenario);
$I->wantTo('Install RedShop');
$I->installExtension();
$I->click("//input[@onclick=\"submitWizard('content');\" and @value='install Demo Content']");
$I->waitForElement("//li[contains(text(),'Sample Data Installed Successfully')]", 30);

