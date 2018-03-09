<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Cest\AbstractCest;

/**
 * Class CouponCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class CouponCest extends AbstractCest
{
	use  Cest\Traits\CheckIn, Cest\Traits\Publish, Cest\Traits\Delete;

	/**
	 * Name field, which is use for search
	 *
	 * @var string
	 */
	public $nameField = 'code';

	/**
	 * Method for set new data.
	 *
	 * @return  array
	 */
	protected function prepareNewData()
	{
		return array(
			'code'        => $this->faker->bothify('Coupon Code ?##?'),
			'type'        => 'Total',
			'value'       => '100',
			'effect'      => 'Global',
			'amount_left' => '10'
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
	 */
	public function deleteDataSave(\AcceptanceTester $tester, Codeception\Scenario $scenario)
	{
		$tester->wantTo('Run after create item with save button ');
		$stepClass = $this->stepClass;

		/** @var AbstractStep $step */
		$tester = new $stepClass($scenario);
		$tester->deleteItem('New ' . $this->dataNew['code']);

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
	 */
	public function deleteDataSaveClose(\AcceptanceTester $tester, Codeception\Scenario $scenario)
	{
		$tester->wantTo('Run after create item with save button ');
		$stepClass = $this->stepClass;

		/** @var AbstractStep $step */
		$tester = new $stepClass($scenario);
		$tester->deleteItem('New ' . $this->dataNew['code']);
	}

	/**
	 * Method for set new data.
	 *
	 * @return  array
	 */
	protected function prepareEditData()
	{
		return array(
			'code'        => 'New ' . $this->dataNew['code'],
			'type'        => 'Total',
			'value'       => '100',
			'effect'      => 'Global',
			'amount_left' => '10'
		);
	}
}
