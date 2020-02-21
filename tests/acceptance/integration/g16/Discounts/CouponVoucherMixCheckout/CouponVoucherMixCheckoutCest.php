<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
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
use Step\AbstractStep;
use AcceptanceTester\UserManagerJoomla3Steps as UserManagerJoomla3Steps;
use AcceptanceTester\ProductCheckoutManagerJoomla3Steps as ProductCheckoutManagerJoomla3Steps;
use AcceptanceTester\VoucherManagerJoomla3Steps as VoucherManagerJoomla3Steps;
use Configuration\ConfigurationSteps as ConfigurationSteps;
class CouponVoucherMixCheckoutCest
{
	/**
	 * CouponVoucherMixCheckoutCest constructor.
	 */
	public function __construct()
	{
		$this->faker               = Faker\Factory::create();
		//create coupon
		$this->dataCoupon = array();
		$this->dataCoupon['code']        = $this->faker->bothify('Coupon Code ?##?');
		$this->dataCoupon['type']        = 'Total';
		$this->dataCoupon['value']       = '10';
		$this->dataCoupon['effect']      = 'Global';
		$this->dataCoupon['amount_left'] = $this->faker->numberBetween(99, 999);
		$this->dataCouponSecond = array();
		$this->dataCouponSecond['code']        = $this->faker->bothify('Coupon Code Second ?##?');
		$this->dataCouponSecond['type']        = 'Total';
		$this->dataCouponSecond['value']       = '20';
		$this->dataCouponSecond['effect']      = 'Global';
		$this->dataCouponSecond['amount_left'] = '10';

		//create category and product
		$this->categoryName        = 'Testing Category ' . $this->faker->randomNumber();
		$this->noPage              = $this->faker->randomNumber();
		$this->productName         = 'Testing ProductManagement' . rand(99, 999);
		$this->productNameDiscount = 'Testing ProductManagement Discount' . rand(99, 999);
		$this->randomProductNumber = $this->faker->numberBetween(999, 9999);
		$this->randomProductNumberDiscount = $this->faker->numberBetween(999, 9999);
		$this->randomProductPrice  = '150';
		$this->randomDiscountPrice = '100';
		$this->minimumPerProduct   = '1';
		$this->minimumQuantity     = 1;
		$this->maximumQuantity     = $this->faker->numberBetween(11, 100);
		$this->discountStart       = "12-01-2019";
		$this->discountEnd         = "23-05-2021";

		//create voucher
		$this->randomVoucherCode = $this->faker->bothify('VoucherCheckoutProductCest ?##?');
		$this->voucherAmount     = 10;
		$this->voucherCount      = $this->faker->numberBetween(99, 999);
		$this->startDate         = "21-01-2019";
		$this->endDate           = "07-07-2020";

		$this->randomVoucherCodeSecond = $this->faker->bothify('VoucherCheckoutProductCestSecond ?##?');
		$this->voucherAmountSecond     = 20;
		$this->voucherCountSecond      = $this->faker->numberBetween(99, 999);
		$this->startDateSecond         = "25-01-2019";
		$this->endDateSecond           = "07-01-2021";

		$this->randomVoucherCodeDiscount = $this->faker->bothify('VoucherCheckoutProductDiscount ?##?');
		$this->voucherAmountDiscount     = 10;
		$this->voucherCountDiscount      = $this->faker->numberBetween(99, 999);
		$this->startDateDiscount         = "25-01-2019";
		$this->endDateDiscount           = "07-01-2030";

		$this->randomVoucherCodeDiscountSecond = $this->faker->bothify('VoucherCheckoutProductDiscountSecond ?##?');
		$this->voucherAmountDiscountSecond     = 20;
		$this->voucherCountDiscountSecond      = $this->faker->numberBetween(99, 999);
		$this->startDateDiscountSecond         = "25-01-2019";
		$this->endDateDiscountSecond           = "07-01-2030";

		//create user
		$this->userName = $this->faker->bothify('UserName ?##?');
		$this->password = 'test';
		$this->email = $this->faker->email;
		$this->shopperGroup = 'Default Private';
		$this->firstName = $this->faker->bothify('FirstName FN ?##?');
		$this->lastName = 'Last';
		$this->shopperName = 'Default Private';
		$this->group = 'Administrator';

		// price discount
		$this->discount = array();
		$this->discount['enable'] = 'yes';
		$this->discount['allow']= 'Discount/voucher/coupon';
		$this->discount['enableCoupon']= 'yes';
		$this->discount['couponInfo'] = 'yes';
		$this->discount['enableVoucher'] = 'yes';
		$this->discount['spendTime'] = 'no';
		$this->discount['applyForProductDiscount'] = 'yes';
		$this->discount['calculate'] = 'total';
		$this->discount['valueOfDiscount'] = 'Total';

		// at checkout page
		$this->applyDiscountCouponCode  = 'couponCode';
		$this->applyDiscountVoucherCode = 'voucherCode';
		$this->haveDiscount = array();
		$this->haveDiscount['yes'] = 'yes';
		$this->haveDiscount['no'] = 'no';
		$this->discount['couponCode']= $this->dataCoupon['code'] ;
		$this->discount['voucherCode'] = $this->randomVoucherCode;
		$this->discount['couponCodeSecond']= $this->dataCouponSecond['code'] ;
		$this->discount['voucherCodeSecond'] = $this->randomVoucherCodeSecond;

		$this->orderInfoSecond = array();
		$this->orderInfoSecond['priceTotal'] = '';
		$this->orderInfoSecond['priceDiscount'] = '';
		$this->orderInfoSecond['priceEnd'] = '';

		$this->orderInfoDiscount = array();
		$this->orderInfoDiscount['priceTotal'] = '';
		$this->orderInfoDiscount['priceDiscount'] = '';
		$this->orderInfoDiscount['priceEnd'] = '';

		$this->orderInfo = array();
		$this->orderInfo['priceTotal'] = '';
		$this->orderInfo['priceDiscount'] = '';
		$this->orderInfo['priceEnd'] = '';

	}

