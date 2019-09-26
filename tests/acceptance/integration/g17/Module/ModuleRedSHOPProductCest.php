<?php
/**
 * @package     redSHOP
 * @subpackage  Cest ModuleRedSHOPProduct
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\AdminManagerJoomla3Steps;
use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use Administrator\Module\ModuleManagerJoomla;
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
	 * ModuleRedSHOPProduct constructor.
	 * @since 2.1.3
	 */
	public function __construct()
	{
		$this->faker          = Faker\Factory::create();
		$this->categoryName   = $this->faker->bothify('CategoryName ?###?');
		$this->productName    = $this->faker->bothify('Testing Product ??####?');
		$this->productNumber  = $this->faker->numberBetween(100, 500);
		$this->productPrice   = $this->faker->numberBetween(9, 19);

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
		$I->wantTo('Check Module redSHOP product');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->categoryName);
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->createProductSaveClose($this->productName, $this->categoryName, $this->productNumber, $this->productPrice);
		$I = new ModuleManagerJoomla($scenario);
		$I->configurationRedShopProduct($this->moduleName, $this->categoryName);

		$I->comment('create user');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->addUser($this->userName, $this->password, $this->emailSave, $this->group, $this->shopperGroup, $this->firstName, $this->lastName, $this->function);

		$I->comment('check module redSHOP Products ');
		$I = new redSHOPProductSteps($scenario);
		$I->checkModuleRedSHOPProduct($this->moduleName, $this->productPrice, $this->productName, $this->userName, $this->password);
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

		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->categoryName);

		$I = new ModuleManagerJoomla($scenario);
		$I->unpublishModule($this->moduleName); 
	}
}