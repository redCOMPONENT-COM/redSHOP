<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
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
	 * @since 1.4.0
	 */
	public $nameField = 'code';

	/**
	 * @var array
	 * @since 2.1.3
	 */
	protected $couponInfo = array();

	/**
	 * @var
	 * @since 2.1.3
	 */
	protected $startDate;

	/**
	 * @var
	 * @since 2.1.3
	 */
	protected $endDate;

	/**
	 * @var
	 * @since 2.1.3
	 */
	protected $type;

	/**
	 * Method for set new data.
	 *
	 * @return  array
	 * @since 1.4.0
	 */
	protected function prepareNewData()
	{
		$this->startDate = date('Y-m-d');
		$this->endDate = date('Y-m-d', strtotime('-2 day', strtotime($this->startDate)));
		$this->type = '0';
		$this->couponInfo = array(
			'code' => $this->faker->bothify('Coupon Code ?##?'),
			'type' => 'Total',
			'value' => '110',
			'amount_left' => '10'
		);

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
	 * @since 1.4.0
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
	 * @since 1.4.0
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
	 * @since 1.4.0
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

	/**
	 * @param CouponSteps $tester
	 * @since 2.1.3
	 */
	public function afterTestItemCreate(CouponSteps $tester)
	{
		$tester->wantTo('I want to check create coupon with Start Date larger than End Date');
		$tester->checkStartDateLargerThanEndDate($this->couponInfo, $this->type, $this->startDate, $this->endDate);
	}
}
