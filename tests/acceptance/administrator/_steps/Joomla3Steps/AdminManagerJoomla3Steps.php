<?php
/**
 * @package     redSHOP
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;

use AdminJ3Page;
use ExtensionManagerJoomla3Page;
use FrontEndProductManagerJoomla3Page;
use Step\Acceptance\Redshop;
use \ConfigurationPage as ConfigurationPage;

/**
 * Class AdminManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @since    1.4
 */
class AdminManagerJoomla3Steps extends Redshop
{
	/**
	 * @param $name
	 * @param $package
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function installComponent($name, $package)
	{
		$I = $this;
		$I->amOnPage(AdminJ3Page::$installURL);
		$I->waitForElementVisible(AdminJ3Page::$link, 30);
		$I->click(AdminJ3Page::$link);
		$path = $I->getConfig($name) . $package;
		$I->wantToTest($path);
		$I->comment($path);
		try
		{
			$I->waitForElementVisible(AdminJ3Page::$urlID, 10);
		} catch (\Exception $e)
		{
			$I->click(AdminJ3Page::$link);
			$I->waitForElementVisible(AdminJ3Page::$urlID, 10);
		}
		$I->fillField(AdminJ3Page::$urlID, $path);
		$I->waitForElementVisible(AdminJ3Page::$installButton, 30);
		$I->click(AdminJ3Page::$installButton);
	}

	/**
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function installRedShopExtension()
	{
		$I = $this;
		$I->wantTo('Install extension');
		$I->disableStatistics();
		$I->wantTo('I Install redSHOP');
		$I->installComponent('packages url', 'redshop.zip');
		$I->waitForText(AdminJ3Page::$messageInstallSuccess, 120, AdminJ3Page::$idInstallSuccess);

		$I->wantTo('install demo data');
		$I->waitForElement(AdminJ3Page::$installDemoContent, 30);
		$I->click(AdminJ3Page::$installDemoContent);
		try
		{
			$I->waitForText(AdminJ3Page::$messageDemoContentSuccess, 120, AdminJ3Page::$idInstallSuccess);
		}catch (\Exception $e)
		{
		}
	}

	/**
	 * Function to CheckForNotices and Warnings
	 *
	 * @return  bool
	 * @since 1.4.0
	 */
	public function checkForNotices()
	{
		$this->checkForPhpNoticesOrWarnings();
	}

	/**
	 * Function to Search for an Item
	 *
	 * @param   Object $pageClass    Class Object for which Search is to be done
	 * @param   String $searchItem   Search Variable
	 * @param   String $resultRow    Xpath for the field to be searched in
	 * @param   string $functionName Name of the function After Which search is being Called
	 *
	 * @return void
	 * @since 1.4.0
	 */
	public function search($pageClass, $searchItem, $resultRow, $functionName = 'Search')
	{
		$I = $this;
		$I->amOnPage($pageClass::$URL);

		if ($functionName == 'Search')
		{
			$I->seeElement(['link' => $searchItem]);
		}
		else
		{
			$I->dontSeeElement(['link' => $searchItem]);
		}
	}

	/**
	 * Function to Delete an Item
	 *
	 * @param   object $pageClass  Page Class where we need to delete the Item
	 * @param   string $deleteItem Item which is to be Deleted
	 * @param   string $resultRow  Result Row Where we need to pick the item from
	 * @param   string $check      Selection Box Path
	 *
	 * @return void
	 * @since 1.4.0
	 * @throws \Exception
	 */
	public function delete($pageClass, $deleteItem, $resultRow, $check, $filterId = "#filter_search")
	{
		$I = $this;
		$I->amOnPage($pageClass::$URL);
		$I->filterListBySearching($deleteItem, $filterId);
		$I->click($check);
		$I->click('Delete');
		$I->dontSeeElement(['link' => $deleteItem]);
	}

