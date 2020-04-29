<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\TaxRateSteps;
use AcceptanceTester\TaxGroupSteps;
use AcceptanceTester\CategoryManagerJoomla3Steps as CategoryManagerJoomla3Steps;
use Configuration\ConfigurationSteps as ConfigurationSteps;
use AcceptanceTester\UserManagerJoomla3Steps as UserManagerJoomla3Steps;
use AcceptanceTester\OrderManagerJoomla3Steps as OrderManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps as ProductSteps;

/**
 * Class ProductAttributesVatCheckoutCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    2.2.0
 */
class ProductAttributesVatCheckoutCest
{
    /**
     * @var \Faker\Generator
     * @since  2.2.0
     */
    public $faker;

    /**
     * @var string
     * @since  2.2.0
     */
    public $categoryName;

    /**
     * @var string
     * @since  2.2.0
     */
    public $shopperName;

    /**
     * @var string
     * @since  2.2.0
     */
    public $shopperType;

    /**
     * @var string
     * @since  2.2.0
     */
    public $customerType;

    /**
     * @var string
     * @since  2.2.0
     */
    public $shopperGroupPortal;

    /**
     * @var string
     * @since  2.2.0
     */
    public $taxGroupName;

    /**
     * @var integer
     * @since  2.2.0
     */
    public $noPage;

    /**
     * @var string
     * @since  2.2.0
     */
    public $productName;

    /**
     * @var integer
     * @since  2.2.0
     */
    public $productPrice = 70;

    /**
     * @var string
     * @since  2.2.0
     */
    public $minimumPerProduct = '1';

    /**
     * @var int|string
     * @since  2.2.0
     */
    public $productNumber = '1';

    /**
     * @var integer
     * @since  2.2.0
     */
    public $minimumQuantity = 1;

    /**
     * @var int
     * @since  2.2.0
     */
    protected $maximumQuantity;

    /**
     * @var array
     * @since  2.2.0
     */
    public $attributes;

    /**
     * @var int
     * @since  2.2.0
     */
    protected $shippingRate;

    /**
     * @var int
     * @since  2.2.0
     */
    protected $shippingCheckout;

    /**
     * @var string
     * @since  2.2.0
     */
    protected $catalog;

    /**
     * @var string
     * @since  2.2.0
     */
    protected $showPrice;

    /**
     * @var string
     * @since  2.2.0
     */
    protected $enableQuotation;

    /**
     * @var null
     * @since  2.2.0
     */
    protected $showVat;

    /**
     * @var string
     * @since  2.2.0
     */
    protected $userName;

    /**
     * @var string
     * @since  2.2.0
     */
    protected $password;

    /**
     * @var string
     * @since  2.2.0
     */
    protected $email;

    /**
     * @var string
     * @since  2.2.0
     */
    protected $group;

    /**
     * @var string
     * @since  2.2.0
     */
    protected $firstName;

    /**
     * @var string
     * @since  2.2.0
     */
    protected $lastName;

    /**
     * @var string
     * @since  2.2.0
     */
    protected $subTotal;

    /**
     * @var string
     * @since  2.2.0
     */
    protected $vatPrice;

    /**
     * @var string
     * @since  2.2.0
     */
    protected $total;

    /**
     * @var string
     * @since  2.2.0
     */
    protected $shippingPrice;

    /**
     * @var string
     * @since  2.2.0
     */
    protected $shippingMethod;

    /**
     * @var array
     * @since  2.2.0
     */
    protected $shipping;

    /**
     * @var string
     * @since  2.2.0
     */
    protected $taxRateName;

    /**
     * @var float
     * @since  2.2.0
     */
    protected $taxRateValue;

    /**
     * @var string
     * @since  2.2.0
     */
    protected $countryName;

    /**
     * @var int
     * @since  2.2.0
     */
    protected $taxRateValueNegative;

    /**
     * @var string
     * @since  2.2.0
     */
    protected $taxRateValueString;

    /**
     * @var string
     * @since  2.2.0
     */
    protected $vatCalculation;

    /**
     * @var string
     * @since  2.2.0
     */
    protected $vatAfter;

    /**
     * @var int
     * @since  2.2.0
     */
    protected $vatNumber;

