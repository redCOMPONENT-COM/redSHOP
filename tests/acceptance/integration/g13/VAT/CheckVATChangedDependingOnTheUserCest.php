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
	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $taxGroupName;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $country;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $vatDefault;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $vatCalculation;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $vatAfter;

	/**
	 * @var int
	 * @since 2.1.3
	 */
	protected $vatNumber;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $calculationBase;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $requiVAT;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $taxRateNameDenmark;

	/**
	 * @var float
	 * @since 2.1.3
	 */
	protected $taxRateValueDenmark;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $countryDenmark;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $subtotalDenmark;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $vatPriceDenmark;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $totalDenmark;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $taxRateNameVN;

	/**
	 * @var float
	 * @since 2.1.3
	 */
	protected $taxRateValueVN;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $countryVietNam;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $subtotalVN;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $vatPriceVN;

	/**
	 * @var string
	 */
	protected $totalVN;

	/**
	 * @var string
	 */
	protected $categoryName;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $productName;

	/**
	 * @var int
	 * @since 2.1.3
	 */
	protected $randomProductNumber;

	/**
	 * @var int
	 * @since 2.1.3
	 */
	protected $randomProductPrice;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $userNameVN;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $passwordVN;
	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $emailVN;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $shopperGroup;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $group;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $firstNameVN;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $updateFirstName;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $lastName;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $address;

	/**
	 * @var int
	 * @since 2.1.3
	 */
	protected $zipcode;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $city;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $countryVN;

	/**
	 * @var int
	 * @since 2.1.3
	 */
	protected $phone;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $userNameDenmark;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $passwordDenmark;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $emailDM;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $firstNameDM;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $addcart;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $allowPreOrder;

	/**
	 * @var int
	 * @since 2.1.3
	 */
	protected $cartTimeOut;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $enabldAjax;

	/**
	 * @var null
	 * @since 2.1.3
	 */
	protected $defaultCart;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $buttonCartLead;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $onePage;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $showShippingCart;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $attributeImage;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $quantityChange;

	/**
	 * @var int
	 * @since 2.1.3
	 */
	protected $quantityInCart;

	/**
	 * @var int
	 * @since 2.1.3
	 */
	protected $minimunOrder;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $enableQuation;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $onePageNo;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $onePageYes;

	/**
	 * CheckVATChangedDependingOnTheUserCest constructor.
	 * @since 2.1.3
	 */
	public function __construct()
	{
		//groupVAT
		$this->faker = Faker\Factory::create();
		$this->taxGroupName             = $this->faker->bothify('TaxGroupsName ?###?');

		//configuration
		$this->country                  = 'Denmark';
		$this->vatDefault               = $this->taxGroupName;
		$this->vatCalculation           = 'Customer';
		$this->vatAfter                 = 'after';
		$this->vatNumber                = 0;
		$this->calculationBase          = 'billing';
		$this->requiVAT                 = 'no';

		//VAT for User in Denmark
		$this->faker = Faker\Factory::create();
		$this->taxRateNameDenmark       = $this->faker->bothify('VAT Denmark ?###?');
		$this->taxRateValueDenmark      = 0.1;
		$this->countryDenmark           = 'Denmark';
		$this->subtotalDenmark          = "DKK 100,00";
		$this->vatPriceDenmark          = "DKK 10,00";
		$this->totalDenmark             = "DKK 110,00";

		//VAT for User in VN
		$this->taxRateNameVN            = $this->faker->bothify('VAT VN ?###?');
		$this->taxRateValueVN           = 0.2;
		$this->countryVietNam           = 'Viet Nam';
		$this->subtotalVN               = "DKK 100,00";
		$this->vatPriceVN               = "DKK 20,00";
		$this->totalVN                  = "DKK 120,00";

		//Categories
		$this->categoryName             = $this->faker->bothify('CategoryNameVAT ?###?');

		//Products
		$this->productName              = $this->faker->bothify('NameProductVAT ?###?');
		$this->randomProductNumber      = $this->faker->numberBetween(999, 9999);
		$this->randomProductPrice       = 100;

		//User in VN
		$this->userNameVN               = $this->faker->bothify('User In VN ?####?');
		$this->passwordVN               = $this->faker->bothify('Password VN ?##?');
		$this->emailVN                  = $this->faker->email;
		$this->shopperGroup             = 'Default Private';
		$this->group                    = 'Registered';
		$this->firstNameVN              = $this->faker->bothify('User In VN ?##?');
		$this->updateFirstName          = 'Updating ' . $this->firstName;
		$this->lastName                 = $this->faker->bothify('LastName ?####?');
		$this->address                  = '14 Phan Ton';
		$this->zipcode                  = 2000;
		$this->city                     = 'Ho Chi Minh';
		$this->countryVN                = 'Viet Nam';
		$this->phone                    = 010101010;

		//User in Denmark

		$this->userNameDenmark          = $this->faker->bothify('User In DM ?####?');
		$this->passwordDenmark          = $this->faker->bothify('Password DM ?##?');
		$this->emailDM                  = $this->faker->email;
		$this->firstNameDM              = $this->faker->bothify('User In DM ?##?');

		//configuration enable one page checkout
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
		$I->wantTo('I Want to add Tax Rates');
		$I = new TaxRateSteps($scenario);
		$I->addTAXRatesSave($this->taxRateNameVN, $this->taxGroupName, $this->taxRateValueVN, $this->countryVietNam, null);
		$I->addTAXRatesSave($this->taxRateNameDenmark, $this->taxGroupName, $this->taxRateValueDenmark, $this->countryDenmark, null);

		$I->wantTo('setup VAT at admin');
		$I = new ConfigurationSteps($scenario);
		$I->setupVAT($this->country, null, $this->vatDefault, $this->vatCalculation, $this->vatAfter, $this->vatNumber, $this->calculationBase, $this->requiVAT);
		$I->cartSetting($this->addcart, $this->allowPreOrder, $this->enableQuation, $this->cartTimeOut, $this->enabldAjax, $this->defaultCart, $this->buttonCartLead, $this->onePageYes, $this->showShippingCart, $this->attributeImage, $this->quantityChange, $this->quantityInCart, $this->minimunOrder);

		$I->wantTo('Create user have country');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->addUserHaveCountry($this->userNameDenmark, $this->passwordDenmark, $this->emailDM, $this->group, $this->shopperGroup, $this->firstNameDM, $this->lastName, $this->address, $this->city, $this->zipcode, $this->phone, $this->countryDenmark);
		$I->addUserHaveCountry($this->userNameVN, $this->passwordVN, $this->emailVN, $this->group, $this->shopperGroup, $this->firstNameVN, $this->lastName, $this->address, $this->city, $this->zipcode, $this->phone, $this->countryVietNam);

		$I->wantTo('Create new category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->categoryName);

		$I->wantTo('Create new product');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductSaveClose($this->productName, $this->categoryName, $this->randomProductNumber, $this->randomProductPrice);

		$I->wantTo('I Want check VAT');
		$I = new CheckoutOnFrontEnd($scenario);
		$I->testProductWithVatCheckout($this->userNameDenmark, $this->passwordDenmark, $this->productName, $this->categoryName, $this->subtotalDenmark, $this->vatPriceDenmark, $this->totalDenmark);
		$I->doFrontendLogout();
		$I = new CheckoutOnFrontEnd($scenario);
		$I->testProductWithVatCheckout($this->userNameVN, $this->passwordVN, $this->productName, $this->categoryName, $this->subtotalVN, $this->vatPriceVN, $this->totalVN);

		$I->wantTo('I Want to delete Tax Rates');
		$I = new TaxRateSteps($scenario);
		$I->deleteTAXRatesOK($this->taxRateNameVN);
		$I->deleteTAXRatesOK($this->taxRateNameDenmark);

		$I->wantTo('Detele VAT Group');
		$I = new TaxGroupSteps($scenario);
		$I->deleteVATGroupOK($this->taxGroupName);

		$I->wantTo('Delete User');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->firstNameDM);
		$I->deleteUser($this->firstNameVN);

		$I->wantTo('Delete product');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->productName);

		$I->wantTo('Delete category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->categoryName);
	}
}