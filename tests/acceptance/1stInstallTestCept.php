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
$I = new AcceptanceTester\InstallredSHOP1Steps($scenario);
$I->wantTo('Install RedShop');
$I->installredShop1();
