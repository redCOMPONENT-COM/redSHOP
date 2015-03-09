<?php
/**
 * @package     redSHOP
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;
/**
 * Class UninstallJoomla3ExtensionSteps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class UninstallJoomla3ExtensionSteps extends \AcceptanceTester
{
	/**
	 * Function to Uninstall Extension
	 *
	 * @param   String  $extensionName  Name of the Extension
	 *
	 * @return void
	 */
	public function uninstallExtension($extensionName)
	{
		$I = $this;
		$I->amOnPage(\ExtensionManagerJoomla3Page::$URL);
		$I->click("Manage");
		$I->fillField(\ExtensionManagerJoomla3Page::$extensionSearchJ3, $extensionName);
		$I->click(\ExtensionManagerJoomla3Page::$searchButtonJ3);
		$I->click(\ExtensionManagerJoomla3Page::$extensionNameLink);
		$name = $I->grabTextFrom(\ExtensionManagerJoomla3Page::$extensionTable);

		while (strtolower($name) != strtolower($extensionName))
		{
			$I->click(\ExtensionManagerJoomla3Page::$firstCheck);
			$I->click("Uninstall");
			$I->seeElement(\ExtensionManagerJoomla3Page::$uninstallSuccessMessageJ3);
			$name = $I->grabTextFrom(\ExtensionManagerJoomla3Page::$extensionTable);
		}

		$I->click(\ExtensionManagerJoomla3Page::$firstCheck);
		$I->click("Uninstall");
		$I->seeElement(\ExtensionManagerJoomla3Page::$uninstallSuccessMessageJ3);
	}
}
