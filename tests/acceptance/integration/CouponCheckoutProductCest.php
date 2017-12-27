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
use AcceptanceTester\UserManagerJoomla3Steps as UserManagerJoomla3Steps;
use AcceptanceTester\ProductCheckoutManagerJoomla3Steps as ProductCheckoutManagerJoomla3Steps;
use AcceptanceTester\VoucherManagerJoomla3Steps as VoucherManagerJoomla3Steps;
use AcceptanceTester\ConfigurationManageJoomla3Steps as ConfigurationManageJoomla3Steps;
use AcceptanceTester\CouponSteps as CouponSteps;
class CouponCheckoutProductCest
{
	/**
	 * CouponCheckoutProductCest constructor.
	 */
	public function __construct()
	{
		$this->faker               = Faker\Factory::create();
		$this->couponCode          = $this->faker->bothify('CouponCheckoutProductCest ?##?');
		$this->couponValueIn       = 'Total';
		$this->couponValue         = '10';
		$this->couponType          = 'Globally';
		$this->couponLeft          = '10';
		$this->categoryName        = 'Testing Category ' . $this->faker->randomNumber();
		$this->noPage              = $this->faker->randomNumber();
		$this->productName         = 'Testing Products' . rand(99, 999);
		$this->randomProductNumber = $this->faker->numberBetween(999, 9999);
		$this->randomProductPrice  = '70';
		$this->minimumPerProduct   = '1';
		$this->minimumQuantity     = 1;
		$this->maximumQuantity     = $this->faker->numberBetween(11, 100);
		$this->discountStart       = "12-12-2016";
		$this->discountEnd         = "23-05-2017";


		//create voucher
		$this->randomVoucherCode = $this->faker->bothify('VoucherCheckoutProductCest ?##?');
		$this->voucherAmount     = 10;
		$this->voucherCount      = $this->faker->numberBetween(99, 999);
		$this->startDate         = "21-06-2017";
		$this->endDate           = "07-07-2017";


		//user login
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
		$this->discount['couponCode']= $this->couponCode;
		$this->discount['voucherCode'] = $this->randomVoucherCode;

		$this->orderInfoSecond = array();
		$this->orderInfoSecond['priceTotal'] = '';
		$this->orderInfoSecond['priceDiscount'] = '';
		$this->orderInfoSecond['priceEnd'] = '';


		$this->orderInfo = array();
		$this->orderInfo['priceTotal'] = '';
		$this->orderInfo['priceDiscount'] = '';
		$this->orderInfo['priceEnd'] = '';

	}

	/**
	 * Test to Verify the Payment Plugin
	 *
	 * @param   AcceptanceTester  $I         Actor Class Object
	 * @param   String            $scenario  Scenario Variable
	 *
	 * @return void
	 */
	public function deleteData($scenario)
	{
		$I= new RedshopSteps($scenario);
		$I->clearAllData();
	}

	public function testProductsCouponFrontEnd(AcceptanceTester $I, \Codeception\Scenario $scenario)
	{
		$I = new AcceptanceTester($scenario);
		$I->wantTo('Test Product Checkout on Front End with 2 Checkout Payment Plugin');
		$I->doAdministratorLogin();
		$this->createCoupon($I, $scenario);
		$this->createCategory($I, $scenario);
		$this->createProductSave($I, $scenario);

		$I->wantTo('Test User creation with save button in Administrator for checkout');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperName, $this->firstName, $this->lastName, 'save');


		$I->wantToTest('Configuration for voucher/coupon/discount');
		$I = new ConfigurationManageJoomla3Steps($scenario);
		$I->priceDiscount($this->discount);
		$I = new VoucherManagerJoomla3Steps($scenario);
		$I->addVoucher($this->randomVoucherCode, $this->voucherAmount, $this->startDate, $this->endDate, $this->voucherCount, $this->productName, 'validday');

		$this->orderInfo['priceTotal'] = "DKK 70,00";
		$this->orderInfo['priceDiscount'] =  "DKK 10,00";
		$this->orderInfo['priceEnd'] =  "DKK 60,00";

