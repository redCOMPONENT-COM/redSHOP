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
use AcceptanceTester\ProductCheckoutManagerJoomla3Steps as ProductCheckoutSteps;
use AcceptanceTester\TaxRateSteps as TaxRateSteps;
use AcceptanceTester\TaxGroupSteps as TaxGroupSteps;
use AcceptanceTester\ConfigurationSteps as ConfigurationSteps;

/**
 * Class AttributeUserCest
 *
 * @since  2.1.0
 */
class AttributeUserCest
{
	/**
	 * @var string
	 */
	public $category;

	/**
	 * @var string
	 */
	public $shopperName;

	/**
	 * @var string
	 */
	public $shopperType;

	/**
	 * @var string
	 */
	public $customerType;

	/**
	 * @var string
	 */
	public $shopperGroupPortal;

	/**
	 * @var \Faker\Generator
	 */
	public $faker;

	/**
	 * @var string
	 */
	public $taxGroupName;

	/**
	 * @var integer
	 */
	public $noPage;

	/**
	 * @var string
	 */
	public $productName;

	/**
	 * @var integer
	 */
	public $productPrice = 70;

	/**
	 * @var string
	 */
	public $minimumPerProduct = '1';

	/**
	 * @var int|string
	 */
	public $productNumber = '1';

	/**
	 * @var integer
	 */
	public $minimumQuantity = 1;

	/**
	 * @var array
	 */
	public $attributes;

	/**
	 * AttributeUserCest constructor.
	 */
	public function __construct()
	{
		$this->faker = Faker\Factory::create();

		$this->category        = $this->faker->bothify('Category Attribute ??####?');
		$this->noPage          = $this->faker->randomNumber();
		$this->productName     = $this->faker->bothify('Testing ProductManagement ??####?');
		$this->productNumber   = $this->faker->numberBetween(999, 9999);
		$this->minimumQuantity = 1;
		$this->maximumQuantity = $this->faker->numberBetween(11, 100);
		$this->attributes      = array(
			array(
				'name'           => $this->faker->bothify('Attribute Name ??###?'),
				'attributeName'  => $this->faker->bothify('AttributeName ??###?'),
				'attributePrice' => 10
			),
			array(
				'name'           => $this->faker->bothify('attributeSecond Name ??###?'),
				'attributeName'  => $this->faker->bothify('attributeSecond ??###?'),
				'attributePrice' => 20
			),
		);

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
		$this->lastName  = $this->faker->bothify('LastName ?####?');

		// Order info
		$this->subTotal      = 'DKK 100,00';
		$this->vatPrice      = 'DKK 10,00';
		$this->total         = 'DKK 110,00';
		$this->shippingPrice = 'DKK 0,00';

		// Shipping info
		$this->shippingMethod           = 'redSHOP - Standard Shipping';
		$this->shipping                 = array();
		$this->shipping['shippingName'] = $this->faker->bothify('TestingShippingRate ?###?');
		$this->shipping['shippingRate'] = 10;

		$this->taxRateName          = $this->faker->bothify('Testing Tax Rates Groups ?###?');
		$this->taxGroupName         = $this->faker->bothify(' ?###? TaxGroupsNam');
		$this->taxRateValue         = 0.1;
		$this->countryName          = 'Denmark';
		$this->taxRateValueNegative = -1;
		$this->taxRateValueString   = 'Test';

		// Vat setting
		$this->vatCalculation  = 'Webshop';
		$this->vatAfter        = 'after';
		$this->vatNumber       = 0;
		$this->calculationBase = 'billing';
		$this->requiVAT        = 'no';
	}

	/**
	 * Method delete data at database
	 *
	 * @param   \Codeception\Scenario $scenario Scenario
	 *
	 * @return  void
	 */
	public function deleteData($scenario)
	{
		(new RedshopSteps($scenario))->clearAllData();
	}

	/**
	 * @param   AcceptanceTester      $client   Client
	 * @param   \Codeception\Scenario $scenario Scenario
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 */
	public function testProductAdministrator(AcceptanceTester $client, \Codeception\Scenario $scenario)
	{
		$client->doAdministratorLogin();

		$client->wantTo('VAT Groups - Save creation in Administrator');
		(new TaxGroupSteps($scenario))->addVATGroupsSave($this->taxGroupName);

		$client->wantTo('Test TAX Rates Save creation in Administrator');
		(new TaxRateSteps($scenario))->addTAXRatesSave($this->taxRateName, $this->taxGroupName, $this->taxRateValue, $this->countryName, null);

		$client->wantTo('Create Category in Administrator');
		$client = new CategorySteps($scenario);
		$client->wantTo('Create a Category');
		$client->addCategorySave($this->category);

		$client->wantTo('Configuration for apply VAT');
		(new ConfigurationSteps($scenario))->setupVAT($this->countryName, null, $this->taxGroupName, $this->vatCalculation,
			$this->vatAfter, $this->vatNumber, $this->calculationBase, $this->requiVAT
		);

		$client->wantTo('Create new product with attribute');
		(new ProductSteps($scenario))->productMultiAttribute($this->productName, $this->category,
			$this->productNumber, $this->productPrice, $this->attributes
		);

		$client->wantTo('Create a Category Save button');
		(new ShopperGroupSteps($scenario))->addShopperGroups($this->shopperName, $this->shopperType, $this->customerType,
			$this->shopperGroupPortal, $this->category, $this->shipping, $this->shippingRate, $this->shippingCheckout,
			$this->catalog, $this->showVat, $this->showPrice, $this->enableQuotation, 'save'
		);

		$client->wantTo('Test User creation with save button in Administrator');
		(new \AcceptanceTester\UserManagerJoomla3Steps($scenario))->addUser(
			$this->userName, $this->userName, $this->email, $this->group, $this->shopperName, $this->firstName, $this->lastName, 'save'
		);

		$client->wantTo('Add shipping rate');
		(new \AcceptanceTester\ShippingSteps($scenario))->createShippingRateStandard($this->shippingMethod, $this->shipping);
	}

	/**
	 * @param   AcceptanceTester      $client   Acceptance tester
	 * @param   \Codeception\Scenario $scenario Scenario
	 *
	 * @return  void
	 *
	 * @throws Exception
	 */
	public function checkoutWithAttributeProduct(AcceptanceTester $client, \Codeception\Scenario $scenario)
	{
		$client->wantTo('checkout with attribute');
		(new ProductCheckoutSteps($scenario))->checkoutAttributeShopperUser(
			$this->userName, $this->productName, $this->attributes, $this->category, $this->subTotal,
			$this->vatPrice, $this->total, $this->shippingPrice
		);
	}

	public function clearUpDatabase(AcceptanceTester $client, $scenario)
    {
        $client->wantTo('Delete all data');
        $client= new RedshopSteps($scenario);
        $client->clearAllData();
    }
}