    /**
     * @var string
     * @since  2.2.0
     */
    protected $calculationBase;

    /**
     * @var string
     * @since  2.2.0
     */
    protected $requiredVAT;

    /**
     * @var string
     * @since 2.2.0     
     */
    protected $nameAttribute;

    /**
     * @var string
     * @since 2.2.0
     */
    protected $product1;

    /**
     * @var string
     * @since 2.2.0
     */
    protected $product2;

    /**
     * @var array
     * @since 2.2.0
     */
    protected $cartSetting;

    /**
     * @var array
     * @since 2.2.0
     */
    protected $customerInformation;

    /**
     * @var array
     * @since 2.2.0
     */
    protected $customerBussinesInformation;

    /**
     * ProductAttributesVatCheckoutCest constructor
     * @since 2.2.0
     */
	public function __construct()
	{
		$this->faker                = Faker\Factory::create();
		$this->taxRateName          = $this->faker->bothify('Testing Tax Rates Groups ?###?');
		$this->taxGroupName         = $this->faker->bothify('TaxGroupsName ?###?');
		$this->taxRateValue         = 0.25;
		$this->countryName          = 'Denmark';
		$this->taxRateValueNegative = -1;
		$this->taxRateValueString   = 'Test';
		$this->categoryName         = $this->faker->bothify('CategoryNameVAT ?###?');

		$this->productName          = $this->faker->bothify('Testing ProductManagement ??####?');
		$this->productNumber        = $this->faker->numberBetween(999, 9999);
		$this->productPrice         = 100;
		$this->minimumQuantity      = 1;
		$this->maximumQuantity      = $this->faker->numberBetween(11, 100);

		$this->nameAttribute        = $this->faker->bothify('AttributeName ??###?');

		$this->attributes           = array(
			array(
				'attributeName'  => $this->faker->bothify('AttributeValue ??###?'),
				'attributePrice' => 20
			),
			array(
				'attributeName'  => $this->faker->bothify('AttributeValue ??###?'),
				'attributePrice' => 40
			),
		);

		// Vat setting
		$this->vatCalculation       = 'Webshop';
		$this->vatAfter             = 'after';
		$this->vatNumber            = 0;
		$this->calculationBase      = 'billing';
		$this->requiredVAT             = 'no';

		$this->product1             = "120";
		$this->vatPrice             = "";
		$this->product2             = "140";
		$this->group                = 'Registered';

		//configuration enable one page checkout
		$this->cartSetting = array(
			"addCart"           => 'product',
			"allowPreOrder"     => 'yes',
			"cartTimeOut"       => $this->faker->numberBetween(100, 10000),
			"enabledAjax"       => 'no',
			"defaultCart"       => null,
			"buttonCartLead"    => 'Back to current view',
			"onePage"           => 'yes',
			"showShippingCart"  => 'no',
			"attributeImage"    => 'no',
			"quantityChange"    => 'no',
			"quantityInCart"    => 0,
			"minimumOrder"      => 0,
			"enableQuotation"   => 'no'
		);

		$this->customerInformation = array(
			"userName"      => $this->faker->bothify('UserName ?####?'),
			"password"      => $this->faker->bothify('Password ?##?'),
			"email"         => $this->faker->email,
			"firstName"     => $this->faker->bothify('firstNameCustomer ?####?'),
			"lastName"      => $this->faker->bothify('lastNameCustomer ?####?'),
			"address"       => "Some Place in the World",
			"postalCode"    => "23456",
			"city"          => "HCM",
			"country"       => "Denmark",
			"state"         => "Karnataka",
			"phone"         => "8787878787",
			"shopperGroup"  => 'Default Private',
		);

		$this->customerBussinesInformation = array(
			"userName"      => $this->faker->bothify('UserName ?####?'),
			"password"      => $this->faker->bothify('Password ?##?'),
			"email"         => $this->faker->email,
			"businessNumber" => 1231312,
			"firstName"      => $this->faker->bothify('firstName ?####?'),
			"lastName"       => $this->faker->bothify('lastName ?####?'),
			"address"        => "Some Place in the World",
			"postalCode"     => "23456",
			"city"           => "HCM",
			"country"        => "Denmark",
			"state"          => "Karnataka",
			"phone"          => "8787878787",
			"eanNumber"      => 1212331331231,
			"shopperGroup"   => "Default Company",
		);
	}

