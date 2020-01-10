<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\OrderManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\ShippingSteps;
use AcceptanceTester\UserManagerJoomla3Steps;
use Configuration\ConfigurationSteps;

/**
 * Class ShippingWithOneItemCest
 * @since 2.1.5
 */
class ShippingWithOneItemCest
{
	/**
	 * @var \Faker\Generator
	 * @since 2.1.5
	 */
	public $faker;

	/**
	 * @var string
	 * @since 2.1.5
	 */
	public $categoryName;

	/**
	 * @var array
	 * @since 2.1.5
	 */
	public $product;

	/**
	 * @var array
	 * @since 2.1.5
	 */
	public $customerInformation;

	/**
	 * @var array
	 * @since 2.1.5
	 */
	public $cartSetting;

	/**
	 * @var string
	 * $since 2.1.5
	 */
	public $shippingMethod;

	/**
	 * @var array
	 * @since 2.1.5
	 */
	public $shipping;

	/**
	 * @var string
	 * @since 2.1.5
	 */
	public $paymentMethod;

	/**
	 * @var string
	 * @since 2.1.5
	 */
	public $function;

	/**
	 * ShippingWithOneItemCest constructor.
	 * @since 2.1.5
	 */
	public function __construct()
	{
		$this->faker        = Faker\Factory::create();
		$this->categoryName = $this->faker->bothify("Category Demo ?##?");

		$this->product = array(
			"name"          => $this->faker->bothify("Product Demo ?##?"),
			"number"        => $this->faker->numberBetween(999,9999),
			"price"         => $this->faker->numberBetween(1,990)
		);

		$this->customerInformation = array(
			"email"        => $this->faker->email,
			"firstName"    => $this->faker->firstName,
			"lastName"     => $this->faker->lastName,
			"address"      => $this->faker->address,
			"postalCode"   => "5000",
			"city"         => "HCM",
			"country"      => "Denmark",
			"phone"        => "0909909999",
		);

		$this->cartSetting = array(
			"addCart"          => 'product',
			"allowPreOrder"    => 'yes',
			"cartTimeOut"      => $this->faker->numberBetween(100, 10000),
			"enabledAjax"      => 'no',
			"defaultCart"      => null,
			"buttonCartLead"   => 'Back to current view',
			"onePage"          => 'yes',
			"showShippingCart" => 'no',
			"attributeImage"   => 'no',
			"quantityChange"   => 'yes',
			"quantityInCart"   => 3,
			"minimumOrder"     => 0,
			"enableQuotation"  => 'no'
		);

		$this->shippingMethod = 'redSHOP - Standard Shipping';

		// Shipping info
		$this->shipping = array(
			'shippingName' => $this->faker->bothify('TestingShippingRate ?##?'),
			'zipCodeStart' => '5000',
			'zipCodeEnd'   => '5000',
			'shippingRate' => 10
		);

		$this->paymentMethod = 'RedSHOP - Bank Transfer Payment';
		$this->function      = 'saveclose';
	}

	/**
	 * @param AcceptanceTester $I
	 * @throws Exception
	 * @since 2.1.5
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.5
	 */
	public function checkoutWithShipping(AcceptanceTester $I, $scenario)
	{
		$I->comment('Setting one page checkout');
		$I = new ConfigurationSteps($scenario);
		$I->cartSetting($this->cartSetting);

		$I->comment('Create Category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->categoryName);

		$I->comment("Create Product");
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->createProductSaveClose($this->product['name'], $this->categoryName, $this->product['number'], $this->product['price']);

		$I = new ShippingSteps($scenario);
		$I->comment('Check create new Shipping rate');
		$I->createShippingRateStandard($this->shippingMethod, $this->shipping, $this->function);

		$I->comment("Checkout Shipping when has 1 item shipping in checkout final page");
		$I = new ShippingWithOneItemSteps($scenario);
		$I->ChekoutWithOneItemShippingSteps($this->categoryName, $this->product, $this->customerInformation, $this->shipping, $this->shippingMethod);

		$I->comment('Check Order detail in backend');
		$I = new ShippingWithOneItemSteps($scenario);
		$I->checkOrderShipping($this->customerInformation, $this->shippingMethod, $this->shipping);
	}

	/**
	 * @param ProductManagerJoomla3Steps $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.5
	 */
	public function clearAll(ProductManagerJoomla3Steps $I, $scenario)
	{
		$I->wantToTest('Delete Product');
		$I->deleteProduct($this->product['name']);

		$I->wantToTest('Delete Category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->categoryName);

		$I->wantToTest('Delete User');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->customerInformation['firstName']);

		$I->wantToTest('Delete Shipping Rate');
		$I = new ShippingSteps($scenario);
		$I->deleteShippingRate($this->shippingMethod, $this->shipping['shippingName']);

		$I->wantToTest('Delete Order');
		$I = new OrderManagerJoomla3Steps($scenario);
		$I->deleteOrder($this->customerInformation['firstName']);
	}
}
