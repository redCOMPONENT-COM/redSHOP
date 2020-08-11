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
 * Class OnePageCheckoutWithCartAjaxCest
 * @since 2.1.4
 */
class OnePageCheckoutWithCartAjaxCest
{
	/**
	 * @var \Faker\Generator
	 * @since 2.1.4
	 */
	protected $faker;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $productName;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $categoryName;

	/**
	 * @var int
	 * @since 2.1.4
	 */
	protected $randomProductNumber;

	/**
	 * @var int
	 * @since 2.1.4
	 */
	protected $randomProductPrice;

	/**
	 * @var array
	 * @since 2.1.4
	 */
	protected $customerInformation;

	/**
	 * @var array
	 * @since 2.1.4
	 */
	protected $cartSetting;

	/**
	 * OnePageCheckoutWithCartAjaxCest constructor.
	 * @since 2.1.4
	 */
	public function __construct()
	{
		$this->faker               = Faker\Factory::create();
		$this->productName         = $this->faker->bothify('ProductName ?####?');
		$this->categoryName        = $this->faker->bothify('CategoryName ?####?');
		$this->randomProductNumber = $this->faker->numberBetween(999, 9999);
		$this->randomProductPrice  = 100;

		$this->customerInformation = array(
			"email"      => $this->faker->email,
			"firstName"  => $this->faker->bothify('First Name ?####?'),
			"lastName"   => $this->faker->bothify('Last name ?####?'),
			"address"    =>  $this->faker->address,
			"postalCode" => "23456",
			"city"       => "Ho Chi Minh",
			"country"    => "Viet Nam",
			"state"      => "Ho Chi Minh",
			"phone"      => "0334110366"
		);

		//configuration enable one page checkout
		$this->cartSetting = array(
			"addCart"           => 'product',
			"allowPreOrder"     => 'yes',
			"cartTimeOut"       => $this->faker->numberBetween(100, 10000),
			"enabledAjax"       => 'yes',
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
	 * @since 2.1.4
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.4
	 */
	public function checkoutWithCartAjax(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Config cart');
		$I = new ConfigurationSteps($scenario);
		$I->cartSetting($this->cartSetting);

		$I->wantTo('Create Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->categoryName);

		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductSaveClose($this->productName, $this->categoryName, $this->randomProductNumber, $this->randomProductPrice);

		$I = new CheckoutWithAjaxCart($scenario);
		$I->onePageCheckoutWithAjaxCart($this->categoryName, $this->productName, $this->customerInformation);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.4
	 */
	public function deleteData(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Disable one page checkout');
		$this->cartSetting["onePage"] = 'no';
		$this->cartSetting["enabledAjax"] = 'no';
		$I = new ConfigurationSteps($scenario);
		$I->cartSetting($this->cartSetting);

		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I want to delete product');
		$I->deleteProduct($this->productName);

		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->wantTo('I want to delete category');
		$I->deleteCategory($this->categoryName);
	}
}
