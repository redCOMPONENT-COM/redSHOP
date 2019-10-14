<?php
/**
 * @package     redSHOP
 * @subpackage  Cest ModuleMultiCurrencies
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\CategoryManagerJoomla3Steps as CategoryManagerJoomla3Steps;
use AcceptanceTester\OrderManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\TaxGroupSteps;
use AcceptanceTester\TaxRateSteps;
use AcceptanceTester\UserManagerJoomla3Steps as UserManagerJoomla3Steps;
use Configuration\ConfigurationSteps;

/**
 * Class OderBackendWithForeignCountryCest
 * @since 2.1.3
 */
class OderBackendWithForeignCountryCest
{
	/**
	 * @var \Faker\Generator
	 * @since 2.1.3
	 */
	protected $faker;

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
	 * @var string
	 * @since 2.1.3
	 */
	protected $attributeParameter;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	protected $attributes1;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	protected $attributes2;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	protected $attributes;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	protected $productOder;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $functionHaveVAT;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $functionNotVAT;

	/**
	 * OderBackendWithForeignCountryCest constructor.
	 * @since 2.1.3
	 */
	public function __construct()
	{
		$this->faker        = Faker\Factory::create();
		$this->taxGroupName = $this->faker->bothify('TaxGroupsName ?###?');

		//configuration
		$this->country         = 'Denmark';
		$this->vatDefault      = $this->taxGroupName;
		$this->vatCalculation  = 'Customer';
		$this->vatAfter        = 'after';
		$this->vatNumber       = 0;
		$this->calculationBase = 'billing';
		$this->requireVAT      = 'no';

		//VAT for User in Denmark
		$this->taxRateNameDenmark  = $this->faker->bothify('VAT Denmark ?###?');
		$this->taxRateValueDenmark = 0.1;
		$this->countryDenmark      = 'Denmark';

		//Categories
		$this->categoryName         = $this->faker->bothify('Category Name ?###?');

		//Products
		$this->productName         = $this->faker->bothify('Name Product ?###?');
		$this->randomProductNumber = $this->faker->numberBetween(999, 9999);
		$this->randomProductPrice  = 100;

		$this->userVN = array(
			"userName"     => $this->faker->bothify('User In VN ?####?'),
			"password"     => $this->faker->bothify('Password VN ?##?'),
			"email"        => $this->faker->email,
			"group"        => 'Registered',
			"shopperGroup" => 'Default Private',
			"firstName"    => $this->faker->bothify('User In VN ?##?'),
			"lastName"     => $this->faker->bothify('LastName ?####?'),
			"address"      => $this->faker->address,
			"zipcode"      => $this->faker->postcode,
			"city"         => 'Ho Chi Minh',
			"phone"        => $this->faker->phoneNumber,
			"country"      => 'Viet Nam'
		);

		//User in Denmark
		$this->userDM =
			array(
			"userName"     => $this->faker->bothify('User In DM ?####?'),
			"password"     =>$this->faker->bothify('Password DM ?##?'),
			"email"        => $this->faker->email,
			"group"        => 'Registered',
			"shopperGroup" => 'Default Private',
			"firstName"    => $this->faker->bothify('User In DM ?##?'),
			"lastName"     => $this->faker->bothify('LastName ?####?'),
			"address"      => $this->faker->address,
			"zipcode"      => $this->faker->postcode,
			"city"         => 'Ho Chi Minh',
			"phone"        => $this->faker->phoneNumber,
			"country"      => 'Denmark'
		);

		$this->attributeParameter = $this->faker->bothify('AttributeValue ??###?');

		$this->attributes1 = array(
			array(
				'subPropertyName'  => $this->faker->bothify('color ??###?'),
				'subPropertyPrice' => 0
			),
			array(
				'subPropertyName'  => $this->faker->bothify('color ??###?'),
				'subPropertyPrice' => 10
			)

		);

		$this->attributes2 = array(
			array(
				'subPropertyName'  => $this->faker->bothify('color ??###?'),
				'subPropertyPrice' => 0
			),
			array(
				'subPropertyName'  => $this->faker->bothify('color ??###?'),
				'subPropertyPrice' => 300
			)
		);

		$this->attributes = array(
			array(
				'attributeName'  => $this->faker->bothify('Size ??###?'),
				'attributePrice' => 0,
				'nameSubProperty' => $this->faker->bothify('Color'),
				'listSubProperty'  => $this->attributes1
			),
			array(
				'attributeName'  => $this->faker->bothify('Size ??###?'),
				'attributePrice' => 10,
				'nameSubProperty' => $this->faker->bothify('Color'),
				'listSubProperty'  => $this->attributes2
			)
		);

		$this->productOder =
		[
			'productName'   => $this->productName,
			'attributeName' => $this->attributeParameter,
			'size'          => $this->attributes[1]['attributeName'],
			'priceProduct'  => $this->randomProductPrice,
			'priceSize'     => 10,
			'color'         => $this->attributes[1]['listSubProperty'][1]['subPropertyName'],
			'priceColor'    => 300,
			"priceVAT"      => "DKK  0,00"
		];

		$this->functionHaveVAT = "HaveVAT";

		$this->functionNotVAT = "NotVAT";
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
		$I->wantTo('VAT Groups - Save creation in Administrator');
		$I = new TaxGroupSteps($scenario);
		$I->addVATGroupsSave($this->taxGroupName);
		$I = new TaxRateSteps($scenario);
		$I->addTAXRatesSave($this->taxRateNameDenmark, $this->taxGroupName, $this->taxRateValueDenmark, $this->countryDenmark, null);

		$I->wantTo('Setup VAT at admin');
		$I = new ConfigurationSteps($scenario);
		$I->setupVAT($this->country, null, $this->vatDefault, $this->vatCalculation, $this->vatAfter, $this->vatNumber, $this->calculationBase, $this->requireVAT);

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
		$I->createProductAttribute($this->productName, $this->attributeParameter, $this->attributes, $this->categoryName, $this->randomProductNumber, $this->randomProductPrice);

		$I = new OrderManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to create order with user DM');
		$I->addOrderWithVATWithUserForeignCountry($this->userDM['userName'], $this->functionHaveVAT, $this->taxRateValueDenmark, $this->productOder);

		$I->wantTo('I Want to create order with user VN');
		$I->addOrderWithVATWithUserForeignCountry($this->userVN['userName'], $this->functionNotVAT, $this->taxRateValueDenmark, $this->productOder);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.3
	 */
	public function deleteAlldata(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Delete Tax Rates');
		$I = new TaxRateSteps($scenario);
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