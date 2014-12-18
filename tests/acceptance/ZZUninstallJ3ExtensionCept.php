<?php
/**
 * @package     redSHOP
 * @subpackage  Cept
 * @copyright   Copyright (C) 2012 - 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
/* Name of the File is Kept as ZZUninstallExtension instead of UninstallExtension
   So that this tests is loaded at the last during the test execution */

// Load the Step Object Page
$scenario->group('Joomla3');
$I = new AcceptanceTester\LoginSteps($scenario);

$I->wantTo('Uninstall Extension');
$I->doAdminLogin();

$I = new AcceptanceTester\UninstallJ3ExtensionSteps($scenario);
$config = $I->getConfig();
$I->uninstallExtension($config['extension_name']);
