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
use ProductManagerPage as ProductManagerPage;

/**
 * Class ProductsConfigurationSteps
 * @package Configuration
 * since 2.1.2
 */
class ProductsConfigurationSteps extends AdminManagerJoomla3Steps
{
	/**
	 * @param $productName
	 * @param $category
	 * @param $productNumber
	 * @param $price
	 * @param $nameAttribute
	 * @param $valueAttribute
	 * @param $priceAttribute
	 * @throws \Exception
	 * since 2.1.2
	 */
	public function createProductWithAttributeStockRoom($productName, $category, $productNumber, $price, $nameAttribute, $valueAttribute, $priceAttribute)
	{
		$I = $this;
		$I->amOnPage(\ProductManagerPage::$URL);
		$I->click(ProductManagerPage::$buttonNew);
		$I->waitForElementVisible(ProductManagerPage::$productName, 30);
		$I->fillField(ProductManagerPage::$productName, $productName);
		$I->fillField(ProductManagerPage::$productNumber, $productNumber);
		$I->addValueForField(ProductManagerPage::$productPrice, $price, 6);
		$I->click(ProductManagerPage::$categoryId);
		$I->fillField(ProductManagerPage::$categoryFile, $category);
		$usePage = new ProductManagerPage();
		$I->waitForElement($usePage->returnChoice($category), 30);
		$I->click($usePage->returnChoice($category));
		$I->click(ProductManagerPage::$buttonProductAttribute);
		$I->waitForElement(ProductManagerPage::$attributeTab, 60);
		$I->click(ProductManagerPage::$addAttribute);
		$I->fillField($usePage->addAttributeName(0), $nameAttribute);
		$I->waitForElementVisible($usePage->attributeNameProperty(0),30);
		$I->fillField($usePage->attributeNameProperty(0), $valueAttribute);
		$I->waitForElementVisible($usePage->attributePriceProperty(0), 30);
		$I->fillField($usePage->attributePriceProperty(0), $priceAttribute);
		$I->waitForElementVisible($usePage->attributePreSelect(0),30);
		$I->click($usePage->attributePreSelect(0));
		$I->click(ProductManagerPage::$stockroomTab);
		$I->fillField(ProductManagerPage::$quantityInStock,$valueAttribute);
		$I->click(ProductManagerPage::$buttonSave);
		$I->waitForText(ProductManagerPage::$messageSaveSuccess, 30);
	}

	/**
	 * @param $categoryName
	 * @param $productName
	 * @param $nameAttribute
	 * @throws \Exception
	 * since 2.1.2
	 */
	public function checkProductWithAttributeStockRoomYes($categoryName, $productName, $nameAttribute)
	{
		$I = $this;
		$I->amOnPage(ProductManagerPage::$url);
		$I->waitForText($categoryName,30);
		$I->click($categoryName);
		$I->waitForText($productName,30);
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
	 * since 2.1.2
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
}