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
			"edit"          => $this->faker->bothify('Edit Why is this Happening ??####??')
		);
	}

	/**
	 * @param AcceptanceTester $I
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param CategoryManagerJoomla3Steps $I
	 * @param $scenario
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function createData(CategoryManagerJoomla3Steps $I, $scenario)
	{
		//Create data
		$I->wantTo('Create category in administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->categoryName);

		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I want to add product inside the category');
		$I->createProductSaveClose($this->productName, $this->categoryName, $this->randomProductNumber, $this->randomProductPrice);

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

		// Delete data
		$I->wantTo('Delete product');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->productName);

		$I->wantTo('Delete Category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->categoryName);
	}
}
