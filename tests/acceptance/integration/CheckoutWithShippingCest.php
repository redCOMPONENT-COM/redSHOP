<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class ProductsCheckoutFrontEndCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */

use AcceptanceTester\ShippingSteps as ShippingSteps ;
use AcceptanceTester\CategoryManagerJoomla3Steps as CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps as ProductManagerJoomla3Steps;
use AcceptanceTester\ProductCheckoutManagerJoomla3Steps;
use AcceptanceTester\ShopperGroupManagerJoomla3Steps as ShopperGroupManagerJoomla3Steps;
use AcceptanceTester\UserManagerJoomla3Steps as UserManagerJoomla3Steps;
use AcceptanceTester\OrderManagerJoomla3Steps as OrderManagerJoomla3Steps;
use AcceptanceTester\ConfigurationManageJoomla3Steps as ConfigurationManageJoomla3Steps;
class ProductsCheckoutFrontEndCest
{
	/**
	 * Test to Verify the Payment Plugin
	 *
	 * @param   AcceptanceTester $I        Actor Class Object
	 * @param   String           $scenario Scenario Variable
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->faker = Faker\Factory::create();
		$this->categoryName                 = 'TestingCategory';
		$this->CategoryNamePlus 			= $this->categoryName;
		$this->ramdoCategoryNameAssign      = 'CategoryAssign' . rand(99, 999);
		$this->productName                  = 'Testing Products' . rand(99, 999);
		$this->minimumPerProduct            = 1;
		$this->minimumQuantity              = 1;
		$this->maximumQuantity              = 1;
		$this->discountStart                = "12-12-2016";
		$this->discountEnd                  = "23-05-2017";
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

		//setup Cart setting
		$this->addcart = 'product';
		$this->allowPreOrder = 'no';
		$this->cartTimeOut = $this->faker->numberBetween(100, 10000);
		$this->enabldAjax = 'no';
		$this->defaultCart = null;
		$this->buttonCartLead = 'Back to current view';
		$this->onePage = 'no';
		$this->showShippingCart = 'yes';
		$this->attributeImage = 'no';
		$this->quantityChange = 'no';
		$this->quantityInCart = 0;
		$this->minimunOrder = 0;
		$this->enableQuation = 'yes';

		//create shopper groups
		$this->shopperName = $this->faker->bothify(' Testing shopper ##??');
		$this->shopperType = null;
		$this->customerType = 'Company customer';
		$this->shippingRate = 10;
		$this->shippingCheckout = $this->faker->numberBetween(1, 100);
		$this->catalog = 'Yes';
		$this->showPrice = 'Yes';
		$this->shipping = 'yes';
		$this->enableQuotation='no';
		$this->showVat='no';
		$this->shopperGroupPortal='no';
		$this->CategoryName='';
		//create user
		$this->userName = $this->faker->bothify('UserName ?##?');
		$this->password = 'test';
		$this->email = $this->faker->email;
		$this->group = 'Manager';
		$this->firstName = $this->faker->bothify('FirstName FN ?##?');
		$this->lastName = 'Last';
		// update user for get billing code
		$this->user = array();
		$this->user['zipcode'] = 5000;
		$this->user['country'] = 'Denmark';

		//shipping info
		$this->shippingName = 'TestingShippingRate' . rand(99, 999);
		$this->shippingNameSecond = $this->faker->bothify('NameShiipingSecond ?##?');
		$this->shippingNameSaveClose = "TestingSave" . rand(1, 100);
		$this->shippingRate = rand(1, 100);
		$this->shippingRateEdit = rand(100, 1000);
		$this->weightStart = "";
		$this->weightEnd = "";
		$this->volumeStart = "";
		$this->volumeEnd = "";
		$this->shippingRateLenghtStart = "";
		$this->shippingRateLegnhtEnd = "";
		$this->shippingRateWidthStart = "";
		$this->shippingRateWidthEnd = "";
		$this->shippingRateHeightStart = "";
		$this->shippingRateHeightEnd = "";
		$this->orderTotalStart = "";
		$this->orderTotalEnd = "";
		$this->zipCodeStart = "";
		$this->zipCodeEnd = "";
		$this->country = "";
		$this->shippingRateProduct = "";
		$this->shippingPriority = "";
		$this->shippingRateFor = "";
		$this->shippingVATGroups = "";
		$this->pickup = "pick";
		
		//bill
		$this->Total = '';
		$this->TotalShow = '';
		$this->TotalIncludeShippingShow = '';

		//create other user for checkout do not apply shipping rate
		$this->userNameSecond = $this->faker->bothify('UserNamesecond ?##?');
		$this->passwordSecond = 'test';
		$this->emailSecond = $this->faker->email;
		$this->shopperGroupSecond = 'Default Private';
		$this->groupSecond = 'Administrator';
		$this->firstNameSecond = $this->faker->bothify('FirstNamesecond FN ?##?');
		$this->lastNameSecond = $this->faker->bothify('Lasenamesecond ?##?');
		$this->shippingRateSecond  =  rand(1, 100);
		$this->TotalIncludeShipping = 0;
		

	}



	public function deleteData($scenario)
	{
		$I= new RedshopSteps($scenario);
		$I->clearAllData();
		$I->clearAllShippingRate();
	}
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	public function createCategory(AcceptanceTester $I, $scenario)
	{
		$I->wantTo(' Enable Quotation at configuration ');
		$I = new ConfigurationManageJoomla3Steps($scenario);
		$I->cartSetting($this->addcart, $this->allowPreOrder, $this->enableQuation, $this->cartTimeOut, $this->enabldAjax, $this->defaultCart, $this->buttonCartLead, $this->onePage,$this->showShippingCart,$this->attributeImage,$this->quantityChange,$this->quantityInCart,$this->minimunOrder);


		$I->wantTo('Create Category in Administrator');

		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->wantTo('Create a Category');
		$I->addCategorySave($this->categoryName);
		//create new product to checkout
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductSave($this->productName, $this->categoryName, $this->randomProductNumber, $this->randomProductPrice, $this->minimumPerProduct, $this->minimumQuantity, $this->maximumQuantity, $this->discountStart, $this->discountEnd);
		$I = new ShopperGroupManagerJoomla3Steps($scenario);
		$this->CategoryName = '- '.$this->categoryName;
		$I->addShopperGroups($this->shopperName, $this->shopperType, $this->customerType,$this->shopperGroupPortal, $this->CategoryName,$this->shipping,$this->shippingRate, $this->shippingCheckout, $this->catalog,$this->showVat, $this->showPrice, $this->enableQuotation,'save');
		$I->wantTo('Test User creation with save button in Administrator');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperName, $this->firstName, $this->lastName, 'save');
		$I->wantTo('update zipcode and country ');
		$I->updateBillingInfo($this->firstName, $this->user);

		$I->wantTo('Test Discount creation with save and close button in Administrator');
		$I = new ShippingSteps($scenario);
		$I->wantTo('Create a shipping rate for first category');
		$I->createShippingRateStandard($this->shippingName, $this->shippingRate, $this->weightStart, $this->weightEnd, $this->volumeStart, 
			$this->volumeEnd, $this->shippingRateLenghtStart, $this->shippingRateLegnhtEnd, $this->shippingRateWidthStart, $this->shippingRateWidthEnd, 
			$this->shippingRateHeightStart, $this->shippingRateHeightEnd
			, $this->orderTotalStart, $this->orderTotalEnd, $this->zipCodeStart, $this->zipCodeEnd, $this->user['country'], $this->shippingRateProduct, $this->CategoryName,
			$this->shopperName, $this->shippingPriority, $this->shippingRateFor, $this->shippingVATGroups, 'save');
		$this->Total  = $this->randomProductPrice + $this->shippingRate;
		$this->TotalShow = 'DKK '.$this->Total;
		$I->wantTo('Test This user will apply shipping rate');
		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->checkoutSpecificShopperGroup($this->userName,$this->password,$this->productName ,$this->CategoryNamePlus,$this->shippingRate,$this->TotalShow);
	}

	public function checkoutWithUseApplyOtherShipping(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Add other shopper user and do not apply any shipping for this user');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->addUser($this->userNameSecond, $this->passwordSecond, $this->emailSecond, $this->groupSecond, $this->shopperGroupSecond, $this->firstNameSecond, $this->lastNameSecond, 'save');
		$I ->wantTo('Create other shipping for Private shopper groups');
		$I = new ShippingSteps($scenario);
		$I->createShippingRateStandard($this->shippingNameSecond, $this->shippingRateSecond, $this->weightStart, $this->weightEnd, $this->volumeStart, $this->volumeEnd, $this->shippingRateLenghtStart, $this->shippingRateLegnhtEnd, $this->shippingRateWidthStart, $this->shippingRateWidthEnd, $this->shippingRateHeightStart, $this->shippingRateHeightEnd
			, $this->orderTotalStart, $this->orderTotalEnd, $this->zipCodeStart, $this->zipCodeEnd, $this->user['country'], $this->shippingRateProduct, $this->CategoryName,
			$this->shopperGroupSecond, $this->shippingPriority, $this->shippingRateFor, $this->shippingVATGroups, 'save');
		$this->TotalIncludeShipping = $this->randomProductPrice + $this->shippingRateSecond;
		$this->TotalIncludeShippingShow = 'DKK '.$this->TotalIncludeShipping;
		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->wantTo('Test with user do not apply shipping rate');
		$I->checkoutSpecificShopperGroup($this->userNameSecond,$this->passwordSecond,$this->productName ,$this->CategoryNamePlus,$this->shippingRateSecond,$this->TotalIncludeShippingShow);
	}

	public function clearUp(AcceptanceTester $I, $scenario)
	{

		$I->wantTo('Delete product');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->productName);
		$I->wantTo('Delete Category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->CategoryNamePlus);
		$I->wantTo('Delete shipping');
		$I = new ShippingSteps($scenario, true);
		$I->deleteShippingRate($this->shippingName);
		$I->wantTo('Delete the first User');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->firstName, false);
		$I->wantTo('Delete the second User');
		$I->deleteUser($this->firstNameSecond, false);
	}

}

