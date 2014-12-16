<?php
/**
 * @package     RedShop
 * @subpackage  Cept
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
$scenario->group('Joomla2');
$scenario->group('Joomla3');
/* Name of the File is Kept as ZZUninstallExtension instead of UninstallExtension
   So that this tests is loaded at the last during the test execution */

// Load the Step Object Page
$I = new AcceptanceTester\LoginSteps($scenario);

$I->wantTo('Uninstall Extension');
$I->doAdminLogin("Function to Login to Admin Panel");
$config = $I->getConfig();

if ($config['env'] == 'joomla2')
{
	$I = new AcceptanceTester\UninstallExtensionSteps($scenario);
	$config = $I->getConfig();
	$I->uninstallExtension($config['extension_name']);
}
else
{
	$I = new AcceptanceTester\UninstallJ3ExtensionSteps($scenario);
	$config = $I->getConfig();
	$I->uninstallExtension($config['extension_name']);
}
