<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\TaxRateSteps;
use AcceptanceTester\TaxGroupSteps;
use AcceptanceTester\CategoryManagerJoomla3Steps as CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductCheckoutManagerJoomla3Steps as ProductCheckoutManagerJoomla3Steps;
use AcceptanceTester\ConfigurationSteps as ConfigurationSteps;
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
		$this->faker                = Faker\Factory::create();
		$this->taxRateName          = $this->faker->bothify('Testing Tax Rates Groups ?###?');
		$this->taxRateNameEdit      = $this->taxRateName . 'Edit';
		$this->taxGroupName         = $this->faker->bothify('TaxGroupsName ?###?');
		$this->taxRateValue         = 0.1;
		$this->countryName          = 'Denmark';
		$this->taxRateValueNegative = -1;
		$this->taxRateValueString   = 'Test';
		$this->productName          = $this->faker->bothify('NameProductVAT ?###?');
		$this->categoryName         = $this->faker->bothify('CategoryNameVAT ?###?');
		$this->randomProductNumber  = $this->faker->bothify('productNumber ?###?');
		$this->randomProductPrice   = 100;

		// Vat setting
		$this->vatCalculation  = 'Webshop';
		$this->vatAfter        = 'after';
		$this->vatNumber       = 0;
		$this->calculationBase = 'billing';
		$this->requiVAT        = 'no';

		$this->subtotal = "DKK 100,00";
		$this->vatPrice = "DKK 10,00";
		$this->total    = "DKK 110,00";

		//create user for quotation
		$this->faker           = Faker\Factory::create();
		$this->userName        = $this->faker->bothify('ManageUserAdministratorCest ?####?');
		$this->password        = $this->faker->bothify('Password ?##?');
		$this->email           = $this->faker->email;
		$this->shopperGroup    = 'Default Private';
		$this->group           = 'Registered';
		$this->firstName       = $this->faker->bothify('ManageUserAdministratorCest FN ?##?');
		$this->updateFirstName = 'Updating ' . $this->firstName;
		$this->lastName        = $this->faker->bothify('LastName ?####?');
		$this->address         = '14 Phan Ton';
		$this->zipcode         = 7000;
		$this->city            = 'Ho Chi Minh';
		$this->phone           = 010101010;

	}

	/**
	 * Method delete data at database
	 *
	 * @return  void
	 */
	public function deleteData()
	{
		(new RedshopSteps)->clearAllData();
	}

	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * Create VAT Group with
	 *
	 * @param   AcceptanceTester      $client   Current user state.
	 * @param   \Codeception\Scenario $scenario Scenario for test.
	 *
	 * @return  void
	 * @throws  Exception
	 */
	public function createVATGroupSave(AcceptanceTester $client, $scenario)
	{
	    $client->wantTo('Enable PayPal');
	    $client->enablePlugin('PayPal');

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

		$client->wantTo('Create user for checkout');
		$client = new UserManagerJoomla3Steps($scenario);
		$client->addUser(
			$this->userName, $this->password, $this->email, $this->group, $this->shopperGroup, $this->firstName, $this->lastName
		);

		$client = new ProductCheckoutManagerJoomla3Steps($scenario);
		$client->testProductWithVatCheckout(
			$this->userName, $this->password, $this->productName, $this->categoryName, $this->subtotal, $this->vatPrice, $this->total
		);
	}

	/**
	 * Method clear all data
	 *
	 * @param   AcceptanceTester      $tester   Tester
	 * @param   \Codeception\Scenario $scenario Scenario
	 *
	 * @return  void
	 * @throws  Exception
	 */
	public function clearUp(AcceptanceTester $tester, $scenario)
	{
		$tester->wantTo('Delete product');
		(new \AcceptanceTester\ProductManagerJoomla3Steps($scenario))->deleteProduct($this->productName);

		$tester->wantTo('Delete Category');
		(new CategoryManagerJoomla3Steps($scenario))->deleteCategory($this->categoryName);

		$tester->wantTo('Delete tax value');
		(new TaxRateSteps($scenario))->deleteTAXRatesOK($this->taxRateName);

		$tester->wantTo('Delete tax group');
		(new TaxGroupSteps($scenario))->deleteVATGroupOK($this->taxGroupName);

		$tester->wantTo('Delete user');
		(new UserManagerJoomla3Steps($scenario))->deleteUser($this->firstName);

		$tester->wantTo('Test Order delete by user  in Administrator');
		(new OrderManagerJoomla3Steps($scenario))->deleteOrder($this->firstName);
	}
}
