<?php
/**
 * @package     redSHOP
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Configuration;
use AcceptanceTester\AdminManagerJoomla3Steps;
use ConfigurationPage;
use Facebook\WebDriver\WebDriverKeys;
use FrontEndProductManagerJoomla3Page;
use ProductManagerPage as ProductManagerPage;

/**
 * Class ProductsConfigurationSteps
 * @package Configuration
 * @since 2.1.2
 */
class ProductsConfigurationSteps extends AdminManagerJoomla3Steps
{
	/**
	 * @param $categoryName
	 * @param $productName
	 * @param $nameAttribute
	 * @throws \Exception
	 * @since 2.1.2
	 */
	public function checkProductWithAttributeStockRoomYes($categoryName, $productName, $nameAttribute)
	{
		$I = $this;
		$I->amOnPage(ProductManagerPage::$url);
		$I->waitForText($categoryName, 30);
		$I->click($categoryName);
		$I->waitForText($productName, 30);
		$I->click($productName);
		$I->waitForText($productName);
		$I->see($nameAttribute);
		$I->waitForText(ConfigurationPage::$messOutOfStockRoom,30);
		$I->see(ConfigurationPage::$messOutOfStockRoom);
	}

	/**
	 * @param $categoryName
	 * @param $productName
	 * @param $nameAttribute
	 * @throws \Exception
	 * @since 2.1.2
	 */
	public function checkProductWithAttributeStockRoomNo($categoryName, $productName, $nameAttribute)
	{
		$I = $this;
		$I->amOnPage(ProductManagerPage::$url);
		$I->waitForText($categoryName,30);
		$I->click($categoryName);
		$I->waitForText($productName,30);
		$I->click($productName);
		$I->dontSee($nameAttribute);
	}

	/**
	 * @throws \Exception
	 * @since 2.1.2
	 */
	public function configurationProductAccessory($function)
	{
		$I = $this;
		$I->amOnPage(ConfigurationPage::$URL);
		$I->waitForElementVisible(ConfigurationPage::$productTab, 30);
		$I->click(ConfigurationPage::$productTab);
		$I->waitForElementVisible(ConfigurationPage::$productAccessory, 30);
		$I->click(ConfigurationPage::$productAccessory);

		switch ($function) {
			case 'Yes':
				$I->waitForElementVisible(ConfigurationPage::$enableAccessoryYes, 30);
				$I->click(ConfigurationPage::$enableAccessoryYes);
				break;
			case 'No':
				$I->waitForElementVisible(ConfigurationPage::$enableAccessoryNo, 30);
				$I->click(ConfigurationPage::$enableAccessoryNo);
				break;
		}

		$I->click(ConfigurationPage::$buttonSaveClose);
		$I->assertSystemMessageContains(ConfigurationPage::$messageSaveSuccess);
	}

	/**
	 * @param $categoryName
	 * @param $productName
	 * @param $productNameRelated
	 * @param $function
	 * @throws \Exception
	 * @since 2.1.2
	 */
	public function checkConfigurationProductRelated($categoryName, $productName, $productNameRelated, $function)
	{
		$I = $this;
		$I->amOnPage(ProductManagerPage::$url);
		$I->waitForText($categoryName, 30);
		$I->click($categoryName);
		$I->waitForText($productNameRelated, 30);
		switch ($function)
		{
			case 'Yes':
				$I->click($productNameRelated);
				$I->waitForText($productName, 30);
				$I->see($productName);
				$I->click($productName);
				$I->waitForText($productNameRelated, 30);
				$I->see($productNameRelated);
				$I->see(FrontEndProductManagerJoomla3Page::$messageRelated);
				break;
			case 'No':
				$I->click($productNameRelated);
				$I->waitForText($productName, 30);
				$I->see($productName);
				$I->click($productName);
				$I->dontSee(FrontEndProductManagerJoomla3Page::$messageRelated);
				break;
		}
	}

