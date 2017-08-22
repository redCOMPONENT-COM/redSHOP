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

use \AcceptanceTester\CategoryManagerJoomla3Steps;
use \AcceptanceTester\ProductManagerJoomla3Steps;
use \AcceptanceTester\ConfigurationManageJoomla3Steps;
use \AcceptanceTester\TaxGroupSteps;
use \AcceptanceTester\TaxRateSteps;
use \AcceptanceTester\ProductCheckoutManagerJoomla3Steps;
use \AcceptanceTester\UserManagerJoomla3Steps;

class ProductCheckoutVatExemptUserCest
{
	/**
	 * @var \Faker\Generator
	 */
	public  $faker;

	/**
	 * @var string
	 */
	public  $categoryName;

	/**
	 * @var int
	 */
	public  $noPage;

	/**
	 * @var string
	 */
	public  $productName;
	/**
	 * @var int
	 */
	public  $randomProductNumber;
	/**
	 * @var string
	 */
	public  $randomProductPrice;
	/**
	 * @var string
	 */
	public  $minimumPerProduct;
	/**
	 * @var int
	 */
	public  $minimumQuantity;
	/**
	 * @var int
	 */
	public  $maximumQuantity;

	/**
	 * @var string
	 */
	public  $discountStart;

	/**
	 * @var string
	 */
	public  $discountEnd;

	/**
	 * @var string
	 */
	public $taxRateName = '';

	/**
	 * @var string
	 */
	public $taxRateNameEdit = '';

	/**
	 * @var string
	 */
	public $taxGroupName = '';

	/**
	 * @var string
	 */
	public $taxRateValue = '';

	/**
	 * @var string
	 */
	public $countryName = '';

	/**
	 * @var string
	 */
	public $stateName = '';

	/**
	 * @var integer
	 */
	public $taxRateValueNegative;

	/**
	 * @var string
	 */
	public $taxRateValueString;



	/**
	 * Prepare the following structure
	 *
	 * Step 1: create Category
	 * Step 2: create product
	 * Step 3: create Group VAT
	 * Step 4: create VAT
	 * Step 5: setup configuration at Price belong to this VAT
	 * Step 5: goes on frontend and create order with product
	 */


	public function __construct()
	{
		$this->faker = Faker\Factory::create();
		$this->vatGroupName = $this->faker->bothify('ProductCheckoutVatExemptUserCest ?##?');
		$this->companyName = $this->faker->bothify('ProductCheckoutVatExemptUserCest Company ?##?');
		$this->country = 'Denmark';
		$this->state = null;
		$this->taxRate = ".10";

		//create user
		$this->userName = $this->faker->bothify('ManageUserAdministratorCest ?##?');
		$this->password = $this->faker->bothify('Password ?##?');
		$this->email = $this->faker->email;
		$this->emailMissingUser=$this->faker->email;
		$this->emailsave = $this->faker->email;
		$this->shopperGroup = 'Default Private';
		$this->group = 'Public';
		$this->firstName = $this->faker->bothify('ManageUserAdministratorCest FN ?##?');
		$this->updateFirstName = 'Updating ' . $this->firstName;
		$this->lastName = 'Last';
		$this->firstNameSave = "FirstName";
		$this->lastNameSave = "LastName";
		$this->emailWrong = "email";
		$this->userNameEdit = "UserNameSave" . $this->faker->randomNumber();
		$this->emailMatching = $this->faker->email;
		$this->userMissing = $this->faker->bothify('ManageUserMissingAdministratorCest ?##?');

		//create category
		$this->categoryName = 'Testing Category ' . $this->faker->randomNumber();
		$this->noPage = $this->faker->randomNumber();

		//create product
		$this->productName = 'Testing Products' . rand(99, 999);
		$this->randomProductNumber = $this->faker->numberBetween(999, 9999);
		$this->randomProductPrice = '100';
		$this->minimumPerProduct = '1';
		$this->minimumQuantity = 1;
		$this->maximumQuantity = $this->faker->numberBetween(11, 100);
		$this->discountStart = "12-12-2016";
		$this->discountEnd = "23-05-2017";

		//setup VAT
		$this->taxRateName          = 'Testing Tax Rates Groups' . rand(1, 199);
		$this->taxRateNameEdit      = $this->taxRateName . 'Edit';
		$this->taxGroupName         = 'Testing VAT Groups690';
		$this->taxRateValue         = rand(0, 1);
		$this->countryName          = 'United States';
		$this->stateName            = 'Alabama';
		$this->taxRateValueNegative = -1;
		$this->taxRateValueString   = 'Test';

		//setup price belong VAT
		//this value should be change when we setup VAT
		//$this->vatDefault = 'Default';
		$this->vatCalculation = 'Customer';
		$this->vatAfter = 'after';
		$this->vatNumber =0;
		$this->calculationBase = 'billing';
		$this->requiVAT = 'no';
	}

