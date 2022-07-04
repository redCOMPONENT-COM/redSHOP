<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\UserManagerJoomla3Steps;
use AcceptanceTester\OrderManagerJoomla3Steps;

/**
 * Class OrderBackendProductAttributeCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    2.4.0
 */
class OrderBackendProductAttributeCest
{
	/**
	 * @var \Faker\Generator
	 * @since 2.4.0
	 */
	protected $faker;

	/**
	 * @var string
	 * @since 2.4.0
	 */
	protected $productName;

	/**
	 * @var string
	 * @since 2.4.0
	 */
	protected $categoryName;

	/**
	 * @var int
	 * @since 2.4.0
	 */
	protected $minimumPerProduct;

	/**
	 * @var int
	 * @since 2.4.0
	 */
	protected $minimumQuantity;

	/**
	 * @var int
	 * @since 2.4.0
	 */
	protected $maximumQuantity;

	/**
	 * @var int
	 * @since 2.4.0
	 */
	protected $productNumber;

	/**
	 * @var int
	 * @since 2.4.0
	 */
	protected $price;

	/**
	 * @var string
	 * @since 2.4.0
	 */
	protected $nameAttribute;

	/**
	 * @var string
	 * @since 2.4.0
	 */
	protected $valueAttribute;

	/**
	 * @var int
	 * @since 2.4.0
	 */
	protected $priceAttribute;

	/**
	 * @var string
	 * @since 2.4.0
	 */
	protected $userName;

	/**
	 * @var string
	 * @since 2.4.0
	 */
	protected $password;

	/**
	 * @var string
	 * @since 2.4.0
	 */
	protected $email;

	/**
	 * @var string
	 * @since 2.4.0
	 */
	protected $shopperGroup;

	/**
	 * @var string
	 * @since 2.4.0
	 */
	protected $group;

	/**
	 * @var string
	 * @since 2.4.0
	 */
	protected $firstName;

	/**
	 * @var string
	 * @since 2.4.0
	 */
	protected $lastName;

	/**
	 * @var string
	 * @since 2.4.0
	 */
	protected $address;

	/**
	 * @var string
	 * @since 2.4.0
	 */
	protected $zipcode;

	/**
	 * @var string
	 * @since 2.4.0
	 */
	protected $city;

	/**
	 * @var string
	 * @since 2.4.0
	 */
	protected $phone;

	/**
	 * @var int
	 * @since 2.4.0
	 */
	protected $subtotal;

	/**
	 * OrderBackendProductAttributeCest constructor.
	 * @since 2.4.0
	 */
	public function __construct()
	{
		$this->faker                = Faker\Factory::create();
		$this->productName          = $this->faker->bothify('ProductName ?##?');
		$this->categoryName         = $this->faker->bothify('CategoryName ?##?');
		$this->minimumPerProduct    = 1;
		$this->minimumQuantity      = 1;
		$this->maximumQuantity      = $this->faker->numberBetween(100, 1000);
		$this->productNumber        = $this->faker->numberBetween(999, 9999);
		$this->price                = 100;
		$this->nameAttribute        = 'Size';
		$this->valueAttribute       = "Z";
		$this->priceAttribute       = 12;
		$this->userName             = $this->faker->bothify('ManageUserAdministratorCest ?##?');
		$this->password             = $this->faker->bothify('Password ?##?');
		$this->email                = $this->faker->email;
		$this->shopperGroup         = 'Default Private';
		$this->group                = 'Public';
		$this->firstName            = $this->faker->bothify('First Name ?##?');
		$this->lastName             = 'Last';
		$this->address              = '449 Tran Hung Dao';
		$this->zipcode              = '1';
		$this->city                 = 'Ho Chi Minh';
		$this->phone                = '0123456789';
		$this->subtotal             = $this->price;
	}

	/**
	 * @param AcceptanceTester $I
	 * @throws Exception
	 * @since 2.4.0
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
	 * @since 2.4.0
	 */
	public function OrderBackendProductAttribute(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Create Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySaveClose($this->categoryName);

		$I->wantTo('Create Product with attribute in Administrator');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->createProductWithAttribute($this->productName, $this->categoryName, $this->productNumber, $this->price, $this->nameAttribute, $this->valueAttribute, $this->priceAttribute);

		$I->wantTo('Create User testing in Administrator');
		$I = new AcceptanceTester\UserManagerJoomla3Steps($scenario);
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperGroup, $this->userName, $this->userName, 'saveclose');
		$I->searchUser($this->userName);

		$I->wantTo('Create Order in Administrator');
		$I = new OrderManagerJoomla3Steps($scenario);
		$I->addOrderWithAttribute($this->userName, $this->productName, $this->price, $this->priceAttribute);

		$I->wantTo('Delete Product');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->productName);

		$I->wantTo('Delete Category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->categoryName);

		$I->wantTo('Delete User');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->userName);

		$I->wantTo('Delete Order');
		$I = new OrderManagerJoomla3Steps($scenario);
		$I->deleteOrder($this->userName);
	}
}
