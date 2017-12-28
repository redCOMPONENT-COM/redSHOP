<?php
/**
 * @package     RedShop
 * @subpackage  Step
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester\Step;

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
		$tester = $this;
		$tester->waitForElement(['id' => 'system-message-container'], 60);
		$tester->waitForText($message, 30, ['id' => 'system-message-container']);
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
		$tester = $this;
		$tester->amOnPage($pageClass::$url);
		$tester->checkForPhpNoticesOrWarnings();
		$tester->click($pageClass::$buttonNew);
		$tester->checkForPhpNoticesOrWarnings();
		$tester->fillFormData($formFields, $data);
		$tester->click($pageClass::$buttonSave);
		$tester->assertSystemMessageContains($pageClass::$messageItemSaveSuccess);
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
		$tester = $this;
		$tester->searchItem($pageClass, $searchName);
		$tester->see($searchName, $pageClass::$resultRow);
		$tester->click($searchName);
		$tester->checkForPhpNoticesOrWarnings();
		$tester->waitForElement($pageClass::$selectorPageTitle, 30);
		$tester->fillFormData($formFields, $data);
		$tester->click($pageClass::$buttonSaveClose);
		$tester->assertSystemMessageContains($pageClass::$messageItemSaveSuccess);
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
		$tester = $this;
		$tester->amOnPage($pageClass::$url);
		$tester->checkForPhpNoticesOrWarnings();
		$tester->waitForText($pageClass::$namePage, 30, $pageClass::$headPage);
		$tester->executeJS('window.scrollTo(0,0)');
		$tester->fillField($searchField, $item);
		$tester->pressKey($searchField, \Facebook\WebDriver\WebDriverKeys::ENTER);
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
		$tester = $this;
		$tester->amOnPage($pageClass::$url);
		$tester->click($pageClass::$buttonDelete);
		$tester->acceptPopup();
		$tester->waitForElement($pageClass::$searchField, 30);
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
		$tester = $this;
		$tester->searchItem($pageClass, $item);
		$tester->see($item, $pageClass::$resultRow);
		$tester->checkAllResults();
		$tester->click($pageClass::$buttonDelete);
		$tester->acceptPopup();
		$tester->assertSystemMessageContains($pageClass::$messageDeleteSuccess);
		$tester->searchItem($pageClass, $item);
		$tester->dontSee($item, $pageClass::$resultRow);
	}

	/**
	 * Method for fill data in form.
	 *
	 * @param   array  $formFields  Array of form fields
	 * @param   array  $data        Array of data.
	 *
	 * @return  void
	 */
	protected function fillFormData($formFields = array(), $data = array())
	{
		foreach ($formFields as $index => $field)
		{
			if (!isset($data[$index]) || empty($data[$index]))
			{
				continue;
			}

			switch ($field['type'])
			{
				case 'radio':
					$this->selectOption($field['xpath'], $data[$index]);
					break;

				default:
					$this->fillField($field['xpath'], $data[$index]);
					break;
			}
		}
	}
}
