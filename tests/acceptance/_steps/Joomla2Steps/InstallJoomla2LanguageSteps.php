<?php
/**
 * @package     redSHOP
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;
/**
 * Class InstallJoomla2LanguageSteps
 *
 * @package  AcceptanceTester
 *
 * @since    1.4
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 */
class InstallJoomla2LanguageSteps extends InstallJoomla2Steps
{
	/**
	 * Function which would help us to select the Default Language during the Joomla Installation
	 *
	 * @return void
	 */
	public function selectLanguage()
	{
		$I = $this;
		$this->acceptanceTester = $I;
		$I->amOnPage(\InstallJoomla2ManagerPage::$URL);
		$I->click(\InstallJoomla2ManagerPage::$englishLanguage);
		$I->click('Next');
		$I->waitForText(\InstallJoomla2ManagerPage::$preinstallationCheckPage);
		$I->click('Next');
		$I->waitForText(\InstallJoomla2ManagerPage::$LicenseCheckPage);
		$I->click('Next');
		$I->waitForText(\InstallJoomla2ManagerPage::$databaseConfigurationPage);
	}
}
