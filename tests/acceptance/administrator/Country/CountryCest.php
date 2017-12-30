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
