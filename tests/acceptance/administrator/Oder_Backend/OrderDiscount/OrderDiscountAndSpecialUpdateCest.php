<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\UserManagerJoomla3Steps;
use AcceptanceTester\OrderUpdateDiscountAndSpecialDiscountSteps;
use AcceptanceTester\OrderManagerJoomla3Steps;

/**
 * Class UpdateDiscountAndSpecialDiscountCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    2.1.3
 */
class OrderDiscountAndSpecialDiscountCest
{
	/**
	 * @var \Faker\Generator
	 * @since 2.1.3
	 */
	public $faker;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public $productName;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public $categoryName;

	/**
	 * @var int
	 * @since 2.1.3
	 */
	public $randomProductNumber;

	/**
	 * @var int
	 * @since 2.1.3
	 */
	public $randomProductPrice;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public $userName;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public $password;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public $email;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public $shopperGroup;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public $group;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public $firstName;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public $lastName;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public $address;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public $zipCode;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public $city;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public $phone;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public $quantity;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public $discountUpdate;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public $specialUpdate;

	/**
	 * OrderDiscountAndSpecialDiscountCest constructor.
	 * @since 2.1.3
	 */
	public function __construct()
	{
		//Product & Category
		$this->faker = Faker\Factory::create();
		$this->productName = $this->faker->bothify('Product Name ?##?');;
		$this->categoryName = $this->faker->bothify('Category Name ?##?');
		$this->randomProductNumber = $this->faker->numberBetween(999, 9999);
		$this->randomProductPrice = 1000;
		//User
		$this->userName = $this->faker->bothify('ManagerUser ?##?');
		$this->password = $this->faker->bothify('123456');
		$this->email = $this->faker->email;
		$this->shopperGroup = 'Default Private';
		$this->group = 'Super User';
		$this->firstName = $this->faker->bothify('FirstName FN ?##?');
		$this->lastName = "LastName";
		//Orders
		$this->address = '449 Tran Hung Dao';
		$this->zipcode = '5000';
		$this->city = 'Ho Chi Minh';
		$this->phone = '0126541687';
		$this->quantity = '1';
		$this->discountUpdate = '10';
		$this->specialUpdate = '10';
	}

	/**
	 * @param AcceptanceTester $I
	 * @since 2.1.2
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @since 2.1.3
	 */
	public function updateDiscountAndSpecialDiscount(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Create Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->categoryName);
		$I->wantTo('I Want to add product inside the category');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->createProductSaveClose($this->productName, $this->categoryName, $this->randomProductNumber, $this->randomProductPrice);
		$I->wantTo('Test User creation with save button in Administrator');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperGroup, $this->firstName, $this->lastName);
		$I->wantTo('I want create order and update discount and special discount');
		$I = new OrderUpdateDiscountAndSpecialDiscountSteps($scenario);
		$I->updateDiscountAndSpecialDiscount($this->userName, $this->productName, $this->firstName, $this->address, $this->zipcode, $this->city, $this->phone, $this->discountUpdate, $this->specialUpdate, $this->randomProductPrice);

		//Detele data
		$I->wantTo('Delete product');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->productName);
		$I->wantTo('Delete Category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->categoryName);
		$I->wantTo('Delete Order just create');
		$I = new OrderManagerJoomla3Steps($scenario);
		$I->deleteOrder($this->firstName);
		$I->wantTo('Delete account in redSHOP and Joomla');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->firstName, false);
	}
}