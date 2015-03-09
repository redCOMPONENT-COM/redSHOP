<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;

/**
 * Class InstallExtensionJoomla2Steps
 *
 * @package  AcceptanceTester
 *
 * @since    1.4
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 */
class InstallExtensionJoomla2Steps extends \AcceptanceTester
{
	/**
	 * Function to Install RedShop1, inside Joomla 2.5
	 *
	 * @return void
	 */
	public function installExtension()
	{
		$I = $this;
		$this->acceptanceTester = $I;
		$I->amOnPage(\ExtensionManagerJoomla2Page::$URL);
		$config = $I->getConfig();
		$I->fillField(\ExtensionManagerJoomla2Page::$extensionDirectoryPath, $config['folder']);
		$I->click(\ExtensionManagerJoomla2Page::$installButton);
		$I->waitForText(\ExtensionManagerJoomla2Page::$installSuccessMessage, 60);
	}

	/**
	 * Function to Install Demo Data for the Extension
	 *
	 * @return void
	 */
	public function installSampleData()
	{
		$I = $this;
		$config = $I->getConfig();

		if ($config['install_extension_demo_data'] == 'yes')
		{
			$I->click(\ExtensionManagerJoomla2Page::$installDemoContent);
			$I->waitForText(\ExtensionManagerJoomla2Page::$demoDataInstallSuccessMessage, 30);
		}
	}
}
