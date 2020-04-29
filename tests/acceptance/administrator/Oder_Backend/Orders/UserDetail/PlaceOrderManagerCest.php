<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\OrderManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\UserManagerJoomla3Steps;

/**
 * Class PlaceOrderManagerCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    2.1.2
 */
class PlaceOrderManagerCest
{
    /**
     * @var \Faker\Generator
     * @since 2.1.2
     */
    protected $faker;

    /**
     * @var string
     * @since 2.1.2
     */
    protected $userName;

    /**
     * @var string
     * @since 2.1.2
     */
    protected $password;

    /**
     * @var string
     * @since 2.1.2
     */
    protected $email;

    /**
     * @var string
     * @since 2.1.2
     */
    protected $emailMissingUser;

    /**
     * @var string
     * @since 2.1.2
     */
    protected $emailsave;

    /**
     * @var string
     * @since 2.1.2
     */
    protected $shopperGroup;

    /**
     * @var string
     * @since 2.1.2
     */
    protected $group;

    /**
     * @var string
     * @since 2.1.2
     */
    protected $firstName;

    /**
     * @var string
     * @since 2.1.2
     */
    protected $updateFirstName;

    /**
     * @var string
     * @since 2.1.2
     */
    protected $lastName;

    /**
     * @var string
     * @since 2.1.2
     */
    protected $categoryName;

    /**
     * @var string
     * @since 2.1.2
     */
    protected $productName;

    /**
     * @var int
     * @since 2.1.2
     */
    protected $randomProductNumber;

    /**
     * @var int
     * @since 2.1.2
     */
    protected $randomProductPrice;

    /**
     * @var int
     * @since 2.1.2
     */
    protected $quantity;

    /**
     * PlaceOrderManagerCest constructor
     * @since 2.1.2
     */
	public function __construct()
	{
		$this->faker                        = Faker\Factory::create();
		$this->userName                     = $this->faker->bothify('ManageUserAdministratorCest ?##?');
		$this->password                     = $this->faker->bothify('Password ?##?');
		$this->email                        = $this->faker->email;
		$this->emailMissingUser             = $this->faker->email;
		$this->emailsave                    = $this->faker->email;
		$this->shopperGroup                 = 'Default Private';
		$this->group                        = 'Public';
		$this->firstName                    = $this->faker->bothify('ManageUserAdministratorCest FN ?##?');
		$this->updateFirstName              = 'Updating ' . $this->firstName;
		$this->lastName                     = 'Last';

		$this->categoryName                 = $this->faker->bothify('CategoryTesting ??####?');
		$this->productName                  = $this->faker->bothify('TestingProductManagement ??####?');
		$this->randomProductNumber          = $this->faker->numberBetween(999, 9999);
		$this->randomProductPrice           = rand(9, 19);
		$this->quantity                     = 1;
	}

	/**
	 * @param AcceptanceTester $I
     * @throws \Exception
     * @since 2.1.2
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
     * @since 2.1.2
	 */
	public function createProduct(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Create Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->categoryName);

		$I->wantTo('I Want to add product inside the category');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->createProductSaveClose($this->productName, $this->categoryName, $this->randomProductNumber, $this->randomProductPrice);
	}

	/**
	 *
	 * Function add user with save and save &c lose button
	 *
	 * @param AcceptanceTester $I
	 * @param $scenario
     * @throws \Exception
     * @since 2.1.2
	 */
	public function createOder(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test User creation with save button in Administrator');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperGroup, $this->firstName, $this->lastName, 'saveclose');

		$I->wantTo('Create Oder');
		$I->checkPlaceOder($this->firstName,$this->productName,$this->quantity);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 */
	public function clearAllData(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Delete Oder');
		$I = new OrderManagerJoomla3Steps($scenario);
		$I->wantTo('Test Order delete by user  in Administrator');
		$I->deleteOrder($this->firstName);

		$I->wantTo('Deletion Product in Administrator');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->productName);

		$I->wantTo('Deletion Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->categoryName);

		$I->wantTo('Deletion of User in Administrator');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->firstName, 'true');
	}
}