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
        $I->amOnPage('/administrator/index.php?option=com_installer');
        $I->waitForText('Extensions: Install', '30', ['css' => 'H1']);
        $I->click(['link' => 'Install from URL']);

        $path = $I->getConfig('redshop packages url') . 'redshop.zip';
        $I->wantToTest('Path for get redshop.zip');
        $I->wantToTest($path);
        $I->comment($path);

        $I->fillField(['id' => 'install_url'], $path);
        $I->click(['id' => 'installbutton_url']);
        $I->waitForText('installed successfully', '120', ['id' => 'system-message-container']);

        $I->wantTo('install demo data');
        $I->waitForElement(['id' => 'btn-demo-content'], 30);
        $I->click(['id' => 'btn-demo-content']);
        $I->waitForText('Data Installed Successfully', 10, '#system-message-container');
	}
}
