<?php
/**
 * @package     redSHOP
 * @subpackage  Cest ModuleProductRelated
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\AdminManagerJoomla3Steps;
use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use Administrator\Module\ModuleManagerJoomla;
use Configuration\ConfigurationSteps;
use Frontend\Module\ModuleProductRelatedSteps;

/**
 * Class ModuleProductRelatedCest
 * @since 3.0.2
 */
class ModuleProductRelatedCest
{
	/**
	 * @var \Faker\Generator
	 * @since 3.0.2
	 */
	protected $faker;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $categoryName;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $productName;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $productRelated;

	/**
	 * @var int
	 * @since 3.0.2
	 */
	protected $productNumber;

	/**
	 * @var int
	 * @since 3.0.2
	 */
	protected $productNumberRelated;

	/**
	 * @var int
	 * @since 3.0.2
	 */
	protected $productPrice;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $userName;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $password;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $emailSave;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $shopperGroup;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $group;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $firstName;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $lastName;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $extensionURL;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $moduleName;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $moduleURL;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $package;

	/**
	 * @var array
	 * @since 3.0.2
	 */
	protected $cartSetting;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $function;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $configModule;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $customerInformation;

	/**
	 * ModuleProductRelatedCest constructor.
	 * @since 3.0.2
	 */
	public function __construct()
	{
		$this->faker          = Faker\Factory::create();
		$this->categoryName   = $this->faker->bothify('CategoryName ?###?');
		$this->productName    = $this->faker->bothify('Testing Product ??####?');
		$this->productRelated = $this->faker->bothify('Product ??####?');
		$this->productNumber  = $this->faker->numberBetween(100, 500);
		$this->productNumberRelated = $this->faker->numberBetween(501, 999);
		$this->productPrice   = $this->faker->numberBetween(9, 19);

		$this->userName       = $this->faker->bothify('UserAdministratorCest ?##?');
		$this->password       = $this->faker->bothify('Password ?##?');
		$this->emailSave      = $this->faker->email;
		$this->shopperGroup   = 'Default Private';
		$this->group          = 'Registered';
		$this->firstName      = $this->faker->bothify('ManageUserAdministratorCest FN ?##?');
		$this->lastName       = 'Last';
		$this->function       = 'saveclose';

		//module
		$this->extensionURL   = 'extension url';
		$this->moduleName     = 'redSHOP - Related Products';
		$this->moduleURL      = 'paid-extensions/tests/releases/modules/site/';
		$this->package        = 'mod_redshop_related_products.zip';
		$this->configModule   = array(
		"showProductImage"           => "yes",
		"showProductPrice"           => "yes",
		"showVAT"                    => "yes",
		"showShortDescription"       => "no",
		"showReadMore"               => "yes",
		"showAddToCart"              => "yes",
		"displayDiscountPriceLayout" => "no",
		"displayStockroomStatus"     => "no",
		"showWishlist"               => "no",
		);

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

		$this->customerInformation = array(
			"email"             => $this->faker->email,
			"firstName"         => $this->faker->bothify('firstNameCustomer ?####?'),
			"lastName"          => $this->faker->bothify('lastNameCustomer ?####?'),
			"address"           => "Some Place in the World",
			"postalCode"        => "5000",
			"city"              => "Blangstedgaardsvej 1",
			"country"           => "Denmark",
			"state"             => "Odense SØ",
			"phone"             => "8787878787",
			"shopperGroup"      => 'Default Private',
		);
	}

	/**
	 * @param AcceptanceTester $I
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param AdminManagerJoomla3Steps $I
	 * @param $scenario
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function installAndConfigModule(AdminManagerJoomla3Steps $I, $scenario)
	{
		$I->wantTo("install module Multi Currencies");
		$I->installExtensionPackageFromURL($this->extensionURL, $this->moduleURL, $this->package);
		$I->waitForText(AdminJ3Page::$messageInstallModuleSuccess, 120, AdminJ3Page::$idInstallSuccess);
		$I->publishModule($this->moduleName);
		$I = new ModuleManagerJoomla($scenario);
		$I->configurationModuleProductRelated($this->moduleName, $this->configModule);
		$I->displayModuleOnAllPages($this->moduleName);
		$I->setModulePosition($this->moduleName);
		$I->comment('disable Plugin');
		$I->disablePlugin('PayPal');
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function checkModuleProductRelated(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('check Module Products related');

		$I->comment('create categories');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->categoryName);

		$I->comment('create products');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->createProductSaveClose($this->productName, $this->categoryName, $this->productNumber, $this->productPrice);
		$I->wantTo('Create Product with Related');
		$I->createProductWithRelated($this->productRelated, $this->categoryName, $this->productNumberRelated, $this->productPrice, $this->productName );

		$I->comment('setup up one page checkout at admin');
		$I = new ConfigurationSteps($scenario);
		$I->cartSetting($this->cartSetting);

		$I->comment('check module Products related');

		$I = new ModuleProductRelatedSteps($scenario);
		$I->checkModuleRedSHOPProduct($this->moduleName, $this->categoryName, $this->productName, $this->productRelated, $this->productPrice, $this->customerInformation);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function clearAllData(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Delete Data');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->productName);
		$I->deleteProduct($this->productRelated);

		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->categoryName);

		$I = new ModuleManagerJoomla($scenario);
		$I->unpublishModule($this->moduleName);
	}
}