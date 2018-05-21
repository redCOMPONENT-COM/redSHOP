<?php

/**
 * Class check page Mass Discount. This page missing create mass discount have start date before end date
 *
 * @since 1.6.0
 */
class MassDiscountCest
{
	/**
	 * @var \Faker\Generator
	 */
	public $faker;

	/**
	 * @var string
	 */
	public $productName;

	/**
	 * @var string
	 */
	public $massDiscountName;

	/**
	 * @var string
	 */
	public $massDiscountNameSave;

	/**
	 * @var string
	 */
	public $massDiscountNameEdit;

	/**
	 * @var string
	 */
	public $categoryName;

	/**
	 * @var string
	 */
	public $manufacturerName;

	/**
	 * @var integer
	 */
	public $massDiscountAmountInput;

	/**
	 * @var float
	 */
	public $massDiscountPercentage;

	/**
	 * @var integer
	 */
	public $minimumPerProduct;

	/**
	 * @var integer
	 */
	public $minimumQuantity;

	/**
	 * @var integer
	 */
	public $maximumQuantity;

	/**
	 * @var string
	 */
	public $discountStart;

	/**
	 * @var string
	 */
	public $discountEnd;

	/**
	 * @var string
	 */
	public $randomProductNumber;

	/**
	 * @var string
	 */
	public $randomProductNumberNew;

	/**
	 * @var string
	 */
	public $randomProductAttributeNumber;

	/**
	 * @var string
	 */
	public $randomProductNameAttribute;

	/**
	 * @var string
	 */
	public $randomProductPrice;

	/**
	 * @var string
	 */
	public $discountPriceThanPrice;

	/**
	 * @var integer
	 */
	public $statusProducts;

	/**
	 * @var string
	 */
	public $searchCategory;

	/**
	 * ManagerMassDiscountAdministratorCest constructor.
	 */
	public function __construct()
	{
		$this->faker                        = Faker\Factory::create();
		$this->productName                  = 'ProductName' . rand(100, 999);
		$this->massDiscountName             = 'MassDiscount' . rand(10, 100);
		$this->massDiscountNameSave         = 'MassDiscountSave' . rand(10, 1000);
		$this->massDiscountNameEdit         = 'Edit' . $this->massDiscountName;
		$this->categoryName                 = "CategoryName" . rand(1, 100);
		$this->manufacturerName             = "ManufactureName" . rand(1, 10);
		$this->massDiscountAmountInput      = $this->faker->numberBetween(10, 100);
		$this->massDiscountPercentage       = 0.3;
		$this->minimumPerProduct            = $this->faker->numberBetween(1, 3);
		$this->minimumQuantity              = $this->faker->numberBetween(10, 100);
		$this->maximumQuantity              = $this->faker->numberBetween(100, 1000);
		$this->discountStart                = "2016-12-12";
		$this->discountEnd                  = "2017-05-23";
		$this->randomProductNumber          = $this->faker->numberBetween(999, 9999);
		$this->randomProductNumberNew       = $this->faker->numberBetween(999, 9999);
		$this->randomProductAttributeNumber = $this->faker->numberBetween(999, 9999);
		$this->randomProductNameAttribute   = 'Testing Attribute' . rand(99, 999);
		$this->randomProductPrice           = $this->faker->numberBetween(99, 199);
		$this->discountPriceThanPrice       = $this->faker->numberBetween(10, 100);
		$this->statusProducts               = 'Product on sale';
		$this->searchCategory               = 'Category';
		$this->newProductName               = 'New-Test Product' . rand(99, 999);
		$this->nameAttribute                = 'Size';
		$this->valueAttribute               = "Z";
		$this->priceAttribute               = $this->faker->numberBetween(1, 30);
		$this->nameProductAccessories       = "redFORM";
		$this->nameRelatedProduct           = "redITEM";
		$this->quantityStock                = $this->faker->numberBetween(10, 100);
		$this->PreorderStock                = $this->faker->numberBetween(10, 100);
		$this->priceProductForThan          = $this->faker->numberBetween(10, 100);
	}

