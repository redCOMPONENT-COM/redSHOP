<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class ProductVatCheckoutCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class ProductVatCheckoutCest
{
	public function __construct()
	{
		$this->faker = Faker\Factory::create();
		$this->vatGroupName = $this->faker->bothify('ProductVatCheckoutCest ?##?');
		$this->country = 'United States';
		$this->state = 'Alabama';
		$this->taxRate = ".10";
	}

	/**
	 * Test to Verify the Vat Integration
	 *
	 * @param   AcceptanceTester  $I         Actor Class Object
	 * @param   String            $scenario  Scenario Variable
	 *
	 * @return void
	 */
	public function testProductWithVatCheckout(AcceptanceTester $I, $scenario)
	{
		$I = new AcceptanceTester($scenario);

		$I->wantTo('Test to Verify the Vat Integration with product checkout');
		$I->doAdministratorLogin();

		$I->amOnPage('/administrator/index.php?option=com_redshop&view=tax_groups');
		$I->waitForText('VAT / Tax Group Management', 30, ['xpath' => "//h1"]);
		$I->click("New");
		$I->waitForElement(['id' => "jform_name"], 30);
		$I->fillField(['id' => "jform_name"], $this->vatGroupName);
		$I->click(["xpath" => "//input[@name='published' and @value='1']"]);
		$I->click("Save & Close");
		$I->waitForText("VAT Group Detail saved", 10, '.alert-message');
		$I->see("VAT Group Detail saved", '.alert-message');
		$I->click("ID");
		$I->see($this->vatGroupName, ['xpath' => "//div[@id='editcell']/table/tbody/tr[1]"]);
		$I->click(['id' => "cb0"]);
		$I->click("Edit");
		$I->waitForElement(['xpath' => "//div[@id='toolbar-redshop_tax_tax32']/button"], 30);
		$I->click(['xpath' => "//div[@id='toolbar-redshop_tax_tax32']/button"]);
		$I->waitForText("VAT Rates", 30, ['xpath' => "//h1"]);
		$I->click("New");
		$I->selectOptionInChosenByIdUsingJs("tax_country", $this->country);
		$I->selectOptionInChosenByIdUsingJs("tax_state", $this->state);
		$I->fillField(['id' => "tax_rate"], $this->taxRate);
		$I->click("Save & Close");
		$I->waitForText("VAT rate detail saved successfully", 30, '.alert-message');
		$I->see("VAT rate detail saved successfully", '.alert-message');
		$I->amOnPage("/administrator/index.php?option=com_redshop&view=configuration");
		$I->waitForText("Configuration", 30, ['xpath' => "//h1"]);
		$I->click(["link" => "Price"]);
		$I->waitForElement(['id' => 'price_decimal']);
		$I->fillField(['id' => 'price_decimal'], 2);
		$I->executeJS("window.scrollTo(0, 900);");
		// @todo: check why this is not working $I->scrollTo('#default_vat_country', 0, -200);
		$I->wait(1);
		$I->selectOptionInChosenByIdUsingJs("default_vat_country", $this->country);
		$I->selectOptionInChosenByIdUsingJs("default_vat_state", $this->state);
		$I->click(["xpath" => "//div[@id='default_vat_group_chzn']/a"]);
		$I->click(["xpath" => "//div[@id='default_vat_group_chzn']/div/ul/li[text() = '" . $this->vatGroupName . "']"]);
		$I->click("Save & Close");
		$I->waitForText("Configuration Saved", 30, '.alert-message');
		$I->see("Configuration Saved", '.alert-message');
		$I->doAdministratorLogout();
		$productName = 'redCOOKIE';
		$categoryName = 'Events and Forms';
		$I->amOnPage(\FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$categoryDiv,30);
		$I->checkForPhpNoticesOrWarnings();
		$productFrontEndManagerPage = new \FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$productList,30);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->click(\FrontEndProductManagerJoomla3Page::$addToCart);
		$I->waitForText("Product has been added to your cart.", 10, '.alert-message');
		$I->see("Product has been added to your cart.", '.alert-message');
		$I->amOnPage('index.php?option=com_redshop&view=cart');
		$I->checkForPhpNoticesOrWarnings();
		$I->seeElement(['link' => $productName]);
		$I->see("$ 24,00", ['class' => "lc-subtotal"]);
		$I->see("$ 2,40", ['class' => "lc-vat"]);
		$I->see("$ 26,40", ['class' => "lc-total"]);
		$I->doAdministratorLogin();
		$I->amOnPage("/administrator/index.php?option=com_redshop&view=tax_group");
		$I->waitForText('VAT / Tax Group Management', 30, ['xpath' => "//h1"]);
		$I->click("ID");
		$I->see($this->vatGroupName, ['xpath' => "//div[@id='editcell']/table/tbody/tr[1]"]);
		$I->click(['id' => "cb0"]);
		$I->click("Delete");
		$I->waitForText("VAT Group detail deleted successfully", 10, '.alert-message');
		$I->see("VAT Group detail deleted successfully", '.alert-message');
		$I->amOnPage("/administrator/index.php?option=com_redshop&view=configuration");
		$I->waitForText("Configuration", 30, ['xpath' => "//h1"]);
		$I->click(["link" => "Price"]);
		$I->executeJS("window.scrollTo(0, 900);");
		// @todo: check why this is not working $I->scrollTo('#default_vat_country', 0, -200);
		$I->wait(1);
		$I->selectOptionInChosenByIdUsingJs("default_vat_country", "Select");
		$I->click(["xpath" => "//div[@id='default_vat_group_chzn']/a"]);
		$I->click(["xpath" => "//div[@id='default_vat_group_chzn']/div/ul/li[text() = 'Default']"]);
		$I->click("Save & Close");
		$I->waitForText("Configuration Saved", 30, '.alert-message');
		$I->see("Configuration Saved", '.alert-message');
	}
}
