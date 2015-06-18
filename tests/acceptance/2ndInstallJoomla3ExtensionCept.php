<?php
/**
 * @package     redSHOP
 * @subpackage  Cept
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// Before executing this tests configuration.php is removed at tests/_groups/InstallationGroup.php

// Load the Step Object Page
$I = new AcceptanceTester($scenario);
$I->wantTo('Install Extension');
$I->doAdministratorLogin();
$I->wantTo('Install redSHOP');
$I->installExtensionFromDirectory($I->getConfig('repo folder'));

if ($I->getConfig('install demo data') == 'Yes')
{
	$I->click("//input[@value='Install Demo Content']");
	$I->waitForText('Data Installed Successfully', 10, '#system-message-container');
}