	/**
	 * Filters an administrator list by searching for a given string
	 *
	 * @param   String $text        text to be searched to filter the administrator list
	 * @param   String $searchField id of field to search
	 *
	 * @return void
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function filterListBySearching($text, $searchField = "#filter_search")
	{
		$I = $this;
		$I->executeJS('window.scrollTo(0,0)');
		$I->waitForElementVisible($searchField, 30);
		$I->fillField($searchField, $text);
		$I->pressKey($searchField, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->waitForElement(['link' => $text]);
	}

	/**
	 * Function to get State of an Item in the Administrator
	 *
	 * @param   Object $pageClass     Page at which Operation is to be performed
	 * @param   String $item          Item for which the State is being fetched
	 * @param   String $resultRow     Result Row where we need to pick the item from
	 * @param   String $itemStatePath Path to the State for the Item
	 *
	 * @return string  Result of state
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function getState($pageClass, $item, $resultRow, $itemStatePath)
	{
		$I = $this;
		$I->amOnPage($pageClass::$URL);
		$I->waitForElement(['link' => $item], 60);
		$text = $I->grabAttributeFrom($itemStatePath, 'onclick');

		if (strpos($text, 'unpublish') > 0)
		{
			$result = 'published';
		}
		else
		{
			$result = 'unpublished';
		}

		return $result;
	}

	/**
	 * Function to change State of an Item in the Backend
	 *
	 * @param   Object $pageClass   Page Class on which we are performing the Operation
	 * @param   String $state       State for the Item
	 *
	 * @return void
	 * @throws  \Exception
	 * @since 1.4.0
	 */
	public function changeState($pageClass, $state)
	{
		$I = $this;
		$I->amOnPage($pageClass::$URL);
		$I->checkAllResults();
		$I->wait(0.3);
		if ($state == 'unpublish')
		{
			$I->click("Unpublish");
		}
		else
		{
			$I->click("Publish");
		}
	}

