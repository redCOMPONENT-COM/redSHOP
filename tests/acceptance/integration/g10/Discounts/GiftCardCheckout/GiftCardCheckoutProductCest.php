<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\GiftCardManagerJoomla3Steps;
use AcceptanceTester\OrderManagerJoomla3Steps;
use AcceptanceTester\ProductCheckoutManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\UserManagerJoomla3Steps;

/**
 * Class GiftCardCheckoutProductCest
 * @since 3.0.2
 */
class GiftCardCheckoutProductCest
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
	protected $userName;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $password;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $email;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $shopperGroup;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $group;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $firstName;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $lastName;

	/**
	 * @var array
	 * @since 3.0.2
	 */
	protected $userInformation;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $randomCardName;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $cardPrice;

	/**
	 * @var int
	 * @since 3.0.2
	 */
	protected $cardValidity;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $cardValue;

	/**
	 * @var null
	 * @since 3.0.2
	 */
	protected $couponCode;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $categoryName;

	/**
	 * @var int
	 * @since 3.0.2
	 */
	protected $noPage;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $productName;

	/**
	 * @var int
	 * @since 3.0.2
	 */
	protected $randomProductNumber;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $randomProductPrice;

	/**
	 * @var string
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
	 * @var string
	 * @since 3.0.2
	 */
	protected $status;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $paymentStatus;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $newQuantity;

	/**
	 * @var array
	 * @since 3.0.2
	 */
	protected $dataCoupon;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $total;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $discountPrice;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $subTotal;

	/**
	 * GiftCardCheckoutProductCest constructor.
	 * @since 3.0.2
	 */
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

		$this->randomCardName = $this->faker->bothify('ManageGiftCardAdministratorCest Card ?##?');
		$this->cardPrice      = '10';
		$this->cardValue      = '10';
		$this->cardValidity   = $this->faker->numberBetween(2, 10);
		$this->couponCode     = null;

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

		$this->dataCoupon = array();
		$this->dataCoupon['code']        = $this->faker->bothify('Coupon Code ?##?');
		$this->dataCoupon['type']        = 'Total';
		$this->dataCoupon['value']       = '10';
		$this->dataCoupon['effect']      = 'Global';
		$this->dataCoupon['amount_left'] = $this->faker->numberBetween(99, 999);

		$this->total         = "DKK 24,00";
		$this->discountPrice = "DKK 10,00";
		$this->subTotal      = "DKK 14,00";
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 */
	public function checkoutWithPayment(AcceptanceTester $I, $scenario)
	{
		$I->doAdministratorLogin();
		$I->enablePlugin('PayPal');

		$I->wantTo('Test Category Save creation in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->wantTo('Create a Category Save button');
		$I->addCategorySave($this->categoryName);

		$I->wantTo('Test Product Save Manager in Administrator');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductSave($this->productName, $this->categoryName, $this->randomProductNumber, $this->randomProductPrice, $this->minimumPerProduct, $this->minimumQuantity, $this->maximumQuantity, $this->discountStart, $this->discountEnd);

		$I->wantTo('Test User creation in Administrator');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperGroup, $this->firstName, $this->lastName, 'save');
		$I->searchUser($this->firstName);

		$I->wantTo('Test Gift Card creation in Administrator');
		$I = new GiftCardManagerJoomla3Steps($scenario);
		$I->addCardNew($this->randomCardName, $this->cardPrice, $this->cardValue, $this->cardValidity, 'save');

		$I = new CouponSteps($scenario);
		$I->wantTo('Create a Coupon');
		$I->addNewItem($this->dataCoupon);

		$I->wantTo('Test Giftcard checkout on Frontend, Applying Giftcard to a Product, using Authorize payment plugin for purchasing gift card');
		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->checkoutGiftCard($this->userInformation, $this->randomCardName, $this->userName, $this->password, $this->firstName, $this->email);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 3.0.2
	 */
	 function changeStatusOrder(AcceptanceTester $I, $scenario)
	{
		$I->doAdministratorLogin();
		$I->wantTo('Change order status');
		$I = new OrderManagerJoomla3Steps($scenario);
		$I->editOrder($this-> userInformation['firstName'] . ' ' . $this-> userInformation['lastName'], $this->status, $this->paymentStatus, $this->newQuantity);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function fetchCouponCode(AcceptanceTester $I, $scenario)
	{
		$I->doAdministratorLogin();
		$I = new AcceptanceTester($scenario);
		$I->amOnPage(\CouponPage::$url);
		$I->executeJS('window.scrollTo(0,0)');
		$I->click(['link' => 'ID']);
		$I->waitForElement(\CouponPage::$selectValueCoupon, 30);
		$I->click(\CouponPage::$selectValueCoupon);
		$I->waitForElement(\CouponPage::$idFromCode, 30);
		$this->couponCode = $I->grabValueFrom(\CouponPage::$idFromCode);
	}

	/**
	 * @param CouponSteps $I
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function getGiftCartCheckout(CouponSteps $I)
	{
		$I->checkoutProductWithCouponCode($this->userName, $this->password, $this->productName, $this->categoryName, $this->couponCode, $this->total, $this->discountPrice, $this->subTotal);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function deleteGiftCard(AcceptanceTester $I, $scenario)
	{
		$I->doAdministratorLogin();
		$I->wantTo('Deletion of Gift Card in Administrator');
		$I = new GiftCardManagerJoomla3Steps($scenario);
		$I->deleteCard($this->randomCardName);

		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('Delete Product  in Administrator');
		$I->deleteProduct($this->productName);

		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->wantTo('Delete Category in Administrator');
		$I->deleteCategory($this->categoryName);

		$I = new CouponSteps($scenario);
		$I->wantTo('Create a Coupon');
		$I->deleteItem($this->dataCoupon['code']);
	}
}
