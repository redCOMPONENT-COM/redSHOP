<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class ManageOrderAdministratorCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class OrderCest
{
	/**
	 * Function to Test Order Creation in Backend
	 *
	 */

	public function __construct()
	{
		//create user for quotation
		$this->faker           = Faker\Factory::create();
		$this->userName        = 'ManageUserAdministratorCest' . rand(10, 100);
		$this->password        = $this->faker->bothify('Password ?##?');
		$this->email           = $this->faker->email;
		$this->shopperGroup    = 'Default Private';
		$this->group           = 'Public';
		$this->firstName       = $this->faker->bothify('ManageUserAdministratorCest FN ?##?');
		$this->updateFirstName = 'Updating ' . $this->firstName;
		$this->lastName        = 'Last';
		$this->address         = '14 Phan Ton';
		$this->zipcode         = 7000;
		$this->city            = 'Ho Chi Minh';
		$this->phone           = 010101010;

		// Create product and category
		$this->randomCategoryName           = 'TestingCategory' . rand(99, 999);
		$this->ramdoCategoryNameAssign      = 'CategoryAssign' . rand(99, 999);
		$this->randomProductName            = 'Testing Products' . rand(99, 999);
		$this->minimumPerProduct            = 2;
		$this->minimumQuantity              = 3;
		$this->maximumQuantity              = 5;
		$this->discountStart                = "2016-12-12";
		$this->discountEnd                  = "2017-05-23";
		$this->randomProductNumber          = rand(999, 9999);
		$this->randomProductNumberNew       = rand(999, 9999);
		$this->randomProductAttributeNumber = rand(999, 9999);
		$this->randomProductNameAttribute   = 'Testing Attribute' . rand(99, 999);
		$this->randomProductPrice           = rand(99, 199);
		$this->discountPriceThanPrice       = 100;
		$this->statusProducts               = 'Product on sale';
		$this->searchCategory               = 'Category';
		$this->newProductName               = 'New-Test Product' . rand(99, 999);
		$this->nameAttribute                = 'Size';
		$this->valueAttribute               = "Z";
		$this->priceAttribute               = 12;
		$this->nameProductAccessories       = "redFORM";
		$this->nameRelatedProduct           = "redITEM";
		$this->quantityStock                = 4;
		$this->PreorderStock                = 2;
		$this->priceProductForThan          = 10;

		$this->quantity    = $this->faker->numberBetween(1, 100);
		$this->newQuantity = $this->faker->numberBetween(100, 300);

		$this->status        = "Confirmed";
		$this->paymentStatus = "Paid";


	}

	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 *
	 * Function create category for product
	 *
	 * @param AcceptanceTester $I
	 * @param                  $scenario
	 */
	public function createCategory(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Create Category in Administrator');
		$I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
		$I->wantTo('Create a Category');
		$I->addCategorySave($this->randomCategoryName);

		$I->wantTo('Test Product Save Manager in Administrator');
		$I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductSave($this->randomProductName, $this->randomCategoryName, $this->randomProductNumber, $this->randomProductPrice, $this->minimumPerProduct, $this->minimumQuantity, $this->maximumQuantity, $this->discountStart, $this->discountEnd);

		$I->wantTo('Test User creation in Administrator');
		$I = new AcceptanceTester\UserManagerJoomla3Steps($scenario);
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperGroup, $this->firstName, $this->lastName, 'save');
		$I->searchUser($this->firstName);

		$I->wantTo('Test Order creation in Administrator');
		$I = new AcceptanceTester\OrderManagerJoomla3Steps($scenario);
		$I->addOrder($this->userName, $this->address, $this->zipcode, $this->city, $this->phone, $this->randomProductName, $this->quantity);

		$I->wantTo('Test Order Edit status and payment in Administrator');
		$I = new AcceptanceTester\OrderManagerJoomla3Steps($scenario);
		$I->editOrder($this->firstName . ' ' . $this->lastName, $this->status, $this->paymentStatus, $this->newQuantity);

		$I->wantTo('Test Order delete by user  in Administrator');
		$I = new AcceptanceTester\OrderManagerJoomla3Steps($scenario);
		$I->deleteOrder($this->firstName);
	}
}
