<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\ProductUpdateOnQuantitySteps;
use Configuration\ConfigurationSteps as ConfigurationSteps;

/**
 * Class CheckoutProductInManufacturerDetailCest
 * @since 3.0.2
 */
class CheckoutProductInManufacturerDetailCest
{
	/**
	 * @var \Faker\Generator
	 * @since 3.0.2
	 */
	protected $faker;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $categoryName;

	/**
	 * @var array
	 * @since 3.0.2
	 */
	protected $manufacturer;

	/**
	 * @var array
	 * @since 3.0.2
	 */
	protected $product;

	/**
	 * @var array
	 * @since 3.0.2
	 */
	protected $cartSetting;

	/**
	 * @var array
	 * @since 3.0.2
	 */
	protected $customerInformation;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $menuItemName;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $menuCategory;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $menu;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $menuItem;

	/**
	 * CheckoutProductInManufacturerDetailCest constructor.
	 * @since 3.0.2
	 */
	public function __construct()
	{
		$this->faker        = Faker\Factory::create();
		$this->categoryName = $this->faker->bothify('CategoryName ?###?');

		$this->manufacturer = array
			(
				'name'           => $this->faker->bothify('Manufacturer?##?'),
				'email'          => $this->faker->email,
				'template'       => 'manufacturer_products',
				'productPerPage' => 5,
			 );

		$this->product = array
			(
				'name'         => $this->faker->bothify('Testing Product ??####?'),
				'number'       => rand(999, 9999),
				'price'        => rand(99, 199),
				'manufacturer' => $this->manufacturer['name']
			);

		$this->customerInformation = array
		(
			"email"      => "test@test" . rand() . ".com",
			"firstName"  => "Tester",
			"lastName"   => "User",
			"address"    => "Some Place in the World",
			"postalCode" => "23456",
			"city"       => "Bangalore",
			"country"    => "Denmark",
			"state"      => "Karnataka",
			"phone"      => "8787878787"
		);

		//configuration enable one page checkout
		$this->cartSetting = array
		(
			"addCart"           => 'product',
			"allowPreOrder"     => 'yes',
			"cartTimeOut"       => $this->faker->numberBetween(100, 10000),
			"enabledAjax"       => 'no',
			"defaultCart"       => null,
			"buttonCartLead"    => 'Back to current view',
			"onePage"           => 'yes',
			"showShippingCart"  => 'no',
			"attributeImage"    => 'no',
			"quantityChange"    => 'no',
			"quantityInCart"    => 0,
			"minimumOrder"      => 0,
			"enableQuotation"   => 'no'
		);

		$this->menuItemName = $this->faker->bothify("Manufacturer Details ??####?");
		$this->menuCategory = 'redSHOP';
		$this->menu         = 'Main Menu';
		$this->menuItem     = 'Manufacturer Details';
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function createData(AcceptanceTester $I, $scenario)
	{
		$I->doAdministratorLogin();
		$I->wantTo('setup up one page checkout at admin');
		$I = new ConfigurationSteps($scenario);
		$I->cartSetting($this->cartSetting);
		$I = new ManufacturerSteps($scenario);
		$I->wantTo('Create a manufacturer');
		$I->addManufacturer($this->manufacturer);
		$I = new ProductUpdateOnQuantitySteps($scenario);
		$I->createNewMenuItemManufacturer($this->menuItemName, $this->menuCategory, $this->menuItem, $this->manufacturer['name'], $this->menu);

		$I->wantTo('Create Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->wantTo('Create a Category');
		$I->addCategorySave($this->categoryName);

		$I->wantTo("create new product to checkout");
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductHaveManufacturer($this->product, $this->categoryName);

		$I = new CheckoutOnFrontEnd($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->CheckoutProductInManufacturerDetail($this->product['name'], $this->menuItemName, $this->product['price'], $this->customerInformation);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function clearAllData(AcceptanceTester $I, $scenario)
	{
		$I->doAdministratorLogin();
		$I->wantTo('Delete product');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->product['name']);

		$I->wantTo('Delete Category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->categoryName);

		$I = new ManufacturerSteps($scenario);
		$I->wantTo('Delete a manufacturer');
		$I->deleteManufacturer($this->manufacturer['name']);
	}
}
