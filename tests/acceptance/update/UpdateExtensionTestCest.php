<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
use \AcceptanceTester;
/**
 * Class UpdateExtensionTestCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class UpdateExtensionTestCest
{
	/**
	 * Test to Update Joomla
	 *
	 * @param   AcceptanceTester  $I  Actor Class Object
	 *
	 * @return void
	 */
	public function testUpdateJoomla(AcceptanceTester $I)
	{
		$I->wantTo('Test Update Extension');
		$I->doAdministratorLogin();
		$I->wantTo('Install redSHOP from develop branch');
		$I->installExtensionFromFolder($I->getConfig('repo folder') . 'tests/develop/');

		if ($I->getConfig('install demo data') == 'Yes')
		{
			$I->click("//input[@value='Install Demo Content']");
			$I->waitForText('Data Installed Successfully', 10, '#system-message-container');
		}

		$I->installExtensionFromFolder($I->getConfig('repo folder'));
	}
}
