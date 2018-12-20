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
use TheSeer\Tokenizer\Exception;

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
		$tester->waitForElement(['id' => 'system-message'], 30);
		$tester->waitForText($message, 30, ['id' => 'system-message']);
	}

	/**
	 * Method for save item with save , save & close, save & new
	 *
	 * @param   array  $data  Array of data.
	 *
	 * @return  void
	 */
	public function addNewItem($data = array(), $function = 'save')
	{
		$pageClass = $this->pageClass;
		$tester    = $this;

		$tester->amOnPage($pageClass::$url);
		$tester->checkForPhpNoticesOrWarnings();
		$tester->click($pageClass::$buttonNew);
		$tester->checkForPhpNoticesOrWarnings();
		$tester->fillFormData($this->getFormFields(), $data);
		
		switch ($function)
		{
			case 'save':
				$tester->wait(0.5);
				$tester->click($pageClass::$buttonSave);
                $tester->assertSystemMessageContains($pageClass::$messageItemSaveSuccess);
                $tester->click($pageClass::$buttonClose);
				break;
			case 'save&close':
				$tester->wait(0.5);
				$tester->click($pageClass::$buttonSaveClose);
                $tester->assertSystemMessageContains($pageClass::$messageItemSaveSuccess);
				break;
			case 'save&new':
				$tester->wait(0.5);
				$tester->click($pageClass::$buttonSaveNew);
                $tester->assertSystemMessageContains($pageClass::$messageItemSaveSuccess);
                $tester->click($pageClass::$buttonCancel);
				break;
			default:
				break;
		}
	}

	/**
	 * Method for edit item.
	 *
	 * @param   string   $searchName  Old item search name
	 * @param   array    $data        Array of data.
	 *
	 * @return  void
	 */
	public function editItem($searchName = '', $data = array(), $function)
	{
		$pageClass = $this->pageClass;
		$tester    = $this;
		$tester->searchItemCheckIn($searchName);
		$tester->waitForElement($pageClass::$resultRow, 30);
		$tester->see($searchName, $pageClass::$resultRow);
		$tester->wait(0.5);
		$tester->click($searchName);
		$tester->wait(0.5);
		$tester->checkForPhpNoticesOrWarnings();
		$tester->waitForElement($pageClass::$selectorPageTitle, 30);
		$tester->fillFormData($this->getFormFields(), $data);
		
		switch ($function)
		{
			case 'save':
				$tester->click($pageClass::$buttonSave);
				$tester->wait(0.5);
                $tester->assertSystemMessageContains($pageClass::$messageItemSaveSuccess);
                $tester->click($pageClass::$buttonClose);
				break;
			case 'save&close':
				$tester->click($pageClass::$buttonSaveClose);
				$tester->wait(0.5);
                $tester->assertSystemMessageContains($pageClass::$messageItemSaveSuccess);
				break;
			case 'save&new':
				$tester->click($pageClass::$buttonSaveNew);
				$tester->wait(0.5);
                $tester->assertSystemMessageContains($pageClass::$messageItemSaveSuccess);
                $tester->click($pageClass::$buttonCancel);
				break;
			default:
				break;
		}
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
     * @param string $item
     * @param array $searchField
     */
    public function searchItemCheckIn($item = '',  $searchField = ['id' => 'filter_search'])
    {
        $pageClass = $this->pageClass;
        $tester    = $this;

        $tester->amOnPage($pageClass::$url);
        $tester->checkForPhpNoticesOrWarnings();
        $tester->waitForText($pageClass::$namePage, 30, $pageClass::$headPage);
        $tester->executeJS('window.scrollTo(0,0)');
        $tester->fillField($searchField, $item);
        $tester->pressKey($searchField, \Facebook\WebDriver\WebDriverKeys::ENTER);
        try{
            $tester->waitForElement($pageClass::$checkInButtonList, 2);
            $tester->click($pageClass::$checkInButtonList);
        }catch (\Exception $e)
        {

        }
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
	 *
	 * Method for change show list 
	 *
	 * @param $value
	 *
	 */
	public function showAllItem($value)
	{
		$pageClass = $this->pageClass;
		$tester    = $this;
		$tester->amOnPage($pageClass::$url);
		$tester->waitForElement($pageClass::$listId, 30);
		$tester->click($pageClass::$listId);
		$tester->waitForElement($pageClass::$listSearchId, 30);
		$tester->fillField($pageClass::$listSearchId, $value);
		$usePage = new $pageClass();
		$tester->waitForElement($usePage->returnChoice($value), 30);
		$tester->click($usePage->returnChoice($value));
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

			switch (strtolower($field['type']))
			{
				case 'radio':
				case 'redshop.radio':
					$this->selectOption($field['xpath'], $data[$index]);
					break;
				case 'redshop.fieldsection':
					$this->chooseOnSelect2($field['xpath'], $data[$index]);
					break;

				case 'redshop.mail_section':
				case 'redshop.template':
				case 'categories':
				case 'template':
				case 'shoppergrouplist':
					$this->chooseOnSelect2($field['xpath'], $data[$index]);
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

	/**
	 * Method for choose option in select2
	 *
	 * @param   mixed   $element  Element xPath
	 * @param   string  $text     Text of option
	 *
	 * @return  void
	 */
	public function chooseOnSelect2($element, $text)
	{
		$elementId = is_array($element) ? $element['id'] : $element;
		$this->executeJS('jQuery("#' . $elementId . '").select2("search", "' . $text . '")');
		$this->waitForElement(['xpath' => "//div[@id='select2-drop']//ul[@class='select2-results']/li[1]/div"], 60);
		$this->click(['xpath' => "//div[@id='select2-drop']//ul[@class='select2-results']/li[1]/div"]);
	}
}
