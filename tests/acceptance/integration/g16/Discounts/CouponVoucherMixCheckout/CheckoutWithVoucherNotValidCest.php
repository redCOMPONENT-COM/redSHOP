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
	 * @var string
	 * @since 2.1.3
	 */
	protected $productName2;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $productName3;

	/**
	 * @var int
	 * @since 2.1.3
	 */
	protected $randomProductNumber;

	/**
	 * @var int
	 * @since 2.1.3
	 */
	protected $randomProductNumber2;

	/**
	 * @var int
	 * @since 2.1.3
	 */
	protected $randomProductNumber3;

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
	 * @var string
	 * @since 2.1.3
	 */
	protected $randomVoucherCode2;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $randomVoucherCode3;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $randomVoucherCode4;

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
	 * @var array
	 * @since 2.1.3
	 */
	protected $dataCoupon;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $randomNameDisscount;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $discountType;

	/**
	 * @var int
	 * @since 2.1.3
	 */
	protected $randomAmount;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	protected $discount;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	protected $discount1;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	protected $discount2;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	protected $discount3;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	protected $discount4;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	protected $discount5;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	protected $discount6;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	protected $discount7;

	/**
	 * CheckoutWithVoucherNotValidCest constructor.
	 */
	public function __construct()
	{
		$this->faker = Faker\Factory::create();
		$this->categoryName              = $this->faker->bothify('TestingCategory ?##');
		$this->productName               = $this->faker->bothify('Testing Product ?##');
		$this->productName2              = $this->faker->bothify('Testing Product name ?##');
		$this->productName3              = $this->faker->bothify('Testing ProductManagement ?##');
		$this->randomProductNumber       = $this->faker->numberBetween(999, 3333);
		$this->randomProductNumber2      = $this->faker->numberBetween(3333, 5555);
		$this->randomProductNumber3      = $this->faker->numberBetween(5555, 9999);
		$this->randomProductPrice        = $this->faker->numberBetween(300, 999);
		$this->minimumPerProduct         = 2;
		$this->minimumQuantity           = 3;
		$this->maximumQuantity           = 5;
		$this->discountStart             = "2019-01-05";
		$this->discountEnd               = "2019-08-08";
		$this->randomVoucherCode         = $this->faker->bothify('ManageVoucherAdministratorCest ??');
		$this->randomVoucherCode2        = $this->faker->bothify('ManageVoucherAdministratorCest ?#?');
		$this->randomVoucherCode3        = $this->faker->bothify('ManageVoucherAdministratorCest ?##?');
		$this->randomVoucherCode4        = $this->faker->bothify('ManageVoucherAdministratorCest ?##');
		$this->voucherAmount             = $this->faker->numberBetween(9, 99);
		$this->startDate                 = "2019-01-05";
		$this->endDate                   = "2019-07-07";
		$this->voucherCount              = $this->faker->numberBetween(99, 999);
		$this->invalidVoucher            = $this->faker->bothify('invalidvoucher ?##?');

		$this->dataCoupon = array();
		$this->dataCoupon['code']        = $this->faker->bothify('Coupon Code ?##?');
		$this->dataCoupon['type']        = 'Total';
		$this->dataCoupon['value']       = '10';
		$this->dataCoupon['effect']      = 'Global';
		$this->dataCoupon['amount_left'] = $this->faker->numberBetween(99, 999);


		//$name, $discountType, $amount, $startDate, $endDate, $product
		$this->randomNameDisscount       = 'Testing Disscount' . rand(99, 999);
		$this->discountType              = 'Total';
		$this->randomAmount              = rand(99, 300);

		// price Discount/voucher/coupon
		$this->discount                   = array();
		$this->discount['enable']         = 'yes';
		$this->discount['allow']          = 'Discount/voucher/coupon';
		$this->discount['enableCoupon']   = 'yes';
		$this->discount['couponInfo']     = 'no';
		$this->discount['enableVoucher']  = 'yes';
		$this->discount['spendTime']      = 'no';
		$this->discount['applyForProductDiscount'] = 'yes';
		$this->discount['calculate']      = 'total';
		$this->discount['valueOfDiscount']= 'Total';

		//price Discount + voucher/coupon
		$this->discount1 = array();
		$this->discount1['enable'] = 'yes';
		$this->discount1['allow']= 'Discount + voucher/coupon';
		$this->discount1['enableCoupon']= 'yes';
		$this->discount1['couponInfo'] = 'no';
		$this->discount1['enableVoucher'] = 'yes';
		$this->discount1['spendTime'] = 'yes';
		$this->discount1['applyForProductDiscount'] = 'yes';
		$this->discount1['calculate'] = 'total';
		$this->discount1['valueOfDiscount'] = 'Total';

		//price Discount + voucher (single) + coupon (single)
		$this->discount2 = array();
		$this->discount2['enable'] = 'yes';
		$this->discount2['allow']= 'Discount + voucher (single) + coupon (single)';
		$this->discount2['enableCoupon']= 'yes';
		$this->discount2['couponInfo'] = 'no';
		$this->discount2['enableVoucher'] = 'yes';
		$this->discount2['spendTime'] = 'yes';
		$this->discount2['applyForProductDiscount'] = 'yes';
		$this->discount2['calculate'] = 'total';
		$this->discount2['valueOfDiscount'] = 'Total';

		//price Discount + voucher (multiple) + coupon (multiple)
		$this->discount3 = array();
		$this->discount3['enable'] = 'yes';
		$this->discount3['allow']= 'Discount + voucher (multiple) + coupon (multiple)';
		$this->discount3['enableCoupon']= 'yes';
		$this->discount3['couponInfo'] = 'yes';
		$this->discount3['enableVoucher'] = 'yes';
		$this->discount3['spendTime'] = 'yes';
		$this->discount3['applyForProductDiscount'] = 'yes';
		$this->discount3['calculate'] = 'total';
		$this->discount3['valueOfDiscount'] = 'Total';

		// price Discount/voucher/coupon + enable Vouchers in price
		$this->discount = array();
		$this->discount4['enable'] = 'yes';
		$this->discount4['allow']= 'Discount/voucher/coupon';
		$this->discount4['enableCoupon']= 'yes';
		$this->discount4['couponInfo'] = 'no';
		$this->discount4['enableVoucher'] = 'no';
		$this->discount4['spendTime'] = 'no';
		$this->discount4['applyForProductDiscount'] = 'yes';
		$this->discount4['calculate'] = 'total';
		$this->discount4['valueOfDiscount'] = 'Total';

		//price Discount + voucher/coupon + enable Vouchers in price
		$this->discount1 = array();
		$this->discount5['enable'] = 'yes';
		$this->discount5['allow']= 'Discount + voucher/coupon';
		$this->discount5['enableCoupon']= 'yes';
		$this->discount5['couponInfo'] = 'no';
		$this->discount5['enableVoucher'] = 'no';
		$this->discount5['spendTime'] = 'yes';
		$this->discount5['applyForProductDiscount'] = 'yes';
		$this->discount5['calculate'] = 'total';
		$this->discount5['valueOfDiscount'] = 'Total';

		//price Discount + voucher (single) + coupon (single) + enable Vouchers in price
		$this->discount6 = array();
		$this->discount6['enable'] = 'yes';
		$this->discount6['allow']= 'Discount + voucher (single) + coupon (single)';
		$this->discount6['enableCoupon']= 'yes';
		$this->discount6['couponInfo'] = 'no';
		$this->discount6['enableVoucher'] = 'no';
		$this->discount6['spendTime'] = 'yes';
		$this->discount6['applyForProductDiscount'] = 'yes';
		$this->discount6['calculate'] = 'total';
		$this->discount6['valueOfDiscount'] = 'Total';

		//price Discount + voucher (multiple) + coupon (multiple) + enable Vouchers in price
		$this->discount7 = array();
		$this->discount7['enable'] = 'yes';
		$this->discount7['allow']= 'Discount + voucher (multiple) + coupon (multiple)';
		$this->discount7['enableCoupon']= 'yes';
		$this->discount7['couponInfo'] = 'yes';
		$this->discount7['enableVoucher'] = 'no';
		$this->discount7['spendTime'] = 'yes';
		$this->discount7['applyForProductDiscount'] = 'yes';
		$this->discount7['calculate'] = 'total';
		$this->discount7['valueOfDiscount'] = 'Total';
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
		$I->createProductSave($this->productName2, $this->categoryName, $this->randomProductNumber2, $this->randomProductPrice, $this->minimumPerProduct, $this->minimumQuantity, $this->maximumQuantity, $this->discountStart, $this->discountEnd);
		$I->createProductSave($this->productName3, $this->categoryName, $this->randomProductNumber3, $this->randomProductPrice, $this->minimumPerProduct, $this->minimumQuantity, $this->maximumQuantity, $this->discountStart, $this->discountEnd);

		$I->wantTo('Create Voucher creation in Administrator');
		$I = new VoucherManagerJoomla3Steps($scenario);
		$I->addVoucher($this->randomVoucherCode, $this->voucherAmount, $this->startDate, $this->endDate, $this->voucherCount, $this->productName, 'save');
		$I->addVoucher($this->randomVoucherCode2, $this->voucherAmount, $this->startDate, $this->endDate, $this->voucherCount, $this->productName2, 'save');
		$I->addVoucherNotHaveProducts($this->randomVoucherCode3, $this->voucherAmount, $this->startDate, $this->endDate, $this->voucherCount);

		$I->wantTo('Create a Coupon');
		$I = new CouponSteps($scenario);
		$I->addNewItem($this->dataCoupon);

		$I->wantTo('Test add mass discount');
		$I = new MassDiscountManagerJoomla3Steps($scenario);
		$I->addMassDiscountHaveProduct($this->randomNameDisscount, $this->randomAmount, $this->startDate, $this->endDate, $this->categoryName, $this->productName);

		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->wantToTest('check out product with voucher of product2');
		$I->checkoutInvalidWithOneDisscount($this->productName, $this->categoryName, $this->randomVoucherCode2);
		$I->wantToTest('checkout with invalid voucher');
		$I->checkoutWithInvalidVoucher($this->productName3, $this->categoryName, $this->invalidVoucher);

		$I->wantToTest('Configuration for voucher/coupon/discount');
		$I = new ConfigurationSteps($scenario);
		// configuration price voucher/coupon/discount
		$I->priceDiscount($this->discount);

		$I->wantTo('Test check out with invalid voucher');
		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->wantToTest('checkout coupon -> voucher');
		$I->checkoutInvalidWithTowDisscount($this->productName3, $this->categoryName, $this->dataCoupon['code'], $this->randomVoucherCode3);
		$I->wantToTest('checkout voucher -> voucher');
		$I->checkoutInvalidWithTowDisscount($this->productName3, $this->categoryName, $this->randomVoucherCode3, $this->randomVoucherCode3);
		$I->wantToTest('checkout discount -> voucher');
		$I->checkoutInvalidWithOneDisscount($this->productName, $this->categoryName, $this->randomVoucherCode);

		$I->wantToTest('Configuration for Discount + voucher/coupon');
		$I = new ConfigurationSteps($scenario);
		$I->wantToTest('configuration price Discount + voucher/coupon');
		$I->priceDiscount($this->discount1);

		$I->wantTo('Test check out with invalid voucher');
		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->wantToTest('checkout discout products -> voucher -> voucher');
		$I->checkoutInvalidWithTowDisscount($this->productName, $this->categoryName, $this->randomVoucherCode3, $this->randomVoucherCode3);

		$I->wantToTest('Configuration for Discount + voucher (single) + coupon (single)');
		$I = new ConfigurationSteps($scenario);
		// configuration price Discount + voucher (single) + coupon (single)
		$I->wantToTest('check out product with voucher of product2');
		$I->priceDiscount($this->discount2);

		$I->wantTo('Test check out with invalid voucher');
		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->wantToTest('checkout discout products -> voucher -> voucher');
		$I->checkoutInvalidWithTowDisscount($this->productName3, $this->categoryName, $this->randomVoucherCode3, $this->randomVoucherCode3);

		$I->wantToTest('Discount + voucher (multiple) + coupon (multiple)');
		$I = new ConfigurationSteps($scenario);
		// configuration price Discount + voucher (multiple) + coupon (multiple)
		$I->priceDiscount($this->discount3);

		$I->wantTo('Test check out with invalid voucher');
		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->wantToTest('checkout discout products -> voucher -> voucher');
		$I->checkoutInvalidWithTowDisscount($this->productName3, $this->categoryName, $this->randomVoucherCode3, $this->randomVoucherCode3);

		//enable Vouchers in price

		$I->wantToTest('Discount + voucher (multiple) + coupon (multiple)');
		$I = new ConfigurationSteps($scenario);
		// price Discount/voucher/coupon + enable Vouchers in price
		$I->priceDiscount($this->discount4);
		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->wantToTest('check out product with voucher');
		$I->checkoutInvalidWithOneDisscount($this->productName3, $this->categoryName, $this->randomVoucherCode3);

		$I->wantToTest('Discount + voucher (multiple) + coupon (multiple)');
		$I = new ConfigurationSteps($scenario);
		// configuration price Discount + voucher/coupon + enable Vouchers in price + enable Vouchers in price
		$I->priceDiscount($this->discount5);
		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->wantToTest('check out product with voucher');
		$I->checkoutInvalidWithOneDisscount($this->productName3, $this->categoryName, $this->randomVoucherCode3);

		$I->wantToTest('Discount + voucher (multiple) + coupon (multiple)');
		$I = new ConfigurationSteps($scenario);
		// configuration price Discount + voucher (single) + coupon (single) + enable Vouchers in price
		$I->priceDiscount($this->discount6);
		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->wantToTest('check out product with voucher');
		$I->checkoutInvalidWithOneDisscount($this->productName3, $this->categoryName, $this->randomVoucherCode3);

		$I->wantToTest('Discount + voucher (multiple) + coupon (multiple)');
		$I = new ConfigurationSteps($scenario);
		// configuration price Discount + voucher (multiple) + coupon (multiple) + enable Vouchers in price
		$I->priceDiscount($this->discount7);
		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->wantToTest('check out product with voucher');
		$I->checkoutInvalidWithOneDisscount($this->productName3, $this->categoryName, $this->randomVoucherCode3);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.3
	 */
	public function clearUp(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test delete Voucher in Administrator');
		$I = new VoucherManagerJoomla3Steps($scenario);
		$I->deleteAllVoucher($this->randomVoucherCode);

		$I->wantTo('Test delete Voucher in Administrator');
		$I = new CouponSteps($scenario);
		$I->deleteCoupon($this->dataCoupon['code']);

		$I->wantTo('Test delete massdiscount in Administrator');
		$I = new MassDiscountManagerJoomla3Steps($scenario);
		$I->deleteAllMassDiscountOK($this->randomNameDisscount);

		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('Delete Product  in Administrator');
		$I->deleteProduct($this->productName);
		$I->deleteProduct($this->productName2);
		$I->deleteProduct($this->productName3);

		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->wantTo('Delete Category in Administrator');
		$I->deleteCategory($this->categoryName);
	}
}