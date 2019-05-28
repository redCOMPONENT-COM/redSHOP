<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class InstallRedSHOPPaidExtensionsCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    2.1.2
 */

class InstallRedSHOPPaidExtensionsCest
{

	/**
	 * InstallRedSHOPPaidExtensionsCest constructor.
	 *
	 * @since 2.1.2
	 */
	public function __construct()
	{
		$this->packageModules = 'mod_fb_albums.zip';
		$this->packagePlugin  = 'plg_acymailing_redshop.zip';
		$this->extensionURL   = 'extension url';
		$this->modulesURL     = 'paid-extensions/tests/releases/modules/site/';
		$this->pluginURL      = 'paid-extensions/tests/releases/plugins/';
	}

	/**
	 * Install Extension function
	 * @param   AcceptanceTester $I
	 * @return  void
	 * @since   2.1.2
	 * @throws  \Exception
	 */
	public function installPaidExtensionsModule(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
		$I->amOnPage(AdminJ3Page::$installURL);
		$I->waitForElement(AdminJ3Page::$link, 30);
		$I->click(AdminJ3Page::$link);
		$path = $I->getConfig( $this->extensionURL) .$this->modulesURL.$this->packageModules;
		$I->wantToTest($path);
		$I->comment($path);
		try {
			$I->waitForElementVisible(AdminJ3Page::$urlID, 10);
		} catch (\Exception $e) {
			$I->click(AdminJ3Page::$link);
			$I->waitForElementVisible(AdminJ3Page::$urlID, 10);
		}
		$I->fillField(AdminJ3Page::$urlID, $path);
		$I->waitForElement(AdminJ3Page::$installButton, 30);
		$I->click(AdminJ3Page::$installButton);
		$I->waitForText(AdminJ3Page:: $messageInstallModuleSuccess, 120, AdminJ3Page::$idInstallSuccess);
	}

	/**
	 * Install Extension function
	 * @param   AcceptanceTester $I
	 * @return  void
	 * @since   2.1.2
	 * @throws  \Exception
	 */
	public function installPaidExtensionsPlugin(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
		$I->amOnPage(AdminJ3Page::$installURL);
		$I->waitForElement(AdminJ3Page::$link, 30);
		$I->click(AdminJ3Page::$link);
		$path = $I->getConfig( $this->extensionURL) .$this->pluginURL.$this->packagePlugin;
		$I->wantToTest($path);
		$I->comment($path);
		try {
			$I->waitForElementVisible(AdminJ3Page::$urlID, 10);
		} catch (\Exception $e) {
			$I->click(AdminJ3Page::$link);
			$I->waitForElementVisible(AdminJ3Page::$urlID, 10);
		}
		$I->fillField(AdminJ3Page::$urlID, $path);
		$I->waitForElement(AdminJ3Page::$installButton, 30);
		$I->click(AdminJ3Page::$installButton);
		$I->waitForText(AdminJ3Page::$messageInstallPluginSuccess, 120, AdminJ3Page::$idInstallSuccess);
	}
}