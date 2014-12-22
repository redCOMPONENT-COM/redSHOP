<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace AcceptanceTester;
/**
 * Class GlobalConfigurationManagerJoomla2Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class GlobalConfigurationManagerJoomla2Steps extends AdminManagerJoomla2Steps
{
	/**
	 * Function to set error reporting level to Development
	 *
	 * @param   string  $level  Level for the error reporting
	 * 
	 * @return void
	 */
	public function setErrorReportingLevel($level = 'Development')
	{
		$I = $this;
		$I->amOnPage(\GlobalConfigurationJ2ManagerPage::$URL);
		$I->waitForText(\GlobalConfigurationJ2ManagerPage::$pageTitle);
		$I->click('Server');
		$I->waitForElement(\GlobalConfigurationJ2ManagerPage::$errorReporting);
		$I->selectOption(\GlobalConfigurationJ2ManagerPage::$errorReporting, $level);
		$I->click('Save & Close');
		$I->waitForText(\GlobalConfigurationJ2ManagerPage::$successMessage);
	}
}
