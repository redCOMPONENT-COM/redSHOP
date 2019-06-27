<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\CategoryManagerJoomla3Steps as CategorySteps;
use AcceptanceTester\ProductManagerJoomla3Steps as ProductSteps;
use AcceptanceTester\ShopperGroupManagerJoomla3Steps as ShopperGroupSteps;
use AcceptanceTester\UserManagerJoomla3Steps as UserSteps;
use AcceptanceTester\DiscountProductSteps;

/**
 * Class ProductPriceDiscountCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    2.1.2.2
 */
class ProductPriceDiscountCest
{
	/**
	 * ProductPriceDiscountCest constructor.
	 * @since 2.1.2.2
	 */
	public function __construct()
	{
		$this->faker = Faker\Factory::create();
		$this->productname = $this->faker->bothify('Product demo ##');
		$this->categoryname1 = $this->faker->bothify('Category parent ##');
		$this->categoryname2 = $this->faker->bothify('Category child ##');
		$this->shoppergroupname = $this->faker->bothify('VIP ##');
		$this->username = $this->faker->bothify('test##');
		$this->pass = $this->faker->bothify('???###?#');
		$this->email = $this->faker->email;
		$this->group = 'Administrator';
		$this->shoppergroupitem = $this->faker->bothify('Default Private');
		$this->customerType = 'Company customer';
		$this->shipping = 'no';
		$this->enableQuotation = 'yes';
		$this->showVat = 'no';
		$this->shopperGroupPortal = 'no';
		$this->shippingRate = $this->faker->numberBetween(1, 100);
		$this->shippingCheckout = $this->faker->numberBetween(1, 100);
		$this->catalog = 'Yes';
		$this->showPrice = 'Yes';
		$this->firstname = $this->faker->firstName;
		$this->lastname = $this->faker->lastName;
		$this->address = $this->faker->address;
		$this->postcode = '2000';
		$this->city = 'Ho Chi Minh';
		$this->phone = $this->faker->phoneNumber;
		$this->discountname = $this->faker->bothify('Product price discounts ##');
		$this->number = $this->faker->numberBetween(50, 1000);
		$this->price = $this->faker->numberBetween(100, 1000);
		$this->totalAmount = '100';
		// Higher
		$this->condition = 3;
		// Total
		$this->type = 1;
		$this->discountAmount = $this->faker->numberBetween(1, 99);
		$this->total = $this->price - $this->discountAmount;
		$this->startDate = date('Y-m-d');
		$this->endDate = date('Y-m-d', strtotime('+2 day', strtotime($this->startDate)));
		$this->currentcyunit = 'DKK ';
	}

	/**
	 * @param AcceptanceTester $I
	 * @throws Exception
	 * @since 2.1.2.2
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param CategorySteps $I
	 * @throws Exception
	 * @since 2.1.2.2
	 */
	public function createCategory(CategorySteps $I, $scenario)
	{
		$I->wantToTest("I want to create category parent");
		$I->addCategorySaveClose($this->categoryname1);

		$I->wantToTest("I want to create category child");
		$I->createCategoryChild($this->categoryname1, $this->categoryname2);

		$I =new ProductSteps($scenario);
		$I->wantToTest("I want to create product with category child");
		$I->createProductSaveClose($this->productname, $this->categoryname2, $this->number, $this->price);

        $I = new ShopperGroupSteps($scenario);
        $I->wantToTest("I want to create shopper group");
        $I->addShopperGroups($this->shoppergroupname, $this->shoppergroupitem, $this->customerType, $this->shopperGroupPortal,$this->categoryname2,$this->shipping, $this->shippingRate, $this->shippingCheckout, $this->catalog, $this->showVat,$this->showPrice,$this->enableQuotation, 'saveclose');

        $I = new UserSteps($scenario);
        $I->wantToTest("I want to create user");
        $I->addUser($this->username, $this->pass, $this->email, $this->group, $this->shoppergroupname, $this->firstname, $this->lastname, 'saveclose');
		$I->editAddShipping($this->firstname, $this->pass, $this->address, $this->city, $this->phone, $this->postcode);

        $I = new DiscountProductSteps($scenario);
        $I->wantToTest("I want to create product price discounts");
        $I->addDiscountProductSave($this->totalAmount, $this->condition, $this->type, $this->discountAmount, $this->startDate, $this->endDate, $this->categoryname2, $this->shoppergroupname);

        $I = new \AcceptanceTester\ProductCheckoutManagerJoomla3Steps($scenario);
        $I->wantToTest("I want to check discount in frontend");
        $I->doFrontEndLogin($this->username, $this->pass);
		$I->checkDiscountWithCategoryChild($this->categoryname1, $this->categoryname2, $this->productname, $this->currentcyunit.$this->total);
	}

	/**
	 * @param ProductSteps $I
	 * @param $scenario
	 * @throws Exception
     * @since 2.1.2.2
	 */
	public function deleteAll(DiscountProductSteps $I, $scenario)
	{
		$I->wantToTest("I want to delete user");
		$I->deleteAllDiscountProducts();

		$I = new UserSteps($scenario);
		$I->wantToTest("I want to delete user");
		$I->deleteUser($this->firstname);

		$I = new ShopperGroupSteps($scenario);
		$I->wantToTest("I want to delete shopper group");
		$I->deleteShopperGroups($this->shoppergroupname);

		$I = new ProductSteps($scenario);
		$I->wantToTest("I want to delete product");
		$I->deleteProduct($this->productname);

		$I = new CategorySteps($scenario);
		$I->wantToTest("I want to delete category child");
		$I->deleteCategory($this->categoryname2);

		$I->wantToTest("I want to delete category parent");
		$I->deleteCategory($this->categoryname1);
	}
}