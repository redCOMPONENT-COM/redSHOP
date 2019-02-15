<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\UserManagerJoomla3Steps;
use AcceptanceTester\UpdateDiscountAndSpecialDiscountSteps;
/**
 * Class UpdateDiscountAndSpecialDiscountCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    2.4
 */
class UpdateDiscountAndSpecialDiscountCest
{
    public function __construct()
    {
        //Product & Category
        $this->faker = Faker\Factory::create();
        $this->productName = $this->faker->bothify('Product Name ?##?');;
        $this->categoryName = $this->faker->bothify('Category Name ?##?');
        $this->randomProductNumber = $this->faker->numberBetween(999, 9999);
        $this->randomProductPrice = 100;

        //User
        $this->userName = $this->faker->bothify('ManagerUser ?##?');
        $this->password = $this->faker->bothify('123456');
        $this->email = $this->faker->email;
        $this->shopperGroup = 'Default Private';
        $this->group = 'Super User';
        $this->firstName = $this->faker->bothify('FirstName FN ?##?');
        $this->lastName = "LastName";
    }


    /**
     * @param AcceptanceTester $I
     */
    public function _before(AcceptanceTester $I)
    {
        $I->doAdministratorLogin();
    }
    public function changeQuantityInCart(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Create Category in Administrator');
        $I = new CategoryManagerJoomla3Steps($scenario);
        $I->addCategorySave($this->categoryName);

        $I->wantTo('I Want to add product inside the category');
        $I = new ProductManagerJoomla3Steps($scenario);
        $I->createProductSaveClose($this->productName, $this->categoryName, $this->randomProductNumber, $this->randomProductPrice);

        $I->wantTo('Test User creation with save button in Administrator');
        $I = new UserManagerJoomla3Steps($scenario);
        $I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperGroup, $this->firstName, $this->lastName, $function = "save");

        $I->wantTo('I want create order and update discount and special discount');
        $I = new UpdateDiscountAndSpecialDiscountSteps($scenario);
        $I->updateDiscountAndSpecialDiscount($this->userName, $this->productName);

        $I->wantTo('Delete product');
        $I = new ProductManagerJoomla3Steps($scenario);
        $I->deleteProduct($this->productName);

        $I->wantTo('Delete Category');
        $I = new CategoryManagerJoomla3Steps($scenario);
        $I->deleteCategory($this->categoryName);

        $I->wantTo('Delete account in redSHOP and Joomla');
        $I = new UserManagerJoomla3Steps($scenario);
        $I->deleteUser($this->firstName, false);

    }
}