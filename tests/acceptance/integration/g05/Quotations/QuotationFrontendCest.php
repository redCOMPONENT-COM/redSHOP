<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\CategoryManagerJoomla3Steps;
use Configuration\ConfigurationSteps;
use AcceptanceTester\ProductCheckoutManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\QuotationManagerJoomla3Steps;

class QuotationFrontendCest
{
	public function __construct()
	{
		$this->faker               = Faker\Factory::create();
		$this->ProductName         = 'ProductName' . rand(100, 999);
		$this->CategoryName        = "CategoryName" . rand(1, 100);
		$this->ManufactureName     = "ManufactureName" . rand(1, 10);
		$this->minimumPerProduct   = 1;
		$this->minimumQuantity     = 1;
		$this->maximumQuantity     = $this->faker->numberBetween(100, 1000);
		$this->discountStart       = "2016-12-12";
		$this->discountEnd         = "2017-05-23";
		$this->randomProductNumber = $this->faker->numberBetween(999, 9999);
		$this->randomProductPrice  = 100;

		$this->subtotal = "DKK 10,00";
		$this->Discount = "DKK 0,00";
		$this->Total    = "DKK 10,00";

		$this->cartSetting = array(
			"addCart"           => 'product',
			"allowPreOrder"     => 'yes',
			"cartTimeOut"       => $this->faker->numberBetween(100, 10000),
			"enabledAjax"       => 'no',
			"defaultCart"       => null,
			"buttonCartLead"    => 'Back to current view',
			"onePage"           => 'no',
			"showShippingCart"  => 'no',
			"attributeImage"    => 'no',
			"quantityChange"    => 'no',
			"quantityInCart"    => 0,
			"minimumOrder"      => 0,
			"enableQuotation"   => 'yes'
		);

		//user
		$this->userName     = $this->faker->bothify('UserNameCheckoutProductCest ?##?');
		$this->password     = 'test';
		$this->email        = $this->faker->email;
		$this->shopperGroup = 'Default Private';
		$this->group        = 'Administrator';
		$this->firstName    = $this->faker->bothify('ManageUserAdministratorCest FN ?##?');
		$this->lastName     = 'Last';

		$this->userInformation = array(
			"email"      => $this->email,
			"firstName"  => "Tester",
			"lastName"   => "User",
			"address"    => "Some Place in the World",
			"postalCode" => "23456",
			"city"       => "Bangalore",
			"phone"      => "8787878787"
		);

		$this->newQuantity  = 4;
		$this->statusChange = 'Accepted';
	}

	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}


	/**
	 * Step1 : create category
	 * Step2 : create product
	 * Step3 : Goes on configuration and enable Quotation
	 * Step4 : Goes on frontend and checkout quotation
	 * Step5 : Clicks on button add quotation without email
	 * Step6 : Accept alter and fill in valid value
	 *
	 *
	 * @param AcceptanceTester $I
	 * @param                  $scenario
	 */
	public function createQuotation(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Create Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->CategoryName);

		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductSave($this->ProductName, $this->CategoryName, $this->randomProductNumber, $this->randomProductPrice, $this->minimumPerProduct, $this->minimumQuantity, $this->maximumQuantity, $this->discountStart, $this->discountEnd);

		$I->wantTo(' Enable Quotation at configuration ');
		$I = new ConfigurationSteps($scenario);
		$I->cartSetting($this->cartSetting);

		$I->wantTo('Create Quotation at frontend ');
		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->checkoutQuotation($this->ProductName, $this->CategoryName, $this->email);
	}

	/**
	 * Function delete all data
	 *
	 * @param AcceptanceTester $I
	 * @param $scenario
	 */
	public function clearDatabase(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Edit quotation');
		$I = new QuotationManagerJoomla3Steps($scenario);
		$I->editQuotation($this->newQuantity);

		$I->wantTo('Change status of quotation');
		$I->editStatus($this->statusChange);

		$I->wantTo('Delete quotation');
		$I->deleteQuotation();

		$I->wantTo(' Disable Quotation at configuration ');
		$I = new ConfigurationSteps($scenario);
		$this->cartSetting["enableQuotation"] = 'no';
		$I->cartSetting($this->cartSetting);
	}
}