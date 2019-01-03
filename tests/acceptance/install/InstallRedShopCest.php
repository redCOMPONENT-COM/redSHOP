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
use \Facebook\WebDriver\Remote\RemoteWebDriver;
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
        $I->wait(2);
        $I->executeInSelenium(
            function (RemoteWebDriver $webdriver) {
                if (count($webdriver->findElements(\WebDriverBy::xpath("//a[contains(text(), 'PLG_SYSTEM_STATS_BTN_NEVER_SEND')]"))) > 0)
                {
                    $webdriver->findElement(\WebDriverBy::xpath("//a[contains(text(), 'PLG_SYSTEM_STATS_BTN_NEVER_SEND')]"))->click();
                }
                else
                {
                    $webdriver->findElement(\WebDriverBy::xpath("//div[contains(@class, 'alert-info')]//a[contains(text(), 'Never')]"))->click();
                }
            }
        );
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
        $I->installComponent('packages url', 'redshop.zip');
        $I->waitForText('installed successfully', 120, ['id' => 'system-message-container']);

        $I->wantTo('install demo data');
        $I->waitForElement(\AdminJ3Page::$installDemoContent, 30);
        $I->click(\AdminJ3Page::$installDemoContent);
        $I->waitForText('Data Installed Successfully', 120, ['id' => 'system-message-container']);
	}
}
