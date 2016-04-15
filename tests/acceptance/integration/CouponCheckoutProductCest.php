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
		$this->faker = Faker\Factory::create();
		$this->couponCode = $this->faker->bothify('CouponCheckoutProductCest ?##?');
		$this->couponValueIn = 'Total';
		$this->couponValue = '10';
		$this->couponType = 'Globally';
		$this->couponLeft = '10';
	}

	/**
	 * Test to Verify the Payment Plugin
	 *
	 * @param   AcceptanceTester  $I         Actor Class Object
	 * @param   String            $scenario  Scenario Variable
	 *
	 * @return void
	 */
	public function testProductsCouponFrontEnd(AcceptanceTester $I, $scenario)
	{
		$I = new AcceptanceTester($scenario);

		$I->wantTo('Test Product Checkout on Front End with 2 Checkout Payment Plugin');
		$I->doAdministratorLogin();

		$this->createCoupon($I, $scenario);
		$I->doAdministratorLogout();
		$I = new AcceptanceTester\ProductCheckoutManagerJoomla3Steps($scenario);
		$customerInformation = array(
			"email" => "test@test" . rand() . ".com",
			"firstName" => "Tester",
			"lastName" => "User",
			"address" => "Some Place in the World",
			"postalCode" => "23456",
			"city" => "Bangalore",
			"country" => "India",
			"state" => "Karnataka",
			"phone" => "8787878787"
		);

		$productName = 'redCOOKIE';
		$categoryName = 'Events and Forms';

		$this->checkoutProductWithCouponCode($I, $scenario, $customerInformation, $customerInformation, $productName, $categoryName, $this->couponCode);
		$I->doAdministratorLogin();
		$this->deleteCoupon($I, $scenario);
	}

	/**
	 * Function to Test Checkout Process of a Product using the Coupon Code
	 *
	 * @param   AcceptanceTester  $I               Actor Class Object
	 * @param   String            $scenario        Scenario Variable
	 * @param   Array             $addressDetail   Address Detail
	 * @param   Array             $shipmentDetail  Shipping Address Detail
	 * @param   string            $productName     Name of the Product
	 * @param   string            $categoryName    Name of the Category
	 * @param   string            $couponCode      Code for the Coupon
	 *
	 * @return void
	 */
	private function checkoutProductWithCouponCode(AcceptanceTester $I, $scenario, $addressDetail, $shipmentDetail, $productName = 'redCOOKIE', $categoryName = 'Events and Forms', $couponCode)
	{
		$I->amOnPage(\FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$categoryDiv,30);
		$I->checkForPhpNoticesOrWarnings();
		$productFrontEndManagerPage = new \FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$productList,30);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->click(\FrontEndProductManagerJoomla3Page::$addToCart);
		$I->waitForText("Product has been added to your cart.", 10, '.alert-message');
		$I->see("Product has been added to your cart.", '.alert-message');
		$I->amOnPage('index.php?option=com_redshop&view=cart');
		$I->checkForPhpNoticesOrWarnings();
		$I->seeElement(['link' => $productName]);
		$I->fillField(['id' => 'coupon_input'], $couponCode);
		$I->click(['id' => 'coupon_button']);
		$I->waitForText("The discount code is valid", 10, '.alert-success');
		$I->see("The discount code is valid", '.alert-success');
		$I->see("$ 24,00", ['class' => "lc-subtotal"]);
		$I->see("$ 10,00", ['class' => "lc-discount"]);
		$I->see("$ 14,00", ['class' => "lc-total"]);
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
	 * Function to Test Coupon Deletion
	 *
	 */
	private function deleteCoupon(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Deletion of Coupon in Administrator');
		$I = new AcceptanceTester\CouponManagerJoomla3Steps($scenario);
		$I->wantTo('Delete a Coupon');
		$I->deleteCoupon($this->couponCode);
		$I->searchCoupon($this->couponCode, 'Delete');
	}

}
