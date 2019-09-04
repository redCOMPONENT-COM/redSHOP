<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\TaxRateSteps;
use AcceptanceTester\TaxGroupSteps;
use AcceptanceTester\CategoryManagerJoomla3Steps as CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductCheckoutManagerJoomla3Steps as ProductCheckoutManagerJoomla3Steps;
use Configuration\ConfigurationSteps as ConfigurationSteps;
use AcceptanceTester\UserManagerJoomla3Steps as UserManagerJoomla3Steps;
use AcceptanceTester\OrderManagerJoomla3Steps as OrderManagerJoomla3Steps;

/**
 * Class ProductVatCheckoutCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class ProductVatCheckoutCest
{
	public function __construct()
	{
		$this->faker = Faker\Factory::create();
		$this->taxRateName = $this->faker->bothify('Testing Tax Rates Groups ?###?');
		$this->taxRateNameEdit = $this->taxRateName . 'Edit';
		$this->taxGroupName = $this->faker->bothify('TaxGroupsName ?###?');
		$this->taxRateValue = 0.1;
		$this->countryName = 'Denmark';
		$this->taxRateValueNegative = -1;
		$this->taxRateValueString = 'Test';
		$this->productName = $this->faker->bothify('NameProductVAT ?###?');
		$this->categoryName = $this->faker->bothify('CategoryNameVAT ?###?');
		$this->randomProductNumber = $this->faker->bothify('productNumber ?###?');
		$this->randomProductPrice = 100;

		// Vat setting
		$this->vatCalculation = 'Webshop';
		$this->vatAfter = 'after';
		$this->vatNumber = 0;
		$this->calculationBase = 'billing';
		$this->requiVAT = 'no';

		$this->subtotal = "DKK 100,00";
		$this->vatPrice = "DKK 10,00";
		$this->total = "DKK 110,00";

		//create user for quotation
		$this->faker = Faker\Factory::create();
		$this->userName = $this->faker->bothify('ManageUserAdministratorCest ?####?');
		$this->password = $this->faker->bothify('Password ?##?');
		$this->email = $this->faker->email;
		$this->shopperGroup = 'Default Private';
		$this->group = 'Registered';
		$this->firstName = $this->faker->bothify('ManageUserAdministratorCest FN ?##?');
		$this->updateFirstName = 'Updating ' . $this->firstName;
		$this->lastName = $this->faker->bothify('LastName ?####?');
		$this->address = '14 Phan Ton';
		$this->zipcode = 7000;
		$this->city = 'Ho Chi Minh';
		$this->phone = 010101010;

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
	}

	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * Create VAT Group with
	 *
	 * @param   AcceptanceTester $client Current user state.
	 * @param   \Codeception\Scenario $scenario Scenario for test.
	 *
	 * @return  void
	 * @throws  Exception
	 */
	public function createVATGroupSave(AcceptanceTester $client, $scenario)
	{

		$client->wantTo('VAT Groups - Save creation in Administrator');
		$client = new TaxGroupSteps($scenario);
		$client->addVATGroupsSave($this->taxGroupName);

		$client->wantTo('Test TAX Rates Save creation in Administrator');
		$client = new TaxRateSteps($scenario);
		$client->addTAXRatesSave($this->taxRateName, $this->taxGroupName, $this->taxRateValue, $this->countryName, null);

		$client->wantTo('Create new category ');
		$client = new CategoryManagerJoomla3Steps($scenario);
		$client->addCategorySave($this->categoryName);

		$client->wantTo('Create new product');
		$client = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
		$client->wantTo('I Want to add product inside the category');
		$client->createProductWithVATGroups(
			$this->productName, $this->categoryName, $this->randomProductNumber, $this->randomProductPrice, $this->taxGroupName
		);

		$client->wantTo('Configuration for apply VAT');
		$client = new ConfigurationSteps($scenario);
		$client->setupVAT(
			$this->countryName, null, $this->taxGroupName, $this->vatCalculation, $this->vatAfter, $this->vatNumber,
			$this->calculationBase, $this->requiVAT
		);
		$client->cartSetting($this->cartSetting);

		$client->wantTo('Create user for checkout');
		$client = new UserManagerJoomla3Steps($scenario);
		$client->addUser(
			$this->userName, $this->password, $this->email, $this->group, $this->shopperGroup, $this->firstName, $this->lastName, 'saveclose'
		);

		$client = new ProductCheckoutManagerJoomla3Steps($scenario);
		$client->testProductWithVatCheckout(
			$this->userName, $this->password, $this->productName, $this->categoryName, $this->subtotal, $this->vatPrice, $this->total
		);

		$client = new ConfigurationSteps($scenario);
		$client->cartSetting($this->cartSetting);
	}

	/**
	 * Method clear all data
	 *
	 * @param   AcceptanceTester $tester Tester
	 * @param   \Codeception\Scenario $scenario Scenario
	 *
	 * @return  void
	 * @throws  Exception
	 */
	public function clearUp(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Delete tax value');
		(new TaxRateSteps($scenario))->deleteTAXRatesOK($this->taxRateName);

		$client->wantTo('Delete tax group');
		(new TaxGroupSteps($scenario))->deleteVATGroupOK($this->taxGroupName);

		$client->wantTo('Delete user');
		(new UserManagerJoomla3Steps($scenario))->deleteUser($this->firstName);

		$client->wantTo('Test Order delete by user  in Administrator');
		(new OrderManagerJoomla3Steps($scenario))->deleteOrder($this->firstName);

		$I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
		$I->wantTo('Delete Product  in Administrator');
		$I->deleteProduct($this->productName);

		$I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
		$I->wantTo('Delete Category in Administrator');
		$I->deleteCategory($this->categoryName);
	}
}
