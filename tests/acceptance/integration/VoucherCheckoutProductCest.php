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
	/**
	 * @var \Faker\Generator
	 */
	public $faker;

	/**
	 * @var string
	 */
	public $randomVoucherCode;

	/**
	 * @var int
	 */
	public $voucherAmount;

	/**
	 * @var int
	 */
	public $voucherCount;

	/**
	 * @var string
	 */
	public $startDate;

	/**
	 * @var string
	 */
	public $endDate;

	/**
	 * @var string
	 */
	public $randomCategoryName;

	/**
	 * @var string
	 */
	public $productName;

	/**
	 * @var int
	 */
	public $minimumPerProduct;

	/**
	 * @var int
	 */
	public $minimumQuantity;

	/**
	 * @var string
	 */
	public $discountStart;

	/**
	 * @var string
	 */
	public $discountEnd;

	/**
	 * @var int
	 */
	public $maximumQuantity;

	/**
	 * @var int
	 */
	public $randomProductNumber;

	/**
	 * @var int
	 */
	public $randomProductPrice;


	public function __construct()
	{
		$this->faker = Faker\Factory::create();
		//create voucher
		$this->randomVoucherCode = $this->faker->bothify('VoucherCheckoutProductCest ?##?');
		$this->voucherAmount     = 10;
		$this->voucherCount      = $this->faker->numberBetween(99, 999);
		$this->startDate         = "21-06-2017";
		$this->endDate           = "07-07-2017";

		//create category
		$this->randomCategoryName = $this->faker->bothify('TestingCategory ?##');

		//create product
		$this->productName         = 'Testing Products' . rand(99, 999);
		$this->minimumPerProduct   = 1;
		$this->minimumQuantity     = 1;
		$this->maximumQuantity     = 5;
		$this->discountStart       = "12-12-2016";
		$this->discountEnd         = "23-05-2017";
		$this->randomProductNumber = rand(999, 9999);
		$this->randomProductPrice  = 24;
	}

	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 *
	 * Function create category
	 *
	 * @param AcceptanceTester $I
	 * @param                  $scenario
	 *
	 */
	public function createCategory(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Voucher creation in Administrator');
		$I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->randomCategoryName);
	}

	/**
	 *
	 * Function create product
	 *
	 * @param AcceptanceTester $I
	 * @param                  $scenario
	 *
	 * @depends createCategory
	 *
	 */
	public function createProduct(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Voucher creation in Administrator');
		$I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
		$I->createProductSave($this->productName, $this->randomCategoryName, $this->randomProductNumber, $this->randomProductPrice, $this->minimumPerProduct, $this->minimumQuantity, $this->maximumQuantity, $this->discountStart, $this->discountEnd);
	}

	/**
	 * Function create voucher and checkout
	 *
	 * @param AcceptanceTester $I
	 *
	 * @param                  $scenario
	 *
	 * @depends createProduct
	 */
	public function addVoucher(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Voucher creation in Administrator');
		$I = new AcceptanceTester\VoucherManagerJoomla3Steps($scenario);
		$I->addVoucher($this->randomVoucherCode, $this->voucherAmount, $this->startDate, $this->endDate, $this->voucherCount, $this->productName, 'validday');
		$this->checkoutProductWithVoucherCode($I, $this->productName, $this->randomCategoryName, $this->randomVoucherCode);
	}

	/**
	 * Function to Test Checkout Process of a Product using the Voucher Code
	 *
	 * @param   AcceptanceTester $I              Actor Class Object
	 * @param   String           $scenario       Scenario Variable
	 * @param   Array            $addressDetail  Address Detail
	 * @param   Array            $shipmentDetail Shipping Address Detail
	 * @param   string           $productName    Name of the Product
	 * @param   string           $categoryName   Name of the Category
	 * @param   string           $voucherCode    Code for the Coupon
	 *
	 * @return void
	 */
	private function checkoutProductWithVoucherCode(AcceptanceTester $I, $productName, $categoryName, $voucherCode)
	{
		$I->amOnPage(\FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$productFrontEndManagerPage = new \FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->click(\FrontEndProductManagerJoomla3Page::$addToCart);
		$I->waitForText(\FrontEndProductManagerJoomla3Page::$alertSuccessMessage, 10, \FrontEndProductManagerJoomla3Page::$selectorSuccess);
		$I->see(\FrontEndProductManagerJoomla3Page::$alertSuccessMessage, \FrontEndProductManagerJoomla3Page::$selectorSuccess);
		$I->amOnPage(\GiftCardCheckoutPage::$cartPageUrL);
		$I->seeElement(['link' => $productName]);
		$I->fillField(\GiftCardCheckoutPage::$couponInput, $voucherCode);
		$I->click(\GiftCardCheckoutPage::$couponButton);
		$I->see(\GiftCardCheckoutPage::$messageInvalid, \AdminJ3Page::$selectorSuccess);
		$I->see("DKK 24,00", \GiftCardCheckoutPage::$priceTotal);
		$I->see("DKK 10,00", \GiftCardCheckoutPage::$priceDiscount);
		$I->see("DKK 14,00", \GiftCardCheckoutPage::$priceEnd);
	}


	/**
	 * Function to Test Voucher Deletion
	 *
	 * @depends addVoucher
	 */
	public function deleteVoucher(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Deletion of Voucher in Administrator');
		$I = new AcceptanceTester\VoucherManagerJoomla3Steps($scenario);
		$I->deleteVoucher($this->randomVoucherCode);
	}


}
