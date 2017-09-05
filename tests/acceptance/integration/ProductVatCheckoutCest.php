<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
use \AcceptanceTester\CategoryManagerJoomla3Steps;
use \AcceptanceTester\ProductManagerJoomla3Steps;
use \AcceptanceTester\ConfigurationManageJoomla3Steps;
use \AcceptanceTester\TaxGroupSteps;
use \AcceptanceTester\TaxRateSteps;
use \AcceptanceTester\ProductCheckoutManagerJoomla3Steps;
use \AcceptanceTester\UserManagerJoomla3Steps;
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
		$this->vatCalculation = 'Webshop';
		$this->vatAfter = 'after';
		$this->vatNumber =0;
		$this->calculationBase = 'billing';
		$this->requiVAT = 'no';
	}
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
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
		$client->addTAXRatesSave($this->taxRateName, $this->taxGroupName, $this->taxRateValue, $this->countryName);
		$client->wantTo('Setup VAT is groups default');
		$client=new ConfigurationManageJoomla3Steps($scenario);
		$client->setupVAT($this->country, $this->state, $this->taxGroupName, $this->vatCalculation, $this->vatAfter, $this->vatNumber, $this->calculationBase, $this->requiVAT);
		$client->wantTo('Create order with product and user');
		$client= new ProductCheckoutManagerJoomla3Steps($scenario);
		$client->checkOutProductWithBankTransfer($this->productName,$this->categoryName);

	}
	public function clearUp(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Deletion of Coupon in Administrator');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->productName);
		$I->wantTo('Delete Category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->categoryName);
		$I->wantTo('Delete VAT value');
		$I= new TaxRateSteps($scenario);
		$I->deleteTAXRatesOK($this->taxRateName);
		$I->wantTo('Delete VAT Groups');
		$I= new TaxGroupSteps($scenario);
		$I->deleteVATGroupOK($this->taxGroupName);
	}
}
