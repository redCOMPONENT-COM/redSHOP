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
     * @param $categoryName
     * @throws \Exception
     * @since 2.1.3
     */
    public function configurationRedShopProduct($moduleName)
    {
        $I = $this;
        $I->amOnPage(ModuleManagerJoomlaPage::$URL);
        $I->searchForItem($moduleName);
        $I->waitForElementVisible(ModuleManagerJoomlaPage::$redShopProductConfiguration, 30);
        $I->click(ModuleManagerJoomlaPage::$redShopProductConfiguration);
        $I->waitForText($moduleName, 30, ModuleManagerJoomlaPage::$h2);
        $I->chooseOnSelect2(ModuleManagerJoomlaPage::$moduleType,  'Latest products');



//        if ($moduleType)
//        {
//
//        }
//
//        if ($moduleType)
//        {
//
//        }
//
//        if ($moduleType)
//        {
//
//        }
//
//        if ($moduleType)
//        {
//
//        }
//
//        if ($moduleType)
//        {
//
//        }
//
//        if ($moduleType)
//        {
//
//        }
//
//        if ($moduleType)
//        {
//
//        }
//
//        if ($moduleType)
//        {
//
//        }









//        $I->waitForElementVisible(ModuleManagerJoomlaPage::$inputCategories, 30);
//        $I->fillField(ModuleManagerJoomlaPage::$inputCategories, $categoryName);
//        $I->pressKey(ModuleManagerJoomlaPage::$inputCategories, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
//        $I->waitForElementVisible(ModuleManagerJoomlaPage::$saveCloseButton, 30);
//        $I->click(ModuleManagerJoomlaPage::$saveCloseButton);
//        $I->waitForText(ModuleManagerJoomlaPage::$messageModuleSaved, 30);
    }
}