	/**
	 * Function to create new Category
	 *
	 * @param   AcceptanceTester $tester   client
	 * @param   mixed            $scenario Scenario
	 *
	 * @return  void
	 */
	public function createCategory(AcceptanceTester $tester, $scenario)
	{
		$tester->wantTo('Create Category in Administrator');
		$tester->doAdministratorLogin();
		$tester = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
		$tester->addCategorySave($this->categoryName);
	}

	/**
	 * Function to create Product inside the category
	 *
	 * @param   AcceptanceTester $tester   client
	 * @param   mixed            $scenario Scenario
	 *
	 * @return void
	 *
	 * @depends createCategory
	 */
	public function createProductSave(AcceptanceTester $tester, $scenario)
	{
		$tester->doAdministratorLogin();
		$tester = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
		$tester->wantTo('I Want to add product inside the category');
		$tester->createProductSave(
			$this->productName,
			$this->categoryName,
			$this->randomProductNumber,
			$this->randomProductPrice,
			$this->minimumPerProduct,
			$this->minimumQuantity,
			$this->maximumQuantity,
			$this->discountStart,
			$this->discountEnd
		);
	}

    /**
     * @param AcceptanceTester $I
     */
    public function _before(AcceptanceTester $I)
    {
        $I->doAdministratorLogin();
    }

	/**
	 * Function to mass Discount for Product inside the category
	 *
	 * @param   AcceptanceTester $tester   client
	 * @param   mixed            $scenario Scenario
	 *
	 * @return void
	 *
	 * @depends createProductSave
	 */
	public function addMassDiscount(AcceptanceTester $tester, $scenario)
	{
		$tester = new AcceptanceTester\MassDiscountManagerJoomla3Steps($scenario);
		$tester->wantTo('Test check add Mass discount ');
		$tester->addMassDiscount(
			$this->massDiscountName, $this->massDiscountAmountInput, $this->discountStart, $this->discountEnd, $this->categoryName, $this->productName
		);
	}

	/**
	 * Function to mass Discount save & close
	 *
	 * @param   AcceptanceTester $tester   client
	 * @param   mixed            $scenario Scenario
	 *
	 * @return void
	 *
	 * @depends createProductSave
	 */
	public function addMassDiscountSaveClose(AcceptanceTester $tester, $scenario)
	{
		$tester->wantTo(' add Mass discount with Save and Close in Administrator');
		$tester = new AcceptanceTester\MassDiscountManagerJoomla3Steps($scenario);
		$tester->addMassDiscountSaveClose(
			$this->massDiscountNameSave, $this->massDiscountAmountInput, $this->discountStart, $this->discountEnd,
			$this->categoryName, $this->productName
		);

	}

//    public function addMassDiscountStartThanEnd(AcceptanceTester $I, $scenario)
//    {
//        $I->wantTo(' add Mass discount Start Than End  in Administrator');
//        $I->doAdministratorLogin();
//        $I = new AcceptanceTester\MassDiscountManagerJoomla3Steps($scenario);
//        $I->wantTo('Test check add Mass discount ');
//        $I->addMassDiscountSaveClose($this->MassDiscountName, $this->MassDiscountAmoutTotal, $this->discountStart, $this->discountEnd, $this->CategoryName, $this->ProductName);
//    }

	public function addMassDiscountMissingAllFields(AcceptanceTester $I, $scenario)
	{
		$I->wantTo(' add Mass discount missing all fields in Administrator');
		$I = new AcceptanceTester\MassDiscountManagerJoomla3Steps($scenario);
		$I->wantTo('Test check add Mass discount ');
		$I->addMassDiscountMissingAllFields();
	}


