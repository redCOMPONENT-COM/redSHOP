<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\QuotationManagerJoomla3Steps;
use AcceptanceTester\ConfigurationManageJoomla3Steps;
use AcceptanceTester\ProductCheckoutManagerJoomla3Steps;
class QuotationFrontendCest
{
	public function __construct()
	{

		$this->faker = Faker\Factory::create();
		$this->ProductName = 'ProductName' . rand(100, 999);
		$this->CategoryName = "CategoryName" . rand(1, 100);
		$this->ManufactureName = "ManufactureName" . rand(1, 10);
		$this->minimumPerProduct = 1;
		$this->minimumQuantity = 1;
		$this->maximumQuantity = $this->faker->numberBetween(100, 1000);
		$this->discountStart = "12-12-2016";
		$this->discountEnd = "23-05-2017";
		$this->randomProductNumber = $this->faker->numberBetween(999, 9999);
		$this->randomProductPrice = 100;

		$this->subtotal="DKK 10,00";
		$this->Discount ="DKK 0,00";
		$this->Total="DKK 10,00";

		//setup Cart setting
		$this->addcart = 'product';
		$this->allowPreOrder = 'yes';
		$this->allowPreOrderNo='no';
		$this->cartTimeOut = $this->faker->numberBetween(100, 10000);
		$this->enabldAjax = 'no';
		$this->defaultCart = null;
		$this->buttonCartLead = 'Back to current view';
		$this->onePage = 'no';
		$this->showShippingCart = 'no';
		$this->attributeImage = 'no';
		$this->quantityChange = 'no';
		$this->quantityInCart = 0;
		$this->minimunOrder = 0;
		$this->enableQuation = 'yes';

		$this->enableQuotationOff='no';
		//user
		$this->userName = $this->faker->bothify('UserNameCheckoutProductCest ?##?');
		$this->password = 'test';
		$this->email = $this->faker->email;
		$this->shopperGroup = 'Default Private';
		$this->group = 'Administrator';
		$this->firstName = $this->faker->bothify('ManageUserAdministratorCest FN ?##?');
		$this->lastName = 'Last';
		$this->userInformation = array(
			"email" => $this->email,
			"firstName" => "Tester",
			"lastName" => "User",
			"address" => "Some Place in the World",
			"postalCode" => "23456",
			"city" => "Bangalore",
			"phone" => "8787878787"
		);

		$this->newQuantity=4;
	}

	public function deleteData($scenario)
	{
		$I= new RedshopSteps($scenario);
		$I->clearAllData();
	}

	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}


	/**
	 * first delete all database product, category
	 * Step1 : create category
	 * Step2 : create product
	 * Step3 : Goes on configuration and enable Quotation
	 * Step4 : Goes on frontend and checkout quotation
	 * Step5 : Clicks on button add quotation without email
	 * Step6 : Accept alter and fill in valid value
	 * Step7 : Delete product, category , quotation and convert cart checkout like default
	 *
	 * @param AcceptanceTester $I
	 * @param                  $scenario
	 */
	public function checkoutQuotation(AcceptanceTester $I, $scenario)
	{
		$I->wantTo(' Enable Quotation at configuration ');
		$I = new ConfigurationManageJoomla3Steps($scenario);
		$I->cartSetting($this->addcart, $this->allowPreOrder, $this->enableQuation, $this->cartTimeOut, $this->enabldAjax, $this->defaultCart, $this->buttonCartLead, $this->onePage,$this->showShippingCart,$this->attributeImage,$this->quantityChange,$this->quantityInCart,$this->minimunOrder);


		$I->wantTo('Create Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->CategoryName);

		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductSave($this->ProductName, $this->CategoryName, $this->randomProductNumber, $this->randomProductPrice, $this->minimumPerProduct, $this->minimumQuantity, $this->maximumQuantity, $this->discountStart, $this->discountEnd);


		$I->wantTo('Create Quotation at frontend ');
		$I= new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->checkoutQuotation($this->ProductName, $this->CategoryName,$this->email);
	}

	public function clearDatabase(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Edit quotation and delete q');
		$I = new QuotationManagerJoomla3Steps($scenario);
		$I->editQuotation($this->newQuantity);
		$I->deleteQuotation();


		$I->wantTo('Delete Product');
		$I= new ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->ProductName);

		$I->wantTo('Delete Category');
		$I= new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->CategoryName);

		$I->wantTo(' Enable Quotation at configuration ');
		$I = new ConfigurationManageJoomla3Steps($scenario);
		$I->cartSetting($this->addcart, $this->allowPreOrderNo, $this->enableQuotationOff, $this->cartTimeOut, $this->enabldAjax, $this->defaultCart, $this->buttonCartLead, $this->onePage,$this->showShippingCart,$this->attributeImage,$this->quantityChange,$this->quantityInCart,$this->minimunOrder);

	}
}