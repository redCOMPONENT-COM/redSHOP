<?php
/**
 * @package     RedShop
 * @subpackage  Step
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Step;

use Codeception\Scenario;
use AcceptanceTester\AdminManagerJoomla3Steps;
/**
 * Class Redshop
 *
 * @package Step\Acceptance
 *
 * @since  2.1.0
 */
class AbstractStep extends AdminManagerJoomla3Steps
{
	/**
	 * @var \AdminJ3Page
	 */
	public $pageClass;

	/**
	 * AbstractStep constructor.
	 *
	 * @param   Scenario  $scenario  Scenario
	 */
	public function __construct(Scenario $scenario)
	{
		parent::__construct($scenario);

		$this->pageClass = ucfirst(str_replace('Steps', '', get_called_class()) . 'Page');
	}

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
		$tester->waitForElement(['id' => 'system-message-container'], 30);
		$tester->waitForText($message, 30, ['id' => 'system-message-container']);
	}

	/**
	 * Method for save item.
	 *
	 * @param   array  $data  Array of data.
	 *
	 * @return  void
	 */
	public function addNewItem($data = array())
	{
		$pageClass = $this->pageClass;
		$tester    = $this;

		$tester->amOnPage($pageClass::$url);
		$tester->checkForPhpNoticesOrWarnings();
		$tester->click($pageClass::$buttonNew);
		$tester->checkForPhpNoticesOrWarnings();
		$tester->fillFormData($this->getFormFields(), $data);
		$tester->click($pageClass::$buttonSave);
		$tester->assertSystemMessageContains($pageClass::$messageItemSaveSuccess);
	}

	/**
	 * Method for edit item.
	 *
	 * @param   string   $searchName  Old item search name
	 * @param   array    $data        Array of data.
	 *
	 * @return  void
	 */
	public function editItem($searchName = '', $data = array())
	{
		$pageClass = $this->pageClass;
		$tester    = $this;

		$tester->searchItem($searchName);
		$tester->waitForElement($pageClass::$resultRow, 30);
		$tester->see($searchName, $pageClass::$resultRow);
		$tester->click($searchName);
		$tester->checkForPhpNoticesOrWarnings();
		$tester->waitForElement($pageClass::$selectorPageTitle, 30);
		$tester->fillFormData($this->getFormFields(), $data);
		$tester->click($pageClass::$buttonSaveClose);
		$tester->assertSystemMessageContains($pageClass::$messageItemSaveSuccess);
	}

	/**
	 * Function to search item
	 *
	 * @param   string  $item         Item for search
	 * @param   array   $searchField  XPath for search field
	 *
	 * @return  void
	 */
	public function searchItem($item = '',  $searchField = ['id' => 'filter_search'])
	{
		$pageClass = $this->pageClass;
		$tester    = $this;

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
	 * @return  void
	 */
	public function deleteWithoutChoice()
	{
		$pageClass = $this->pageClass;
		$tester    = $this;
		$tester->amOnPage($pageClass::$url);
		$tester->click($pageClass::$buttonDelete);
		$tester->acceptPopup();
		$tester->waitForElement($pageClass::$searchField, 30);
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
				case 'redshop.radio':
					$this->selectOption($field['xpath'], $data[$index]);
					break;
				case 'Categories':
					$this->chooseOnSelect2($field['xpath'],  $data[$index]);
					break;
				case 'template':
					$this->chooseOnSelect2($field['xpath'],  $data[$index]);
					break;
				case 'shoppergrouplist':
					$this->chooseOnSelect2($field['xpath'],  $data[$index]);
					break;
				default:
					$this->fillField($field['xpath'], $data[$index]);
					break;
			}
		}
	}

	/**
	 * Method for set form fields.
	 *
	 * @return  array
	 */
	protected function getFormFields()
	{
		$formPath = __DIR__ . '/../../../component/admin/models/forms/' . strtolower(str_replace('Page', '', $this->pageClass)) . '.xml';

		// Load single form xml file
		$form = simplexml_load_file($formPath);

		// Get field set data
		$fields = $form->xpath('(//fieldset[@name="details"]//field | //field[@fieldset="details"])[not(ancestor::field)]');

		if (empty($fields))
		{
			return array();
		}

		$formFields = array();

		foreach ($fields as $field)
		{
			$fieldName = (string) $field['name'];

			$formFields[$fieldName] = array(
				'xpath' => ['id' => 'jform_' . $fieldName],
				'type'  => (string) $field['type']
			);
		}

		return $formFields;
	}
}
