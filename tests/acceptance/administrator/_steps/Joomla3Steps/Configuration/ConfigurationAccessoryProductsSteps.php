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
use FrontEndProductManagerJoomla3Page;
use ProductManagerPage;

class ConfigurationAccessoryProductsSteps extends AdminManagerJoomla3Steps
{
	/**
	 * @throws \Exception
	 * since 2.1.2
	 */
	public function configurationProductAccessoryYes()
	{
		$I = $this;
		$I->amOnPage(ConfigurationPage::$URL);
		$I->waitForElementVisible(ConfigurationPage::$productTab);
		$I->click(ConfigurationPage::$productTab);
		$I->click(ConfigurationPage::$productAccessory);
		$I->click(ConfigurationPage::$enableAccessoryYes);
		$I->click(ConfigurationPage::$buttonSaveClose);
		$I->assertSystemMessageContains(ConfigurationPage::$messageSaveSuccess);
	}

	/**
	 * @throws \Exception
	 * since 2.1.2
	 */
	public function configurationProductAccessoryNo()
	{
		$I = $this;
		$I->amOnPage(ConfigurationPage::$URL);
		$I->waitForElementVisible(ConfigurationPage::$productTab);
		$I->click(ConfigurationPage::$productTab);
		$I->click(ConfigurationPage::$productAccessory);
		$I->click(ConfigurationPage::$enableAccessoryNo);
		$I->click(ConfigurationPage::$buttonSaveClose);
		$I->assertSystemMessageContains(ConfigurationPage::$messageSaveSuccess);
	}

	/**
	 * @param $categoryName
	 * @param $productName
	 * @param $productNameAccessories
	 * @throws \Exception
     * since 2.1.2
	 */
	public function checkAddProductAccessoryYesToCart($categoryName, $productName, $productNameAccessories)
	{
		$I = $this;
		$I->amOnPage(ProductManagerPage::$url);
		$I->waitForText($categoryName, 30);
		$I->click($categoryName);
		$I->waitForText($productNameAccessories, 30);
		$I->click($productNameAccessories);
		$I->waitForText($productNameAccessories);
		$I->see($productName);
		$I->click(ConfigurationPage::$addAccessory);
		$I->click(FrontEndProductManagerJoomla3Page::$addToCart);
	}

	/**
	 * @param $categoryName
	 * @param $productName
	 * @param $productNameAccessories
	 * @throws \Exception
     * since 2.1.2
	 */
	public function checkAddProductAccessoryNoToCart($categoryName, $productName, $productNameAccessories)
	{
		$I = $this;
		$I->amOnPage(ProductManagerPage::$url);
		$I->waitForText($categoryName, 30);
		$I->click($categoryName);
		$I->waitForText($productNameAccessories, 30);
		$I->click($productNameAccessories);
		$I->waitForText($productNameAccessories, 30);
		$I->see($productName);
		$I->click(ConfigurationPage::$addAccessory);
		$I->click(FrontEndProductManagerJoomla3Page::$addToCart);
		$I->waitForText(FrontEndProductManagerJoomla3Page::$errorAddToCart, 30);
	}
}
