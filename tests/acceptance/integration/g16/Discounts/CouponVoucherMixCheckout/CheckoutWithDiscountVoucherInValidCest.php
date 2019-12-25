<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\VoucherManagerJoomla3Steps;

/**
 * Class CheckoutWithDiscountVoucherInValidCest
 * @since 2.1.4
 */
class CheckoutWithDiscountVoucherInValidCest
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
	protected $categoryName;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $productName;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $productNameDiscount;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $randomProductNumber;

	/**
	 * @var int
	 * @since 2.1.4
	 */
	protected $randomProductNumberDiscount;

	/**
	 * @var int
	 * @since 2.1.4
	 */
	protected $randomProductPrice;

	/**
	 * @var int
	 * @since 2.1.4
	 */
	protected $randomDiscountPrice;

	/**
	 * @var int
	 * @since 2.1.4
	 */
	protected $minimumPerProduct;

	/**
	 * @var int
	 * @since 2.1.4
	 */
	protected $minimumQuantity;

	/**
	 * @var int
	 * @since 2.1.4
	 */
	protected $maximumQuantity;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $discountStart;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $discountEnd;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $randomVoucherCode;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $voucherAmount;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $voucherCount;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $startDate;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $endDate;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $randomVoucherCodeSecond;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $voucherAmountSecond;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $voucherCountSecond;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $startDateSecond;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $endDateSecond;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $randomVoucherInValid;

	/**
	 * CheckoutWithDiscountVoucherInValidCest constructor.
	 * @since 2.1.4
	 */
	public function __construct()
	{
		$this->faker               = Faker\Factory::create();
		$this->categoryName        = 'Testing Category ' . $this->faker->randomNumber();
		$this->productName         = 'Product Management' . rand(99, 999);
		$this->productNameDiscount = 'Product Management Discount' . rand(99, 999);
		$this->randomProductNumber = $this->faker->numberBetween(999, 9999);
		$this->randomProductNumberDiscount = $this->faker->numberBetween(999, 9999);
		$this->randomProductPrice  = 150;
		$this->randomDiscountPrice = 100;
		$this->minimumPerProduct   = 1;
		$this->minimumQuantity     = 1;
		$this->maximumQuantity     = $this->faker->numberBetween(11, 100);
		$this->discountStart       = "12-01-2019";
		$this->discountEnd         = "23-05-2025";

		$this->randomVoucherCode = $this->faker->bothify('VoucherCheckoutProductCest ?##?');
		$this->voucherAmount     = 10;
		$this->voucherCount      = $this->faker->numberBetween(99, 999);
		$dateNow                 = date('Y-m-d');
		$this->startDate         = date('Y-m-d', strtotime('-2 day', strtotime($dateNow)));
		$this->endDate           = date('Y-m-d', strtotime('-1 day', strtotime($dateNow)));

		$this->randomVoucherCodeSecond = $this->faker->bothify('VoucherCheckoutProductCestSecond ?##?');
		$this->voucherAmountSecond     = 20;
		$this->voucherCountSecond      = $this->faker->numberBetween(99, 999);
		$this->startDateSecond         = "2019-1-25";
		$this->endDateSecond           = "2025-12-25";

		$this->randomVoucherInValid = $this->faker->bothify('Voucher InValid ?##?');
	}

	/**
	 * @param AcceptanceTester $I
	 * @throws Exception
	 * @since 2.1.3
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 */
	public function createData(AcceptanceTester $I, $scenario)
	{
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->wantTo('Create a Category Save button');
		$I->addCategorySave($this->categoryName);

		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductSaveClose($this->productName, $this->categoryName, $this->randomProductNumber, $this->randomProductPrice);

		$I->wantTo('I Want to add product inside the category');
		$I->createProductSaveHaveDiscount($this->productNameDiscount, $this->categoryName, $this->randomProductNumberDiscount, $this->randomProductPrice, $this->randomDiscountPrice, $this->minimumPerProduct, $this->minimumQuantity, $this->maximumQuantity, $this->discountStart, $this->discountEnd);

		$I = new VoucherManagerJoomla3Steps($scenario);
		$I->addVoucher($this->randomVoucherCode, $this->voucherAmount, $this->startDate, $this->endDate, $this->voucherCount, $this->productName, 'save');
		$I->addVoucher($this->randomVoucherCodeSecond, $this->voucherAmountSecond, $this->startDateSecond, $this->endDateSecond, $this->voucherCountSecond, $this->productNameDiscount, 'save');
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 */
	public function checkoutWithVoucherExpires(AcceptanceTester $I, $scenario)
	{
		$I = new CheckoutMissingData($scenario);
		$I->addToCart($this->categoryName, $this->productName);
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$cartPageUrL);
		$I->fillField(GiftCardCheckoutPage::$couponInput, $this->randomVoucherCode);
		$I->click(GiftCardCheckoutPage::$couponButton);
		$I->waitForText(GiftCardCheckoutPage::$messageInvalid, 10, GiftCardCheckoutPage::$selectorError);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$buttonEmptyCart, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$buttonEmptyCart);
	}

	/**
	 * @param CheckoutMissingData $I
	 * @throws Exception
	 */
	public function checkoutWithVoucherInvalid(CheckoutMissingData $I)
	{
		$I->wantToTest("checkout with product have voucher Expires");
		$I->checkoutWithVoucherInvalid($this->categoryName, $this->productName, $this->randomVoucherCode);

		$I->wantToTest("Checkout with Voucher does not exist");
		$I->checkoutWithVoucherInvalid($this->categoryName, $this->productName, $this->randomVoucherInValid);

		$I->wantToTest("Checkout with Vouchers of other products");
		$I->checkoutWithVoucherInvalid($this->categoryName, $this->productName, $this->randomVoucherCodeSecond);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 */
	public function clearUp(AcceptanceTester $I, $scenario)
	{
		$I = new VoucherManagerJoomla3Steps($scenario);
		$I->deleteVoucher($this->randomVoucherCode);
		$I->deleteVoucher($this->randomVoucherCodeSecond);

		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('Delete Product  in Administrator');
		$I->deleteProduct($this->productName);
		$I->deleteProduct($this->productNameDiscount);

		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->wantTo('Delete Category in Administrator');
		$I->deleteCategory($this->categoryName);
	}
}
