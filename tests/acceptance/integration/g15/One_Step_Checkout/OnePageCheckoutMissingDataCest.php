<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use Configuration\ConfigurationSteps as ConfigurationSteps;

/**
 * Class OnePageCheckoutMissingDataCest
 * @since 2.1.2
 */
class OnePageCheckoutMissingDataCest
{
	public function __construct()
	{
		$this->faker                  = Faker\Factory::create();
		$this->ProductName            =  $this->faker->bothify('ProductName ?####?');
		$this->CategoryName           =  $this->faker->bothify('CategoryName ?####?');
		$this->ManufactureName        = $this->faker->bothify('ManufactureName ?#####');
		$this->MassDiscountAmoutTotal = 90;
		$this->MassDiscountPercent    = 0.3;
		$this->minimumPerProduct      = 1;
		$this->minimumQuantity        = 1;
		$this->maximumQuantity        = $this->faker->numberBetween(100, 1000);
		$this->discountStart          = "12-12-2016";
		$this->discountEnd            = "23-05-2017";
		$this->randomProductNumber    = $this->faker->numberBetween(999, 9999);
		$this->randomProductPrice     = 100;

		$this->business               = "business";
		$this->private                = "private";
		$this->createAccount          = "createAccount";

		$this->customerInformation    = array(
			"email"      => "test@test" . rand() . ".com",
			"firstName"  => $this->faker->bothify('firstNameCustomer ?####?'),
			"lastName"   => $this->faker->bothify('lastNameCustomer ?####?'),
			"address"    => "Some Place in the World",
			"postalCode" => "5000",
			"city"       => "Odense SØ",
			"country"    => "Denmark",
			"state"      => "Blangstedgaardsvej 1",
			"phone"      => "8787878787"
		);

		$this->customerBussinesInformation = array(
			"email"          => "test@test" . rand() . ".com",
			"companyName"    => "CompanyName",
			"businessNumber" => 1231312,
			"firstName"      => $this->faker->bothify('firstName ?####?'),
			"lastName"       => $this->faker->bothify('lastName ?####?'),
			"address"        => "Some Place in the World",
			"postalCode"     => "5000",
			"city"           => "Odense SØ",
			"country"        => "Denmark",
			"state"          => "Blangstedgaardsvej 1",
			"phone"          => "8787878787",
			"eanNumber"      => 1212331331231,
		);

		//configuration enable one page checkout
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

		$this->buttonCartLeadEdit = 'Back to current view';
		$this->shippingWithVat    = "DKK 0,00";

		$this->shippingMethod     = 'redSHOP - Standard Shipping';
		$this->shipping           = array(
			'shippingName' => $this->faker->bothify('TestingShippingRate ?##?'),
			'shippingRate' => 10
		);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @since 2.1.2
	 * @throws Exception
	 */
	public function onePageCheckoutMissing(AcceptanceTester $I, $scenario)
	{
		$I->doAdministratorLogin();
		$I->enablePlugin('PayPal');

		$I->wantTo('setup up one page checkout at admin');
		$I = new ConfigurationSteps($scenario);
		$I->cartSetting($this->cartSetting);

		$I->wantTo('Create Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->CategoryName);

		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductSaveClose($this->ProductName, $this->CategoryName, $this->randomProductNumber, $this->randomProductPrice);

		$I = new CheckoutMissingData($scenario);
		$I->addToCart($this->CategoryName, $this->ProductName );
		$I->wantToTest('Check out with missing user');
		$I->onePageCheckoutMissingWithUserPrivate($this->ProductName, $this->customerInformation, 'user');
		$I->onePageCheckoutMissingWithUserBusiness($this->ProductName, $this->customerBussinesInformation, 'user');

		$I->wantToTest('Check out with missing click accept Terms');
		$I->onePageCheckoutMissingWithUserPrivate($this->ProductName, $this->customerInformation, 'acceptTerms');
		$I->onePageCheckoutMissingWithUserBusiness($this->ProductName, $this->customerBussinesInformation, 'acceptTerms');

		$I->wantToTest('Check out with missing click payment');
		$I->onePageCheckoutMissingWithUserPrivate($this->ProductName, $this->customerInformation, 'payment');
		$I->onePageCheckoutMissingWithUserBusiness($this->ProductName, $this->customerBussinesInformation, 'payment');

		$I->wantToTest('Check out with wrong address email');
		$this->customerInformation['email'] = "test";
		$I->onePageCheckoutMissingWithUserPrivate($this->ProductName, $this->customerInformation, 'wrongEmail');
		$this->customerBussinesInformation['email'] = "test";
		$I->onePageCheckoutMissingWithUserBusiness($this->ProductName, $this->customerBussinesInformation, 'wrongEmail');
		$this->customerInformation['email'] =  "test@test" . rand() . ".com";
		$this->customerBussinesInformation['email'] = "test@test" . rand() . ".com";

		$I->wantToTest('Check out with wrong phone number');
		$this->customerInformation['phone'] = "test";
		$I->onePageCheckoutMissingWithUserPrivate( $this->ProductName, $this->customerInformation, 'wrongPhone');
		$this->customerBussinesInformation['phone'] = "test";
		$I->onePageCheckoutMissingWithUserBusiness( $this->ProductName, $this->customerBussinesInformation, 'wrongPhone');
		$this->customerBussinesInformation['phone'] = "8787878787";
		$this->customerInformation['phone'] = "8787878787";

		$I->wantToTest('Check out with wrong EAN Number');
		$this->customerBussinesInformation['eanNumber'] = "test";
		$I->onePageCheckoutMissingWithUserBusiness( $this->ProductName, $this->customerBussinesInformation, 'wrongEAN');
	}

	/**
	 * @param ProductManagerJoomla3Steps $I
	 * @param $scenario
	 * @since 2.1.2
	 * @throws Exception
	 */
	public function clearUpDatabase(ProductManagerJoomla3Steps $I, $scenario)
	{
		$I->doAdministratorLogin();
		$I->wantTo('Disable one page checkout');
		$this->cartSetting["onePage"] = 'no';
		$I = new ConfigurationSteps($scenario);
		$I->cartSetting($this->cartSetting);

		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('Delete Product  in Administrator');
		$I->deleteProduct($this->ProductName);

		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->wantTo('Delete Category in Administrator');
		$I->deleteCategory($this->CategoryName);
	}
}