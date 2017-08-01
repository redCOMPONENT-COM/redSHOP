<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class ProductCheckoutVatExemptUserCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class ProductCheckoutVatExemptUserCest
{
	public function __construct()
	{
		$this->faker = Faker\Factory::create();
		$this->vatGroupName = $this->faker->bothify('ProductCheckoutVatExemptUserCest ?##?');
		$this->companyName = $this->faker->bothify('ProductCheckoutVatExemptUserCest Company ?##?');
		$this->country = 'United States';
		$this->state = 'Alabama';
		$this->taxRate = ".10";
		$this->userName = $this->faker->bothify('ProductCheckoutVatExemptUserCest ?##?');
		$this->password = 'test';
		$this->email = $this->faker->email;
		$this->shopperGroup = 'Default Private';
		$this->group = 'Administrator';
		$this->firstName = $this->faker->bothify('ProductCheckoutVatExemptUserCest FN ?##?');
		$this->lastName = 'Last';
		$this->userInformation = array(
			"email" => $this->email,
			"firstName" => "Tester",
			"lastName" => "User",
			"address" => "Some Place in the World",
			"postalCode" => "23456",
			"city" => "Bangalore",
			"country" => "India",
			"state" => "Karnataka",
			"phone" => "8787878787"
		);
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

		$I->wantTo('Test to Verify the Vat Integration with product checkout using Tax Exempt user');
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
		$I->waitForElement(['id' => 'default_vat_country']);
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
		$this->createUser($I, $scenario);
		$I->amOnPage(\UserManagerJoomla3Page::$URL);
		$I->click("ID");
		$I->see($this->firstName, \UserManagerJoomla3Page::$firstResultRow);
		$I->click(\UserManagerJoomla3Page::$selectFirst);
		$I->click('Edit');
		$I->waitForElement(['xpath' => "//input[@id='is_company1']"], 30);
		$I->click(['xpath' => "//input[@id='is_company1']"]);
		$I->click(\UserManagerJoomla3Page::$billingInformationTab);
		$I->waitForElement(\UserManagerJoomla3Page::$firstName);
		$I->click(['xpath' => "//input[@id='tax_exempt1']"]);
		$I->click(['xpath' => "//input[@id='requesting_tax_exempt1']"]);
		$I->click(['xpath' => "//input[@id='tax_exempt_approved1']"]);
		$I->fillField(['xpath' => "//input[@name='company_name']"], $this->companyName);
		$I->click(\UserManagerJoomla3Page::$generalUserInformationTab);
		$I->click('Save & Close');
		$I->waitForText(\UserManagerJoomla3Page::$userSuccessMessage,60,'.alert-success');
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
		$I->click(['xpath' => "//img[@class='delete_cart']"]);
		$I->doFrontEndLogin($this->userName, $this->password);
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
		$I->see("$ 24,00", ['class' => "lc-total"]);
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
		$I->waitForElement(['id' => 'price_decimal']);
		$I->fillField(['id' => 'price_decimal'], 2);
		$I->executeJS("window.scrollTo(0, 900);");
		// @todo: check why this is not working $I->scrollTo('#default_vat_country', 0, -200);
		$I->scrollTo(['id' => 'default_vat_country'], 0, -200);
		$I->selectOptionInChosenByIdUsingJs("default_vat_country", "Select");
		$I->click(["xpath" => "//div[@id='default_vat_group_chzn']/a"]);
		$I->click(["xpath" => "//div[@id='default_vat_group_chzn']/div/ul/li[text() = 'Default']"]);
		$I->click("Save & Close");
		$I->waitForText("Configuration Saved", 30, '.alert-message');
		$I->see("Configuration Saved", '.alert-message');
		$this->deleteUser($I, $scenario);
	}

	/**
	 * Function to Test User Creation in Backend
	 *
	 */
	private function createUser(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test User creation in Administrator');
		$I = new AcceptanceTester\UserManagerJoomla3Steps($scenario);
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperGroup, $this->firstName, $this->lastName,'save');
		$I->searchUser($this->firstName);
	}

	/**
	 * Function to Test User Deletion
	 *
	 */
	private function deleteUser(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Deletion of User in Administrator');
		$I = new AcceptanceTester\UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->firstName);
	}
}
