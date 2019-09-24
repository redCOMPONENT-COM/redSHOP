<?php
/**
 * @package     redSHOP
 * @subpackage  Cest ShopperGroupProduct
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Faker\Factory;
use AcceptanceTester\AdminManagerJoomla3Steps;
use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use Administrator\Module\ModuleManagerJoomla;
use Frontend\Module\REDMASSCARTSteps;

/**
 * Class REDMASSCARTCest
 * @since 2.1.3
 */
class REDMASSCARTCest
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
	protected $moduleConfig;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $total;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	protected $customerInformation;

	/**
	 * @var int
	 * @since 2.1.3
	 */
	protected $randomNumber;

	/**
	 * ShopperGroupProductCest constructor.
	 * @since 2.1.3
	 */
	public function __construct()
	{
		$this->faker             = Factory::create();
		$this->randomNumber      = $this->faker->numberBetween(999,9999);
		$this->categoryName      = $this->faker->bothify('Category Demo ?##?');
		$this->product           = array(
			'name'               => $this->faker->bothify('Product Demo ').$this->randomNumber,
			'number'             => $this->randomNumber,
			'price'              => $this->faker->numberBetween(9,99),
			'quantity'           => $this->faker->numberBetween(1,10)
		);

		$this->total = $this->product['price'] * $this->product['quantity'];

		//install module
		$this->extensionURL      = 'extension url';
		$this->moduleName        = 'redSHOP - redMASSCART';
		$this->moduleURL         = 'paid-extensions/tests/releases/modules/site/';
		$this->package           = 'mod_redmasscart.zip';

		//config module
		$this->position          = 'Right [position-7]';
		$this->moduleConfig      = array(
			'titleButton'        => 'Add to cart',
			'productQuantityBox' => 'Yes',
			'titleInputBox'      => 'Product Number'
		);

		//customer information
		$this->customerInformation = array(
			"userName"          => $this->faker->userName,
			"email"             => $this->faker->email,
			"firstName"         => $this->faker->firstName,
			"lastName"          => $this->faker->lastName,
			"address"           => $this->faker->address,
			"postalCode"        => "700000",
			"city"              => "HCM",
			"country"           => "Denmark",
			"state"             => "Karnataka",
			"phone"             => '0123456789',
			'group'             => 'Registered'
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
		$I->installExtensionPackageFromURL($this->extensionURL, $this->moduleURL, $this->package);
		$I->waitForText(AdminJ3Page::$messageInstallModuleSuccess, 120, AdminJ3Page::$idInstallSuccess);
		$I->publishModule($this->moduleName);
		$I = new ModuleManagerJoomla($scenario);
		$I->configRedMassCart($this->moduleName, $this->moduleConfig);
		$I->setModulePosition($this->moduleName, $this->position);
		$I->displayModuleOnAllPages($this->moduleName);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.3
	 */
	public function createData(AcceptanceTester $I, $scenario)
	{
		$I->wantToTest('Create Category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySaveClose($this->categoryName);

		$I->wantToTest('Create Product');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->createProductSaveClose($this->product['name'], $this->categoryName, $this->product['number'], $this->product['price']);

		$I->wantToTest('Checkout with redSHOP - redMASSCART');
		$I = new REDMASSCARTSteps($scenario);
		$I->checkOutRedMassCartModule($this->product['name'], $this->product['number'], $this->product['quantity'],
			$this->moduleConfig['titleButton'], 'yes', $this->customerInformation, $this->customerInformation, $this->total);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.3
	 */
	public function clearAll(AcceptanceTester $I, $scenario)
	{
		$I->wantToTest('Delete Product');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->product['name']);

		$I->wantToTest('Delete Category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->categoryName);

		$I->wantToTest('Unpublish Module');
		$I = new ModuleManagerJoomla($scenario);
		$I->unpublishModule($this->moduleName);
	}
}