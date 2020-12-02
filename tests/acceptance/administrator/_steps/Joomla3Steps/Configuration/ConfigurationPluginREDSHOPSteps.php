<?php
/**
 * @package     redSHOP
 * @subpackage  Steps
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Configuration;
use AcceptanceTester\AdminManagerJoomla3Steps;
use ConfigurationPluginREDSHOPPage as PluginManagerPage;

/**
 * Class ConfigurationPluginREDSHOPSteps
 * @package Configuration
 * @since 3.0.3
 */
class ConfigurationPluginREDSHOPSteps extends AdminManagerJoomla3Steps
{
	/**
	 * @param $namePlugin
	 * @throws \Exception
	 * @since 3.0.3
	 */
	public function searchPlugin($namePlugin)
	{
		$I = $this;
		$I->amOnPage(PluginManagerPage::$url);
		$I->checkForPhpNoticesOrWarnings();
		$I->waitForText(PluginManagerPage::$titlePage, 30, PluginManagerPage::$h1);
		$I->waitForElementVisible(PluginManagerPage::$searchField, 30);
		$I->fillField(PluginManagerPage::$searchField, $namePlugin);
		$I->pressKey(PluginManagerPage::$searchField, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->waitForText($namePlugin, 30, PluginManagerPage::$tablePlugin);
	}

	/**
	 * @param $namePlugin
	 * @param $status
	 * @throws \Exception
	 * @since 3.0.3
	 */
	public function changeStatus($namePlugin, $status)
	{
		$I = $this;
		$I->searchPlugin($namePlugin);
		$I->checkAllResults();

		if ($status == PluginManagerPage::$buttonUnpublish)
		{
			$I->waitForText(PluginManagerPage::$buttonUnpublish, 30);
			$I->click(PluginManagerPage::$buttonUnpublish);
			$I->waitForText(PluginManagerPage::$messageUnpublishSuccess, 30);
		}
		else if ($status == PluginManagerPage::$buttonPublish)
		{
			$I->waitForText(PluginManagerPage::$buttonPublish, 30);
			$I->click(PluginManagerPage::$buttonPublish);
			$I->waitForText(PluginManagerPage::$messagePublishSuccess, 30);
		}
		else
		{
			$I->waitForText(PluginManagerPage::$buttonCheckIn, 30);
			$I->click(PluginManagerPage::$buttonCheckIn);
			$I->waitForText(PluginManagerPage::$messageCheckInSuccess, 30);
		}
	}

	/**
	 * @param $filter
	 * @param $filterValue
	 * @throws \Exception
	 * @since 3.0.3
	 */
	public function filterPlugin($filter, $filterValue)
	{
		$I = $this;
		$I->amOnPage(PluginManagerPage::$url);
		$I->checkForPhpNoticesOrWarnings();
		$I->waitForText(PluginManagerPage::$titlePage, 30, PluginManagerPage::$h1);
		$I->waitForElementVisible(PluginManagerPage::$resetButton, 30);
		$I->click(PluginManagerPage::$resetButton);
		$I->waitForElementVisible(PluginManagerPage::$buttonSearchTool, 30);
		$I->click(PluginManagerPage::$buttonSearchTool);

		switch ($filter)
		{
			case 'status':
				$I->waitForElementVisible(PluginManagerPage::$selectStatus, 30);
				$I->click(PluginManagerPage::$selectStatus);
				$I->waitForElementVisible(PluginManagerPage::$inputSearchStatus, 30);
				$I->fillField(PluginManagerPage::$inputSearchStatus, $filterValue);
				$I->pressKey(PluginManagerPage::$inputSearchStatus, \Facebook\WebDriver\WebDriverKeys::ENTER);
				$I->waitForElementVisible(PluginManagerPage::$tablePlugin, 30);
				break;

			case 'component':
				$I->waitForElementVisible(PluginManagerPage::$selectComponent, 30);
				$I->click(PluginManagerPage::$selectComponent);
				$I->waitForElementVisible(PluginManagerPage::$inputSearchComponent, 30);
				$I->fillField(PluginManagerPage::$inputSearchComponent, $filterValue);
				$I->pressKey(PluginManagerPage::$inputSearchComponent, \Facebook\WebDriver\WebDriverKeys::ENTER);
				$I->waitForElementVisible(PluginManagerPage::$tablePlugin, 30);
				break;

			case 'type':
				$I->waitForElementVisible(PluginManagerPage::$selectType, 30);
				$I->click(PluginManagerPage::$selectType);
				$I->waitForElementVisible(PluginManagerPage::$inputSearchType, 30);
				$I->fillField(PluginManagerPage::$inputSearchType, $filterValue);
				$I->pressKey(PluginManagerPage::$inputSearchType, \Facebook\WebDriver\WebDriverKeys::ENTER);
				$I->waitForElementVisible(PluginManagerPage::$tablePlugin, 30);
				break;

			case 'element':
				$I->waitForElementVisible(PluginManagerPage::$selectElement, 30);
				$I->click(PluginManagerPage::$selectElement);
				$I->waitForElementVisible(PluginManagerPage::$inputSearchElement, 30);
				$I->fillField(PluginManagerPage::$inputSearchElement, $filterValue);
				$I->pressKey(PluginManagerPage::$inputSearchElement, \Facebook\WebDriver\WebDriverKeys::ENTER);
				$I->waitForElementVisible(PluginManagerPage::$tablePlugin, 30);
				break;

			case 'access':
				$I->waitForElementVisible(PluginManagerPage::$selectAccess, 30);
				$I->click(PluginManagerPage::$selectAccess);
				$I->waitForElementVisible(PluginManagerPage::$inputSearchAccess, 30);
				$I->fillField(PluginManagerPage::$inputSearchAccess, $filterValue);
				$I->pressKey(PluginManagerPage::$inputSearchAccess, \Facebook\WebDriver\WebDriverKeys::ENTER);
				$I->waitForElementVisible(PluginManagerPage::$tablePlugin, 30);
				break;
		}
	}
}