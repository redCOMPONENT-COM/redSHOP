<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\TaxRateSteps;
use AcceptanceTester\TaxGroupSteps;
use AcceptanceTester\CategoryManagerJoomla3Steps as CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductCheckoutManagerJoomla3Steps as ProductCheckoutManagerJoomla3Steps;
use Configuration\ConfigurationSteps as ConfigurationSteps;
use AcceptanceTester\UserManagerJoomla3Steps as UserManagerJoomla3Steps;
use AcceptanceTester\OrderManagerJoomla3Steps as OrderManagerJoomla3Steps;

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
	 * @var  string
	 * @since 1.4.0
	 */
	protected $faker;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $taxRateName = '';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $taxRateName2 = '';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $taxRateNameEdit = '';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $taxGroupName = '';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $taxRateValue = '';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $countryName = '';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $stateName = '';

	/**
	 * @var integer
	 * @since 1.4.0
	 */
	protected $taxRateValueNegative;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $taxRateValueString;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $productName;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $categoryName;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $randomProductNumber;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $randomProductPrice;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $vatCalculation;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $vatAfter;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $vatNumber;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $calculationBase;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $requiredVAT;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $subtotal;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $vatPrice;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $total;

	/**
	 * @var array
	 * @since 1.4.0
	 */
	protected $cartSetting;

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
	protected $shopperGroup;

	/**
	 * ProductVatCheckoutCest constructor.
	 * @since 1.4.0
	 */
	public function __construct()
	{
		$this->faker                = Faker\Factory::create();
		$this->taxRateName          = $this->faker->bothify('Testing Tax Rates Groups ?###?');
		$this->taxRateNameEdit      = $this->taxRateName . 'Edit';
		$this->taxGroupName         = $this->faker->bothify('TaxGroupsName ?###?');
		$this->taxRateValue         = 0.1;
		$this->countryName          = 'Denmark';
		$this->taxRateValueNegative = -1;
		$this->taxRateValueString   = 'Test';
		$this->productName          = $this->faker->bothify('NameProductVAT ?###?');
		$this->categoryName         = $this->faker->bothify('CategoryNameVAT ?###?');
		$this->randomProductNumber  = $this->faker->bothify('productNumber ?###?');
		$this->randomProductPrice   = 100;

		// Vat setting
		$this->vatCalculation  = 'Webshop';
		$this->vatAfter        = 'after';
		$this->vatNumber       = 0;
		$this->calculationBase = 'billing';
		$this->requiredVAT     = 'no';

		$this->subtotal = "DKK 100,00";
		$this->vatPrice = "DKK 10,00";
		$this->total    = "DKK 110,00";

		$this->userName     = $this->faker->bothify('ManageUserAdministratorCest ?####?');
		$this->password     = $this->faker->bothify('Password ?##?');
		$this->email        = $this->faker->email;
		$this->shopperGroup = 'Default Private';
		$this->group        = 'Registered';
		$this->firstName    = $this->faker->bothify('ManageUserAdministratorCest FN ?##?');
		$this->lastName     = $this->faker->bothify('LastName ?####?');

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
	}

	/**
	 * @param AcceptanceTester $I
	 * @throws Exception
	 * @since 1.4.0
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * Create VAT Group with
	 *
	 * @param   AcceptanceTester $client Current user state.
	 * @param   \Codeception\Scenario $scenario Scenario for test.
	 *
	 * @return  void
	 * @throws  Exception
	 * @since 1.4.0
	 */
	public function createVATGroupSave(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('VAT Groups - Save creation in Administrator');
		$client = new TaxGroupSteps($scenario);
		$client->addVATGroupsSave($this->taxGroupName);

		$client->wantTo('Test TAX Rates Save creation in Administrator');
		$client = new TaxRateSteps($scenario);
		$client->addTAXRatesSave($this->taxRateName, $this->taxGroupName, $this->taxRateValue, $this->countryName, null);

		$client->wantTo('Create new category ');
		$client = new CategoryManagerJoomla3Steps($scenario);
		$client->addCategorySave($this->categoryName);

		$client->wantTo('Create new product');
		$client = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
		$client->wantTo('I Want to add product inside the category');
		$client->createProductWithVATGroups(
			$this->productName, $this->categoryName, $this->randomProductNumber, $this->randomProductPrice, $this->taxGroupName
		);

		$client->wantTo('Configuration for apply VAT');
		$client = new ConfigurationSteps($scenario);
		$client->setupVAT(
			$this->countryName, null, $this->taxGroupName, $this->vatCalculation, $this->vatAfter, $this->vatNumber,
			$this->calculationBase, $this->requiredVAT
		);
		$client->cartSetting($this->cartSetting);

		$client->wantTo('Create user for checkout');
		$client = new UserManagerJoomla3Steps($scenario);
		$client->addUser(
			$this->userName, $this->password, $this->email, $this->group, $this->shopperGroup, $this->firstName, $this->lastName, 'saveclose'
		);

		$client = new ProductCheckoutManagerJoomla3Steps($scenario);
		$client->testProductWithVatCheckout(
			$this->userName, $this->password, $this->productName, $this->categoryName, $this->subtotal, $this->vatPrice, $this->total
		);

		$client->wantTo('Disable one page checkout');
		$this->cartSetting["onePage"] = 'no';
		$client = new ConfigurationSteps($scenario);
		$client->cartSetting($this->cartSetting);
	}

	/**
	 * Method clear all data
	 *
	 * @param   AcceptanceTester $tester Tester
	 * @param   \Codeception\Scenario $scenario Scenario
	 *
	 * @return  void
	 * @throws  Exception
	 * @since 1.4.0
	 */
	public function clearUp(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Delete tax value');
		(new TaxRateSteps($scenario))->deleteTAXRatesOK($this->taxRateName);

		$client->wantTo('Delete tax group');
		(new TaxGroupSteps($scenario))->deleteVATGroupOK($this->taxGroupName);

		$client->wantTo('Delete user');
		(new UserManagerJoomla3Steps($scenario))->deleteUser($this->firstName);

		$client->wantTo('Test Order delete by user  in Administrator');
		(new OrderManagerJoomla3Steps($scenario))->deleteOrder($this->firstName);

		$I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
		$I->wantTo('Delete Product  in Administrator');
		$I->deleteProduct($this->productName);

		$I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
		$I->wantTo('Delete Category in Administrator');
		$I->deleteCategory($this->categoryName);
	}
}