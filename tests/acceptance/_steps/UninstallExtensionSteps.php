<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;
/**
 * Class UninstallExtensionSteps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class UninstallExtensionSteps extends \AcceptanceTester
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
		$I->amOnPage(\ExtensionManagerPage::$URL);
		$I->click("Manage");
		$I->fillField(\ExtensionManagerPage::$extensionSearch, $extensionName);
		$I->click(\ExtensionManagerPage::$searchButton);
		$I->click(\ExtensionManagerPage::$extensionNameLink);
		$name = $I->grabTextFrom(\ExtensionManagerPage::$extensionTable);

		while (strtolower($name) != strtolower($extensionName))
		{
			$I->click(\ExtensionManagerPage::$firstCheck);
			$I->click("Uninstall");
			$I->seeElement(\ExtensionManagerPage::$uninstallSuccessMessage);
			$name = $I->grabTextFrom(\ExtensionManagerPage::$extensionTable);
		}

		$I->click(\ExtensionManagerPage::$firstCheck);
		$I->click("Uninstall");
		$I->seeElement(\ExtensionManagerPage::$uninstallComponentSuccessMessage);
	}
}
