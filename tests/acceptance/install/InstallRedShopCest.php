<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class InstallRedShopCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    2.1
 */
use AcceptanceTester\AdminManagerJoomla3Steps as AdminManagerJoomla3Steps;
class InstallRedShopCest
{
	/**
	 * Test to Install Joomla
	 *
	 * @param   AcceptanceTester  $I  Actor Class Object
	 *
	 * @return void
	 */
	public function testInstallJoomla(AcceptanceTester $I)
	{
		$I->wantTo('Execute Joomla Installation');
		$I->installJoomlaRemovingInstallationFolder();
		$I->doAdministratorLogin();
		$I->setErrorReportingtoDevelopment();
	}

	/**
	 * Test to Install redSHOP Extension on Joomla
	 *
	 * @param   AcceptanceTester  $I  Actor Class Object
	 *
	 * @return void
	 */
	public function testInstallRedShopExtension(AcceptanceTester $I)
	{
		$I->wantTo('Install extension');
		$I->doAdministratorLogin();
		$I->disableStatistics();
		$I->wantTo('I Install redSHOP');

		$path = $I->getConfig('redshop packages url') . 'redshop.zip';
		$I->wantToTest('Path for get redshop.zip');
		$I->wantToTest($path);
		$I->comment($path);
		$I->installExtensionFromUrl($I->getConfig('redshop packages url') . 'redshop.zip');

		if ($I->getConfig('install demo data') == 'Yes')
		{
			$I->click("//button[@id='installdemo']");
			$I->waitForText('data installed successful', 10, '#system-message-container');
		}
	}
}
