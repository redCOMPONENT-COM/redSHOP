<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\ShippingSteps;
use AcceptanceTester\UserManagerJoomla3Steps;
use Configuration\ConfigurationSteps;

/**
 * Class PromotionsManagementCest
 * @since 3.0.3
 */
class PromotionsManagementCest
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
	protected $firstName;

	/**
	 * @var string
	 * @since 3.0.3
	 */
	protected $lastName;

	/**
	 * @var array
	 * @since 3.0.3
	 */
	protected $customerInformation;

	/**
	 * @var array
	 * @since 3.0.3
	 */
	protected $cartSetting;

	/**
	 * @var string
	 * @since 3.0.3
	 */
	protected $productAwardsName;

	/**
	 * @var string
	 * @since 3.0.3
	 */
	protected $randomAwardsProductNumber;

	/**
	 * @var string
	 * @since 3.0.3
	 */
	protected $randomAwardsProductPrice;

	/**
	 * @var string
	 * @since 3.0.3
	 */
	protected $startDate;

	/**
	 * @var string
	 * @since 3.0.3
	 */
	protected $endDate;

	/**
	 * @var array
	 * @since 3.0.3
	 */
	protected $promotion1;

	/**
	 * @var array
	 * @since 3.0.3
	 */
	protected $promotion2;

	/**
	 * @var string
	 * @since 3.0.3
	 */
	protected $shippingMethod;

	/**
	 * @var array
	 * @since 3.0.3
	 */
	protected $shipping;

	/**
	 * WrappingFrontEndCest constructor.
	 * @since 3.0.3
	 */
	public function __construct()
	{
		$this->faker                     = Faker\Factory::create();
		$this->categoryName              = $this->faker->bothify('category name ?####?');
		$this->productName               = $this->faker->bothify('product name ?####?');
		$this->randomProductNumber       = $this->faker->numberBetween(999, 9999);
		$this->randomProductPrice        = $this->faker->numberBetween(100, 900);
		$this->productAwardsName         = $this->faker->bothify('product name ?####?');
		$this->randomAwardsProductNumber = $this->faker->numberBetween(999, 9999);
		$this->randomAwardsProductPrice  = $this->faker->numberBetween(100, 900);

		$this->customerInformation = array(
			"userName"     => $this->faker->userName,
			"password"     =>$this->faker->bothify('Password ?##?'),
			"email"        => $this->faker->email,
			"firstName"    => $this->faker->firstName,
			"lastName"     => $this->faker->lastName,
			"address"      => $this->faker->address,
			"postalCode"   => "5000",
			"city"         => "HCM",
			"country"      => "Denmark",
			"state"        => "Karnataka",
			"phone"        => "0909909999",
			"shopperGroup" => 'Default Private',
			'group'        => 'Registered'
		);

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

		$this->startDate = date('Y-m-d');
		$this->endDate   = date('Y-m-d', strtotime('+ 1 day', strtotime($this->startDate)));

		$this->promotion1 = array(
			"name"            => $this->faker->bothify('promotion name ?####?'),
			"desc"            => $this->faker->bothify('promotion desc ?####?'),
			"promotionType"   => 'Amount Product',
			"manufacturer"    => 'redcomponent.com',
			"category"        => $this->categoryName ,
			"product"         => $this->productName ,
			"conditionAmount" =>  $this->faker->numberBetween(1, 5),
			"fromDate"        => $this->startDate,
			"toDate"          => $this->endDate,
			"productAward"    => $this->productAwardsName,
			"awardAmount"     => $this->faker->numberBetween(1, 5),
			"freeShipping"    => 'Yes',
		);

		$this->promotion2 = array(
			"name"          => $this->faker->bothify('promotion name ?####?'),
			"desc"          => $this->faker->bothify('promotion desc ?####?'),
			"promotionType" => 'Volume Order',
			"category"      => $this->categoryName,
			"product"       => $this->productName,
			"orderVolume"   =>  $this->faker->numberBetween(10, 100),
			"fromDate"      => $this->startDate,
			"toDate"        => $this->endDate,
			"productAward"  => $this->productAwardsName,
			"awardAmount"   => $this->faker->numberBetween(1, 5),
			"freeShipping"  => 'No',
		);

		$this->shippingMethod = 'redSHOP - Standard Shipping';
		$this->shipping       = array(
			'shippingName' => $this->faker->bothify('TestingShippingRate ?##?'),
			'shippingRate' => 10
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
	public function createData(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Disable paypal plugin');
		$I->disablePlugin('PayPal');

		$I->wantTo('setup up one page checkout at config');
		$I = new ConfigurationSteps($scenario);
		$I->cartSetting($this->cartSetting);

		$I->wantTo('Create Category in administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->categoryName);

		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I want to add product inside the category');
		$I->createProductSaveClose($this->productName, $this->categoryName, $this->randomProductNumber, $this->randomProductPrice);

		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I want to add product inside the category');
		$I->createProductSaveClose($this->productAwardsName, $this->categoryName, $this->randomAwardsProductNumber, $this->randomAwardsProductPrice);

		$I = new ShippingSteps($scenario);
		$I->wantTo('Check create new Shipping rate with save & close button');
		$I->createShippingRateStandard($this->shippingMethod, $this->shipping, 'saveclose');

		$I = new UserManagerJoomla3Steps($scenario);
		$I->addUser($this->customerInformation['userName'], $this->customerInformation['password'], $this->customerInformation['email'], $this->customerInformation['group'],
			$this->customerInformation['shopperGroup'], $this->customerInformation['firstName'], $this->customerInformation['lastName'], 'saveclose');

		$I = new PromotionsManagementSteps($scenario);
		$I->wantTo('Create promotion');
		$I->createPromotion($this->promotion1, "Save & Close");
		$I->createPromotion($this->promotion2, "Save & Close");

		$I->wantTo('Checkout promotion with shipping');
		$I->checkoutPromotionWithShipping($this->promotion1, $this->shipping, $this->customerInformation);
		$I->checkoutPromotionWithShipping($this->promotion2, $this->shipping, $this->customerInformation);

		$I = new ConfigurationSteps($scenario);
		$I->wantTo('enable cart ajax');
		$this->cartSetting['enabledAjax'] = 'yes';
		$I->cartSetting($this->cartSetting);

		$I = new PromotionsManagementSteps($scenario);
		$I->wantTo('check promotion with cart ajax');
		$I->checkPromotionWithCartAjax($this->promotion1, $this->customerInformation);
		$I->checkPromotionWithCartAjax($this->promotion2, $this->customerInformation);
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

		$I->deleteProduct($this->productAwardsName);

		$I->wantTo('Delete category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->categoryName);

		$I->wantTo('Delete promotion');
		$I = new PromotionsManagementSteps($scenario);
		$I->deletePromotion($this->promotion1['name']);
		$I->deletePromotion($this->promotion2['name']);
	}
}