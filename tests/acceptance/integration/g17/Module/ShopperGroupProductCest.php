<?php
/**
 * @package     redSHOP
 * @subpackage  Cest ShopperGroupProduct
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Faker\Factory;
use AcceptanceTester\AdminManagerJoomla3Steps;
use Administrator\Module\ModuleManagerJoomla;
use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\UserManagerJoomla3Steps;
use AcceptanceTester\ShopperGroupManagerJoomla3Steps;

/**
 * Class ShopperGroupProductCest
 * @since 2.1.3
 */
class ShopperGroupProductCest
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
	protected $categoryName;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	protected $product;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $position;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	protected $moduleSetting;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	protected $shopperGroup;

	/**
	 * ShopperGroupProductCest constructor.
	 * @since 2.1.3
	 */
	public function __construct()
	{
		$this->faker            = Factory::create();
		$this->categoryName     = $this->faker->bothify('Category Demo ?##?');
		$this->product          = array(
			'name'              => $this->faker->bothify('Product Demo ?##?'),
			'number'            => $this->faker->numberBetween(999,9999),
			'price'             => '100'
		);

		//install module
		$this->extensionURL     = 'extension url';
		$this->moduleName       = 'redSHOP - ShopperGroup Product';
		$this->moduleURL        = 'paid-extensions/tests/releases/modules/site/';
		$this->package          = 'mod_redshop_shoppergroup_product.zip';

		//config module
		$this->position         = 'Right [position-7]';
		$this->moduleSetting    = array(
			'numberOfProduct'   => 3,
			'showImage'         => 'No',
			'showPrice'         => 'Yes',
			'showVAT'           => 'No',
			'showDescription'   => 'No',
			'showReadMore'      => 'No',
			'showAddToCart'     => 'Yes'
		);

		//customer information
		$this->customerInformation = array(
			"userName"          => $this->faker->userName,
			"email"             => $this->faker->email,
			"firstName"         => $this->faker->firstName,
			"lastName"          => $this->faker->lastName,
			"address"           => $this->faker->address,
			"postalCode"        => "700000",
			"city"              => "HCMC",
			"country"           => "Denmark",
			"state"             => "Karnataka",
			"phone"             => '0123456789',
			'group'             => 'Registered'
		);

		//shopper group information
		$this->shopperGroup = array(
			'shopperName'       => $this->faker->bothify('Shopper Group Demo ?##?'),
			'type'              => 'Default Private',
			'customerType'      => 'Company customer',
			'shippingRate'      => $this->faker->numberBetween(1, 100),
			'shippingCheckout'  => $this->faker->numberBetween(1, 100),
			'catalog'           => 'Yes',
			'showPrice'         => 'Yes',
			'idShopperChange'   => '1',
			'shipping'          => 'no',
			'enableQuotation'   => 'yes',
			'showVat'           => 'no',
			'shopperGroupPortal'=> 'no'
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
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.3
	 */
	public function installModule(AcceptanceTester $I, $scenario)
	{
		$I->wantTo("Install Module Shopper Group Product");
		$I = new AdminManagerJoomla3Steps($scenario);
//        $I->installExtensionPackageFromURL($this->extensionURL, $this->moduleURL, $this->package);
//        $I->waitForText(AdminJ3Page::$messageInstallModuleSuccess, 120, AdminJ3Page::$idInstallSuccess);
//		$I->publishModule($this->moduleName);
		$I = new ModuleManagerJoomla($scenario);
		$I->configShopperGroupProduct($this->moduleName, $this->moduleSetting);
		$I->setModulePosition($this->moduleName, $this->position);
		$I->displayModuleOnAllPages($this->moduleName);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.3
	 */
//	public function createData(AcceptanceTester $I, $scenario)
//	{
//		$I->wantToTest('Create Category');
//		$I = new CategoryManagerJoomla3Steps($scenario);
//		$I->addCategorySaveClose($this->categoryName);
//
//		$I->wantToTest('Create Product');
//		$I = new ProductManagerJoomla3Steps($scenario);
//		$I->createProductSaveClose($this->product['name'], $this->categoryName, $this->product['number'], $this->product['price']);
//
//		$I->wantToTest('Create Shopper Group');
//		$I = new ShopperGroupManagerJoomla3Steps($scenario);
//		$I->addShopperGroups($this->shopperGroup['shopperName'], $this->shopperGroup['type'], $this->shopperGroup['customerType'], $this->shopperGroup['shopperGroupPortal'], $this->categoryName, $this->shopperGroup['shipping'],
//			$this->shopperGroup['shippingRate'], $this->shopperGroup['shippingCheckout'], $this->shopperGroup['catalog'], $this->shopperGroup['showVat'], $this->shopperGroup['showPrice'], $this->shopperGroup['enableQuotation'], 'saveclose');
//
//		$I->wantToTest('Create User');
//		$I = new UserManagerJoomla3Steps($scenario);
//		$I->addUser($this->customerInformation['userName'], $this->customerInformation['userName'], $this->customerInformation['email'], $this->customerInformation['group'],
//			$this->shopperGroup['shopperName'], $this->customerInformation['firstName'], $this->customerInformation['lastName'], 'saveclose');
//		$I->editAddShipping($this->customerInformation['firstName'], $this->customerInformation['lastName'], $this->customerInformation['address'],
//			$this->customerInformation['city'], $this->customerInformation['phone'], $this->customerInformation['postalCode']);
//	}
//
//	/**
//	 * @param AcceptanceTester $I
//	 * @param $scenario
//	 * @throws Exception
//	 * @since 2.1.3
//	 */
//	public function clearAll(AcceptanceTester $I, $scenario)
//	{
//		$I->wantToTest('Delete Product');
//		$I = new ProductManagerJoomla3Steps($scenario);
//		$I->deleteProduct($this->product['name']);
//
//		$I->wantToTest('Delete Category');
//		$I = new CategoryManagerJoomla3Steps($scenario);
//		$I->deleteCategory($this->categoryName);
//
//		$I->wantToTest('Delete User');
//		$I = new UserManagerJoomla3Steps($scenario);
//		$I->deleteUser($this->customerInformation['firstName']);
//
//		$I->wantToTest('Delete Shopper Group');
//		$I = new ShopperGroupManagerJoomla3Steps($scenario);
//		$I->deleteShopperGroups($this->shopperGroup['shopperName']);
//	}
}