	/**
	 * @param AcceptanceTester $I
	 * @throws Exception
     * @since 2.2.0
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * Create VAT Group with
	 *
	 * @param   TaxGroupSteps $I Current user state.
	 * @param   \Codeception\Scenario $scenario Scenario for test.
	 *
	 * @return  void
	 * @throws  Exception
     * @since 2.2.0
	 */
	public function createVATGroupSave(TaxGroupSteps $I, $scenario)
	{
		$I->wantTo('VAT Groups - Save creation in Administrator');
		$I = new TaxGroupSteps($scenario);
		$I->addVATGroupsSave($this->taxGroupName);

		$I->wantTo('Test TAX Rates Save creation in Administrator');
		$I = new TaxRateSteps($scenario);
		$I->addTAXRatesSave($this->taxRateName, $this->taxGroupName, $this->taxRateValue, $this->countryName, null);

		$I->wantTo('Create new category ');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->categoryName);

		$I->wantTo('Create new product');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');

		$I->wantTo('Create new product with attribute');
		(new ProductSteps($scenario))->productMultiAttributeValue($this->productName, $this->categoryName,
			$this->productNumber, $this->productPrice,$this->nameAttribute, $this->attributes
		);

		$I->wantTo('Configuration for apply VAT');
		$I = new ConfigurationSteps($scenario);
		$I->setupVAT(
			$this->countryName, null, $this->taxGroupName, $this->vatCalculation, $this->vatAfter, $this->vatNumber,
			$this->calculationBase, $this->requiredVAT
		);
		$I->cartSetting($this->cartSetting);

		$I->wantTo('Create user for checkout');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->addUser(
			$this->customerInformation["userName"], $this->customerInformation["password"], $this->customerInformation["email"], $this->group,$this->customerInformation["shopperGroup"],
			$this->customerInformation["firstName"], $this->customerInformation["lastName"], 'saveclose'
		);

		$I->addUser(
			$this->customerBussinesInformation["userName"], $this->customerBussinesInformation["password"], $this->customerBussinesInformation["email"], $this->group,$this->customerBussinesInformation["shopperGroup"],
			$this->customerBussinesInformation["firstName"], $this->customerBussinesInformation["lastName"], 'saveclose'
		);

		$I = new CheckoutOnFrontEnd($scenario);
		$I->testProductAttributeWithVatCheckout(
			$this->customerInformation["userName"], $this->customerInformation["password"], $this->productName, $this->categoryName, $this->product1, $this->product2, $this->vatPrice, $this->attributes
		);

		$I->testProductAttributeWithVatCheckout(
			$this->customerBussinesInformation["userName"], $this->customerBussinesInformation["password"], $this->productName, $this->categoryName, $this->product1, $this->product2, $this->vatPrice, $this->attributes
		);

		$I->wantTo('Disable one page checkout');
		$this->cartSetting["onePage"] = 'no';
		$I = new ConfigurationSteps($scenario);
		$I->cartSetting($this->cartSetting);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
     * @since 2.2.0
	 */
	public function clearUp(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Delete tax value');
		(new TaxRateSteps($scenario))->deleteTAXRatesOK($this->taxRateName);

		$I->wantTo('Delete tax group');
		(new TaxGroupSteps($scenario))->deleteVATGroupOK($this->taxGroupName);

		$I->wantTo('Delete user');
		(new UserManagerJoomla3Steps($scenario))->deleteUser($this->customerInformation["firstName"]);

		$I->wantTo('Delete user');
		(new UserManagerJoomla3Steps($scenario))->deleteUser($this->customerBussinesInformation["firstName"]);

		$I->wantTo('Test Order delete by user  in Administrator');
		(new OrderManagerJoomla3Steps($scenario))->deleteOrder($this->customerInformation["firstName"]);
		(new OrderManagerJoomla3Steps($scenario))->deleteOrder($this->customerBussinesInformation["firstName"]);

		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('Delete Product  in Administrator');
		$I->deleteProduct($this->productName);

		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->wantTo('Delete Category in Administrator');
		$I->deleteCategory($this->categoryName);
	}
}