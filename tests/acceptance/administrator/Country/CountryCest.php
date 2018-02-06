<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Cest\AbstractCest;

/**
 * Country cest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class CountryCest extends AbstractCest
{
	use Cest\Traits\Delete;

	/**
	 * Name field, which is use for search
	 *
	 * @var string
	 */
	public $nameField = 'country_name';

	/**
	 * Method for set new data.
	 *
	 * @return  array
	 */
	protected function prepareNewData()
	{
		return array(
			'country_name'   => $this->faker->bothify('Testing Country ?##?'),
			'country_2_code' => $this->faker->numberBetween(10, 99),
			'country_3_code' => $this->faker->numberBetween(99, 999),
			'country_jtext'  => $this->faker->bothify('Country ?##?')
		);
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
	public function deleteDataSave(\AcceptanceTester $tester,  $scenario)
	{
		$tester->wantTo('Run after create item with save button ');
		$stepClass = $this->stepClass;

		/** @var AbstractStep $step */
		$tester = new $stepClass($scenario);
		$tester->deleteItem('New ' . $this->dataNew['country_name']);

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
	public function deleteDataSaveClose(\AcceptanceTester $tester,  $scenario)
	{
		$tester->wantTo('Run after create item with save button ');
		$stepClass = $this->stepClass;

		/** @var AbstractStep $step */
		$tester = new $stepClass($scenario);
		$tester->deleteItem('New ' . $this->dataNew['country_name']);
	}

	/**
	 * Method for set new data.
	 *
	 * @return  array
	 */
	protected function prepareEditData()
	{
		return array(
			'country_name'   => 'New ' . $this->dataNew['country_name'],
			'country_2_code' => $this->faker->numberBetween(10, 99),
			'country_3_code' => $this->faker->numberBetween(99, 999),
			'country_jtext'  => $this->faker->bothify('Country ?##?')
		);
	}
}
