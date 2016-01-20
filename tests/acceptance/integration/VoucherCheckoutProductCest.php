<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
use \AcceptanceTester;
/**
 * Class VoucherCheckoutProductCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class VoucherCheckoutProductCest
{
	public function __construct()
	{
		$this->faker = Faker\Factory::create();
		$this->randomVoucherCode = $this->faker->bothify('VoucherCheckoutProductCest ?##?');
		$this->voucherAmount = 10;
		$this->voucherCount = $this->faker->numberBetween(99, 999);
	}

	/**
	 * Test to Verify the Voucher Checkout
	 *
	 * @param   AcceptanceTester  $I         Actor Class Object
	 * @param   String            $scenario  Scenario Variable
	 *
	 * @return void
	 */
	public function testProductsVoucherFrontEnd(AcceptanceTester $I, $scenario)
	{
		$I = new AcceptanceTester($scenario);

		$I->wantTo('Test Product Checkout on Front End with 2 Checkout Payment Plugin');
		$I->doAdministratorLogin();

		$I = new AcceptanceTester\ProductCheckoutManagerJoomla3Steps($scenario);
		$I->doAdministratorLogout();
		$customerInformation = array(
			"email" => "test@test" . rand() . ".com",
			"firstName" => "Tester",
			"lastName" => "User",
			"address" => "Some Place in the World",
			"postalCode" => "23456",
			"city" => "Bangalore",
			"country" => "India",
			"state" => "Karnataka",
			"phone" => "8787878787"
		);

		$randomNumber = rand(10, 1000);

		if (($randomNumber % 2) == 1)
		{
			$productRandomizer = rand(10, 1000);

			if (($productRandomizer % 2) == 1)
			{
				$productName = 'redSLIDER';
			}
			else
			{
				$productName = 'redCOOKIE';
			}

			$categoryName = 'Events and Forms';

		}
		else
		{
			$productRandomizer = rand(10, 1000);

			if (($productRandomizer % 2) == 1)
			{
				$productName = 'redSHOP';
			}
			else
			{
				$productName = 'redITEM';
			}

			$categoryName = 'CCK and e-Commerce';

		}

		$this->createVoucher($I, $scenario, $productName);
		$this->checkoutProductWithVoucherCode($I, $scenario, $customerInformation, $customerInformation, $productName, $categoryName, $this->randomVoucherCode);
		$this->deleteVoucher($I, $scenario);
	}

	/**
	 * Function to Test Checkout Process of a Product using the Voucher Code
	 *
	 * @param   AcceptanceTester  $I               Actor Class Object
	 * @param   String            $scenario        Scenario Variable
	 * @param   Array             $addressDetail   Address Detail
	 * @param   Array             $shipmentDetail  Shipping Address Detail
	 * @param   string            $productName     Name of the Product
	 * @param   string            $categoryName    Name of the Category
	 * @param   string            $voucherCode     Code for the Coupon
	 *
	 * @return void
	 */
	private function checkoutProductWithVoucherCode(AcceptanceTester $I, $scenario, $addressDetail, $shipmentDetail, $productName = 'redCOOKIE', $categoryName = 'Events and Forms', $voucherCode)
	{
		$I->amOnPage(\FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$categoryDiv,30);
		$I->checkForPhpNoticesOrWarnings();
		$productFrontEndManagerPage = new \FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$productList,30);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->click(\FrontEndProductManagerJoomla3Page::$addToCart);
		$I->waitForText("Product has been added to your cart.", 10, '.alert-message');
		$I->see("Product has been added to your cart.", '.alert-message');
		$I->amOnPage('index.php?option=com_redshop&view=cart');
		$I->checkForPhpNoticesOrWarnings();
		$I->seeElement(['link' => $productName]);
		$I->fillField(['id' => 'coupon_input'], $voucherCode);
		$I->click(['id' => 'coupon_button']);
		$I->waitForText("The discount code is valid", 10, '.alert-success');
		$I->see("The discount code is valid", '.alert-success');
		$temp = $I->grabTextFrom(['xpath' => "(//table[@class='cart_calculations']//tbody//tr//td[2])[1]"]);
		$amount = explode(',', $temp);
		$actual = explode(' ', $amount[0]);
		$subTotal = $actual[1];
		$temp = $I->grabTextFrom(['xpath' => "(//table[@class='cart_calculations']//tbody//tr//td[2])[2]"]);
		$amount = explode(',', $temp);
		$actual = explode(' ', $amount[0]);
		$discount = $actual[1];
		$temp = $I->grabTextFrom(['xpath' => "//span[@id='spnTotal']"]);
		$amount = explode(',', $temp);
		$actual = explode(' ', $amount[0]);
		$finalTotal = $actual[1];
		$I->verifyTotals($subTotal, $discount, $finalTotal);
	}

	/**
	 * Function to Test Voucher Creation in Backend
	 *
	 */
	private function createVoucher(AcceptanceTester $I, $scenario, $productName = 'redCOOKIE')
	{
		$I->wantTo('Test Voucher creation in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\VoucherManagerJoomla3Steps($scenario);
		$I->amOnPage(\VoucherManagerPage::$URL);
		$I->click("ID");
		$I->click('New');
		$I->waitForElement(\VoucherManagerPage::$voucherCode, 30);
		$I->fillField(\VoucherManagerPage::$voucherCode, $this->randomVoucherCode);
		$I->fillField(\VoucherManagerPage::$voucherAmount, $this->voucherAmount);
		$I->fillField(['id' => 's2id_autogen1'], $productName);
		$I->waitForElement(['xpath' => "//div[@class='select2-result-label']"], 30);
		$I->click(['xpath' => "//div[@class='select2-result-label']"]);
		$I->fillField(\VoucherManagerPage::$voucherLeft, $this->voucherCount);
		$I->click('Save & Close');
		$I->see("Voucher details saved", '.alert-success');
		$I->click("ID");
		$I->see($this->randomVoucherCode, \VoucherManagerPage::$voucherResultRow);
		$I->click("ID");
	}

	/**
	 * Function to Test Voucher Deletion
	 *
	 */
	private function deleteVoucher(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Deletion of Voucher in Administrator');
		$I = new AcceptanceTester\VoucherManagerJoomla3Steps($scenario);
		$I->deleteVoucher($this->randomVoucherCode);
	}

}
