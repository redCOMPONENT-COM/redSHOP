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
use AcceptanceTester\OrderPaymentDiscountAndSpecialDiscountSteps;
use AcceptanceTester\OrderManagerJoomla3Steps;
use Administrator\plugins\PluginPaymentManagerJoomla;

/**
 * Class OrderPaymentDiscountAndSpecialDiscountCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    2.1.4
 */
class OrderPaymentDiscountAndSpecialDiscountCest
{

	/**
	 * @var \Faker\Generator
	 * @since 2.1.4
	 */
	public $faker;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public $productName;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public $categoryName;

	/**
	 * @var int
	 * @since 2.1.4
	 */
	public $randomProductNumber;

	/**
	 * @var int
	 * @since 2.1.4
	 */
	public $randomProductPrice;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public $userName;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public $password;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public $email;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public $shopperGroup;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public $group;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public $firstName;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public $lastName;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public $address;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public $zipcode;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public $city;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public $phone;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public $specialUpdate;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public $extensionURL;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public $pluginName;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public $pluginURL;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public $priceDiscount;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public $type1;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public $type2;

	/**
	 * OrderPaymentDiscountAndSpecialDiscountCest constructor.
	 * @since 2.1.4
	 */
	public function __construct()
	{
		//Product & Category
		$this->faker                = Faker\Factory::create();
		$this->productName          = $this->faker->bothify('Product Name ?##?');;
		$this->categoryName         = $this->faker->bothify('Category Name ?##?');
		$this->randomProductNumber  = $this->faker->numberBetween(999, 9999);
		$this->randomProductPrice   = 1000;

		//User
		$this->userName     = $this->faker->bothify('ManagerUser ?##?');
		$this->password     = $this->faker->bothify('123456');
		$this->email        = $this->faker->email;
		$this->shopperGroup = 'Default Private';
		$this->group        = 'Super User';
		$this->firstName    = $this->faker->bothify('FirstName FN ?##?');
		$this->lastName     = "LastName";

		//Orders
		$this->address          = '449 Tran Hung Dao';
		$this->zipcode          = '5000';
		$this->city             = 'Ho Chi Minh';
		$this->phone            = '0126541687';
		$this->specialUpdate    = '20';

		//Plugin BankTransfer
		$this->extensionURL     = 'extension url';
		$this->pluginName       = 'redSHOP - Bank Transfer Payment';
		$this->priceDiscount    ='20';
		$this->type1            = 'Total';
		$this->type2            = 'Discount';
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
     * @since 2.1.4
     */
	public function orderPaymentDiscountAndSpecialDiscount (AcceptanceTester $I, $scenario)
	{
        $I->comment('Config discount for redSHOP - Bank Transfer Payment');
		$I = new PluginPaymentManagerJoomla($scenario);
		$I->configCheckoutBankTransferPlugin($this->pluginName, $this->priceDiscount);

		$I->comment('Create order in backend');
		$I->wantTo('Create Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->categoryName);
		$I->wantTo('I Want to add product inside the category');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->createProductSaveClose($this->productName, $this->categoryName, $this->randomProductNumber, $this->randomProductPrice);
		$I->wantTo('Test User creation with save button in Administrator');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperGroup, $this->firstName, $this->lastName);
		$I->wantTo('I want create order and payment discount and special discount');
		$I = new OrderPaymentDiscountAndSpecialDiscountSteps($scenario);
		$I->updatePaymentDiscountAndSpecialDiscount($this->userName, $this->productName, $this->firstName, $this->address, $this->zipcode, $this->city, $this->phone, $this->priceDiscount, $this->specialUpdate, $this->randomProductPrice);

		$I->comment('Delete data');
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

		$I->comment('Return price discount for Bankstranfer');
		$I->wantto("Return the price discount of redSHOP Banks transfer Payment");
		$I = new PluginPaymentManagerJoomla($scenario);
		$I->returnConfigCheckoutBankTransferPlugin($this->pluginName, $this->priceDiscount);
	}
}