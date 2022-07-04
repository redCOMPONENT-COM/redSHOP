<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\DiscountSteps;

/**
 * Class ManageDiscountAdministratorCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class DiscountCest
{
	/**
	 * @var \Faker\Generator
	 * @since 1.4.0
	 */
	public $faker;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public $discountName;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	public $amount;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	public $discountAmount;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	public $newAmount;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public $startDate;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public $endDate;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public $shopperGroup;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	public $discountType;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	public $discountCondition;

	/**
	 * DiscountCest constructor.
	 * @since 1.4.0
	 */
	public function __construct()
	{
		$this->faker             = Faker\Factory::create();
		$this->discountName      = 'Discount' . rand(1, 100);
		$this->amount            = $this->faker->numberBetween(100, 999);
		$this->discountAmount    = $this->faker->numberBetween(10, 100);
		$this->newAmount         = $this->faker->numberBetween(100, 999);
		$this->startDate         = '13-06-' . date('Y', strtotime('+1 year'));
		$this->endDate           = '13-08-' . date('Y', strtotime('+1 year'));
		$this->shopperGroup      = 'Default Private';
		$this->discountType      = 0;
		$this->discountCondition = 1;
	}

	/**
	 * @param AcceptanceTester $I
	 * @throws Exception
	 * @since 1.4.0
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param DiscountSteps $client
	 * @since 1.4.0
	 */
	public function createDiscount(DiscountSteps $client)
	{
		$client->addDiscount(
			$this->discountName, $this->amount, $this->discountAmount, $this->shopperGroup, $this->discountType, $this->discountCondition
		);
		$client->searchDiscount($this->discountName);

		$client->wantToTest('Deletion of Discount in Administrator');
		$client->deleteDiscount($this->discountName);
	}

	/**
	 * @param DiscountSteps $client
	 * @since 1.4.0
	 */
	public function addDiscountSaveChangeStatus(DiscountSteps $client)
	{
		$client->wantTo('Test Discount creation with save button in Administrator');
		$client->addDiscountSave($this->discountName, $this->amount, $this->discountAmount, $this->shopperGroup, $this->discountType);

		$client->wantTo('Test if State of a Discount gets Updated in Administrator');
		$client->changeDiscountState($this->discountName);
		$client->verifyState('unpublished', $client->getDiscountState($this->discountName));
	}

	/**
	 * Function to Test Discount Update in the Administrator
	 * @param DiscountSteps $client
	 * @since 1.4.0
	 */
	public function updateDiscount(DiscountSteps $client)
	{
		$client->wantTo('Test if Discount gets updated in Administrator');
		$client->wantTo('Update Existing Discount');
		$client->editDiscount($this->discountName, $this->amount, $this->newAmount);
	}

	/**
	 * Function test discount with start date higher than end date.
	 * @param DiscountSteps $client
	 * @since 1.4.0
	 */
	public function addDiscountBadCases(DiscountSteps $client)
	{
		$client->wantTo('Test Discount creation with start date higher than end date.');
		$client->addDiscountStartThanEnd(
			$this->discountName, $this->amount, $this->discountAmount, $this->shopperGroup, $this->discountType, $this->startDate, $this->endDate
		);

		$client->wantTo('Test Discount creation with missing name.');
		$client->addDiscountMissingName(
			$this->amount, $this->discountAmount, $this->shopperGroup, $this->discountType, $this->startDate, $this->endDate
		);

		$client->wantTo('Test Discount creation with missing amount.');
		$client->addDiscountMissingAmount(
			$this->discountName, $this->discountAmount, $this->shopperGroup, $this->discountType, $this->startDate, $this->endDate
		);

		$client->wantTo('Test Discount creation with missing shopper groups.');
		$client->addDiscountMissingShopperGroups(
			$this->discountName, $this->amount, $this->discountAmount, $this->discountType, $this->startDate, $this->endDate
		);

		$client->wantTo('Test Discount creation with missing all fields.');
		$client->addDiscountWithAllFieldsEmpty();
	}
}