	/**
	 * Test to Verify the Vat Integration
	 *
	 * @param   AcceptanceTester  $I         Actor Class Object
	 * @param   String            $scenario  Scenario Variable
	 *
	 * @return void
	 */
	public function testProductWithVatCheckout(AcceptanceTester $client, $scenario)
	{
		$client->doAdministratorLogin();

		$client->wantTo('Create category');
		$client = new CategoryManagerJoomla3Steps($scenario);
		$client->addCategorySave($this->categoryName);

		$client->wantTo('Create Product');
		$client= new ProductManagerJoomla3Steps($scenario);
		$client->createProductSave($this->productName, $this->categoryName, $this->randomProductNumber, $this->randomProductPrice, $this->minimumPerProduct, $this->minimumQuantity, $this->maximumQuantity, $this->discountStart, $this->discountEnd);

		$client->wantTo('Create VAT groups');
		$client = new TaxGroupSteps($scenario);
		$client->addVATGroupsSave($this->taxGroupName);

		$client->wantTo('Create VAT rates');
		$client = new TaxRateSteps($scenario);
		$client->addTAXRatesSave($this->taxRateName, $this->taxGroupName, $this->taxRateValue, $this->countryName, $this->stateName);

		$client->wantTo('Setup VAT is groups default');
		$client=new ConfigurationManageJoomla3Steps($scenario);
		$client->setupVAT($this->country, $this->state, $this->taxGroupName, $this->vatCalculation, $this->vatAfter, $this->vatNumber, $this->calculationBase, $this->requiVAT);

		//create use
		$client->wantTo('Test User creation with save button in Administrator');
		$client = new UserManagerJoomla3Steps($scenario);
		$client->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperGroup, $this->firstName, $this->lastName, 'save');

		//login at frontend and create order
		$client->wantTo('Create order with product and user');



