<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\AdminManagerJoomla3Steps;

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
	 * @param   AdminManagerJoomla3Steps $I
	 * @return  void
	 * @since   2.1.2
	 * @throws  \Exception
	 */
	public function installPaidExtensionsModule(AdminManagerJoomla3Steps $I)
	{
		$I->doAdministratorLogin();
		$I->installExtensionPackageFromURL($this->extensionURL, $this->modulesURL, $this->packageModules);
		$I->waitForText(AdminJ3Page:: $messageInstallModuleSuccess, 120, AdminJ3Page::$idInstallSuccess);
	}

	/**
	 * Install Extension function
	 * @param   AdminManagerJoomla3Steps $I
	 * @return  void
	 * @since   2.1.2
	 * @throws  \Exception
	 */
	public function installPaidExtensionsPlugin(AdminManagerJoomla3Steps $I)
	{
		$I->doAdministratorLogin();
		$I->installExtensionPackageFromURL($this->extensionURL, $this->pluginURL, $this->packagePlugin);
		$I->waitForText(AdminJ3Page::$messageInstallPluginSuccess, 120, AdminJ3Page::$idInstallSuccess);
	}
}