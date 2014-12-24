<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace AcceptanceTester;
/**
 * Class GlobalConfigurationManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class GlobalConfigurationManagerJoomla3Steps extends AdminManagerJoomla3Steps
{
	/**
	 * Function to set error reporting level to Development
	 *
	 * @return void
	 */
	public function setErrorReportingLevel()
	{
		$I = $this;
		$I->amOnPage(\GlobalConfigurationManagerJoomla3Page::$URL);
		$I->waitForText(\GlobalConfigurationManagerJoomla3Page::$pageTitle);
		$I->click(\GlobalConfigurationManagerJoomla3Page::$serverLink);
		$I->waitForElement(\GlobalConfigurationManagerJoomla3Page::$errorReportingDropDown);
		$I->click(\GlobalConfigurationManagerJoomla3Page::$errorReportingDropDown);
		$I->click(\GlobalConfigurationManagerJoomla3Page::$errorReporting);
		$I->click('Save & Close');
		$I->waitForText(\GlobalConfigurationManagerJoomla3Page::$successMessage);
	}
}
