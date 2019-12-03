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
 * @since    2.1.3
 */

class OrderPaymentDiscountAndSpecialDiscountCest
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
	public $zipcode;

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
	public $specialUpdate;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public $extensionURL;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public $pluginName;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public $pluginURL;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public $priceDiscount;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public $type1;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public $type2;

	/**
	 * OrderPaymentDiscountAndSpecialDiscountCest constructor.
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
		$this->specialUpdate = '20';

		//Plugin BankTransfer
		$this->extensionURL   = 'extension url';
		$this->pluginName     = 'RedSHOP - Bank Transfer Payment';
		$this->pluginURL      = 'redSHOP/tests/releases/plugins/';
		$this->package        = 'plg_redshop_payment_rs_payment_banktransfer.zip';
		$this->priceDiscount ='20';
		$this->type1 = 'Total';
		$this->type2 = 'Discount';
	}

	/**
	 * @param AcceptanceTester $I
	 * @throws Exception
	 */

	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}


	public function installPlugin(AdminManagerJoomla3Steps $I, $scenario)
	{
		$I->wantTo("install plugin payment RedSHOP - Bank Transfer Payment");
		$I->pauseExecution();
		$I->installExtensionPackageFromURL($this->extensionURL, $this->pluginURL, $this->package);
		$I->waitForText(AdminJ3Page::$messageInstallPluginSuccess, 120, AdminJ3Page::$idInstallSuccess);
		$I->wantTo('Enable Plugin RedSHOP - Bank Transfer Payment in Administrator');
		$I->enablePlugin($this->pluginName);
		$I = new PluginPaymentManagerJoomla($scenario);
		$I->configCheckoutBankTransferPlugin($this->pluginName, $this->priceDiscount);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.3
	 */
	public function orderPaymentDiscountAndSpecialDiscount (AcceptanceTester $I, $scenario)
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
		$I->wantTo('I want create order and payment discount and special discount');
		$I = new OrderPaymentDiscountAndSpecialDiscountSteps($scenario);
		$I->updatePaymentDiscountAndSpecialDiscount($this->userName, $this->productName, $this->firstName, $this->address, $this->zipcode, $this->city, $this->phone, $this->priceDiscount, $this->specialUpdate, $this->randomProductPrice);

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