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
$I->installJoomla2("Install Joomla 2.5.x");
$I = new AcceptanceTester\LoginSteps($scenario);
$I->wantTo('Execute Admin Login');
$I->doAdminLogin("Login to the Admin Panel of Joomla");
$I = new AcceptanceTester\InstallExtensionSteps($scenario);
$I->wantTo('Install RedShop');
$I->installExtension("To Install the Extension");
$I->wantTo('Install redSHOP1 demo data');
$I->installSampleData("Finally Install the Demo Data");

