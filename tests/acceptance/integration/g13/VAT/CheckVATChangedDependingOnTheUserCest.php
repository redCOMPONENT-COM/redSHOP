<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\CategoryManagerJoomla3Steps as CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\TaxGroupSteps;
use AcceptanceTester\TaxRateSteps;
use AcceptanceTester\UserManagerJoomla3Steps as UserManagerJoomla3Steps;
use Configuration\ConfigurationSteps;

/**
 * Class CheckVATChangedDependingOnTheUserCest
 * @since 2.1.3
 */
class CheckVATChangedDependingOnTheUserCest
{
	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $taxGroupName;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $country;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $vatDefault;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $vatCalculation;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $vatAfter;

	/**
	 * @var int
	 * @since 2.1.3
	 */
	protected $vatNumber;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $calculationBase;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $requireVAT;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $taxRateNameDenmark;

	/**
	 * @var float
	 * @since 2.1.3
	 */
	protected $taxRateValueDenmark;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $countryDenmark;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $subtotalDenmark;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $vatPriceDenmark;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $totalDenmark;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $taxRateNameVN;

	/**
	 * @var float
	 * @since 2.1.3
	 */
	protected $taxRateValueVN;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $countryVietNam;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $subtotalVN;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $vatPriceVN;

	/**
	 * @var string
	 */
	protected $totalVN;

	/**
	 * @var string
	 */
	protected $categoryName;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $productName;

	/**
	 * @var int
	 * @since 2.1.3
	 */
	protected $randomProductNumber;

	/**
	 * @var int
	 * @since 2.1.3
	 */
	protected $randomProductPrice;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	protected $userVN;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	protected $userDM;

	/**
	 * @var \Faker\Generator
	 * @since 2.1.3
	 */
	protected $faker;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	protected $cartSetting;

	/**
	 * CheckVATChangedDependingOnTheUserCest constructor.
	 * @since 2.1.3
	 */
	public function __construct()
	{
		//groupVAT
		$this->faker = Faker\Factory::create();
		$this->taxGroupName             = $this->faker->bothify('TaxGroupsName ?###?');

		//configuration
		$this->country                  = 'Denmark';
		$this->vatDefault               = $this->taxGroupName;
		$this->vatCalculation           = 'Customer';
		$this->vatAfter                 = 'after';
		$this->vatNumber                = 0;
		$this->calculationBase          = 'billing';
		$this->requireVAT               = 'no';

		//VAT for User in Denmark
		$this->taxRateNameDenmark       = $this->faker->bothify('VAT Denmark ?###?');
		$this->taxRateValueDenmark      = 0.1;
		$this->countryDenmark           = 'Denmark';
		$this->subtotalDenmark          = "DKK 100,00";
		$this->vatPriceDenmark          = "DKK 10,00";
		$this->totalDenmark             = "DKK 110,00";

		//VAT for User in VN
		$this->taxRateNameVN            = $this->faker->bothify('VAT VN ?###?');
		$this->taxRateValueVN           = 0.2;
		$this->countryVietNam           = 'Viet Nam';
		$this->subtotalVN               = "DKK 100,00";
		$this->vatPriceVN               = "DKK 20,00";
		$this->totalVN                  = "DKK 120,00";

		//Categories
		$this->categoryName             = $this->faker->bothify('CategoryNameVAT ?###?');

		//Products
		$this->productName              = $this->faker->bothify('NameProductVAT ?###?');
		$this->randomProductNumber      = $this->faker->numberBetween(999, 9999);
		$this->randomProductPrice       = 100;

		$this->userVN = array(
			"userName"              => $this->faker->bothify('User In VN ?####?'),
			"password"              => $this->faker->bothify('Password VN ?##?'),
			"email"                 => $this->faker->email,
			"group"                 => 'Registered',
			"shopperGroup"          => 'Default Private',
			"firstName"             => $this->faker->bothify('User In VN ?##?'),
			"lastName"              => $this->faker->bothify('LastName ?####?'),
			"address"               => $this->faker->address,
			"zipcode"               => $this->faker->postcode,
			"city"                  => 'Ho Chi Minh',
			"phone"                 => $this->faker->phoneNumber,
			"country"               => 'Viet Nam'
		);

		//User in Denmark
		$this->userDM = array(
			"userName"              => $this->faker->bothify('User In DM ?####?'),
			"password"              =>$this->faker->bothify('Password DM ?##?'),
			"email"                 => $this->faker->email,
			"group"                 => 'Registered',
			"shopperGroup"          => 'Default Private',
			"firstName"             => $this->faker->bothify('User In DM ?##?'),
			"lastName"              => $this->faker->bothify('LastName ?####?'),
			"address"               => $this->faker->address,
			"zipcode"               => $this->faker->postcode,
			"city"                  => 'Ho Chi Minh',
			"phone"                 => $this->faker->phoneNumber,
			"country"               => 'Denmark'
		);

		//configuration enable one page checkout
		$this->cartSetting = array(
			"addCart"            => 'product',
			"allowPreOrder"      => 'yes',
			"cartTimeOut"        => $this->faker->numberBetween(100, 10000),
			"enabledAjax"        => 'no',
			"defaultCart"        => null,
			"buttonCartLead"     => 'Back to current view',
			"onePage"            => 'yes',
			"showShippingCart"   => 'no',
			"attributeImage"     => 'no',
			"quantityChange"     => 'no',
			"quantityInCart"     => 0,
			"minimumOrder"       => 0,
			"enableQuotation"    => 'no'
		);
	}

