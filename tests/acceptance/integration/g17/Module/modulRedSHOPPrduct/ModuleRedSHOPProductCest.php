<?php
/**
 * @package     redSHOP
 * @subpackage  Cest ModuleRedSHOPProduct
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\AdminManagerJoomla3Steps;
use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\CheckoutChangeQuantityProductSteps;
use AcceptanceTester\OrderManagerJoomla3Steps as OrderSteps;
use AcceptanceTester\OrderManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\ProductUpdateOnQuantitySteps;
use AcceptanceTester\UserManagerJoomla3Steps as UserSteps;
use Administrator\Module\ModuleManagerJoomla;
use Configuration\ConfigurationSteps;
use Frontend\Module\redSHOPProductSteps;
use AcceptanceTester\UserManagerJoomla3Steps;

/**
 * Class ModuleRedSHOPProduct
 * @since 2.1.3
 */
class ModuleRedSHOPProductCest
{
	/**
	 * @var \Faker\Generator
	 * @since 2.1.3
	 */
	protected $faker;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $categoryName;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $categoryName1;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $productName1;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $productName2;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $productName3;

	/**
	 * @var int
	 * @since 2.1.3
	 */
	protected $productNumber;

	/**
	 * @var int
	 * @since 2.1.3
	 */
	protected $productNumber2;

	/**
	 * @var int
	 * @since 2.1.3
	 */
	protected $productNumber3;

	/**
	 * @var int
	 * @since 2.1.3
	 */
	protected $productPrice;

	/**
	 * @var int
	 * @since 2.1.3
	 */
	protected $discountPrice;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $minimumPerProduct;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $minimumQuantity;

	/**
	 * @var int
	 * @since 2.1.3
	 */
	protected $maximumQuantity;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $discountStart;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $discountEnd;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $total;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $extensionURL;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $moduleName;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $moduleURL;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $package;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $userName;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $password;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $emailSave;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $shopperGroup;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $group;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $firstName;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $lastName;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $function;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	protected $moduleConfig;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $option;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	protected $cartSetting;

	/**
	 * ModuleRedSHOPProduct constructor.
	 * @since 2.1.3
	 */
	public function __construct()
	{
		$this->faker             = Faker\Factory::create();
		$this->categoryName      = $this->faker->bothify('CategoryName ?###?');
		$this->categoryName1      = $this->faker->bothify('CategoryName1 ?###?');

		//product1
		$this->productName1      = $this->faker->bothify('Testing Product ??####?');
		$this->productNumber1    = $this->faker->numberBetween(100, 500);
		$this->productPrice      = 100;
		$this->discountPrice     = 10;
		$this->minimumPerProduct = '1';
		$this->minimumQuantity   = '1';
		$this->maximumQuantity   = $this->faker->numberBetween(9, 19);
		$this->discountStart     = '25-09-' . date('Y', strtotime('-1 year'));
		$this->discountEnd       = '27-10-' . date('Y', strtotime('+1 year'));
		$this->total             = "DKK 1.000,00";

		//product2
		$this->productName2    = $this->faker->bothify('Testing Product ??####?');
		$this->productNumber2  = $this->faker->numberBetween(100, 500);

		//product3
		$this->productName3    = $this->faker->bothify('Testing Product ??####?');
		$this->productNumber3  = $this->faker->numberBetween(100, 500);

		//install module
		$this->extensionURL   = 'extension url';
		$this->moduleName     = 'redSHOP - Products';
		$this->moduleURL      = 'paid-extensions/tests/releases/modules/site/';
		$this->package        = 'mod_redshop_products.zip';

		$this->userName       = $this->faker->bothify('UserAdministratorCest ?##?');
		$this->password       = $this->faker->bothify('Password ?##?');
		$this->emailSave      = $this->faker->email;
		$this->shopperGroup   = 'Default Private';
		$this->group          = 'Registered';
		$this->firstName      = $this->faker->bothify('First Name FN ?##?');
		$this->lastName       = $this->faker->bothify('LastName FN ?##?');
		$this->function       = 'saveclose';

		$this->moduleConfig      = array(
			'moduleType'         => 'Newest',
			'Products display'   => '3'
		);

		$this->option   = 'Yes';

		$this->cartSetting = array(
			"addCart"           => 'product',
			"allowPreOrder"     => 'no',
			"cartTimeOut"       => 'no',
			"enabledAjax"       => 'no',
			"defaultCart"       => null,
			"buttonCartLead"    => 'Back to current view',
			"onePage"           => 'yes',
			"showShippingCart"  => 'no',
			"attributeImage"    => 'no',
			"quantityChange"    => 'yes',
			"quantityInCart"    => 3,
			"minimumOrder"      => 0,
			"enableQuotation"   => 'no'
		);
	}

