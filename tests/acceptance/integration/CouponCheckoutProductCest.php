<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class CouponCheckoutProductCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class CouponCheckoutProductCest
{
	public function __construct()
	{
		$this->faker               = Faker\Factory::create();
		$this->couponCode          = $this->faker->bothify('CouponCheckoutProductCest ?##?');
		$this->couponValueIn       = 'Total';
		$this->couponValue         = '10';
		$this->couponType          = 'Globally';
		$this->couponLeft          = '10';
		$this->categoryName        = 'Testing Category ' . $this->faker->randomNumber();
		$this->noPage              = $this->faker->randomNumber();
		$this->productName         = 'Testing Products' . rand(99, 999);
		$this->randomProductNumber = $this->faker->numberBetween(999, 9999);
		$this->randomProductPrice  = '24';
		$this->minimumPerProduct   = '1';
		$this->minimumQuantity     = 1;
		$this->maximumQuantity     = $this->faker->numberBetween(11, 100);
		$this->discountStart       = "12-12-2016";
		$this->discountEnd         = "23-05-2017";
	}

	/**
	 * Test to Verify the Payment Plugin
	 *
	 * @param   AcceptanceTester $I        Actor Class Object
	 * @param   String           $scenario Scenario Variable
	 *
	 * @return void
	 */

	public function delete(AcceptanceTester $I, $scenario)
	{
		$I= new AcceptanceTester\Redshop($scenario);
		$I->clearAllTables();
	}

	public function testProductsCouponFrontEnd(AcceptanceTester $I, $scenario)
	{
		$I = new AcceptanceTester($scenario);
		$I->wantTo('Test Product Checkout on Front End with 2 Checkout Payment Plugin');
		$I->doAdministratorLogin();
		$this->createCoupon($I, $scenario);
		$this->createCategory($I, $scenario);
		$this->createProductSave($I, $scenario);

		//process checkout
		$I = new AcceptanceTester\ProductCheckoutManagerJoomla3Steps($scenario);
		$I->checkoutProductWithCouponOrGift($this->productName, $this->categoryName, $this->couponCode);
	}

	/**
	 * Function to Test Coupon Creation in Backend
	 *
	 */
	private function createCoupon(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Coupon creation in Administrator');
		$I = new AcceptanceTester\CouponManagerJoomla3Steps($scenario);
		$I->wantTo('Create a Coupon');
		$I->addCoupon($this->couponCode, $this->couponValueIn, $this->couponValue, $this->couponType, $this->couponLeft);
		$I->searchCoupon($this->couponCode);
	}

	/**
	 *
	 * Create category
	 *
	 * @param AcceptanceTester $I
	 * @param                  $scenario
	 */
	private function createCategory(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Category Save creation in Administrator');
		$I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
		$I->wantTo('Create a Category Save button');
		$I->addCategorySave($this->categoryName);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param                  $scenario
	 *
	 */
	private function createProductSave(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Product Save Manager in Administrator');
		$I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductSave($this->productName, $this->categoryName, $this->randomProductNumber, $this->randomProductPrice, $this->minimumPerProduct, $this->minimumQuantity, $this->maximumQuantity, $this->discountStart, $this->discountEnd);
	}

	public function clearUp(AcceptanceTester $I, $scenario)
	{
		$I->doAdministratorLogin();

		$I->wantTo('Deletion of Coupon in Administrator');
		$I = new AcceptanceTester\CouponManagerJoomla3Steps($scenario);
		$I->wantTo('Delete a Coupon');
		$I->deleteCoupon($this->couponCode);
		$I->searchCoupon($this->couponCode, 'Delete');

		$I->wantTo('Delete product');
		$I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->productName);

		$I->wantTo('Delete Category');
		$I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->categoryName);
	}

}
