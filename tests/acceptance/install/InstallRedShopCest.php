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
	public function testInstallRedShopExtension(AcceptanceTester $I, $scenario)
	{
        $I->wantTo('Install extension');
        $I->doAdministratorLogin();
        $I->disableStatistics();
        $I->wantTo('I Install redSHOP');
        $I = new AdminManagerJoomla3Steps($scenario);
        $I->installComponent('redshop packages url', 'redshop.zip');
        $I->waitForText(\AdminJ3Page::$messageInstallSuccess, 120, \AdminJ3Page::$idInstallSuccess);

        $I->wantTo('install demo data');
        $I->waitForElement(\AdminJ3Page::$installDemoContent, 30);
        $I->click(\AdminJ3Page::$installDemoContent);
        $I->waitForText(\AdminJ3Page::$messageDemoContentSuccess, 10, \AdminJ3Page::$selector);
	}
}
