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
class Payment2CheckoutPluginManagerJoomla3Steps extends AdminManagerJoomla3Steps
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
		$I->waitForElement($pluginManagerPage->searchResultPluginName($pluginName), 30);
		$I->seeElement(\PluginManagerJoomla3Page::$searchResultRow);
		$I->see($pluginName, \PluginManagerJoomla3Page::$searchResultRow);
		$I->click(\PluginManagerJoomla3Page::$firstCheck);
		$I->click('Enable');
		$I->see(\PluginManagerJoomla3Page::$pluginEnabledSuccessMessage, '.alert-success');
	}

	/**
	 * Function to Update Checkout Plugin Information
	 *
	 * @param   String  $vendorID    Vendor ID for the Account
	 * @param   String  $secretWord  Secret Word for the Account
	 *
	 * @return void
	 */
	public function update2CheckoutPlugin($vendorID, $secretWord)
	{
		$I = $this;
		$I->amOnPage(\PluginManagerJoomla3Page::$URL);
		$I->verifyNotices(false, $this->checkForNotices(), 'Plugin Manager Page');
		$I->fillField(\PluginManagerJoomla3Page::$pluginSearch, '2Checkout');
		$I->click(\PluginManagerJoomla3Page::$searchButton);
		$pluginManagerPage = new \PluginManagerJoomla3Page;
		$I->waitForElement($pluginManagerPage->searchResultPluginName('2Checkout'), 30);
		$I->seeElement(\PluginManagerJoomla3Page::$searchResultRow);
		$I->see('2Checkout', \PluginManagerJoomla3Page::$searchResultRow);
		$I->click(\PluginManagerJoomla3Page::$firstCheck);
		$I->click('Edit');
		$I->waitForElement(['xpath' => "//input[@id='jform_params_vendor_id']"], 30);
		$I->fillField(['xpath' => "//input[@id='jform_params_vendor_id']"], $vendorID);
		$I->fillField(['xpath' => "//input[@id='jform_params_secret_words']"], $secretWord);
		$I->click("Save & Close");
		$I->see(\PayPalPluginManagerJoomla3Page::$pluginSuccessSavedMessage, '.alert-success');
	}
}