	/**
	 * @param $categoryName
	 * @param $productParent
	 * @param $function
	 * @throws \Exception
	 * @since 2.1.4
	 */
	public function checkProductConfigPurchaseParent($categoryName, $productParent, $function)
	{
		$I = $this;
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->click($productFrontEndManagerPage->product($productParent));

		switch ($function)
		{
			case 'Yes':
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$addToCart, 30);
				$I->click(FrontEndProductManagerJoomla3Page::$addToCart);
				$I->waitForText(FrontEndProductManagerJoomla3Page::$alertSuccessMessage, 30, FrontEndProductManagerJoomla3Page::$selectorMessage);
				$I->amOnPage(FrontEndProductManagerJoomla3Page::$cartPageUrL);
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$tableCart, 30);
				$I->waitForText($productParent, 30, FrontEndProductManagerJoomla3Page::$columnProduct);
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$buttonEmptyCart, 30);
				$I->click(FrontEndProductManagerJoomla3Page::$buttonEmptyCart);
				break;

			case 'No':
				$I->waitForElementNotVisible(FrontEndProductManagerJoomla3Page::$addToCart, 30);
				break;
		}
	}

	/**
	 * @param $categoryName
	 * @param $productName
	 * @param $function
	 * @throws \Exception
	 * @since 3.0.2
	 */
	public function checkProductConfigDiscontinue($categoryName, $productName, $function)
	{
		$I = $this;
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$productList, 30);

		switch ($function)
		{
			case 'Yes':
				$I->waitForElementVisible($productFrontEndManagerPage->product($productName), 30);
				$I->click($productFrontEndManagerPage->product($productName));
				$I->waitForElementNotVisible(FrontEndProductManagerJoomla3Page::$addToCart, 30);
				break;

			case 'No':
				$I->waitForElementNotVisible($productFrontEndManagerPage->product($productName), 30);
				break;
		}
	}

	/**
	 * @param $productLayout
	 * @throws \Exception
	 * @since 3.0.2
	 */
	public function configProductLayout($productLayout)
	{
		$I = $this;
		$I->amOnPage(ConfigurationPage::$URL);
		$I->waitForElementVisible(ConfigurationPage::$productTab, 30);
		$I->click(ConfigurationPage::$productTab);
		$config = new ConfigurationPage();

		if (isset($productLayout['defaultTemplate']))
		{
			$I->waitForElement(ConfigurationPage::$defaultProductTemplate, 30);
			$I->selectOption(ConfigurationPage::$defaultProductTemplate, $productLayout['defaultTemplate']);
		}

		if (isset($productLayout['defaultSorting']))
		{
			$I->waitForElementVisible(ConfigurationPage::$defaultSort, 30);
			$I->click(ConfigurationPage::$defaultSort);
			$I->fillField(ConfigurationPage::$inputDefaultSort,$productLayout['defaultSorting']);
			$I->pressKey(ConfigurationPage::$inputDefaultSort, WebDriverKeys::ENTER);
		}

		if (isset($productLayout['displayOutOfStockAfterNormal']))
		{
			switch ($productLayout['displayOutOfStockAfterNormal'])
			{
				case 'Yes':
					$I->waitForElementVisible($config->displayOutOfStockAfterNormal(1), 30);
					$I->click($config->displayOutOfStockAfterNormal(1));
					break;

				case 'No':
					$I->waitForElementVisible($config->displayOutOfStockAfterNormal(0), 30);
					$I->click($config->displayOutOfStockAfterNormal(0));
					break;
			}
		}
	}

	/**
	 * @param $categoryName
	 * @param $productNormal
	 * @param $productOutOfStock
	 * @throws \Exception
	 * @since 3.0.2
	 */
	public function checkDisplayOutOfStockAfterNormal($categoryName, $productNormal, $productOutOfStock)
	{
		$I = $this;
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->waitForText($productNormal, 10, FrontEndProductManagerJoomla3Page::$productFirst);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$addToCart, 30, FrontEndProductManagerJoomla3Page::$productFirst);
		$I->waitForText($productOutOfStock, 10, FrontEndProductManagerJoomla3Page::$productSecond);
	}
}
