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
	 * @param   AcceptanceTester $I Actor Class Object
	 *
	 * @return void
	 * @throws Exception
	 */
	public function testInstallRedShopExtension(AcceptanceTester $I)
	{
		$I->wantTo('Install extension');
		$I->doAdministratorLogin();
		$I->disableStatistics();
		$I->wantTo('Install redSHOP extension');
		$I->amOnPage('/administrator/index.php?option=com_installer');
		$I->waitForText('Extensions: Install', '30', ['css' => 'H1'], null);
		$I->click(['link' => 'Install from URL']);
		$I->fillField(['id' => 'install_url'], $I->getConfig('redshop packages url') . '/redshop.zip');
		$I->click(['id' => 'installbutton_url']);
		$I->waitForText('installed successfully', '120', ['id' => 'system-message-container']);

		if ($I->getConfig('install demo data') == 'Yes')
		{
			$I->click(['id' => 'btn-demo-content']);
			$I->waitForText('Data Installed Successfully', 10, '#system-message-container');
		}
	}
}
