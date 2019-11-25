<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\AdminManagerJoomla3Steps;
use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use Administrator\Module\ModuleManagerJoomla;
use Frontend\Module\ModuleWhoBoughtSteps;

/**
 * Class ModuleWhoBoughtCest
 * @since 2.1.3
 */
class ModuleWhoBoughtCest
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
	 * ModuleWhoBoughtCest constructor.
	 * @since 2.1.3
	 */
	public function __construct()
	{
		$this->faker           = Faker\Factory::create();
		$this->categoryName    = $this->faker->bothify('CategoryName ?###?');
		$this->productName     = $this->faker->bothify('Testing Product ??####?');
		$this->productNumber   = $this->faker->numberBetween(999, 9999);
		$this->productPrice    = $this->faker->numberBetween(1, 999);
		$this->minimumQuantity = $this->faker->numberBetween(1, 10);
		$this->maximumQuantity = $this->faker->numberBetween(11, 100);

		//install module
		$this->extensionURL   = 'extension url';
		$this->moduleName     = 'redSHOP - Who Bought';
		$this->moduleURL      = 'paid-extensions/tests/releases/modules/site/';
		$this->package        = 'mod_redshop_who_bought.zip';
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
		$I->setModulePosition($this->moduleName);
		$I->displayModuleOnAllPages($this->moduleName);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.3
	 */
	public function checkDisplayModuleRedSHOPWhoBought(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Check Module Who Bought');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->categoryName);
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->createProductSaveClose($this->productName, $this->categoryName, $this->productNumber, $this->productPrice);

		$I = new ModuleManagerJoomla($scenario);
		$I->configurationModuleredSHOPWhoBought($this->moduleName, $this->categoryName);

		$I = new ModuleWhoBoughtSteps($scenario);
		$I->checkDisplayModuleWhoBought($this->moduleName, $this->productName, $this->productPrice);
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