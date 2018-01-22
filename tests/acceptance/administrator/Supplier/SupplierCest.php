<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Cest\AbstractCest;
use Codeception\Scenario;
use Step\AbstractStep;

/**
 * Class SupplierSteps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class SupplierCest extends AbstractCest
{
	use Cest\Traits\CheckIn, Cest\Traits\Publish, Cest\Traits\Delete;

	/**
	 * Name field, which is use for search
	 *
	 * @var string
	 */
	public $nameField = 'name';

	/**
	 * Method for set new data.
	 *
	 * @return  array
	 */
	protected function prepareNewData()
	{
		return array(
			'name' => $this->faker->bothify('Testing Currency ?##?'),
			'email' => $this->faker->email
		);
	}

	/**
	 * Method for set new data.
	 *
	 * @return  array
	 */
	protected function prepareEditData()
	{
		return array(
			'name' => 'New ' . $this->dataNew['name'],
			'email' => $this->faker->email
		);
	}

	/**
	 * Function add supplier when missing name
	 *
	 * @param   AcceptanceTester  $tester    Tester
	 * @param   Scenario          $scenario  Scenario
	 *
	 * @return  void
	 */
	public function addSupplierSaveMissingName(AcceptanceTester $tester, $scenario)
	{
		$tester->wantTo('Test Supplier Missing Name creation in Administrator');
		$stepClass = $this->stepClass;

		/** @var AbstractStep $step */
		$step = new $stepClass($scenario);
		$step->addSupplierSaveMissingName($this->faker->email);
	}

	/**
	 * Method check cancel button at detail page
	 *
	 * @param   AcceptanceTester  $client    Tester
	 * @param   Scenario          $scenario  Scenario
	 *
	 * @return  void
	 */
	public function checkCancelButton(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test Supplier check Close button in Administrator');
		$stepClass = $this->stepClass;

		/** @var AbstractStep $step */
		$step = new $stepClass($scenario);
		$step->checkCancelButton();
	}

	/**
	 * Method check wrong email at page detail
	 *
	 * @param   AcceptanceTester  $client    Tester
	 * @param   Scenario          $scenario  Scenario
	 *
	 * @return  void
	 */
	public function addSupplierWrongEmail(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test Supplier check Close button in Administrator');
		$stepClass = $this->stepClass;

		/** @var AbstractStep $step */
		$step = new $stepClass($scenario);
		$step->addSupplierWrongEmail($this->dataNew['name'], 'demo');
	}
}

