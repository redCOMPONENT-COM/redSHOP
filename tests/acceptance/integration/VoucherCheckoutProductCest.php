<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class VoucherCheckoutProductCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class VoucherCheckoutProductCest
{
	public function __construct()
	{
		$this->faker = Faker\Factory::create();
		$this->randomVoucherCode = $this->faker->bothify('VoucherCheckoutProductCest ?##?');
		$this->voucherAmount = 10;
		$this->voucherCount = $this->faker->numberBetween(99, 999);

		//create category
		$this->randomCategoryName = $this->faker->bothify('TestingCategory ?##');

		//create product
		$this->productName = 'Testing Products' . rand(99, 999);
		$this->minimumPerProduct = 1;
		$this->minimumQuantity = 1;
		$this->maximumQuantity = 5;
		$this->discountStart = "12-12-2016";
		$this->discountEnd = "23-05-2017";
		$this->randomProductNumber = rand(999, 9999);
		$this->randomProductPrice = rand(99, 199);

//		$this->faker= \Faker\Provider\DateTime::dateTime('now',null);
//		$this->startDate = $this->faker->setDate($format = 'Y-m-d', $max = 'now') ;
//		$this->endDate = $this->faker->fakeTime($max = 'now');

		//Create user
		$this->userName = $this->faker->bothify('GiftCardCheckoutProductCest ?##?');
		$this->password = 'test';
		$this->email = $this->faker->email;
		$this->shopperGroup = 'Default Private';
		$this->group = 'Administrator';
		$this->firstName = $this->faker->bothify('ManageUserAdministratorCest FN ?##?');
		$this->lastName = 'Last';
		$this->userInformation = array(
			"email" => $this->email,
			"firstName" => "Tester",
			"lastName" => "User",
			"address" => "Some Place in the World",
			"postalCode" => "23456",
			"city" => "Bangalore",
			"country" => "India",
			"state" => "Karnataka",
			"phone" => "8787878787"
		);
	}

	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}
////
//	public function createUser(AcceptanceTester $I, $scenario)
//	{
//		$I->wantTo('Test User creation in Administrator');
//		$I = new AcceptanceTester\UserManagerJoomla3Steps($scenario);
//		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperGroup, $this->firstName, $this->lastName, 'save');
//		$I->searchUser($this->firstName);
//	}



//add product to create order

	public function createCategory(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Voucher creation in Administrator');
		$I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->randomCategoryName);
	}

	public function createProduct(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Voucher creation in Administrator');
		$I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
		$I->createProductSave($this->productName, $this->randomCategoryName, $this->randomProductNumber, $this->randomProductPrice, $this->minimumPerProduct, $this->minimumQuantity, $this->maximumQuantity, $this->discountStart, $this->discountEnd);
	}

	public function addVoucher(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Voucher creation in Administrator');
		$I = new AcceptanceTester\VoucherManagerJoomla3Steps($scenario);
		$I->addVoucher($this->randomVoucherCode, $this->voucherAmount, $this->startDate, $this->endDate, $this->voucherCount, $this->productName, 'save');
	}


	/**
	 * Test to Verify the Voucher Checkout
	 *
	 * @param   AcceptanceTester $I Actor Class Object
	 * @param   String $scenario Scenario Variable
	 *
	 * @return void
	 */
//	public function testProductsVoucherFrontEnd(AcceptanceTester $I, $scenario)
//	{
//		$I = new AcceptanceTester($scenario);
//
//		$I->wantTo('Test Product Checkout on Front End with 2 Checkout Payment Plugin');
//		$I->doAdministratorLogin();
//
////		$I = new AcceptanceTester\ProductCheckoutManagerJoomla3Steps($scenario);
////		$I->doAdministratorLogout();
////		$customerInformation = array(
////			"email" => "test@test" . rand() . ".com",
////			"firstName" => "Tester",
////			"lastName" => "User",
////			"address" => "Some Place in the World",
////			"postalCode" => "23456",
////			"city" => "Bangalore",
////			"country" => "India",
////			"state" => "Karnataka",
////			"phone" => "8787878787"
////		);
//		$productName = 'redCOOKIE';
//		$categoryName = 'Events and Forms';
//		$this->createVoucher($I, $scenario, $productName);
//		$this->checkoutProductWithVoucherCode($I, $scenario, $customerInformation, $customerInformation, $productName, $categoryName, $this->randomVoucherCode);
//		$this->deleteVoucher($I, $scenario);
//	}

	/**
	 * Function to Test Checkout Process of a Product using the Voucher Code
	 *
	 * @param   AcceptanceTester $I Actor Class Object
	 * @param   String $scenario Scenario Variable
	 * @param   Array $addressDetail Address Detail
	 * @param   Array $shipmentDetail Shipping Address Detail
	 * @param   string $productName Name of the Product
	 * @param   string $categoryName Name of the Category
	 * @param   string $voucherCode Code for the Coupon
	 *
	 * @return void
	 */
