<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\OrderStatusManagerSteps;
use AcceptanceTester\UserManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\CategoryManagerJoomla3Steps;
use CheckoutOnFrontEnd;
use AcceptanceTester\ShippingSteps;

/**
 * Class OrderStatusManagerCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    2.4
 */

class OrderStatusManagerCest
{
	/**
	 * @var \Faker\Generator
	 * @since 2.1.3
	 */
	public $faker;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	public $customerInformation;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public $categoryName;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	public $shipping;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public $shippingMethod;

	public $currencyUnit;

	public $total;

	public function __construct()
	{
		//create user for quotation
		$this->faker           = Faker\Factory::create();
		$this->orderStatusName = $this->faker->bothify('ManageNameStatus ?##?');
		$this->orderStatusCode = $this->faker->bothify('ManageCodeStatus ?##?');
		$this->changeName      = $this->faker->bothify('ManageChangeNameStatus ?##?');

		$this->customerInformation = array(
			"userName"      => $this->faker->userName,
			"email"         => $this->faker->email,
			"firstName"     => $this->faker->firstName,
			"lastName"      => $this->faker->lastName,
			"address"       => $this->faker->address,
			"postalCode"    => "700000",
			"city"          => "HCM",
			"country"       => "Denmark",
			"state"         => "Karnataka",
			"phone"         => '0123456789',
			"shopperGroup"  => 'Default Private',
			'group'         => 'Registered'
		);

		$this->categoryName = $this->faker->bothify('Category Demo ?##?');
		$this->product = array(
			'name'          => $this->faker->bothify('Product Demo ?##?'),
			'number'        => $this->faker->numberBetween(999,9999),
			'price'         => '100'
		);

		$this->shippingMethod = 'redSHOP - Standard Shipping';
		$this->shipping = array(
			'shippingName'          => $this->faker->bothify("Demo Rate ?##?"),
			'shippingRate'         => '10'
		);

		$this->currencyUnit = 'DKK ';
		$this->total = $this->product['price'] + $this->shipping['shippingRate'];
	}
	/**
	 * @param AcceptanceTester $I
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param AcceptanceTester $I
	 *
	 * @param   \Codeception\Scenario $scenario Scenario
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 */
//	public function createEditDeleteOrderStatus(AcceptanceTester $I, $scenario)
//	{
//		$I->wantTo("I want create Order Status");
//		$I = new OrderStatusManagerSteps($scenario);
//		$I->createOrderStatus($this->orderStatusName, $this->orderStatusCode);
//		$I->createOrderStatusMissingName($this->orderStatusCode);
//		$I->createOrderStatusMissingCode($this->orderStatusName);
//
//		$I->wantTo("I want edit Order Status");
//		$I = new OrderStatusManagerSteps($scenario);
//		$I->editOrderStatus($this->orderStatusName, $this->changeName);
//
//		$I->wantTo("I want edit Order Status");
//		$I = new OrderStatusManagerSteps($scenario);
//		$I->changeStatusUnpublish($this->changeName);
//
//		$I->wantTo("I want edit Order Status");
//		$I = new OrderStatusManagerSteps($scenario);
//		$I->changeStatusPublish($this->changeName);
//
//		$I->wantTo("I want to delete Order Status");
//		$I = new OrderStatusManagerSteps($scenario);
//		$I->deleteOrderStatus($this->changeName);
//	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 */
	public function checkoutAndChangeOrderStatus(AcceptanceTester $I, $scenario)
	{
		$I->wantToTest('Create Order Status');
		$I = new OrderStatusManagerSteps($scenario);
		$I->createOrderStatus($this->orderStatusName, $this->orderStatusCode);

		$I->wantToTest('Create User');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->addUser($this->customerInformation['userName'], $this->customerInformation['userName'], $this->customerInformation['email'], $this->customerInformation['group'],
			$this->customerInformation['shopperGroup'], $this->customerInformation['firstName'], $this->customerInformation['lastName']);
		$I->editAddShipping($this->customerInformation['firstName'], $this->customerInformation['lastName'], $this->customerInformation['address'], $this->customerInformation['city'],
			$this->customerInformation['phone'], $this->customerInformation['postalCode']);

		$I->wantToTest("Create Category");
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySaveClose($this->categoryName);

		$I->wantToTest('Create Product');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->createProductSaveClose($this->product['name'], $this->categoryName, $this->product['number'], $this->product['price']);

		$I->wantToTest('Create Shipping rate');
		$I = new ShippingSteps($scenario);
		$I->createShippingRateStandard($this->shippingMethod, $this->shipping);

		$I->wantToTest('Checkout');
		$I = new \AcceptanceTester\ProductCheckoutManagerJoomla3Steps($scenario);
		$I->checkOutProductWithBankTransfer($this->customerInformation, $this->customerInformation, $this->product['name'], $this->categoryName);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.3
	 */
	public function clearAll(AcceptanceTester $I, $scenario)
	{
		$I->wantToTest('Delete User');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->customerInformation['firstName']);

		$I->wantToTest('Delete Product');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->product['name']);

		$I->wantToTest('Delete Category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->categoryName);

		$I->wantToTest('Delete Shipping Rate');
		$I = new ShippingSteps($scenario);
		$I->deleteShippingRate($this->shippingMethod, $this->shipping['name']);

		$I->wantToTest('Delete Order Status');
		$I = new OrderStatusManagerSteps($scenario);
		$I->deleteOrderStatus($this->orderStatusName);
	}
}