	/**
	 * @param AcceptanceTester $I
	 * @throws Exception
	 * @since 2.1.3
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.3
	 */
	public function CheckVATChangedDependingOnTheUserCest(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Disable PayPal');
		$I->disablePlugin('PayPal');
		$I->wantTo('VAT Groups - Save creation in Administrator');
		$I = new TaxGroupSteps($scenario);
		$I->addVATGroupsSave($this->taxGroupName);
		$I = new TaxRateSteps($scenario);
		$I->addTAXRatesSave($this->taxRateNameVN, $this->taxGroupName, $this->taxRateValueVN, $this->countryVietNam, null);
		$I->addTAXRatesSave($this->taxRateNameDenmark, $this->taxGroupName, $this->taxRateValueDenmark, $this->countryDenmark, null);

		$I->wantTo('Setup VAT at admin');
		$I = new ConfigurationSteps($scenario);
		$I->setupVAT($this->country, null, $this->vatDefault, $this->vatCalculation, $this->vatAfter, $this->vatNumber, $this->calculationBase, $this->requireVAT);
		$I->cartSetting($this->cartSetting);

		$I->wantTo('Create user have country');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->addUserHaveCountry($this->userVN);
		$I->addUserHaveCountry($this->userDM);

		$I->wantTo('Create new category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->categoryName);

		$I->wantTo('Create new product');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductSaveClose($this->productName, $this->categoryName, $this->randomProductNumber, $this->randomProductPrice);

		$I->wantTo('I Want to check VAT');
		$I = new CheckoutOnFrontEnd($scenario);
		$I->testProductWithVatCheckout($this->userDM["userName"], $this->userDM["password"], $this->productName, $this->categoryName, $this->subtotalDenmark, $this->vatPriceDenmark, $this->totalDenmark);
		$I->doFrontendLogout();
		$I = new CheckoutOnFrontEnd($scenario);
		$I->testProductWithVatCheckout($this->userVN["userName"], $this->userVN["password"], $this->productName, $this->categoryName, $this->subtotalVN, $this->vatPriceVN, $this->totalVN);

		$I->wantTo('Delete Tax Rates');
		$I = new TaxRateSteps($scenario);
		$I->deleteTAXRatesOK($this->taxRateNameVN);
		$I->deleteTAXRatesOK($this->taxRateNameDenmark);

		$I->wantTo('Delete VAT Group');
		$I = new TaxGroupSteps($scenario);
		$I->deleteVATGroupOK($this->taxGroupName);

		$I->wantTo('Delete User');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->userDM["firstName"]);
		$I->deleteUser($this->userVN["firstName"]);

		$I->wantTo('Delete Product');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->productName);

		$I->wantTo('Delete Category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->categoryName);
	}
}
