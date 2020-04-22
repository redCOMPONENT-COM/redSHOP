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
 * Class VoucherCest
 * @since 1.4.0
 */
class VoucherCest
{
	/**
	 * @var \Faker\Generator
	 * @since 1.4.0
	 */
	protected $faker;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $randomVoucherCode;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $randomVoucherCodeSave;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $updatedRandomVoucherCode;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	protected $voucherAmount;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	protected $voucherCount;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $startDate;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $endDate;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $randomCategoryName;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $randomCategoryNameAssign;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $productName;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	protected $minimumPerProduct;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	protected $minimumQuantity;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	protected $maximumQuantity;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $discountStart;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $discountEnd;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	protected $randomProductNumber;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	protected $randomProductPrice;

	/**
	 * VoucherCest constructor.
	 * @since 1.4.0
	 */
	public function __construct()
	{
		$this->faker                        = Faker\Factory::create();
		$this->randomVoucherCode            = $this->faker->bothify('ManageVoucherAdministratorCest ?##?');
		$this->randomVoucherCodeSave         = $this->faker->bothify("VoucherCodeSave ?##");
		$this->updatedRandomVoucherCode     = 'Updating ' . $this->randomVoucherCode;
		$this->voucherAmount                = $this->faker->numberBetween(9, 99);
		$this->voucherCount                 = $this->faker->numberBetween(99, 999);
		$this->startDate                    = "2017-06-21";
		$this->endDate                      = "2017-07-07";
		$this->randomCategoryName           = $this->faker->bothify('TestingCategory ?##');
		$this->randomCategoryNameAssign     = $this->faker->bothify('CategoryAssign ?##');
		$this->productName                  = 'Testing ProductManagement' . rand(99, 999);
		$this->minimumPerProduct            = 2;
		$this->minimumQuantity              = 3;
		$this->maximumQuantity              = 5;
		$this->discountStart                = "2016-12-12";
		$this->discountEnd                  = "2017-05-23";
		$this->randomProductNumber          = rand(999, 9999);
		$this->randomProductPrice           = rand(99, 199);
	}

