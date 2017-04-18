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
	/**
	 * Function to test Products Manager in Administrator
	 *
	 * @param   AcceptanceTester  $I         Tester Object
	 * @param   String            $scenario  Scenario Name
	 *
	 * @return void
	 */
	public function testProductAdministrator(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Product Manager in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
		$randomCategoryName = 'TestingCategory' . rand(99, 999);
		$randomProductName = 'Testing Products' . rand(99, 999);
		$randomProductNumber = rand(999, 9999);
		$randomProductPrice = rand(99, 199);

		$I->wantTo('Create a Category');
		$I->addCategory($randomCategoryName);
		$I->see($randomCategoryName);

		$this->createProduct($I, $randomProductName, $randomCategoryName, $randomProductNumber, $randomProductPrice);
		$this->searchProduct($I, $randomProductName);
		$I->wantTo('Delete the product which was created');
		$this->deleteProduct($I, $randomProductName);
		$I->wantTo('Delete Category');
		$I->amOnPage(\CategoryManagerJ3Page::$URL);
		$I->deleteCategory($randomCategoryName);
		$I->searchCategory($randomCategoryName, 'Delete');
		$I->dontSee($randomCategoryName);
	}

	/**
	 * Function to create a Product
	 *
	 * @param   AcceptanceTester  $I                Object
	 * @param   String            $productName      Name for the Product
	 * @param   String            $productCategory  Category for the Product
	 * @param   String            $productNumber    Number for the Product
	 * @param   String            $price            Price for the Product
	 *
	 * @return void
	 */
	private function createProduct(AcceptanceTester $I, $productName, $productCategory, $productNumber, $price)
	{
		$I->amOnPage('administrator/index.php?option=com_redshop&view=products');
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

	/**
	 * Function to Delete a Product
	 *
	 * @param   AcceptanceTester  $I            Acceptance Tester Object
	 * @param   String            $productName  Name of the Product which is to be deleted
	 *
	 * @return void
	 */
	private function deleteProduct(AcceptanceTester $I, $productName)
	{
		$I->wantTo('Delete an existing Product');
		$I->amOnPage('administrator/index.php?option=com_redshop&view=products');
		$I->waitForText('Product Management', 30, ['xpath' => "//h1"]);
		$I->fillField(['xpath' => "//div[@class='filterItem']//div//input[@name='keyword']"], $productName);
		$I->click(['xpath' => "//div[@class='filterItem']//div//input[@value='Search']"]);
		$I->see($productName, ['xpath' => "//table[contains(@class, 'adminlist')]//tbody//tr[1]"]);
		$I->checkAllResults();
		$I->click("Delete");
		$I->acceptPopup();
		$I->waitForText('Product deleted successfully', 30, ['class' => 'alert-success']);
		$this->searchProduct($I, $productName, 'Delete');
		$I->dontSee($productName);
	}

	/**
	 * Function to Search for a Product
	 *
	 * @param   AcceptanceTester  $I             Acceptance Tester Helper Object
	 * @param   String            $productName   Name of the Product which is to be Searched
	 * @param   string            $functionName  Function Name for which Search is being called
	 *
	 * @return void
	 */
	private function searchProduct(AcceptanceTester $I, $productName, $functionName = 'Search')
	{
		$I->wantTo('Search the Product');
		$I->amOnPage('administrator/index.php?option=com_redshop&view=products');
		$I->waitForText('Product Management', 30, ['xpath' => "//h1"]);
		$I->fillField(['xpath' => "//div[@class='filterItem']//div//input[@name='keyword']"], $productName);
		$I->click(['xpath' => "//div[@class='filterItem']//div//input[@value='Search']"]);

		if ($functionName == 'Search')
		{
			$I->see($productName, ['xpath' => "//table[contains(@class, 'adminlist')]//tbody//tr[1]"]);
		}
		else
		{
			$I->dontSee($productName, ['xpath' => "//table[contains(@class, 'adminlist')]//tbody//tr[1]"]);
		}
	}
}
