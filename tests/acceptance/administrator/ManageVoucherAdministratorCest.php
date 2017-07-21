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
        $this->updatedRandomVoucherCode = 'Updating ' . $this->randomVoucherCode;
        $this->voucherAmount = $this->faker->numberBetween(9, 99);
        $this->voucherCount = $this->faker->numberBetween(99, 999);
        $this->startDate = "21-06-2017";
        $this->endDate = "07-07-2017";
        $this->randomCategoryName = 'TestingCategory' . rand(99, 999);
        $this->ramdoCategoryNameAssign = 'CategoryAssign' . rand(99, 999);
        $this->productName = 'Testing Products' . rand(99, 999);
        $this->minimumPerProduct = 2;
        $this->minimumQuantity = 3;
        $this->maximumQuantity = 5;
        $this->discountStart = "12-12-2016";
        $this->discountEnd = "23-05-2017";
        $this->randomProductNumber = rand(999, 9999);
        $this->randomProductNumberNew = rand(999, 9999);
        $this->randomProductAttributeNumber = rand(999, 9999);
        $this->randomProductNameAttribute = 'Testing Attribute' . rand(99, 999);
        $this->randomProductPrice = rand(99, 199);
        $this->discountPriceThanPrice =100;
        $this->statusProducts = 'Product on sale';
        $this->searchCategory = 'Category';
        $this->newProductName = 'New-Test Product' . rand(99, 999);
        $this->nameAttribute = 'Size';
        $this->valueAttribute = "Z";
        $this->priceAttribute = 12;
        $this->nameProductAccessories = "redFORM";
        $this->nameRelatedProduct = "redITEM";
        $this->quantityStock = 4;
        $this->PreorderStock = 2;
        $this->priceProductForThan=10;
    }


    public function createCategory(AcceptanceTester $I, $scenario){
        $I->wantTo('Test Voucher creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
        $I->addCategorySave($this->randomCategoryName);
    }

    public function createProduct(AcceptanceTester $I, $scenario){
        $I->wantTo('Test Voucher creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->createProductSave($this->productName,$this->randomCategoryName, $this->randomProductNumber, $this->randomProductPrice, $this->minimumPerProduct, $this->minimumQuantity, $this->maximumQuantity, $this->discountStart, $this->discountEnd);
    }
    /**
     * Function to Test Voucher with Save and Close  Creation in Backend
     *
     */
    public function createVoucher(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Voucher creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\VoucherManagerJoomla3Steps($scenario);
        $I->addVoucher($this->randomVoucherCode, $this->voucherAmount, $this->voucherCount,$this->productName);
    }

    /**
     * Function check cancel button when goes on new page
     *
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function cancelButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Voucher creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\VoucherManagerJoomla3Steps($scenario);
        $I->cancelButton();
        $I->see('Voucher Management', '.page-title');
    }

//     When backend fix this bugs , Please open code
//    public function addStartMoreThanEnd(AcceptanceTester $I, $scenario)
//    {
//        $I->wantTo('Test Voucher creation in Administrator');
//        $I->doAdministratorLogin();
//        $I = new AcceptanceTester\VoucherManagerJoomla3Steps($scenario);
//        $I->addVoucher($this->randomVoucherCode, $this->voucherAmount,$this->startDate,$this->endDate, $this->voucherCount);
//    }

    /**
     *
     * Function add voucher missing code
     *
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function addVoucherMissingCode(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Voucher creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\VoucherManagerJoomla3Steps($scenario);
        $I->addVoucherMissingCode($this->voucherAmount, $this->startDate, $this->endDate, $this->voucherCount,$this->productName);
    }

    /**
     *
     * Fucntion add voucher missing products
     *
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function addVoucherMissingProducts(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Voucher creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\VoucherManagerJoomla3Steps($scenario);
        $I->addVoucherMissingProducts($this->randomVoucherCode, $this->voucherAmount, $this->startDate, $this->endDate, $this->voucherCount);
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
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\VoucherManagerJoomla3Steps($scenario);
        $I->editVoucherMissingName($this->randomVoucherCode);
    }

    /**
     *
     * Function check cancel button
     *
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function checkCloseButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test if Voucher gets updated in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\VoucherManagerJoomla3Steps($scenario);
        $I->checkCloseButton($this->randomVoucherCode);
    }

    /**
     *
     * Function check edit button without choice any voucher
     *
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function checkEditButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test if Voucher gets updated in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\VoucherManagerJoomla3Steps($scenario);
        $I->checkEditButton();
    }

    /**
     *
     * Function check delete button without choice any voucher
     *
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function checkDeleteButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test if Voucher gets updated in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\VoucherManagerJoomla3Steps($scenario);
        $I->checkDeleteButton();
    }

    /**
     *
     * Function check publish button without choice any voucher
     *
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function checkPublishButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test if Voucher gets updated in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\VoucherManagerJoomla3Steps($scenario);
        $I->checkPublishButton();
    }

    /**
     *
     * Function check unpublish button without choice any voucher
     *
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function checkUnpublishButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test if Voucher gets updated in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\VoucherManagerJoomla3Steps($scenario);
        $I->checkUnpublishButton();
    }

    /**
     * Function to Test Voucher Deletion
     *
     * @depends createVoucher
     */
    public function deleteVoucher(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Deletion of Voucher in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\VoucherManagerJoomla3Steps($scenario);
        $I->deleteVoucher($this->randomVoucherCode);
    }

    /**
     * Function to Test Voucher Creation in Backend
     *
     */
    public function addVoucherSave(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Voucher creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\VoucherManagerJoomla3Steps($scenario);
        $I->addVoucherSave($this->randomVoucherCode, $this->voucherAmount, $this->startDate, $this->endDate, $this->voucherCount,$this->productName);
    }

    /**
     *
     * Function change  sates unpublish voucher state inline
     *
     * @param AcceptanceTester $I
     * @param $scenario
     *
     * @depends addVoucherSave
     */
    public function changeVoucherStateUnpublish(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test if State Unpublish of a Voucher gets Updated in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\VoucherManagerJoomla3Steps($scenario);
        $I->changeVoucherState($this->randomVoucherCode);
        $I->verifyState('unpublished', $I->getVoucherState($this->randomVoucherCode), 'State Must be Unpublished');
    }

    /**
     *
     * Function change  sates publish voucher state inline
     *
     * @param AcceptanceTester $I
     * @param $scenario
     *
     * @depends changeVoucherStateUnpublish
     *
     */
    public function changeVoucherPublish(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test if State Publish of a Voucher gets Updated in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\VoucherManagerJoomla3Steps($scenario);
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
     * @depends changeVoucherPublish
     */
    public function changeVoucherUnpublishButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test if State Unpublish with unpublish button of a Voucher gets Updated in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\VoucherManagerJoomla3Steps($scenario);
        $I->changeVoucherUnpublishButton($this->randomVoucherCode);
        $I->wait(3);
        $I->verifyState('unpublished', $I->getVoucherState($this->randomVoucherCode), 'State Must be Unpublished');
    }

    /**
     *
     * Function change  sates publish voucher state with button
     *
     * @param AcceptanceTester $I
     * @param $scenario
     *
     * @depends changeVoucherUnpublishButton
     */
    public function changeVoucherPublishButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test if State Publish with publish button of a Voucher gets Updated in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\VoucherManagerJoomla3Steps($scenario);
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
     * @depends changeVoucherPublishButton
     */
    public function changeAllVoucherUnpublishButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Change all vouchers with unpublish button of a Voucher gets Updated in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\VoucherManagerJoomla3Steps($scenario);
        $I->changeAllVoucherUnpublishButton();
        $I->verifyState('unpublished', $I->getVoucherState($this->randomVoucherCode), 'State Must be Unpublished');
    }

    /**
     *
     * Function change  sates unpublish for all vouchers state with button
     *
     * @param AcceptanceTester $I
     * @param $scenario
     *
     * @depends changeAllVoucherUnpublishButton
     */
    public function changeAllVoucherPublishButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Change all vouchers Publish with publish button of a Voucher gets Updated in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\VoucherManagerJoomla3Steps($scenario);
        $I->changeAllVoucherPublishButton();
        $I->verifyState('published', $I->getVoucherState($this->randomVoucherCode), 'State Must be Unpublished');
    }

    /**
     * Function to Test Voucher Update with Save button in the Administrator
     *
     *
     * @depends changeAllVoucherPublishButton
     */
    public function updateVoucher(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test if Voucher gets updated in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\VoucherManagerJoomla3Steps($scenario);
        $I->editVoucher($this->randomVoucherCode, $this->updatedRandomVoucherCode);
    }


    /**
     * Function to Test Voucher Update with Save button in the Administrator
     *
     * @depends updateVoucher
     */
    public function deleteAllVoucher(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test if Voucher gets updated in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\VoucherManagerJoomla3Steps($scenario);
        $I->deleteAllVoucher($this->updatedRandomVoucherCode);
    }
}
