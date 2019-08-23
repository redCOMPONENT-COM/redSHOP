<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\CategoryManagerJoomla3Steps as CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\TaxGroupSteps;
use AcceptanceTester\TaxRateSteps;
use AcceptanceTester\UserManagerJoomla3Steps as UserManagerJoomla3Steps;
use Configuration\ConfigurationSteps;
use Faker\Factory;

/**
 * Class CheckVATChangedDependingOnTheUserCest
 * @since 2.1.3
 */
class CheckVATChangedDependingOnTheUserCest
{
	public function __construct()
	{
		#groupVAT
		$this->faker = Faker\Factory::create();
		$this->taxGroupName             = $this->faker->bothify('TaxGroupsName ?###?');

		#configuration
		$this->country                  = 'Denmark';
		$this->vatDefault               = $this->taxGroupName;
		$this->vatCalculation           = 'Customer';
		$this->vatAfter                 = 'after';
		$this->vatNumber                = 0;
		$this->calculationBase          = 'billing';
		$this->requiVAT                 = 'no';

		#VAT for User in Denmark
		$this->faker = Faker\Factory::create();
		$this->taxRateNameDenmark       = $this->faker->bothify('VAT Denmark ?###?');
		$this->taxRateValueDenmark      = 0.1;
		$this->countryDenmark           = 'Denmark';
		$this->subtotalDenmark          = "DKK 100,00";
		$this->vatPriceDenmark          = "DKK 10,00";
		$this->totalDenmark             = "DKK 110,00";

		#VAT for User in VN
		$this->taxRateNameVN            = $this->faker->bothify('VAT VN ?###?');
		$this->taxRateValueVN           = 0.2;
		$this->countryVietNam           = 'Viet Nam';
		$this->subtotalVN               = "DKK 100,00";
		$this->vatPriceVN               = "DKK 20,00";
		$this->totalVN                  = "DKK 120,00";

		#Categories
		$this->categoryName             = $this->faker->bothify('CategoryNameVAT ?###?');

		#Products
		$this->productName              = $this->faker->bothify('NameProductVAT ?###?');
		$this->randomProductNumber      = $this->faker->numberBetween(999, 9999);
		$this->randomProductPrice       = 100;

		#User in VN
		$this->userNameVN               = $this->faker->bothify('User In VN ?####?');
		$this->passwordVN               = $this->faker->bothify('Password VN ?##?');
		$this->emailVN                    = $this->faker->email;
		$this->shopperGroup             = 'Default Private';
		$this->group                    = 'Registered';
		$this->firstName                = $this->faker->bothify('ManageUserAdministratorCest VN ?##?');
		$this->updateFirstName          = 'Updating ' . $this->firstName;
		$this->lastName                 = $this->faker->bothify('LastName ?####?');
		$this->address                  = '14 Phan Ton';
		$this->zipcode                  = 2000;
		$this->city                     = 'Ho Chi Minh';
		$this->countryVN                = 'Viet Nam';
		$this->phone                    = 010101010;

		#User in Denmark
		$this->userNameDenmark          = $this->faker->bothify('User In DM ?####?');
		$this->passwordDenmark          = $this->faker->bothify('Password DM ?##?');
		$this->emailDM                  = $this->faker->email;

		#configuration enable one page checkout
		$this->addcart                  = 'product';
		$this->allowPreOrder            = 'yes';
		$this->cartTimeOut              = $this->faker->numberBetween(100, 10000);
		$this->enabldAjax               = 'no';
		$this->defaultCart              = null;
		$this->buttonCartLead           = 'Back to current view';
		$this->onePage                  = 'yes';
		$this->showShippingCart         = 'no';
		$this->attributeImage           = 'no';
		$this->quantityChange           = 'no';
		$this->quantityInCart           = 0;
		$this->minimunOrder             = 0;
		$this->enableQuation            = 'no';
		$this->onePageNo                = 'no';
		$this->onePageYes               = 'yes';
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
	public function ChecckVATChangedDependingOnTheUserCest(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('VAT Groups - Save creation in Administrator');
		$I = new TaxGroupSteps($scenario);
		$I->addVATGroupsSave($this->taxGroupName);
		$I = new TaxRateSteps($scenario);
		$I->addTAXRatesSave($this->taxRateNameVN, $this->taxGroupName, $this->taxRateValueVN, $this->countryVietNam, null);
		$I->addTAXRatesSave($this->taxRateNameDenmark, $this->taxGroupName, $this->taxRateValueDenmark, $this->countryDenmark, null);

		$I->wantTo('setup VAT at admin');
		$I = new Configuration\ConfigurationSteps($scenario);
		$I->setupVAT($this->country, null, $this->vatDefault, $this->vatCalculation, $this->vatAfter, $this->vatNumber, $this->calculationBase, $this->requiVAT);
		$I->cartSetting($this->addcart, $this->allowPreOrder, $this->enableQuation, $this->cartTimeOut, $this->enabldAjax, $this->defaultCart, $this->buttonCartLead, $this->onePageYes, $this->showShippingCart, $this->attributeImage, $this->quantityChange, $this->quantityInCart, $this->minimunOrder);


		$I->wantTo('Create user for checkout');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->addUserHaveCountry($this->userNameDenmark, $this->passwordDenmark, $this->emailDM, $this->group, $this->shopperGroup, $this->firstName, $this->lastName, $this->countryDenmark);
		$I->addUserHaveCountry($this->userNameVN, $this->passwordVN, $this->emailVN, $this->group, $this->shopperGroup, $this->firstName, $this->lastName, $this->countryVietNam);

		$I->wantTo('Create new category ');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->categoryName);

		$I->wantTo('Create new product');
		$I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductSaveClose($this->productName, $this->categoryName, $this->randomProductNumber, $this->randomProductPrice);

		$I = new CheckoutOnFrontEnd($scenario);
		$I->testProductWithVatCheckout($this->userNameDenmark, $this->passwordDenmark, $this->productName, $this->categoryName, $this->subtotalDenmark, $this->vatPriceDenmark, $this->totalDenmark);
		$I->doFrontendLogout();
		$I = new CheckoutOnFrontEnd($scenario);
		$I->testProductWithVatCheckout($this->userNameVN, $this->passwordVN, $this->productName, $this->categoryName, $this->subtotalVN, $this->vatPriceVN, $this->totalVN);

		$I->wantTo('VAT Groups - Save creation in Administrator');
		$I = new TaxGroupSteps($scenario);
		$I->deleteVATGroupOK($this->taxGroupName);

		$I = new TaxRateSteps($scenario);
		$I->deleteTAXRatesOK($this->taxRateNameVN);
		$I->deleteTAXRatesOK($this->taxRateNameDenmark);

		$I->wantTo('Create user for checkout');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->userNameDenmark);
		$I->deleteUser($this->userNameVN);

		$I->wantTo('Create new category ');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->categoryName);

		$I->wantTo('Create new product');
		$I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->productName);
	}
}