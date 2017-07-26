<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class ManageVoucherAdministratorCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class ManageVoucherAdministratorCest
{
    public function __construct()
    {
        $this->faker = Faker\Factory::create();
        $this->randomVoucherCode = $this->faker->bothify('ManageVoucherAdministratorCest ?##?');
        $this->randomVocherCodeSave=$this->faker->bothify("VoucherCodeSave ?##");
        $this->updatedRandomVoucherCode = 'Updating ' . $this->randomVoucherCode;
        $this->voucherAmount = $this->faker->numberBetween(9, 99);
        $this->voucherCount = $this->faker->numberBetween(99, 999);
        $this->startDate = "21-06-2017";
        $this->endDate = "07-07-2017";
        $this->randomCategoryName = $this->faker->bothify('TestingCategory ?##');
        $this->ramdoCategoryNameAssign = $this->faker->bothify('CategoryAssign ?##');
        $this->productName = 'Testing Products' . rand(99, 999);
        $this->minimumPerProduct = 2;
        $this->minimumQuantity = 3;
        $this->maximumQuantity = 5;
        $this->discountStart = "12-12-2016";
        $this->discountEnd = "23-05-2017";
        $this->randomProductNumber = rand(999, 9999);
        $this->randomProductNumberNew = rand(999, 9999);
        $this->randomProductAttributeNumber = rand(999, 9999);
        $this->randomProductNameAttribute = $this->faker->bothify('Testing Attribute ?##');
        $this->randomProductPrice = rand(99, 199);
        $this->discountPriceThanPrice = 100;
        $this->statusProducts = 'Product on sale';
        $this->searchCategory = 'Category';
        $this->newProductName = $this->faker->bothify('New-Test Product ?##');
        $this->nameAttribute = 'Size';
        $this->valueAttribute = "Z";
        $this->priceAttribute = 12;
        $this->nameProductAccessories = "redFORM";
        $this->nameRelatedProduct = "redITEM";
        $this->quantityStock = 4;
        $this->PreorderStock = 2;
        $this->priceProductForThan = 10;
    }

    public function _before(AcceptanceTester $I)
    {
        $I->doAdministratorLogin();
    }

    public function createCategory(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Voucher creation in Administrator');
        $I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
        $I->addCategorySave($this->randomCategoryName);
    }

    public function createProduct(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Voucher creation in Administrator');
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->createProductSave($this->productName, $this->randomCategoryName, $this->randomProductNumber, $this->randomProductPrice, $this->minimumPerProduct, $this->minimumQuantity, $this->maximumQuantity, $this->discountStart, $this->discountEnd);
    }

    public function addVoucher(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Voucher creation in Administrator');
        $I = new AcceptanceTester\VoucherManagerJoomla3Steps($scenario);
        $I->addVoucher($this->randomVoucherCode, $this->voucherAmount, $this->startDate, $this->endDate, $this->voucherCount, $this->productName, 'save');
        $I->addVoucher($this->randomVocherCodeSave, $this->voucherAmount, $this->startDate, $this->endDate, $this->voucherCount, $this->productName, 'saveclose');
    }

    /**
     *
     * Function change  sates unpublish voucher state inline
     *
     * @param AcceptanceTester $I
     * @param $scenario
     *
     * @depends addVoucher
     */
    public function changeVoucherState(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test change State with clicks on icon of a Voucher gets Updated in Administrator');
        $I = new AcceptanceTester\VoucherManagerJoomla3Steps($scenario);
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
     * @param $scenario
     *
     * @depends changeVoucherState
     */
    public function changeVoucherStatusButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test change State with user button of a Voucher gets Updated in Administrator');
        $I = new AcceptanceTester\VoucherManagerJoomla3Steps($scenario);
        $I->changeVoucherUnpublishButton($this->randomVoucherCode);
        $I->wait(3);
        $I->verifyState('unpublished', $I->getVoucherState($this->randomVoucherCode), 'State Must be Unpublished');
        $I->changeVoucherPublishButton($this->randomVoucherCode);
        $I->verifyState('published', $I->getVoucherState($this->randomVoucherCode), 'State Must be Unpublished');
    }
    /**
     *
     * Function change  sates unpublish for all vouchers state with button
     *
     * @param AcceptanceTester $I
     * @param $scenario
     *
     * @depends changeVoucherStatusButton
     */
    public function changeAllVoucherButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Change all vouchers status with  button of a Voucher gets Updated in Administrator');
        $I = new AcceptanceTester\VoucherManagerJoomla3Steps($scenario);
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
     * @param $scenario
     */
    public function editVoucherMissingNameCode(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test if Voucher gets updated in Administrator');
        $I = new AcceptanceTester\VoucherManagerJoomla3Steps($scenario);
        $I->editVoucherMissingName($this->randomVoucherCode);
    }
    /**
     * Function to Test Voucher Update with Save button in the Administrator
     *
     *
     * @depends changeAllVoucherButton
     */
    public function updateVoucher(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test if Voucher gets updated in Administrator');
        $I = new AcceptanceTester\VoucherManagerJoomla3Steps($scenario);
        $I->editVoucher($this->randomVoucherCode, $this->updatedRandomVoucherCode);
    }

    /**
     * Function to Test Voucher Deletion
     *
     * @depends updateVoucher
     */
    public function deleteVoucher(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Deletion of Voucher in Administrator');
        $I = new AcceptanceTester\VoucherManagerJoomla3Steps($scenario);
        $I->deleteVoucher($this->updatedRandomVoucherCode);
    }

    /**
     * Function to Test Voucher Update with Save button in the Administrator
     *
     * @depends updateVoucher
     */
    public function deleteAllVoucher(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test if Voucher gets updated in Administrator');
        $I = new AcceptanceTester\VoucherManagerJoomla3Steps($scenario);
        $I->deleteAllVoucher($this->randomVocherCodeSave);
    }

    public function checkButtons(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test to validate different buttons on Voucher Views');
        $I = new AcceptanceTester\VoucherManagerJoomla3Steps($scenario);
        $I->checkButtons('edit');
        $I->checkButtons('cancel');
        $I->checkButtons('publish');
        $I->checkButtons('unpublish');
        $I->checkButtons('cancel');
    }

    public function voucherMissingFieldValidValidation(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test to validate different Missing Fields in the Voucher View');
        $I = new AcceptanceTester\VoucherManagerJoomla3Steps($scenario);
        $I->voucherMissingFieldValidValidation($this->randomVoucherCode, $this->voucherAmount, $this->startDate, $this->endDate, $this->voucherCount, $this->productName, 'code');
        $I->voucherMissingFieldValidValidation($this->randomVoucherCode, $this->voucherAmount, $this->startDate, $this->endDate, $this->voucherCount, $this->productName, 'product');
    }
}
