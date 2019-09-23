<?php
/**
 * @package     redSHOP
 * @subpackage  Cest ModuleProductsTab
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\AdminManagerJoomla3Steps;
use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\OrderManagerJoomla3Steps as OrderSteps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\UserManagerJoomla3Steps as UserSteps;
use Administrator\Module\ModuleManagerJoomla;
use Configuration\ConfigurationSteps as ConfigurationSteps;
use Frontend\Module\ProductTabsSteps;

/**
 * Class ModuleProductsTabCest
 * @since 2.1.3
 */
class ModuleProductsTabCest
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
	 * @var string
	 * @since 2.1.3
	 */
	protected $productNewest;

	/**
	 * @var int
	 * @since 2.1.3
	 */
	protected $productNumber;

	/**
	 * @var int
	 * @since 2.1.3
	 */
	protected $productNumber1;

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
	protected $function;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $option;

	/**
	 * ModuleProductsTabCest constructor.
	 * @since 2.1.3
	 */
	public function __construct()
	{
		$this->faker            = Faker\Factory::create();
		$this->categoryName     = $this->faker->bothify('CategoryName ?###?');
		$this->productName      = $this->faker->bothify('Testing Product ??####?');
		$this->productNewest    = $this->faker->bothify('Product ??####?');
		$this->productNumber    = $this->faker->numberBetween(100, 500);
		$this->productNumber1   = $this->faker->numberBetween(501, 999);
		$this->productPrice     = $this->faker->numberBetween(9, 19);

		$this->userName                    = $this->faker->bothify('UserAdministratorCest ?##?');
		$this->password                    = $this->faker->bothify('Password ?##?');
		$this->emailSave                   = $this->faker->email;
		$this->shopperGroup                = 'Default Private';
		$this->group                       = 'Registered';
		$this->firstName                   = $this->faker->bothify('ManageUserAdministratorCest FN ?##?');
		$this->lastName                    = 'Last';
		$this->function                   = 'saveclose';

		//install module
		$this->extensionURL   = 'extension url';
		$this->moduleName     = 'redSHOP - Product Tab Module';
		$this->moduleURL      = 'paid-extensions/tests/releases/modules/site/';
		$this->package        = 'mod_redproducttab.zip';
		$this->option         = 'No';

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
		$I->wantTo("install module Multi Currencies");
		$I->installExtensionPackageFromURL($this->extensionURL, $this->moduleURL, $this->package);
		$I->waitForText(AdminJ3Page::$messageInstallModuleSuccess, 120, AdminJ3Page::$idInstallSuccess);
		$I->publishModule($this->moduleName);
		$I = new ModuleManagerJoomla($scenario);
		$I->configurationProductTab($this->moduleName, $this->option);
		$I->setModulePosition($this->moduleName);
		$I->displayModuleOnAllPages($this->moduleName);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.3
	 */
	public function checkModuleProductTab(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('check Module Products Tab');
		$I->comment('enablePlugin');
		$I->enablePlugin('PayPal');

		$I->comment('create user');
		$I = new UserSteps($scenario);
		$I->addUser($this->userName, $this->password, $this->emailSave, $this->group, $this->shopperGroup, $this->firstName, $this->lastName, $this->function);

		$I->comment('create categories');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->categoryName);

		$I->comment('create products');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->createProductSaveClose($this->productName, $this->categoryName, $this->productNumber, $this->productPrice);
		$I->createProductSaveClose($this->productNewest, $this->categoryName, $this->productNumber1, $this->productPrice);

		$I->comment('setup up one page checkout at admin');
		$I = new ConfigurationSteps($scenario);
		$I->cartSetting($this->cartSetting);

		$I->comment('checkout one product');
		$I = new OrderSteps($scenario);
		$I->addProductToCartWithBankTransfer($this->productName, $this->productPrice, $this->userName , $this->password);

		$I->comment('check module Products Tab');
		$I = new ProductTabsSteps($scenario);
		$I->checkModuleProductTab($this->productName, $this->productNewest);
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
		$I->deleteProduct($this->productNewest);

		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->categoryName);
	}
}