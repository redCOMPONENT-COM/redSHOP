<?php
/**
 * @package     RedShop
 * @subpackage  Step
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Step;

/**
 * Class Redshop
 *
 * @package Step\Acceptance
 *
 * @since  2.1.0
 */
class AbstractStep extends \AcceptanceTester
{
	/**
	 * Asserts the system message contains the given message.
	 *
	 * @param   string $message The message
	 *
	 * @return  void
	 */
	public function assertSystemMessageContains($message)
	{
		$browser = $this;
		$browser->waitForElement(['id' => 'system-message-container'], 60);
		$browser->waitForText($message, 30, ['id' => 'system-message-container']);
	}

	/**
	 * Method for save item.
	 *
	 * @param   \AdminJ3Page  $pageClass   Page class
	 * @param   array         $formFields  Array of form fields
	 * @param   array         $data        Array of data.
	 *
	 * @return  void
	 */
	public function addNewItem($pageClass = null, $formFields = array(), $data = array())
	{
		$client = $this;
		$client->amOnPage($pageClass::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->click($pageClass::$buttonNew);
		$client->checkForPhpNoticesOrWarnings();

		foreach ($formFields as $index => $field)
		{
			if (!isset($data[$index]) || empty($data[$index]))
			{
				continue;
			}

			switch ($field['type'])
			{
				default:
					$client->fillField($field['xpath'], $data[$index]);
					break;
			}
		}

		$client->click($pageClass::$buttonSave);
		$client->assertSystemMessageContains($pageClass::$messageItemSaveSuccess);
	}

	/**
	 * Method for edit item.
	 *
	 * @param   \AdminJ3Page  $pageClass   Page class
	 * @param   string        $searchName  Old item search name
	 * @param   array         $formFields  Array of form fields
	 * @param   array         $data        Array of data.
	 *
	 * @return  void
	 */
	public function editItem($pageClass = null, $searchName = '', $formFields = array(), $data = array())
	{
		$client = $this;
		$client->searchItem($pageClass, $searchName);
		$client->see($searchName, $pageClass::$resultRow);
		$client->click($searchName);
		$client->waitForElement($pageClass::$selectorPageTitle, 30);

		foreach ($formFields as $index => $field)
		{
			if (!isset($data[$index]) || empty($data[$index]))
			{
				continue;
			}

			switch ($field['type'])
			{
				default:
					$client->fillField($field['xpath'], $data[$index]);
					break;
			}
		}

		$client->click($pageClass::$buttonSaveClose);
		$client->assertSystemMessageContains($pageClass::$messageItemSaveSuccess);
	}

	/**
	 * Function to search item
	 *
	 * @param   \AdminJ3Page  $pageClass    Page class
	 * @param   string        $item         Item for search
	 * @param   array         $searchField  XPath for search field
	 *
	 * @return  void
	 */
	public function searchItem($pageClass = null, $item = '',  $searchField = ['id' => 'filter_search'])
	{
		$client = $this;
		$client->amOnPage($pageClass::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->waitForText($pageClass::$namePage, 30, $pageClass::$headPage);
		$client->executeJS('window.scrollTo(0,0)');
		$client->fillField($searchField, $item);
		$client->pressKey($searchField, \Facebook\WebDriver\WebDriverKeys::ENTER);
	}

	/**
	 * Method for click button "Delete" without choice
	 *
	 * @param   \AdminJ3Page  $pageClass  Page class
	 *
	 * @return  void
	 */
	public function deleteWithoutChoice($pageClass = null)
	{
		$client = $this;
		$client->amOnPage($pageClass::$url);
		$client->click($pageClass::$buttonDelete);
		$client->acceptPopup();
		$client->waitForElement($pageClass::$searchField, 30);
	}

	/**
	 * Method for delete item
	 *
	 * @param   \AdminJ3Page  $pageClass  Page class
	 * @param   string        $item       Name of the item
	 *
	 * @return void
	 */
	public function deleteItem($pageClass = null, $item = '')
	{
		$client = $this;
		$client->searchItem($pageClass, $item);
		$client->see($item, $pageClass::$resultRow);
		$client->checkAllResults();
		$client->click($pageClass::$buttonDelete);
		$client->acceptPopup();
		$client->assertSystemMessageContains($pageClass::$messageDeleteSuccess);
		$client->searchItem($pageClass, $item);
		$client->dontSee($item, $pageClass::$resultRow);
	}
}
