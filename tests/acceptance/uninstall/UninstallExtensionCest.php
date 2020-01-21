<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class ZZUninstallExtensionCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class UninstallExtensionCest
{
	/**
	 * Function to Uninstall redSHOP extension
	 *
	 * @return void
	 */
	public function uninstallExtension(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Uninstall redSHOP Extensions');
		$I->doAdministratorLogin();
		$I->amOnPage('/administrator/index.php?option=com_installer&view=manage');
		$I->checkForPhpNoticesOrWarnings();
		$I->waitForElementVisible(\ExtensionManagerJoomla3Page::$searchTools, 30);
		$I->click(\ExtensionManagerJoomla3Page::$searchTools);
		$I->waitForElement('#filter_type', 30);
		$I->selectOptionInChosen('#filter_type', 'Component');
		$I->fillField('#filter_search', 'redSHOP');
		$I->click(\ExtensionManagerJoomla3Page::$searchButtonJ3);
		$I->waitForElement('#manageList');
		$I->click(['link' => 'Location']);
		$I->waitForElement('#manageList');
		$I->click(['link' => 'Location']);
		$I->click(\ExtensionManagerJoomla3Page::$firstCheck);
		$I->click("Uninstall");
		$I->acceptPopup();
		$I->see('Uninstalling the component was successful', '#system-message-container');

		$I->fillField('#filter_search', 'redSHOP');
		$I->click(\ExtensionManagerJoomla3Page::$searchButtonJ3);
		$I->waitForText('There are no extensions installed matching your query.', 10, '.alert-no-items');
		$I->see('There are no extensions installed matching your query.', '.alert-no-items');
		$I->selectOptionInChosen('#filter_type', '- Select Type -');
	}
}
