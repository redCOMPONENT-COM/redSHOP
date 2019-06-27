<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps as ProductManagerSteps;

/**
 * Class ImageAndSEOCategoryProductCest
 * @since 2.1.2
 */
class ImageAndSEOCategoryProductCest
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
	public $noPage;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public $categoryName;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public $image;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public $titleSEO;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public $keySEO;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public $descriptionSEO;

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
	public $titleSEOPD;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public $headingSEO;

	/**
	 * ImageAndSEOCategoryProductCest constructor.
	 * @since 2.1.2
	 */
	public function __construct()
	{
		$this->faker = Faker\Factory::create();
		$this->categoryName = $this->faker->bothify('CategoryName ?####?');
		$this->noPage = 5;
		$this->image = 'hinhanh.jpg';
		$this->titleSEO = "SEO title ".$this->categoryName;
		$this->keySEO = "SEO key ".$this->categoryName;
		$this->descriptionSEO = "SEO description ".$this->categoryName;
		$this->productName = $this->faker->bothify('ProductName ?####?');
		$this->productNumber = rand(100,1000);
		$this->productPrice = rand(100,1000);
		$this->titleSEOPD = "SEO title ".$this->productName;
		$this->headingSEO = "SEO heading ".$this->productName;

	}

	/**
	 * @param CategoryManagerJoomla3Steps $I
	 * @param $scenario
	 * @throws Exception
	 */
	public function createCategoryHaveImage(CategoryManagerJoomla3Steps $I, $scenario)
	{
		$I->doAdministratorLogin();

		$I->comment('create category have image and SEO');
		$I = new CategorySteps($scenario);
		$I->createCategoryImageAndSEO( $this->categoryName,$this->noPage,$this->image, $this->titleSEO, $this->keySEO, $this->descriptionSEO);

		$I->comment('create product have image and SEO');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->createProductHaveImageAndSEO($this->productName,$this->categoryName, $this->productNumber,$this->productPrice,$this->titleSEOPD,$this->headingSEO,$this->image);

		$I->wantTo('Delete product');
		$I->deleteProduct($this->productName);

		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->wantTo('Delete Category');
		$I->deleteCategory($this->categoryName);
	}
}