<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\QuotationManagerJoomla3Steps;
use AcceptanceTester\UserManagerJoomla3Steps;

/**
 * Class QuotationCest
 * @since 1.4.0
 */
class QuotationCest
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
	protected $randomCategoryName;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $randomProductName;

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
	 * QuotationCest constructor.
	 * @since 1.4.0
	 */
	public function __construct()
	{
		$this->faker           = Faker\Factory::create();
		$this->userName        = $this->faker->bothify('ManageUserAdministratorCest ?##?');
		$this->password        = $this->faker->bothify('Password ?##?');
		$this->email           = $this->faker->email;
		$this->shopperGroup    = 'Default Private';
		$this->group           = 'Public';
		$this->firstName       = $this->faker->bothify('ManageUserAdministratorCest FN ?##?');
		$this->updateFirstName = 'Updating ' . $this->firstName;
		$this->lastName        = 'Last';

		$this->randomCategoryName  = $this->faker->bothify('category name ?##?');
		$this->randomProductName   = $this->faker->bothify('product name ?##?');
		$this->minimumPerProduct   = 2;
		$this->minimumQuantity     = 3;
		$this->maximumQuantity     = 5;
		$this->discountStart       = "2016-12-12";
		$this->discountEnd         = "2017-05-23";
		$this->randomProductNumber = rand(999, 9999);
		$this->randomProductPrice  = rand(99, 199);

		$this->quantity    = $this->faker->numberBetween(1, 100);
		$this->newQuantity = $this->faker->numberBetween(100, 300);
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
	public function checkAllCase(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Create Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->wantTo('Create a Category');
		$I->addCategorySave($this->randomCategoryName);

		$I->wantTo('Test Product Save Manager in Administrator');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductSave($this->randomProductName, $this->randomCategoryName, $this->randomProductNumber, $this->randomProductPrice, $this->minimumPerProduct, $this->minimumQuantity, $this->maximumQuantity, $this->discountStart, $this->discountEnd);

		$I->wantTo('Test User creation in Administrator');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperGroup, $this->firstName, $this->lastName, 'save');
		$I->searchUser($this->firstName);

		$I->wantTo('Test Quotation creation in Administrator');
		$I = new QuotationManagerJoomla3Steps($scenario);
		$I->addQuotation($this->userName, $this->randomProductName, $this->quantity);

		$I->wantTo('Test Quotation creation in Administrator');
		$I->editQuotation($this->newQuantity);

		$I->wantTo('Test Quotation creation in Administrator');
		$I->deleteQuotation();
	}
}