		$I->comment('Configuration for voucher/coupon/discount');
		$I->comment('Checkout with coupon even you input voucher but still get value of voucher ');
		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->checkoutProductCouponOrVoucherOrDiscount($this->userName,$this->password,$this->productName, $this->categoryName, $this->discount, $this->orderInfo, $this->applyDiscountCouponCode, null);
		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->firstName);
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperName, $this->firstName, $this->lastName, 'save');
		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->comment('Checkout with voucher even you input coupon but still get value of voucher ');
		$I->checkoutProductCouponOrVoucherOrDiscount($this->userName,$this->password,$this->productName, $this->categoryName, $this->discount, $this->orderInfo, $this->applyDiscountVoucherCode, null);
	}

	public function checkWithVoucher(ConfigurationManageJoomla3Steps $I, \Codeception\Scenario $scenario)
	{
		$I->doAdministratorLogin();
		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->firstName);
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperName, $this->firstName, $this->lastName, 'save');

		$I->comment('Configuration for voucher/coupon/discount');
		$I->comment('Checkout with coupon even you input voucher but still get value of voucher ');
		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->checkoutProductCouponOrVoucherOrDiscount($this->userName,$this->password,$this->productName, $this->categoryName, $this->discount, $this->orderInfo, $this->applyDiscountCouponCode, null);
		$I->comment('Checkout with voucher even you input coupon but still get value of voucher ');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->firstName);
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperName, $this->firstName, $this->lastName, 'save');
		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->checkoutProductCouponOrVoucherOrDiscount($this->userName,$this->password,$this->productName, $this->categoryName, $this->discount, $this->orderInfo, $this->applyDiscountVoucherCode, null);
	}

	public function checkWithSignVoucherCoupon(AcceptanceTester $I, \Codeception\Scenario $scenario)
	{
		$I->doAdministratorLogin();
		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->firstName);
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperName, $this->firstName, $this->lastName, 'save');

		$this->discount['allow'] = 'Discount + voucher (single) + coupon (single)';
		$I->comment('I want to setup checkout with apply single coupon and voucher');
		$I = new ConfigurationManageJoomla3Steps($scenario);
		$I->priceDiscount($this->discount);
		$I->comment('Checkout with coupon even you input voucher but still get value of voucher ');
		$this->orderInfoSecond['priceTotal'] = "DKK 70,00";
		$this->orderInfoSecond['priceDiscount'] =  "DKK 20,00";
		$this->orderInfoSecond['priceEnd'] =  "DKK 50,00";

		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->checkoutProductCouponOrVoucherOrDiscount($this->userName,$this->password,$this->productName, $this->categoryName, $this->discount, $this->orderInfo, $this->applyDiscountCouponCode, $this->orderInfoSecond);
		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->firstName);
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperName, $this->firstName, $this->lastName, 'save');
		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->comment('Checkout with voucher even you input coupon but still get value of voucher ');
		$I->checkoutProductCouponOrVoucherOrDiscount($this->userName,$this->password,$this->productName, $this->categoryName, $this->discount, $this->orderInfo, $this->applyDiscountVoucherCode, $this->orderInfoSecond);
	}


	public function checkWithSignVoucherCouponMulti(AcceptanceTester $I, \Codeception\Scenario $scenario)
	{
		$I->doAdministratorLogin();
		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->firstName);
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperName, $this->firstName, $this->lastName, 'save');


		$this->discount['allow'] = 'Discount + voucher (multiple) + coupon (multiple)';
		$I->comment('I want to setup checkout with apply single coupon and voucher');
		$I = new ConfigurationManageJoomla3Steps($scenario);
		$I->priceDiscount($this->discount);
		$I->comment('Checkout with coupon even you input voucher but still get value of voucher ');

		$I->comment('the first time apply discount');
		$this->orderInfo['priceTotal'] = "DKK 70,00";
		$this->orderInfo['priceDiscount'] =  "DKK 10,00";
		$this->orderInfo['priceEnd'] =  "DKK 60,00";


		$this->orderInfoSecond['priceTotal'] = "DKK 70,00";
		$this->orderInfoSecond['priceDiscount'] =  "DKK 30,00";
		$this->orderInfoSecond['priceEnd'] =  "DKK 40,00";

		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->comment('Checkout with voucher (multiple) + coupon (multiple) ');
		$I->checkoutProductCouponOrVoucherOrDiscount($this->userName,$this->password,$this->productName, $this->categoryName, $this->discount, $this->orderInfo, $this->applyDiscountCouponCode, $this->orderInfoSecond);

		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->firstName);
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperName, $this->firstName, $this->lastName, 'save');
		$I = new ProductCheckoutManagerJoomla3Steps($scenario);

		$I->comment('Checkout with voucher (multiple) + coupon (multiple) ');
		$I->checkoutProductCouponOrVoucherOrDiscount($this->userName,$this->password,$this->productName, $this->categoryName, $this->discount, $this->orderInfo, $this->applyDiscountVoucherCode, $this->orderInfoSecond);
	}

	/**
	 * Function to Test Coupon Creation in Backend
	 *
	 */
	private function createCoupon(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Coupon creation in Administrator');
		$I = new AcceptanceTester\CouponSteps($scenario);
		$I->wantTo('Create a Coupon');
		$I->addCoupon($this->couponCode, $this->couponValueIn, $this->couponValue, $this->couponType, $this->couponLeft);
		$I->searchCoupon($this->couponCode);
	}

	/**
	 *
	 * Create category
	 *
	 * @param AcceptanceTester $I
	 * @param                  $scenario
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
	 *
	 */
	private function createProductSave(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Product Save Manager in Administrator');
		$I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductSave($this->productName, $this->categoryName, $this->randomProductNumber, $this->randomProductPrice, $this->minimumPerProduct, $this->minimumQuantity, $this->maximumQuantity, $this->discountStart, $this->discountEnd);
	}

	public function clearUp(AcceptanceTester $I, $scenario)
	{
		$I->doAdministratorLogin();

		$I->wantTo('Deletion of Coupon in Administrator');
		$I = new AcceptanceTester\CouponSteps($scenario);
		$I->wantTo('Delete a Coupon');
		$I->deleteCoupon($this->couponCode);

		$I->wantTo('Delete product');
		$I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->productName);

		$I->wantTo('Delete Category');
		$I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->categoryName);

		$I->wantToTest('Delete user');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->firstName);
	}
}
