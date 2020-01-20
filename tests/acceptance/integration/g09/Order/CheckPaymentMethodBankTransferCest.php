<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
/**
 * Class CouponCheckoutProductCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
use AcceptanceTester\ProductManagerJoomla3Steps as ProductSteps;
use AcceptanceTester\CategoryManagerJoomla3Steps as CategorySteps;
use Configuration\ConfigurationSteps as ConfigurationSteps;
use AcceptanceTester\UserManagerJoomla3Steps as UserSteps;
use AcceptanceTester\OrderManagerJoomla3Steps as OrderSteps;

class CheckPaymentMethodBankTransferCest
{
	/**
	 * CheckPaymentMethodBankTransferCest constructor.
	 */
	public function __construct()
	{
		$this->faker                        = Faker\Factory::create();
		$this->randomCategoryName           = $this->faker->bothify('CategoryTesting ??####?');
		$this->randomProductName            = $this->faker->bothify('TestingProductManagement ??####?');
		$this->minimumPerProduct            = 2;
		$this->minimumQuantity              = 2;
		$this->maximumQuantity              = 5;
		$this->productStart                 = "2018-05-05";
		$this->productEnd                   = "2018-07-08";
		$this->randomProductNumber          = $this->faker->numberBetween(999, 9999);
		$this->randomProductPrice           = rand(9, 19);
		$this->discountPriceThanPrice       = 100;
		$this->statusProducts               = 'Product on sale';
		$this->searchCategory               = 'Category';
		$this->newProductName               = 'New-Test Product' . rand(99, 999);
		$this->priceProductForThan          = 10;
		$this->totalAmount                  = $this->faker->numberBetween(100, 999);
		$this->discountAmount               = $this->faker->numberBetween(10, 100);
		$this->userName                    = $this->faker->bothify('UserAdministratorCest ?##?');
		$this->password                    = $this->faker->bothify('Password ?##?');
		$this->email                       = $this->faker->email;
		$this->emailsave                   = $this->faker->email;
		$this->shopperGroup                = 'Default Private';
		$this->group                       = 'Registered';
		$this->firstName                   = $this->faker->bothify('ManageUserAdministratorCest FN ?##?');
		$this->lastName                    = 'Last';
		$this->firstNameSave               = "FirstName";
		$this->lastNameSave                = "LastName";
		$this->emailWrong                  = "email";
		$this->userNameDelete              = $this->firstName;
		$this->searchOrder                 = $this->firstName.' '.$this->lastName ;
		$this->paymentMethod               = 'RedSHOP - Bank Transfer Payment';

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
	 * @param AcceptanceTester $I
	 * @param \Codeception\Scenario $scenario
	 * @throws Exception
	 */
	public function checkPaymentMethodOnOrderDetail(AcceptanceTester $I, \Codeception\Scenario $scenario)
	{
		$I->doAdministratorLogin();

		$I->wantTo('Enable PayPal');
		$I->enablePlugin('PayPal');

		$I->wantTo('Test User creation with save button in Administrator');
		$I = new UserSteps($scenario);
		$I->addUser($this->userName, $this->password, $this->emailsave, $this->group, $this->shopperGroup, $this->firstName, $this->lastName, 'saveclose');

		$I->wantTo('Create Category in Administrator');
		$I = new CategorySteps($scenario);
		$I->addCategorySave($this->randomCategoryName);

		$I->wantTo('I want to add product inside the category');
		$I = new ProductSteps($scenario);
		$I->createProductSave($this->randomProductName, $this->randomCategoryName, $this->randomProductNumber, $this->randomProductPrice, $this->minimumPerProduct, $this->minimumQuantity, $this->maximumQuantity, $this->productStart, $this->productEnd);

		$I->wantTo('setup up one page checkout at admin');
		$I = new ConfigurationSteps($scenario);
		$I->cartSetting($this->cartSetting);

		$I->wantTo('Add products in cart');
		$I = new OrderSteps($scenario);
		$I->addProductToCartWithBankTransfer($this->randomProductName, $this->randomCategoryName, $this->randomProductPrice, $this->userName, $this->password );

		$I = new ConfigurationSteps($scenario);
		$I->wantTo('Check Order');
		$I->checkPriceTotal($this->randomProductPrice, $this->searchOrder, $this->firstName, $this->lastName, $this->randomProductName, $this->randomCategoryName, $this->paymentMethod);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 */
	public function clearAllData(AcceptanceTester $I, $scenario)
	{
		$I->doAdministratorLogin();

		$I->wantTo('Disable one page checkout');
		$this->cartSetting["onePage"] = 'no';
		$I = new ConfigurationSteps($scenario);
		$I->cartSetting($this->cartSetting);

		$I->wantTo('Deletion Product in Administrator');
		$I = new ProductSteps($scenario);
		$I->deleteProduct($this->randomProductName);

		$I->wantTo('Deletion Category in Administrator');
		$I = new CategorySteps($scenario);
		$I->deleteCategory($this->randomCategoryName);

		$I->wantTo('Deletion of User in Administrator');
		$I = new UserSteps($scenario);
		$I->deleteUser($this->userNameDelete, 'true');

		$I->wantTo('Deletion of Order Total Discount in Administrator');
		$I = new OrderSteps($scenario);
		$I->deleteOrder( $this->searchOrder);
	}
}
