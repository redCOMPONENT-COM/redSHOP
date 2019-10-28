<?php
/**
 * @package     redSHOP
 * @subpackage  Cest ModuleRedSHOPDiscount
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\AdminManagerJoomla3Steps;
use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\DiscountSteps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\ShopperGroupManagerJoomla3Steps as ShopperGroupSteps;
use AcceptanceTester\UserManagerJoomla3Steps as UserSteps;
use AcceptanceTester\UserManagerJoomla3Steps as UserManagerJoomla3Steps;
use Administrator\Module\ModuleManagerJoomla;
use Configuration\ConfigurationSteps as ConfigurationSteps;
use Frontend\Module\redSHOPDiscountSteps;

/**
 * Class ModuleRedSHOPDiscountCest
 * @since 2.1.3
 */
class ModuleRedSHOPDiscountCest
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
	protected $productName;

	/**
	 * @var int
	 * @since 2.1.3
	 */
	protected $productNumber;

	/**
	 * @var int
	 * @since 2.1.3
	 */
	protected $productPrice;

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
	 * @var string
	 * @since 2.1.3
	 */
	protected $userNameDC;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $passwordDC;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $emailSaveDC;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $shopperGroup;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $firstNameDC;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $lastNameDC;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $shopperGroupName;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $shopperGroupItem;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $customerType;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $shopperGroupPortal;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $shipping;

	/**
	 * @var int
	 * @since 2.1.3
	 */
	protected $shippingRate;

	/**
	 * @var int
	 * @since 2.1.3
	 */
	protected $shippingCheckout;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $catalog;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $showVat;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $showPrice;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $enableQuotation;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $saveClose;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $discountName;

	/**
	 * @var int
	 * @since 2.1.3
	 */
	protected $amount;

	/**
	 * @var int
	 * @since 2.1.3
	 */
	protected $discountAmount;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $discountType;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $discountCondition;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $startDate;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $endDate;

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
	 * @var array
	 * @since 2.1.3
	 */
	protected $cartSetting;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $functionHaveDiscount;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $functionDontHaveDiscount;

	/**
	 * ModuleRedSHOPDiscountCest constructor.
	 * @since 2.1.3
	 */
	public function __construct()
	{
		$this->faker          = Faker\Factory::create();
		$this->categoryName   = $this->faker->bothify('CategoryName ?###?');
		$this->productName    = $this->faker->bothify('Testing Product ??####?');
		$this->productNumber  = $this->faker->numberBetween(100, 500);
		$this->productPrice   = $this->faker->numberBetween(51, 150);

		$this->userName       = $this->faker->bothify('UserAdministratorCest ?##?');
		$this->password       = $this->faker->bothify('Password ?##?');
		$this->emailSave      = $this->faker->email;
		$this->group          = 'Registered';
		$this->firstName      = $this->faker->bothify('ManageUserAdministratorCest FN ?##?');
		$this->lastName       = $this->faker->bothify('ManageUserAdministratorCest FN ?##?');
		$this->function       = 'saveclose';

		//User have shopper group discount
		$this->userNameDC         = $this->faker->bothify('User Name ?##?');
		$this->passwordDC         = $this->faker->bothify('password ?##?');
		$this->emailSaveDC        = $this->faker->email;
		$this->shopperGroup       = 'Default Private';
		$this->firstNameDC        = $this->faker->bothify('First Name FN ?##?');
		$this->lastNameDC         = $this->faker->bothify('Last Name FN ?##?');

		//shopper group
		$this->shopperGroupName   = $this->faker->bothify('VIP ##');
		$this->shopperGroupItem   = $this->faker->bothify('Default Private');
		$this->customerType       = 'Company customer';
		$this->shopperGroupPortal = 'no';
		$this->shipping           = 'no';
		$this->shippingRate       = $this->faker->numberBetween(1, 100);
		$this->shippingCheckout   = $this->faker->numberBetween(1, 100);
		$this->catalog            = 'Yes';
		$this->showVat            = 'no';
		$this->showPrice          = 'Yes';
		$this->enableQuotation    = 'yes';
		$this->saveClose          = 'saveclose';

		//discount
		$this->discountName       = 'Discount' . rand(1, 100);
		$this->amount             = 150;
		$this->discountAmount     = 50;
		$this->discountType       = 'Total';
		$this->discountCondition  = 'Lower';
		$dateNow                  = date('Y-m-d');
		$this->startDate          = $dateNow;
		$this->endDate            = date('Y-m-d', strtotime('+2 day', strtotime($dateNow)));

		//install module
		$this->extensionURL       = 'extension url';
		$this->moduleName         = 'redSHOP - Discount';
		$this->moduleURL          = 'paid-extensions/tests/releases/modules/site/';
		$this->package            = 'mod_redshop_discount.zip';

		$this->cartSetting = array(
			"addCart"           => 'product',
			"allowPreOrder"     => 'yes',
			"cartTimeOut"       => $this->faker->numberBetween(100, 10000),
			"enabledAjax"       => 'no',
			"defaultCart"       => null,
			"buttonCartLead"    => 'Back to current view',
			"onePage"           => 'yes',
			"showShippingCart"  => 'no',
			"attributeImage"    => 'no',
			"quantityChange"    => 'no',
			"quantityInCart"    => 0,
			"minimumOrder"      => 0,
			"enableQuotation"   => 'no'
		);
		$this->functionDontHaveDiscount      = 'dontHaveHisscount';
		$this->functionHaveDiscount          = 'haveDiscount';
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
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.3
	 */
	public function installModule(AdminManagerJoomla3Steps $I, $scenario)
	{
		$I->wantTo("install module redSHOP Discount");
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
	public function checkModuleRedSHOPDiscount(AcceptanceTester $I, $scenario)
	{
		$I->comment('create categories');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->categoryName);

		$I->comment('create products');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->createProductSaveClose($this->productName, $this->categoryName, $this->productNumber, $this->productPrice);

		$I->comment('setup up one page checkout at admin');
		$I = new ConfigurationSteps($scenario);
		$I->cartSetting($this->cartSetting);

		$I = new ShopperGroupSteps($scenario);
		$I->comment("create shopper group");
		$I->addShopperGroups($this->shopperGroupName, $this->shopperGroupItem, $this->customerType, $this->shopperGroupPortal, $this->categoryName, $this->shipping, $this->shippingRate, $this->shippingCheckout, $this->catalog, $this->showVat, $this->showPrice, $this->enableQuotation, $this->saveClose);

		$I->comment('create user');
		$I = new UserSteps($scenario); 
		$I->addUser($this->userName, $this->password, $this->emailSave, $this->group, $this->shopperGroup, $this->firstName, $this->lastName, $this->function);
		$I->addUser($this->userNameDC, $this->passwordDC, $this->emailSaveDC, $this->group, $this->shopperGroupName, $this->firstNameDC, $this->lastNameDC, $this->function);

		$I = new DiscountSteps($scenario);
		$I->addTotalDiscountSaveClose($this->discountName, $this->amount, $this->discountCondition, $this->discountType, $this->discountAmount, $this->startDate, $this->endDate, $this->shopperGroupName);

		$I = new redSHOPDiscountSteps($scenario);
		$I->comment('check with user dont have discount');
		$I->checkModuleRedSHOPDiscount($this->moduleName, $this->userName, $this->password, $this->functionDontHaveDiscount, $this->discountAmount, $this->categoryName, $this->productName);
		$I->comment('check with user have discount');
		$I->checkModuleRedSHOPDiscount($this->moduleName, $this->userNameDC, $this->passwordDC, $this->functionHaveDiscount, $this->discountAmount, $this->categoryName, $this->productName);
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
		$I->deleteProduct($this->productName);

		$I = new ShopperGroupSteps($scenario);
		$I->deleteShopperGroups($this->shopperGroupName);

		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->categoryName);

		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->firstName);
		$I->deleteUser($this->firstNameDC);

		$I = new ModuleManagerJoomla($scenario);
		$I->unpublishModule($this->moduleName);
	}
}