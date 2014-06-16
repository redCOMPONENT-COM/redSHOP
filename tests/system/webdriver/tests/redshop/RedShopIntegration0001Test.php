<?php
/**
 * @package     RedShop
 * @subpackage  Test
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

require_once 'JoomlaWebdriverTestCase.php';

/**
 * This class tests the  Checkout Process Products Add/Edit.
 *
 * @package     RedShop.Test
 * @subpackage  Webdriver
 * @since       1.2
 */
class RedShopIntegration0001Test extends JoomlaWebdriverTestCase
{
	/**
	 * The menu name being tested.
	 *
	 * @var     string
	 * @since   3.0
	 */
	protected $appMenuLinkName = 'RedShopFrontEndManagerPage';

	/**
	 * The menu group name being tested.
	 *
	 * @var     string
	 * @since   3.0
	 */
	protected $appMenuGroupName = 'RedSHOP_Product';

	/**
	 * Function to login to the Application
	 *
	 * @return void
	 */
	public function setUp()
	{
		parent::setUp();
		$cpPage = $this->doAdminLogin();
	}

	/**
	 * Logout and close test.
	 *
	 * @return void
	 *
	 * @since   3.0
	 */
	public function tearDown()
	{
		$this->doAdminLogout();
		parent::tearDown();
	}

	/**
	 * Function to Test Product Checkout with a New Customer
	 *
	 * @test
	 *
	 * @return void
	 */
	public function newCustomerProductCheckout()
	{
		$rand = rand();
		$elementObject = $this->driver;
		$config = new SeleniumConfig;
		$name = $rand . 'RedShop Product';
		$number = $rand;
		$price = 10;
		$category = 'redCOMPONENT';
		$productsURL = 'administrator/index.php?option=com_redshop&view=product';
		$frontEndURL = 'index.php?option=com_redshop';
		$customerInfo = array('firstname' => 'Raj', 'lastname' => 'Raj', 'address' => '123 Testing', 'email' => 'testing' . $rand . '@test.com', 'postalcode' => '1234', 'city' => 'Holy', 'state' => 'Alabama', 'phone' => '982763534');
		$elementObject->get($config->host . $config->path . $productsURL);
		$this->redShopProductsManagerPage = $this->getPageObject("RedShopProductsManagerPage");
		$this->redShopProductsManagerPage->addProduct($name, $number, $price, $category);
		$this->assertTrue($this->redShopProductsManagerPage->searchProduct($name), 'Product Must be Present');
		$elementObject->get($config->host . $config->path . $frontEndURL);
		$this->redShopFrontEndManagerPage = $this->getPageObject("RedShopFrontEndManagerPage");
		$this->assertTrue($this->redShopFrontEndManagerPage->checkOutProduct($name, $category, $customerInfo), 'Product Must be Succesfully Checked Out');
		$elementObject->get($config->host . $config->path . $productsURL);
		$this->redShopProductsManagerPage = $this->getPageObject("RedShopProductsManagerPage");
		$this->redShopProductsManagerPage->deleteProduct($name);
		$this->assertFalse($this->redShopProductsManagerPage->searchProduct($name, 'Delete'), 'Product Must be Deleted');
	}
}
