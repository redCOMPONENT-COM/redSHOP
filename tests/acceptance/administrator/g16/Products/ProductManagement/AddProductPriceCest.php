<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\UserManagerJoomla3Steps;
use AcceptanceTester\PriceProductManagerJoomla3Steps;
use Faker\Factory;
use Faker\Generator;
/**
 * Class     PriceProductCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    2.1.2
 */
class PriceProductCest
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
	protected $userName;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $password;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $email;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $shopperGroup;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $group;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $firstName;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $lastName;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $categoryName;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $productName;

	/**
	 * @var int
	 * @since 2.1.2
	 */
	protected $productNumber;

	/**
	 * @var float
	 * @since 2.1.2
	 */
	protected $productPrice;

	/**
	 * @var int
	 * @since 2.1.2
	 */
	protected $priceDiscount;

	/**
	 * @var int
	 * @since 2.1.2
	 */
	protected $quantityStart;

	/**
	 * @var int
	 * @since 2.1.2
	 */
	protected $quantityEnd;

	/**
	 * @var int
	 * @since 2.1.2
	 */
	protected $priceDefault;

	/**
	 * @var
	 * @since 2.1.2
	 */
	protected $endDate;

	/**
	 * @var
	 * @since 2.1.2
	 */
	protected $startDate;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $showPriceYes;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $total;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $shippingRate;

	/**
	 * PriceProductCest constructor.
	 * @throws Exception
	 * @since 2.1.2
	 */
	public function __construct()
	{
		$this->faker = Factory::create();
		$this->userName = $this->faker->bothify('user??');
		$this->password = $this->faker->bothify('password##');
		$this->email = $this->faker->email;
		$this->shopperGroup = 'Default Private';
		$this->group = 'Super Users';
		$this->firstName = $this->faker->bothify('First name ?##?');
		$this->lastName = $this->faker->bothify('Last name ?##?');
		$this->categoryName = $this->faker->bothify("Category ?##?");
		$this->productName = $this->faker->bothify('redSHOEMANIAC ##');
		$this->productNumber = $this->faker->numberBetween(999,9999);
		$this->productPrice = 100.0;
		$this->priceDiscount = 40.0;
		$this->quantityStart = 2;
		$this->quantityEnd = 10;
		$this->priceDefault = 100.0;
		$this->startDate;
		$this->endDate = "2019-05-31";
		$this->showPriceYes = 'Yes';
		$this->shippingRate = "DKK 0,00";
		$this->total = 'DKK 150,00';
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
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.2
	 */
	public function addProductPrice(AcceptanceTester $I, $scenario)
	{
		$I->wantToTest("Add new user");
		$I = new UserManagerJoomla3Steps($scenario);
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperGroup, $this->firstName, $this->lastName);

		$I->wantToTest('Create new category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySaveClose($this->categoryName);

		$I->wantToTest('Create new product');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->createProductSaveClose($this->productName, $this->categoryName, $this->productNumber, $this->priceDefault);

		$I->wantToTest('Add product price in product detail');
		$I = new PriceProductManagerJoomla3Steps($scenario);
		$I->addPriceProduct($this->productName, $this->productPrice, $this->quantityStart, $this->quantityEnd, $this->priceDiscount, $this->startDate, $this->endDate);

		$I->wantToTest('Check out product');
		$I = new AcceptanceTester\ProductCheckoutManagerJoomla3Steps($scenario);
		$I->checkoutOnePageWithLogin($this->userName, $this->password, $this->productName, $this->categoryName, $this->shippingRate, $this->total);

		$I->wantTo('Delete Product');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->productName);

		$I->wantTo('Delete Category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->categoryName);

		$I->wantTo('Delete User');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->userName, false);
	}
}