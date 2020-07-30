<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Faker\Factory;
use Faker\Generator;
use AcceptanceTester\CategoryManagerJoomla3Steps as CategorySteps;
use AcceptanceTester\ProductManagerJoomla3Steps as ProductSteps;
use AcceptanceTester\ShopperGroupManagerJoomla3Steps as ShopperGroupSteps;
use AcceptanceTester\UserManagerJoomla3Steps as UserSteps;
use AcceptanceTester\ProductCheckoutManagerJoomla3Steps as FrontEndSteps;

/**
 * Class AddProductPriceCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    2.1.2
 */
class AddProductPriceCest
{
	/**
	 * @var Generator
	 * @since 2.1.2
	 */
	protected $faker;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $productname;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $categoryname;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $shoppergroupname;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $username;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $pass;


	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $email;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $group;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $shoppergroupitem;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $customerType;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $shipping;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $enableQuotation;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $showVat;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $shopperGroupPortal;

	/**
	 * @var int
	 * @since 2.1.2
	 */
	protected $shippingRate;

	/**
	 * @var int
	 * @since 2.1.2
	 */
	protected $shippingCheckout;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $catalog;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $showPrice;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $firstname;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $lastname;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $address;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $postcode;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $city;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $phone;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $discountname;

	/**
	 * @var int
	 * @since 2.1.2
	 */
	protected $number;

	/**
	 * @var int
	 * @since 2.1.2
	 */
	protected $price;

	/**
	 * @var int
	 * @since 2.1.2
	 */
	protected $discountPrice;

	/**
	 * @var int
	 * @since 2.1.2
	 */
	protected $total;

	/**
	 * @var false|string
	 * @since 2.1.2
	 */
	protected $startDate;

	/**
	 * @var false|string
	 * @since 2.1.2
	 */
	protected $endDate;

	/**
	 * @var int
	 * @since 2.1.2
	 */
	protected $quantity;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $addprice;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $quantityStart;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $quantityEnd;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $currentcyunit;

	/**
	 * AddProductPriceCest constructor.
	 * @since 2.1.2
	 */
	public function __construct()
	{
		$this->faker = Factory::create();
		$this->productname = $this->faker->bothify('Product demo ##');
		$this->categoryname = $this->faker->bothify('Category demo ##');
		$this->shoppergroupname = $this->faker->bothify('VIP ##');
		$this->username = $this->faker->bothify('test##');
		$this->pass = $this->faker->bothify('???###?#');
		$this->email = $this->faker->email;
		$this->group = 'Administrator';
		$this->shoppergroupitem = $this->faker->bothify('Default Private');
		$this->customerType = 'Company customer';
		$this->shipping = 'no';
		$this->enableQuotation = 'yes';
		$this->showVat = 'no';
		$this->shopperGroupPortal = 'no';
		$this->shippingRate = $this->faker->numberBetween(1, 100);
		$this->shippingCheckout = $this->faker->numberBetween(1, 100);
		$this->catalog = 'Yes';
		$this->showPrice = 'Yes';
		$this->firstname = $this->faker->firstName;
		$this->lastname = $this->faker->lastName;
		$this->address = $this->faker->address;
		$this->postcode = '2000';
		$this->city = 'Ho Chi Minh City';
		$this->phone = $this->faker->phoneNumber;
		$this->number = $this->faker->numberBetween(50, 1000);
		$this->price = $this->faker->numberBetween(100, 1000);
		$this->addprice = '50';
		$this->quantityStart = '2';
		$this->quantityEnd = '10';
		$this->discountPrice = '40';
		$this->startDate = date('Y-m-d');
		$this->endDate = date('Y-m-d', strtotime('+2 day', strtotime($this->startDate)));
		$this->currentcyunit = 'DKK ';
		$this->quantity = $this->faker->numberBetween(2, 10);
		$this->total = $this->quantity * $this->discountPrice;
	}

	/**
	 * @param AcceptanceTester $I
	 * @throws Exception
	 * @since 2.1.2
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param CategorySteps $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.2
	 */
	public function createPrice(CategorySteps $I, $scenario)
	{
		$I->wantToTest("I want to create category parent");
		$I->addCategorySaveClose($this->categoryname);

		$I = new ShopperGroupSteps($scenario);
		$I->wantToTest("I want to create shopper group");
		$I->addShopperGroups($this->shoppergroupname, $this->shoppergroupitem, $this->customerType, $this->shopperGroupPortal,$this->categoryname,$this->shipping, $this->shippingRate, $this->shippingCheckout, $this->catalog, $this->showVat,$this->showPrice,$this->enableQuotation, 'saveclose');

		$I =new ProductSteps($scenario);
		$I->wantToTest("I want to create product with category child");
		$I->createProductWithAddPrice($this->productname, $this->categoryname, $this->number, $this->price, $this->shoppergroupname, $this->addprice, $this->quantityStart, $this->quantityEnd, $this->discountPrice, $this->startDate, $this->endDate);

		$I = new UserSteps($scenario);
		$I->wantToTest("I want to create user");
		$I->addUser($this->username, $this->pass, $this->email, $this->group, $this->shoppergroupname, $this->firstname, $this->lastname, 'saveclose');
		$I->editAddShipping($this->firstname, $this->pass, $this->address, $this->city, $this->phone, $this->postcode);

		$I = new FrontEndSteps($scenario);
		$I->wantToTest("I want to check price of product on frontend");
		$I->doFrontEndLogin($this->username, $this->pass);
		$I->checkoutProductwithAddPrice($this->productname, $this->categoryname, $this->discountPrice, $this->quantity, $this->currentcyunit.$this->total);
	}

	/**
	 * @param ProductSteps $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.2
	 */
	public function deleteAll(UserSteps $I, $scenario)
	{
		$I->wantToTest("I want to delete user");
		$I->deleteUser($this->firstname);
		$I = new ShopperGroupSteps($scenario);
		$I->wantToTest("I want to delete shopper group");
		$I->deleteShopperGroups($this->shoppergroupname);
		$I = new ProductSteps($scenario);
		$I->wantToTest("I want to delete product");
		$I->deleteProduct($this->productname);
		$I = new CategorySteps($scenario);
		$I->wantToTest("I want to delete category");
		$I->deleteCategory($this->categoryname);
	}
}