	/**
	 * @param AcceptanceTester $I
	 * @throws Exception
	 * @since 2.1.3
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param AdminManagerJoomla3Steps $I
	 * @throws Exception
	 * @since 2.1.3
	 */
	public function installModule(AdminManagerJoomla3Steps $I)
	{
		$I->wantTo("Install Module Multi Currencies");
		$I->installExtensionPackageFromURL($this->extensionURL, $this->moduleURL, $this->package);
		$I->waitForText(AdminJ3Page::$messageInstallModuleSuccess, 120, AdminJ3Page::$idInstallSuccess);
		$I->publishModule($this->moduleName);
		$I->setModulePosition($this->moduleName);
		$I->displayModuleOnAllPages($this->moduleName);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.3
	 */
	public function checkRedShopProduct(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Enable PayPal');
		$I->enablePlugin('PayPal');

		$I->wantTo('Check Module redSHOP product');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->categoryName);
		$I->addCategorySave($this->categoryName1);
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->createProductSaveHaveDiscount($this->productName1, $this->categoryName1, $this->productNumber1, $this->productPrice, $this->discountPrice, $this->minimumPerProduct, $this->minimumQuantity, $this->maximumQuantity, $this->discountStart, $this->discountEnd);
		$I->createProductSaveClose($this->productName2, $this->categoryName1, $this->productNumber2, $this->productPrice);
		$I->createProductSaveClose($this->productName3, $this->categoryName, $this->productNumber3, $this->productPrice);

		$I = new ModuleManagerJoomla($scenario);
		$I->configurationRedShopProduct($this->moduleName, $this->option, $this->moduleConfig);

		$I->comment('check module redSHOP Products ');
		$I = new redSHOPProductSteps($scenario);
		$I->checkModuleRedSHOPProduct($this->moduleName , $this->moduleConfig, $this->productName3 ,$this->productName2);

//		$this->moduleConfig['moduleType'] = 'Product on sale';
//		$I = new ModuleManagerJoomla($scenario);
//		$I->configurationRedShopProduct($this->moduleName, $this->option, $this->moduleConfig);
//
//		$I->comment('check module redSHOP Products ');
//		$I = new redSHOPProductSteps($scenario);
//		$I->checkModuleRedSHOPProduct($this->moduleName , $this->moduleConfig, $this->productName1 ,$this->discountPrice);

		$this->moduleConfig['moduleType'] = 'Most sold products';
		$I = new ModuleManagerJoomla($scenario);
		$I->configurationRedShopProduct($this->moduleName, $this->option, $this->moduleConfig);

		$I = new ConfigurationSteps($scenario);
		$I->cartSetting($this->cartSetting);

		$I->wantTo('Test User creation with save button in Administrator');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->addUser($this->userName, $this->password, $this->emailSave, $this->group, $this->shopperGroup, $this->firstName, $this->lastName);

		$I->wantTo('I want to login in site page');
		$I->doFrontEndLogin($this->userName, $this->password);

		$I->wantTo('I want go to Product tab, Choose Product and Add to cart');
		$I = new CheckoutChangeQuantityProductSteps($scenario);
		$I->checkoutChangeQuantity($this->categoryName, $this->total);

		$this->cartSetting['allowPreOrder'] = 'yes';
		$this->cartSetting['quantityChange'] = 'no';
		$this->cartSetting['quantityInCart'] = 0;
		$this->cartSetting['cartTimeOut'] = $this->faker->numberBetween(100, 10000);

		$I = new ConfigurationSteps($scenario);
		$I->cartSetting($this->cartSetting);

		$I->wantTo('I want to login Site page with user just create');
		$I->doFrontendLogout();

		$I->comment('checkout one product');
		$I = new OrderSteps($scenario);
		$I->addProductToCartWithBankTransfer($this->productName2, $this->productPrice, $this->userName , $this->password);

		$I->comment('check module redSHOP Products ');
		$I = new redSHOPProductSteps($scenario);
		$I->checkModuleRedSHOPProduct($this->moduleName , $this->moduleConfig, $this->productName3 ,$this->productName2);

		$this->moduleConfig['moduleType'] = 'Watched Product';
		$I = new ModuleManagerJoomla($scenario);
		$I->configurationRedShopProduct($this->moduleName,$this->option, $this->moduleConfig);

		$I = new OrderManagerJoomla3Steps($scenario);
		$I->checkReview($this->productName3);
		$I->checkReview($this->productName2);

		$I->comment('check module redSHOP Products ');
		$I = new redSHOPProductSteps($scenario);
		$I->checkModuleRedSHOPProduct($this->moduleName , $this->moduleConfig, $this->productName2 ,$this->productName3);

		$this->moduleConfig['moduleType'] = 'Specific products';
		$I = new ModuleManagerJoomla($scenario);
		$I->configurationRedSHOPProductWithModuleTypeSpecificProduct($this->moduleName, $this->productName2, $this->productName3, $this->moduleConfig, $this->moduleConfig);

		$I->comment('check module redSHOP Products ');
		$I = new redSHOPProductSteps($scenario);
		$I->checkModuleRedSHOPProduct($this->moduleName, $this->moduleConfig, $this->productName2 ,$this->productName3);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.3
	 */
	public function clearAllData(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Delete Data');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->productName1);
		$I->deleteProduct($this->productName2);
		$I->deleteProduct($this->productName3);

		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->categoryName);
		$I->deleteCategory($this->categoryName1);

		$I->comment("I want to delete user");
		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->firstName);

		$I = new ModuleManagerJoomla($scenario);
		$I->unpublishModule($this->moduleName);
	}
}