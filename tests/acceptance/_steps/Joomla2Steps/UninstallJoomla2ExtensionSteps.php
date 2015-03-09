<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;
/**
 * Class UninstallJoomla2ExtensionSteps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class UninstallJoomla2ExtensionSteps extends \AcceptanceTester
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
		$I->amOnPage(\ExtensionManagerJoomla2Page::$URL);
		$I->click("Manage");
		$I->fillField(\ExtensionManagerJoomla2Page::$extensionSearchJ2, $extensionName);
		$I->click(\ExtensionManagerJoomla2Page::$searchButtonJ2);
		$I->click(\ExtensionManagerJoomla2Page::$extensionNameLink);
		$name = $I->grabTextFrom(\ExtensionManagerJoomla2Page::$extensionTableJ2);

		while (strtolower($name) != strtolower($extensionName))
		{
			$I->click(\ExtensionManagerJoomla2Page::$firstCheck);
			$I->click("Uninstall");
			$I->seeElement(\ExtensionManagerJoomla2Page::$uninstallSuccessMessageJ2);
			$name = $I->grabTextFrom(\ExtensionManagerJoomla2Page::$extensionTableJ2);
		}

		$I->click(\ExtensionManagerJoomla2Page::$firstCheck);
		$I->click("Uninstall");
		$I->seeElement(\ExtensionManagerJoomla2Page::$uninstallSuccessMessageJ2);
	}
}
