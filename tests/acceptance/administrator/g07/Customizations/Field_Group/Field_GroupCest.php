<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
use Cest\AbstractCest;

/**
 * Class Field_GroupCest
 * @since 1.4.0
 */
class Field_GroupCest extends AbstractCest
{
	use Cest\Traits\CheckIn, Cest\Traits\Publish, Cest\Traits\Delete;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public $nameField = 'name';

	/**
	 * @return array
	 * @since 1.4.0
	 */
	protected function prepareNewData()
	{
		return array(
			'name'        => $this->faker->bothify('Testing FieldGroups ?##?'),
			'section'     => 'category',
			'description' => $this->faker->bothify('Description Field Groups ?###?'),
			'published'   => 'published'
		);
	}

	/**
	 * Abstract method for run after complete create item.
	 *
	 * @param   \AcceptanceTester     $tester   Tester
	 * @param   Codeception\Scenario  $scenario Scenario
	 *
	 * @return  void
	 *
	 * @depends testItemCreate
	 * @since 1.4.0
	 */
	public function deleteDataSave(\AcceptanceTester $tester, Codeception\Scenario $scenario)
	{
		$tester->wantTo('Delete data after save button ');
		$stepClass = $this->stepClass;

		/** @var AbstractStep $step */
		$tester = new $stepClass($scenario);
		$tester->deleteItem('New ' . $this->dataNew['name']);
	}

	/**
	 * Abstract method for run after complete create item.
	 *
	 * @param   \AcceptanceTester    $tester   Tester
	 * @param   Codeception\Scenario $scenario Scenario
	 *
	 * @return  void
	 *
	 * @depends testItemCreateSaveClose
	 * @since 1.4.0
	 */
	public function deleteDataSaveClose(\AcceptanceTester $tester, Codeception\Scenario $scenario)
	{
		$tester->wantTo('Delete data after save button ');
		$stepClass = $this->stepClass;

		/** @var Field_GroupSteps $step */
		$tester = new $stepClass($scenario);
		$tester->deleteItem('New ' . $this->dataNew['name']);
	}

	/**
	 * Abstract method for run after complete create item.
	 * Method check missing name
	 *
	 * @param   \AcceptanceTester     $tester   Tester
	 * @param   \Codeception\Scenario $scenario Scenario
	 *
	 * @return  void
	 *
	 * @depends testItemCreateSaveNew
	 * @throws \Exception
	 */
	public function afterTestItemCreate(\AcceptanceTester $tester, \Codeception\Scenario $scenario)
	{
		$tester->wantTo('Run after create field group test suite');
		$stepClass = $this->stepClass;
		/** @var Field_GroupSteps $tester */
		$tester = new $stepClass($scenario);
		$tester->missingName();
	}

	/**
	 * Method for set new data.
	 *
	 * @return  array
	 * @since 1.4.0
	 */
	protected function prepareEditData()
	{
		return array(
			'name'        => 'New ' . $this->dataNew['name'],
			'section'     => 'category',
			'description' => $this->faker->bothify('Description Field Groups ?###?'),
			'published'   => 'Unpublished'
		);
	}
}