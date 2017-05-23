<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace AcceptanceTester;
/**
 * Class ProductManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class ProductManagerJoomla3Steps extends AdminManagerJoomla3Steps
{
	/**
	 * Function to add a Product
	 *
	 * @return void
	 */
	public function addProduct()
	{
		$I = $this;
		$I->amOnPage(\ProductManagerPage::$URL);
		$I->verifyNotices(false, $this->checkForNotices(), 'Product Manager Page');
		$I->click('New');
		$I->verifyNotices(false, $this->checkForNotices(), 'Product Manager New');
		$I->click('Cancel');
	}


	public function checkEditButton(){
        $I=$this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->waitForText('Product Management', 30, ['xpath' => "//h1"]);
        $I->click("Edit");
        $I->acceptPopup();
        $I->waitForElement(\ProductManagerPage::$productFilter, 30);
    }

    public function checkCopyButton(){
        $I=$this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->waitForText('Product Management', 30, ['xpath' => "//h1"]);
        $I->click("Copy");
        $I->acceptPopup();
        $I->waitForElement(\ProductManagerPage::$productFilter, 30);
    }

    public function checkDeleteButton(){
        $I=$this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->waitForText('Product Management', 30, ['xpath' => "//h1"]);
        $I->click("Delete");
        $I->acceptPopup();
        $I->waitForElement(\ProductManagerPage::$productFilter, 30);
    }

    public function checkPublishButton(){
        $I=$this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->waitForText('Product Management', 30, ['xpath' => "//h1"]);
        $I->click("Publish");
        $I->acceptPopup();
        $I->waitForElement(\ProductManagerPage::$productFilter, 30);
    }

    public function checkUnpublishButton(){
        $I=$this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->waitForText('Product Management', 30, ['xpath' => "//h1"]);
        $I->click("Unpublish");
        $I->acceptPopup();
        $I->waitForElement(\ProductManagerPage::$productFilter, 30);
    }

    public function checkAssignNewCategoryButton(){
        $I=$this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->waitForText('Product Management', 30, ['xpath' => "//h1"]);
        $I->click("Assign new Category");
        $I->acceptPopup();
        $I->waitForElement(\ProductManagerPage::$productFilter, 30);
    }

    public function checkRemoveCategoryButton(){
        $I=$this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->waitForText('Product Management', 30, ['xpath' => "//h1"]);
        $I->click("Remove Category");
        $I->acceptPopup();
        $I->waitForElement(\ProductManagerPage::$productFilter, 30);
    }




    public function createProductSave($productName, $productCategory, $productNumber, $price)
    {
        $I=$this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->waitForText('Product Management', 30, ['xpath' => "//h1"]);
        $I->click("New");
        $I->waitForElement(['id' => "product_name"], 30);
        $I->checkForPhpNoticesOrWarnings();
        $I->fillField(['id' => "product_name"], $productName);
        $I->fillField(['id' => "product_number"], $productNumber);
        $I->fillField(['id' => "product_price"], $price);
        $I->click(['xpath' => "//div[@id='s2id_product_category']//ul/li"]);
        $I->fillField(['xpath' => "//div[@id='s2id_product_category']//ul/li//input"], $productCategory);
        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $productCategory . "')]"]);
        $I->click(['xpath' => "//span[contains(text(), '" . $productCategory . "')]"]);
        $I->click("Save");
        $I->waitForText('Product details saved', 30, ['class' => 'alert-success']);
        $I->see('Product details saved', ['class' => 'alert-success']);
    }


    public function deleteProduct($productName)
    {
        $I = $this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->waitForText('Product Management', 30, ['xpath' => "//h1"]);
        $this->searchProduct($productName);
        $I->checkAllResults();
        $I->click("Delete");
        $I->acceptPopup();
        $I->waitForText("Product deleted successfully", 60, '.alert-success');
        $I->see("Product deleted successfully", '.alert-success');
        $I->dontSee($productName);
    }


    public function createProductSaveClose($productName, $productCategory, $productNumber, $price)
    {
        $I=$this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->waitForText('Product Management', 30, ['xpath' => "//h1"]);
        $I->click("New");
        $I->waitForElement(['id' => "product_name"], 30);
        $I->checkForPhpNoticesOrWarnings();
        $I->fillField(['id' => "product_name"], $productName);
        $I->fillField(['id' => "product_number"], $productNumber);
        $I->fillField(['id' => "product_price"], $price);
        $I->click(['xpath' => "//div[@id='s2id_product_category']//ul/li"]);
        $I->fillField(['xpath' => "//div[@id='s2id_product_category']//ul/li//input"], $productCategory);
        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $productCategory . "')]"]);
        $I->click(['xpath' => "//span[contains(text(), '" . $productCategory . "')]"]);
        $I->click("Save & Close");
        $I->waitForText('Product details saved', 30, ['class' => 'alert-success']);
        $I->see('Product details saved', ['class' => 'alert-success']);
    }

    public function createProductSaveNew($productName, $productCategory, $productNumber, $price)
    {
        $I=$this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->waitForText('Product Management', 30, ['xpath' => "//h1"]);
        $I->click("New");
        $I->waitForElement(['id' => "product_name"], 30);
        $I->checkForPhpNoticesOrWarnings();
        $I->fillField(['id' => "product_name"], $productName);
        $I->fillField(['id' => "product_number"], $productNumber);
        $I->fillField(['id' => "product_price"], $price);
        $I->click(['xpath' => "//div[@id='s2id_product_category']//ul/li"]);
        $I->fillField(['xpath' => "//div[@id='s2id_product_category']//ul/li//input"], $productCategory);
        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $productCategory . "')]"]);
        $I->click(['xpath' => "//span[contains(text(), '" . $productCategory . "')]"]);
        $I->click("Save & New");
        $I->waitForText('Product details saved', 30, ['class' => 'alert-success']);
        $I->see('Product details saved', ['class' => 'alert-success']);
    }


    public function createProductCancel()
    {
        $I=$this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->waitForText('Product Management', 30, ['xpath' => "//h1"]);
        $I->click("New");
        $I->waitForElement(['id' => "product_name"], 30);
        $I->checkForPhpNoticesOrWarnings();
        $I->click("Cancel");
        $I->waitForText('Product details saved', 30, ['class' => 'alert-success']);
        $I->see('Product detail editing cancelled', ['class' => 'alert-success']);
    }



    public function checkSelectCategory(){
        $I=$this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->waitForText('Product Management', 30, ['xpath' => "//h1"]);
        $I->click("Remove Category");
        $I->acceptPopup();
        $I->waitForElement(\ProductManagerPage::$productFilter, 30);
    }



    public function createProductSaveCopy($productName, $productCategory, $productNumber, $price)
    {
        $I=$this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->waitForText('Product Management', 30, ['xpath' => "//h1"]);
        $I->click("New");
        $I->waitForElement(['id' => "product_name"], 30);
        $I->checkForPhpNoticesOrWarnings();
        $I->fillField(['id' => "product_name"], $productName);
        $I->fillField(['id' => "product_number"], $productNumber);
        $I->fillField(['id' => "product_price"], $price);
        $I->click(['xpath' => "//div[@id='s2id_product_category']//ul/li"]);
        $I->fillField(['xpath' => "//div[@id='s2id_product_category']//ul/li//input"], $productCategory);
        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $productCategory . "')]"]);
        $I->click(['xpath' => "//span[contains(text(), '" . $productCategory . "')]"]);
        $I->click("Save & Copy");
        $I->waitForText('Product Copied', 30, ['class' => 'alert-success']);
        $I->see('Product details saved', ['class' => 'alert-success']);
    }


    public function searchProduct($productName){
        $I = $this;
        $I->wantTo('Search the Product');
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->waitForText('Product Management', 30, ['xpath' => "//h1"]);
        $I->filterListBySearchingProduct($productName);
    }
//    private function searchProduct(AcceptanceTester $I, $productName, $functionName = 'Search')
//    {
//        $I->wantTo('Search the Product');
//        $I->amOnPage('administrator/index.php?option=com_redshop&view=product');
//        $I->waitForText('Product Management', 30, ['xpath' => "//h1"]);
//        $I->fillField(['xpath' => "//div[@class='filterItem']//div//input[@name='keyword']"], $productName);
//        $I->click(['xpath' => "//div[@class='filterItem']//div//input[@value='Search']"]);
//
//        if ($functionName == 'Search') {
//            $I->see($productName, ['xpath' => "//table[contains(@class, 'adminlist')]//tbody//tr[1]"]);
//        } else {
//            $I->dontSee($productName, ['xpath' => "//table[contains(@class, 'adminlist')]//tbody//tr[1]"]);
//        }
//    }


}
