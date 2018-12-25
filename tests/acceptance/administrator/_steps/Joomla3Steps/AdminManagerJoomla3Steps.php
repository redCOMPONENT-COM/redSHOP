<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;

use Step\Acceptance\Redshop;

/**
 * Class AdminManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @since    1.4
 */
class AdminManagerJoomla3Steps extends Redshop
{

	public function installComponent($name, $package)
	{
		$I = $this;
		$I->amOnPage(\AdminJ3Page::$installURL);
		$I->waitForElement(\AdminJ3Page::$link, 30);
		$I->click(\AdminJ3Page::$link);
		$path = $I->getConfig($name) . $package;
		$I->wantToTest($path);
		$I->comment($path);
		$I->fillField(\AdminJ3Page::$urlID, $path);
		$I->waitForElement(\AdminJ3Page::$installButton, 30);
		$I->click(\AdminJ3Page::$installButton);
	}
	/**
	 * Function to Check for Presence of Notices and Warnings on all the Modules of Extension
	 *
	 * @return void
	 */
	public function CheckAllLinks()
	{
		$I = $this;

		foreach (\AdminManagerPage::$allExtensionPages as $page => $url)
		{
			$I->amOnPage($url);
			$I->verifyNotices(false, $this->checkForNotices(), $page);
			$I->click('New');
			$I->verifyNotices(false, $this->checkForNotices(), $page . ' New');
			$I->click('Cancel');
		}
	}

	/**
	 * Function to CheckForNotices and Warnings
	 *
	 * @return  bool
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
	 */
	public function filterListBySearching($text, $searchField = "#filter_search")
	{
		$I = $this;
		$I->executeJS('window.scrollTo(0,0)');
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
	 * @param   String $item        Item which we are supposed to change
	 * @param   String $state       State for the Item
	 * @param   String $resultRow   Result row where we need to look for the item
	 * @param   String $check       Checkbox path for Selecting the Item
	 * @param   String $searchField The locator for the search field
	 *
	 * @return void
	 */
	public function changeState($pageClass, $item, $state, $resultRow, $check, $searchField = "#filter")
	{
		$I = $this;
		$I->amOnPage($pageClass::$URL);
		$I->filterListBySearching($item, $searchField);
		$I->click($check);

		if ($state == 'unpublish')
		{
			$I->click("Unpublish");
		}
		else
		{
			$I->click("Publish");
		}
	}

	public function filterListBySearchingProduct($text, $searchField = "#keyword")
	{
		$I = $this;
		$I->executeJS('window.scrollTo(0,0)');
		$I->fillField($searchField, $text);
		$I->pressKey($searchField, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->waitForElement(['link' => $text], 30);
	}

	public function filterListBySearchDiscount($text, $searchField = "#name_filter")
	{
		$I = $this;
		$I->executeJS('window.scrollTo(0,0)');
		$I->fillField($searchField, $text);
		$I->pressKey('#name_filter', \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->waitForElement(['link' => $text], 30);
	}
	
	/**
	 * @param $xpath
	 * @param $value
	 * @param $lengh
	 */
	public function addValueForField($xpath, $value, $lengh)
	{
		$I = $this;
		$I->click($xpath);
		for ($i = 1; $i <= $lengh; $i++)
		{
			$I->pressKey($xpath, \Facebook\WebDriver\WebDriverKeys::BACKSPACE);
		}

		$price = str_split($value);
		foreach ($price as $char)
		{
			$I->pressKey($xpath, $char);
		}
	}

	public function chooseOnSelect2($element, $text)
	{
		$I = $this;
		$elementId = is_array($element) ? $element['id'] : $element;
		$I->executeJS('jQuery("' . $elementId . '").select2("search", "' . $text . '")');
		$I->waitForElement("//ul[@class='select2-results']/li[1]/div", 60);
		$I->click("//ul[@class='select2-results']/li[1]/div");
	}

	public function filterListBySearchOrder($text, $searchField = "#filter"){
		$I = $this;
		$I->executeJS('window.scrollTo(0,0)');
		$I->fillField($searchField, $text);
		$I->pressKey('#filter', \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
	}

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
}