	public function checkCancelButton(AcceptanceTester $I, $scenario)
	{
		$I->wantTo(' add Mass discount check cancel button at new form in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\MassDiscountManagerJoomla3Steps($scenario);
		$I->wantTo('Test check add Mass discount ');
		$I->checkCancelButton();
	}

	public function addMassDiscountMissingName(AcceptanceTester $I, $scenario)
	{
		$I->wantTo(' add Mass discount missing name in Administrator');
		$I = new AcceptanceTester\MassDiscountManagerJoomla3Steps($scenario);
		$I->wantTo('Test check add Mass discount ');
		$I->addMassDiscountMissingName($this->massDiscountAmountInput, $this->discountStart, $this->discountEnd, $this->categoryName, $this->productName);

	}

	public function addMassDiscountMissingAmount(AcceptanceTester $I, $scenario)
	{
		$I->wantTo(' add Mass discount missing amount in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\MassDiscountManagerJoomla3Steps($scenario);
		$I->wantTo('Test check add Mass discount ');
		$I->addMassDiscountMissingAmount($this->massDiscountName, $this->discountStart, $this->discountEnd, $this->categoryName, $this->productName);

	}

	public function addMassDiscountMissingProducts(AcceptanceTester $I, $scenario)
	{
		$I->wantTo(' add Mass discount missing product in Administrator');
		$I = new AcceptanceTester\MassDiscountManagerJoomla3Steps($scenario);
		$I->wantTo('Test check add Mass discount ');
		$I->addMassDiscountMissingProducts($this->massDiscountName, $this->massDiscountAmountInput, $this->discountStart, $this->discountEnd);
	}

	public function editMassDiscount(AcceptanceTester $I, $scenario)
	{
		$I->wantTo(' Edit Name Mass discount with save in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\MassDiscountManagerJoomla3Steps($scenario);
		$I->wantTo('Test check add Mass discount ');
		$I->editMassDiscount($this->massDiscountName, $this->massDiscountNameEdit);
	}

	public function editButtonMassDiscountSave(AcceptanceTester $I, $scenario)
	{
		$I->wantTo(' edit  Mass discount with save and close in Administrator');
		$I = new AcceptanceTester\MassDiscountManagerJoomla3Steps($scenario);
		$I->wantTo('Test check add Mass discount ');
		$I->editButtonMassDiscountSave($this->massDiscountNameEdit, $this->massDiscountName);
	}

	public function checkCloseButton(AcceptanceTester $I, $scenario)
	{
		$I->wantTo(' check button close button Mass discount in Administrator');
		$I = new AcceptanceTester\MassDiscountManagerJoomla3Steps($scenario);
		$I->wantTo('Test check add Mass discount ');
		$I->checkCloseButton($this->massDiscountName);
	}

	public function deleteMassDiscountCancel(AcceptanceTester $I, $scenario)
	{
		$I->wantTo(' delete Mass discount in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\MassDiscountManagerJoomla3Steps($scenario);
		$I->wantTo('Test check add Mass discount ');
		$I->deleteMassDiscountCancel($this->massDiscountName);
	}

	public function deleteMassDiscountOK(AcceptanceTester $I, $scenario)
	{
		$I->wantTo(' delete mass discount Mass discount in Administrator');
		$I = new AcceptanceTester\MassDiscountManagerJoomla3Steps($scenario);
		$I->wantTo('Test check delete Mass discount ');
		$I->deleteMassDiscountOK($this->massDiscountName);
	}

	public function addMassDiscountForDeleteAll(AcceptanceTester $I, $scenario)
	{
		$I->wantTo(' add Mass discount with Save and Close in Administrator');
		$I = new AcceptanceTester\MassDiscountManagerJoomla3Steps($scenario);
		$I->wantTo('Test check add Mass discount ');
		$I->addMassDiscountSaveClose($this->massDiscountName, $this->massDiscountAmountInput, $this->discountStart, $this->discountEnd, $this->categoryName, $this->productName);

	}

	public function deleteAllMassDiscountOK(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('delete all mass discounts in Administrator');
		$I = new AcceptanceTester\MassDiscountManagerJoomla3Steps($scenario);
		$I->wantTo('Test check add Mass discount ');
		$I->deleteAllMassDiscountOK($this->massDiscountName);

	}

}