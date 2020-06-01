<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\CategoryManagerJoomla3Steps as CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\TaxGroupSteps;
use AcceptanceTester\TaxRateSteps;
use AcceptanceTester\UserManagerJoomla3Steps as UserManagerJoomla3Steps;
use Configuration\ConfigurationSteps;

/**
 * Class CheckVATWithUserMultiGroupCest
 * @since 3.0.2
 */
class CheckVATWithUserMultiGroupCest
{
	/**
	 * @var \Faker\Generator
	 * @since 3.0.2
	 */
	protected $faker;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $categoryName;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $productName;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $randomProductNumber;

	/**
	 * @var int
	 * @since 3.0.2
	 */
	protected $randomProductPrice;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $taxGroupName;

	/**
	 * @var array
	 * @since 3.0.2
	 */
	protected $taxRateCompany;

	/**
	 * @var array
	 * @since 3.0.2
	 */
	protected $taxRatePrivate;

	/**
	 * @var array
	 * @since 3.0.2
	 */
	protected $taxRateExempt;

	/**
	 * @var array
	 * @since 3.0.2
	 */
	protected $taxRateRegion;

	/**
	 * @var array
	 * @since 3.0.2
	 */
	protected $VATSetting;

	/**
	 * @var array
	 * @since 3.0.2
	 */
	protected $cartSetting;

	/**
	 * @var array
	 * @since 3.0.2
	 */
	protected $userCompany;

	/**
	 * @var array
	 * @since 3.0.2
	 */
	protected $userPrivate;

	/**
	 * @var array
	 * @since 3.0.2
	 */
	protected $userExemptVAT;

	/**
	 * @var array
	 * @since 3.0.2
	 */
	protected $userRegion;

