<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\ProductManagerJoomla3Steps as ProductSteps;
use AcceptanceTester\CategoryManagerJoomla3Steps as CategorySteps;
use AcceptanceTester\ShopperGroupManagerJoomla3Steps as ShopperGroupSteps;
use AcceptanceTester\ProductCheckoutManagerJoomla3Steps as ProductCheckoutSteps;
use AcceptanceTester\ShippingSteps as ShippingSteps;
use AcceptanceTester\TaxRateSteps as TaxRateSteps;
use AcceptanceTester\TaxGroupSteps as TaxGroupSteps;
use AcceptanceTester\UserManagerJoomla3Steps;
use Configuration\ConfigurationSteps as ConfigurationSteps;

/**
 * Class AttributeUserCest
 *
 * @since  2.1.0
 */
class AttributeUserCest
{
	/**
	 * @var \Faker\Generator
	 * @since  2.1.0
	 */
	public $faker;

	/**
	 * @var string
	 * @since  2.1.0
	 */
	public $category;

	/**
	 * @var string
	 * @since  2.1.0
	 */
	public $shopperName;

	/**
	 * @var string
	 * @since  2.1.0
	 */
	public $shopperType;

	/**
	 * @var string
	 * @since  2.1.0
	 */
	public $customerType;

	/**
	 * @var string
	 * @since  2.1.0
	 */
	public $shopperGroupPortal;

	/**
	 * @var string
	 * @since  2.1.0
	 */
	public $taxGroupName;

	/**
	 * @var integer
	 * @since  2.1.0
	 */
	public $noPage;

	/**
	 * @var string
	 * @since  2.1.0
	 */
	public $productName;

	/**
	 * @var integer
	 * @since  2.1.0
	 */
	public $productPrice = 70;

	/**
	 * @var string
	 * @since  2.1.0
	 */
	public $minimumPerProduct = '1';

	/**
	 * @var int|string
	 * @since  2.1.0
	 */
	public $productNumber = '1';

	/**
	 * @var integer
	 * @since  2.1.0
	 */
	public $minimumQuantity = 1;

	/**
	 * @var int
	 * @since  2.1.0
	 */
	protected $maximumQuantity;

	/**
	 * @var array
	 * @since  2.1.0
	 */
	public $attributes;

	/**
	 * @var int
	 * @since  2.1.0
	 */
	protected $shippingRate;

	/**
	 * @var int
	 * @since  2.1.0
	 */
	protected $shippingCheckout;

	/**
	 * @var string
	 * @since  2.1.0
	 */
	protected $catalog;

	/**
	 * @var string
	 * @since  2.1.0
	 */
	protected $showPrice;

	/**
	 * @var string
	 * @since  2.1.0
	 */
	protected $enableQuotation;

	/**
	 * @var null
	 * @since  2.1.0
	 */
	protected $showVat;

	/**
	 * @var string
	 * @since  2.1.0
	 */
	protected $userName;

	/**
	 * @var string
	 * @since  2.1.0
	 */
	protected $password;

	/**
	 * @var string
	 * @since  2.1.0
	 */
	protected $email;

	/**
	 * @var string
	 * @since  2.1.0
	 */
	protected $group;

	/**
	 * @var string
	 * @since  2.1.0
	 */
	protected $firstName;

	/**
	 * @var string
	 * @since  2.1.0
	 */
	protected $lastName;

	/**
	 * @var string
	 * @since  2.1.0
	 */
	protected $subTotal;

	/**
	 * @var string
	 * @since  2.1.0
	 */
	protected $vatPrice;

	/**
	 * @var string
	 * @since  2.1.0
	 */
	protected $total;

	/**
	 * @var string
	 * @since  2.1.0
	 */
	protected $shippingPrice;

	/**
	 * @var string
	 * @since  2.1.0
	 */
	protected $shippingMethod;

	/**
	 * @var array
	 * @since  2.1.0
	 */
	protected $shipping;

	/**
	 * @var string
	 * @since  2.1.0
	 */
	protected $taxRateName;

	/**
	 * @var float
	 * @since  2.1.0
	 */
	protected $taxRateValue;

	/**
	 * @var string
	 * @since  2.1.0
	 */
	protected $countryName;

	/**
	 * @var int
	 * @since  2.1.0
	 */
	protected $taxRateValueNegative;

	/**
	 * @var string
	 * @since  2.1.0
	 */
	protected $taxRateValueString;

	/**
	 * @var string
	 * @since  2.1.0
	 */
	protected $vatCalculation;

