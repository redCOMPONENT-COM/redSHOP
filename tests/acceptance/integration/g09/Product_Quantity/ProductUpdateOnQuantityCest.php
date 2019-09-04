<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\CategoryManagerJoomla3Steps;
use Configuration\ConfigurationSteps;
use AcceptanceTester\OrderManagerJoomla3Steps;
use AcceptanceTester\PayPalPluginManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\ProductUpdateOnQuantitySteps;

/**
 * Class ProductUpdateOnQuantityCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    2.1.2
 */
class ProductUpdateOnQuantityCest
{
	/**
	 * @var   Generator
	 */
	protected $faker;

	/**
	 * @var string
	 */
	protected $menuItem;

	/**
	 * @var string
	 */
	protected  $menuCategory;

	/**
	 * @var string
	 */
	protected  $nameProduct;

	/**
	 * @var int
	 */
	protected  $quantity;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	protected $cartSetting;

	public function __construct()
	{
		$this->faker               = Faker\Factory::create();

		$this->menuItem            = 'Cart';
		$this->menuCategory        = 'redSHOP';

		$this->nameProduct         = $this->faker->bothify('Product Name ?##?');;
		$this->quantity            = 10;
		$this->categoryName        = $this->faker->bothify('Category Name ?##?');
		$this->randomProductNumber = $this->faker->numberBetween(999, 9999);
		$this->randomProductPrice  = 50;
		$this->paymentMethod       = 'RedSHOP - Bank Transfer Payment';
		$this->customerInformation = array(
			"email"      => "test@test" . rand() . ".com",
			"firstName"  => $this->faker->bothify('firstNameCustomer ?####?'),
			"lastName"   => $this->faker->bothify('lastNameCustomer ?####?'),
			"address"    => "Some Place in the World",
			"postalCode" => "23456",
			"city"       => "Ho Chi Minh",
			"country"    => "Viet Nam",
			"state"      => "",
			"phone"      => "0334110355"
		);

		$this->cartSetting = array(
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
	}

	/**
	 * @param ProductUpdateOnQuantitySteps $I
	 * @throws Exception
	 */
	public function _before(ProductUpdateOnQuantitySteps $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param ProductUpdateOnQuantitySteps $I
	 * @throws Exception
	 */
	public function createMenuItem(ProductUpdateOnQuantitySteps $I)
	{
		$I->wantTo("Menu item cart in front end");
		$I->createNewMenuItem($this->menuItem, $this->menuCategory, $this->menuItem);
	}

	/**
	 * @param ProductUpdateOnQuantitySteps $I
	 * @param $scenario
	 * @throws Exception
	 */
	public function addToCartWithProductUpdateQuantity(ProductUpdateOnQuantitySteps $I,$scenario)
	{
		$I = new ConfigurationSteps($scenario);
		$I->cartSetting($this->cartSetting);

		$I = new PayPalPluginManagerJoomla3Steps($scenario);
		$I->wantTo('Disable PayPal');
		$I->disablePlugin('PayPal');

		$I->wantTo('Create Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->categoryName);

		$I->wantTo('I Want to add product inside the category');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->createProductSaveClose($this->nameProduct, $this->categoryName, $this->randomProductNumber, $this->randomProductPrice);
		$I->wantTo('setup up one page checkout at admin');

		$I->amOnPage(ConfigurationPage::$URL);
		$currencySymbol = $I->grabValueFrom(ConfigurationPage::$currencySymbol);
		$decimalSeparator = $I->grabValueFrom(ConfigurationPage::$decimalSeparator);
		$numberOfPriceDecimals = $I->grabValueFrom(ConfigurationPage::$numberOfPriceDecimals);
		$numberOfPriceDecimals = (int)$numberOfPriceDecimals;
		$NumberZero = null;
		for  ( $b = 1; $b <= $numberOfPriceDecimals; $b++)
		{
			$NumberZero = $NumberZero."0";
		}
		$quantity = (int)$this->quantity;
		$priceTotal = $currencySymbol.''.$this->randomProductPrice*$quantity.$decimalSeparator.$NumberZero;
		$priceTotalWithName = 'Total: '.$currencySymbol.' '.$this->randomProductPrice *$quantity.$decimalSeparator.$NumberZero;

		$I->wantToTest("Review product");
		$I = new OrderManagerJoomla3Steps($scenario);
		$I->checkReview($this->nameProduct);

		$I = new ProductUpdateOnQuantitySteps($scenario);
		$I->checkProductUpdateQuantity($this->nameProduct,$this->quantity,$this->menuItem,$priceTotal,$priceTotalWithName,$this->customerInformation);
		$I->wantTo('Check Order');
		$I = new ConfigurationSteps($scenario);
		$I->checkPriceTotal($this->randomProductPrice, $this->customerInformation['firstName'], $this->customerInformation['firstName'],$this->customerInformation['lastName'], $this->nameProduct, $this->categoryName, $this->paymentMethod);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 */
	public function clearAllData(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Deletion of Order Total Discount in Administrator');
		$I = new OrderManagerJoomla3Steps($scenario);
		$I->deleteOrder( $this->customerInformation['firstName']);

		$I->wantTo('Deletion Product in Administrator');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->nameProduct);

		$I->wantTo('Deletion Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->categoryName);
	}
}
