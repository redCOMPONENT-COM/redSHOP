<?php
/**
 * 1. create new product with attribute
 * 2. create user belong shopper group
 * 3. go to frontpage and add to cart for product
 * 4. add to cart with product attibute
 * 5. go to checkout final
 */

use AcceptanceTester\ProductManagerJoomla3Steps as ProductSteps;
use AcceptanceTester\CategoryManagerJoomla3Steps as CategorySteps;
use AcceptanceTester\ShopperGroupManagerJoomla3Steps as ShopperGroupSteps;
use AcceptanceTester\UserManagerJoomla3Steps as UserSteps;
use AcceptanceTester\ProductCheckoutManagerJoomla3Steps as ProductCheckoutSteps;
use AcceptanceTester\ShippingSteps as ShippingSteps;
use AcceptanceTester\TaxRateSteps as TaxRateSteps;
use AcceptanceTester\TaxGroupSteps as TaxGroupSteps;
use AcceptanceTester\ConfigurationSteps as ConfigurationSteps;

class AttributeUserCest
{
	/**
	 * @var \Faker\Generator
	 */
	public $faker;

	public function __construct()
	{
		$this->faker = Faker\Factory::create();

		$this->category          = $this->faker->bothify("Category Attribute ??####?");
		$this->noPage            = $this->faker->randomNumber();
		$this->productName       = 'Testing Products' . rand(99, 999);
		$this->productNumber     = $this->faker->numberBetween(999, 9999);
		$this->productPrice      = 70;
		$this->minimumPerProduct = '1';
		$this->minimumQuantity   = 1;
		$this->maximumQuantity   = $this->faker->numberBetween(11, 100);
		$this->attributes        = array();

		$this->attribute = array();

		$this->attribute['name'] = $this->faker->bothify('Attribute Name ??###?');

		$this->attribute['attributeName'] = $this->faker->bothify('AttributeName ??###?');

		$this->attribute['attributePrice'] = 10;

		$this->attributes[] = $this->attribute;

		$this->attributeSecond = array();

		$this->attributeSecond['name'] = $this->faker->bothify('attributeSecond Name ??###?');

		$this->attributeSecond['attributeName'] = $this->faker->bothify('attributeSecond ??###?');

		$this->attributeSecond['attributePrice'] = 20;

		$this->attributes[] = $this->attributeSecond;

		$this->shopperName        = $this->faker->bothify(' Testing shopper ##??');
		$this->shopperType        = null;
		$this->customerType       = 'Company customer';
		$this->shippingRate       = 10;
		$this->shippingCheckout   = $this->faker->numberBetween(1, 100);
		$this->catalog            = 'Yes';
		$this->showPrice          = 'Yes';
		$this->shipping           = 'yes';
		$this->enableQuotation    = 'yes';
		$this->showVat            = null;
		$this->shopperGroupPortal = 'no';

		// User info
		$this->userName  = $this->faker->bothify('UserName ?##?');
		$this->email     = $this->faker->email;
		$this->group     = 'Administrator';
		$this->firstName = $this->faker->bothify('FirstName FN ?##?');
		$this->lastName  = "LastName ?####?";

		// Order info
		$this->subTotal      = "DKK 80,00";
		$this->vatPrice      = "DKK 8,00";
		$this->total         = "DKK 88,00";
		$this->shippingPrice = "DKK 0,00";

		// Shipping info
		$this->shippingMethod           = 'redSHOP - Standard Shipping';
		$this->shipping                 = array();
		$this->shipping['shippingName'] = 'TestingShippingRate' . rand(99, 999);
		$this->shipping['shippingRate'] = 10;

		$this->taxRateName          = 'Testing Tax Rates Groups' . rand(1, 199);
		$this->taxGroupName         = $this->faker->bothify(' ?###? TaxGroupsNam');
		$this->taxRateValue         = 0.1;
		$this->countryName          = 'Denmark';
		$this->taxRateValueNegative = -1;
		$this->taxRateValueString   = 'Test';

		// vat setting
		$this->vatCalculation  = 'Webshop';
		$this->vatAfter        = 'after';
		$this->vatNumber       = 0;
		$this->calculationBase = 'billing';
		$this->requiVAT        = 'no';
	}

	/**
	 * @param AcceptanceTester $client
	 * @param                  $scenario
	 */
	public function testProductAdministrator(AcceptanceTester $client, $scenario)
	{
		$client->doAdministratorLogin();

		$client->wantTo('VAT Groups - Save creation in Administrator');
		$client = new TaxGroupSteps($scenario);
		$client->addVATGroupsSave($this->taxGroupName);

		$client->wantTo('Test TAX Rates Save creation in Administrator');
		$client = new TaxRateSteps($scenario);
		$client->addTAXRatesSave($this->taxRateName, $this->taxGroupName, $this->taxRateValue, $this->countryName, null);

		$client->wantTo('Create Category in Administrator');
		$client = new CategorySteps($scenario);
		$client->wantTo('Create a Category');
		$client->addCategorySave($this->category);

		$client->wantTo('Configuration for apply VAT');
		$client = new ConfigurationSteps($scenario);
		$client->setupVAT($this->countryName, null, $this->taxGroupName, $this->vatCalculation,
			$this->vatAfter, $this->vatNumber, $this->calculationBase, $this->requiVAT);

		$client->wantTo('Create new product with attribute');
		$client = new ProductSteps($scenario);
		$client->productMultiAttribute($this->productName, $this->category,
			$this->productNumber, $this->productPrice, $this->attributes);


		$client = new ShopperGroupSteps($scenario);
		$client->wantTo('Create a Category Save button');
		$client->addShopperGroups($this->shopperName, $this->shopperType, $this->customerType,
			$this->shopperGroupPortal, $this->category, $this->shipping, $this->shippingRate, $this->shippingCheckout,
			$this->catalog, $this->showVat, $this->showPrice, $this->enableQuotation, 'save');

		$client->wantTo('Test User creation with save button in Administrator');
		$client = new UserSteps($scenario);
		$client->addUser($this->userName, $this->userName, $this->email, $this->group, $this->shopperName, $this->firstName, $this->lastName, 'save');

		$client->wantTo('Add shipping rate');
		$client = new ShippingSteps($scenario);
		$client->createShippingRateStandard($this->shippingMethod, $this->shipping, 'save');

	}

	/**
	 * @param AcceptanceTester $client
	 * @param                  $scenario
	 */
	public function checkoutWithAttributeProduct(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('checkout with attribute');
		$client = new ProductCheckoutSteps($scenario);
		$client->checkoutAttributeShopperUser($this->userName, $this->productName, $this->attributes, $this->category, $this->subTotal, $this->vatPrice, $this->total, $this->shippingPrice);
	}
}
