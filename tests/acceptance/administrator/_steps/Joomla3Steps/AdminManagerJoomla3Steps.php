<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;
/**
 * Class AdminManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @since    1.4
 */
class AdminManagerJoomla3Steps extends \AcceptanceTester
{
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
	public function delete($pageClass, $deleteItem, $resultRow, $check, $filterId = ['id' => 'filter_search'])
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
	public function filterListBySearching($text, $searchField = ['id' => 'filter_search'])
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
	public function changeState($pageClass, $item, $state, $resultRow, $check, $searchField = ['id' => 'filter'])
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

	public function filterListBySearchingProduct($text, $searchField = ['id' => 'keyword'])
	{
		$I = $this;
		$I->executeJS('window.scrollTo(0,0)');
		$I->fillField($searchField, $text);
		$I->pressKey($searchField, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->waitForElement(['link' => $text]);
	}

	public function filterListBySearchDiscount($text, $searchField = ['id' => 'name_filter'])
	{
		$I = $this;
		$I->executeJS('window.scrollTo(0,0)');
		$I->fillField($searchField, $text);
		$I->pressKey('#name_filter', \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->wait(3);
		$I->waitForElement(['link' => $text]);
	}

	public function chooseOnSelect2($elementId, $text)
	{
		$I = $this;
		$I->executeJS('jQuery("' . $elementId . '").select2("search", "' . $text . '")');
		$I->waitForElement(['xpath' => "//div[@id='select2-drop']//ul[@class='select2-results']/li[1]/div"], 60);
		$I->click(['xpath' => "//div[@id='select2-drop']//ul[@class='select2-results']/li[1]/div"]);
	}

	public function filterListBySearchOrder($text, $searchField = ['id' => 'filter']){
		$I = $this;
		$I->executeJS('window.scrollTo(0,0)');
		$I->fillField($searchField, $text);
		$I->pressKey('#filter', \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->wait(3);
//        $I->waitForElement(['link' => $text]);
	}
}
