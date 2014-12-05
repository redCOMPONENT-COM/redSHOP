<?php
/**
 * @package     RedShop
 * @subpackage  Cept
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

$scenario->group('Joomla2');
// Load the Step Object Page
$I = new AcceptanceTester\LoginSteps($scenario);

$I->wantTo('Test Presence of Notices, Warnings on Administrator');
$I->doAdminLogin();
$I = new AcceptanceTester\AdminManagerSteps($scenario);
$I->CheckAllLinks();
