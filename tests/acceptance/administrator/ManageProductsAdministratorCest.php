<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class ManageProductsAdministratorCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class ManageProductsAdministratorCest
{

    public function __construct()
    {
        $this->randomCategoryName = 'TestingCategory' . rand(99, 999);
        $this->randomProductName = 'Testing Products' . rand(99, 999);
        $this->randomProductNumber = rand(999, 9999);
        $this->randomProductPrice = rand(99, 199);
    }

    /**
     * Function to test Products Manager in Administrator
     *
     * @param   AcceptanceTester $I Tester Object
     * @param   String $scenario Scenario Name
     *
     * @return void
     */
    public function testProductAdministrator(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Create Category in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
        $I->wantTo('Create a Category');
        $I->addCategory($this->randomCategoryName);
//        $I->see($this->randomCategoryName);

//        $I->wantTo('I Want to add product inside the category');
//        $I->createProduct($this->randomProductName, $this->randomCategoryName, $this->randomProductNumber, $this->randomProductPrice);
//        $this->searchProduct($I, $this->randomProductName);
//        $I->wantTo('Delete the product which was created');
//        $this->deleteProduct($I, $this->randomProductName);
//        $I->wantTo('Delete Category');
//        $I->deleteCategory($this->randomCategoryName);
    }

    /**
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function checkEditButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Edit Button Product Manager in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->wantTo('I Want to add product inside the category');
        $I->checkEditButton();
    }


    /**
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function checkCopyButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Copy Button Product Manager in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->wantTo('I Want to add product inside the category');
        $I->checkCopyButton();
    }

    /**
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function checkDeleteButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Delete  Button Product Manager in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->wantTo('I Want to add product inside the category');
        $I->checkDeleteButton();
    }

    /**
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function checkPublishButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Publish Button Product Manager in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->wantTo('I Want to add product inside the category');
        $I->checkPublishButton();
    }

    /**
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function checkUnpublishButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Unpublish Button Product Manager in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->wantTo('I Want to add product inside the category');
        $I->checkUnpublishButton();
    }

    /**
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function checkAssignNewCategoryButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Assign New Category Button Product Manager in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->wantTo('I Want to add product inside the category');
        $I->checkAssignNewCategoryButton();
    }

    /**
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function checkRemoveCategoryButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Assign New Category Button Product Manager in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->wantTo('I Want to add product inside the category');
        $I->checkRemoveCategoryButton();
    }

    /**
     * @param AcceptanceTester $I
     * @param $scenario
     *
     */
    public function createProductSave(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Product Save Manager in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->wantTo('I Want to add product inside the category');
        $I->createProductSave($this->randomProductName, $this->randomCategoryName, $this->randomProductNumber, $this->randomProductPrice);
    }

    public function deleteProduct(AcceptanceTester $I,  $scenario)
    {
        $I->wantTo('Delete Product Manager in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->wantTo('I Want to delete product inside the category');
        $I->deleteProduct($this->randomProductName);
    }


    /**
     * @param AcceptanceTester $I
     * @param $scenario
     */

    public function createProductSaveClose(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Product Save Manager in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->wantTo('I Want to add product inside the category');
        $I->createProductSaveClose($this->randomProductName, $this->randomCategoryName, $this->randomProductNumber, $this->randomProductPrice);
    }


    public function createProductSaveNew(AcceptanceTester $I, $scenario){
        $I->wantTo('Test Product Save Manager in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->wantTo('I Want to add product inside the category');
        $I->createProductSaveNew($this->randomProductName, $this->randomCategoryName, $this->randomProductNumber, $this->randomProductPrice);
    }

    public function createProductCancel(AcceptanceTester $I, $scenario){
        $I->wantTo('Test Product Save Manager in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->wantTo('I Want to add product inside the category');
        $I->createProductCancel();
    }
    /**
     * Function to create a Product
     *
     * @param   AcceptanceTester $I Object
     * @param   String $productName Name for the Product
     * @param   String $productCategory Category for the Product
     * @param   String $productNumber Number for the Product
     * @param   String $price Price for the Product
     *
     * @return void
     */
//    private function createProduct(AcceptanceTester $I, $productName, $productCategory, $productNumber, $price)
//    {
//        $I->amOnPage(\ProductManagerPage::$URL);
//        $I->checkForPhpNoticesOrWarnings();
//        $I->waitForText('Product Management', 30, ['xpath' => "//h1"]);
//        $I->click("New");
//        $I->waitForElement(['id' => "product_name"], 30);
//        $I->checkForPhpNoticesOrWarnings();
//        $I->fillField(['id' => "product_name"], $productName);
//        $I->fillField(['id' => "product_number"], $productNumber);
//        $I->fillField(['id' => "product_price"], $price);
//        $I->click(['xpath' => "//div[@id='s2id_product_category']//ul/li"]);
//        $I->fillField(['xpath' => "//div[@id='s2id_product_category']//ul/li//input"], $productCategory);
//        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $productCategory . "')]"]);
//        $I->click(['xpath' => "//span[contains(text(), '" . $productCategory . "')]"]);
//        $I->click("Save & Close");
//        $I->waitForText('Product details saved', 30, ['class' => 'alert-success']);
//        $I->see('Product details saved', ['class' => 'alert-success']);
//    }


    /**
     * Function to Delete a Product
     *
     * @param   AcceptanceTester $I Acceptance Tester Object
     * @param   String $productName Name of the Product which is to be deleted
     *
     * @return void
     */
//    private function deleteProduct(AcceptanceTester $I, $productName)
//    {
//        $I->wantTo('Delete an existing Product');
//        $I->amOnPage('administrator/index.php?option=com_redshop&view=product');
//        $I->waitForText('Product Management', 30, ['xpath' => "//h1"]);
//        $I->fillField(['xpath' => "//div[@class='filterItem']//div//input[@name='keyword']"], $productName);
//        $I->click(['xpath' => "//div[@class='filterItem']//div//input[@value='Search']"]);
//        $I->see($productName, ['xpath' => "//table[contains(@class, 'adminlist')]//tbody//tr[1]"]);
//        $I->checkAllResults();
//        $I->click("Delete");
//        $I->acceptPopup();
//        $I->waitForText('Product deleted successfully', 30, ['class' => 'alert-success']);
//        $this->searchProduct($I, $productName, 'Delete');
//        $I->dontSee($productName);
//    }

    /**
     * Function to Search for a Product
     *
     * @param   AcceptanceTester $I Acceptance Tester Helper Object
     * @param   String $productName Name of the Product which is to be Searched
     * @param   string $functionName Function Name for which Search is being called
     *
     * @return void
     */
    private function searchProduct(AcceptanceTester $I, $productName, $functionName = 'Search')
    {
        $I->wantTo('Search the Product');
        $I->amOnPage('administrator/index.php?option=com_redshop&view=product');
        $I->waitForText('Product Management', 30, ['xpath' => "//h1"]);
        $I->fillField(['xpath' => "//div[@class='filterItem']//div//input[@name='keyword']"], $productName);
        $I->click(['xpath' => "//div[@class='filterItem']//div//input[@value='Search']"]);

        if ($functionName == 'Search') {
            $I->see($productName, ['xpath' => "//table[contains(@class, 'adminlist')]//tbody//tr[1]"]);
        } else {
            $I->dontSee($productName, ['xpath' => "//table[contains(@class, 'adminlist')]//tbody//tr[1]"]);
        }
    }
}
