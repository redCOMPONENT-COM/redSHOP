<?php
/**
 * @package     redSHOP
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;

/**
 * Class PayPalPluginManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @since    1.5
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 */
class PayPalPluginManagerJoomla3Steps extends AdminManagerJoomla3Steps
{
	/**
	 * Function to Enable a Payment Plugin
	 *
	 * @param   String  $pluginName  Name of the Plugin
	 *
	 * @return void
	 */
	public function enablePlugin($pluginName)
	{
		$I = $this;
		$I->amOnPage(\PluginManagerJoomla3Page::$URL);
		$I->verifyNotices(false, $this->checkForNotices(), 'Plugin Manager Page');
		$I->fillField(\PluginManagerJoomla3Page::$pluginSearch, $pluginName);
		$I->click(\PluginManagerJoomla3Page::$searchButton);
		$pluginManagerPage = new \PluginManagerJoomla3Page;
		$I->waitForElement($pluginManagerPage->searchResultPluginName($pluginName),30);
		$I->seeElement(\PluginManagerJoomla3Page::$searchResultRow);
		$I->see($pluginName, \PluginManagerJoomla3Page::$searchResultRow);
		$I->click(\PluginManagerJoomla3Page::$firstCheck);
		$I->click('Enable');
		$I->see(\PluginManagerJoomla3Page::$pluginEnabledSuccessMessage, '.alert-success');
	}

	/**
	 * Function To Edit PayPal plugin with Important Information corresponding to SandBox Accoutn
	 *
	 * @param   String  $businessUserEmail  Email Id for the sandBox Account
	 *
	 * @return void
	 */
	public function updatePayPalPlugin($businessUserEmail)
	{
		$I = $this;
		$I->amOnPage(\PluginManagerJoomla3Page::$URL);
		$I->verifyNotices(false, $this->checkForNotices(), 'Plugin Manager Page');
		$I->fillField(\PluginManagerJoomla3Page::$pluginSearch, 'Paypal');
		$I->click(\PluginManagerJoomla3Page::$searchButton);
		$pluginManagerPage = new \PluginManagerJoomla3Page;
		$I->waitForElement($pluginManagerPage->searchResultPluginName('Paypal'),30);
		$I->seeElement(\PluginManagerJoomla3Page::$searchResultRow);
		$I->see('Paypal', \PluginManagerJoomla3Page::$searchResultRow);
		$I->click(\PluginManagerJoomla3Page::$firstCheck);
		$I->click('Edit');
		$I->waitForElement(\PayPalPluginManagerJoomla3Page::$payPalBusinessAccountEmail,30);
		$I->fillField(\PayPalPluginManagerJoomla3Page::$payPalBusinessAccountEmail, $businessUserEmail);
		$I->click(\PayPalPluginManagerJoomla3Page::$payPalUseField);
		$I->click("Save & Close");
		$I->see(\PayPalPluginManagerJoomla3Page::$pluginSuccessSavedMessage,'.alert-success');
	}
}