	/**
	 * @param AcceptanceTester $I
	 * @param \Codeception\Scenario $scenario
	 *
	 * The method check for voucher/coupon/discount
	 * @throws Exception
	 */
	public function testProductsCouponFrontEnd(AcceptanceTester $I, \Codeception\Scenario $scenario)
	{
		$I = new AcceptanceTester($scenario);
		$I->wantTo('Test Product Checkout on Front End with 2 Checkout Payment Plugin');
		$I->doAdministratorLogin();
		$this->createCoupon($I, $scenario);
		$this->createCategory($I, $scenario);
		$this->createProductSave($I, $scenario);
		$this->createProductHaveDiscount($I, $scenario);

		$I->wantTo('Test User creation with save button in Administrator for checkout');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperName, $this->firstName, $this->lastName, 'save');


		$I->wantToTest('Configuration for voucher/coupon/discount');
		$I = new ConfigurationSteps($scenario);
		$I->priceDiscount($this->discount);

		$I = new VoucherManagerJoomla3Steps($scenario);
		$I->addVoucher($this->randomVoucherCode, $this->voucherAmount, $this->startDate, $this->endDate, $this->voucherCount, $this->productName, 'validday');
		$I->addVoucher($this->randomVoucherCodeSecond, $this->voucherAmountSecond, $this->startDateSecond, $this->endDateSecond, $this->voucherCountSecond, $this->productName, 'validday');
		$I->addVoucher($this->randomVoucherCodeDiscount, $this->voucherAmountDiscount, $this->startDateDiscount, $this->endDateDiscount, $this->voucherCountDiscount, $this->productNameDiscount, 'validday');
		$I->addVoucher($this->randomVoucherCodeDiscountSecond, $this->voucherAmountDiscountSecond, $this->startDateDiscountSecond, $this->endDateDiscountSecond, $this->voucherCountDiscountSecond, $this->productNameDiscount, 'validday');

		$this->orderInfo['priceTotal'] = "DKK 150,00";
		$this->orderInfo['priceDiscount'] =  "DKK 10,00";
		$this->orderInfo['priceEnd'] =  "DKK 140,00";

		$this->orderInfoDiscount['priceTotal'] = 'DKK 100,00';
		$this->orderInfoDiscount['priceDiscount'] = 'DKK 0,00';
		$this->orderInfoDiscount['priceEnd'] = 'DKK 100,00';

