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
	 * @param array $moduleSetting
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function configShopperGroupProduct($moduleName, $moduleSetting)
	{
		$I = $this;
		$I->amOnPage(ModuleManagerJoomlaPage::$URL);
		$I->searchForItem($moduleName);
		$I->waitForElementVisible(ModuleManagerJoomlaPage::$shopperGroupProduct, 30);
		$I->click(ModuleManagerJoomlaPage::$shopperGroupProduct);
		$module = new ModuleManagerJoomlaPage();

		if (isset($moduleSetting['numberOfProduct']))
		{
			$I->waitForElementVisible(ModuleManagerJoomlaPage::$numberOfProductDisplay, 30);
			$I->fillField(ModuleManagerJoomlaPage::$numberOfProductDisplay, $moduleSetting['numberOfProduct']);
		}

		if (isset($moduleSetting['showImage']))
		{
			if ($moduleSetting['showImage'] == 'Yes')
			{
				$I->waitForElementVisible($module->showProductImage(''), 30);
				$I->click($module->showProductImage(1));
			}
			else
			{
//			    $I->executeJS('jQuery(".fieldset").attr("id","jform_params_image")');
                $I->waitForElementVisible('#jform_params_image', 30);
                $I->executeJS('return jQuery(".fieldset").attr("text","Show product image")');
//				$I->selectOptionInChosenjs('Show product image', 'No');
                $I->click($module->showProductImage(0));
			}
		}
//
//		if (isset($moduleSetting['imageWidth']))
//		{
//			$I->waitForElementVisible(ModuleManagerJoomlaPage::$productImageWidth, 30);
//			$I->fillField(ModuleManagerJoomlaPage::$productImageWidth, $moduleSetting['imageWidth']);
//		}
//
//		if (isset($moduleSetting['imageHeight']))
//		{
//			$I->waitForElementVisible(ModuleManagerJoomlaPage::$productImageHeight, 30);
//			$I->fillField(ModuleManagerJoomlaPage::$productImageWidth, $moduleSetting['imageWidth']);
//		}
//
//		if (isset($moduleSetting['showPrice']))
//		{
//			if ($moduleSetting['showPrice'] == 'Yes')
//			{
//				$I->waitForElementVisible('#jform_params_show_price', 30);
//				$I->checkOption($module->showProductPrice(1));
//			}
//			else
//			{
//				$I->waitForElementVisible($module->showProductPrice(''), 30);
//				$I->checkOption($module->showProductPrice(0));
//			}
//		}
//
//		if (isset($moduleSetting['showVAT']))
//		{
//			if ($moduleSetting['showVAT'] == 'Yes')
//			{
//				$I->waitForElementVisible($module->showVAT(''), 30);
//				$I->checkOption($module->showVAT(1));
//			}
//			else
//			{
//				$I->waitForElementVisible($module->showVAT(''), 30);
//				$I->checkOption($module->showVAT(0));
//			}
//		}
//
//		if (isset($moduleSetting['showDescription']))
//		{
//			if ($moduleSetting['showDescription'] == 'Yes')
//			{
//				$I->waitForElementVisible($module->showShortDescription(''), 30);
//				$I->checkOption($module->showShortDescription(1));
//			}
//			else
//			{
//				$I->waitForElementVisible($module->showShortDescription(''), 30);
//				$I->checkOption($module->showShortDescription(0));
//			}
//		}
//
//		if (isset($moduleSetting['showReadMore']))
//		{
//			if ($moduleSetting['showReadMore'] == 'Yes')
//			{
//				$I->waitForElementVisible($module->showReadMore(''), 30);
//				$I->checkOption($module->showReadMore(1));
//			}
//			else
//			{
//				$I->waitForElementVisible($module->showReadMore(''), 30);
//				$I->checkOption($module->showReadMore(0));
//			}
//		}
//
//		if (isset($moduleSetting['showAddToCart']))
//		{
//			if ($moduleSetting['showAddToCart'] == 'Yes')
//			{
//				$I->waitForElementVisible($module->showAddToCart(''), 30);
//				$I->checkOption($module->showAddToCart(1));
//			}
//			else
//			{
//				$I->waitForElementVisible($module->showAddToCart(''), 30);
//				$I->checkOption($module->showAddToCart(0));
//			}
//		}
//
//		if (isset($moduleSetting['displayPriceLayout']))
//		{
//			if ($moduleSetting['displayPriceLayout'] == 'Yes')
//			{
//				$I->waitForElementVisible($module->displayDiscountPrice(''), 30);
//				$I->checkOption($module->displayDiscountPrice(1));
//			}
//			else
//			{
//				$I->waitForElementVisible($module->displayDiscountPrice(''), 30);
//				$I->checkOption($module->displayDiscountPrice(0));
//			}
//		}
//
//		$I->waitForElementVisible(ModuleManagerJoomlaPage::$saveCloseButton, 30);
//		$I->click(ModuleManagerJoomlaPage::$saveCloseButton);
//		$I->waitForText(ModuleManagerJoomlaPage::$messageModuleSaved, 30);
	}
}