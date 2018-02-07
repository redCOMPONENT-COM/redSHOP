<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
use AcceptanceTester\TaxRateSteps;
use AcceptanceTester\TaxGroupSteps;
use AcceptanceTester\CategoryManagerJoomla3Steps as CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductCheckoutManagerJoomla3Steps as ProductCheckoutManagerJoomla3Steps; 
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
		$this->faker        = Faker\Factory::create();
		$this->taxRateName          = 'Testing Tax Rates Groups' . rand(1, 199);
		$this->taxRateNameEdit      = $this->taxRateName . 'Edit';
		$this->taxGroupName         = 'Testing VAT Groups690';
		$this->taxRateValue         = 0.1;
		$this->countryName          = 'United States';
		$this->stateName            = 'Alabama';
		$this->taxRateValueNegative = -1;
		$this->taxRateValueString   = 'Test';
		$this->productName  = $this->faker->bothify('NameProductVAT ?###?');
		$this->categoryName = $this->faker->bothify('CategoryNameVAT ?###?');
		$this->randomProductNumber = $this->faker->bothify('productNumber ?###?');
		$this->randomProductPrice = 100;


		$this->subtotal = "DKK 100,00";
		$this->vatPrice = "DKK 10,00";
		$this->total    = "DKK 110,00";

	}

	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}
	/**
	 * Create VAT Group with
	 *
	 * @param   AcceptanceTester  $client    Current user state.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 */
	public function createVATGroupSave(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('VAT Groups - Save creation in Administrator');
		$client = new TaxGroupSteps($scenario);
		$client->addVATGroupsSave($this->taxGroupName);

		$client->wantTo('Test TAX Rates Save creation in Administrator');
		$client = new TaxRateSteps($scenario);
		$client->addTAXRatesSave($this->taxRateName, $this->taxGroupName, $this->taxRateValue, $this->countryName, $this->stateName);

		$client->wantTo('Create new category ');
		$client = new CategoryManagerJoomla3Steps($scenario);
		$client->addCategorySave($this->categoryName);

		$client->wantTo('Create new product');
		$client = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
		$client->wantTo('I Want to add product inside the category');
		$client->createProductSaveClose($this->productName, $this->categoryName, $this->randomProductNumber, $this->randomProductPrice);
		
		$client= new ProductCheckoutManagerJoomla3Steps($scenario);
		$client->testProductWithVatCheckout($this->productName, $this->categoryName, $this->subtotal, $this->vatPrice, $this->total);
	}


	/**
	 * Test to Verify the Vat Integration
	 *
	 * @param   AcceptanceTester $I        Actor Class Object
	 * @param   String           $scenario Scenario Variable
	 *
	 * @return void
	 */
	public function testProductWithVatCheckout(AcceptanceTester $I, $scenario)
	{
		$I->doAdministratorLogout();
		$I->amOnPage(\FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$I->checkForPhpNoticesOrWarnings();
		$productFrontEndManagerPage = new \FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$productList, 30);
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