	/**
	 * CheckVATWithUserMultiGroupCest constructor.
	 * @since 3.0.2
	 */
	public function __construct()
	{
		$this->faker               = Faker\Factory::create();
		$this->categoryName        = $this->faker->bothify('Category name ?###?');
		$this->productName         = $this->faker->bothify('Product name ?###?');
		$this->randomProductNumber = $this->faker->bothify('Product number ?###?');
		$this->randomProductPrice  = 100;

		// Vat group, vat rate
		$this->taxGroupName   = $this->faker->bothify('Tax Groups Name ?###?');
		$this->taxRateCompany = array(
				'name'         =>  $this->faker->bothify('Tax rate company ?###?'),
				'group'        => $this->taxGroupName,
				'shopperGroup' => "Default Company",
				'country'      => "Denmark",
				'amount'       => 0.1,
				'EUCountry'    => 'yes'
		);

		$this->taxRatePrivate = array(
			'name'         =>  $this->faker->bothify('Tax rate private ?###?'),
			'group'        => $this->taxGroupName,
			'shopperGroup' => "Default Private",
			'country'      => "Denmark",
			'amount'       => 0.2,
			'EUCountry'    => 'yes'
		);

		$this->taxRateExempt = array(
			'name'         =>  $this->faker->bothify('Tax rate exempt ?###?'),
			'group'        => $this->taxGroupName,
			'shopperGroup' => "Default Tax Exempt",
			'country'      => "Denmark",
			'amount'       => 0,
			'EUCountry'    => 'yes'
		);

		$this->taxRateRegion = array(
			'name'         =>  $this->faker->bothify('Tax rate Region ?###?'),
			'group'        => $this->taxGroupName,
			'shopperGroup' => "All",
			'country'      => "Viet Nam",
			'amount'       => 0.25,
			'EUCountry'    => 'yes'
		);

		// User information
		$this->userCompany = array(
			"userName"     => $this->faker->bothify('User name ?##?'),
			"password"     => $this->faker->bothify('Password ?##?'),
			"email"        => $this->faker->email,
			"group"        => 'Registered',
			"shopperGroup" => 'Default Company',
			"firstName"    => $this->faker->bothify('User company ?##?'),
			"lastName"     => $this->faker->bothify('Last name ?####?'),
			"address"      => $this->faker->address,
			"zipcode"      => "5000",
			"postalCode"   => "5000",
			"city"         => 'Odense',
			"phone"        => "0334110366",
			"country"      => 'Denmark',
			"companyName"  => $this->faker->company,
			"eanNumber"    => $this->faker->ean13,
			"vatNumber"    => '12345678',
		);

		$this->userPrivate = array(
			"userName"     => $this->faker->bothify('User name ?##?'),
			"password"     => $this->faker->bothify('Password ?##?'),
			"email"        => $this->faker->email,
			"group"        => 'Registered',
			"shopperGroup" => 'Default Private',
			"firstName"    => $this->faker->bothify('User Private ?##?'),
			"lastName"     => $this->faker->bothify('Last Name ?##?'),
			"address"      => $this->faker->address,
			"zipcode"      => "5000",
			"postalCode"   => "5000",
			"city"         => 'Odense',
			"phone"        => "0334110366",
			"country"      => 'Denmark'
		);

		$this->userExemptVAT = array(
			"userName"     => $this->faker->bothify('User name ?####?'),
			"password"     =>$this->faker->bothify('Password ?##?'),
			"email"        => $this->faker->email,
			"group"        => 'Registered',
			"shopperGroup" => 'Default Tax Exempt',
			"firstName"    => $this->faker->bothify('User name Exempt ?##?'),
			"lastName"     => $this->faker->bothify('LastName ?##?'),
			"address"      => $this->faker->address,
			"zipcode"      => $this->faker->postcode,
			"city"         => 'Odense',
			"phone"        => $this->faker->phoneNumber,
			"country"      => 'Denmark'
		);

		$this->userRegion = array(
			"userName"     => $this->faker->bothify('User Region ?####?'),
			"password"     =>$this->faker->bothify('Password ?##?'),
			"email"        => $this->faker->email,
			"group"        => 'Registered',
			"shopperGroup" => 'Default Private',
			"firstName"    => $this->faker->bothify('User name Region ?##?'),
			"lastName"     => $this->faker->bothify('LastName ?##?'),
			"address"      => $this->faker->address,
			"zipcode"      => "5000",
			"postalCode"   => "5000",
			"city"         => 'Odense',
			"phone"        => "0334110366",
			"country"      => 'Viet Nam'
		);

		// Vat setting
		$this->VATSetting = array(
			'country'         => 'Denmark',
			'vatGroup'        => $this->taxGroupName,
			'vatCalculation'  => 'EU-mode',
			'vatAfter'        => 'after',
			'vatNumber'       => 0,
			'calculationBase' => 'billing',
			'requireVAT'      => 'yes'
		);

		//configuration enable one page checkout
		$this->cartSetting = array(
			"addCart"          => 'product',
			"allowPreOrder"    => 'yes',
			"cartTimeOut"      => $this->faker->numberBetween(100, 10000),
			"enabledAjax"      => 'no',
			"defaultCart"      => null,
			"buttonCartLead"   => 'Back to current view',
			"onePage"          => 'yes',
			"showShippingCart" => 'no',
			"attributeImage"   => 'no',
			"quantityChange"   => 'no',
			"quantityInCart"   => 0,
			"minimumOrder"     => 0,
			"enableQuotation"  => 'no'
		);
	}