		$client->wantTo('Goes on frontend checkout');
		$client= new ProductCheckoutManagerJoomla3Steps($scenario);
		$client->checkoutApplyVATForUser($this->userName,$this->password,$this->productName,$this->categoryName);
//
//$addressDetail, $shipmentDetail, $productName = 'redCOOKIE', $categoryName = 'Events and Forms'
//
//
//		$I->amOnPage('/administrator/index.php?option=com_redshop&view=tax_groups');
//		$I->waitForText('VAT / Tax Group Management', 30, ['xpath' => "//h1"]);
//		$I->click("New");
//		$I->waitForElement(['id' => "jform_name"], 30);
//		$I->fillField(['id' => "jform_name"], $this->vatGroupName);
//		$I->click(["xpath" => "//input[@name='published' and @value='1']"]);
//		$I->click("Save & Close");
//		$I->waitForText("VAT Group Detail saved", 10, '.alert-message');
//		$I->see("VAT Group Detail saved", '.alert-message');
//		$I->click("ID");
//		$I->see($this->vatGroupName, ['xpath' => "//div[@id='editcell']/table/tbody/tr[1]"]);
//		$I->click(['id' => "cb0"]);
//		$I->click("Edit");
//		$I->waitForElement(['xpath' => "//div[@id='toolbar-redshop_tax_tax32']/button"], 30);
//		$I->click(['xpath' => "//div[@id='toolbar-redshop_tax_tax32']/button"]);
//		$I->waitForText("VAT Rates", 30, ['xpath' => "//h1"]);
//		$I->click("New");
//		$I->selectOptionInChosenByIdUsingJs("tax_country", $this->country);
//		$I->selectOptionInChosenByIdUsingJs("tax_state", $this->state);
//		$I->fillField(['id' => "tax_rate"], $this->taxRate);
//		$I->click("Save & Close");
//		$I->waitForText("VAT rate detail saved successfully", 30, '.alert-message');
//		$I->see("VAT rate detail saved successfully", '.alert-message');
//		$I->amOnPage("/administrator/index.php?option=com_redshop&view=configuration");
//		$I->waitForText("Configuration", 30, ['xpath' => "//h1"]);
//		$I->click(["link" => "Price"]);
//		$I->waitForElement(['id' => 'price_decimal']);
//		$I->fillField(['id' => 'price_decimal'], 2);
//		$I->waitForElement(['id' => 'default_vat_country']);
//		$I->executeJS("window.scrollTo(0, 900);");
//		// @todo: check why this is not working $I->scrollTo('#default_vat_country', 0, -200);
//		$I->wait(1);
//		$I->selectOptionInChosenByIdUsingJs("default_vat_country", $this->country);
//		$I->selectOptionInChosenByIdUsingJs("default_vat_state", $this->state);
//		$I->click(["xpath" => "//div[@id='default_vat_group_chzn']/a"]);
//		$I->click(["xpath" => "//div[@id='default_vat_group_chzn']/div/ul/li[text() = '" . $this->vatGroupName . "']"]);
//		$I->click("Save & Close");
//		$I->waitForText("Configuration Saved", 30, '.alert-message');
//		$I->see("Configuration Saved", '.alert-message');
//		$this->createUser($I, $scenario);
//		$I->amOnPage(\UserManagerJoomla3Page::$URL);
//		$I->click("ID");
//		$I->see($this->firstName, \UserManagerJoomla3Page::$firstResultRow);
//		$I->click(\UserManagerJoomla3Page::$selectFirst);
//		$I->click('Edit');
//		$I->waitForElement(['xpath' => "//input[@id='is_company1']"], 30);
//		$I->click(['xpath' => "//input[@id='is_company1']"]);
//		$I->click(\UserManagerJoomla3Page::$billingInformationTab);
//		$I->waitForElement(\UserManagerJoomla3Page::$firstName);
//		$I->click(['xpath' => "//input[@id='tax_exempt1']"]);
//		$I->click(['xpath' => "//input[@id='requesting_tax_exempt1']"]);
//		$I->click(['xpath' => "//input[@id='tax_exempt_approved1']"]);
//		$I->fillField(['xpath' => "//input[@name='company_name']"], $this->companyName);
//		$I->click(\UserManagerJoomla3Page::$generalUserInformationTab);
//		$I->click('Save & Close');
//		$I->waitForText(\UserManagerJoomla3Page::$userSuccessMessage,60,'.alert-success');
//		$I->doAdministratorLogout();
//		$productName = 'redCOOKIE';
//		$categoryName = 'Events and Forms';
//		$I->amOnPage(\FrontEndProductManagerJoomla3Page::$URL);
//		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$categoryDiv,30);
//		$I->checkForPhpNoticesOrWarnings();
//		$productFrontEndManagerPage = new \FrontEndProductManagerJoomla3Page;
//		$I->click($productFrontEndManagerPage->productCategory($categoryName));
//		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$productList,30);
//		$I->click($productFrontEndManagerPage->product($productName));
//		$I->click(\FrontEndProductManagerJoomla3Page::$addToCart);
//		$I->waitForText("Product has been added to your cart.", 10, '.alert-message');
//		$I->see("Product has been added to your cart.", '.alert-message');
//		$I->amOnPage('index.php?option=com_redshop&view=cart');
//		$I->checkForPhpNoticesOrWarnings();
//		$I->seeElement(['link' => $productName]);
//		$I->see("$ 24,00", ['class' => "lc-subtotal"]);
//		$I->see("$ 2,40", ['class' => "lc-vat"]);
//		$I->see("$ 26,40", ['class' => "lc-total"]);
//		$I->click(['xpath' => "//img[@class='delete_cart']"]);
//		$I->doFrontEndLogin($this->userName, $this->password);
//		$I->amOnPage(\FrontEndProductManagerJoomla3Page::$URL);
//		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$categoryDiv,30);
//		$I->checkForPhpNoticesOrWarnings();
//		$productFrontEndManagerPage = new \FrontEndProductManagerJoomla3Page;
//		$I->click($productFrontEndManagerPage->productCategory($categoryName));
//		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$productList,30);
//		$I->click($productFrontEndManagerPage->product($productName));
//		$I->click(\FrontEndProductManagerJoomla3Page::$addToCart);
//		$I->waitForText("Product has been added to your cart.", 10, '.alert-message');
//		$I->see("Product has been added to your cart.", '.alert-message');
//		$I->amOnPage('index.php?option=com_redshop&view=cart');
//		$I->checkForPhpNoticesOrWarnings();
//		$I->seeElement(['link' => $productName]);
//		$I->see("$ 24,00", ['class' => "lc-subtotal"]);
//		$I->see("$ 24,00", ['class' => "lc-total"]);
//		$I->doAdministratorLogin();
//		$I->amOnPage("/administrator/index.php?option=com_redshop&view=tax_group");
//		$I->waitForText('VAT / Tax Group Management', 30, ['xpath' => "//h1"]);
//		$I->click("ID");
//		$I->see($this->vatGroupName, ['xpath' => "//div[@id='editcell']/table/tbody/tr[1]"]);
//		$I->click(['id' => "cb0"]);
//		$I->click("Delete");
//		$I->waitForText("VAT Group detail deleted successfully", 10, '.alert-message');
//		$I->see("VAT Group detail deleted successfully", '.alert-message');
//		$I->amOnPage("/administrator/index.php?option=com_redshop&view=configuration");
//		$I->waitForText("Configuration", 30, ['xpath' => "//h1"]);
//		$I->click(["link" => "Price"]);
//		$I->waitForElement(['id' => 'price_decimal']);
//		$I->fillField(['id' => 'price_decimal'], 2);
//		$I->executeJS("window.scrollTo(0, 900);");
//		// @todo: check why this is not working $I->scrollTo('#default_vat_country', 0, -200);
//		$I->scrollTo(['id' => 'default_vat_country'], 0, -200);
//		$I->selectOptionInChosenByIdUsingJs("default_vat_country", "Select");
//		$I->click(["xpath" => "//div[@id='default_vat_group_chzn']/a"]);
//		$I->click(["xpath" => "//div[@id='default_vat_group_chzn']/div/ul/li[text() = 'Default']"]);
//		$I->click("Save & Close");
//		$I->waitForText("Configuration Saved", 30, '.alert-message');
//		$I->see("Configuration Saved", '.alert-message');
//		$this->deleteUser($I, $scenario);
	}

}
