<?php
use AcceptanceTester\CategoryManagerJoomla3Steps as CategorySteps;
use AcceptanceTester\ProductManagerJoomla3Steps as ProductSteps;
use AcceptanceTester\ProductCheckoutManagerJoomla3Steps as ProductCheckoutSteps;
use Configuration\ConfigurationSteps;

class CompareProductsCest
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
	protected $ProductName;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $CategoryName;

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
	protected $productNumber;

	/**
	 * @var int
	 * @since 3.0.2
	 */
	protected $productPrice;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $subtotal;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $Discount;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $Total;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $productNameCompares;

	/**
	 * @var int
	 * @since 3.0.2
	 */
	protected $productNumberCompares;

	/**
	 * @var int
	 * @since 3.0.2
	 */
	protected $productPriceCompares;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $productPriceDKK;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $productPriceComparesDKK;

	public function __construct()
	{
		$this->faker = Faker\Factory::create();
		$this->ProductName = $this->faker->bothify('Testing Product ??####?');
		$this->CategoryName = $this->faker->bothify('CategoryName ?###?');;
		$this->minimumPerProduct = 1;
		$this->minimumQuantity = 1;
		$this->maximumQuantity = $this->faker->numberBetween(100, 1000);
		$this->discountStart = "12-12-2016";
		$this->discountEnd = "23-05-2017";
		$this->productNumber = $this->faker->numberBetween(999, 9999);
		$this->productPrice = 100;

		$this->subtotal="DKK 100,00";
		$this->Discount ="DKK 50,00";
		$this->Total="DKK 50,00";

		$this->productNameCompares = $this->faker->bothify('Product Compare ??####?');
		$this->productNumberCompares = $this->faker->numberBetween(999, 9999);
		$this->productPriceCompares = 80;

		$this->productPriceDKK = "DKK 100,00";
		$this->productPriceComparesDKK = "DKK 80,00";

	}


	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 */
	public function compareProducts(AcceptanceTester $I, $scenario)
	{
		$I->wantTo("Config Comparison");
		$I = new ConfigurationSteps($scenario);
		$I->featureComparisonYes();

		$I->wantTo('Create Category in Administrator');
		$I = new CategorySteps($scenario);
		$I->addCategorySaveClose($this->CategoryName);

		$I = new ProductSteps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductSaveClose($this->ProductName, $this->CategoryName, $this->productNumber, $this->productPrice);


		$I = new ProductSteps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductSaveClose($this->productNameCompares, $this->CategoryName, $this->productNumberCompares, $this->productPriceCompares);


		$I = new ProductCheckoutSteps($scenario);
		$I->wantTo('I Want to compare 2 products');
		$I->comparesProducts($this->CategoryName, $this->ProductName,$this->productNameCompares);
	}

	public function deleteDataEnd(ProductSteps $I, $scenario)
	{
		$I->wantTo('Delete Product in Administrator');
		$I->deleteProduct($this->productNameCompares);

		$I->wantTo('Delete Product Compares in Administrator');
		$I->deleteProduct($this->ProductName);

		$I = new CategorySteps($scenario);
		$I->wantTo('Delete Category in Administrator');
		$I->deleteCategory($this->CategoryName);
	}
}