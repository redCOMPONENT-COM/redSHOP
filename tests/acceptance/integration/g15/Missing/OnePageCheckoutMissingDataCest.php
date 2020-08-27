<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use Configuration\ConfigurationSteps as ConfigurationSteps;

/**
 * Class OnePageCheckoutMissingDataCest
 * @since 2.1.2
 */
class OnePageCheckoutMissingDataCest
{
	/**
	 * @var \Faker\Generator
	 * @since 2.1.2
	 */
	protected $faker;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public $categoryName;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public $productName;

	/**
	 * @var int
	 * @since 2.1.2
	 */
	public $productPrice;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public $total;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public $subtotal;

	/**
	 * @var int
	 * @since 2.1.2
	 */
	public $randomProductNumber;

	/**
	 * @var int
	 * @since 2.1.2
	 */
	public $randomProductPrice;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public $userName;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public $password;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $email;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $shopperGroup;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $group;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $firstName;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $lastName;

	/**
	 * @var integer
	 * @since 2.1.2
	 */
	public $minimumPerProduct;

	/**
	 * @var integer
	 * @since 2.1.2
	 */
	public $minimumQuantity;

	/**
	 * @var integer
	 * @since 2.1.2
	 */
	public $maximumQuantity;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public $discountStart;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public $discountEnd;

	/**
	 * @var array
	 * @since 2.1.2
	 */
	protected $customerInformation;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $business;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $private;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $createAccount;

	/**
	 * @var array
	 * @since 2.1.2
	 */
	protected $cartSetting;

	/**
	 * @var array
	 * @since 2.1.2
	 */
	protected $customerBussinesInformation;

	/**
	 * OnePageCheckoutMissingDataCest constructor.
	 * @since 2.1.2
	 */
	public function __construct()
	{
		$this->faker                  = Faker\Factory::create();
		$this->productName            =  $this->faker->bothify('productName ?####?');
		$this->categoryName           =  $this->faker->bothify('categoryName ?####?');
		$this->minimumPerProduct      = 1;
		$this->minimumQuantity        = 1;
		$this->maximumQuantity        = $this->faker->numberBetween(100, 1000);
		$this->discountStart          = "12-12-2016";
		$this->discountEnd            = "23-05-2017";
		$this->randomProductNumber    = $this->faker->numberBetween(999, 9999);
		$this->randomProductPrice     = 100;

		$this->business               = "business";
		$this->private                = "private";
		$this->createAccount          = "createAccount";

		$this->customerInformation    = array(
			"email"      => "test@test" . rand() . ".com",
			"firstName"  => $this->faker->bothify('firstNameCustomer ?####?'),
			"lastName"   => $this->faker->bothify('lastNameCustomer ?####?'),
			"address"    => "Some Place in the World",
			"postalCode" => "5000",
			"city"       => "Odense SØ",
			"country"    => "Denmark",
			"state"      => "Blangstedgaardsvej 1",
			"phone"      => "8787878787"
		);

		$this->customerBussinesInformation = array(
			"email"          => "test@test" . rand() . ".com",
			"companyName"    => "CompanyName",
			"businessNumber" => 1231312,
			"firstName"      => $this->faker->bothify('firstName ?####?'),
			"lastName"       => $this->faker->bothify('lastName ?####?'),
			"address"        => "Some Place in the World",
			"postalCode"     => "5000",
			"city"           => "Odense SØ",
			"country"        => "Denmark",
			"state"          => "Blangstedgaardsvej 1",
			"phone"          => "8787878787",
			"eanNumber"      => 1212331331231,
		);

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
	 * @param $scenario
	 * @since 2.1.2
	 * @throws Exception
	 */
	public function onePageCheckoutMissing(AcceptanceTester $I, $scenario)
	{
		$I->doAdministratorLogin();
		$I->enablePlugin('PayPal');

		$I->wantTo('setup up one page checkout at admin');
		$I = new ConfigurationSteps($scenario);
		$I->cartSetting($this->cartSetting);

		$I->wantTo('Create Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->categoryName);

		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductSaveClose($this->productName, $this->categoryName, $this->randomProductNumber, $this->randomProductPrice);

		$I = new CheckoutMissingData($scenario);
		$I->addToCart($this->categoryName, $this->productName );
		$I->wantToTest('Check out with missing user');
		$I->onePageCheckoutMissingWithUserPrivate($this->productName, $this->customerInformation, 'user');
		$I->onePageCheckoutMissingWithUserBusiness($this->productName, $this->customerBussinesInformation, 'user');

		$I->wantToTest('Check out with missing click accept Terms');
		$I->onePageCheckoutMissingWithUserPrivate($this->productName, $this->customerInformation, 'acceptTerms');
		$I->onePageCheckoutMissingWithUserBusiness($this->productName, $this->customerBussinesInformation, 'acceptTerms');

		$I->wantToTest('Check out with wrong address email');
		$this->customerInformation['email'] = "test";
		$I->onePageCheckoutMissingWithUserPrivate($this->productName, $this->customerInformation, 'wrongEmail');
		$this->customerBussinesInformation['email'] = "test";
		$I->onePageCheckoutMissingWithUserBusiness($this->productName, $this->customerBussinesInformation, 'wrongEmail');
		$this->customerInformation['email'] =  "test@test" . rand() . ".com";
		$this->customerBussinesInformation['email'] = "test@test" . rand() . ".com";

		$I->wantToTest('Check out with wrong phone number');
		$this->customerInformation['phone'] = "test";
		$I->onePageCheckoutMissingWithUserPrivate( $this->productName, $this->customerInformation, 'wrongPhone');
		$this->customerBussinesInformation['phone'] = "test";
		$I->onePageCheckoutMissingWithUserBusiness( $this->productName, $this->customerBussinesInformation, 'wrongPhone');
		$this->customerBussinesInformation['phone'] = "8787878787";
		$this->customerInformation['phone'] = "8787878787";

		$I->wantToTest('Check out with wrong EAN Number');
		$this->customerBussinesInformation['eanNumber'] = "test";
		$I->onePageCheckoutMissingWithUserBusiness( $this->productName, $this->customerBussinesInformation, 'wrongEAN');
	}

	/**
	 * @param ProductManagerJoomla3Steps $I
	 * @param $scenario
	 * @since 2.1.2
	 * @throws Exception
	 */
	public function clearUpDatabase(ProductManagerJoomla3Steps $I, $scenario)
	{
		$I->doAdministratorLogin();
		$I->wantTo('Disable one page checkout');
		$this->cartSetting["onePage"] = 'no';
		$I = new ConfigurationSteps($scenario);
		$I->cartSetting($this->cartSetting);

		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('Delete Product  in Administrator');
		$I->deleteProduct($this->productName);

		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->wantTo('Delete Category in Administrator');
		$I->deleteCategory($this->categoryName);
	}
}