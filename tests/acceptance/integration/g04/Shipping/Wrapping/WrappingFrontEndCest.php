<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\OrderManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\WrapperSteps;
use Configuration\ConfigurationSteps;

/**
 * Class WrappingFrontEndCest
 * @since 3.0.3
 */
class WrappingFrontEndCest
{
	/**
	 * @var \Faker\Generator
	 * @since 3.0.3
	 */
	protected $faker;

	/**
	 * @var string
	 * @since 3.0.3
	 */
	public $categoryName;

	/**
	 * @var string
	 * @since 3.0.3
	 */
	public $productName;

	/**
	 * @var int
	 * @since 3.0.3
	 */
	public $productPrice;

	/**
	 * @var string
	 * @since 3.0.3
	 */
	public $total;

	/**
	 * @var string
	 * @since 3.0.3
	 */
	public $subtotal;

	/**
	 * @var int
	 * @since 3.0.3
	 */
	public $randomProductNumber;

	/**
	 * @var int
	 * @since 3.0.3
	 */
	public $randomProductPrice;

	/**
	 * @var string
	 * @since 3.0.3
	 */
	public $userName;

	/**
	 * @var string
	 * @since 3.0.3
	 */
	public $password;

	/**
	 * @var string
	 * @since 3.0.3
	 */
	protected $email;

	/**
	 * @var string
	 * @since 3.0.3
	 */
	protected $shopperGroup;

	/**
	 * @var string
	 * @since 3.0.3
	 */
	protected $group;

	/**
	 * @var string
	 * @since 3.0.3
	 */
	protected $firstName;

	/**
	 * @var string
	 * @since 3.0.3
	 */
	protected $lastName;

	/**
	 * @var integer
	 * @since 3.0.3
	 */
	public $minimumPerProduct;

	/**
	 * @var integer
	 * @since 3.0.3
	 */
	public $minimumQuantity;

	/**
	 * @var integer
	 * @since 3.0.3
	 */
	public $maximumQuantity;

	/**
	 * @var string
	 * @since 3.0.3
	 */
	public $discountStart;

	/**
	 * @var string
	 * @since 3.0.3
	 */
	public $discountEnd;

	/**
	 * @var array
	 * @since 3.0.3
	 */
	protected $customerInformation;

	/**
	 * @var string
	 * @since 3.0.3
	 */
	protected $shippingWithVat;

	/**
	 * @var array
	 * @since 3.0.3
	 */
	protected $cartSetting;

	/**
	 * @var string
	 * @since 3.0.3
	 */
	protected $wrappingName;

	/**
	 * @var string
	 * @since 3.0.3
	 */
	protected $wrappingPrice;

	/**
	 * WrappingFrontEndCest constructor.
	 * @since 3.0.3
	 */
	public function __construct()
	{
		$this->faker                  = Faker\Factory::create();
		$this->productName            = $this->faker->bothify('product name ?####?');
		$this->categoryName           = $this->faker->bothify('category name ?####?');
		$this->minimumPerProduct      = 1;
		$this->minimumQuantity        = 1;
		$this->maximumQuantity        = $this->faker->numberBetween(100, 1000);
		$this->discountStart          = "12-12-2016";
		$this->discountEnd            = "23-05-2017";
		$this->randomProductNumber    = $this->faker->numberBetween(999, 9999);
		$this->randomProductPrice     = $this->faker->numberBetween(100, 900);;

		$this->wrappingName  = $this->faker->bothify('wrapping name ?####?');
		$this->wrappingPrice = $this->faker->numberBetween(10, 20);
		
		$this->customerInformation = array(
			"email"      => $this->faker->email,
			"firstName"  => $this->faker->bothify('first Name ?####?'),
			"lastName"   => $this->faker->bothify('last Name ?####?'),
			"address"    => "Some Place in the World",
			"postalCode" => "23456",
			"city"       => "Ho Chi Minh",
			"country"    => "Viet Nam",
			"phone"      => "8787878787"
		);

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
	 * @since 3.0.3
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 3.0.3
	 */
	public function prepareData(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Disable paypal plugin');
		$I->disablePlugin('PayPal');

		$I->wantTo('setup up one page checkout at admin');
		$I = new ConfigurationSteps($scenario);
		$I->cartSetting($this->cartSetting);

		$I->wantTo('Create Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->categoryName);

		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I want to add product inside the category');
		$I->createProductSaveClose($this->productName, $this->categoryName, $this->randomProductNumber, $this->randomProductPrice);

		$I->wantTo('I want to create wrapping');
		$I = new WrapperSteps($scenario);
		$I->createWrapper($this->wrappingName, $this->categoryName, $this->wrappingPrice);
	}

	/**
	 * @param CheckoutOnFrontEnd $I
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function checkoutProductHaveWrapping(CheckoutOnFrontEnd $I)
	{
		$I->wantTo('Checkout with product have wrapping');
		$I->checkoutProductHaveWrapping($this->categoryName, $this->productName, $this->randomProductPrice, $this->wrappingName, $this->wrappingPrice, $this->customerInformation);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 3.0.3
	 */
	public function clearUpDatabase(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Delete product');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->productName);
		$I->wantTo('Delete Category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->categoryName);
		$I = new WrapperSteps($scenario);
		$I->deleteWrapper($this->wrappingName);
		$I->wantTo('Test Order delete by user  in Administrator');
		$I = new OrderManagerJoomla3Steps($scenario);
		$I->deleteOrder($this->customerInformation['firstName']);
	}
}