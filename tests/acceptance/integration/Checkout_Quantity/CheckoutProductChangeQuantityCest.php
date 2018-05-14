<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2018 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\ConfigurationSteps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\UserManagerJoomla3Steps;
use AcceptanceTester\ProductCheckoutManagerJoomla3Steps;
use AcceptanceTester\CheckoutProductQuantityChangeSteps;
/**
 * Class CheckoutChangeQuantityCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    2.1
 */
class CheckoutProductChangeQuantityCest
{
	/**
	 * @var string
	 */
	public $categoryName;
	/**
	 * @var \Faker\Generator
	 */
	public $faker;
	public function __construct()
	{
		$this->faker               = Faker\Factory::create();
		$this->productName         = $this->faker->bothify('Product Name ?##?');;
		$this->categoryName        = $this->faker->bothify('Category Name ?##?');
		$this->productPrice        = 50;
		$this->total               = "DKK 50,00";;
		$this->randomProductNumber = $this->faker->numberBetween(999, 9999);
		$this->randomProductPrice  = 100;
		//configuration enable one page checkout
		$this->addcart          = 'product';
		$this->allowPreOrder    = 'yes';
		$this->cartTimeOut      = $this->faker->numberBetween(100, 10000);
		$this->enabldAjax       = 'no';
		$this->defaultCart      = null;
		$this->buttonCartLead   = 'Back to current view';
		$this->onePage          = 'yes';
		$this->showShippingCart = 'yes';
		$this->attributeImage   = 'no';
		$this->quantityChange   = 'yes';
		$this->quantityInCart   = 2;
		$this->minimunOrder     = '';
		$this->enableQuation    = 'no';
		$this->onePageNo        = 'no';
		$this->onePageYes       = 'yes';
		$this->buttonCartLeadEdit = 'Back to current view';
		$this->shippingWithVat    = "DKK 0,00";
		//add new user
		$this->userName        = $this->faker->bothify('OnePageCest ?####?');
		$this->password        = $this->faker->bothify('Password ?##?');
		$this->email           = $this->faker->email;
		$this->shopperGroup    = 'Default Private';
		$this->group           = 'Registered';
		$this->firstName       = $this->faker->bothify('OnePageCest FN ?#####?');
		$this->updateFirstName = 'Updating ' . $this->firstName;
		$this->lastName        = 'Last';
		$this->address         = '14 Phan Ton';
		$this->zipcode         = 7000;
		$this->city            = 'Ho Chi Minh';
		$this->phone           = 010101010;
	}
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}
	/**
	 * Step1 : Enable Quantity change
	 * Step2 : Create category
	 * Step3 : Create product have price is 100
	 * Step4 : Goes on frontend
	 * Step5 : Click "Add to cart", change, checkout for product
	 *
	 * @param  AcceptanceTester $I
	 * @param  mixed            $scenario
	 *
	 * @return  void
	 */
	public function changeQuantityInCart(AcceptanceTester $I, $scenario)
	{
		$I-> wantTo('Enable Quantity Change in Cart');
		$I = new ConfigurationSteps($scenario);
		$I->cartSetting($this->addcart, $this->allowPreOrder, $this->enableQuation, $this->cartTimeOut, $this->enabldAjax, $this->defaultCart, $this->buttonCartLead,
			$this->onePageYes, $this->showShippingCart, $this->attributeImage, $this->quantityChange, $this->quantityInCart, $this->minimunOrder);

		$I->wantTo('Create Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->categoryName);

		$I->wantTo('I Want to add product inside the category');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->createProductSaveClose($this->productName, $this->categoryName, $this->randomProductNumber, $this->randomProductPrice);

		$I->wantTo('I want to login Site page with user just create');
		$I->doFrontEndLogin();

		$I-> wantTo('I want go to Product tab, Choose Product and Add to cart');
		$I = new CheckoutProductQuantityChangeSteps($scenario);
		$I->goOnFrontEnd($this->categoryName);
	}
}