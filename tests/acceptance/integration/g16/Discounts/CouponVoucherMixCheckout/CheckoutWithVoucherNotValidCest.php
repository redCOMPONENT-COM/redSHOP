<?php
/**
 * @package     RedSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductCheckoutManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\VoucherManagerJoomla3Steps;

/**
 * Class CheckoutWithVoucherNotValidCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    2.1.2
 */
class CheckoutWithVoucherNotValidCest
{
	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $categoryName;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $productName;

	/**
	 * @var int
	 * @since 2.1.2
	 */
	protected $randomProductNumber;

	/**
	 * @var int
	 * @since 2.1.2
	 */
	protected $randomProductPrice;

	/**
	 * @var int
	 * @since 2.1.2
	 */
	protected $minimumPerProduct;

	/**
	 * @var int
	 * @since 2.1.2
	 */
	protected $minimumQuantity;

	/**
	 * @var int
	 * @since 2.1.2
	 */
	protected $maximumQuantity;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $discountStart;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $discountEnd;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $randomVoucherCode;

	/**
	 * @var int
	 * @since 2.1.2
	 */
	protected $voucherAmount;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $startDate;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $endDate;

	/**
	 * @var int
	 * @since 2.1.2
	 */
	protected $voucherCount;

	/**
	 * @var string
	 * @since 2.1.2
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
		$this->randomProductPrice = $this->faker->numberBetween(99, 999);
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
	}

	/**
	 * @param AcceptanceTester $I
	 * @throws Exception
	 * @since 2.1.2
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.2
	 */
	public function createCategory(AcceptanceTester $I, $scenario)
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

		$I->wantTo('Test check out with invalid voucher');
		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->checkoutWithInvalidVoucher($this->productName , $this->categoryName, $this->invalidVoucher);

		$I->wantTo('Test delete Voucher in Administrator');
		$I = new VoucherManagerJoomla3Steps($scenario);
		$I->deleteAllVoucher($this->randomVoucherCode);

		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('Delete Product  in Administrator');
		$I->deleteProduct($this->productName);

		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->wantTo('Delete Category in Administrator');
		$I->deleteCategory($this->categoryName);
	}
}