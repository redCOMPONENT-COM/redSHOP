<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\MassDiscountManagerJoomla3Steps;
use AcceptanceTester\ProductCheckoutManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\VoucherManagerJoomla3Steps;
use Codeception\Scenario;
use Configuration\ConfigurationSteps as ConfigurationSteps;
use Faker\Factory;

/**
 * Class CheckoutWithVoucherNotValidCest
 * @since 2.1.3
 */
class CheckoutWithVoucherNotValidCest
{
	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $categoryName;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $productName;

	/**
	 * @var int
	 * @since 2.1.3
	 */
	protected $randomProductNumber;

	/**
	 * @var int
	 * @since 2.1.3
	 */
	protected $randomProductPrice;

	/**
	 * @var int
	 * @since 2.1.3
	 */
	protected $minimumPerProduct;

	/**
	 * @var int
	 * @since 2.1.3
	 */
	protected $minimumQuantity;

	/**
	 * @var int
	 * @since 2.1.3
	 */
	protected $maximumQuantity;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $discountStart;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $discountEnd;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $randomVoucherCode;

	/**
	 * @var int
	 * @since 2.1.3
	 */
	protected $voucherAmount;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $startDate;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $endDate;

	/**
	 * @var int
	 * @since 2.1.3
	 */
	protected $voucherCount;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $invalidVoucher;

	/**
	 * CheckoutWithVoucherNotValidCest constructor.
	 */
	public function __construct()
	{
		$this->faker = Faker\Factory::create();
		$this->categoryName = $this->faker->bothify('TestingCategory ?##');
		$this->productName =  $this->faker->bothify('Testing ProductManagement ?##');
		$this->randomProductNumber = $this->faker->numberBetween(999, 9999);
		$this->randomProductPrice = $this->faker->numberBetween(300, 999);
		$this->minimumPerProduct = 2;
		$this->minimumQuantity = 3;
		$this->maximumQuantity = 5;
		$this->discountStart = "2019-01-05";
		$this->discountEnd = "2019-08-08";
		$this->randomVoucherCode = $this->faker->bothify('ManageVoucherAdministratorCest ?##?');
		$this->voucherAmount = $this->faker->numberBetween(9, 99);
		$this->startDate = "2019-01-05";
		$this->endDate = "2019-07-07";
		$this->voucherCount = $this->faker->numberBetween(99, 999);
		$this->invalidVoucher = $this->faker->bothify('invalidvoucher ?##?');

		$this->dataCoupon = array();
		$this->dataCoupon['code']        = $this->faker->bothify('Coupon Code ?##?');
		$this->dataCoupon['type']        = 'Total';
		$this->dataCoupon['value']       = '10';
		$this->dataCoupon['effect']      = 'Global';
		$this->dataCoupon['amount_left'] = $this->faker->numberBetween(99, 999);


		//$name, $discountType, $amount, $startDate, $endDate, $product
		$this->randomNameDisscount   = 'Testing Disscount' . rand(99, 999);
		$this->discountType      = 'Total';
		$this->randomAmount           = rand(99, 300);
		$this->startDate         = '29-07-' . date('Y', strtotime('+1 year'));
		$this->endDate           = '29-08-' . date('Y', strtotime('+1 year'));

		// price Discount/voucher/coupon
		$this->discount = array();
		$this->discount['enable'] = 'yes';
		$this->discount['allow']= 'Discount/voucher/coupon';
		$this->discount['enableCoupon']= 'yes';
		$this->discount['couponInfo'] = 'no';
		$this->discount['enableVoucher'] = 'no';
		$this->discount['spendTime'] = 'no';
		$this->discount['applyForProductDiscount'] = 'yes';
		$this->discount['calculate'] = 'total';
		$this->discount['valueOfDiscount'] = 'Total';

		//price Discount + voucher/coupon
		$this->discount1 = array();
		$this->discount1['enable'] = 'yes';
		$this->discount1['allow']= 'Discount + voucher/coupon';
		$this->discount1['enableCoupon']= 'yes';
		$this->discount1['couponInfo'] = 'no';
		$this->discount1['enableVoucher'] = 'no';
		$this->discount1['spendTime'] = 'yes';
		$this->discount1['applyForProductDiscount'] = 'yes';
		$this->discount1['calculate'] = 'total';
		$this->discount1['valueOfDiscount'] = 'Total';
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
	 * @since 2.1.3
	 */
	public function CheckOutWithVoucherNotValid(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Create category for use in Voucher test');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->categoryName);

		$I->wantTo('Create product for use in Voucher test');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->createProductSave($this->productName, $this->categoryName, $this->randomProductNumber, $this->randomProductPrice, $this->minimumPerProduct, $this->minimumQuantity, $this->maximumQuantity, $this->discountStart, $this->discountEnd);

		$I->wantTo('Create Voucher creation in Administrator');
		$I = new VoucherManagerJoomla3Steps($scenario);
		$I->addVoucher($this->randomVoucherCode, $this->voucherAmount, $this->startDate, $this->endDate, $this->voucherCount, $this->productName, 'save');

		$I->wantTo('Create a Coupon');
		$I = new CouponSteps($scenario);
		$I->addNewItem($this->dataCoupon);

		$I->wantToTest('Configuration for voucher/coupon/discount');
		$I = new ConfigurationSteps($scenario);
		$I->priceDiscount($this->discount);

		$I->wantTo('Test check out with invalid voucher');
		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->checkoutWithInvalidVoucher($this->productName, $this->categoryName, $this->invalidVoucher);
		$I->checkoutWithCouponAndVoucher($this->productName, $this->categoryName,$this->dataCoupon['code'], $this->randomVoucherCode);

		$I->wantTo('Test add mass discount');
		$I = new MassDiscountManagerJoomla3Steps($scenario);
		$I->addMassDiscount($this->randomNameDisscount, $this->randomAmount, $this->startDate, $this->endDate, $this->categoryName, $this->productName);

		$I->wantTo('Test check out with invalid voucher');
		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->checkoutWithDisscountAndVoucher($this->productName, $this->categoryName, $this->randomVoucherCode);

		$I->wantToTest('Configuration for Discount + voucher/coupon');
		$I = new ConfigurationSteps($scenario);
		$I->priceDiscount($this->discount1);

		$I->wantTo('Test check out with invalid voucher');
		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->checkoutWithDisscountCouponAndVoucher($this->productName, $this->categoryName,$this->dataCoupon['code'], $this->randomVoucherCode);

		$I->wantTo('Test delete Voucher in Administrator');
		$I = new VoucherManagerJoomla3Steps($scenario);
		$I->deleteAllVoucher($this->randomVoucherCode);

		$I->wantTo('Test delete Voucher in Administrator');
		$I = new CouponSteps($scenario);
		$I->deleteCoupon($this->dataCoupon['code']);

		$I->wantTo('Test delete massdiscount in Administrator');
		$I = new MassDiscountManagerJoomla3Steps($scenario);
		$I->deleteMassDiscountOK($this->randomNameDisscount);

		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('Delete Product  in Administrator');
		$I->deleteProduct($this->productName);

		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->wantTo('Delete Category in Administrator');
		$I->deleteCategory($this->categoryName);
	}
}