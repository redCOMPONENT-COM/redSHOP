<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
use AcceptanceTester\WrapperSteps;

/**
 * Class WrappingCheckInvalidFieldCest
 * *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since 2.1.2.2
 */
class WrappingCheckInvalidFieldCest
{
	/**
	 * WrappingCheckInvalidFieldCest constructor.
	 * @since 2.1.2.2
	 */
	public function __construct()
	{
		$this->faker = Faker\Factory::create();
		$this->name = $this->faker->bothify('Wrapping test ##');
		$this->price = $this->faker->bothify("##??");
		$this->categoryname = null;
	}

	/**
	 * @param AcceptanceTester $I
	 * @throws Exception
	 * @since 2.1.2.2
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param WrapperSteps $I
	 * @throws Exception
	 * @since 2.1.2.2
	 */
	public function CheckInvalidPrice(WrapperSteps $I)
	{
		$I->wantTo("I want to test invalid price of Wrapping");
		$I->checkWrapperInvalidField($this->name, $this->price);
	}
}