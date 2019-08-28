<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Cest\AbstractCest;
//use AcceptanceTester\TaxGroupSteps;
//use Codeception\Scenario;

/**
 * Class TaxGroupCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class Tax_GroupCest extends AbstractCest
{
	use  Cest\Traits\CheckIn, Cest\Traits\Publish, Cest\Traits\Delete;

	/**
	 * Name field, which is use for search
	 *
	 * @var string
	 */
	public $nameField = 'name';

	/**
	 * Method for set new data.
	 *
	 * @return array
	 * @since 2.1.3
	 */
	protected function prepareNewData()
	{
		return array(
			'name'      => $this->faker->bothify('Testing VAT Groups ?##?')
		);
	}

	/**
	 * Abstract method for run after complete create item.
	 *
	 * @param   \AcceptanceTester      $tester    Tester
	 * @param   \Codeception\Scenario  $scenario  Scenario
	 *
	 * @return  void
	 *
	 * @depends testItemCreate
	 */
	public function deleteDataSave(\AcceptanceTester $tester, \Codeception\Scenario $scenario)
	{
		$tester->wantTo('Run after create item with save button ');
		$stepClass = $this->stepClass;

		/** @var CategorySteps $tester */
		$tester = new $stepClass($scenario);
		$tester->deleteItem('Edit ' . $this->dataNew['name']);

	}

	/**
	 * Abstract method for run after complete create item.
	 *
	 * @param   \AcceptanceTester      $tester    Tester
	 * @param   \Codeception\Scenario  $scenario  Scenario
	 *
	 * @return  void
	 *
	 * @depends testItemCreateSaveClose
	 */
	public function deleteDataSaveClose(\AcceptanceTester $tester, \Codeception\Scenario $scenario)
	{
		$tester->wantTo('Run after create item with save button ');
		$stepClass = $this->stepClass;

		/** @var CategorySteps $tester */
		$tester = new $stepClass($scenario);
		$tester->deleteItem('Edit ' . $this->dataNew['name']);

	}

	/**
	 * @return array
	 */
	protected function prepareEditData()
	{
		return array(
			'name'      => 'Edit ' . $this->dataNew['name']
		);
	}


	public function afterTestItemCreate(\AcceptanceTester $tester, \Codeception\Scenario $scenario)
	{
		$tester->wantTo('Run after create category test suite');
		$stepClass = $this->stepClass;
//
		/** @var Tax_GroupSteps $tester */
		$tester = new $stepClass($scenario);
		$tester->wantTo('Test VAT Groups and clicks on Cancel in Administrator');
		$tester->addVATGroupsCancel();

		$tester->wantTo('Test VAT Groups  Save (Missing Name) creation in Administrator');
		$tester->addVATGroupsMissingName();

		$tester->wantTo('Test VAT Groups - Edit without name in Administrator');
		$tester->addVATGroupsSave('New '.$this->dataNew['name']);
		$tester->editVATGroupsWithoutName('New '.$this->dataNew['name']);

		$tester->wantTo('Unpublish all VAT/tax Group in Administrator');
		$tester->unpublishAllGroups();

		$tester->wantTo('Publish all VAT/tax Group in Administrator');
		$tester->publishAllGroups();

		$tester->wantTo('Delete VAT/tax Group - Cancel in Administrator');
		$tester->deleteVATGroupCancel('New '.$this->dataNew['name']);

		$tester->wantTo('Delete VAT/tax Group - OK in Administrator');
		$tester->deleteVATGroupOK('New '.$this->dataNew['name']);
	}
}
