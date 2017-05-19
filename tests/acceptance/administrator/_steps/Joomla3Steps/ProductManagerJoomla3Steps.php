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

    public function createProductSave(AcceptanceTester $I, $productName, $productCategory, $productNumber, $price)
    {
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
}
