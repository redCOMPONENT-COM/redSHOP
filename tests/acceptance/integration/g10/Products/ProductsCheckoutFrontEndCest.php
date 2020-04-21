<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;

/**
 * Class ProductsCheckoutFrontEndCest
 * @since 3.0.2
 */
class ProductsCheckoutFrontEndCest
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
	 * @var int
	 * @since 3.0.2
	 */
	protected $minimumPerProduct;

	/**
	 * @var int
	 * @since 3.0.2
	 */
	protected $minimumQuantity;

	/**
	 * @var int
	 * @since 3.0.2
	 */
	protected $maximumQuantity;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $discountStart;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $discountEnd;

	/**
	 * @var int
	 * @since 3.0.2
	 */
	protected $randomProductNumber;

	/**
	 * @var int
	 * @since 3.0.2
	 */
	protected $randomProductPrice;

	/**
	 * @var array
	 * @since 3.0.2
	 */
	protected $customerInformation;

	/**
	 * ProductsCheckoutFrontEndCest constructor.
	 * @since 3.0.2
	 */
	public function __construct()
	{
		$this->faker               = Faker\Factory::create();
		$this->categoryName        = $this->faker->bothify('CategoryName ?###?');
		$this->productName         = $this->faker->bothify('Testing Product ??####?');
		$this->minimumPerProduct   = 1;
		$this->minimumQuantity     = 3;
		$this->maximumQuantity     = 5;
		$this->discountStart       = "2016-12-12";
		$this->discountEnd         = "2017-05-23";
		$this->randomProductNumber = rand(999, 9999);
		$this->randomProductPrice  = rand(99, 199);
		$this->customerInformation = array(
			"email"      => "test@test" . rand() . ".com",
			"firstName"  => "Tester",
			"lastName"   => "User",
			"address"    => "Some Place in the World",
			"postalCode" => "23456",
			"city"       => "Bangalore",
			"country"    => "India",
			"state"      => "Karnataka",
			"phone"      => "8787878787"
			);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function createCategory(AcceptanceTester $I, $scenario)
	{
		$I->doAdministratorLogin();
		$I->wantTo('Enable PayPal');
		$I->enablePlugin('PayPal');
		$I->wantTo('Create Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->wantTo('Create a Category');
		$I->addCategorySave($this->categoryName);
		$I->wantTo("create new product to checkout");
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductSave($this->productName, $this->categoryName, $this->randomProductNumber, $this->randomProductPrice, $this->minimumPerProduct, $this->minimumQuantity, $this->maximumQuantity, $this->discountStart, $this->discountEnd);
	}

	/**
	 * @param CheckoutOnFrontEnd $I
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function testProductsCheckoutFrontEnd(CheckoutOnFrontEnd $I)
	{
		$I->wantTo('Test Product Checkout on Front End with Bank Transfer');
		$I->checkOutWithBankTransfer($this->customerInformation, $this->customerInformation, $this->productName, $this->categoryName);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function clearAllData(AcceptanceTester $I, $scenario)
	{
		$I->doAdministratorLogin();
		$I->wantTo('Delete product');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->productName);

		$I->wantTo('Delete Category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->categoryName);
	}
}

