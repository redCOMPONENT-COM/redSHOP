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
use \Facebook\WebDriver\Remote\RemoteWebDriver;
use AcceptanceTester\AdminManagerJoomla3Steps as AdminManagerJoomla3Steps;
class InstallRedShopCest
{
    /**
     * @var    $faker
     * @since   1.0.0
     */

    protected $faker;
	/**
	 * Test to Install Joomla
	 *
	 * @param   AcceptanceTester  $i  Actor Class Object
	 *
	 * @return void
	 */
	public function testInstallJoomla(\AcceptanceTester $client, $scenario)
	{
        $adminLoginPageUrl = $client->getLocatorPath('adminLoginPageUrl');

        $client->wantTo('Execute Joomla Installation');
        $this->faker = \Faker\Factory::create();
        $client->installJoomlaRemovingInstallationFolder();

        if ($adminLoginPageUrl !== false)
        {
            $client->amOnPage($adminLoginPageUrl);
        }

        $client->doAdministratorLogin();
        $client->wait(2);
        $client->executeInSelenium(
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

        $client->setErrorReportingtoDevelopment();

//        $client->disableStatistics();
        $client->wantTo('I Install redSHOP');
        $client = new AdminManagerJoomla3Steps($scenario);
        $client->installComponent('packages url', 'redshop.zip');
        $client->waitForText('installed successfully', 120, ['id' => 'system-message-container']);

        $client->wantTo('install demo data');
        $client->waitForElement(\AdminJ3Page::$installDemoContent, 30);
        $client->click(\AdminJ3Page::$installDemoContent);
        $client->waitForText('Data Installed Successfully', 120, ['id' => 'system-message-container']);
	}
}
