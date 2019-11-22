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
	 * @param $moduleConfig
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function configurationRedSHOPProductWithModuleTypeSpecificProduct($moduleName, $productName, $productName1, $moduleConfig)
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
		$I->selectOptionInChosenjs(ModuleManagerJoomlaPage::$labelModuleType, $moduleConfig['moduleType']);
		$I->fillField(ModuleManagerJoomlaPage::$specificProducts, $productName);
		$I->pressKey(ModuleManagerJoomlaPage::$specificProducts, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->fillField(ModuleManagerJoomlaPage::$specificProducts2, $productName1);
		$I->pressKey(ModuleManagerJoomlaPage::$specificProducts2, \Facebook\WebDriver\WebDriverKeys::ENTER);
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
		$I->waitForText($moduleName, 30);
		$I->waitForElementVisible(ModuleManagerJoomlaPage::$redShopProductConfiguration, 30);
		$I->click(ModuleManagerJoomlaPage::$redShopProductConfiguration);
		$I->waitForText($moduleName, 30);

		$I->waitForElementVisible(ModuleManagerJoomlaPage::$idLabelShowProductPrice, 30);
		$I->scrollTo(ModuleManagerJoomlaPage::$idLabelShowProductPrice);

		$I->selectOptionInRadioField(ModuleManagerJoomlaPage::$labelShowProductPrice, $option);
		$I->waitForElementVisible(ModuleManagerJoomlaPage::$productsDisplay, 30);
		$I->fillField(ModuleManagerJoomlaPage::$productsDisplay, $moduleConfig['Products display']);

		if ($moduleConfig['moduleType'] == 'Newest') 
		{
			$I->selectOptionInChosenjs(ModuleManagerJoomlaPage::$labelModuleType, $moduleConfig['moduleType']);
		}

		if ($moduleConfig['moduleType'] == 'Latest products')
		{
			$I->selectOptionInChosenjs(ModuleManagerJoomlaPage::$labelModuleType, $moduleConfig['moduleType']);;
		}

		if ($moduleConfig['moduleType'] == 'Most sold products')
		{
			$I->selectOptionInChosenjs(ModuleManagerJoomlaPage::$labelModuleType, $moduleConfig['moduleType']);
		}

		if ($moduleConfig['moduleType'] == 'Random Product')
		{
			$I->selectOptionInChosenjs(ModuleManagerJoomlaPage::$labelModuleType, $moduleConfig['moduleType']);
		}

		if ($moduleConfig['moduleType'] == 'Product on sale')
		{
			$I->selectOptionInChosenjs(ModuleManagerJoomlaPage::$labelModuleType, $moduleConfig['moduleType']);
			$I->scrollTo(ModuleManagerJoomlaPage::$showDiscountProductPrice);
			$I->selectOptionInRadioField(ModuleManagerJoomlaPage::$labelDiscountPriceLayout, $option);
		}

		if ($moduleConfig['moduleType'] == 'Product On Sale and discount date check')
		{
			$I->selectOptionInChosenjs(ModuleManagerJoomlaPage::$labelModuleType, $moduleConfig['moduleType']);
		}

		if ($moduleConfig['moduleType'] == 'Watched Product')
		{
			$I->selectOptionInChosenjs(ModuleManagerJoomlaPage::$labelModuleType, $moduleConfig['moduleType']);
		}

		if ($moduleConfig['moduleType'] == 'Ordering')
		{
			$I->selectOptionInChosenjs(ModuleManagerJoomlaPage::$labelModuleType, $moduleConfig['moduleType']);
		}

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
				$I->waitForElementVisible($module->showProductImage(0), 30);
				$I->click($module->showProductImage(0));
			}
			else
			{
				$I->waitForElementVisible($module->showProductImage(1), 30);
				$I->click($module->showProductImage(1));
			}
		}

		if (isset($moduleSetting['imageWidth']))
		{
			$I->waitForElementVisible(ModuleManagerJoomlaPage::$productImageWidth, 30);
			$I->fillField(ModuleManagerJoomlaPage::$productImageWidth, $moduleSetting['imageWidth']);
		}

		if (isset($moduleSetting['imageHeight']))
		{
			$I->waitForElementVisible(ModuleManagerJoomlaPage::$productImageHeight, 30);
			$I->fillField(ModuleManagerJoomlaPage::$productImageHeight, $moduleSetting['imageHeight']);
		}

		if (isset($moduleSetting['showPrice']))
		{
			if ($moduleSetting['showPrice'] == 'Yes')
			{
				$I->waitForElementVisible($module->showProductPrice(0), 30);
				$I->click($module->showProductPrice(0));
			}
			else
			{
				$I->waitForElementVisible($module->showProductPrice(1), 30);
				$I->click($module->showProductPrice(1));
			}
		}

		if (isset($moduleSetting['showVAT']))
		{
			if ($moduleSetting['showVAT'] == 'Yes')
			{
				$I->waitForElementVisible($module->showVAT(0), 30);
				$I->click($module->showVAT(0));
			}
			else
			{
				$I->waitForElementVisible($module->showVAT(1), 30);
				$I->click($module->showVAT(1));
			}
		}

		if (isset($moduleSetting['showDescription']))
		{
			if ($moduleSetting['showDescription'] == 'Yes')
			{
				$I->waitForElementVisible($module->showShortDescription(0), 30);
				$I->click($module->showShortDescription(0));
			}
			else
			{
				$I->waitForElementVisible($module->showShortDescription(1), 30);
				$I->click($module->showShortDescription(1));
			}
		}

		if (isset($moduleSetting['showReadMore']))
		{
			if ($moduleSetting['showReadMore'] == 'Yes')
			{
				$I->waitForElementVisible($module->showReadMore(0), 30);
				$I->click($module->showReadMore(0));
			}
			else
			{
				$I->waitForElementVisible($module->showReadMore(1), 30);
				$I->click($module->showReadMore(1));
			}
		}

		if (isset($moduleSetting['showAddToCart']))
		{
			if ($moduleSetting['showAddToCart'] == 'Yes')
			{
				$I->waitForElementVisible($module->showAddToCart(0), 30);
				$I->click($module->showAddToCart(0));
			}
			else
			{
				$I->waitForElementVisible($module->showAddToCart(1), 30);
				$I->click($module->showAddToCart(1));
			}
		}

		if (isset($moduleSetting['displayPriceLayout']))
		{
			if ($moduleSetting['displayPriceLayout'] == 'Yes')
			{
				$I->waitForElementVisible($module->displayDiscountPrice(0), 30);
				$I->click($module->displayDiscountPrice(0));
			}
			else
			{
				$I->waitForElementVisible($module->displayDiscountPrice(1), 30);
				$I->click($module->displayDiscountPrice(1));
			}
		}

		$I->waitForElementVisible(ModuleManagerJoomlaPage::$saveCloseButton, 30);
		$I->click(ModuleManagerJoomlaPage::$saveCloseButton);
		$I->waitForText(ModuleManagerJoomlaPage::$messageModuleSaved, 30);
	}
}