	/**
	 * @param $text
	 * @param string $searchField
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function filterListBySearchingProduct($text, $searchField = "#keyword")
	{
		$I = $this;
		$I->executeJS('window.scrollTo(0,0)');
		$I->wait(0.5);
		$I->click(FrontEndProductManagerJoomla3Page::$buttonReset);
		$I->fillField($searchField, $text);
		$I->pressKey($searchField, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->waitForElement(['link' => $text], 30);
	}

	/**
	 * @param $text
	 * @param string $searchField
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function filterListBySearchDiscount($text, $searchField = "#name_filter")
	{
		$I = $this;
		$I->executeJS('window.scrollTo(0,0)');
		$I->fillField($searchField, $text);
		$I->pressKey($searchField, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->waitForElement(['link' => $text], 30);
	}

	/**
	 * @param $xpath
	 * @param $value
	 * @param $lengh
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function addValueForField($xpath, $value, $lengh)
	{
		$I = $this;
		$I->waitForElementVisible($xpath, 30);
		$I->click($xpath);
		for ($i = 1; $i <= $lengh; $i++)
		{
			$I->waitForElementVisible($xpath, 30);
			$I->click($xpath);
			$I->pressKey($xpath, \Facebook\WebDriver\WebDriverKeys::BACKSPACE);
		}

		$price = str_split($value);
		foreach ($price as $char)
		{
			$I->pressKey($xpath, $char);
		}
	}

	/**
	 * @param $element
	 * @param $text
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function chooseOnSelect2($element, $text)
	{
		$I = $this;
		$elementId = is_array($element) ? $element['id'] : $element;
		$I->executeJS('jQuery("' . $elementId . '").select2("search", "' . $text . '")');
		$I->waitForElement(AdminJ3Page::$select2Results, 60);
		$I->click(AdminJ3Page::$select2Results);
	}

	/**
	 * @param $text
	 * @param string $searchField
	 * @since 1.4.0
	 */
	public function filterListBySearchOrder($text, $searchField = "#filter"){
		$I = $this;
		$I->executeJS('window.scrollTo(0,0)');
		$I->fillField($searchField, $text);
		$I->pressKey($searchField, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
	}

	/**
	 * @param $label
	 * @param $option
	 * @since 1.4.0
	 */
	public function selectOptionInChosenjs($label, $option)
	{
		$I = $this;

		$I->waitForJS("return jQuery(\"label:contains('$label')\");");
		$selectID = $I->executeJS("return jQuery(\"label:contains('$label')\").attr(\"for\");");

		$option = trim($option);

		$I->waitForJS(
			"jQuery('#$selectID option').filter(function(){ return this.text.trim() === \"$option\" }).prop('selected', true); return true;",
			30
		);
		$I->waitForJS(
			"jQuery('#$selectID').trigger('liszt:updated').trigger('chosen:updated'); return true;",
			30
		);
		$I->waitForJS(
			"jQuery('#$selectID').trigger('change'); return true;",
			30
		);
	}

	/**
	 * @param $extensionURL
	 * @param $pathExtension
	 * @param $package
	 * @since 2.1.2
	 * @throws \Exception
	 */
	public function installExtensionPackageFromURL($extensionURL, $pathExtension,$package)
	{
		$I = $this;
		$I->amOnPage(AdminJ3Page::$installURL);
		$I->waitForElement(AdminJ3Page::$link, 30);
		$I->waitForElementVisible(AdminJ3Page::$link, 30);
		$I->click(AdminJ3Page::$link);
		$path = $I->getConfig($extensionURL) . $pathExtension. $package;
		$I->wantToTest($path);
		$I->comment($path);
		try {
			$I->waitForElementVisible(AdminJ3Page::$urlID, 10);
		} catch (\Exception $e) {
			$I->click(AdminJ3Page::$link);
			$I->waitForElementVisible(AdminJ3Page::$urlID, 10);
		}
		$I->fillField(AdminJ3Page::$urlID, $path);
		$I->waitForElement(AdminJ3Page::$installButton, 30);
		$I->waitForElementVisible(AdminJ3Page::$installButton, 30);
		$I->click(AdminJ3Page::$installButton);
	}

	/**
	 * @return array
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function getCurrencyValue()
	{
		$I = $this;
		$I->amOnPage(ConfigurationPage::$URL);
		$I->click(ConfigurationPage::$price);
		$I->waitForElementVisible(ConfigurationPage::$priceTab, 30);
		$currencySymbol = $I->grabValueFrom(ConfigurationPage::$currencySymbol);
		$decimalSeparator = $I->grabValueFrom(ConfigurationPage::$decimalSeparator);
		$numberOfPriceDecimals = $I->grabValueFrom(ConfigurationPage::$numberOfPriceDecimals);
		$numberOfPriceDecimals = (int)$numberOfPriceDecimals;
		$NumberZero = null;

		for ($b = 1; $b <= $numberOfPriceDecimals; $b++)
		{
			$NumberZero = $NumberZero."0";
		}

		return array(
			'currencySymbol'            => $currencySymbol,
			'decimalSeparator'          => $decimalSeparator,
			'numberZero'                => $NumberZero
		);
	}

	/**
	 * Function Uninstall redSHOP component
	 * @throws \Exception
	 * @since 3.0.2
	 */
	public function uninstallRedSHOP()
	{
		$I = $this;
		$I->amOnPage(ExtensionManagerJoomla3Page::$urlManage);
		$I->checkForPhpNoticesOrWarnings();
		$I->waitForText(ExtensionManagerJoomla3Page::$buttonClear, 30);
		$I->click(ExtensionManagerJoomla3Page::$buttonClear);

		$I->waitForJS("return window.jQuery && jQuery.active == 0;", 30);
		$I->waitForElementVisible(ExtensionManagerJoomla3Page::$searchTools, 30);
		$I->wait(0.5);
		$I->click(ExtensionManagerJoomla3Page::$searchTools);
		$I->waitForJS("return window.jQuery && jQuery.active == 0;", 30);
		$I->waitForElement(ExtensionManagerJoomla3Page::$filterType, 30);
		$I->selectOptionInChosen(ExtensionManagerJoomla3Page::$filterType, 'Component');
		$I->fillField(ExtensionManagerJoomla3Page::$searchField, 'redSHOP');
		$I->click(ExtensionManagerJoomla3Page::$searchButtonJ3);
		$I->waitForElementVisible(ExtensionManagerJoomla3Page::$manageList);
		$I->click(ExtensionManagerJoomla3Page::$linkLocation);
		$I->waitForElementVisible(ExtensionManagerJoomla3Page::$manageList);
		$I->click(ExtensionManagerJoomla3Page::$linkLocation);
		$I->click(ExtensionManagerJoomla3Page::$firstCheck);
		$I->click(ExtensionManagerJoomla3Page::$buttonUninstall);
		$I->acceptPopup();
		$I->see(ExtensionManagerJoomla3Page::$messageUninstallSuccess, ExtensionManagerJoomla3Page::$idInstallSuccess);

		$I->fillField(ExtensionManagerJoomla3Page::$searchField, 'redSHOP');
		$I->click(ExtensionManagerJoomla3Page::$searchButtonJ3);
		$I->waitForText(ExtensionManagerJoomla3Page::$messageUninstall, 10, ExtensionManagerJoomla3Page::$selectorAlert);
		$I->see(ExtensionManagerJoomla3Page::$messageUninstall, ExtensionManagerJoomla3Page::$selectorAlert);
		$I->selectOptionInChosen(ExtensionManagerJoomla3Page::$filterType, '- Select Type -');
	}
}
