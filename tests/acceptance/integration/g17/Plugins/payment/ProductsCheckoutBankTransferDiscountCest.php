<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\AdminManagerJoomla3Steps;
use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\OrderManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use Configuration\ConfigurationSteps;
use Frontend\payment\checkoutWithBankTransferDiscount;

/**
 * Class ProductsCheckoutBankTransferDiscountCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class ProductsCheckoutBankTransferDiscountCest
{

	/**
	 * @var \Faker\Generator
	 * @since 2.1.2
	 */
	public $faker;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public $categoryName;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public $productName;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public $productNumber;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public $productPrice;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public $minimumQuantity;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public $maximumQuantity;

	/**
	 * @var array
	 * @since 2.1.2
	 */
	protected $customerInformation;

	/**
	 * @var array
	 * @since 2.1.2
	 */
	protected $customerInformationSecond;

	/**
	 * @var array
	 * @since 2.1.2
	 */
	protected $checkoutAccountInformation;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public $group;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public $extensionURL;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public $pluginName;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public $pluginURL;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public $package;

	/**
	 * @var array
	 * @since 2.1.2
	 */
	public $cartSetting;

	public function __construct()
	{
		$this->faker            = Faker\Factory::create();
		$this->categoryName     = $this->faker->bothify('CategoryName ?###?');
		$this->productName      = $this->faker->bothify('Testing Product ??####?');
		$this->productNumber    = $this->faker->numberBetween(999, 9999);
		$this->productPrice     = 100;
		$this->minimumQuantity  = 1;
		$this->maximumQuantity  = $this->faker->numberBetween(11, 100);

		//configuration enable one page checkout
		$this->cartSetting = array(
			"addcart"           => 'product',
			"allowPreOrder"     => 'yes',
			"cartTimeOut"       => $this->faker->numberBetween(100, 10000),
			"enabldAjax"        => 'no',
			"defaultCart"       => null,
			"buttonCartLead"    => 'Back to current view',
			"onePage"           => 'yes',
			"showShippingCart"  => 'no',
			"attributeImage"    => 'no',
			"quantityChange"    => 'no',
			"quantityInCart"    => 0,
			"minimunOrder"      => 0,
			"enableQuation"     => 'no',
			"onePageNo"         => 'no',
			"onePageYes"        => 'yes'
		);

		$this->customerInformation = array(
			"email"             => $this->faker->email,
			"firstName"         => $this->faker->bothify('firstNameCustomer ?####?'),
			"lastName"          => $this->faker->bothify('lastNameCustomer ?####?'),
			"address"           => "Some Place in the World",
			"postalCode"        => "5000",
			"city"              => "Blangstedgaardsvej 1",
			"country"           => "Denmark",
			"state"             => "Odense SØ",
			"phone"             => "8787878787",
			"shopperGroup"      => 'Default Private',
		);

		$this->extensionURL     = 'extension url';
		$this->pluginName       = 'Bank Transfer Discount Payments';
		$this->pluginURL        = 'paid-extensions/tests/releases/plugins/';
		$this->package          = 'plg_redshop_payment_rs_payment_banktransfer_discount.zip';
	}

	/**
	 * @param AcceptanceTester $I
	 * @throws Exception
	 * @since    2.1.2
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param AdminManagerJoomla3Steps $I
	 * @throws Exception
	 */
	public function installPlugin(AdminManagerJoomla3Steps $I)
	{
		$I->wantTo("install plugin payment Bank Transfer Discount");
		$I->installExtensionPackageFromURL($this->extensionURL, $this->pluginURL, $this->package);
		$I->waitForText(AdminJ3Page:: $messageInstallPluginSuccess, 120, AdminJ3Page::$idInstallSuccess);
		$I->wantTo('Enable Plugin Bank Transfer Discount Payments in Administrator');
		$I->enablePlugin($this->pluginName);
	}

	/**
	 * @param ConfigurationSteps $I
	 * @param $scenario
	 * @throws Exception
	 */
	public function testBankTransferDiscountPaymentPlugin( ConfigurationSteps $I, $scenario)
	{
		$I->cartSetting($this->cartSetting["addcart"], $this->cartSetting["allowPreOrder"], $this->cartSetting["enableQuation"],$this->cartSetting["cartTimeOut"], $this->cartSetting["enabldAjax"], $this->cartSetting["defaultCart"],
			$this->cartSetting["buttonCartLead"], $this->cartSetting["onePageYes"], $this->cartSetting["showShippingCart"], $this->cartSetting["attributeImage"], $this->cartSetting["quantityChange"], $this->cartSetting["quantityInCart"], $this->cartSetting["minimunOrder"]);

		$I->wantTo('Create Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->categoryName);

		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductSaveClose($this->productName, $this->categoryName, $this->productNumber, $this->productPrice);

		$I->wantTo('checkout with Plugin Bank Transfer Discount Payments in Administrator');
		$I = new checkoutWithBankTransferDiscount($scenario);
		$I->wantTo('One Steps checkout with payment');
		$I->checkoutProductWithBankTransferDiscountPayment($this->productName, $this->categoryName,$this->customerInformation);
		$I = new ConfigurationSteps($scenario);
		$I->wantTo('Check Order');
		$I->checkPriceTotal($this->productPrice, $this->customerInformation["firstName"], $this->customerInformation["firstName"], $this->customerInformation["lastName"],
			$this->productName, $this->categoryName, $this->pluginName);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since    2.1.2
	 */
	public function clearAllData(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Deletion of Order in Administrator');
		$I = new OrderManagerJoomla3Steps($scenario);
		$I->deleteOrder( $this->customerInformation['firstName']);

		$I->wantTo('Delete product');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->productName);

		$I->wantTo('Delete Category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->categoryName);
	}
}