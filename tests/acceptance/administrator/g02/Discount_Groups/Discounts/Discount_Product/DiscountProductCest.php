<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\DiscountProductSteps;

/**
 * Class DiscountProductCest
 * @since 2.1.0
*/
class DiscountProductCest
{
	/**
	 * @var \Faker\Generator
	 * @since 2.1.0
	 */
	public $faker;

	/**
	 * @var string
	 * @since 2.1.0
	 */
	public $categoryName;

	/**
	 * @var int
	 * @since 2.1.0
	 */
	public $noPage;

	/**
	 * @var int
	 * @since 2.1.0
	 */
	public $productPrice;

	/**
	 * @var int
	 * @since 2.1.0
	 */
	public $condition;

	/**
	 * @var int
	 * @since 2.1.0
	 */
	public $type;

	/**
	 * @var string
	 * @since 2.1.0
	 */
	public $startDate;

	/**
	 * @var string
	 * @since 2.1.0
	 */
	public $endDate;

	/**
	 * @var int
	 * @since 2.1.0
	 */
	public $discountAmount;

	/**
	 * @var string
	 * @since 2.1.0
	 */
	public $groupName;

	/**
	 * @var array
	 * @since 2.1.0
	 */
	protected $dataCategory;

	/**
	 * DiscountProductCest constructor.
	 * @since 2.1.0
	 */
	public function __construct()
	{
		$this->faker          = Faker\Factory::create();
		$this->dataCategory = array();
		$this->dataCategory['name'] = $this->faker->bothify('Category Name ?##?');
		$this->dataCategory['type'] = 'Total';
		$this->noPage         = $this->faker->randomNumber();
		$this->productPrice   = $this->faker->numberBetween(100, 999);
		$this->startDate      = "2017-06-19";
		$this->endDate        = "2017-06-23";
		$this->discountAmount = $this->faker->numberBetween(1, 10);
		$this->groupName      = "Default Private";

		// Higher
		$this->condition = 3;

		// Percentage
		$this->type = 1;
	}

	/**
	 * @param AcceptanceTester $I
	 * @throws Exception
	 * @since 2.1.0
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param CategorySteps $client
	 * @since 2.1.0
	 */
	public function createCategory(CategorySteps $client)
	{
		$client->wantTo('Test Category creation in Administrator');
		$client->wantTo('Create a Category');
		$client->addNewItem($this->dataCategory);
	}

	/**
	 * Function to Test Discount Product Creation in Backend
	 * @param DiscountProductSteps $client
	 * @since 2.1.0
	 */
	public function createDiscountSave(DiscountProductSteps $client)
	{
		$client->addDiscountProductSave(
			$this->productPrice,
			$this->condition,
			$this->type,
			$this->discountAmount,
			$this->startDate,
			$this->endDate,
			$this->dataCategory['name'],
			$this->groupName
		);
	}

	/**
	 * Function check cancel button
	 * @param DiscountProductSteps $client
	 * @since 2.1.0
	 */
	public function addDiscountProductCancelButton(DiscountProductSteps $client)
	{
		$client->addDiscountProductCancelButton();
		$client->see(DiscountProductPage::$namePage, DiscountProductPage::$selectorPageTitle);
	}

	/**
	 * Function add discount product missing amount
	 * @param DiscountProductSteps $client
	 * @since 2.1.0
	 */
	public function addDiscountProductMissingAmountSaveClose(DiscountProductSteps $client)
	{
		$client->wantTo('Add product discount missing amount then clicks on save and close button');
		$client->addDiscountProductMissingAmountSaveClose(
			$this->productPrice,
			$this->condition,
			$this->type,
			$this->startDate,
			$this->endDate,
			$this->dataCategory['name'],
			$this->groupName
		);
		$client->wantTo('Add product discount missing shopper group then clicks on save and close button');
		$client->addDiscountProductMissingShopperGroupSaveClose(
			$this->productPrice,
			$this->condition,
			$this->type,
			$this->discountAmount,
			$this->startDate,
			$this->endDate,
			$this->dataCategory['name']
		);

		$client->wantTo('Add discount product strt more than end ');
		$client->addDiscountProductStartMoreThanEnd(
			$this->productPrice,
			$this->condition,
			$this->type,
			$this->discountAmount,
			$this->startDate,
			$this->endDate,
			$this->dataCategory['name'],
			$this->groupName
		);
	}

	/**
	 * Function check delete button
	 * @param DiscountProductSteps $client
	 * @since 2.1.0
	 */
	public function checkDeleteButton(DiscountProductSteps $client)
	{
		$client->checkDeleteButton();
		$client->see(DiscountProductPage::$namePage, DiscountProductPage::$selectorPageTitle);
	}

	/**
	 * Function delete all discounts
	 * @param DiscountProductSteps $client
	 * @since 2.1.0
	 */
	public function checkDeleteAll(DiscountProductSteps $client)
	{
		$client->addDiscountToday($this->productPrice, $this->condition, $this->type, $this->discountAmount, $this->dataCategory['name'], $this->groupName);
		$client->checkDeleteAll();
	}
}
