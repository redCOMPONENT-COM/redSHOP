<?php
/**
 * @package     redSHOP
 * @subpackage  Steps ModuleManagerJoomla
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Administrator\Module;
use AcceptanceTester\AdminManagerJoomla3Steps;
use ModuleManagerJoomlaPage;

/**
 * Class ModuleManagerJoomla
 * @package Administrator\Module
 * @since 2.1.3
 */
class ModuleManagerJoomla extends AdminManagerJoomla3Steps
{
	/**
	 * @param $moduleName
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function configurationCurrent($moduleName)
	{
		$I = $this;
		$I->amOnPage(ModuleManagerJoomlaPage::$URL);
		$I->searchForItem($moduleName);
		$I->waitForElementVisible(ModuleManagerJoomlaPage::$currentConfiguration, 30);
		$I->click(ModuleManagerJoomlaPage::$currentConfiguration);
		$I->waitForText($moduleName, 30, ModuleManagerJoomlaPage::$h2);
		$I->waitForElementVisible(ModuleManagerJoomlaPage::$inputCurrent, 30);
		$I->fillField(ModuleManagerJoomlaPage::$inputCurrent, ModuleManagerJoomlaPage::$currentSelectEuro);
		$I->pressKey(ModuleManagerJoomlaPage::$inputCurrent, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->fillField(ModuleManagerJoomlaPage::$inputCurrent, ModuleManagerJoomlaPage::$currentSelectKorean);
		$I->pressKey(ModuleManagerJoomlaPage::$inputCurrent, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->waitForElementVisible(ModuleManagerJoomlaPage::$saveCloseButton, 30);
		$I->click(ModuleManagerJoomlaPage::$saveCloseButton);
		$I->waitForText(ModuleManagerJoomlaPage::$messageModuleSaved, 30);
	}

	/**
	 * @param $moduleName
	 * @param $option
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function configurationProductTab($moduleName, $option)
	{
		$I = $this;
		$I->amOnPage(ModuleManagerJoomlaPage::$URL);
		$I->searchForItem($moduleName);
		$I->waitForElementVisible(ModuleManagerJoomlaPage::$productTabConfiguration, 30);
		$I->click(ModuleManagerJoomlaPage::$productTabConfiguration);
		$I->waitForText($moduleName, 30, ModuleManagerJoomlaPage::$h2);
		$I->selectOptionInRadioField(ModuleManagerJoomlaPage::$labelAdjustToCategory, $option);
		$I->waitForElementVisible(ModuleManagerJoomlaPage::$saveCloseButton, 30);
		$I->click(ModuleManagerJoomlaPage::$saveCloseButton);
		$I->waitForText(ModuleManagerJoomlaPage::$messageModuleSaved, 30);
	}

	/**
	 * @param $moduleName
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function unpublishModule($moduleName)
	{
		$I = $this;
		$I->amOnPage(ModuleManagerJoomlaPage::$URL);
		$I->searchForItem($moduleName);
		$I->checkAllResults();
		$I->click(ModuleManagerJoomlaPage::$buttonUnpublish);
		$I->waitForText(ModuleManagerJoomlaPage::$messageUnpublishSuccess, 30, ModuleManagerJoomlaPage::$selectorMessage);
	}

	/**
	 * @param $moduleName
	 * @param $moduleConfig
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function configRedMassCart($moduleName, $moduleConfig)
	{
		$I = $this;
		$I->amOnPage(ModuleManagerJoomlaPage::$URL);
		$I->searchForItem($moduleName);
		$I->waitForElementVisible(ModuleManagerJoomlaPage::$redMassCartLink, 30);
		$I->click(ModuleManagerJoomlaPage::$redMassCartLink);
		$module = new ModuleManagerJoomlaPage();

		if (isset($moduleConfig['moduleClassSuffix']))
		{
			$I->waitForElementVisible(ModuleManagerJoomlaPage::$moduleClassSuffix, 30);
			$I->fillField(ModuleManagerJoomlaPage::$moduleClassSuffix, $moduleConfig['moduleClassSuffix']);
		}

		$I->waitForElementVisible(ModuleManagerJoomlaPage::$titleButtonID, 30);
		$I->fillField(ModuleManagerJoomlaPage::$titleButtonID, $moduleConfig['titleButton']);

		if ($moduleConfig['productQuantityBox'] == 'Yes')
		{
			$I->waitForElementVisible($module->productQuantityBox(0), 30);
			$I->click($module->productQuantityBox(0));
		}
		else
		{
			$I->waitForElementVisible($module->productQuantityBox(1), 30);
			$I->click($module->productQuantityBox(1));
		}

		$I->waitForElementVisible(ModuleManagerJoomlaPage::$titleInputBox, 30);
		$I->fillField(ModuleManagerJoomlaPage::$titleInputBox, $moduleConfig['titleInputBox']);
		$I->waitForElementVisible(ModuleManagerJoomlaPage::$saveCloseButton, 30);
		$I->click(ModuleManagerJoomlaPage::$saveCloseButton);
		$I->waitForText(ModuleManagerJoomlaPage::$messageModuleSaved, 30);
	}

	/**
	 * @param $moduleName
	 * @param $productName
	 * @param $productName1
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function configurationRedSHOPProductWithModuleTypeSpecificProduct($moduleName, $productName, $productName1, $number)
	{
		$I = $this;
		$I->amOnPage(ModuleManagerJoomlaPage::$URL);
		$I->searchForItem($moduleName);
		$I->waitForElementVisible(ModuleManagerJoomlaPage::$redShopProductConfiguration, 30);
		$I->click(ModuleManagerJoomlaPage::$redShopProductConfiguration);
		$I->waitForText($moduleName, 30, ModuleManagerJoomlaPage::$h2);

		$I->waitForElementVisible(ModuleManagerJoomlaPage::$productsDisplay, 30);
		$I->fillField(ModuleManagerJoomlaPage::$productsDisplay, $moduleConfig['Products display']);
		$I->selectOptionInRadioField(ModuleManagerJoomlaPage::$labelShowProductPrice, 'Yes');
		$I->click(ModuleManagerJoomlaPage::$moduleType);

		$moduleType = new ModuleManagerJoomlaPage();
		$I->waitForElement($moduleType->moduleXpath($moduleConfig['moduleType']), 30);
		$I->click(ModuleManagerJoomlaPage::$moduleTypeSpecificProducts);

		$I->fillField(ModuleManagerJoomlaPage::$specificProducts, $productName);
		$I->pressKey(ModuleManagerJoomlaPage::$specificProducts, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->fillField(ModuleManagerJoomlaPage::$specificProducts2, $productName1);
		$I->pressKey(ModuleManagerJoomlaPage::$specificProducts2, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);

		$I->waitForElementVisible(ModuleManagerJoomlaPage::$saveCloseButton, 30);
		$I->click(ModuleManagerJoomlaPage::$saveCloseButton);
		$I->waitForText(ModuleManagerJoomlaPage::$messageModuleSaved, 30);
	}

	/**
	 * @param $moduleName
	 * @param $option
	 * @param $moduleConfig
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function configurationRedShopProduct($moduleName, $option, $moduleConfig)
	{
		$I = $this;
		$I->amOnPage(ModuleManagerJoomlaPage::$URL);
		$I->searchForItem($moduleName);
		$I->waitForElementVisible(ModuleManagerJoomlaPage::$redShopProductConfiguration, 30);
		$I->click(ModuleManagerJoomlaPage::$redShopProductConfiguration);
		$I->waitForText($moduleName, 30, ModuleManagerJoomlaPage::$h2);
		$I->selectOptionInRadioField(ModuleManagerJoomlaPage::$labelShowProductPrice, $option);
		$I->waitForElementVisible(ModuleManagerJoomlaPage::$productsDisplay, 30);
		$I->fillField(ModuleManagerJoomlaPage::$productsDisplay, $moduleConfig['Products display']);
		$I->click(ModuleManagerJoomlaPage::$moduleType);

		if ($moduleConfig['moduleType'] == 'Newest')
		{
			$moduleType = new ModuleManagerJoomlaPage();
			$I->waitForElement($moduleType->moduleXpath($moduleConfig['moduleType']), 30);
			$I->click(ModuleManagerJoomlaPage::$moduleTypeNewest);
		}

		if ($moduleConfig['moduleType'] == 'Latest products')
		{
			$moduleType = new ModuleManagerJoomlaPage();
			$I->waitForElement($moduleType->moduleXpath($moduleConfig['moduleType']), 30);
			$I->click(ModuleManagerJoomlaPage::$moduleTypeLatestProducts);
		}

		if ($moduleConfig['moduleType'] == 'Most sold products')
		{
			$moduleType = new ModuleManagerJoomlaPage();
			$I->waitForElement($moduleType->moduleXpath($moduleConfig['moduleType']), 30);
			$I->click(ModuleManagerJoomlaPage::$moduleTypeMostSoldProducts);
		}

		if ($moduleConfig['moduleType'] == 'Random Product')
		{
			$moduleType = new ModuleManagerJoomlaPage();
			$I->waitForElement($moduleType->moduleXpath($moduleConfig['moduleType']), 30);
			$I->click(ModuleManagerJoomlaPage::$moduleTypeRandomProduct);
		}

		if ($moduleConfig['moduleType'] == 'Product on sale')
		{
			$I->selectOptionInRadioField(ModuleManagerJoomlaPage::$labelDiscountPriceLayout);
			$moduleType = new ModuleManagerJoomlaPage();
			$I->waitForElement($moduleType->moduleXpath($moduleConfig['moduleType']), 30);
			$I->click(ModuleManagerJoomlaPage::$moduleTypeProductOnSale);
		}

		if ($moduleConfig['moduleType'] == 'Product On Sale and discount date check')
		{
			$moduleType = new ModuleManagerJoomlaPage();
			$I->waitForElement($moduleType->moduleXpath($moduleConfig['moduleType']), 30);
			$I->click(ModuleManagerJoomlaPage::$moduleTypeProductOnSaleAndDiscountDateCheck);
		}

		if ($moduleConfig['moduleType'] == 'Watched Product')
		{
			$moduleType = new ModuleManagerJoomlaPage();
			$I->waitForElement($moduleType->moduleXpath($moduleConfig['moduleType']), 30);
			$I->click(ModuleManagerJoomlaPage::$moduleTypeWatchedProduct);
		}

		if ($moduleConfig['moduleType'] == 'Ordering')
		{
			$moduleType = new ModuleManagerJoomlaPage();
			$I->waitForElement($moduleType->moduleXpath($moduleConfig['moduleType']), 30);
			$I->click(ModuleManagerJoomlaPage::$moduleTypeOrdering);
		}

		$I->waitForElementVisible(ModuleManagerJoomlaPage::$saveCloseButton, 30);
		$I->click(ModuleManagerJoomlaPage::$saveCloseButton);
		$I->waitForText(ModuleManagerJoomlaPage::$messageModuleSaved, 30);
	}
}