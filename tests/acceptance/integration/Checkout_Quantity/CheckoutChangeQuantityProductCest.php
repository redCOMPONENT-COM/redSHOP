<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2018 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\CheckoutProductQuantityChangeSteps;
use AcceptanceTester\ConfigurationSteps;
use AcceptanceTester\UserManagerJoomla3Steps;
/**
 * Class CheckoutChangeQuantityCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    2.1
 */
class CheckoutProductChangeQuantityCest
{
    /**
     * @var string
     */
    public $categoryName;
    /**
     * @var \Faker\Generator
     */
    public $faker;
    public function __construct()
    {
        //Product & Category
        $this->faker = Faker\Factory::create();
        $this->productName = $this->faker->bothify('Product Name ?##?');;
        $this->categoryName = $this->faker->bothify('Category Name ?##?');
        $this->productPrice = 50;
        $this->total = "DKK 50,00";;
        $this->randomProductNumber = $this->faker->numberBetween(999, 9999);
        $this->randomProductPrice = 100;
        //User
        $this->userName = $this->faker->bothify('ManageUserAdministratorCest ?##?');
        $this->password = $this->faker->bothify('123456');
        $this->email = $this->faker->email;
        $this->shopperGroup = 'Default Private';
        $this->group = 'Super User';
        $this->firstName = $this->faker->bothify('ManageUserAdministratorCest FN ?##?');
        $this->lastName = "LastName";
        $this->address = "449 Tran Hung Dao";
        $this->city = "Thanh pho Ho Chi Minh";
        $this->phone = "0123456789";
        $this->zipcode = "1";
    }
    public function _before(AcceptanceTester $I)
    {
        $I->doAdministratorLogin();
    }
    /**
     * Step1 : Enable Configuration change (One step checkout, quantity, shipping default same address)
     * Step2 : Create category
     * Step3 : Create product have price is 100
     * Step4 : Create User
     * Step4 : Goes on frontend
     * Step5 : Click "Add to cart", change, checkout for product
     * Step6 : Delete data
     * Step7 : Disable Configuration change
     *
     * @param  AcceptanceTester $I
     * @param  mixed $scenario
     *
     * @return  void
     */
    public function changeQuantityInCart(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Enable Quantity Change in Cart');
        $I = new ConfigurationSteps($scenario);
        $I->configChangeQuantityProduct();

        $I->wantTo('Create Category in Administrator');
        $I = new CategoryManagerJoomla3Steps($scenario);
        $I->addCategorySave($this->categoryName);

        $I->wantTo('I Want to add product inside the category');
        $I = new ProductManagerJoomla3Steps($scenario);
        $I->createProductSaveClose($this->productName, $this->categoryName, $this->randomProductNumber, $this->randomProductPrice);

        $I->wantTo('Test User creation with save button in Administrator');
        $I = new UserManagerJoomla3Steps($scenario);
        $I->createUser($this->userName, $this->password, $this->email, $this->group, $this->shopperGroup, $this->firstName, $this->lastName, $this->address, $this->city, $this->phone, $this->zipcode);

        $I->wantTo('I want go to Product tab, Choose Product and Add to cart');
        $I = new CheckoutProductQuantityChangeSteps($scenario);
        $I->checkoutChangeQuantity($this->categoryName, $this->userName, $this->password);

        $I->wantTo('I want to login Site page with user just create');
        $I->doFrontendLogout();

        $I->wantTo('Delete product');
        $I = new ProductManagerJoomla3Steps($scenario);
        $I->deleteProduct($this->productName);

        $I->wantTo('Delete Category');
        $I = new CategoryManagerJoomla3Steps($scenario);
        $I->deleteCategory($this->categoryName);

//        $I->wantTo('Delete account in redSHOP and Joomla');
//        $I = new CheckoutProductQuantityChangeSteps($scenario);
//        $I->deleteUser($this->firstName);

        $I->wantTo('Return Configuration in Administrator page');
        $I = new ConfigurationSteps($scenario);
        $I->returnConfigChangeQuantityProduct();
    }
}
