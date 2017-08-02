<?php
/**
 * @package     redSHOP
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;

/**
 * Class Payment2CheckoutPluginManagerJoomla3Steps
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
		$I->amOnPage('/administrator/index.php?option=com_plugins');
		$I->checkForPhpNoticesOrWarnings();
		$I->fillField(['xpath' => "//input[@id='filter_search']"], '2Checkout');
		$I->click(['xpath' => "//button[@type='submit' and @data-original-title='Search']"]);
		$pluginManagerPage = new \PluginManagerJoomla3Page;
		$I->waitForElement($pluginManagerPage->searchResultPluginName('2Checkout'),30);
		$I->seeElement(['xpath' => "//form[@id='adminForm']/div/table/tbody/tr[1]"]);
		$I->see('2Checkout', ['xpath' => "//form[@id='adminForm']/div/table/tbody/tr[1]"]);
		$I->click(['xpath' => "//input[@id='cb0']"]);
		$I->click(['xpath' => "//div[@id='toolbar-edit']/button"]);
		$I->waitForElement(['xpath' => "//input[@id='jform_params_vendor_id']"],30);
		$I->fillField(['xpath' => "//input[@id='jform_params_vendor_id']"], $vendorID);
		$I->fillField(['xpath' => "//input[@id='jform_params_secret_words']"], $secretWord);
		$I->click(['xpath' => "//div[@id='toolbar-save']/button"]);
		$I->see( 'saved', ['id' => 'system-message-container']);
	}
}
