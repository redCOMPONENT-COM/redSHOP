<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2018 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\UserManagerJoomla3Steps;
use AcceptanceTester\OrderManagerJoomla3Steps;
/**
 * Class OrderBackendProductAttributeCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    2.4
 */
class OrderBackendProductAttributeCest
{
	public function __construct()
	{
		$this->faker                = Faker\Factory::create();
		$this->productName          = $this->faker->bothify('ProductName ?##?');
		$this->categoryName         = $this->faker->bothify('CategoryName ?##?');
		$this->minimumPerProduct    = 1;
		$this->minimumQuantity      = 1;
		$this->maximumQuantity      = $this->faker->numberBetween(100, 1000);
		$this->productNumber        = $this->faker->numberBetween(999, 9999);
		$this->price                = $this->faker->numberBetween(100, 1000);
		$this->nameAttribute        = 'Size';
		$this->valueAttribute       = "Z";
		$this->priceAttribute       = 12;
		$this->userName             = $this->faker->bothify('ManageUserAdministratorCest ?##?');
		$this->password             = $this->faker->bothify('Password ?##?');
		$this->email                = $this->faker->email;
		$this->shopperGroup         = 'Default Private';
		$this->group                = 'Public';
		$this->firstName            = $this->faker->bothify('First Name ?##?');
		$this->lastName             = 'Last';
		$this->address              = '449 Tran Hung Dao';
		$this->zipcode              = '1';
		$this->city                 = 'Ho Chi Minh';
		$this->phone                = '0123456789';
		$this->subtotal             = $this->price;
	}

	/**
	 * @param AcceptanceTester $I
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param AcceptanceTester $I
	 *
	 * @param   \Codeception\Scenario $scenario Scenario
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 */
	public function OrderBackendProductAttribute(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Create Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySaveClose($this->categoryName);

		$I->wantTo('Create Product with attribute in Administrator');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->createProductWithAttribute($this->productName, $this->categoryName, $this->productNumber, $this->price, $this->nameAttribute, $this->valueAttribute, $this->priceAttribute);

		$I->wantTo('Create User testing in Administrator');
		$I = new AcceptanceTester\UserManagerJoomla3Steps($scenario);
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperGroup, $this->userName, $this->userName, 'saveclose');
		$I->searchUser($this->userName);

		$I->wantTo('Create Order in Administrator');
		$I = new OrderManagerJoomla3Steps($scenario);
		$I->addOrderWithAttribute($this->userName, $this->productName, $this->valueAttribute, $this->price);

		$I->wantTo('Delete Product');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->productName);

		$I->wantTo('Delete Category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->categoryName);

		$I->wantTo('Delete User');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->userName);

		$I->wantTo('Delete Order');
		$I = new OrderManagerJoomla3Steps($scenario);
		$I->deleteOrder($this->userName);
	}
}