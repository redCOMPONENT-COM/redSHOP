<?php
/**
 * @package    RedCore
 * @subpackage Model
 * @copyright  Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
require_once 'JoomlaWebdriverTestCase.php';

/**
 * This class is to Install Joomla.
 *
 * @package     RedShop2.Test
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
		$cpPage = $this->doAdminLogin();
		$cpPage->clearInstallMessages();
		$gcPage = $cpPage->clickMenu('Global Configuration', 'GlobalConfigurationPage');
		$gcPage->setFieldValue('Cache', 'OFF');
		$gcPage->setFieldValue('Error Reporting', 'Development');
		$gcPage->saveAndClose('ControlPanelPage');
		$extensionInstaller = $cpPage->clickMenu('Extension Manager', 'ExtensionManagerPage');
		$extensionInstaller->installRedCore($this->cfg);
		$extensionInstaller->installRedShop2($this->cfg);
		$extensionInstaller = $cpPage->clickMenu('Extension Manager', 'ExtensionManagerPage');
		$this->assertTrue($extensionInstaller->verifyInstallation('RedSHOP2'), 'Extension Must be Installed');
		$this->doAdminLogout();
	}

	/**
	 * Function to Delete Configuration File
	 *
	 * @return void
	 */
	protected function deleteConfigurationFile()
	{
		$configFile = $this->cfg->folder . $this->cfg->path . "configuration.php";
		if (file_exists($configFile))
		{
			chmod($configFile, 0777);
			unlink($configFile);
		}
	}
}