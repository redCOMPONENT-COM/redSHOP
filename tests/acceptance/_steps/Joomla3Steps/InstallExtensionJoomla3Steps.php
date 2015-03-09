<?php
/**
 * @package     redSHOP
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;

/**
 * Class InstallExtensionJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @since    2.1
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 */
class InstallExtensionJoomla3Steps extends \AcceptanceTester
{
	/**
	 * Function to Install redCORE, inside Joomla 3
	 *
	 * @return void
	 */
	public function installExtension()
	{
		$I = $this;
		$this->acceptanceTester = $I;
		$I->wantTo('install redCORE');
		$I->amOnPage(\ExtensionManagerJoomla3Page::$URL);
		$config = $I->getConfig();
		$I->click('Install from Directory');
		$I->fillField(\ExtensionManagerJoomla3Page::$extensionDirectoryPath, $config['folder']);
		$I->click(\ExtensionManagerJoomla3Page::$installButton);
		$I->waitForText(\ExtensionManagerJoomla3Page::$installSuccessMessage, 60);
		$I->see(\ExtensionManagerJoomla3Page::$installSuccessMessage);
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
			$I->click(\ExtensionManagerJoomla3Page::$installDemoContent);
			$I->waitForText(\ExtensionManagerJoomla3Page::$demoDataInstallSuccessMessage, 30);
		}
	}
}
