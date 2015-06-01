<?php
/**
 * @package     redSHOP
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;

/**
 * Class PaymentBraintreePluginManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @since    1.5
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 */
class PaymentBraintreePluginManagerJoomla3Steps extends AdminManagerJoomla3Steps
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
	 * Function to Update the Configuration of Braintree payment plugin
	 *
	 * @param   String  $merchantCode  Merchant Code
	 * @param   String  $publicKey     Username
	 * @param   String  $privateKey    Password for the API
	 *
	 * @return void
	 */
	public function updateBraintreePlugin($merchantCode, $publicKey, $privateKey)
	{
		$I = $this;
		$I->amOnPage(\PluginManagerJoomla3Page::$URL);
		$I->verifyNotices(false, $this->checkForNotices(), 'Plugin Manager Page');
		$I->fillField(\PluginManagerJoomla3Page::$pluginSearch, 'Braintree');
		$I->click(\PluginManagerJoomla3Page::$searchButton);
		$pluginManagerPage = new \PluginManagerJoomla3Page;
		$I->waitForElement($pluginManagerPage->searchResultPluginName('Braintree'), 30);
		$I->seeElement(\PluginManagerJoomla3Page::$searchResultRow);
		$I->see('Braintree', \PluginManagerJoomla3Page::$searchResultRow);
		$I->click(\PluginManagerJoomla3Page::$firstCheck);
		$I->click('Edit');
		$I->waitForElement(['xpath' => "//input[@id='jform_params_merchant_id']"], 30);
		$I->fillField(['xpath' => "//input[@id='jform_params_merchant_id']"], $merchantCode);
		$I->fillField(['xpath' => "//input[@id='jform_params_public_key']"], $publicKey);
		$I->fillField(['xpath' => "//input[@id='jform_params_private_key']"], $privateKey);
		$I->click(['link' => 'Advanced']);
		$I->click(['xpath' => "//li//label[text()='Visa']"]);
		$I->click(['xpath' => "//li//label[text()='MasterCard']"]);
		$I->click("Save & Close");
		$I->see(\PayPalPluginManagerJoomla3Page::$pluginSuccessSavedMessage, '.alert-success');
	}
}
