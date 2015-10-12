<?php
/**
 * @package     redSHOP
 * @subpackage  Cept
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// Before executing this tests configuration.php is removed at tests/_groups/InstallationGroup.php
$scenario->group('installationJ3');

// Load the Step Object Page
$I = new AcceptanceTester($scenario);
$I->wantTo('Execute Joomla Installation');
$I->installJoomlaRemovingInstallationFolder();
$I->doAdministratorLogin();
$I->setErrorReportingtoDevelopment();