	/**
	 * @var string
	 * @since  2.1.0
	 */
	protected $vatAfter;

	/**
	 * @var int
	 * @since  2.1.0
	 */
	protected $vatNumber;

	/**
	 * @var string
	 * @since  2.1.0
	 */
	protected $calculationBase;

	/**
	 * @var string
	 * @since  2.1.0
	 */
	protected $requiredVAT;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public $shopperGroup;

	/**
	 * AttributeUserCest constructor.
	 * @since  2.1.0
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
		$this->taxGroupName         = $this->faker->bothify('TaxGroupsNam ?###?');
		$this->taxRateValue         = 0.1;
		$this->countryName          = 'Denmark';
		$this->taxRateValueNegative = -1;
		$this->taxRateValueString   = 'Test';

		// Vat setting
		$this->vatCalculation  = 'Webshop';
		$this->vatAfter        = 'after';
		$this->vatNumber       = 0;
		$this->calculationBase = 'billing';
		$this->requiredVAT     = 'no';

		$this->shopperGroup         = 'All';
	}

	/**
	 * @param   AcceptanceTester      $client   Client
	 * @param   \Codeception\Scenario $scenario Scenario
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 * @since  2.1.0
	 */
	public function testProductAdministrator(AcceptanceTester $client, \Codeception\Scenario $scenario)
	{
		$client->doAdministratorLogin();

		$client->wantTo('VAT Groups - Save creation in Administrator');
		(new TaxGroupSteps($scenario))->addVATGroupsSave($this->taxGroupName);

		$client->wantTo('Test TAX Rates Save creation in Administrator');
		(new TaxRateSteps($scenario))->addTAXRatesSave($this->taxRateName, $this->taxGroupName, $this->taxRateValue, $this->countryName, null, $this->shopperGroup);

		$client->wantTo('Create Category in Administrator');
		$client = new CategorySteps($scenario);
		$client->wantTo('Create a Category');
		$client->addCategorySave($this->category);

		$client->wantTo('Configuration for apply VAT');
		(new ConfigurationSteps($scenario))->setupVAT($this->countryName, null, $this->taxGroupName, $this->vatCalculation,
			$this->vatAfter, $this->vatNumber, $this->calculationBase, $this->requiredVAT
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
		(new UserManagerJoomla3Steps($scenario))->addUser(
			$this->userName, $this->userName, $this->email, $this->group, $this->shopperName, $this->firstName, $this->lastName, 'save'
		);

		$client->wantTo('Add shipping rate');
		(new ShippingSteps($scenario))->createShippingRateStandard($this->shippingMethod, $this->shipping);
	}

	/**
	 * @param   AcceptanceTester      $client   Acceptance tester
	 * @param   \Codeception\Scenario $scenario Scenario
	 *
	 * @return  void
	 *
	 * @throws Exception
	 * @since 2.1.0
	 */
	public function checkoutWithAttributeProduct(AcceptanceTester $client, \Codeception\Scenario $scenario)
	{
		$client->wantTo('checkout with attribute');
		(new ProductCheckoutSteps($scenario))->checkoutAttributeShopperUser(
			$this->userName, $this->productName, $this->attributes, $this->category, $this->subTotal,
			$this->vatPrice, $this->total, $this->shippingPrice
		);
	}

	/**
	 * @param AcceptanceTester $client
	 * @param $scenario
	 * @throws Exception
	 * @since  2.1.0
	 */
	public function clearUpDatabase(AcceptanceTester $client, $scenario)
	{
		$client->doAdministratorLogin();

		$client->wantTo('Delete  TAX Rates in Administrator');
		(new TaxRateSteps($scenario))->deleteTAXRatesOK($this->taxRateName);
		$client->wantTo(' Delete VAT Groups in Administrator');
		(new TaxGroupSteps($scenario))->deleteVATGroupOK($this->taxGroupName);

		$client->wantTo('Delete product with attribute');
		$client = new ProductSteps($scenario);
		$client->deleteProduct($this->productName);
		$client->wantTo('Delete Category in Administrator');
		$client = new CategorySteps($scenario);
		$client->deleteCategory($this->category);
		$client->wantTo('Delete User creation in Administrator');
		$client = new UserManagerJoomla3Steps(($scenario));
		$client->deleteUser($this->firstName);
		$client->wantTo('Configuration for apply VAT');
		(new ConfigurationSteps($scenario))->setupVAT($this->countryName, null, 'Select', $this->vatCalculation,
			$this->vatAfter, $this->vatNumber, $this->calculationBase, $this->requiredVAT
		);
	}
}
