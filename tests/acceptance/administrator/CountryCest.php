<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Cest\AbstractCest;

/**
 * Class ManageCountryAdministratorCest
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
	 * Disable test check-in
	 *
	 * @var boolean
	 */
	public $testCheckIn = false;

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
	 * @param   string  $oldName  Old name
	 *
	 * @return  array
	 */
	protected function prepareEditData($oldName = '')
	{
		return array(
			'country_name'   => 'New ' . (empty($oldName) ? $this->faker->bothify('Testing Country ?##?') : $oldName),
			'country_2_code' => $this->faker->numberBetween(10, 99),
			'country_3_code' => $this->faker->numberBetween(99, 999),
			'country_jtext'  => $this->faker->bothify('Country ?##?')
		);
	}
}
