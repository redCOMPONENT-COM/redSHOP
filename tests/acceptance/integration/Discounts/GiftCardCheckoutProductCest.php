<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class GiftCardCheckoutProductCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class GiftCardCheckoutProductCest
{
	public function __construct()
	{
		$this->faker           = Faker\Factory::create();
		$this->userName        = $this->faker->bothify('UserNameCheckoutProductCest ?##?');
		$this->password        = 'test';
		$this->email           = $this->faker->email;
		$this->shopperGroup    = 'Default Private';
		$this->group           = 'Administrator';
		$this->firstName       = $this->faker->bothify('ManageUserAdministratorCest FN ?##?');
		$this->lastName        = 'Last';
		$this->userInformation = array(
			"email"      => $this->email,
			"firstName"  => $this->faker->bothify('firstName ?###?'),
			"lastName"   => $this->faker->bothify('LastName ?###'),
			"address"    => "Some Place in the World",
			"postalCode" => "23456",
			"city"       => "Bangalore",
			"phone"      => "8787878787"
		);
		$this->randomCardName  = $this->faker->bothify('ManageGiftCardAdministratorCest Card ?##?');
		$this->cardPrice       = '10';
		$this->cardValue       = '10';
		$this->cardValidity    = $this->faker->numberBetween(2, 10);
		$this->couponCode      = null;

		//create category
		$this->categoryName = 'Testing Category ' . $this->faker->randomNumber();
		$this->noPage       = $this->faker->randomNumber();

		//create product
		$this->productName         = 'Testing ProductManagement' . rand(99, 999);
		$this->randomProductNumber = $this->faker->numberBetween(999, 9999);
		$this->randomProductPrice  = '24';
		$this->minimumPerProduct   = '1';
		$this->minimumQuantity     = 1;
		$this->maximumQuantity     = $this->faker->numberBetween(11, 100);
		$this->discountStart       = "2016-12-12";
		$this->discountEnd         = "2017-05-23";

		//Order change
		$this->status        = "Confirmed";
		$this->paymentStatus = "Paid";
		$this->newQuantity   = '1';
		//information checkout
		$this->checkoutAccountInformation = array(
			"accessId"        => "62qpC9xN9nN4",
			"transactionId"   => "97sRY6pGTea3E48d",
			"password"        => "Pull416!t",
			"debitCardNumber" => "4012888818888",
			"cvv"             => "1234",
			"cardExpiryMonth" => '2',
			"cardExpiryYear"  => '2016',
			"shippingAddress" => "some place on earth",
			"customerName"    => 'Testing Customer'
		);

	}

	/**
	 * Method for clean data.
	 *
	 * @param   mixed $scenario Scenario
	 *
	 * @return  void
	 */
	public function deleteData($scenario)
	{
		$I = new RedshopSteps($scenario);
		$I->clearAllData();
	}

	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * Function install payment_paypal and enable
	 */
	public function enablePayment(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Giftcard checkout on Frontend, Applying Giftcard to a Product, using paypal payment plugin for purchasing gift card');
		$I->amOnPage(\GiftCardCheckoutPage::$URLLoginAdmin);
		$I->wantTo('Enable redshop_payment_paypal Administrator');
		$I->wait(3);
		$path = $I->getConfig('redshop packages url') . 'plugins/plg_redshop_payment_rs_payment_paypal.zip';
		$I->comment( 'The path of payment');
		$I->wantTo($path);
		$I->installExtensionFromUrl($I->getConfig('redshop packages url') . 'plugins/plg_redshop_payment_rs_payment_paypal.zip');
		$I->wait(5);
		$I->enablePlugin('PayPal');
	}

	/**
	 *
	 * Create category
	 *
	 * @param AcceptanceTester $I
	 * @param                  $scenario
	 */
	public function createCategory(AcceptanceTester $I, $scenario)
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
	public function createProductSave(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Product Save Manager in Administrator');
		$I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductSave($this->productName, $this->categoryName, $this->randomProductNumber, $this->randomProductPrice, $this->minimumPerProduct, $this->minimumQuantity, $this->maximumQuantity, $this->discountStart, $this->discountEnd);
	}

	/**
	 * Function to Test User Creation in Backend
	 *
	 */
	public function createUser(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test User creation in Administrator');
		$I = new AcceptanceTester\UserManagerJoomla3Steps($scenario);
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperGroup, $this->firstName, $this->lastName, 'save');
		$I->searchUser($this->firstName);
	}

	/**
	 * Function to Test Gift Cards Creation in Backend
	 *
	 */
	public function createGiftCard(AcceptanceTester $I, $scenario)
	{
		$I = new AcceptanceTester($scenario);
		$I->wantTo('Test Gift Card creation in Administrator');
		$I = new AcceptanceTester\GiftCardManagerJoomla3Steps($scenario);
		$I->addCardNew($this->randomCardName, $this->cardPrice, $this->cardValue, $this->cardValidity, 'save');
	}

	/**
	 * Test to Verify the Gift Card Checkout
	 *
	 * @param   AcceptanceTester $I        Actor Class Object
	 * @param   String           $scenario Scenario Variable
	 *
	 * @return void
	 */
	public function testProductsGiftCardFrontEnd(AcceptanceTester $I, $scenario)
	{
		$I = new AcceptanceTester($scenario);
		$I->wantTo('Test Giftcard checkout on Frontend, Applying Giftcard to a Product, using Authorize payment plugin for purchasing gift card');
		$I = new AcceptanceTester\ProductCheckoutManagerJoomla3Steps($scenario);
		$this->checkoutGiftCardWithAuthorizePayment($I, $this->userInformation, $this->randomCardName);
	}

	/**
	 * Function to Test Checkout Process of a Gift Card using the Authorize Payment Plugin
	 *
	 * @param   AcceptanceTester $I                     Actor Class Object
	 * @param   String           $scenario              Scenario Variable
	 * @param   Array            $addressDetail         Address Detail
	 * @param   Array            $shipmentDetail        Shipping Address Detail
	 * @param   Array            $checkoutAccountDetail 2Checkout Account Detail
	 * @param   string           $giftCardName          Name of the Gift Card which needs to be purchased
	 *
	 * @return void
	 */
	private function checkoutGiftCardWithAuthorizePayment(AcceptanceTester $I, $addressDetail, $giftCardName)
	{
		$I->doFrontEndLogin($this->userName, $this->password);
		// here , can't get this link
		$I->amOnPage(\GiftCardCheckoutPage::$pageCart);
		$I->waitForElement(['link' => $giftCardName], 60);
		$I->click(['link' => $giftCardName]);
		$I->waitForElement(\GiftCardCheckoutPage::$reciverName);
		$I->fillField(\GiftCardCheckoutPage::$reciverName, $this->firstName);
		$I->fillField(\GiftCardCheckoutPage::$reciverEmail, $this->email);
		$I->click(\GiftCardCheckoutPage::$addToCart);
		$I->waitForText(\GiftCardCheckoutPage::$alertSuccessMessage, 60, \GiftCardCheckoutPage::$selectorSuccess);
		$I->see(GiftCardCheckoutPage::$alertSuccessMessage, \GiftCardCheckoutPage::$selectorSuccess);
		$I->amOnPage(\GiftCardCheckoutPage::$cartPageUrL);
		$I->seeElement(['link' => $giftCardName]);

		$I->click(\GiftCardCheckoutPage::$checkoutButton);
		$I->waitForElement(\GiftCardCheckoutPage::$paymentPayPad, 30);
		$I->click(\GiftCardCheckoutPage::$paymentPayPad);

		$I->click(\GiftCardCheckoutPage::$checkoutButton);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$addressEmail, 30);
		$I->fillField(\FrontEndProductManagerJoomla3Page::$addressEmail, $this->email);
		$I->fillField(\FrontEndProductManagerJoomla3Page::$addressFirstName, $this->firstName);
		$I->fillField(\FrontEndProductManagerJoomla3Page::$addressLastName, $this->lastName);
		$I->fillField(\FrontEndProductManagerJoomla3Page::$addressAddress, $addressDetail['address']);
		$I->fillField(\FrontEndProductManagerJoomla3Page::$addressPostalCode, $addressDetail['postalCode']);
		$I->fillField(\FrontEndProductManagerJoomla3Page::$addressCity, $addressDetail['city']);
		$I->fillField(\FrontEndProductManagerJoomla3Page::$addressPhone, $addressDetail['phone']);
		$I->click(GiftCardCheckoutPage::$buttonSave);

		$I->waitForElement(\GiftCardCheckoutPage::$addressLink, 30);
		$I->click(\GiftCardCheckoutPage::$paymentPayPad);
		$I->click(\GiftCardCheckoutPage::$checkoutButton);

		//accept
		$I->click(\GiftCardCheckoutPage::$acceptTerms);
		$I->click(\GiftCardCheckoutPage::$checkoutFinalStep);
	}

	/**
	 * Change status of order to paid for get coupon
	 *
	 * @param AcceptanceTester $I
	 * @param                  $scenario
	 */
	public function changeStatusOrder(AcceptanceTester $I, $scenario)
	{
		$I = new AcceptanceTester($scenario);
		$I = new AcceptanceTester\OrderManagerJoomla3Steps($scenario);
		$I->editOrder($this->firstName . ' ' . $this->lastName, $this->status, $this->paymentStatus, $this->newQuantity);
	}

	/**
	 * Function to Fetch the Coupon Code Generated by Gift Card
	 *
	 * @param   AcceptanceTester $I        Actor Class Object
	 * @param   String           $scenario Scenario Variable
	 *
	 * @return String
	 */
	public function fetchCouponCode(AcceptanceTester $I, $scenario)
	{
		$I = new AcceptanceTester($scenario);
		$I->amOnPage(\CouponPage::$url);
		$I->executeJS('window.scrollTo(0,0)');
		$I->click(['link' => 'ID']);
		$I->waitForElement(\CouponPage::$selectValueCoupon, 30);
		$I->click(\CouponPage::$selectValueCoupon);
		$I->waitForElement(\CouponPage::$idFromCode, 30);
		$this->couponCode = $I->grabValueFrom(\CouponPage::$idFromCode);
	}

	public function getGiftCartCheckout(AcceptanceTester $I, $scenario)
	{
		$I = new AcceptanceTester($scenario);
		$this->checkoutProductWithCouponCode($I, $this->productName, $this->categoryName, $this->couponCode);
		$this->deleteGiftCard($I, $scenario);
	}

	/**
	 * Function to Test Checkout Process of a Product using the Coupon Code
	 *
	 * @param   AcceptanceTester $I              Actor Class Object
	 * @param   String           $scenario       Scenario Variable
	 * @param   Array            $addressDetail  Address Detail
	 * @param   Array            $shipmentDetail Shipping Address Detail
	 * @param   string           $productName    Name of the Product
	 * @param   string           $categoryName   Name of the Category
	 * @param   string           $couponCode     Code for the Coupon
	 *
	 * @return void
	 */
	private function checkoutProductWithCouponCode(AcceptanceTester $I, $productName, $categoryName, $couponCode)
	{
		$I->doFrontEndLogin($this->userName, $this->password);
		$I->amOnPage(\FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$productFrontEndManagerPage = new \FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->click(\FrontEndProductManagerJoomla3Page::$addToCart);
		$I->waitForText(\GiftCardCheckoutPage::$alertSuccessMessage, 60, \GiftCardCheckoutPage::$selectorSuccess);
		$I->see(GiftCardCheckoutPage::$alertSuccessMessage, \GiftCardCheckoutPage::$selectorSuccess);
		$I->amOnPage(\GiftCardCheckoutPage::$cartPageUrL);
		$I->seeElement(['link' => $productName]);
		$I->fillField(\GiftCardCheckoutPage::$couponInput, $couponCode);
		$I->click(\GiftCardCheckoutPage::$couponButton);
		$I->waitForText(\GiftCardCheckoutPage::$messageValid, 10, \GiftCardCheckoutPage::$selectorSuccess);
		$I->see(\GiftCardCheckoutPage::$messageValid, \GiftCardCheckoutPage::$selectorSuccess);

		$I->see("DKK 24,00", \GiftCardCheckoutPage::$priceTotal);
		$I->see("DKK 10,00", \GiftCardCheckoutPage::$priceDiscount);
		$I->see("DKK 14,00", \GiftCardCheckoutPage::$priceEnd);
	}

	/**
	 * Function to Test Gift Card Deletion
	 *
	 */
	private function deleteGiftCard(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Deletion of Gift Card in Administrator');
		$I = new AcceptanceTester\GiftCardManagerJoomla3Steps($scenario);
		$I->deleteCard($this->randomCardName);
	}
}