	/**
	 * @param AcceptanceTester $I
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 1.4.0
	 */
	public function createCategory(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Create category for use in Voucher test');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->randomCategoryName);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 1.4.0
	 */
	public function createProduct(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Create product for use in Voucher test');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->createProductSave($this->productName, $this->randomCategoryName, $this->randomProductNumber, $this->randomProductPrice, $this->minimumPerProduct, $this->minimumQuantity, $this->maximumQuantity, $this->discountStart, $this->discountEnd);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @since 1.4.0
	 */
	public function addVoucher(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Voucher creation in Administrator');
		$I = new VoucherManagerJoomla3Steps($scenario);
		$I->addVoucher($this->randomVoucherCode, $this->voucherAmount, $this->startDate, $this->endDate, $this->voucherCount, $this->productName, 'save');
		$I->addVoucher($this->randomVoucherCodeSave, $this->voucherAmount, $this->startDate, $this->endDate, $this->voucherCount, $this->productName, 'saveclose');
	}

	/**
	 *
	 * Function change  sates unpublish voucher state inline
	 *
	 * @param AcceptanceTester $I
	 * @param                  $scenario
	 *
	 * @depends addVoucher
	 * @since 1.4.0
	 */
	public function changeVoucherState(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test change State with clicks on icon of a Voucher gets Updated in Administrator');
		$I = new VoucherManagerJoomla3Steps($scenario);
		$I->changeVoucherState($this->randomVoucherCode);
		$I->verifyState('unpublished', $I->getVoucherState($this->randomVoucherCode), 'State Must be Unpublished');
		$I->changeVoucherState($this->randomVoucherCode);
		$I->verifyState('published', $I->getVoucherState($this->randomVoucherCode), 'State Must be published');
	}

	/**
	 *
	 * Function change  sates unpublish voucher state with button
	 *
	 * @param AcceptanceTester $I
	 * @param                  $scenario
	 *
	 * @since 1.4.0
	 * @depends changeVoucherState
	 */
	public function changeVoucherStatusButton(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test change State with user button of a Voucher gets Updated in Administrator');
		$I = new VoucherManagerJoomla3Steps($scenario);
		$I->changeVoucherUnpublishButton($this->randomVoucherCode);
		$I->verifyState('unpublished', $I->getVoucherState($this->randomVoucherCode), 'State Must be Unpublished');
		$I->changeVoucherPublishButton($this->randomVoucherCode);
		$I->verifyState('published', $I->getVoucherState($this->randomVoucherCode), 'State Must be Unpublished');
	}

	/**
	 *
	 * Function change  sates unpublish for all vouchers state with button
	 *
	 * @param AcceptanceTester $I
	 * @param                  $scenario
	 *
	 * @depends changeVoucherStatusButton
	 * @since 1.4.0
	 */
	public function changeAllVoucherButton(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Change all vouchers status with  button of a Voucher gets Updated in Administrator');
		$I = new VoucherManagerJoomla3Steps($scenario);
		$I->changeAllVoucherUnpublishButton();
		$I->verifyState('unpublished', $I->getVoucherState($this->randomVoucherCode), 'State Must be Unpublished');
		$I->changeAllVoucherPublishButton();
		$I->verifyState('published', $I->getVoucherState($this->randomVoucherCode), 'State Must be Unpublished');
	}

	/**
	 *
	 * Function Edit voucher missing code
	 *
	 * @param AcceptanceTester $I
	 * @param                  $scenario
	 * @since 1.4.0
	 */
	public function editVoucherMissingNameCode(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test if Voucher gets updated in Administrator');
		$I = new VoucherManagerJoomla3Steps($scenario);
		$I->editVoucherMissingName($this->randomVoucherCode);
	}

	/**
	 * Function to Test Voucher Update with Save button in the Administrator
	 * @since 1.4.0
	 * @depends changeAllVoucherButton
	 */
	public function updateVoucher(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test if Voucher gets updated in Administrator');
		$I = new VoucherManagerJoomla3Steps($scenario);
		$I->editVoucher($this->randomVoucherCode, $this->updatedRandomVoucherCode);
	}

	/**
	 * Function to Test Voucher Deletion
	 *
	 * @depends updateVoucher
	 * @since 1.4.0
	 */
	public function deleteVoucher(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Deletion of Voucher in Administrator');
		$I = new VoucherManagerJoomla3Steps($scenario);
		$I->deleteVoucher($this->updatedRandomVoucherCode);
	}

	/**
	 * Function to Test Voucher Update with Save button in the Administrator
	 *
	 * @depends updateVoucher
	 * @since 1.4.0
	 */
	public function deleteAllVoucher(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test if Voucher gets updated in Administrator');
		$I = new VoucherManagerJoomla3Steps($scenario);
		$I->deleteAllVoucher($this->randomVoucherCodeSave);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @since 1.4.0
	 */
	public function checkButtons(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test to validate different buttons on Voucher Views');
		$I = new VoucherManagerJoomla3Steps($scenario);
		$I->checkButtons('cancel');
		$I->checkButtons('publish');
		$I->checkButtons('unpublish');
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @since 1.4.0
	 */
	public function voucherMissingFieldValidValidation(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test to validate different Missing Fields in the Voucher View');
		$I = new VoucherManagerJoomla3Steps($scenario);
		$I->voucherMissingFieldValidValidation($this->randomVoucherCode, $this->voucherAmount, $this->startDate, $this->endDate, $this->voucherCount, $this->productName, 'code');
		$I->voucherMissingFieldValidValidation($this->randomVoucherCode, $this->voucherAmount, $this->startDate, $this->endDate, $this->voucherCount, $this->productName, 'product');
	}
}
