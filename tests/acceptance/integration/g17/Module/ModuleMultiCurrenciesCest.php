<?php
/**
 * @package     redSHOP
 * @subpackage  Cest ModuleMultiCurrencies
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\AdminManagerJoomla3Steps;
use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use Administrator\Module\ModuleManagerJoomla;
use Frontend\Module\MultiCurrenciesSteps;

/**
 * Class ModuleMultiCurrenciesCest
 * @since 2.1.3
 */
class ModuleMultiCurrenciesCest
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
	 * @var int
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
	protected $curentEuro;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $curentKorean;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $priceEuro;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $price1Korean;

	/**
	 * ModuleMultiCurrenciesCest constructor.
	 * @since 2.1.3
	 */
	public function __construct()
	{
		$this->faker            = Faker\Factory::create();
		$this->categoryName     = $this->faker->bothify('CategoryName ?###?');
		$this->productName      = $this->faker->bothify('Testing Product ??####?');
		$this->productNumber    = $this->faker->numberBetween(999, 9999);
		$this->productPrice     = $this->faker->numberBetween(999, 9999);
		$this->minimumQuantity  = $this->faker->numberBetween(1, 10);
		$this->maximumQuantity  = $this->faker->numberBetween(11, 100);

		//install module
		$this->extensionURL   = 'extension url';
		$this->moduleName     = '\Redshop Multi Currencies';
		$this->moduleURL      = 'paid-extensions/tests/releases/modules/site/';
		$this->package        = 'mod_redshop_currencies.zip';

		//currency
		$this->curentEuro     = 'Euro';
		$this->curentKorean   = '(South) Korean Won';

		//price
		$this->priceEuro      = 'EUR';
		$this->price1Korean   = 'KRW';
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
		$I->wantTo("Install Module Multi Currencies");
		$I->installExtensionPackageFromURL($this->extensionURL, $this->moduleURL, $this->package);
		$I->waitForText(AdminJ3Page::$messageInstallModuleSuccess, 120, AdminJ3Page::$idInstallSuccess);
		$I->publishModule($this->moduleName);
		$I = new ModuleManagerJoomla($scenario);
		$I->configurationCurrent($this->moduleName);
		$I->setModulePosition($this->moduleName);
		$I->displayModuleOnAllPages($this->moduleName);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.3
	 */
	public function checkModuleMultiCurrencies(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Check Module Currencies');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->categoryName);
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->createProductSaveClose($this->productName, $this->categoryName, $this->productNumber, $this->productPrice);
		$I = new MultiCurrenciesSteps($scenario);
		$I->checkModuleCurrencies($this->categoryName, $this->productName, $this->curentEuro, $this->priceEuro, $this->curentKorean, $this->price1Korean);
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