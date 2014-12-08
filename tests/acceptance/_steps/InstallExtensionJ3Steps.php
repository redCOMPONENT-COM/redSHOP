<?php
/**
 * @package     redSHOP
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;

/**
 * Class InstallExtensionSteps
 *
 * @package  AcceptanceTester
 *
 * @since    2.1
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 */
class InstallExtensionJ3Steps extends \AcceptanceTester
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
		$I->amOnPage(\ExtensionManagerPage::$URL);
		$config = $I->getConfig();
		$I->click('Install from Directory');
		$I->fillField(\ExtensionManagerPage::$extensionDirectoryPath, $config['folder']);
		$I->click(\ExtensionManagerPage::$installButton);
		$I->waitForText(\ExtensionManagerPage::$installSuccessMessage, 60);
		$I->see(\ExtensionManagerPage::$installSuccessMessage);
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
			$I->click(\ExtensionManagerPage::$installDemoContent);
			$I->waitForText(\ExtensionManagerPage::$demoDataInstallSuccessMessage, 30);
		}
	}
}
