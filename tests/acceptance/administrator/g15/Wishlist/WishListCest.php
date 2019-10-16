<?php
/**
 * @package     redSHOP
 * @subpackage  Cest Wishlist
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\ProductUpdateOnQuantitySteps;
use AcceptanceTester\UserManagerJoomla3Steps;
use Configuration\ConfigurationSteps;
use AcceptanceTester\WishListSteps;

/**
 * Class WishlistCest
 *
 * @package  AcceptanceTester
 *
 * @since 2.1.3
 */
class WishListCest
{
	/**
	 * @var \Faker\Generator
	 * @since 2.1.3
	 */
	protected $faker;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	protected $customerInformation;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $categoryName;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	protected $product;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $wishListName;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $wishListName1;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $menuItemName;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $menuCategory;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $menu;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $menuItem;

	/**
	 * WishlistCest constructor.
	 * @since 2.1.3
	 */
	public function __construct()
	{
		$this->faker = Faker\Factory::create();
		$this->wishListName = $this->faker->bothify('WishList Demo ?##?');
		$this->wishListName1 = $this->faker->bothify('WishList Demo 1 ?##?');
		$this->customerInformation = array(
			"userName"      => $this->faker->userName,
			"email"         => $this->faker->email,
			"firstName"     => $this->faker->firstName,
			"lastName"      => $this->faker->lastName,
			"address"       => $this->faker->address,
			"postalCode"    => "700000",
			"city"          => "HCMC",
			"country"       => "Denmark",
			"state"         => "Karnataka",
			"phone"         => $this->faker->phoneNumber,
			"shopperGroup"  => 'Default Private',
			'group'         => 'Registered'
		);

		$this->categoryName = $this->faker->bothify("Category Demo ?##?");
		$this->product = array(
			"name"          => $this->faker->bothify("Product Demo ?##?"),
			"number"        => $this->faker->numberBetween(999,9999),
			"price"         => $this->faker->numberBetween(1,900)
		);

		$this->menuItemName = "My Wishlist";
		$this->menuCategory = 'redSHOP';
		$this->menu         = 'Main Menu';
		$this->menuItem = 'All Wish Lists';
	}

	/**
	 * @param AcceptanceTester $I
	 * @throws Exception
	 * @since 2.1.3
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.3
	 */
	public function createProductAndCategory(AcceptanceTester $I, $scenario)
	{
		$I->wantTo("create Menu item My wish list");
		$I = new ProductUpdateOnQuantitySteps($scenario);
		$I->createNewMenuItem($this->menuItemName, $this->menuCategory, $this->menuItem, $this->menu);

		$I->wantToTest("Create Category");
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySaveClose($this->categoryName);

		$I->wantToTest("Create Product");
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->createProductSaveClose($this->product['name'], $this->categoryName, $this->product['number'], $this->product['price']);

		$I->wantToTest("Create User");
		$I = new UserManagerJoomla3Steps($scenario);
		$I->addUser($this->customerInformation['userName'], $this->customerInformation['userName'], $this->customerInformation['email'], $this->customerInformation['group'],
			$this->customerInformation['shopperGroup'], $this->customerInformation['firstName'], $this->customerInformation['lastName'], 'saveclose');
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.3
	 */
	public function configureAndCheckWishListNoLogin(AcceptanceTester $I, $scenario)
	{
		$I->wantToTest('Configure WishList with no login');
		$I = new ConfigurationSteps($scenario);
		$I->ConfigFeatureWishList('no');

		$I->wantToTest("Check Wish List at frontend");
		$I = new WishListSteps($scenario);
		$I->checkWistListAtFrontend($this->categoryName, $this->product['name'], $this->customerInformation['userName'], $this->customerInformation['userName'],
			$this->menuItemName, $this->wishListName, 'no');
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.3
	 */
	public function checkRemoveProductInFrontend(AcceptanceTester $I, $scenario)
	{
		$I->wantToTest("Remove Product just add WishList");
		$I = new WishListSteps($scenario);
		$I->removeProductInWishList($this->customerInformation['userName'],$this->customerInformation['userName'], $this->wishListName, $this->menuItemName);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.3
	 */
	public function configureAndCheckWishListWithLogin(AcceptanceTester $I, $scenario)
	{
		$I->wantToTest('Configure WishList with login');
		$I = new ConfigurationSteps($scenario);
		$I->ConfigFeatureWishList('yes');

		$I->wantToTest("Check Wish List at frontend");
		$I = new WishListSteps($scenario);
		$I->checkWistListAtFrontend($this->categoryName, $this->product['name'], $this->customerInformation['userName'], $this->customerInformation['userName'],
			$this->menuItemName, $this->wishListName1, 'yes');
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.3
	 */
	public function clearALL(AcceptanceTester $I, $scenario)
	{
		$I->wantToTest('Delete Product');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->product['name']);

		$I->wantToTest('Delete Category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->categoryName);

		$I->wantToTest('Delete User');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->customerInformation['firstName']);
	}
}