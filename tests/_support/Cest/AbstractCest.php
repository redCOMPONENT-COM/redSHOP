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
use Cest\Traits\Delete;

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
		$this->faker     = Factory::create();
		$this->className = get_called_class();
		$this->stepClass = str_replace('Cest', 'Steps', $this->className);
		$this->pageClass = str_replace('Cest', 'Page', $this->className);
		$this->dataNew   = $this->prepareNewData();
		$this->dataEdit  = $this->prepareEditData();
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
	 * Method for test create item
	 *
	 * @param   \AcceptanceTester  $tester    Tester
	 * @param   Scenario           $scenario  Scenario
	 *
	 * @return  void
	 */
	public function testItemCreate(\AcceptanceTester $tester, Scenario $scenario)
	{
		$tester->wantTo('Test create item.');
		$tester->wantToTest($this->stepClass);
		$tester->wantToTest($this->stepClass);
		$stepClass = $this->stepClass;

		/** @var AbstractStep $step */
		$step = new $stepClass($scenario);
		$step->addNewItem($this->dataNew, 'save');

		$step->wantToTest('edit this item with save button');
		$step->editItem($this->dataNew[$this->nameField], $this->dataEdit, 'save');
	}

	/**
	 * Abstract method for run after complete create item.
	 *
	 * @param   \AcceptanceTester  $tester    Tester
	 * @param   Scenario           $scenario  Scenario
	 *
	 * @return  void
	 *
	 * @depends testItemCreate
	 */
	public function deleteDataSave(\AcceptanceTester $tester, Scenario $scenario)
	{
		$tester->wantTo('Run after create item with save button ');
		$tester->wantToTest('After create with save we should delete');

	}

	/**
	 * Abstract method for run after complete create item.
	 *
	 * @param   \AcceptanceTester  $tester    Tester
	 * @param   Scenario           $scenario  Scenario
	 *
	 * @return  void
	 *
	 * @depends deleteDataSave
	 */
	public function testItemCreateSaveClose(\AcceptanceTester $tester, Scenario $scenario)
	{
		$tester->wantTo('Test create item.');
		$tester->wantToTest($this->stepClass);
		$tester->wantToTest($this->stepClass);
		$stepClass = $this->stepClass;

		/** @var AbstractStep $step */
		$step = new $stepClass($scenario);
		$step->addNewItem($this->dataNew, 'save&close');

		$step->wantToTest('edit this item with save &close button');
		$step->editItem($this->dataNew[$this->nameField], $this->dataEdit, 'save&close');
	}

	/**
	 * Abstract method for run after complete create item.
	 *
	 * @param   \AcceptanceTester  $tester    Tester
	 * @param   Scenario           $scenario  Scenario
	 *
	 * @return  void
	 *
	 * @depends testItemCreateSaveClose
	 */
	public function deleteDataSaveClose(\AcceptanceTester $tester, Scenario $scenario)
	{
		$tester->wantTo('Run after create item with save & close button ');
		$tester->wantToTest('Delete after save & close button');
	}

	/**
	 * Abstract method for run after complete create item.
	 *
	 * @param   \AcceptanceTester  $tester    Tester
	 * @param   Scenario           $scenario  Scenario
	 *
	 * @return  void
	 *
	 * @depends deleteDataSaveClose
	 */
	public function testItemCreateSaveNew(\AcceptanceTester $tester, Scenario $scenario)
	{
		$tester->wantTo('Test create item.');
		$tester->wantToTest($this->stepClass);
		$tester->wantToTest($this->stepClass);
		$stepClass = $this->stepClass;

		/** @var AbstractStep $step */
		$step = new $stepClass($scenario);
		$step->addNewItem($this->dataNew, 'save&new');

		$step->wantToTest('edit this item with save &close button');
		$step->editItem($this->dataNew[$this->nameField], $this->dataEdit, 'save&new');


	}
	
	/**
	 * Abstract method for run after complete create item.
	 *
	 * @param   \AcceptanceTester  $tester    Tester
	 * @param   Scenario           $scenario  Scenario
	 *
	 * @return  void
	 *
	 * @depends testItemCreateSaveNew
	 */
	public function afterTestItemCreate(\AcceptanceTester $tester, Scenario $scenario)
	{
		$tester->wantTo('Run after create item test suite');
	}

	/**
	 * Method for test edit item
	 *
	 * @param   \AcceptanceTester  $tester    Tester
	 * @param   Scenario           $scenario  Scenario
	 *
	 * @return  void
	 *
	 * @depends afterTestItemCreate
	 */
	public function testItemEdit(\AcceptanceTester $tester, Scenario $scenario)
	{
		$tester->wantTo('Test Edit item.');
		$stepClass = $this->stepClass;

		/** @var AbstractStep $step */
		$step = new $stepClass($scenario);
		$step->editItem($this->dataNew[$this->nameField], $this->dataEdit);
		$step->searchItem($this->dataNew[$this->nameField]);
	}

	/**
	 * Abstract method for run after complete edit item.
	 *
	 * @param   \AcceptanceTester  $tester    Tester
	 * @param   Scenario           $scenario  Scenario
	 *
	 * @return  void
	 *
	 * @depends testItemEdit
	 */
	public function afterTestItemEdit(\AcceptanceTester $tester, Scenario $scenario)
	{
		$tester->wantTo('Run after edit item test suite');
	}
}
