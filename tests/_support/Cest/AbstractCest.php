<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Cest;

use Codeception\Scenario;
use Faker\Factory;
use Faker\Generator;
use Step\AbstractStep;
use function strtolower;
use function var_dump;

/**
 * Class Abstract cest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    2.0
 */
class AbstractCest
{
	/**
	 * @var  Generator
	 */
	public $faker;

	/**
	 * @var  string
	 */
	public $className;

	/**
	 * @var  string
	 */
	public $stepClass;

	/**
	 * @var  string
	 */
	public $pageClass;

	/**
	 * @var array
	 */
	public $formFields = array();

	/**
	 * @var array
	 */
	public $dataNew = array();

	/**
	 * @var array
	 */
	public $dataEdit = array();

	/**
	 * Name field, which is use for search
	 *
	 * @var string
	 */
	public $nameField = '';

	/**
	 * CountryCest constructor.
	 */
	public function __construct()
	{
		$this->faker      = Factory::create();
		$this->className  = get_called_class();
		$this->stepClass  = str_replace('Cest', 'Steps', $this->className);
		$this->pageClass  = str_replace('Cest', 'Page', $this->className);
		$this->dataNew    = $this->prepareNewData();
		$this->dataEdit   = $this->prepareEditData();
		$this->formFields = $this->prepareFormFields();
	}

	/**
	 * Method run before
	 *
	 * @param   \AcceptanceTester $tester Acceptance tester
	 *
	 * @return  void
	 */
	public function _before(\AcceptanceTester $tester)
	{
		$tester->doAdministratorLogin();
	}

	/**
	 * Method for set new data.
	 *
	 * @return  array
	 */
	protected function prepareNewData()
	{
		return array();
	}

	/**
	 * Method for set new data.
	 *
	 * @return  array
	 */
	protected function prepareEditData()
	{
		return array();
	}

	/**
	 * Method for set form fields.
	 *
	 * @return  array
	 */
	protected function prepareFormFields()
	{
		$formPath = __DIR__ . '/../../../component/admin/models/forms/' . strtolower(str_replace('Cest', '', $this->className)) . '.xml';

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
	 * Method for test button "Delete"
	 *
	 * @param   \AcceptanceTester  $tester    Tester
	 * @param   Scenario           $scenario  Scenario
	 *
	 * @return  void
	 */
	public function testButtonDelete(\AcceptanceTester $tester, Scenario $scenario)
	{
		$tester->wantTo('Administrator -> Button -> Delete without choice.');

		$stepClass = $this->stepClass;

		/** @var \AdminJ3Page $pageClass */
		$pageClass = $this->pageClass;

		/** @var AbstractStep $step */
		$step = new $stepClass($scenario);

		$step->deleteWithoutChoice($pageClass);
		$step->see($pageClass::$namePage, $pageClass::$selectorPageTitle);
	}

	/**
	 * Method for test create item
	 *
	 * @param   \AcceptanceTester  $tester    Tester
	 * @param   Scenario           $scenario  Scenario
	 *
	 * @return  void
	 */
	public function testItemCreate(\AcceptanceTester $tester, Scenario $scenario)
	{
		$tester->wantTo('Administrator -> Create item.');
		$stepClass = $this->stepClass;

		/** @var AbstractStep $step */
		$step = new $stepClass($scenario);
		$step->addNewItem($this->pageClass, $this->formFields, $this->dataNew);
	}

	/**
	 * Method for test edit item
	 *
	 * @param   \AcceptanceTester  $tester    Tester
	 * @param   Scenario           $scenario  Scenario
	 *
	 * @return  void
	 *
	 * @depends testItemCreate
	 */
	public function testItemEdit(\AcceptanceTester $tester, Scenario $scenario)
	{
		$tester->wantTo('Administrator -> Edit item.');
		$stepClass = $this->stepClass;

		/** @var AbstractStep $step */
		$step = new $stepClass($scenario);
		$step->editItem($this->pageClass, $this->dataNew[$this->nameField], $this->formFields, $this->dataEdit);
	}

	/**
	 * Method for test delete item
	 *
	 * @param   \AcceptanceTester  $tester    Tester
	 * @param   Scenario           $scenario  Scenario
	 *
	 * @return  void
	 *
	 * @depends testItemEdit
	 */
	public function testItemDelete(\AcceptanceTester $tester, Scenario $scenario)
	{
		$tester->wantTo('Administrator -> Delete item.');
		$stepClass = $this->stepClass;

		/** @var AbstractStep $step */
		$step = new $stepClass($scenario);
		$step->deleteItem($this->pageClass, $this->dataNew[$this->nameField]);
	}
}
