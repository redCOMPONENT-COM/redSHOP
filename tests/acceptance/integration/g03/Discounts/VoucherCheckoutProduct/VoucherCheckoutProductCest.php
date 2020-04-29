<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\VoucherManagerJoomla3Steps;

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
	 * @since 1.4.0
	 */
	public $faker;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public $randomVoucherCode;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	public $voucherAmount;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	public $voucherCount;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public $startDate;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public $endDate;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public $randomCategoryName;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public $productName;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	public $minimumPerProduct;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	public $minimumQuantity;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public $discountStart;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public $discountEnd;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	public $maximumQuantity;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	public $randomProductNumber;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	public $randomProductPrice;

	/**
	 * VoucherCheckoutProductCest constructor.
	 * @since 1.4.0
	 */
	public function __construct()
	{
		$this->faker = Faker\Factory::create();
		//create voucher
		$this->randomVoucherCode = $this->faker->bothify('VoucherCheckoutProductCest ?##?');
		$this->voucherAmount     = 10;
		$this->voucherCount      = $this->faker->numberBetween(99, 999);
		$this->startDate         = "2017-06-21";
		$this->endDate           = "2017-07-07";

		//create category
		$this->randomCategoryName = $this->faker->bothify('TestingCategory ?##');

		//create product
		$this->productName         = 'Testing ProductManagement' . rand(99, 999);
		$this->minimumPerProduct   = 1;
		$this->minimumQuantity     = 1;
		$this->maximumQuantity     = 5;
		$this->discountStart       = "2016-12-12";
		$this->discountEnd         = "2017-05-23";
		$this->randomProductNumber = rand(999, 9999);
		$this->randomProductPrice  = 24;
	}

	/**
	 * @param AcceptanceTester $I
	 * @throws Exception
	 * @since 1.4.0
	 */
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
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function createCategory(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Voucher creation in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->randomCategoryName);

		$I->wantTo('Test Voucher creation in Administrator');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->createProductSave($this->productName, $this->randomCategoryName, $this->randomProductNumber, $this->randomProductPrice, $this->minimumPerProduct, $this->minimumQuantity, $this->maximumQuantity, $this->discountStart, $this->discountEnd);

		$I->wantTo('Test Voucher creation in Administrator');
		$I = new VoucherManagerJoomla3Steps($scenario);
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
	 * @throws \Exception
	 * @since 1.4.0
	 */
	private function checkoutProductWithVoucherCode(AcceptanceTester $I, $productName, $categoryName, $voucherCode)
	{
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$addToCart, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$addToCart);
		$I->amOnPage(\GiftCardCheckoutPage::$cartPageUrL);
		$I->seeElement(['link' => $productName]);
		$I->fillField(\GiftCardCheckoutPage::$couponInput, $voucherCode);
		$I->click(\GiftCardCheckoutPage::$couponButton);
		$I->see(\GiftCardCheckoutPage::$messageValid, \AdminJ3Page::$selectorSuccess);
		$I->see("DKK 24,00", \GiftCardCheckoutPage::$priceTotal);
		$I->see("DKK 10,00", \GiftCardCheckoutPage::$priceDiscount);
		$I->see("DKK 14,00", \GiftCardCheckoutPage::$priceEnd);
	}

	/**
	 * Function to Test Voucher Deletion
	 *
	 * @depends createCategory
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function deleteVoucher(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Deletion of Voucher in Administrator');
		$I = new VoucherManagerJoomla3Steps($scenario);
		$I->deleteVoucher($this->randomVoucherCode);

		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('Delete Product  in Administrator');
		$I->deleteProduct($this->productName);

		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->wantTo('Delete Category in Administrator');
		$I->deleteCategory($this->randomCategoryName);
	}
}
