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
		$I->amOnPage('/administrator/index.php?option=com_plugins');
		$I->checkForPhpNoticesOrWarnings();
		$I->fillField(['xpath' => "//input[@id='filter_search']"], 'Braintree');
		$I->click(['xpath' => "//button[@type='submit' and @data-original-title='Search']"]);
		$pluginManagerPage = new \PluginManagerJoomla3Page;
		$I->waitForElement($pluginManagerPage->searchResultPluginName('Braintree'), 30);
		$I->seeElement(['xpath' => "//form[@id='adminForm']/div/table/tbody/tr[1]"]);
		$I->see('Braintree', ['xpath' => "//form[@id='adminForm']/div/table/tbody/tr[1]"]);
		$I->click(['xpath' => "//input[@id='cb0']"]);
		$I->click(['xpath' => "//div[@id='toolbar-edit']/button"]);
		$I->waitForElement(['xpath' => "//input[@id='jform_params_merchant_id']"], 30);
		$I->fillField(['xpath' => "//input[@id='jform_params_merchant_id']"], $merchantCode);
		$I->fillField(['xpath' => "//input[@id='jform_params_public_key']"], $publicKey);
		$I->fillField(['xpath' => "//input[@id='jform_params_private_key']"], $privateKey);
		$I->click(['link' => 'Advanced']);
		$I->waitForElement(['xpath' => "//input[@value='VISA']"],30);
		$I->click(['xpath' => "//input[@value='VISA']"]);
		$I->click(['xpath' => "//input[@value='MC']"]);
		$I->click(['xpath' => "//div[@id='toolbar-save']/button"]);
		$I->see('successfully saved', ['id' => 'system-message-container']);
	}
}
