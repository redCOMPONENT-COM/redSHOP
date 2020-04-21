<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\CategoryManagerJoomla3Steps;
use Configuration\ConfigurationSteps;
use AcceptanceTester\ProductCheckoutManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;

/**
 * Class CheckoutWithStockroomCest
 * @since 3.0.2
 */
class CheckoutWithStockroomCest
{
	/**
	 * @var \Faker\Generator
	 * @since 3.0.2
	 */
	protected $faker;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $randomCategoryName;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $productName;

	/**
	 * @var int
	 * @since 3.0.2
	 */
	protected $productNumber;

	/**
	 * @var int
	 * @since 3.0.2
	 */
	protected $productPrice;

	/**
	 * @var int
	 * @since 3.0.2
	 */
	protected $quantityInStock;

	/**
	 * @var int
	 * @since 3.0.2
	 */
	protected $preOrder;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $subtotal;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $Total;

	/**
	 * CheckoutWithStockroomCest constructor.
	 * @since 3.0.2
	 */
	public function __construct()
	{
		$this->faker = Faker\Factory::create();

		//category information
		$this->randomCategoryName = $this->faker->bothify('TestingCategory ?##');

		//product information
		$this->productName     = $this->faker->bothify('product test ?##');
		$this->productNumber   = rand(999, 9999);
		$this->productPrice    = 24;
		$this->quantityInStock = 1;
		$this->preOrder        = 0;
		$this->subtotal        = "DKK 24,00";
		$this->Total           = "DKK 24,00";
	}

	/**
	 * @param AcceptanceTester $I
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function checkProductInsideStockRoom(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test used Stockroom  in Administrator');
		$I = new ConfigurationSteps($scenario);
		$I->wantTo('Start stockroom ');
		$I->featureUsedStockRoom();
		$I->see(ConfigurationPage::$namePage, ConfigurationPage::$selectorPageTitle);

		$I->wantTo('create category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->randomCategoryName);

		$I->wantTo('create product with stockroom in Administrator');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->createProductInStock($this->productName, $this->productNumber, $this->productPrice, $this->randomCategoryName, $this->quantityInStock, $this->preOrder);

		$I->wantTo('create product with stockroom in Administrator');
		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->checkProductInsideStockRoom($this->productName, $this->randomCategoryName, $this->subtotal, $this->Total);
	}

	/**
	 * Function delete data
	 *
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @since 3.0.2
	 */
	public function clearUp(AcceptanceTester $I, $scenario)
	{
		$I = new ConfigurationSteps($scenario);
		$I->wantTo('Stop stockroom');
		$I->featureOffStockRoom();
	}
}