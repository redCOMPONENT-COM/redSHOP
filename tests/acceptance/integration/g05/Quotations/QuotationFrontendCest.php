<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\CategoryManagerJoomla3Steps;
use Configuration\ConfigurationSteps;
use AcceptanceTester\ProductCheckoutManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\QuotationManagerJoomla3Steps;

/**
 * Class QuotationFrontendCest
 * @since 1.4.0
 */
class QuotationFrontendCest
{
	/**
	 * @var \Faker\Generator
	 * @since 1.4.0
	 */
	protected $faker;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $userName;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $password;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $email;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $shopperGroup;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $group;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $firstName;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $updateFirstName;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $lastName;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $categoryName;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $productName;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	protected $minimumPerProduct;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	protected $minimumQuantity;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	protected $maximumQuantity;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $discountStart;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $discountEnd;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	protected $randomProductNumber;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	protected $randomProductPrice;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	protected $quantity;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	protected $newQuantity;

	/**
	 * @var array
	 * @since
	 */
	protected $cartSetting;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $statusChange;

	/**
	 * QuotationFrontendCest constructor.
	 * @since 1.4.0
	 */
	public function __construct()
	{
		$this->faker               = Faker\Factory::create();
		$this->productName         = 'productName' . rand(100, 999);
		$this->categoryName        = "categoryName" . rand(1, 100);
		$this->minimumPerProduct   = 1;
		$this->minimumQuantity     = 1;
		$this->maximumQuantity     = $this->faker->numberBetween(100, 1000);
		$this->discountStart       = "2016-12-12";
		$this->discountEnd         = "2017-05-23";
		$this->randomProductNumber = $this->faker->numberBetween(999, 9999);
		$this->randomProductPrice  = 100;

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

		$this->newQuantity  = 4;
		$this->statusChange = 'Accepted';
	}

	/**
	 * @param AcceptanceTester $I
	 * @throws Exception
	 * @since 1.4.0
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 1.4.0
	 */
	public function createQuotation(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Create Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->categoryName);

		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductSave($this->productName, $this->categoryName, $this->randomProductNumber, $this->randomProductPrice, $this->minimumPerProduct, $this->minimumQuantity, $this->maximumQuantity, $this->discountStart, $this->discountEnd);

		$I->wantTo(' Enable Quotation at configuration ');
		$I = new ConfigurationSteps($scenario);
		$I->cartSetting($this->cartSetting);

		$I->wantTo('Create Quotation at frontend ');
		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->checkoutQuotation($this->productName, $this->categoryName, $this->email);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 1.4.0
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