<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Cest\AbstractCest;

/**
 * Class ManageCurrencyAdministratorCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4.0
 */
class CurrencyCest extends AbstractCest
{
	use Cest\Traits\CheckIn, Cest\Traits\Delete;

	/**
	 * Name field, which is use for search
	 *
	 * @var string
	 * @since 1.4.0
	 */
	public $nameField = 'name';

	/**
	 * Method for set new data.
	 *
	 * @return  array
	 * @since 1.4.0
	 */
	protected function prepareNewData()
	{
		return array(
			'name' => $this->faker->bothify('Testing Currency ?##?'),
			'code' => $this->faker->bothify('R##')
		);
	}

	/**
	 * Abstract method for run after complete create item.
	 *
	 * @param   \AcceptanceTester     $tester    Tester
	 * @param   Codeception\Scenario  $scenario  Scenario
	 *
	 * @return  void
	 *
	 * @depends testItemCreate
	 * @since 1.4.0
	 */
	public function deleteDataSave(\AcceptanceTester $tester, Codeception\Scenario $scenario)
	{
		$tester->wantTo('Run after create item with save button ');
		$stepClass = $this->stepClass;

		/** @var CurrencySteps $tester */
		$tester = new $stepClass($scenario);
		$tester->deleteItem('New ' . $this->dataNew['name']);

	}

	/**
	 * Abstract method for run after complete create item.
	 *
	 * @param   \AcceptanceTester     $tester    Tester
	 * @param   Codeception\Scenario  $scenario  Scenario
	 *
	 * @return  void
	 *
	 * @depends testItemCreateSaveClose
	 * @since 1.4.0
	 */
	public function deleteDataSaveClose(\AcceptanceTester $tester, Codeception\Scenario $scenario)
	{
		$tester->wantTo('Run after create item with save button ');
		$stepClass = $this->stepClass;

		/** @var CurrencySteps $tester */
		$tester = new $stepClass($scenario);
		$tester->deleteItem('New ' . $this->dataNew['name']);

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
			'name' => 'New ' . $this->dataNew['name'],
			'code' => $this->dataNew['code']
		);
	}
}
