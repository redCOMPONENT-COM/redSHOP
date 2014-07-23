<?php
/**
 * @package     RedCore
 * @subpackage  Model
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
require_once 'JoomlaWebdriverTestCase.php';

/**
 * This class is to Install Joomla.
 *
 * @package     RedShop.Test
 * @subpackage  Webdriver
 * @since       2.0
 */
class InstallTest extends JoomlaWebdriverTestCase
{
	/**
	 * Function to install Joomla
	 *
	 * @test
	 *
	 * @return void
	 */
	public function install_NormalInstallFromConfig_ShouldInstall()
	{
		if ($this->cfg->doInstall == 'true')
		{
			$this->deleteConfigurationFile();
			$url = $this->cfg->host . $this->cfg->path . 'installation/';
			$installPage = $this->getPageObject('InstallationPage', true, $url);
			$installPage->install($this->cfg);
		}
		echo "Verification 1";
		$cpPage = $this->doAdminLogin();
		$extensionInstaller = $cpPage->clickMenu('Extension Manager', 'ExtensionManagerPage');
		$extensionInstaller->installRedShop($this->cfg, 'Sample Data');
		$this->driver->get($this->cfg->host . $this->cfg->path . 'administrator/');
		$cpPage = $this->getPageObject('GenericAdminPage');
		$extensionInstaller = $cpPage->clickMenu('Extension Manager', 'ExtensionManagerPage');
		$this->assertTrue($extensionInstaller->verifyInstallation($this->cfg, 'redSHOP'), 'Extension Must be Installed');
		$this->doAdminLogout();
	}

	/**
	 * Function to Delete Configuration File
	 *
	 * @return void
	 */
	protected function deleteConfigurationFile()
	{
		$configFile = $this->cfg->server . $this->cfg->path . "configuration.php";

		if (file_exists($configFile))
		{
			chmod($configFile, 0777);
			unlink($configFile);
		}
	}
}