		$I->comment('Configuration for voucher/coupon/discount');
		$I->wantToTest('Checkout with coupon first and input voucher but still get value of voucher and product not have discount ');
		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->checkoutProductCouponOrVoucherOrDiscount($this->userName,$this->password,$this->productName, $this->categoryName, $this->discount, $this->orderInfo, $this->applyDiscountCouponCode, null, $this->haveDiscount['no']);

		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->firstName);
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperName, $this->firstName, $this->lastName, 'save');
		$I->wantToTest('Checkout with coupon first and input voucher but still get value of voucher and product have discount ');
		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->checkoutProductCouponOrVoucherOrDiscount($this->userName,$this->password,$this->productNameDiscount, $this->categoryName, $this->discount, $this->orderInfoDiscount, $this->applyDiscountCouponCode, null, $this->haveDiscount['yes']);

		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->firstName);
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperName, $this->firstName, $this->lastName, 'save');
		$I->comment('Checkout with voucher first then input coupon but still get value of voucher and product not have discount');
		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->checkoutProductCouponOrVoucherOrDiscount($this->userName,$this->password,$this->productName, $this->categoryName, $this->discount, $this->orderInfo, $this->applyDiscountVoucherCode, null, $this->haveDiscount['no']);

		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->firstName);
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperName, $this->firstName, $this->lastName, 'save');
		$I->comment('Checkout with voucher first then input coupon but still get value of voucher and product have discount');
		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->checkoutProductCouponOrVoucherOrDiscount($this->userName,$this->password,$this->productNameDiscount, $this->categoryName, $this->discount, $this->orderInfoDiscount, $this->applyDiscountVoucherCode, null, $this->haveDiscount['yes']);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param \Codeception\Scenario $scenario
	 *
	 * The method check for Discount + voucher (single) + coupon (single)
	 * @throws \Exception
	 */
	public function checkWithSignVoucherCoupon(AcceptanceTester $I, \Codeception\Scenario $scenario)
	{
		$I->doAdministratorLogin();
		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->firstName);
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperName, $this->firstName, $this->lastName, 'save');

		$this->discount['allow'] = 'Discount + voucher (single) + coupon (single)';
		$I->wantToTest('I want to setup checkout with apply single coupon and voucher');
		$I = new ConfigurationSteps($scenario);
		$I->priceDiscount($this->discount);
		$I->comment('Checkout with coupon even you input voucher but still get value of voucher ');

		$this->discount['voucherCode'] = $this->randomVoucherCode;
		$this->orderInfo['priceTotal'] = "DKK 150,00";
		$this->orderInfo['priceDiscount'] =  "DKK 10,00";
		$this->orderInfo['priceEnd'] =  "DKK 140,00";

		$this->orderInfoSecond['priceTotal'] = "DKK 150,00";
		$this->orderInfoSecond['priceDiscount'] =  "DKK 20,00";
		$this->orderInfoSecond['priceEnd'] =  "DKK 130,00";
		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->checkoutProductCouponOrVoucherOrDiscount($this->userName,$this->password,$this->productName, $this->categoryName, $this->discount, $this->orderInfo, $this->applyDiscountCouponCode, $this->orderInfoSecond);

		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->firstName);
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperName, $this->firstName, $this->lastName, 'save');
		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->wantToTest('I want to setup checkout with apply single voucher and coupon');
		$I->comment('Checkout with voucher even you input coupon but still get value of coupon ');
		$I->checkoutProductCouponOrVoucherOrDiscount($this->userName,$this->password,$this->productName, $this->categoryName, $this->discount, $this->orderInfo, $this->applyDiscountVoucherCode, $this->orderInfoSecond);

		$this->discount['voucherCode'] = $this->randomVoucherCodeDiscount;
		$this->orderInfo['priceTotal'] = 'DKK 100,00';
		$this->orderInfo['priceDiscount'] = 'DKK 10,00';
		$this->orderInfo['priceEnd'] = 'DKK 90,00';

		$this->orderInfoSecond['priceTotal'] = "DKK 100,00";
		$this->orderInfoSecond['priceDiscount'] =  "DKK 20,00";
		$this->orderInfoSecond['priceEnd'] =  "DKK 80,00";

		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->firstName);
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperName, $this->firstName, $this->lastName, 'save');
		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->checkoutProductCouponOrVoucherOrDiscount($this->userName,$this->password,$this->productNameDiscount, $this->categoryName, $this->discount, $this->orderInfo, $this->applyDiscountCouponCode, $this->orderInfoSecond,$this->haveDiscount['yes']);

		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->firstName);
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperName, $this->firstName, $this->lastName, 'save');
		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->wantToTest('I want to setup checkout with apply single voucher and coupon');
		$I->comment('Checkout with voucher even you input coupon but still get value of coupon ');
		$I->checkoutProductCouponOrVoucherOrDiscount($this->userName,$this->password,$this->productNameDiscount, $this->categoryName, $this->discount, $this->orderInfo, $this->applyDiscountVoucherCode, $this->orderInfoSecond,$this->haveDiscount['yes']);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param \Codeception\Scenario $scenario
	 *
	 * The method check for Discount + voucher (multiple) + coupon (multiple)
	 * @throws Exception
	 */
	public function checkWithSignVoucherCouponMulti(AcceptanceTester $I, \Codeception\Scenario $scenario)
	{
		$I->doAdministratorLogin();
		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->firstName);
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperName, $this->firstName, $this->lastName, 'save');

		$this->discount['allow'] = 'Discount + voucher (multiple) + coupon (multiple)';
		$I = new ConfigurationSteps($scenario);
		$I->priceDiscount($this->discount);
		$I->wantToTest('I want to setup checkout with apply multiple voucher and coupon');

		$I->comment('the first time apply discount');
		$this->discount['voucherCode'] = $this->randomVoucherCode;
		$this->discount['voucherCodeSecond'] = $this->randomVoucherCodeSecond;
		$this->orderInfo['priceTotal'] = "DKK 150,00";
		$this->orderInfo['priceDiscount'] =  "DKK 10,00";
		$this->orderInfo['priceEnd'] =  "DKK 140,00";


		$this->orderInfoSecond['priceTotal'] = "DKK 150,00";
		$this->orderInfoSecond['priceDiscount'] =  "DKK 40,00";
		$this->orderInfoSecond['priceEnd'] =  "DKK 110,00";

		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->comment('Checkout with voucher (multiple) + coupon (multiple) ');
		$I->checkoutProductCouponOrVoucherOrDiscount($this->userName,$this->password,$this->productName, $this->categoryName, $this->discount, $this->orderInfo, $this->applyDiscountCouponCode, $this->orderInfoSecond);

		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->firstName);
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperName, $this->firstName, $this->lastName, 'save');
		$I = new ProductCheckoutManagerJoomla3Steps($scenario);

		$I->comment('Checkout with voucher (multiple) + coupon (multiple) ');
		$I->checkoutProductCouponOrVoucherOrDiscount($this->userName,$this->password,$this->productName, $this->categoryName, $this->discount, $this->orderInfo, $this->applyDiscountVoucherCode, $this->orderInfoSecond);


		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->firstName);
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperName, $this->firstName, $this->lastName, 'save');

		$I->wantToTest('I want to setup checkout with apply multiple voucher and coupon not use discount');
		$I->comment('the first time apply discount');

		$this->discount['voucherCode'] = $this->randomVoucherCodeDiscount;
		$this->discount['voucherCodeSecond'] = $this->randomVoucherCodeDiscountSecond;
		$this->orderInfo['priceTotal'] = "DKK 100,00";
		$this->orderInfo['priceDiscount'] =  "DKK 10,00";
		$this->orderInfo['priceEnd'] =  "DKK 90,00";

		$this->orderInfoSecond['priceTotal'] = "DKK 100,00";
		$this->orderInfoSecond['priceDiscount'] =  "DKK 40,00";
		$this->orderInfoSecond['priceEnd'] =  "DKK 60,00";

		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->wantToTest('I want to setup checkout with apply multiple voucher and coupon use discount');
		$I->comment('Checkout with voucher (multiple) + coupon (multiple) ');
		$I->checkoutProductCouponOrVoucherOrDiscount($this->userName,$this->password,$this->productNameDiscount, $this->categoryName, $this->discount, $this->orderInfo, $this->applyDiscountCouponCode, $this->orderInfoSecond);

		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->firstName);
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperName, $this->firstName, $this->lastName, 'save');
		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->comment('Checkout with voucher (multiple) + coupon (multiple) ');
		$I->checkoutProductCouponOrVoucherOrDiscount($this->userName,$this->password,$this->productNameDiscount, $this->categoryName, $this->discount, $this->orderInfo, $this->applyDiscountVoucherCode, $this->orderInfoSecond);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param \Codeception\Scenario $scenario
	 *
	 * The method check for Discount + voucher/coupon
	 * @throws \Exception
	 */
	public function checkWithDiscountVoucherOrCoupon(AcceptanceTester $I, \Codeception\Scenario $scenario)
	{
		$I->doAdministratorLogin();
		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->firstName);
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperName, $this->firstName, $this->lastName, 'save');

		$this->discount['allow'] = 'Discount + voucher/coupon';
		$I->wantToTest('I want to setup checkout with apply single coupon and voucher');
		$I = new ConfigurationSteps($scenario);
		$I->priceDiscount($this->discount);

		$I->comment('Checkout with coupon even you input voucher but still get value of voucher ');
		$this->orderInfoSecond['priceTotal'] = "DKK 150,00";
		$this->orderInfoSecond['priceDiscount'] =  "DKK 10,00";
		$this->orderInfoSecond['priceEnd'] =  "DKK 140,00";

		$this->orderInfoSecond['priceTotal'] = "DKK 150,00";
		$this->orderInfoSecond['priceDiscount'] =  "DKK 10,00";
		$this->orderInfoSecond['priceEnd'] =  "DKK 140,00";

		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->checkoutProductCouponOrVoucherOrDiscount($this->userName,$this->password,$this->productName, $this->categoryName, $this->discount, $this->orderInfo, $this->applyDiscountCouponCode, $this->orderInfoSecond,$this->haveDiscount['no']);
		$I = new UserManagerJoomla3Steps($scenario);

		$I->deleteUser($this->firstName);
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperName, $this->firstName, $this->lastName, 'save');
		$I = new ProductCheckoutManagerJoomla3Steps($scenario);

		$I->wantToTest('I want to setup checkout with apply single voucher and coupon');
		$I->comment('Checkout with voucher even you input coupon but still get value of coupon ');
		$I->checkoutProductCouponOrVoucherOrDiscount($this->userName,$this->password,$this->productName, $this->categoryName, $this->discount, $this->orderInfo, $this->applyDiscountVoucherCode, $this->orderInfoSecond,$this->haveDiscount['no']);

		$I->comment('Checkout with coupon even you input voucher but still get value of voucher and discount');
		$this->discount['voucherCode'] = $this->randomVoucherCodeDiscount;
		$this->orderInfo['priceTotal'] = "DKK 100,00";
		$this->orderInfo['priceDiscount'] =  "DKK 10,00";
		$this->orderInfo['priceEnd'] =  "DKK 90,00";

		$this->orderInfoSecond['priceTotal'] = "DKK 100,00";
		$this->orderInfoSecond['priceDiscount'] =  "DKK 10,00";
		$this->orderInfoSecond['priceEnd'] =  "DKK 90,00";

		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->firstName);
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperName, $this->firstName, $this->lastName, 'save');
		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->checkoutProductCouponOrVoucherOrDiscount($this->userName,$this->password,$this->productNameDiscount, $this->categoryName, $this->discount, $this->orderInfo, $this->applyDiscountCouponCode, $this->orderInfoSecond,$this->haveDiscount['yes']);

		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->firstName);
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperName, $this->firstName, $this->lastName, 'save');
		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->wantToTest('I want to setup checkout with apply single voucher and coupon');
		$I->comment('Checkout with voucher even you input coupon but still get value of coupon ');
		$I->checkoutProductCouponOrVoucherOrDiscount($this->userName,$this->password,$this->productNameDiscount, $this->categoryName, $this->discount, $this->orderInfo, $this->applyDiscountVoucherCode, $this->orderInfoSecond,$this->haveDiscount['yes']);
	}

	/**
	 * Function to Test Coupon Creation in Backend
	 *
	 */
	private function createCoupon(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Coupon creation in Administrator');
		$I = new CouponSteps($scenario);
		$I->wantTo('Create a Coupon');
		$I->addNewItem($this->dataCoupon);
		$I->addNewItem($this->dataCouponSecond);
	}

	/**
	 *
	 * Create category
	 *
	 * @param AcceptanceTester $I
	 * @param                  $scenario
	 * @throws Exception
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
	 * @throws Exception
	 */
	private function createProductSave(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Product Save Manager in Administrator');
		$I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductSaveClose($this->productName, $this->categoryName, $this->randomProductNumber, $this->randomProductPrice);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param                  $scenario
	 * @throws Exception
	 */
	private function createProductHaveDiscount(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Product Save Manager in Administrator');
		$I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductSaveHaveDiscount($this->productNameDiscount, $this->categoryName, $this->randomProductNumberDiscount, $this->randomProductPrice, $this->randomDiscountPrice, $this->minimumPerProduct, $this->minimumQuantity, $this->maximumQuantity, $this->discountStart, $this->discountEnd);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param                  $scenario
	 * @throws Exception
	 */
	public function clearUp(AcceptanceTester $I, $scenario)
	{
		$I->doAdministratorLogin();
		$I->wantToTest('Delete user');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->firstName);

		$I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
		$I->wantTo('Delete Product  in Administrator');
		$I->deleteProduct($this->productName);
		$I->deleteProduct($this->productNameDiscount);

		$I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
		$I->wantTo('Delete Category in Administrator');
		$I->deleteCategory($this->categoryName);
	}
}
