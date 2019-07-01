<?php
/**
 * @package     redSHOP
 * @subpackage  Steps
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Administrator\plugins;
use AcceptanceTester\AdminManagerJoomla3Steps;
use PluginManagerJoomla3Page;

/**
 * Class PluginPaymentManagerJoomla
 * @since 2.1.2
 */
class PluginPaymentManagerJoomla extends AdminManagerJoomla3Steps
{
	/**
	 * @param $pluginName
	 * @param $vendorID
	 * @param $secretWord
	 * @throws \Exception
	 */
	public function config2CheckoutPlugin($pluginName, $vendorID, $secretWord)
	{
		$I = $this;
		$I->amOnPage(PluginManagerJoomla3Page:: $URL);
		$I->searchForItem($pluginName);
		$pluginManagerPage = new PluginManagerJoomla3Page;
		$I->waitForElement($pluginManagerPage->searchResultPluginName($pluginName), 30);
		$I->waitForElementVisible(PluginManagerJoomla3Page:: $searchResultRow, 30);
		$I->waitForText($pluginName, 30, PluginManagerJoomla3Page:: $searchResultRow);
		$I->click($pluginName);
		$I->waitForElementVisible( PluginManagerJoomla3Page:: $vendorID ,30);
		$I->fillField( PluginManagerJoomla3Page:: $vendorID , $vendorID);
		$I->fillField(PluginManagerJoomla3Page::$secretWords, $secretWord);
		$I->clickToolbarButton(PluginManagerJoomla3Page:: $buttonSaveClose);
		$I->waitForText(PluginManagerJoomla3Page::$pluginSaveSuccessMessage, 30, PluginManagerJoomla3Page:: $idInstallSuccess);
	}

	/**
	 * @param $pluginName
	 * @param $merchantID
	 * @param $keyMD5
	 * @throws \Exception
	 * since 2.1.2
	 */
	public function configEPayPlugin($pluginName, $merchantID, $keyMD5)
	{
		$I = $this;
		$I->amOnPage(PluginManagerJoomla3Page:: $URL);
		$I->searchForItem($pluginName);
		$pluginManagerPage = new PluginManagerJoomla3Page;
		$I->waitForElement($pluginManagerPage->searchResultPluginName($pluginName), 30);
		$I->waitForElementVisible(PluginManagerJoomla3Page:: $searchResultRow, 30);
		$I->waitForText($pluginName, 30, PluginManagerJoomla3Page:: $searchResultRow);
		$I->click($pluginName);
		$I->waitForElementVisible( PluginManagerJoomla3Page:: $merchantID ,30);
		$I->fillField( PluginManagerJoomla3Page:: $merchantID , $merchantID);
		$I->fillField(PluginManagerJoomla3Page::$keyMD5, $keyMD5);
		$I->clickToolbarButton(PluginManagerJoomla3Page:: $buttonSaveClose);
		$I->waitForText(PluginManagerJoomla3Page::$pluginSaveSuccessMessage, 30, PluginManagerJoomla3Page:: $idInstallSuccess);
	}
}