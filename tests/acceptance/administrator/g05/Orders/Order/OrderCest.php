<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\OrderManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\UserManagerJoomla3Steps;

/**
 * Class ManageOrderAdministratorCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class OrderCest
{
	/**
	 * @var \Faker\Generator
	 * @since 1.4.0
	 */
	protected $faker;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $userName;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $password;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $email;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $shopperGroup;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $group;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $firstName;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $updateFirstName;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $lastName;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $randomCategoryName;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $randomProductName;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	protected $minimumPerProduct;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	protected $minimumQuantity;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	protected $maximumQuantity;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $discountStart;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $discountEnd;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	protected $randomProductNumber;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	protected $randomProductPrice;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	protected $quantity;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	protected $newQuantity;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $address;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	protected $zipcode;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $city;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	protected $phone;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $status;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $paymentStatus;

	/**
	 * OrderCest constructor.
	 * @since 1.4.0
	 */
	public function __construct()
	{
		$this->faker           = Faker\Factory::create();
		$this->userName        = $this->faker->bothify('UserOrderCest ?###?');
		$this->password        = $this->faker->bothify('Password ?##?');
		$this->email           = $this->faker->email;
		$this->shopperGroup    = 'Default Private';
		$this->group           = 'Public';
		$this->address         = '14 Phan Ton';
		$this->zipcode         = 7000;
		$this->city            = 'Ho Chi Minh';
		$this->phone           = 010101010;

		$this->randomCategoryName  = 'TestingCategory' . rand(99, 999);
		$this->randomProductName   = 'Testing ProductManagement' . rand(99, 999);
		$this->minimumPerProduct   = 2;
		$this->minimumQuantity     = 3;
		$this->maximumQuantity     = 5;
		$this->discountStart       = "2016-12-12";
		$this->discountEnd         = "2017-05-23";
		$this->randomProductNumber = rand(999, 9999);
		$this->randomProductPrice  = rand(99, 199);

		$this->quantity      = $this->faker->numberBetween(1, 100);
		$this->newQuantity   = $this->faker->numberBetween(100, 300);
		$this->status        = "Confirmed";
		$this->paymentStatus = "Paid";
	}

	/**
	 * @param AcceptanceTester $I
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 *
	 * Function create category for product
	 *
	 * @param AcceptanceTester $I
	 * @param                  $scenario
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function createData(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test User creation in Administrator');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperGroup, $this->userName, $this->userName, 'saveclose');
		$I->searchUser($this->userName);

		$I->wantTo('Create Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->wantTo('Create a Category');
		$I->addCategorySave($this->randomCategoryName);

		$I->wantTo('Test Product Save Manager in Administrator');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductSave($this->randomProductName, $this->randomCategoryName, $this->randomProductNumber, $this->randomProductPrice, $this->minimumPerProduct, $this->minimumQuantity, $this->maximumQuantity, $this->discountStart, $this->discountEnd);


		$I->wantTo('Test Order creation in Administrator');
		$I = new OrderManagerJoomla3Steps($scenario);
		$I->addOrder($this->userName, $this->randomProductName, $this->quantity);

		$I->wantTo('Test Order Edit status and payment in Administrator');
		$I->editOrder($this->userName . ' ' . $this->userName, $this->status, $this->paymentStatus, $this->newQuantity);

		$I->wantTo('Test Order delete by user  in Administrator');
		$I->deleteOrder($this->userName);
	}
}
