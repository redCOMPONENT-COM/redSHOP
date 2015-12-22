<?php
/**
 * @package     redSHOP
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;

/**
 * Class PaymentBeanStreamPluginManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @since    1.5
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 */
class PaymentBeanStreamPluginManagerJoomla3Steps extends AdminManagerJoomla3Steps
{
	/**
	 * Function to Update the Configuration of Braintree payment plugin
	 *
	 * @param   String  $merchantCode  Merchant Code
	 * @param   String  $username      Username
	 * @param   String  $password      Password for the API
	 *
	 * @return void
	 */
	public function updateBeanStreamPlugin($merchantCode, $username, $password)
	{
		$I = $this;
		$I->amOnPage('/administrator/index.php?option=com_plugins');
		$I->checkForPhpNoticesOrWarnings();
		$I->fillField(['xpath' => "//input[@id='filter_search']"], 'BeanStream');
		$I->click(['xpath' => "//button[@type='submit' and @data-original-title='Search']"]);
		$pluginManagerPage = new \PluginManagerJoomla3Page;
		$I->waitForElement($pluginManagerPage->searchResultPluginName('BeanStream'),30);
		$I->seeElement(['xpath' => "//form[@id='adminForm']/div/table/tbody/tr[1]"]);
		$I->see('BeanStream', ['xpath' => "//form[@id='adminForm']/div/table/tbody/tr[1]"]);
		$I->click(['xpath' => "//input[@id='cb0']"]);
		$I->click(['xpath' => "//div[@id='toolbar-edit']/button"]);
		$I->waitForElement(['xpath' => "//input[@id='jform_params_merchant_id']"],30);
		$I->fillField(['xpath' => "//input[@id='jform_params_merchant_id']"], $merchantCode);
		$I->fillField(['xpath' => "//input[@id='jform_params_api_username']"], $username);
		$I->fillField(['xpath' => "//input[@id='jform_params_api_password']"], $password);
		$I->click(['link' => 'Advanced']);
		$I->click(['xpath' => "//li//label[text()='Visa']"]);
		$I->click(['xpath' => "//li//label[text()='MasterCard']"]);
		$I->click(['xpath' => "//div[@id='toolbar-save']/button"]);
		$I->see('successfully saved', ['id' => 'system-message-container']);
	}
}
