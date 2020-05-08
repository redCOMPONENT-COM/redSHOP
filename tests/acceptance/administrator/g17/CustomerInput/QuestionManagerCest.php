<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\QuestionManagerJoomla3Steps;
use AcceptanceTester\UserManagerJoomla3Steps;

/**
 * Class QuestionManagerCest
 * @since 3.0.2
 */
class QuestionManagerCest
{
	/**
	 * @var \Faker\Generator
	 * @since 3.0.2
	 */
	protected $faker;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $productName;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $categoryName;

	/**
	 * @var int
	 * @since 3.0.2
	 */
	protected $minimumPerProduct;

	/**
	 * @var int
	 * @since 3.0.2
	 */
	protected $minimumQuantity;

	/**
	 * @var int
	 * @since 3.0.2
	 */
	protected $maximumQuantity;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $discountStart;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $discountEnd;

	/**
	 * @var int
	 * @since 3.0.2
	 */
	protected $randomProductNumber;

	/**
	 * @var int
	 * @since 3.0.2
	 */
	protected $randomProductPrice;

	/**
	 * @var array
	 * @since 3.0.2
	 */
	protected $questionInformation ;

    /**
     * @var array
     * @since 3.0.2
     */
	protected $user;

	/**
	 * QuestionManagerCest constructor.
	 * @since 3.0.2
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

		$this->questionInformation = array(
			"address"       => $this->faker->address,
			"phone"         => $this->faker->phoneNumber,
			"question"      => $this->faker->bothify('Why is this Happening ??####??'),
			"edit"          => $this->faker->bothify('Edit Why is this Happening ??####??'),
            "userName"     => $this->faker->bothify('User name ?####?'),
			"email"        => $this->faker->email,
			"question1"    => $this->faker->bothify('I not login account and comment question product ?####?'),
			"question2"    => $this->faker->bothify('I login account and comment question product ??##??')
		);

        $this->user        = array (
            "userName"     => $this->faker->bothify('User name ?####?'),
            "password"     => $this->faker->bothify('Password ?##?'),
            "email"        => $this->faker->email,
            "group"        => 'Registered',
            "shopperGroup" => 'Default Private',
            "firstName"    => $this->faker->bothify('First name ?##?'),
            "lastName"     => $this->faker->bothify('LastName ?####?'),
            "address"      => $this->faker->address,
            "zipcode"      => $this->faker->postcode,
            "city"         => 'Ho Chi Minh',
            "phone"        => $this->faker->phoneNumber,
            "country"      => 'Viet Nam'
        );
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function createData(AcceptanceTester $I, $scenario)
	{
	    //Login Administrator Page
        $I->doAdministratorLogin();

		//Create data
		$I->wantTo('Create category in administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->categoryName);

		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I want to add product inside the category');
		$I->createProductSaveClose($this->productName, $this->categoryName, $this->randomProductNumber, $this->randomProductPrice);

        $I = new UserManagerJoomla3Steps($scenario);
        $I->wantTo('Create user in Administrator page');
        $I->addUser($this->user['userName'], $this->user['password'], $this->user['email'], $this->user['group'], $this->user['shopperGroup'], $this->user['firstName'], $this->user['lastName'], 'save');

		//Working with CRUD
		$I->wantTo('Create question in backend');
		$I = new QuestionManagerJoomla3Steps($scenario);
		$I->addQuestion($this->productName, $this->questionInformation);

		$I->wantTo('Edit question in backend');
		$I->editQuestion($this->questionInformation);

		$I->wantTo('Change status for question item');
		$I->changeQuestionState($this->questionInformation);

		$I->wantTo('Delete question');
		$I->deleteQuestion($this->questionInformation);
	}

    /**
     * @param AcceptanceTester $I
     * @param $scenario
     * @throws Exception
     * @since 3.0.2
     */
    public function addQuestionInProductDetail(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Create Question in Frontend page with Missing login account');
        $I = new QuestionManagerJoomla3Steps($scenario);
        $I->addQuestionOnProductDetailOnFrontendMissingLogin($this->productName, $this->categoryName, $this->questionInformation);

        $I->wantTo('Create Question in Frontend page with login account');
        $I = new QuestionManagerJoomla3Steps($scenario);
        $I->addQuestionOnProductDetailOnFrontendLogin($this->productName, $this->categoryName, $this->questionInformation, $this->user);
    }

    /**
     * @param AcceptanceTester $I
     * @param $scenario
     * @throws Exception
     * @since 3.0.2
     */
    public function checkQuestionInBackend(AcceptanceTester $I, $scenario)
    {
        $I->doAdministratorLogin();
        $I->wantTo('Check Question in Backend page');
        $I = new QuestionManagerJoomla3Steps($scenario);
        $I->checkQuestionInAdministrator($this->productName, $this->questionInformation);

        //Delete data

        $I->wantTo('Delete Question item');
        $I = new QuestionManagerJoomla3Steps($scenario);
        $I->deleteAll();

        $I->wantTo('Delete product');
        $I = new ProductManagerJoomla3Steps($scenario);
        $I->deleteProduct($this->productName);

        $I->wantTo('Delete Category');
        $I = new CategoryManagerJoomla3Steps($scenario);
        $I->deleteAllCategory();

        $I->wantTo('Delete User');
        $I = new UserManagerJoomla3Steps($scenario);
        $I->deleteUser($this->user['firstName'],  false);
    }
}