	/**
	 * @param AcceptanceTester $I
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function prepareData(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Create a new vat group');
		$I = new TaxGroupSteps($scenario);
		$I->addVATGroupsSave($this->taxGroupName);
		$I = new TaxRateSteps($scenario);
		$I->createVATRateEUMode($this->taxRateCompany, $this->taxGroupName);
		$I->createVATRateEUMode($this->taxRatePrivate, $this->taxGroupName);
		$I->createVATRateEUMode($this->taxRateExempt, $this->taxGroupName);
		$I->createVATRateEUMode($this->taxRateRegion, $this->taxGroupName);

		$I->wantTo('Setup VAT at admin');
		$I = new ConfigurationSteps($scenario);
		$I->setupVAT($this->VATSetting['country'], null, $this->VATSetting['vatGroup'], $this->VATSetting['vatCalculation'],
			$this->VATSetting['vatAfter'], $this->VATSetting['vatNumber'], $this->VATSetting['calculationBase'], $this->VATSetting['requireVAT']);
		$I->cartSetting($this->cartSetting);

		$I->wantTo('Create new category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->categoryName);

		$I->wantTo('Create new product');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductSaveClose($this->productName, $this->categoryName, $this->randomProductNumber, $this->randomProductPrice);

		$I = new UserManagerJoomla3Steps($scenario);
		$I->addUserWithMultiGroup($this->userCompany);
		$I->addUserWithMultiGroup($this->userPrivate);
		$I->addUserWithMultiGroup($this->userExemptVAT);
		$I->addUserWithMultiGroup($this->userRegion);
	}

	/**
	 * @param CheckoutOnFrontEnd $I
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function testProductWithUserMultiGroupWithOnePageCheckout(CheckoutOnFrontEnd $I)
	{
		$I->testProductWithUserMultiGroup($this->userPrivate, $this->productName, $this->categoryName, $this->randomProductPrice, $this->taxRatePrivate['amount'], "OnePage");
		$I->doFrontEndLogin($this->userCompany['userName'], $this->userCompany['password']);
		$I->doFrontendLogout();
		$I->testProductWithUserMultiGroup($this->userRegion, $this->productName, $this->categoryName, $this->randomProductPrice, $this->taxRateRegion['amount'], "OnePage");
		$I->doFrontEndLogin($this->userCompany['userName'], $this->userCompany['password']);
		$I->doFrontendLogout();
		$I->testProductWithUserMultiGroup($this->userCompany, $this->productName, $this->categoryName, $this->randomProductPrice, $this->taxRateCompany['amount'], "OnePage");
	}

	/**
	 * @param CheckoutOnFrontEnd $I
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function testProductWithUserMultiGroupWithUserLogin(CheckoutOnFrontEnd $I)
	{
		$I->testProductWithUserMultiGroup($this->userCompany, $this->productName, $this->categoryName, $this->randomProductPrice, $this->taxRateCompany['amount'], "Login");
		$I->testProductWithUserMultiGroup($this->userPrivate, $this->productName, $this->categoryName, $this->randomProductPrice, $this->taxRatePrivate['amount'], "Login");
		$I->testProductWithUserMultiGroup($this->userExemptVAT, $this->productName, $this->categoryName, $this->randomProductPrice, $this->taxRateExempt['amount'], "Login");
		$I->testProductWithUserMultiGroup($this->userRegion, $this->productName, $this->categoryName, $this->randomProductPrice, $this->taxRateRegion['amount'], "Login");
	}

	/**
	 * @param CheckoutOnFrontEnd $I
	 * @param $scenario
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function testProductWithUserMultiGroupWithNoOnePageCheckout(CheckoutOnFrontEnd $I, $scenario)
	{
		$I = new ConfigurationSteps($scenario);
		$this->cartSetting["onePage"] = 'no';
		$I->cartSetting($this->cartSetting);
		$I = new CheckoutOnFrontEnd($scenario);
		$I->testProductWithUserMultiGroup($this->userCompany, $this->productName, $this->categoryName, $this->randomProductPrice, $this->taxRateCompany['amount'], "NoOnePage");
		$I->doFrontEndLogin($this->userCompany['userName'], $this->userCompany['password']);
		$I->doFrontendLogout();
		$I->testProductWithUserMultiGroup($this->userPrivate, $this->productName, $this->categoryName, $this->randomProductPrice, $this->taxRatePrivate['amount'], "NoOnePage");
		$I->doFrontEndLogin($this->userCompany['userName'], $this->userCompany['password']);
		$I->doFrontendLogout();
		$I->testProductWithUserMultiGroup($this->userRegion, $this->productName, $this->categoryName, $this->randomProductPrice, $this->taxRateRegion['amount'], "NoOnePage");
	}

	/**
	 * @param TaxRateSteps $I
	 * @param $scenario
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function clearAllData(TaxRateSteps $I, $scenario)
	{
		$I->deleteTAXRatesOK($this->taxRateCompany['name']);
		$I->deleteTAXRatesOK($this->taxRateRegion['name']);
		$I->deleteTAXRatesOK($this->taxRateExempt['name']);
		$I->deleteTAXRatesOK($this->taxRatePrivate['name']);

		$I->wantTo('Delete VAT Group');
		$I = new TaxGroupSteps($scenario);
		$I->deleteVATGroupOK($this->taxGroupName);

		$I->wantTo('Delete User');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->userCompany["firstName"]);
		$I->deleteUser($this->userPrivate["firstName"]);
		$I->deleteUser($this->userExemptVAT["firstName"]);
		$I->deleteUser($this->userRegion["firstName"]);

		$I->wantTo('Delete Product');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->productName);

		$I->wantTo('Delete Category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->categoryName);
	}
}
