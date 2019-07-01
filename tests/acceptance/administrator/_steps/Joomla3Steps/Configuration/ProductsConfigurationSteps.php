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
}