//	public function checkoutProductWithVoucherCode(AcceptanceTester $I, $scenario, $addressDetail, $shipmentDetail, $productName = 'redCOOKIE', $categoryName = 'Events and Forms', $voucherCode)
	public function checkoutProductWithVoucherCode(AcceptanceTester $I, $productName = 'redCOOKIE', $categoryName = 'Events and Forms', $voucherCode)
	{
		$I->amOnPage(\FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
//		$I->checkForPhpNoticesOrWarnings();
		$productFrontEndManagerPage = new \FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->click(\FrontEndProductManagerJoomla3Page::$addToCart);
		$I->waitForText(\FrontEndProductManagerJoomla3Page::$alertSuccessMessage, 10, \FrontEndProductManagerJoomla3Page::$selectorSuccess);
		$I->see(\FrontEndProductManagerJoomla3Page::$alertSuccessMessage, \FrontEndProductManagerJoomla3Page::$selectorSuccess);
		$I->amOnPage('index.php?option=com_redshop&view=cart');
//		$I->checkForPhpNoticesOrWarnings();
		$I->seeElement(['link' => $productName]);
		$I->fillField(['id' => 'coupon_input'], $voucherCode);
		$I->click(['id' => 'coupon_button']);
		$I->waitForText("The discount code is valid", 10, '.alert-success');
		$I->see("The discount code is valid", '.alert-success');
		$I->see("$ 24,00", ['class' => "lc-subtotal"]);
		$I->see("$ 10,00", ['class' => "lc-discount"]);
		$I->see("$ 14,00", ['class' => "lc-total"]);
	}

//	/**
//	 * Function to Test Voucher Creation in Backend
//	 *
//	 */
//	public function createVoucher(AcceptanceTester $I, $scenario, $productName = 'redCOOKIE')
//	{
//		$I->wantTo('Test Voucher creation in Administrator');
//		$I->doAdministratorLogin();
//		$I = new AcceptanceTester\VoucherManagerJoomla3Steps($scenario);
//		$I->amOnPage(\VoucherManagerPage::$URL);
//		// @todo: fixme once REDSHOP-2845 will be fixed
//		// $I->click("ID");
//		$I->click('New');
//		$I->waitForElement(\VoucherManagerPage::$voucherCode, 30);
//		$I->fillField(\VoucherManagerPage::$voucherCode, $this->randomVoucherCode);
//		$I->fillField(\VoucherManagerPage::$voucherAmount, $this->voucherAmount);
//		$I->fillField(['id' => 's2id_autogen1'], $productName);
//		$I->waitForElement(['xpath' => "//div[@class='select2-result-label']"], 30);
//		$I->click(['xpath' => "//div[@class='select2-result-label']"]);
//		$I->fillField(\VoucherManagerPage::$voucherLeft, $this->voucherCount);
//		$I->click('Save & Close');
//		$I->waitForElement(['id' => 'system-message-container'], 60);
//		$I->scrollTo(['css' => '.alert-success']);
//		$I->see("Voucher details saved", '.alert-success');
//		$I->seeElement(['link' => $this->randomVoucherCode]);
//	}
//
//	/**
//	 * Function to Test Voucher Deletion
//	 *
//	 */
//	private function deleteVoucher(AcceptanceTester $I, $scenario)
//	{
//		$I->wantTo('Deletion of Voucher in Administrator');
//		$I = new AcceptanceTester\VoucherManagerJoomla3Steps($scenario);
//		$scenario->skip('@todo: once REDSHOP-2845 will be fixed the following line needs to be updated');
//		$I->deleteVoucher($this->randomVoucherCode);
//	}

}
